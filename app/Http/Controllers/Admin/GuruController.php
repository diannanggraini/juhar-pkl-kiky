<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Unique;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function guru()
    {
        $gurus = Guru::all();
        return view('admin.guru', compact('gurus'));
    }

    public function profile(Request $request)
    {
        $profile = Auth::guard('guru')->user();
        return view('guru.profile', compact('profile'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tambah_guru');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|unique:guru,nip|digits:18',
            'email' => 'required|email|unique:guru,email',
            'password' => 'required|min:6',
            'nama_guru' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $uniqueFile = uniqid() . '_' . $request->file('foto')->getClientOriginalName();

            $foto = $request->file('foto')->storeAs('foto_guru', $uniqueFile, 'public');

            $foto = 'foto_guru/' . $uniqueFile;
        }

        Guru::create([
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nama_guru' => $request->nama_guru,
            'foto' => $foto,
        ]);

        return redirect()->route('admin.guru')->with('success ', 'Data Guru Berhasil di Tambahkan.');
    }

    public function delete(Request $request, $id)
    {
       $guru = Guru::find($id);

       if ($guru->foto) {
        $foto =  $guru->foto;

        if (Storage::disk('public')->exists($foto)) {
         Storage::disk('public')->delete($foto);
        }

       }
       $guru->delete();

       return redirect()->route('admin.guru')->with('success', 'Data Guru Berhasil di Hapus');
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
        $guru = Guru::find($id);
        return view('admin.edit_guru', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::find($id);

        $request->validate([
            'nip' => 'nullable|digits:18|unique:guru,nip,' . $guru->id_guru .',id_guru',
            'email' => 'required|email|unique:guru,email,' .$guru->id_guru .',id_guru',
            'password' => 'nullable|min:6',
            'nama_guru' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $foto = $guru->foto;
        if ($request->hasFile('foto')) {
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }
            $uniqueFile = uniqid() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('foto_guru', $uniqueFile, 'public');
            $foto = 'foto_guru/' . $uniqueFile;
        }

        $guru->update([
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $guru->password,
            'nama_guru' => $request->nama_guru,
            'foto' => $foto,
        ]);

        return redirect()->route('admin.guru')->with('success', 'Data Guru Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function dashboard()
    {
        return view('guru.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('guru.login');
    }

    public function updateGuru(Request $request)
    {
        $id_guru = Auth::guard('guru')->user()->id_guru;
        $guru = Guru::find($id_guru);

        $request->validate([
            'email' => 'required|email|unique:guru,email,' .$guru->id_guru .',id_guru',
            'password' => 'nullable|min:6',
            'nama_guru' => 'required',
            'foto'=> 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        $foto = $guru->foto;
        if ($request->hasFile('foto')) {
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }
            $uniqueFile = uniqid() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('foto_guru', $uniqueFile, 'public');
            $foto = 'foto_guru/' . $uniqueFile;
        }
        $guru->update([
            'email' =>$request->email,
            'password' =>$request->filled('password') ? Hash::make($request->password) : $guru->password,
            'nama_guru' =>$request->nama_guru,
            'foto' =>$foto,
        ]);

        return redirect()->back()->with('success', 'Data Anda Berhasil di Update.');
    }
}
