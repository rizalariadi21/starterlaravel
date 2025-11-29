<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', \App\Models\Pengguna::class);
        $users = Pengguna::select('id_pengguna','pengguna','username','level','status')
            ->addSelect('created_at','updated_at')
            ->orderBy('id_pengguna','asc')
            ->get();
        return view('halaman/pengguna/index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Pengguna::class);
        return view('halaman/pengguna/create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Pengguna::class);
        $data = $request->validate([
            'pengguna' => 'required|string|max:191',
            'institusi' => 'nullable|string|max:191',
            'no_hp' => 'nullable|string|max:20',
            'username' => 'required|string|max:191|unique:pengguna,username',
            'password' => 'required|string|min:6',
            'level' => 'required|integer|in:1,2,3',
            'status' => 'required|integer|in:0,1',
        ]);

        $data['password'] = Hash::make($data['password']);
        Pengguna::create($data);

        return redirect()->route('pengguna-index')->with('status', 'Pengguna berhasil ditambahkan');
    }

    public function edit(Pengguna $pengguna)
    {
        $this->authorize('update', $pengguna);
        return view('halaman/pengguna/edit', compact('pengguna'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $this->authorize('update', $pengguna);
        $data = $request->validate([
            'pengguna' => 'required|string|max:191',
            'institusi' => 'nullable|string|max:191',
            'no_hp' => 'nullable|string|max:20',
            'username' => 'required|string|max:191|unique:pengguna,username,' . $pengguna->id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:6',
            'level' => 'required|integer|in:1,2,3',
            'status' => 'required|integer|in:0,1',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna-index')->with('status', 'Pengguna berhasil diperbarui');
    }

    public function destroy(Pengguna $pengguna)
    {
        $this->authorize('delete', $pengguna);
        $pengguna->delete();
        return redirect()->route('pengguna-index')->with('status', 'Pengguna berhasil dihapus');
    }
}
