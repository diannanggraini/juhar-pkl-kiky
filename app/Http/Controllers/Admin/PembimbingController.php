<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\dudi;
use App\Models\Admin\Guru;
use App\Models\Admin\pembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembimbingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pembimbing()
    {
        $pembimbings = pembimbing::with('guru', 'dudi')->get();
        return view('admin.pembimbing', compact('pembimbings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = Guru::all();
        $dudis = DUDI::all();
        return view('admin.tambah_pembimbing', compact('gurus', 'dudis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required',
            'id_dudi' => 'required',
        ]);

        pembimbing::create([
            'id_guru' => $request->id_guru,
            'id_dudi' => $request->id_dudi,
        ]);

        return redirect()->route('admin.pembimbing')->with('success', 'Data Pembimbing Berhasil di Tambah.');
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
        $pembimbing = pembimbing::find($id);
        $gurus = Guru::with('pembimbingGuru')->get();
        $dudis = dudi::with('pembimbingDudi')->get();
        return view('admin.edit_pembimbing', compact('pembimbing', 'gururs', 'dudis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pembimbing = pembimbing::find($id);

        $request->validate([

            'id_guru' => 'required',
            'id_dudi' => 'required',
        ]);


        $pembimbing->update([
            'id_guru' => $request->id_guru,
            'id_dudi' => $request->id_dudi,
        ]);

        return redirect()->route('admin.pembimbing')->with('success', "Data Pembimbing Berhasil di Edit");
    }

    public function delete($id)
    {
        $pembimbing = pembimbing::find($id);

        $pembimbing->delete();

        return redirect()->back()->with('success', 'Data Pembimbing Berhasil di Hapus.');
    }

    public function pembimbingGuru()
    {
        $guru = Auth::guard('guru')->user();
        $pembimbings = pembimbing::where('id_guru', $guru->id_guru)->get();
        return view('guru.pembimbing', compact('pembimbings'));
    }
}
