<?php

namespace App\Http\Controllers;

use App\Models\RWModel;
use Illuminate\Http\Request;

class RWController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listrw = RWModel::all();

        return view('rw.listrw.index', compact('listrw'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rw.listrw.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_rw' => 'required|String',
            'nomer_rekening' => 'required|numeric',
        ]);

        RWModel::create([
            'nama_rw' => $request->input('nama_rw'),
            'nomer_rekening' => $request->input('nomer_rekening')
        ]);

        return redirect()->route('RW.index')->with('pesan', "RW telah berhasil dibuat");
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
        $rw = RWModel::findOrFail($id);
        return view('rw.listrw.edit', compact('rw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'nama_rw' => 'required|String',
            'nomer_rekening' => 'required|numeric',
        ]);

        RWModel::where('id', $id)->update([
            'nama_rw' => $request->input('nama_rw'),
            'nomer_rekening' => $request->input('nomer_rekening')
        ]);

        return redirect()->route('RW.index')->with('pesan', "RW dengan id {$id} telah berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        RWModel::where('id', $id)->delete();

        return redirect()->route('RW.index')->with('pesan', "RW dengan id {$id} telah berhasil dihapus");
    }
}
