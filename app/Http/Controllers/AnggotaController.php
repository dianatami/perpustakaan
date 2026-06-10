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
                  ->orWhere('hp', 'like', "%{$search}%");
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
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:user,email',
        'hp' => 'required|digits_between:10,13',
        'password' => 'required|string|min:6',
    ]);

    $identifier = trim((string) $request->nip);
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

    }

    $nip = null;
    $nisn = null;
    $role = User::ROLE_ANGGOTA;

    if ($identifier !== '') {

        if (User::isValidNip($identifier)) {

            $nip = $identifier;
            $role = User::ROLE_GURU;

        } elseif (User::isValidNisn($identifier)) {

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
    ]);

    return redirect()
        ->route('admin.anggota.index')
        ->with('success', 'Anggota berhasil ditambahkan');
}

    /**
     * Show the form for editing a member
     */
    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('admin.user.edit', compact('anggota'));
    }

    /**
     * Update the specified member in database
     */
    public function update(Request $request, $id)
{
    $anggota = User::findOrFail($id);

    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:user,email,' . $id,
        'hp' => 'required|string|max:13',
        'nip' => 'nullable|string|max:20',
        'password' => 'nullable|string|min:6',
    ]);

    $identifier = trim((string) $request->nip);

    $nip = null;
    $nisn = null;
    $role = User::ROLE_ANGGOTA;

    if ($identifier !== '') {

        if (User::isValidNip($identifier)) {

            $nip = $identifier;
            $role = User::ROLE_GURU;

        } elseif (User::isValidNisn($identifier)) {

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

    $anggota->update([
        'nama' => $request->nama,
        'email' => $request->email,
        'hp' => $request->hp,
        'status' => $request->status,
        'role' => $role,
        'nip' => $nip,
        'nisn' => $nisn,
    ]);

    if ($request->filled('password')) {

        $anggota->update([
            'password' => bcrypt($request->password)
        ]);

    }

    return redirect()
        ->route('admin.anggota.index')
        ->with('success', 'Anggota berhasil diperbarui');
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
}
