<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\kegiatan;
use App\Models\Admin\pembimbing;
use App\Models\Admin\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    public function kegiatan($id, $id_siswa)
    {
        $loginGuru = Auth::guard('guru')->user()->id_guru;

        $siswa = Siswa::find($id_siswa);

        if (!$siswa || !$siswa->id_pembimbing) {
            return back()->withErrors(['access' => 'Siswa tidak ditemukan atau tidak memiliki pembimbing.']);
        }

        if ($siswa->id_pembimbing != $id) {
            return back()->withErrors(['access' => 'Pembimbig tidak sesuai']);
        }

        $pembimbing = pembimbing::find($id);

        if (!$pembimbing || $pembimbing->id_guru !== $loginGuru) {
            return back()->withErrors(['access' => 'Akses Anda di tolak. Siswa ini tidak dibimbing oleh Anda']);
        }

        $kegiatans = kegiatan::where('id_siswa', $id_siswa)->get();
        $kegiatan = kegiatan::where('id_siswa', $id_siswa)->first();
        $id_pembimbing = $id;
        return view('guru.kegiatan', compact('id', 'kegiatans', 'kegiatan'));
    }

     public function detail($id, $id_siswa, $id_kegiatan)
    {
        $loginGuru = Auth::guard('guru')->user()->id_guru;

        $siswa = Siswa::find($id_siswa);

        if (!$siswa || !$siswa->id_pembimbing) {
            return back()->withErrors(['access' => 'Siswa tidak ditemukan atau tidak memiliki pembimbing.']);
        }

        if ($siswa->id_pembimbing != $id) {
            return back()->withErrors(['access' => 'Pembimbig tidak sesuai']);
        }

        $pembimbing = pembimbing::find($id);

        if (!$pembimbing || $pembimbing->id_guru !== $loginGuru) {
            return back()->withErrors(['access' => 'Akses Anda di tolak. Siswa ini tidak dibimbing oleh Anda']);
        }

        $kegiatan = kegiatan::where('id_siswa', $id_siswa)
             ->where('id_kegiatan', $id_kegiatan)
             ->first();
        if (!$kegiatan) {
            return back()->withErrors(['acces' => 'Kegiatan tidak ditemukan']);
        }

        $nama_siswa = Siswa::where('id_siswa', $id_siswa)->first();
        return view('guru.detail', compact( 'id', 'kegiatan', 'nama_siswa'));


    }}

