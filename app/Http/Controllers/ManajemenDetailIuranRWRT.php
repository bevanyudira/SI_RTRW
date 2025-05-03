<?php

namespace App\Http\Controllers;

use App\Mail\MailPembayaranGagalRT;
use App\Models\DetailIuranRWRT;
use App\Models\IuranRW;
use App\Models\RTModel;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\Mail as Mail;
use Illuminate\Support\Facades\Storage as Storage;

class ManajemenDetailIuranRWRT extends Controller
{
    public function index()
    {
        $rt_warga = Warga::where('id_warga', Auth::user() -> id_warga)->first()->id_rt;
        $rw_warga = RTModel::where('id', $rt_warga)->first()->id_rw;

        // mencari data iuran rw berdasarkan id rw warga
        $iuranRW = IuranRW::where('id_rw', $rw_warga)->pluck('id');

        // mencari data detail iuran rw, apakah data iuran rw tersebut ada di dalam $iuranRW
        $detail_iuran = DetailIuranRWRT::whereIn('id_iuran_rw', $iuranRW)->get();

        return view('iuranbayar.rt.manajemen.index', compact('detail_iuran'));
    }

    public function detailSelesai($id)
    {
        $detail_iuran = DetailIuranRWRT::find($id);
        $detail_iuran -> status = 'selesai';
        $detail_iuran -> save();
        return redirect() -> route('manajemen-detail-iuran-rw-rt.index');
    }

    public function keGagal($id)
    {
        $detail_iuran = DetailIuranRWRT::find($id);
        return view('iuranbayar.rt.manajemen.gagal', compact('detail_iuran')) -> with('success', 'Data iuran RW RT berhasil dihapus');
    }

    public function detailGagal(Request $request, $id)
    {
        // validasi input keterangan
        $this->validate($request, [
            'keterangan' => 'required|string'
        ]);

        // mencari data detail iuran berdasarkan id
        $detail_iuran = DetailIuranRWRT::find($id);

        // mencari data id_rt berdasarkan detail_iuran
        $id_rt = $detail_iuran -> id_rt;

        // mencari data user dengan rt dari data id_rt dan role admin atau ketua

        // mencari data warga dengan rt dari data id_rt
        $arr_rt = Warga::where('id_rt', $id_rt) -> get() -> pluck('id_warga');
        // mencari data user dengan id warga yang ada di $arr_rt
        $user = User::whereIn('id_warga', $arr_rt) -> get();
        // mencari data user dengan role Admin_RT atau Ketua_RT
        $admin_ketua = $user -> whereIn('role', ['Admin_RT', 'Ketua_RT']);

        // menuliskan content yang akan dikirimkan ke email
        $content = [
            'name' => 'Sistem Informasi Masyarakat (SIMAS)',
            'subject' => 'Pembayaran Iuran RW Gagal',
            'body1' => 'Pembayaran iuran dengan id '. $detail_iuran -> id,
            'body' => 'Pembayaran iuran RW anda gagal. Keterangan: ' . $request -> keterangan,
            'body2' => 'Silahkan hubungi ketua RW untuk informasi lebih lanjut.'
        ];

        // melakukan looping pada data user untuk dikirimkan email
        foreach ($admin_ketua as $u){
            // mengirimkan email ke user
            Mail::to($u -> email) -> send(new MailPembayaranGagalRT($content));
        }

        // menghapus data gambar yang tersimpan di storage
        Storage::delete('BuktiPembayaranIuranRWRT/'.$detail_iuran -> bukti_pembayaran);

        // menghapus data detail iuran yang ada di database
        DetailIuranRWRT::find($id) -> delete();

        // mengarahkan kembali ke index beserta dengan pesan sukses
        return redirect() -> route('manajemen-detail-iuran-rw-rt.index') -> with('success', 'Data iuran RW RT berhasil diubah');
    }

    public function show($id)
    {
        $gambar = DetailIuranRWRT::find($id);
        return Storage::get('BuktiPembayaranIuranRWRT/'.$gambar -> bukti_pembayaran);
    }
}
