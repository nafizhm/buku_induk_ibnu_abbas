<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\AkunSiswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('mobile.register', compact('kelas'));
    }

    public function getSiswa(Kelas $kelas): JsonResponse
    {
        $siswa = $kelas->siswa()
            ->where('status_siswa', 'Aktif')
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap']);

        return response()->json(['data' => $siswa]);
    }

    public function success(): View
    {
        return view('mobile.success', [
            'title' => 'Akun Berhasil Dibuat',
            'description' => 'Akun orang tua kamu sudah aktif. Gunakan nomor telepon sebagai username untuk masuk.',
            'primaryLabel' => 'Masuk Sekarang',
            'primaryUrl' => route('orang-tua.beranda'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'peran' => ['required', 'in:ayah,ibu,wali'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id_kelas'],
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['required', 'integer'],
            'phone' => [
                'required',
                'numeric',
                'digits_between:10,15',
                'unique:users,username',
            ],
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'phone.numeric' => 'Nomor telepon hanya boleh angka.',
            'phone.digits_between' => 'Nomor telepon harus 10-15 digit.',
            'siswa_ids.required' => 'Pilih minimal satu anak.',
        ]);

        $siswaIds = Kelas::findOrFail($request->kelas_id)
            ->siswa()
            ->where('status_siswa', 'Aktif')
            ->whereIn('id', $request->siswa_ids)
            ->pluck('id');

        if ($siswaIds->isEmpty()) {
            return back()->withErrors(['siswa_ids' => 'Siswa tidak ditemukan di kelas yang dipilih.'])->withInput();
        }

        $roleId = DB::table('role')->where('role', 'Orang Tua')->value('id');

        $user = (new User())->forceFill([
            'nama' => "Orang Tua ({$request->phone})",
            'username' => $request->phone,
            'email' => "{$request->phone}@ortu.local",
            'password' => $request->password,
            'status' => 'aktif',
            'id_role' => $roleId,
        ]);

        $user->save();

        foreach ($siswaIds as $siswaId) {
            AkunSiswa::create([
                'user_id' => $user->id,
                'siswa_id' => $siswaId,
                'hubungan' => $request->peran,
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('mobile.register.success');
    }
}
