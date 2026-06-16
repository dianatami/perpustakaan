<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Bookrent;
use App\Models\Kategori;
use App\Models\DetailBookrent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookReturnFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(int $role, string $email, ?string $identifier = null): User
    {
        $nip = null;
        $nisn = null;

        if ($role === User::ROLE_GURU) {
            $nip = $identifier ?? '198704122010011001';
        } elseif ($role === User::ROLE_ANGGOTA) {
            $nisn = $identifier ?? '1234567890';
        }

        return User::create([
            'nama' => 'User ' . $role,
            'email' => $email,
            'nip' => $nip,
            'nisn' => $nisn,
            'password' => Hash::make('secret123'),
            'hp' => '081234567890',
            'status' => 1,
            'role' => $role,
        ]);
    }

    private function makeBook(int $stock = 0): Book
    {
        $category = Kategori::create([
            'name_category' => 'Pengujian',
        ]);

        return Book::create([
            'category_id' => $category->id,
            'book_code' => 'BK-' . uniqid(),
            'title' => 'Buku Uji',
            'author' => 'Tester',
            'publisher' => 'Penerbit Uji',
            'year' => '2026',
            'description' => 'Buku untuk test',
            'stock' => $stock,
            'damaged' => 0,
            'lost' => 0,
        ]);
    }

    public function test_anggota_can_return_own_book(): void
    {
        $anggota = $this->makeUser(User::ROLE_ANGGOTA, 'anggota-return@example.com', '1234567890');
        $book = $this->makeBook(0);

        $borrow = Bookrent::create([
            'user_id' => $anggota->id,
            'borrow_date' => now()->subDays(3)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);

        DetailBookrent::create([
            'bookrent_id' => $borrow->id,
            'book_id' => $book->id,
            'qty' => 1,
            'condition' => 'baik',
        ]);

        $response = $this->actingAs($anggota)->post(route('anggota.pengembalian.store', $borrow->id));

        $response->dumpSession();
        $response->assertSessionHas('success');
        $response->assertStatus(302);

        $this->assertDatabaseHas('bookrent', [
            'id' => $borrow->id,
            'status' => 'proses_kembali',
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 0,
        ]);
    }

    public function test_guru_can_return_own_book(): void
    {
        $guru = $this->makeUser(User::ROLE_GURU, 'guru-return@example.com', '198704122010011001');
        $admin = $this->makeUser(User::ROLE_ADMIN, 'admin-confirm@example.com');
        $book = $this->makeBook(2);

        $borrow = Bookrent::create([
            'user_id' => $guru->id,
            'borrow_date' => now()->subDays(9)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);

        DetailBookrent::create([
            'bookrent_id' => $borrow->id,
            'book_id' => $book->id,
            'qty' => 1,
            'condition' => 'baik',
        ]);

        $response = $this->actingAs($guru)->post(route('guru.pengembalian.store', $borrow->id));

        $response->assertSessionHas('success');
        $response->assertStatus(302);

        $this->assertDatabaseHas('bookrent', [
            'id' => $borrow->id,
            'status' => 'proses_kembali',
            'denda' => 0,
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 2,
        ]);

        $confirm = $this->actingAs($admin)->put(route('admin.peminjaman.confirm-return', $borrow->id), [
            'return_date' => now()->toDateString(),
        ]);
        $confirm->assertSessionHas('success');

        $this->assertDatabaseHas('bookrent', [
            'id' => $borrow->id,
            'status' => 'kembali',
            'denda' => 10000,
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 3,
        ]);
    }

    public function test_user_cannot_return_other_users_book(): void
    {
        $owner = $this->makeUser(User::ROLE_ANGGOTA, 'owner-return@example.com', '1234567891');
        $other = $this->makeUser(User::ROLE_ANGGOTA, 'other-return@example.com', '1234567892');
        $book = $this->makeBook(0);

        $borrow = Bookrent::create([
            'user_id' => $owner->id,
            'borrow_date' => now()->subDays(2)->toDateString(),
            'status' => 'dipinjam',
            'denda' => 0,
        ]);

        DetailBookrent::create([
            'bookrent_id' => $borrow->id,
            'book_id' => $book->id,
            'qty' => 1,
            'condition' => 'baik',
        ]);

        $response = $this->actingAs($other)->post(route('anggota.pengembalian.store', $borrow->id));

        $response->assertSessionHas('error', 'Data peminjaman tidak ditemukan.');

        $this->assertDatabaseHas('bookrent', [
            'id' => $borrow->id,
            'status' => 'dipinjam',
        ]);
    }
}
