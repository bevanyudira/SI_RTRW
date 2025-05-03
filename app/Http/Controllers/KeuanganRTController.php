<?php

namespace App\Http\Controllers;

use App\Models\KeuanganRT;
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Support\Facades\DB;   // Impor DB
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Impor Storage

class KeuanganRTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;
        $listKeu = KeuanganRT::where('id_rt', $rt_admin)->get();

        return view('rt.menkeu.index', compact('listKeu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisList = ['D', 'K'];
        return view('rt.menkeu.create', compact('jenisList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'jenis' => 'required|in:D,K',
            'jumlah' => 'required|numeric',
            'path_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'keterangan' => 'required|String',
            'tanggal' => 'required|date',
        ]);

        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;

        if ($request->hasFile('path_file')) {
            $file = $request->file('path_file');
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '_' . $extension;

            $file->storeAs('KeuanganRT', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }

        KeuanganRT::create([
            'id_rt' => $rt_admin,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'path_file' => $filenameToStore,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal
        ]);

        return redirect()->route('RT.Keuangan.index')->with('pesan', "Data keuangan telah berhasil dibuat");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $keuangan = KeuanganRT::find($id);
        return Storage::download("KeuanganRT/" . $keuangan->path_file);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $laporan = KeuanganRT::findOrFail($id);
        $jenisList = ['D', 'K'];

        return view('rt.menkeu.edit', compact('laporan', 'jenisList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'jenis' => 'required|in:D,K',
            'jumlah' => 'required|numeric',
            'path_file' => 'nullable|file',
            'keterangan' => 'required|String',
            'tanggal' => 'required|date',
        ]);

        $laporan = KeuanganRT::findOrFail($id);

        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;

        if ($request->hasFile('path_file')) {
            Storage::delete('KeuanganRT/' . $laporan->path_file);

            $file = $request->file('path_file');
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '_' . $extension;

            $file->storeAs('KeuanganRT', $filenameToStore);

            $laporan->path_file = $filenameToStore;
        }

        $laporan->update([
            'id_rt' => $rt_admin,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal
        ]);

        return redirect()->route('RT.Keuangan.index')->with('pesan', "Laporan Keuangan RT dengan id {$id} telah berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
