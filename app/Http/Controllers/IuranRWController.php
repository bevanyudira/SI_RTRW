<?php

namespace App\Http\Controllers;

use App\Models\IuranRW;
use App\Models\RTModel;
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Support\Facades\DB;   // Impor DB
use Illuminate\Http\Request;

class IuranRWController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;

        $rw_admin = RTModel::find($rt_admin)->first()->id_rw;

        $iuranBulanan = IuranRW::where('id_rw', $rw_admin)->where('jenis_iuran', 'bulanan')->get();
        $iuranTambahan = IuranRW::where('id_rw', $rw_admin)->where('jenis_iuran', 'tambahan')->get();

        return view('rw.iuranrw.index', compact('iuranTambahan', 'iuranBulanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bulanList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $jenis_iuran = ['bulanan', 'tambahan'];
        return view('rw.iuranrw.create', compact('bulanList', 'jenis_iuran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $warga = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;

        $rw_admin = RTModel::find($warga)->first()->id_rw;

        $this->validate($request, [
            'bulan' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'jenis_iuran' => 'required|in:bulanan,tambahan',
            'nama_iuran' => 'required|String',
            'total_iuran' => 'required|numeric',
        ]);

        IuranRW::create([
            'id_rw' => $rw_admin,
            'nama_iuran' => $request->input('nama_iuran'),
            'bulan' => $request->input('bulan'),
            'total_iuran' => $request->input('total_iuran'),
            'jenis_iuran' => $request->input('jenis_iuran')
        ]);

        return redirect()->route('iuranRW.index')->with('pesan', "Data iuran telah berhasil dibuat");
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
        $iuran = IuranRW::find($id);
        $bulanList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $jenis_iuran = ['bulanan', 'tambahan'];
        return view('rw.iuranrw.edit', compact('iuran', 'bulanList', 'jenis_iuran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'bulan' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'jenis_iuran' => 'required|in:bulanan,tambahan',
            'nama_iuran' => 'required|String',
            'total_iuran' => 'required|numeric',
        ]);

        IuranRW::findOrFail($id)->update([
            'nama_iuran' => $request->input('nama_iuran'),
            'bulan' => $request->input('bulan'),
            'total_iuran' => $request->input('total_iuran'),
            'jenis_iuran' => $request->input('jenis_iuran')
        ]);

        return redirect()->route('iuranRW.index')->with('pesan', "Data iuran dengan id {$id} telah berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        IuranRW::destroy($id);
        return redirect()->route('iuranRW.index')->with('pesan', "Data iuran dengan id {$id} telah berhasil dihapus");
    }
}
