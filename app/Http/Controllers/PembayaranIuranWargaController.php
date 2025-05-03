<?php

namespace App\Http\Controllers;

use App\Models\DetailIuranRTPengguna;
use App\Models\IuranRT;
use App\Models\RTModel;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranIuranWargaController extends Controller
{
    public function index()
    {
        $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;
        $rw_warga = RTModel::where('id', $rt_warga)->first()->id_rw;
        $listIuranTambahan = IuranRT::where('jenis_iuran', 'tambahan') -> where('id_rt', $rt_warga)->get();
        $listIuranBulanan = IuranRT::where('jenis_iuran', 'bulanan') -> where('id_rt', $rt_warga)->get();
        return view('iuranbayar.warga.index', compact('listIuranTambahan', 'listIuranBulanan'));
    }

    // public function keBayar(Request $request)
    // {
    //     $iuranBulananIds = $request -> input('iuran_bulanan');
    //     $iuranTambahanIds = $request -> input('iuran_tambahan');

    //     return view('iuranbayar.warga.bayar', compact('iuranBulananIds', 'iuranTambahanIds'));
    // }

    public function bayar(Request $request)
    {
        $iuranBulananIds = $request -> input('iuran_bulanan');
        $iuranTambahanIds = $request -> input('iuran_tambahan');

        // $iuranTambahan = IuranRT::whereIn('id', $iuranTambahanIds)->get();
        // $iuranBulanan = IuranRT::whereIn('id', $iuranBulananIds)->get();
        // Jika $iuranBulananIds kosong, set $iuranBulanan menjadi koleksi kosong
        if (empty($iuranBulananIds)) {
            $iuranBulanan = collect();
        } else {
            $iuranBulanan = IuranRT::whereIn('id', $iuranBulananIds)->get();
        }

        // Jika $iuranTambahanIds kosong, set $iuranTambahan menjadi koleksi kosong
        if (empty($iuranTambahanIds)) {
            $iuranTambahan = collect();
        } else {
            $iuranTambahan = IuranRT::whereIn('id', $iuranTambahanIds)->get();
        }

        // return view('iuranbayar.warga.bayar', compact('iuranBulanan', 'iuranTambahan'));
        // dd(compact('iuranBulanan', 'iuranTambahan'));
        return redirect()->route('bayar-iuran.konfirmasi') -> with(compact('iuranBulanan', 'iuranTambahan'));
    }

    public function konfirmasi()
    {
        $iuranBulanan = session('iuranBulanan', collect());
        $iuranTambahan = session('iuranTambahan', collect());
        return view('iuranbayar.warga.bayar', compact('iuranBulanan', 'iuranTambahan'));
        // return view('iuranbayar.warga.bayar');
    }

    public function konfirmasiBayar(Request $request)
    {
        $iuranTambahanIds = json_decode($request->input('iuran_tambahan', '[]'));
        $iuranBulananIds = json_decode($request->input('iuran_bulanan', '[]'));

        $userId = Auth::user()->id;

        // $this->validate($request, [
        //     // 'nomor_rekening' => 'required|numeric',
        //     'bukti_pembayaran' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);
        // dd($request -> all());

        if ($request -> hasFile('bukti_pembayaran')) {
            $file = $request -> file('bukti_pembayaran');
            $fileNameWithExt = $file -> getClientOriginalName();
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $file -> getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '_'. $extension;

            $file -> storeAs('BuktiPembayaranIuranRTPengguna', $filenameToStore);
        } else {
            return redirect() -> back() -> with('pesan','File is required');
        }

        foreach ($iuranTambahanIds as $id) {
            DetailIuranRTPengguna::create([
                'id_iuran_rt' => $id,
                'id_pengguna' => $userId,
                'status' => 'pending',
                // 'nomor_rekening' => $request->input('nomor_rekening'),
                'bukti_pembayaran' => $filenameToStore,
            ]);
        }

        foreach ($iuranBulananIds as $id) {
            DetailIuranRTPengguna::create([
                'id_iuran_rt' => $id,
                'id_pengguna' => $userId,
                'status' => 'pending',
                // 'nomor_rekening' => $request->input('nomor_rekening'),
                'bukti_pembayaran' => $filenameToStore,
            ]);
        }

        return redirect()->route('bayar-iuran.index')->with('pesan', 'Pembayaran berhasil diproses.');
    }
}
