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
        if (!array_filter([
            $d['ayah_nama'] ?? null, $d['ibu_nama'] ?? null, $d['wali_nama'] ?? null,
        ])) {
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
