<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AnggotaStoreTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'nama' => 'Admin Test',
            'email' => 'admin@smkn1tirtamulya.sch.id',
            'password' => Hash::make('password'),
            'hp' => '081234567890',
            'status' => 1,
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_admin_can_create_siswa_with_kelas(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post(route('admin.anggota.store'), [
            'nip' => '1234567890',
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => '0',
            'kelas' => 'X TJKT 1',
            'hp' => '081234567891',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.anggota.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user', [
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'nisn' => '1234567890',
            'role' => User::ROLE_ANGGOTA,
            'kelas' => 'X TJKT 1',
        ]);
    }

    public function test_admin_can_create_guru_without_kelas(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post(route('admin.anggota.store'), [
            'nip' => '198704122010011001',
            'nama' => 'Drs. Ahmad',
            'email' => 'ahmad@example.com',
            'role' => '2',
            'kelas' => '',
            'hp' => '081234567892',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.anggota.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user', [
            'nama' => 'Drs. Ahmad',
            'email' => 'ahmad@example.com',
            'nip' => '198704122010011001',
            'role' => User::ROLE_GURU,
            'kelas' => null,
        ]);
    }
}
