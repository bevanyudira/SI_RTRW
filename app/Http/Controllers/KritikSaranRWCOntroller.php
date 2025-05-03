<?php

namespace App\Http\Controllers;

use App\Models\KritikSaranRW;
use App\Models\RWModel;
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Http\Request;

class KritikSaranRWCOntroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kritikRW = KritikSaranRW::where('id_rw', Auth::user()->id_rw)->orderBy('created_at', 'desc')->get();

        return view('rw.kritiksaran.index', compact('kritikRW'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $listRW = RWModel::get();
        return view('rw.kritiksaran.create', compact('listRW'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_rw' => 'required',
            'isi' => 'required'
        ]);

        KritikSaranRW::create([
            'id_rw' => $request->id_rw,
            'id_pengguna' => Auth::user()->id,
            'isi' => $request->isi,
            'status' => 'dilihat'
        ]);

        return redirect()->route('kritikRW.index')->with('success', 'Kritik dan saran berhasil dikirim');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kritik = KritikSaranRW::find($id);

        return view('rw.kritiksaran.show', compact('kritik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $listStatus = ['dilihat', 'diproses', 'selesai'];
        $kritik = KritikSaranRW::find($id);

        return view('rw.kritiksaran.edit', compact('kritik', 'listStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'status' => 'required|in:dilihat,diproses,selesai'
        ]);

        $kritik = KritikSaranRW::find($id);
        $kritik->status = $request->status;
        $kritik->save();

        return redirect()->route('kritikRW.index')->with('success', 'Status kritik dan saran berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KritikSaranRW::destroy($id);

        return redirect()->route('kritikRW.index')->with('success', 'Kritik dan saran berhasil dihapus');
    }
}
