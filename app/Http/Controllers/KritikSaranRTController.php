<?php

namespace App\Http\Controllers;

use App\Models\KritikSaranRT;
use App\Models\RTModel;
use App\Models\RWModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;

class KritikSaranRTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $kritikRT = KritikSaranRT::all();
        $kritikRT = KritikSaranRT::where('id_rt', Auth::user() -> id_rt) -> orderBy('created_at', 'desc') -> get();

        return view('rt.kritiksaran.index', compact('kritikRT'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $listRT = RTModel::get();
        $listRW = RWModel::get();

        $listRTRW = array_merge($listRT, $listRW);
        return view('rt.kritiksaran.create', compact('listRTRW'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'id_rt' => 'required',
            'isi' => 'required'
        ]);

        KritikSaranRT::create([
            'id_rt' => $request -> id_rt,
            'id_pengguna' => Auth::user() -> id,
            'isi' => $request -> isi,
            'status' => 'dilihat'
        ]);
        // $kritikRT = new KritikSaranRT();
        // $kritikRT -> id_rt = $request -> id_rt;
        // $kritikRT -> id_pengguna = Auth::user() -> id;
        // $kritikRT -> isi = $request -> isi;
        // $kritikRT -> status = 'dilihat';
        // $kritikRT -> save();

        return redirect() -> route('kritikRT.index') -> with('success', 'Kritik dan saran berhasil dikirim');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kritik = KritikSaranRT::findOrFail($id);

        return view('rt.kritiksaran.show', compact('kritik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $listStatus = ['dilihat', 'diproses', 'selesai'];
        $kritik = KritikSaranRT::findOrFail($id);

        return view('rt.kritiksaran.edit', compact('kritik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate($request, [
            'status' => 'required|in:dilihat,diproses,selesai'
        ]);

        $kritik = KritikSaranRT::findOrFail($id);
        $kritik -> status = $request -> status;
        $kritik -> save();

        return redirect() -> route('kritikRT.index') -> with('success', 'Status kritik dan saran berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KritikSaranRT::where('id', $id) -> delete();

        return redirect() -> route('kritikRT.index') -> with('success', 'Kritik dan saran berhasil dihapus');
    }
}
