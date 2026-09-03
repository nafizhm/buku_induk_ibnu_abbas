<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PresensiKegiatan;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::query()
            ->withCount([
                'presensi as jumlah_undangan',
                'presensi as jumlah_hadir_ayah' => fn ($q) => $q->whereNotNull('jam_kehadiran_ayah'),
                'presensi as jumlah_hadir_ibu' => fn ($q) => $q->whereNotNull('jam_kehadiran_ibu'),
                'presensi as jumlah_hadir_keduanya' => fn ($q) => $q->whereNotNull('jam_kehadiran_ayah')->whereNotNull('jam_kehadiran_ibu'),
            ])->latest('tgl_kegiatan')->latest('id')->get();

        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        [$kegiatan, $created] = DB::transaction(function () use ($data) {
            $kegiatan = Kegiatan::create($data);
            return [$kegiatan, $this->generateUndangan($kegiatan)];
        });

        return back()->with('success', "Data kegiatan berhasil ditambahkan. {$created} undangan presensi dibuat.");
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->loadCount([
            'presensi as jumlah_undangan',
            'presensi as jumlah_kehadiran' => fn ($q) => $q->whereNotNull('jam_kehadiran'),
            'presensi as jumlah_hadir_ayah' => fn ($q) => $q->whereNotNull('jam_kehadiran_ayah'),
            'presensi as jumlah_hadir_ibu' => fn ($q) => $q->whereNotNull('jam_kehadiran_ibu'),
            'presensi as jumlah_hadir_keduanya' => fn ($q) => $q->whereNotNull('jam_kehadiran_ayah')->whereNotNull('jam_kehadiran_ibu'),
        ]);
        $peserta = $kegiatan->presensi()->with('siswa.kelas')->get()->sortBy('siswa.nama_lengkap');

        return view('admin.kegiatan.show', compact('kegiatan', 'peserta'));
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $data = $this->validated($request);
        $created = DB::transaction(function () use ($kegiatan, $data) {
            $kegiatan->update($data);
            return $this->generateUndangan($kegiatan);
        });

        return back()->with('success', "Data kegiatan berhasil diperbarui. {$created} undangan presensi baru dibuat.");
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Data kegiatan berhasil dihapus.');
    }

    public function export(Kegiatan $kegiatan)
    {
        $peserta = $kegiatan->presensi()->with('siswa.kelas')->get()->sortBy('siswa.nama_lengkap');
        $escape = fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $html = '<html><head><meta charset="UTF-8"><style>td{mso-number-format:"\\@"}th{background:#d9eaf7}</style></head><body><h3>'.$escape($kegiatan->nama_kegiatan).'</h3><table border="1"><tr><th>No</th><th>NIS</th><th>Nama Siswa</th><th>Kelas</th><th>QR Code</th><th>Jenis</th><th>Jam Ayah</th><th>Jam Ibu</th><th>Status Hadir</th></tr>';
        foreach ($peserta as $i => $row) {
            $html .= '<tr><td>'.($i + 1).'</td><td>'.$escape($row->siswa?->nipd).'</td><td>'.$escape($row->siswa?->nama_lengkap ?? 'Siswa sudah dihapus').'</td><td>'.$escape($row->siswa?->kelas?->nama_kelas).'</td><td>'.$escape($row->qr_code).'</td><td>'.$escape($row->jenis).'</td><td>'.$escape($row->jam_kehadiran_ayah).'</td><td>'.$escape($row->jam_kehadiran_ibu).'</td><td>'.$escape($this->statusHadir($row)).'</td></tr>';
        }
        $html .= '</table></body></html>';
        return response($html)->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="kehadiran-'.str($kegiatan->nama_kegiatan)->slug().'.xls"');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tgl_kegiatan' => ['required', 'date_format:Y-m-d'],
            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'zona_waktu' => ['required', Rule::in(['WIB', 'WITA', 'WIT'])],
            'status' => ['required', Rule::in(['aktif', 'non aktif'])],
        ]);
    }

    private function generateUndangan(Kegiatan $kegiatan): int
    {
        $created = 0;
        foreach (Siswa::where('status_siswa', 'Aktif')->pluck('id') as $siswaId) {
            if ($kegiatan->presensi()->where('siswa_id', $siswaId)->exists()) continue;
            do { $qr = str()->random(15); } while (PresensiKegiatan::where('qr_code', $qr)->exists());
            $kegiatan->presensi()->create(['siswa_id' => $siswaId, 'qr_code' => $qr, 'jenis' => 'undangan']);
            $created++;
        }
        return $created;
    }

    private function statusHadir(PresensiKegiatan $row): string
    {
        if ($row->jam_kehadiran_ayah && $row->jam_kehadiran_ibu) return 'Keduanya';
        if ($row->jam_kehadiran_ayah) return 'Ayah';
        if ($row->jam_kehadiran_ibu) return 'Ibu';
        return $row->jam_kehadiran ? 'Hadir' : '-';
    }
}
