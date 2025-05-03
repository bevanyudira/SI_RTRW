<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warga;
use Illuminate\Support\Facades\DB; // Impor D
use Illuminate\Support\Facades\Hash; // Impor Hash
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id_warga', 'asc')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.addUser');
    }

    public function requestCreate()
    {
        $roleList = ['Warga', 'Admin_RW', 'Admin_RT', 'Ketua_RW', 'Ketua_RT', 'Super_Admin'];
        return view('users.requestAddUser', compact('roleList'));
    }

    public function requestStore(Request $request)
    {

        $this->validate($request, [
            'nik' => 'required|numeric|min_digits:15',
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if (DB::table('warga')->where('id_warga', $request->nik)->first()) {
            $users = new User();
            $users->id_warga = $request->nik;
            $users->email = $request->email;
            $users->no_hp = $request->no_hp;
            $users->password = Hash::make($request->password);
            $users->role = $request->input('role');
            $users->save();

            return redirect('/masuk')->with('success', 'Akun pengguna berhasil dibuat');
        } else {
            return redirect('/masuk')->with('error', 'Akun pengguna gagal dibuat. NIK pada table warga tidak ditemukan');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required|numeric|min_digits:15',
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required',
            'role' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if (DB::table('warga')->where('id_warga', $request->nik)->first()) {
            $users = new User();
            $users->id_warga = $request->nik;
            $users->email = $request->email;
            $users->no_hp = $request->no_hp;
            $users->password = Hash::make($request->password);
            $users->save();

            return redirect('/account')->with('success', 'Akun pengguna berhasil dibuat');
        } else {
            return redirect('/account')->with('error', 'Akun pengguna gagal dibuat. NIK pada table warga tidak ditemukan');
        }
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
        $user = User::find(id: $id);
        $roleList = ['Warga', 'Admin_RW', 'Admin_RT', 'Ketua_RW', 'Ketua_RT', 'Super_Admin'];
        return view('users.edit', compact('user', 'roleList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required|min:10',
            'aktivasi' => 'required',
        ]);

        $user = User::find($id);
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->aktivasi = $request->input('aktivasi');
        $user->save();

        return redirect()->route('account.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('account.index')->with('pesan', "Data pengguna dengan id {$id} telah berhasil dihapus");
    }
}
