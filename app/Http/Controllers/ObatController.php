<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('obat.index')->with('message', 'Obat berhasil ditambahkan.')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit')->with(['obat' => $obat]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan' => 'required|string',
            'harga' => 'required|integer',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan' => $request->kemasan,
            'harga' => $request->harga,
        ]);

        return redirect()->route('obat.index')->with('message', 'Obat berhasil diperbarui.')->with('type', 'success');
    }

    public function detail(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.detail', compact('obat'));
    }

    public function updateStok(Request $request, string $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ], [
            'jumlah.required' => 'Jumlah stok harus diisi.',
            'jumlah.integer' => 'Jumlah stok harus berupa angka.',
            'jumlah.min' => 'Jumlah stok minimal 1.'
        ]);

        $obat = Obat::findOrFail($id);

        if ($request->action === 'tambah') {
                $obat->update([
                'stok' => $obat->stok + $request->jumlah
            ]);

            return back()->with('message', 'Stok berhasil ditambahkan!')
                ->with('type', 'success');
        }

        if ($request->action === 'kurang') {
            if ($obat->stok < $request->jumlah) {
                return back()->with('message', 'Stok tidak mencukupi!')
                    ->with('type', 'danger');
            }

            $obat->update([
                'stok' => $obat->stok - $request->jumlah
            ]);

            return back()->with('message', 'Stok berhasil dikurangi!')
                ->with('type', 'success');
        }

        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')->with('message', 'Obat berhasil dihapus.')->with('type', 'success');
    }
}
