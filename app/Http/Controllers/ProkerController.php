<?php

namespace App\Http\Controllers;

use App\Models\DetailProkerRT;
use App\Models\DetailProkerRW;
use App\Models\Proker;
use App\Models\RTModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Support\Facades\DB;   // Impor DB
use Illuminate\Support\Facades\Storage; // Impor Storage

class ProkerController extends Controller
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
        $listIdProkerRT = DetailProkerRT::where('id_rt', $rt_admin)->get();
        $listIdProkerRW = DetailProkerRW::where('id_rw', $rw_admin)->get();

        $prokerRTList = $listIdProkerRT->map(function ($detailProkerRT) {
            return $detailProkerRT->proker; // Mengakses relasi 'proker'
        });

        $prokerRWList = $listIdProkerRW->map(function ($detailProkerRW) {
            return $detailProkerRW->proker; // Mengakses relasi 'proker'
        });

        return view('proker.index', compact('prokerRTList', 'prokerRWList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statusList = ['on_progress', 'selesai'];
        return view('proker.create', compact('statusList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pesan = '';
        $this->validate($request, [
            'judul' => 'required|string',
            'isi' => 'required|string',
            'waktu' => 'required',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi' => 'required|string',
            'gambar' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:on_progress,selesai'
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;

            $file->storeAs('Proker', $filenameToStore);
        } else {
            return redirect()->back()->with('pesan', 'File is required');
        }

        $proker = Proker::create([
            'judul' => $request->input('judul'),
            'isi' => $request->input('isi'),
            'waktu' => $request->input('waktu'),
            'tanggal_pelaksanaan' => $request->input('tanggal_pelaksanaan'),
            'lokasi' => $request->input('lokasi'),
            'gambar' => $filenameToStore,
            'status' => $request->input('status')
        ]);

        // find the id rt of the admin
        $rt_admin = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->first()->id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin)->first()->id_rw;

        if (Auth::user()->role == 'Admin_RT') {
            $pesan = "Data Proker RT telah terbuat";
            DetailProkerRT::create([
                'id_proker' => $proker->id,
                'id_rt' => $rt_admin
            ]);
        }
        if (Auth::user()->role == 'Admin_RW') {
            $pesan = "Data Proker RW telah terbuat";
            DetailProkerRW::create([
                'id_proker' => $proker->id,
                'id_rw' => $rw_admin
            ]);
        }

        return redirect()->route('proker.index')->with('pesan', $pesan);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $proker = Proker::findOrFail($id);
        return Storage::get('Proker/' . $proker->gambar);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $proker = Proker::findOrFail($id);
        $statusList = ['on_progress', 'selesai'];
        return view('proker.edit', compact('proker', 'statusList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $proker = Proker::findOrFail($id);

        $this->validate($request, [
            'judul' => 'required|string',
            'isi' => 'required|string',
            'waktu' => 'required|date_format:H:i:s',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi' => 'required|string',
            'gambar' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:on_progress,selesai'
        ]);

        if ($request->hasFile('gambar')) {
            Storage::delete('Proker/' . $proker->gambar);

            $file = $request->file('gambar');
            $fileNameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;

            $proker->gambar = $filenameToStore;
            $file->storeAs('Proker', $filenameToStore);
        }

        $proker->update([
            'judul' => $request->input('judul'),
            'isi' => $request->input('isi'),
            'waktu' => $request->input('waktu'),
            'tanggal_pelaksanaan' => $request->input('tanggal_pelaksanaan'),
            'lokasi' => $request->input('lokasi'),
            'status' => $request->input('status')
        ]);

        return redirect()->route('proker.index')->with('pesan', "Program kerja dengan id {$id} telah berhasil di ubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detProRT = DetailProkerRT::where('id_proker', $id);
        $detProRW = DetailProkerRW::where('id_proker', $id);
        if ($detProRT) {
            $detProRT->delete();
        }
        if ($detProRW) {
            $detProRW->delete();
        }
        Proker::where('id', $id)->delete();

        return redirect()->route('proker.index')->with('pesan', "Program kerja dengan id {$id} telah berhasil dihapus");
    }
}
