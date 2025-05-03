<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IuranRT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiIuranRTController extends Controller
{
    public function index()
    {
        try {
            $id_rt = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->value('id_rt');

            $iuran = IuranRT::where('id_rt', $id_rt)->get();

            return response()->json([
                'success' => true,
                'data' => $iuran
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $id_rt = DB::table('warga')->where('id_warga', Auth::user()->id_warga)->value('id_rt');

            $request->validate([
                'bulan' => 'required',
                'jenis_iuran' => 'required|in:bulanan,tambahan',
                'nama_iuran' => 'required|string',
                'total_iuran' => 'required|numeric',
            ]);

            $iuran = IuranRT::create([
                'id_rt' => $id_rt,
                'bulan' => $request->bulan,
                'jenis_iuran' => $request->jenis_iuran,
                'nama_iuran' => $request->nama_iuran,
                'total_iuran' => $request->total_iuran
            ]);

            return response()->json(['success' => true, 'data' => $iuran], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $iuran = IuranRT::findOrFail($id);
            return response()->json(['success' => true, 'data' => $iuran]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'bulan' => 'required',
                'jenis_iuran' => 'required|in:bulanan,tambahan',
                'nama_iuran' => 'required|string',
                'total_iuran' => 'required|numeric',
            ]);

            $iuran = IuranRT::findOrFail($id);
            $iuran->update($request->only('bulan', 'jenis_iuran', 'nama_iuran', 'total_iuran'));

            return response()->json(['success' => true, 'message' => "Iuran berhasil diupdate"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $iuran = IuranRT::findOrFail($id);
            $iuran->delete();

            return response()->json(['success' => true, 'message' => "Iuran berhasil dihapus"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan atau gagal dihapus'], 500);
        }
    }
}
