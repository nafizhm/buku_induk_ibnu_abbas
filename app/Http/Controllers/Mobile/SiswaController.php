<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BeasiswaSiswa;
use App\Models\OrangTua;
use App\Models\PrestasiSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function create()
    {
        return view('mobile.create');
    }

    public function success()
    {
        return view('mobile.success', [
            'title' => 'Pendaftaran Berhasil',
            'description' => 'Data siswa berhasil disimpan. Terima kasih telah melengkapi pendaftaran peserta didik.',
            'primaryLabel' => 'Kembali ke Beranda',
            'primaryUrl' => url('/'),
            'secondaryLabel' => 'Daftar Lagi',
            'secondaryUrl' => route('siswa.daftar.create'),
        ]);
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['orangTua', 'prestasi', 'beasiswa']);
        $ot = $siswa->orangTua;
        $prestasi = $siswa->prestasi->first();
        $beasiswa = $siswa->beasiswa->first();

        $formatDate = fn($d) => $d ? (is_string($d) ? $d : $d->format('Y-m-d')) : null;

        return response()->json([
            'id' => $siswa->id,
            'data' => [
                // Step 1 - Data Pribadi & Kontak
                'nama_lengkap' => $siswa->nama_lengkap,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'nisn' => $siswa->nisn,
                'nik' => $siswa->nik,
                'no_kk' => $siswa->no_kk,
                'tempat_lahir' => $siswa->tempat_lahir,
                'tanggal_lahir' => $formatDate($siswa->tanggal_lahir),
                'no_akta' => $siswa->no_akta,
                'agama' => $siswa->agama,
                'kewarganegaraan' => $siswa->kewarganegaraan,
                'nama_negara' => $siswa->nama_negara,
                'berkebutuhan_khusus' => $siswa->jenis_kebutuhan_khusus ?? ($siswa->berkebutuhan_khusus ? 'Lainnya' : 'Tidak'),
                'alamat_jalan' => $siswa->alamat,
                'rt' => $siswa->rt,
                'rw' => $siswa->rw,
                'nama_dusun' => $siswa->dusun,
                'kelurahan' => $siswa->desa_kelurahan,
                'kecamatan' => $siswa->kecamatan,
                'kode_pos' => $siswa->kode_pos,
                'lintang' => $siswa->lintang,
                'bujur' => $siswa->bujur,
                'tempat_tinggal' => $siswa->status_tempat_tinggal,
                'moda_transportasi' => $siswa->moda_transportasi,
                'anak_ke' => $siswa->anak_ke,
                'pekerjaan' => $siswa->pekerjaan,
                'punya_kip' => $siswa->punya_kip === null ? null : ($siswa->punya_kip ? 'Ya' : 'Tidak'),
                'terima_kip' => $siswa->terima_kip === null ? null : ($siswa->terima_kip ? 'Ya' : 'Tidak'),
                'alasan_tolak_pip' => $siswa->alasan_tolak_pip,
                'telp_rumah' => $siswa->no_telepon_rumah,
                'no_hp' => $siswa->no_hp,
                'email' => $siswa->email,
                // Step 3 - Data Periodik & Kesejahteraan
                'tinggi_badan' => $siswa->tinggi_badan,
                'berat_badan' => $siswa->berat_badan,
                'lingkar_kepala' => $siswa->lingkar_kepala,
                'jarak_tempuh' => $siswa->jarak_tempuh,
                'waktu_jam' => $siswa->waktu_jam,
                'waktu_menit' => $siswa->waktu_menit,
                'jumlah_saudara' => $siswa->jumlah_saudara ?? $siswa->jumlah_saudara_kandung ?? null,
                'jenis_kesejahteraan' => $siswa->jenis_kesejahteraan,
                'no_kartu' => $siswa->no_kartu,
                'nama_di_kartu' => $siswa->nama_di_kartu,
                // Step 4 - Registrasi, Prestasi & Beasiswa
                'kompetensi_keahlian' => $siswa->kompetensi_keahlian,
                'nis' => $siswa->nis,
                'tanggal_masuk' => $formatDate($siswa->tanggal_masuk_sekolah),
                'sekolah_asal' => $siswa->sekolah_asal,
                'no_peserta_un' => $siswa->no_peserta_un,
                'no_seri_ijazah' => $siswa->no_seri_ijazah,
                'no_skhun' => $siswa->no_skhun,
                'keluar_karena' => $siswa->keluar_karena,
                'tanggal_keluar' => $formatDate($siswa->tanggal_keluar),
                'alasan_keluar' => $siswa->alasan_keluar,
                // Step 2 - Orang Tua / Wali
                'ayah_nama' => $ot?->nama_ayah ?? null,
                'ayah_nik' => $ot?->nik_ayah ?? null,
                'ayah_tahun_lahir' => $ot?->tahun_lahir_ayah ?? null,
                'ayah_pendidikan' => $ot?->pendidikan_ayah ?? null,
                'ayah_pekerjaan' => $ot?->pekerjaan_ayah ?? null,
                'ayah_penghasilan' => $ot?->penghasilan_ayah ?? null,
                'ayah_berkebutuhan' => $ot?->berkebutuhan_ayah ?? null,
                'ibu_nama' => $ot?->nama_ibu ?? null,
                'ibu_nik' => $ot?->nik_ibu ?? null,
                'ibu_tahun_lahir' => $ot?->tahun_lahir_ibu ?? null,
                'ibu_pendidikan' => $ot?->pendidikan_ibu ?? null,
                'ibu_pekerjaan' => $ot?->pekerjaan_ibu ?? null,
                'ibu_penghasilan' => $ot?->penghasilan_ibu ?? null,
                'ibu_berkebutuhan' => $ot?->berkebutuhan_ibu ?? null,
                'wali_nama' => $ot?->nama_wali ?? null,
                'wali_nik' => $ot?->nik_wali ?? null,
                'wali_tahun_lahir' => $ot?->tahun_lahir_wali ?? null,
                'wali_pendidikan' => $ot?->pendidikan_wali ?? null,
                'wali_pekerjaan' => $ot?->pekerjaan_wali ?? null,
                'wali_penghasilan' => $ot?->penghasilan_wali ?? null,
                // Prestasi & Beasiswa
                'prestasi_jenis' => $prestasi->jenis ?? null,
                'prestasi_tingkat' => $prestasi->tingkat ?? null,
                'prestasi_nama' => $prestasi->nama ?? null,
                'prestasi_tahun' => $prestasi->tahun ?? null,
                'prestasi_penyelenggara' => $prestasi->penyelenggara ?? null,
                'beasiswa_jenis' => $beasiswa->jenis ?? null,
                'beasiswa_keterangan' => $beasiswa->keterangan ?? null,
                'beasiswa_tahun_mulai' => $beasiswa->tahun_mulai ?? null,
                'beasiswa_tahun_selesai' => $beasiswa->tahun_selesai ?? null,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $siswa = Siswa::create($this->mapSiswa($data) + ['status_siswa' => 'Draft']);
        $this->syncOrangTua($siswa, $data);
        $this->syncPrestasi($siswa, $data);
        $this->syncBeasiswa($siswa, $data);

        return response()->json(['id' => $siswa->id, 'step' => 1]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $rules = array_intersect_key($this->rules(), $request->all());
        $data = $request->validate($rules);

        $siswa->update($this->mapSiswa($data, array_keys($data)));
        $this->syncOrangTua($siswa, $data);
        $this->syncPrestasi($siswa, $data);
        $this->syncBeasiswa($siswa, $data);

        if ($request->boolean('_selesai')) {
            $siswa->update(['status_siswa' => 'Aktif']);
        }

        return response()->json(['id' => $siswa->id, 'ok' => true]);
    }

    private function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nisn' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:16',
            'no_kk' => 'nullable|string|max:16',
            'no_akta' => 'nullable|string|max:100',
            'agama' => 'nullable|string|max:50',
            'kewarganegaraan' => 'nullable|string|max:10',
            'nama_negara' => 'nullable|string|max:100',
            'berkebutuhan_khusus' => 'nullable|string|max:50',
            'alamat_jalan' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'nama_dusun' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'lintang' => 'nullable|numeric',
            'bujur' => 'nullable|numeric',
            'tempat_tinggal' => 'nullable|string|max:30',
            'moda_transportasi' => 'nullable|string|max:100',
            'anak_ke' => 'nullable|integer|min:1',
            'pekerjaan' => 'nullable|string|max:100',
            'telp_rumah' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:191',
            'jarak_sekolah' => 'nullable|numeric|min:0',
            'jarak_tempuh' => 'nullable|numeric',
            'waktu_jam' => 'nullable|integer|min:0',
            'waktu_menit' => 'nullable|integer|min:0|max:59',
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',
            'lingkar_kepala' => 'nullable|numeric',
            'punya_kip' => 'nullable|in:Ya,Tidak',
            'terima_kip' => 'nullable|in:Ya,Tidak',
            'alasan_tolak_pip' => 'nullable|string|max:100',
            'jenis_kesejahteraan' => 'nullable|string|max:50',
            'no_kartu' => 'nullable|string|max:100',
            'nama_di_kartu' => 'nullable|string|max:191',
            'jumlah_saudara' => 'nullable|integer|min:0',
            'kompetensi_keahlian' => 'nullable|string|max:100',
            'jenis_pendaftaran' => 'nullable|string|max:50',
            'nis' => 'nullable|string|max:50',
            'tanggal_masuk' => 'nullable|date',
            'sekolah_asal' => 'nullable|string|max:191',
            'no_peserta_un' => 'nullable|string|max:100',
            'no_seri_ijazah' => 'nullable|string|max:100',
            'no_skhun' => 'nullable|string|max:100',
            'keluar_karena' => 'nullable|string|max:50',
            'tanggal_keluar' => 'nullable|date',
            'alasan_keluar' => 'nullable|string',
            'ayah_nama' => 'nullable|string|max:200',
            'ayah_nik' => 'nullable|string|max:16',
            'ayah_tahun_lahir' => 'nullable|integer|min:1900|max:2099',
            'ayah_pendidikan' => 'nullable|string|max:50',
            'ayah_pekerjaan' => 'nullable|string|max:100',
            'ayah_penghasilan' => 'nullable|string|max:50',
            'ayah_berkebutuhan' => 'nullable|string|max:50',
            'ibu_nama' => 'nullable|string|max:200',
            'ibu_nik' => 'nullable|string|max:16',
            'ibu_tahun_lahir' => 'nullable|integer|min:1900|max:2099',
            'ibu_pendidikan' => 'nullable|string|max:50',
            'ibu_pekerjaan' => 'nullable|string|max:100',
            'ibu_penghasilan' => 'nullable|string|max:50',
            'ibu_berkebutuhan' => 'nullable|string|max:50',
            'wali_nama' => 'nullable|string|max:200',
            'wali_nik' => 'nullable|string|max:16',
            'wali_tahun_lahir' => 'nullable|integer|min:1900|max:2099',
            'wali_pendidikan' => 'nullable|string|max:50',
            'wali_pekerjaan' => 'nullable|string|max:100',
            'wali_penghasilan' => 'nullable|string|max:50',
            'prestasi_jenis' => 'nullable|string|max:100',
            'prestasi_tingkat' => 'nullable|string|max:50',
            'prestasi_nama' => 'nullable|string|max:191',
            'prestasi_tahun' => 'nullable|integer|min:1900|max:2099',
            'prestasi_penyelenggara' => 'nullable|string|max:191',
            'beasiswa_jenis' => 'nullable|string|max:100',
            'beasiswa_keterangan' => 'nullable|string',
            'beasiswa_tahun_mulai' => 'nullable|integer|min:1900|max:2099',
            'beasiswa_tahun_selesai' => 'nullable|integer|min:1900|max:2099',
        ];
    }

    private function mapSiswa(array $d, ?array $keys = null): array
    {
        $has = fn($k) => $keys === null || array_key_exists($k, $d);
        $v = fn($k) => $d[$k] ?? null;
        $out = [];

        if ($has('nama_lengkap')) $out['nama_lengkap'] = $v('nama_lengkap');
        if ($has('jenis_kelamin')) $out['jenis_kelamin'] = $v('jenis_kelamin');
        if ($has('tempat_lahir')) $out['tempat_lahir'] = $v('tempat_lahir');
        if ($has('tanggal_lahir')) $out['tanggal_lahir'] = $v('tanggal_lahir');
        if ($has('nisn')) $out['nisn'] = $v('nisn');
        if ($has('nik')) $out['nik'] = $v('nik');
        if ($has('no_kk')) $out['no_kk'] = $v('no_kk');
        if ($has('no_akta')) $out['no_akta'] = $v('no_akta');
        if ($has('agama')) $out['agama'] = $v('agama');
        if ($has('alamat_jalan')) $out['alamat'] = $v('alamat_jalan');
        if ($has('rt')) $out['rt'] = $v('rt');
        if ($has('rw')) $out['rw'] = $v('rw');
        if ($has('nama_dusun')) $out['dusun'] = $v('nama_dusun');
        if ($has('kelurahan')) $out['desa_kelurahan'] = $v('kelurahan');
        if ($has('kecamatan')) $out['kecamatan'] = $v('kecamatan');
        if ($has('kode_pos')) $out['kode_pos'] = $v('kode_pos');
        if ($has('lintang')) $out['lintang'] = $v('lintang');
        if ($has('bujur')) $out['bujur'] = $v('bujur');
        if ($has('tempat_tinggal')) $out['status_tempat_tinggal'] = $v('tempat_tinggal');
        if ($has('moda_transportasi')) $out['moda_transportasi'] = $v('moda_transportasi');
        if ($has('anak_ke')) $out['anak_ke'] = $v('anak_ke');
        if ($has('jumlah_saudara')) $out['jumlah_saudara'] = $v('jumlah_saudara');
        if ($has('pekerjaan')) $out['pekerjaan'] = $v('pekerjaan');
        if ($has('telp_rumah')) $out['no_telepon_rumah'] = $v('telp_rumah');
        if ($has('no_hp')) $out['no_hp'] = $v('no_hp');
        if ($has('email')) $out['email'] = $v('email');
        if ($has('jarak_sekolah')) $out['jarak_sekolah'] = $v('jarak_sekolah');
        if ($has('jarak_tempuh')) $out['jarak_tempuh'] = $v('jarak_tempuh');
        if ($has('waktu_jam')) $out['waktu_jam'] = $v('waktu_jam');
        if ($has('waktu_menit')) $out['waktu_menit'] = $v('waktu_menit');
        if ($has('tinggi_badan')) $out['tinggi_badan'] = $v('tinggi_badan');
        if ($has('berat_badan')) $out['berat_badan'] = $v('berat_badan');
        if ($has('lingkar_kepala')) $out['lingkar_kepala'] = $v('lingkar_kepala');
        if ($has('alasan_tolak_pip')) $out['alasan_tolak_pip'] = $v('alasan_tolak_pip');
        if ($has('jenis_kesejahteraan')) $out['jenis_kesejahteraan'] = $v('jenis_kesejahteraan');
        if ($has('no_kartu')) $out['no_kartu'] = $v('no_kartu');
        if ($has('nama_di_kartu')) $out['nama_di_kartu'] = $v('nama_di_kartu');
        if ($has('kompetensi_keahlian')) $out['kompetensi_keahlian'] = $v('kompetensi_keahlian');
        if ($has('jenis_pendaftaran')) $out['jenis_pendaftaran'] = $v('jenis_pendaftaran');
        if ($has('nis')) $out['nis'] = $v('nis');
        if ($has('sekolah_asal')) $out['sekolah_asal'] = $v('sekolah_asal');
        if ($has('no_peserta_un')) $out['no_peserta_un'] = $v('no_peserta_un');
        if ($has('no_seri_ijazah')) $out['no_seri_ijazah'] = $v('no_seri_ijazah');
        if ($has('no_skhun')) $out['no_skhun'] = $v('no_skhun');
        if ($has('keluar_karena')) $out['keluar_karena'] = $v('keluar_karena');
        if ($has('tanggal_keluar')) $out['tanggal_keluar'] = $v('tanggal_keluar');
        if ($has('alasan_keluar')) $out['alasan_keluar'] = $v('alasan_keluar');

        if ($has('kewarganegaraan')) {
            $out['kewarganegaraan'] = $v('kewarganegaraan');
            $out['nama_negara'] = $v('kewarganegaraan') === 'WNA' ? $v('nama_negara') : null;
        }
        if ($has('berkebutuhan_khusus')) {
            $bk = $v('berkebutuhan_khusus');
            $out['jenis_kebutuhan_khusus'] = $bk;
            $out['berkebutuhan_khusus'] = $bk && $bk !== 'Tidak';
        }
        if ($has('punya_kip')) $out['punya_kip'] = isset($d['punya_kip']) ? $d['punya_kip'] === 'Ya' : null;
        if ($has('terima_kip')) $out['terima_kip'] = isset($d['terima_kip']) ? $d['terima_kip'] === 'Ya' : null;
        if ($has('tanggal_masuk')) $out['tanggal_masuk_sekolah'] = $v('tanggal_masuk');

        return $out;
    }

    private function syncOrangTua(Siswa $siswa, array $d): void
    {
        // Step 2 mengirim banyak field ayah/ibu/wali; jangan hanya cek nama,
        // cek apakah ada field ayah_*/ibu_*/wali_* yang terisi agar data tidak hilang saat refresh
        $familyKeys = array_filter(array_keys($d), fn($k) => str_starts_with($k, 'ayah_') || str_starts_with($k, 'ibu_') || str_starts_with($k, 'wali_'));
        $hasValue = false;
        foreach ($familyKeys as $k) {
            if (isset($d[$k]) && $d[$k] !== '' && $d[$k] !== null) { $hasValue = true; break; }
        }
        if (!$hasValue) {
            return;
        }

        OrangTua::updateOrCreate(['siswa_id' => $siswa->id], [
            'nama_ayah' => $d['ayah_nama'] ?? null,
            'nik_ayah' => $d['ayah_nik'] ?? null,
            'tahun_lahir_ayah' => $d['ayah_tahun_lahir'] ?? null,
            'pendidikan_ayah' => $d['ayah_pendidikan'] ?? null,
            'pekerjaan_ayah' => $d['ayah_pekerjaan'] ?? null,
            'penghasilan_ayah' => $d['ayah_penghasilan'] ?? null,
            'berkebutuhan_ayah' => $d['ayah_berkebutuhan'] ?? null,
            'nama_ibu' => $d['ibu_nama'] ?? null,
            'nik_ibu' => $d['ibu_nik'] ?? null,
            'tahun_lahir_ibu' => $d['ibu_tahun_lahir'] ?? null,
            'pendidikan_ibu' => $d['ibu_pendidikan'] ?? null,
            'pekerjaan_ibu' => $d['ibu_pekerjaan'] ?? null,
            'penghasilan_ibu' => $d['ibu_penghasilan'] ?? null,
            'berkebutuhan_ibu' => $d['ibu_berkebutuhan'] ?? null,
            'nama_wali' => $d['wali_nama'] ?? null,
            'nik_wali' => $d['wali_nik'] ?? null,
            'tahun_lahir_wali' => $d['wali_tahun_lahir'] ?? null,
            'pendidikan_wali' => $d['wali_pendidikan'] ?? null,
            'pekerjaan_wali' => $d['wali_pekerjaan'] ?? null,
            'penghasilan_wali' => $d['wali_penghasilan'] ?? null,
        ]);
    }

    private function syncPrestasi(Siswa $siswa, array $d): void
    {
        if (empty($d['prestasi_nama'])) {
            return;
        }

        PrestasiSiswa::updateOrCreate(['siswa_id' => $siswa->id], [
            'jenis' => $d['prestasi_jenis'] ?? null,
            'tingkat' => $d['prestasi_tingkat'] ?? null,
            'nama' => $d['prestasi_nama'],
            'tahun' => $d['prestasi_tahun'] ?? null,
            'penyelenggara' => $d['prestasi_penyelenggara'] ?? null,
        ]);
    }

    private function syncBeasiswa(Siswa $siswa, array $d): void
    {
        if (empty($d['beasiswa_jenis'])) {
            return;
        }

        BeasiswaSiswa::updateOrCreate(['siswa_id' => $siswa->id], [
            'jenis' => $d['beasiswa_jenis'],
            'keterangan' => $d['beasiswa_keterangan'] ?? null,
            'tahun_mulai' => $d['beasiswa_tahun_mulai'] ?? null,
            'tahun_selesai' => $d['beasiswa_tahun_selesai'] ?? null,
        ]);
    }
}
