<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Display a listing of members
     */
    public function index(Request $request)
{
    $search = $request->search;

    $siswa = User::where('role', (string) User::ROLE_ANGGOTA)
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('hp', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        })
        ->paginate(10, ['*'], 'siswa_page')
        ->withQueryString();

    $guru = User::where('role', (string) User::ROLE_GURU)
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('hp', 'like', "%{$search}%");
            });
        })
        ->paginate(10, ['*'], 'guru_page')
        ->withQueryString();

    return view('admin.user.anggota', compact('siswa', 'guru', 'search'));
}

    /**
     * Show the form for creating a new member
     */
    public function create()
    {
        return view('admin.user.create');
            
    }

    /**
     * Store a newly created member in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|string|max:20',
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-\']+$/i',
            'email' => 'required|email|unique:user,email',
            'hp' => 'required|digits_between:10,13',
            'password' => 'required|string|min:6',
            'kelas' => 'nullable|string|max:50',
            'role' => 'nullable|in:0,2',
        ]);

        $identifier = trim((string) $request->nip);
        $nip = null;
        $nisn = null;
        $role = $request->filled('role') ? (int) $request->role : User::ROLE_ANGGOTA;

        if ($identifier !== '') {
            if (User::isValidNip($identifier)) {
                if (User::where('nip', $identifier)->exists()) {
                    return back()
                        ->withErrors([
                            'nip' => 'NIP sudah digunakan oleh pengguna lain.'
                        ])
                        ->withInput();
                }
                $nip = $identifier;
                $role = User::ROLE_GURU;
            } elseif (User::isValidNisn($identifier)) {
                if (User::where('nisn', $identifier)->exists()) {
                    return back()
                        ->withErrors([
                            'nip' => 'NISN sudah digunakan oleh pengguna lain.'
                        ])
                        ->withInput();
                }
                $nisn = $identifier;
                $role = User::ROLE_ANGGOTA;
            } else {
                return back()
                    ->withErrors([
                        'nip' => 'Format NIP/NISN tidak valid.'
                    ])
                    ->withInput();
            }
        }

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password' => bcrypt($request->password),
            'role' => $role,
            'status' => 1,
            'nip' => $nip,
            'nisn' => $nisn,
            'kelas' => ($role === User::ROLE_ANGGOTA && $request->filled('kelas')) ? strtoupper(trim((string) $request->kelas)) : null,
        ]);

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan');
    }

    /**
     * Show the form for editing a member (Disabled)
     */
    public function edit($id)
    {
        return redirect()
            ->route('admin.anggota.index')
            ->with('error', 'Pengubahan data anggota tidak diperbolehkan.');
    }

    /**
     * Update the specified member in database (Disabled)
     */
    public function update(Request $request, $id)
    {
        return redirect()
            ->route('admin.anggota.index')
            ->with('error', 'Pengubahan data anggota tidak diperbolehkan.');
    }

    /**
     * Delete a member
     */
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus');
    }

    /**
     * Toggle member status
     */
    public function toggleStatus($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->update(['status' => !$anggota->status]);

        return redirect()->route('admin.anggota.index')->with('success', 'Status anggota berhasil diubah');
    }

    public function importGuru(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $defaultPassword = $request->input('default_password', 'SmkTirtamulya2026');
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\GuruImport($defaultPassword), $request->file('file'));

            return redirect()->route('admin.anggota.index')->with('success', 'Data Guru & Akun berhasil di-import dan dipindahkan ke sistem!');
        } catch (\Exception $e) {
            return redirect()->route('admin.anggota.index')->with('error', 'Gagal mengimport data guru: ' . $e->getMessage());
        }
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $defaultPassword = $request->input('default_password', 'SmkTirtamulya2026');
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\SiswaImport($defaultPassword), $request->file('file'));

            return redirect()->route('admin.anggota.index')->with('success', 'Data Siswa & Akun berhasil di-import dan dipindahkan ke sistem!');
        } catch (\Exception $e) {
            return redirect()->route('admin.anggota.index')->with('error', 'Gagal mengimport data siswa: ' . $e->getMessage());
        }
    }

    public function downloadTemplateGuru()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GuruTemplateExport(), 'Template_Import_Guru.xlsx');
    }

    public function downloadTemplateSiswa()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SiswaTemplateExport(), 'Template_Import_Siswa.xlsx');
    }
}

