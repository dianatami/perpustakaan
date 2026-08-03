<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PilihKelasController extends Controller
{
    /**
     * Tampilkan halaman pilih kelas (hanya untuk siswa yang belum pernah memilih kelas).
     */
    public function index()
    {
        $user = Auth::user();

        // Jika siswa sudah terdaftar kelasnya, tidak boleh mengakses/mengubah kelas sendiri lagi
        if ($user && $user->hasSelectedKelas()) {
            return redirect()->route('anggota.beranda')
                ->with('info', 'Kelas Anda sudah terdaftar sebagai ' . $user->kelas . '. Perubahan kelas hanya dapat dilakukan oleh Admin Perpustakaan.');
        }

        // Tingkat kelas (10, 11, 12)
        $tingkatList = ['X', 'XI', 'XII'];
        
        // Data jurusan resmi SMKN 1 Tirtamulya beserta batas jumlah rombel
        $jurusanList = [
            'TJKT' => [
                'nama' => 'Teknik Jaringan Komputer Telekomunikasi',
                'max_rombel' => 2,
            ],
            'MP' => [
                'nama' => 'Manajemen Perkantoran',
                'max_rombel' => 2,
            ],
            'AK' => [
                'nama' => 'Akuntansi',
                'max_rombel' => 2,
            ],
            'TM' => [
                'nama' => 'Teknik Mesin',
                'max_rombel' => 3,
            ],
            'TITL' => [
                'nama' => 'Teknik Instalasi Tenaga Listrik',
                'max_rombel' => 2,
            ],
            'DKV' => [
                'nama' => 'Desain Komunikasi Visual',
                'max_rombel' => 2,
            ],
        ];

        return view('anggota.pilih-kelas.index', compact('user', 'tingkatList', 'jurusanList'));
    }

    /**
     * Proses simpan pilihan kelas siswa (hanya 1x saat pendaftaran/onboarding).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user || (int)$user->role !== User::ROLE_ANGGOTA) {
            return redirect()->route('dashboard');
        }

        // Proteksi ganda: jika sudah ada kelas, kunci pengubahan
        if ($user->hasSelectedKelas()) {
            return redirect()->route('anggota.beranda')
                ->with('info', 'Kelas Anda sudah terdaftar sebagai ' . $user->kelas . '. Perubahan kelas hanya dapat dilakukan oleh Admin Perpustakaan.');
        }

        $request->validate([
            'tingkat'     => 'required|in:X,XI,XII',
            'jurusan'     => 'required|in:TJKT,MP,AK,TM,TITL,DKV',
            'nomor_kelas' => 'required|in:1,2,3',
        ], [
            'tingkat.required'     => 'Silakan pilih tingkat kelas (X, XI, atau XII).',
            'jurusan.required'     => 'Silakan pilih jurusan Anda.',
            'jurusan.in'           => 'Pilihan jurusan tidak valid.',
            'nomor_kelas.required' => 'Silakan pilih nomor rombel/kelas (1, 2, atau 3).',
        ]);

        $tingkat    = trim($request->input('tingkat'));
        $jurusan    = trim($request->input('jurusan'));
        $nomorKelas = trim($request->input('nomor_kelas'));

        // Proteksi jika jurusan selain TM memilih Rombel 3
        if ($jurusan !== 'TM' && $nomorKelas === '3') {
            $nomorKelas = '2';
        }

        // Format nama kelas lengkap, misal: "X TJKT 1", "XI TM 3", "XII DKV 2"
        $namaKelasLengkap = strtoupper("{$tingkat} {$jurusan} {$nomorKelas}");

        $user->kelas = $namaKelasLengkap;
        $user->save();

        return redirect()->route('anggota.beranda')
            ->with('status', 'success')
            ->with('message', 'Kelas ' . $namaKelasLengkap . ' berhasil disimpan! Selamat datang di Perpustakaan SMKN 1 Tirtamulya.');
    }
}
