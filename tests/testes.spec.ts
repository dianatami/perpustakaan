import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/');
  await page.getByRole('link', { name: 'Masuk Sekarang' }).click();
  await page.getByRole('link', { name: 'Daftar sekarang' }).click();
  await page.getByRole('textbox', { name: 'Nama Lengkap' }).click();
  await page.goto('http://127.0.0.1:8000/tampilan/login');
  await page.getByRole('textbox', { name: 'Email / NIP / NISN' }).click();
  await page.getByRole('textbox', { name: 'Email / NIP / NISN' }).fill('admin@gmail.com');
  await page.getByRole('textbox', { name: 'Password' }).click();
  await page.getByRole('textbox', { name: 'Password' }).fill('P@55word');
  await page.getByRole('button', { name: 'Masuk Dashboard' }).click();
  await page.goto('http://127.0.0.1:8000/admin/beranda');
  await page.getByRole('link', { name: ' Peminjaman' }).click();
  await page.getByRole('button', { name: ' Setujui' }).click();
  await page.getByRole('dialog', { name: '✓ Setujui Pengajuan Peminjaman' }).click();
  await page.getByRole('button', { name: 'Close' }).click();
  await page.getByRole('link', { name: ' Tambah Peminjaman' }).click();
  await page.goto('http://127.0.0.1:8000/admin/peminjaman');
  page.once('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.dismiss().catch(() => {});
  });
  await page.getByRole('button', { name: ' Tolak' }).click();
  await page.getByText('Control Room Perpustakaan Sekolah Perpustakaan SMKN 1 Tirtamulya Ringkasan Data').click();
  await page.goto('http://127.0.0.1:8000/');
  await page.getByRole('link', { name: 'Dashboard', exact: true }).click();
  await page.getByRole('link', { name: ' Data Buku' }).click();
  await page.getByRole('link', { name: 'Tambah Buku' }).click();
  await page.getByRole('combobox').selectOption('1');
  await page.locator('input[name="title"]').click();
  await page.locator('input[name="title"]').press('CapsLock');
  await page.locator('input[name="title"]').fill('ABCDDD');
  await page.locator('input[name="author"]').click();
  await page.locator('input[name="author"]').fill('ABCDD');
  await page.locator('input[name="publisher"]').click();
  await page.locator('input[name="publisher"]').fill('ABC');
});