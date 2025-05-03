<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ApiForgotPasswordController extends Controller
{
    /**
     * Validasi email dan NIK untuk reset password
     */
    public function validateUser(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'nik' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari user berdasarkan email
            $user = User::where('email', $request->email)->first();

            if (!$user || $user->id_warga != $request->nik) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau NIK tidak cocok'
                ], 404);
            }

            // Return data user
            return response()->json([
                'success' => true,
                'message' => 'Data cocok, lanjutkan ke reset password',
                'data' => [
                    'user_id' => $user->id
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password untuk user yang sudah terverifikasi
     */
    public function resetPassword(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:pengguna,id',  // Menggunakan tabel 'pengguna' dan kolom 'id'
                'new_password' => 'required|min:6|confirmed' // Validasi untuk password baru
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Ambil user berdasarkan ID
            $user = User::find($request->user_id);  // Menggunakan 'find' karena kita mencari berdasarkan ID
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mereset password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
