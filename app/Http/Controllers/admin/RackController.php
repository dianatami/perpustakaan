<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RackController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);

        $query = Rack::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $racks = $query
            ->withCount('books')
            ->orderBy('code')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.racks.index', compact('racks', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:racks,code'],
            'name' => ['required', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper(trim((string) $data['code']));
        $data['is_active'] = $request->boolean('is_active');

        Rack::create($data);

        return redirect()
            ->route('admin.racks.index')
            ->with('success', 'Rak berhasil ditambahkan.');
    }

    public function edit(Rack $rack)
    {
        return view('admin.racks.edit', compact('rack'));
    }

    public function update(Request $request, Rack $rack)
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('racks', 'code')->ignore($rack->id),
            ],
            'name' => ['required', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] = strtoupper(trim((string) $data['code']));
        $data['is_active'] = $request->boolean('is_active');

        $rack->update($data);

        return redirect()
            ->route('admin.racks.index')
            ->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(Rack $rack)
    {
        $rack->delete();

        return redirect()
            ->route('admin.racks.index')
            ->with('success', 'Rak berhasil dihapus.');
    }
}
