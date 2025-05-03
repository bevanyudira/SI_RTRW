<?php

namespace App\Http\Controllers;

use App\Models\Penjabat_RW;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PenjabatRWController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rw.RegisterPenjabat');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request -> validate([
            'nama' => 'required|min:5',
            'email' => 'required|email:rfc,dns',
            'no_hp' => 'required',
            'username' => 'required|min:5|unique:penjabat_rt,username',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $penjabat = new Penjabat_RW();
        $penjabat -> nama = $request -> nama;
        $penjabat -> email = $request -> email;
        $penjabat -> no_hp = $request -> no_hp;
        $penjabat -> username = $request -> username;
        $penjabat -> password = Hash::make($request -> password);
        $penjabat -> role = $request -> input('Role');
        $penjabat -> save();

        return redirect('/hasil');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
