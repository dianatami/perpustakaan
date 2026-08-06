<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessAndLoginTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(int $role, string $email, bool $active = true, ?string $identifier = null): User
    {
        $nip = null;
        $nisn = null;

        if ($role === User::ROLE_GURU) {
            $nip = $identifier ?? '198704122010011001';
        } elseif ($role === User::ROLE_ANGGOTA) {
            $nisn = $identifier ?? '123456789';
        }

        return User::create([
            'nama' => 'User ' . $role,
            'email' => $email,
            'nip' => $nip,
            'nisn' => $nisn,
            'password' => Hash::make('secret123'),
            'hp' => '081234567890',
            'status' => $active,
            'role' => $role,
            'kelas' => $role === User::ROLE_ANGGOTA ? 'X TJKT 1' : null,
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
                'identifier' => $email,
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

    public function test_guru_can_login_with_nip(): void
    {
        $guru = $this->makeUser(User::ROLE_GURU, 'guru-nip@example.com');

        $response = $this->post(route('tampilan.login.process'), [
            'identifier' => '198704122010011001',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('guru.beranda'));
        $this->assertAuthenticatedAs($guru);
    }

    public function test_siswa_can_login_with_nis(): void
    {
        $siswa = $this->makeUser(User::ROLE_ANGGOTA, 'siswa-nis@example.com', true, '123456789');

        $response = $this->post(route('tampilan.login.process'), [
            'identifier' => '123456789',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('anggota.beranda'));
        $this->assertAuthenticatedAs($siswa);
    }

    public function test_invalid_nip_is_rejected_on_login(): void
    {
        $this->post(route('tampilan.login.process'), [
            'identifier' => '1234567',
            'password' => 'secret123',
        ])->assertSessionHas('error', 'Format NIP/NISN/Email tidak valid.');

        $this->assertGuest();
    }

    public function test_invalid_nisn_length_is_rejected_on_login(): void
    {
        $this->post(route('tampilan.login.process'), [
            'identifier' => '1234567890',
            'password' => 'secret123',
        ])->assertSessionHas('error', 'Format NIP/NISN/Email tidak valid.');

        $this->assertGuest();
    }

    public function test_invalid_nip_month_is_rejected_on_login(): void
    {
        $this->post(route('tampilan.login.process'), [
            'identifier' => '198713122010011001',
            'password' => 'secret123',
        ])->assertSessionHas('error', 'Format NIP/NISN/Email tidak valid.');

        $this->assertGuest();
    }

    public function test_invalid_birthdate_month_is_rejected_on_profile_update(): void
    {
        $user = $this->makeUser(User::ROLE_ANGGOTA, 'anggota-birthdate@example.com');

        $response = $this->actingAs($user)->put(route('anggota.update.infopribadi'), [
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2026-13-01',
            'alamat' => 'Jl. Contoh No. 1',
        ]);

        $response->assertSessionHasErrors('tanggal_lahir');
        $this->assertDatabaseMissing('user', [
            'id' => $user->id,
            'tanggal_lahir' => '2026-13-01',
        ]);
    }
}
