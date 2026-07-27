<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_anggota_without_nip(): void
    {
        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa Baru',
            'email' => 'siswabaru@example.com',
            'password' => 'password123',
            'hp' => '081234567890',
            'role' => User::ROLE_ANGGOTA,
        ]);

        $response->assertRedirect(route('anggota.beranda'));
        $this->assertDatabaseHas('user', [
            'email' => 'siswabaru@example.com',
            'role' => User::ROLE_ANGGOTA,
            'nip' => null,
            'nisn' => null,
        ]);
    }

    public function test_user_can_register_as_guru_without_nip(): void
    {
        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Guru Tanpa NIP',
            'email' => 'gurutanpanip@example.com',
            'password' => 'password123',
            'hp' => '081234567891',
            'role' => User::ROLE_GURU,
        ]);

        $response->assertRedirect(route('guru.beranda'));
        $this->assertDatabaseHas('user', [
            'email' => 'gurutanpanip@example.com',
            'role' => User::ROLE_GURU,
        ]);
    }

    public function test_user_can_register_with_valid_nip_auto_assigns_guru_role(): void
    {
        $validNip = '198704122010011001';

        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Guru BerNIP',
            'email' => 'gurubernip@example.com',
            'has_nip' => '1',
            'nip' => $validNip,
            'password' => 'password123',
            'hp' => '081234567892',
        ]);

        $response->assertRedirect(route('guru.beranda'));
        $this->assertDatabaseHas('user', [
            'email' => 'gurubernip@example.com',
            'nip' => $validNip,
            'role' => User::ROLE_GURU,
        ]);
    }

    public function test_user_can_register_with_valid_nisn_auto_assigns_anggota_role(): void
    {
        $validNisn = '0012345678';

        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa BerNISN',
            'email' => 'siswabernisn@example.com',
            'has_nip' => '1',
            'nip' => $validNisn,
            'password' => 'password123',
            'hp' => '081234567893',
        ]);

        $response->assertRedirect(route('anggota.beranda'));
        $this->assertDatabaseHas('user', [
            'email' => 'siswabernisn@example.com',
            'nisn' => $validNisn,
            'role' => User::ROLE_ANGGOTA,
        ]);
    }

    public function test_registration_fails_when_has_nip_is_checked_but_input_is_empty(): void
    {
        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa Kosong NIP',
            'email' => 'siswakosong@example.com',
            'has_nip' => '1',
            'nip' => '',
            'password' => 'password123',
            'hp' => '081234567894',
        ]);

        $response->assertSessionHasErrors('nip');
    }

    public function test_registration_fails_with_invalid_nip_format(): void
    {
        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa NIP Salah',
            'email' => 'salahnip@example.com',
            'has_nip' => '1',
            'nip' => '12345',
            'password' => 'password123',
            'hp' => '081234567895',
        ]);

        $response->assertSessionHasErrors('nip');
    }

    public function test_registration_prevents_reserved_admin_email(): void
    {
        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Fake Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password123',
            'hp' => '081234567896',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
