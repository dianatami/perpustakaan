<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessAndLoginTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(int $role, string $email, bool $active = true): User
    {
        return User::create([
            'nama' => 'User ' . $role,
            'email' => $email,
            'password' => Hash::make('secret123'),
            'hp' => '081234567890',
            'status' => $active,
            'role' => $role,
        ]);
    }

    public function test_login_redirects_to_dashboard_based_on_role(): void
    {
        $roleRoutes = [
            User::ROLE_ADMIN => 'admin.beranda',
            User::ROLE_ANGGOTA => 'anggota.beranda',
            User::ROLE_GURU => 'guru.beranda',
        ];

        foreach ($roleRoutes as $role => $dashboardRoute) {
            $email = 'user' . $role . '@example.com';
            $user = $this->makeUser($role, $email);

            $response = $this->post(route('tampilan.login.process'), [
                'email' => $email,
                'password' => 'secret123',
            ]);

            $response->assertRedirect(route($dashboardRoute));
            $this->assertAuthenticatedAs($user);

            $this->post(route('tampilan.logout'))->assertRedirect(route('tampilan.login'));
            $this->assertGuest();
        }
    }

    public function test_role_middleware_allows_only_owned_dashboard(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN, 'admin-test@example.com');
        $anggota = $this->makeUser(User::ROLE_ANGGOTA, 'anggota-test@example.com');
        $guru = $this->makeUser(User::ROLE_GURU, 'guru-test@example.com');

        $this->actingAs($admin)->get(route('admin.beranda'))->assertOk();
        $this->actingAs($admin)->get(route('guru.beranda'))->assertForbidden();

        $this->actingAs($anggota)->get(route('anggota.beranda'))->assertOk();
        $this->actingAs($anggota)->get(route('admin.beranda'))->assertForbidden();

        $this->actingAs($guru)->get(route('guru.beranda'))->assertOk();
        $this->actingAs($guru)->get(route('admin.beranda'))->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_protected_route(): void
    {
        $this->get(route('admin.beranda'))->assertRedirect(route('tampilan.login'));
    }
}
