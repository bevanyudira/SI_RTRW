<?php

namespace App\Http\Controllers;

use App\Models\KeuanganRW;
use App\Models\RTModel;
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Support\Facades\DB;   // Impor DB
use Illuminate\Support\Facades\Storage; // Impor Storage
use Illuminate\Http\Request;

class KeuanganRWController extends Controller
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
        $listKeu = KeuanganRW::where('id_rw', $rw_admin)->get();

        return view('rw.menkeu.index', compact('listKeu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisList = ['D', 'K'];
        return view('rw.menkeu.create', compact('jenisList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;
        $rw_admin = RTModel::find($rt_admin)->first()->id_rw;

        $this->validate($request, [
            'jenis' => 'required|in:D,K',
            'jumlah' => 'required|numeric',
            'path_file' => 'nullable|file',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        if ($request->hasFile('path_file')) {
            $file = $request->file('path_file');
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;

            $file->storeAs('KeuanganRW', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }

        KeuanganRW::create([
            'id_rw' => $rw_admin,
            'jenis' => $request->input('jenis'),
            'jumlah' => $request->input('jumlah'),
            'path_file' => $filenameToStore,
            'keterangan' => $request->input('keterangan'),
            'tanggal' => $request->input('tanggal')
        ]);

        return redirect()->route('RW.Keuangan.index')->with('pesan', "Laporan Keuangan RW telah berhasil dibuat");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = KeuanganRW::findOrFail($id);
        return Storage::download('KeuanganRW/' . $laporan->path_file);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $laporan = KeuanganRW::findOrFail($id);
        $jenisList = ['D', 'K'];

        return view('rw.menkeu.edit', compact('laporan', 'jenisList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $keuanganRW = KeuanganRW::findOrFail($id);

        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;
        $rw_admin = RTModel::find($rt_admin)->id_rw;

        $this->validate($request, [
            'jenis' => 'required',
            'jumlah' => 'required|numeric',
            'path_file' => 'nullable|file',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        if ($request->hasFile('path_file')) {
            Storage::delete('KeuanganRW/' . $keuanganRW->path_file);

            $file = $request->file('path_file');
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;

            $file->storeAs('KeuanganRW', $filenameToStore);

            $keuanganRW->path_file = $filenameToStore;
        }

        $keuanganRW->update([
            'id_rw' => $rw_admin,
            'jenis' => $request->input('jenis'),
            'jumlah' => $request->input('jumlah'),
            'keterangan' => $request->input('keterangan'),
            'tanggal' => $request->input('tanggal')
        ]);

        return redirect()->route('RW.Keuangan.index')->with('pesan', "Laporan Keuangan RW dengan id {$id} telah berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $keuanganRW = KeuanganRW::findOrFail($id);
        Storage::delete('KeuanganRW/' . $keuanganRW->path_file);
        $keuanganRW->delete();

        return redirect()->route('RW.Keuangan.index')->with('pesan', "Laporan Keuangan RW dengan id {$id} telah berhasil dihapus");
    }

    public function showImage(string $filename)
    {
        return Storage::get('KeuanganRW/' . $filename);
    }
}
