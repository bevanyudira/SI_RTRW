<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\IuranRW;
use App\Models\RTModel;
use Illuminate\Http\Request;
use App\Models\DetailIuranRWRT;
use Illuminate\Support\Facades\Auth;

class PembayaranIuranRTController extends Controller
{
    public function index()
    {
        $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;
        $rw_warga = RTModel::where('id', $rt_warga)->first()->id_rw;

        $listIuranTambahan = IuranRW::where('jenis_iuran', 'tambahan') -> where('id_rw', $rw_warga)->get();
        $listIuranBulanan = IuranRW::where('jenis_iuran', 'bulanan') -> where('id_rw', $rw_warga)->get();
        return view('iuranbayar.rt.index', compact('listIuranTambahan', 'listIuranBulanan'));
    }

    public function bayar(Request $request)
    {
        $iuranBulananIds = $request -> input('iuran_bulanan');
        $iuranTambahanIds = $request -> input('iuran_tambahan');

        // Jika $iuranBulananIds kosong, set $iuranBulanan menjadi koleksi kosong
        if (empty($iuranBulananIds)) {
            $iuranBulanan = collect();
        } else {
            $iuranBulanan = IuranRW::whereIn('id', $iuranBulananIds)->get();
        }

        // Jika $iuranTambahanIds kosong, set $iuranTambahan menjadi koleksi kosong
        if (empty($iuranTambahanIds)) {
            $iuranTambahan = collect();
        } else {
            $iuranTambahan = IuranRW::whereIn('id', $iuranTambahanIds)->get();
        }

        return view('iuranbayar.rt.bayar', compact('iuranBulanan', 'iuranTambahan'));
    }

    public function konfirmasi()
    {
        return view('iuranbayar.rt.bayar');
    }

    public function konfirmasiBayar(Request $request)
    {
        $iuranTambahanIds = json_decode($request->input('iuran_tambahan', '[]'));
        $iuranBulananIds = json_decode($request->input('iuran_bulanan', '[]'));

        // $userId = Auth::user()->id;
        $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;

        if ($request -> hasFile('bukti_pembayaran')) {
            $file = $request -> file('bukti_pembayaran');
            $fileNameWithExt = $file -> getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file -> getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '_'. $extension;

            $file -> storeAs('BuktiPembayaranIuranRWRT', $filenameToStore);
        } else {
            return redirect() -> back() -> with('pesan','File is required');
        }

        foreach ($iuranBulananIds as $id){
            DetailIuranRWRT::create([
                'id_iuran_rw' => $id,
                'id_rt' => $rt_warga,
                'status' => 'pending',
                'bukti_pembayaran' => $filenameToStore,
            ]);
        }

        foreach ($iuranTambahanIds as $id){
            DetailIuranRWRT::create([
                'id_iuran_rw' => $id,
                'id_rt' => $rt_warga,
                'status' => 'pending',
                'bukti_pembayaran' => $filenameToStore,
            ]);
        }

        return redirect() -> route('bayar-iuran-rt.index') -> with('pesan', 'Pembayaran berhasil diproses.');
    }
}
