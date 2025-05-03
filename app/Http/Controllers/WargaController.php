<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Manajemen ini nantinya akan dimiliki oleh admin dari tiap-tiap RT
        $warga = Warga::all();

        return view('warga.index', compact('warga'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warga.RegisterWarga');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required|numeric|min_digits:15',
            'nama' => 'required|string|min:5',
            'alamat' => 'required|string'
        ]);

        $warga = new Warga();
        $warga->id_warga = $request->nik;
        $warga->nama = $request->nama;
        $warga->alamat = $request->alamat;
        $warga->save();

        return redirect()->route('warga.index');
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
        $warga = Warga::where('id_warga', $id)->first();
        return view('warga.edit', compact('warga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'nik' => 'required|numeric|min_digits:15',
            'nama' => 'required|string|min:5',
            'alamat' => 'required|string'
        ]);

        $warga = Warga::where('id_warga', $id)->first();
        $warga->id_warga = $request->nik;
        $warga->nama = $request->nama;
        $warga->alamat = $request->alamat;
        $warga->save();

        return redirect()->route('warga.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warga = Warga::where('id_warga', $id)->first();
        $warga->delete();

        return redirect()->route('warga.index');
    }
}
