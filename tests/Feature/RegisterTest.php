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
        ]);

        $response->assertRedirect(route('anggota.beranda'));
        $this->assertDatabaseHas('user', [
            'email' => 'siswabaru@example.com',
            'role' => User::ROLE_ANGGOTA,
            'nip' => null,
            'nisn' => null,
        ]);
    }

    public function test_registration_without_nip_always_defaults_to_anggota_role(): void
    {
        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Pendaftar Biasa',
            'email' => 'pendaftar@example.com',
            'password' => 'password123',
            'hp' => '081234567891',
            'role' => User::ROLE_GURU, // Attempting to forge guru role without NIP
        ]);

        $response->assertRedirect(route('anggota.beranda'));
        $this->assertDatabaseHas('user', [
            'email' => 'pendaftar@example.com',
            'role' => User::ROLE_ANGGOTA,
        ]);
    }

    public function test_user_can_register_with_valid_nip_auto_assigns_guru_role(): void
    {
        $validNip = '198704122010011001';

        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Guru BerNIP',
            'email' => 'gurubernip@example.com',
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
        $validNisn = '001234567';

        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa BerNISN',
            'email' => 'siswabernisn@example.com',
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

    public function test_registration_fails_with_invalid_nisn_length(): void
    {
        // Test with 10 digits (too long)
        $response1 = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa NISN 10 Digit',
            'email' => 'nisn10digit@example.com',
            'nip' => '1234567890',
            'password' => 'password123',
            'hp' => '081234567894',
        ]);
        $response1->assertSessionHasErrors('nip');

        // Test with 8 digits (too short)
        $response2 = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa NISN 8 Digit',
            'email' => 'nisn8digit@example.com',
            'nip' => '12345678',
            'password' => 'password123',
            'hp' => '081234567895',
        ]);
        $response2->assertSessionHasErrors('nip');
    }

    public function test_registration_fails_with_invalid_nip_format(): void
    {
        $response = $this->post(route('tampilan.register.process'), [
            'nama' => 'Siswa NIP Salah',
            'email' => 'salahnip@example.com',
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
