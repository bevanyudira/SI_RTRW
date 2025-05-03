<?php

namespace App\Http\Controllers;

use App\Models\KritikSaranRT;
use App\Models\KritikSaranRW;
use App\Models\RTModel;
use App\Models\RWModel;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\DB as DB;

class KritikSaranController extends Controller
{
    public function index()
    {
        return view('kritiksaran.index');
    }

    public function store(Request $request)
    {
        $this-> validate($request, [
            'isi' => 'required|string',
        ]);

        // langsung melakukan kritik ke rt masing masing pengguna
        KritikSaranRT::create([
            'id_rt' => Warga::where('id_warga', Auth::user() -> id_warga) -> first() -> id_rt,
            'id_pengguna' => Auth::user() -> id,
            'isi' => $request->isi,
            'status' => 'belum'
        ]);
        return redirect()->route('kritik.index')->with('pesan', 'Kritik dan Saran berhasil dikirim');
    }

    public function listKritikRT()
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga') -> where('id_warga', Auth::user() -> id_warga) -> first() -> id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin) -> first() -> id_rw;
        $listKritikRT = KritikSaranRT::where('id_rt', $rt_admin) -> get() -> sortBy('created_at');

        return view('kritiksaran.listkritikRT', compact('listKritikRT'));
    }

    public function listKritikRW()
    {
        // find the id rt of the admin
        $rt_admin = DB::table('warga') -> where('id_warga', Auth::user() -> id_warga) -> first() -> id_rt;
        // find the id rw of the rt from admin
        $rw_admin = RTModel::find($rt_admin) -> first() -> id_rw;
        $listKritikRW = KritikSaranRW::where('id_rw', $rw_admin) -> get() -> sortBy('created_at');

        return view('kritiksaran.listkritikRW', compact('listKritikRW'));
    }

    public function showKritikRT($id)
    {
        $kritik = KritikSaranRT::find($id);

        return view('kritiksaran.showKritikRT', compact('kritik'));
    }

    public function showKritikRW($id)
    {
        $kritik = KritikSaranRW::find($id);

        return view('kritiksaran.showKritikRW', compact('kritik'));
    }

    public function kritikRWDibaca($id)
    {
        $kritik = KritikSaranRW::find($id);

        $kritik -> status = 'dibaca';
        $kritik -> save();

        return redirect()->route('kritikRW.list') -> with('pesan', 'Kritik dan Saran berhasil dibaca');
    }

    public function kritikRTDibaca($id)
    {
        $kritik = KritikSaranRT::find($id);

        $kritik -> status = 'dibaca';
        $kritik -> save();

        return redirect()->route('kritikRT.list') -> with('pesan', 'Kritik dan Saran berhasil dibaca');
    }

    public function kritikRWSelesai($id)
    {
        $kritik = KritikSaranRW::find($id);

        $kritik -> status = 'selesai';
        $kritik -> save();

        return redirect()->route('kritikRW.list') -> with('pesan', 'Kritik dan Saran berhasil diselesaikan');
    }

    public function kritikRTSelesai($id)
    {
        $kritik = KritikSaranRT::find($id);

        $kritik -> status = 'selesai';
        $kritik -> save();

        return redirect()->route('kritikRT.list') -> with('pesan', 'Kritik dan Saran berhasil diselesaikan');
    }
}
