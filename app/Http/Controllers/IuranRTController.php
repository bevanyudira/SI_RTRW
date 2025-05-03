<?php

namespace App\Http\Controllers;

use App\Models\IuranRT;
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Support\Facades\DB;   // Impor DB
use Illuminate\Http\Request;

class IuranRTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;

        $iuranBulanan = IuranRT::where('id_rt', $rt_admin)->where('jenis_iuran', 'bulanan')->get();
        $iuranTambahan = IuranRT::where('id_rt', $rt_admin)->where('jenis_iuran', 'tambahan')->get();

        return view('rt.iuranrt.index', compact('iuranTambahan', 'iuranBulanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bulanList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $jenis_iuran = ['bulanan', 'tambahan'];
        return view('rt.iuranrt.create', compact('bulanList', 'jenis_iuran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $warga = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;

        $this->validate($request, [
            'bulan' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'jenis_iuran' => 'required|in:bulanan,tambahan',
            'nama_iuran' => 'required|string',
            'total_iuran' => 'required|numeric',
        ]);

        IuranRT::create([
            'id_rt' => $warga,
            'nama_iuran' => $request->input('nama_iuran'),
            'bulan' => $request->input('bulan'),
            'total_iuran' => $request->input('total_iuran'),
            'jenis_iuran' => $request->input('jenis_iuran'),
        ]);

        return redirect()->route('iuranRT.index')->with('pesan', "Data iuran telah berhasil dibuat");
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
        $iuran = IuranRT::findOrFail($id);
        $bulanList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $jenisList = ['bulanan', 'tambahan'];
        return view('rt.iuranrt.edit', compact('iuran', 'bulanList', 'jenisList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'bulan' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'jenis_iuran' => 'required|in:bulanan,tambahan',
            'nama_iuran' => 'required|string',
            'total_iuran' => 'required|numeric',
        ]);

        IuranRT::findOrFail($id)->update([
            'nama_iuran' => $request->input('nama_iuran'),
            'bulan' => $request->input('bulan'),
            'total_iuran' => $request->input('total_iuran'),
            'jenis_iuran' => $request->input('jenis_iuran'),
        ]);

        return redirect()->route('iuranRT.index')->with('pesan', "Data Iuran dengan id {$id} telah berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $iuran = IuranRT::find($id);
        $iuran->delete();

        return redirect()->route('iuranRT.index')->with('Pesan', "Iuran dengan id {$id} telah berhasil dihapus");
    }
}
