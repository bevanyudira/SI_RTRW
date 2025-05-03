<?php

namespace App\Http\Controllers;

use App\Models\RTModel;
use App\Models\RWModel;
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Support\Facades\DB;   // Impor DB
use Illuminate\Http\Request;

class RTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin)->first()->id_rw;
        // get the list of the rt where the rw is the same as the rw_admin
        $listrt = RTModel::where('id_rw', $rw_admin)->get();

        return view('rt.listrt.index', compact('listrt'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rwListid = RWModel::select('id')->distinct()->get();
        // $rwListnama = RWModel::select('nama_rw')->distinct()->get();

        return view('rt.listrt.create', compact('rwListid'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin)->first()->id_rw;

        $this->validate($request, [
            'nama_rt' => 'required|string',
            'nomor_rekening' => 'required|numeric',
        ]);

        RTModel::create([
            'id_rw' => $rw_admin,
            'nama_rt' => $request->input('nama_rt'),
            'nomor_rekening' => $request->input('nomor_rekening')
        ]);

        return redirect()->route('RT.index')->with('pesan', "RT telah berhasil dibuat");
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
        $rt = RTModel::findOrFail($id);

        return view('rt.listrt.edit', compact('rt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin)->first()->id_rw;

        $this->validate($request, [
            'nama_rt' => 'required|string',
            'nomor_rekening' => 'required|numeric',
        ]);

        RTModel::where('id', $id)->update([
            'id_rw' => $rw_admin,
            'nama_rt' => $request->input('nama_rt'),
            'nomor_rekening' => $request->input('nomor_rekening'),
        ]);

        return redirect()->route('RT.index')->with('pesan', "RT dengan id {$id} telah berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        RTModel::where('id', $id)->delete();

        return redirect()->route('RT.index')->with('pesan', "RT dengan id {$id} telah berhasil dihapus");
    }
}
