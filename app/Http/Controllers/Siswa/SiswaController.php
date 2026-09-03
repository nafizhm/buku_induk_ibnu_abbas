<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\LampiranSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    private const DOKUMEN = ['foto_siswa','kartu_keluarga','akta_kelahiran','ktp_ayah','ktp_ibu','ktp_wali','ijazah_sebelumnya','rapor_sebelumnya','surat_pindah'];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Siswa::with('kelas')->latest('id'))->addIndexColumn()
                ->editColumn('jenis_kelamin', fn (Siswa $s) => $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan')
                ->addColumn('kelas_sekarang', fn (Siswa $s) => $s->kelas?->nama_kelas ?? '-')
                ->editColumn('status_siswa', fn (Siswa $s) => $s->status_siswa ?? '-')
                ->addColumn('action', fn (Siswa $s) => '<div class="d-flex justify-content-center gap-1"><a href="'.route('siswa.download-one', $s).'" class="btn btn-sm btn-success" title="Download Excel"><i class="fas fa-file-excel"></i> Download</a><a href="'.route('siswa.edit', $s->id).'" class="btn btn-sm btn-primary">Edit</a><button type="button" class="btn btn-sm btn-danger btn-delete" data-id="'.$s->id.'">Hapus</button></div>')
                ->rawColumns(['action'])->make(true);
        }
        return view('admin.siswa.index', ['kelas' => Kelas::where('status','aktif')->orderBy('id_kelas')->get()]);
    }

    public function create()
    {
        return view('admin.siswa.create', ['siswa' => new Siswa(), 'kelas' => Kelas::where('status','aktif')->orderBy('id_kelas')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $siswa = Siswa::create($this->validatedData($request));
            $this->saveRelatedData($request, $siswa);
        });
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => Siswa::findOrFail($id)]);
    }

    public function edit(int $id)
    {
        return view('admin.siswa.edit', ['siswa' => Siswa::with(['orangTua','lampiran'])->findOrFail($id), 'kelas' => Kelas::orderBy('id_kelas')->get()]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $siswa = Siswa::findOrFail($id);
        DB::transaction(function () use ($request, $siswa, $id) {
            $siswa->update($this->validatedData($request, $id));
            $this->saveRelatedData($request, $siswa);
        });
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(int $id): JsonResponse
    {
        $siswa = Siswa::findOrFail($id);
        Storage::disk('public')->deleteDirectory('lampiran-siswa/'.$siswa->id);
        $siswa->delete();
        return response()->json(['status' => 'success', 'message' => 'Data siswa berhasil dihapus.']);
    }

    public function viewLampiran(LampiranSiswa $lampiran)
    {
        abort_unless(Storage::disk('public')->exists($lampiran->path), 404);

        return response()->file(Storage::disk('public')->path($lampiran->path), [
            'Content-Type' => $lampiran->mime_type ?: 'application/octet-stream',
        ]);
    }

    public function uploadLampiran(Request $request, Siswa $siswa): JsonResponse
    {
        $data = $request->validate([
            'jenis_dokumen' => ['required', Rule::in(self::DOKUMEN)],
            'file' => ['required','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);
        $file = $data['file'];
        $old = $siswa->lampiran()->where('jenis_dokumen', $data['jenis_dokumen'])->first();
        $path = $file->store('lampiran-siswa/'.$siswa->id, 'public');
        if ($old) Storage::disk('public')->delete($old->path);
        $lampiran = $siswa->lampiran()->updateOrCreate(['jenis_dokumen'=>$data['jenis_dokumen']], [
            'path'=>$path, 'nama_asli'=>$file->getClientOriginalName(), 'mime_type'=>$file->getMimeType(), 'ukuran'=>$file->getSize(),
        ]);

        return response()->json(['message'=>'Lampiran berhasil diunggah.','view_url'=>route('siswa.lampiran.view',$lampiran)]);
    }

    public function deleteLampiran(LampiranSiswa $lampiran): JsonResponse
    {
        Storage::disk('public')->delete($lampiran->path);
        $lampiran->delete();

        return response()->json(['message' => 'Lampiran berhasil dihapus.']);
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'nipd' => ['nullable', 'string', 'max:20', Rule::unique('siswa', 'nipd')->ignore($id)],
            'nisn' => ['nullable', 'string', 'max:25', Rule::unique('siswa', 'nisn')->ignore($id)],
            'nik' => ['nullable', 'string', 'max:16', Rule::unique('siswa', 'nik')->ignore($id)],
            'no_kk' => ['nullable', 'string', 'max:16'], 'no_akta' => ['nullable','string','max:100'],
            'nama_lengkap' => ['required', 'string', 'max:200'], 'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tempat_lahir' => ['required', 'string', 'max:100'], 'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'agama' => ['nullable', 'string', 'max:50'], 'kewarganegaraan' => ['nullable', 'string', 'max:50'],
            'nama_negara' => ['nullable','string','max:100'], 'jenis_kebutuhan_khusus' => ['nullable','array'],
            'jenis_kebutuhan_khusus.*' => ['string','max:100'], 'alamat' => ['nullable', 'string','max:1000'],
            'rt' => ['nullable', 'string', 'max:5'], 'rw' => ['nullable', 'string', 'max:5'], 'dusun' => ['nullable', 'string', 'max:100'],
            'desa_kelurahan' => ['nullable', 'string', 'max:100'], 'kecamatan' => ['nullable', 'string', 'max:100'], 'kode_pos' => ['nullable','string','max:10'],
            'lintang' => ['nullable','numeric','between:-90,90'], 'bujur' => ['nullable','numeric','between:-180,180'],
            'status_tempat_tinggal' => ['nullable','string','max:30'], 'moda_transportasi' => ['nullable','string','max:100'],
            'anak_ke' => ['nullable','integer','min:1'], 'pekerjaan' => ['nullable','string','max:100'],
            'punya_kip' => ['nullable',Rule::in(['01) Ya','02) Tidak'])], 'terima_kip' => ['nullable',Rule::in(['01) Ya','02) Tidak'])],
            'alasan_tolak_pip' => ['nullable','string','max:100'], 'no_telepon_rumah' => ['nullable','string','max:20'],
            'no_hp' => ['nullable','string','max:20'], 'email' => ['nullable','email','max:191'],
            'tinggi_badan' => ['nullable', 'numeric', 'min:0', 'max:300'], 'berat_badan' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'lingkar_kepala' => ['nullable', 'numeric', 'min:0', 'max:200'], 'jarak_sekolah' => ['nullable','numeric','min:0'],
            'waktu_jam' => ['nullable','integer','min:0','max:23'], 'waktu_menit' => ['nullable','integer','min:0','max:59'],
            'jumlah_saudara_kandung' => ['nullable','integer','min:0'], 'jenis_kesejahteraan' => ['nullable','string','max:50'],
            'no_kartu' => ['nullable','string','max:100'], 'nama_di_kartu' => ['nullable','string','max:191'],
            'kompetensi_keahlian' => ['nullable','string','max:100'], 'jenis_pendaftaran' => ['nullable','string','max:50'],
            'tanggal_masuk_sekolah' => ['nullable','date'], 'sekolah_asal' => ['nullable','string','max:191'],
            'no_peserta_un' => ['nullable','string','max:100'], 'no_seri_ijazah' => ['nullable','string','max:100'],
            'no_skhun' => ['nullable','string','max:100'], 'keluar_karena' => ['nullable','string','max:50'],
            'tanggal_keluar' => ['nullable','date'], 'alasan_keluar' => ['nullable','string','max:1000'],
            'kelas_id' => ['nullable','exists:kelas,id_kelas'], 'status_siswa' => ['required',Rule::in(['Aktif','Lulus','Pindah','Keluar'])],
        ]);

        if (array_key_exists('jenis_kebutuhan_khusus', $data)) {
            $needs = collect($data['jenis_kebutuhan_khusus'])->filter()->unique();
            if ($needs->count() > 1) $needs = $needs->reject(fn ($value) => str_starts_with($value, '01)'));
            $data['jenis_kebutuhan_khusus'] = $needs->implode(', ');
            $data['berkebutuhan_khusus'] = $needs->isNotEmpty() && ! str_starts_with((string) $needs->first(), '01)');
        }

        return $data;
    }

    public function download(Request $request)
    {
        $data = $request->validate(['kelas_id' => ['required','exists:kelas,id_kelas']]);
        $kelas = Kelas::findOrFail($data['kelas_id']);
        $students = Siswa::with(['kelas','orangTua'])->where('kelas_id',$kelas->id_kelas)->orderBy('nama_lengkap')->get();
        $escape = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
        $studentColumns = [
            'NIS'=>'nipd','NISN'=>'nisn','NIK'=>'nik','Nomor KK'=>'no_kk','Nama Lengkap'=>'nama_lengkap','Nama Panggilan'=>'nama_panggilan',
            'Jenis Kelamin'=>'jenis_kelamin','Tempat Lahir'=>'tempat_lahir','Tanggal Lahir'=>'tanggal_lahir','Agama'=>'agama','Kewarganegaraan'=>'kewarganegaraan',
            'Anak ke-'=>'anak_ke','Saudara Kandung'=>'jumlah_saudara_kandung','Saudara Tiri'=>'jumlah_saudara_tiri','Saudara Angkat'=>'jumlah_saudara_angkat',
            'Status Anak'=>'status_anak','Status dalam Keluarga'=>'status_dalam_keluarga','Tahun Ajaran Masuk'=>'tahun_ajaran_masuk',
            'Tanggal Masuk Sekolah'=>'tanggal_masuk_sekolah','Kelas Saat Masuk'=>'kelas_saat_masuk','Kelas Sekarang'=>'kelas.nama_kelas','Status Siswa'=>'status_siswa',
            'NPSN Sekolah Asal'=>'npsn_sekolah_asal','Nomor Ijazah Sebelumnya'=>'no_ijazah_sebelumnya','Nomor SKHUN/STTB'=>'no_skhun_sttb',
            'Alamat Siswa'=>'alamat','RT Siswa'=>'rt','RW Siswa'=>'rw','Dusun/Kampung'=>'dusun','Desa/Kelurahan'=>'desa_kelurahan','Kecamatan'=>'kecamatan',
            'Kabupaten/Kota'=>'kabupaten_kota','Provinsi'=>'provinsi','Kode Pos'=>'kode_pos','Status Tempat Tinggal'=>'status_tempat_tinggal',
            'Jarak ke Sekolah (km)'=>'jarak_sekolah','Moda Transportasi'=>'moda_transportasi','Nomor HP Darurat'=>'no_hp_darurat','Golongan Darah'=>'golongan_darah',
            'Tinggi Badan (cm)'=>'tinggi_badan','Berat Badan (kg)'=>'berat_badan','Lingkar Kepala (cm)'=>'lingkar_kepala',
            'Berkebutuhan Khusus'=>'berkebutuhan_khusus','Jenis Kebutuhan Khusus'=>'jenis_kebutuhan_khusus','Riwayat Kesehatan'=>'riwayat_kesehatan',
        ];
        $columns=['No'=>null]; foreach($studentColumns as $label=>$path)$columns[$label]=$path;
        foreach (['Ayah' => 'ayah', 'Ibu' => 'ibu'] as $label => $suffix) {
            $columns[$label.' - Nama Lengkap'] = 'orangTua.nama_'.$suffix;
            $columns[$label.' - NIK'] = 'orangTua.nik_'.$suffix;
            $columns[$label.' - Tahun Lahir'] = 'orangTua.tahun_lahir_'.$suffix;
            $columns[$label.' - Nomor Telepon'] = 'orangTua.no_telp_'.$suffix;
            $columns[$label.' - Pendidikan'] = 'orangTua.pendidikan_'.$suffix;
            $columns[$label.' - Pekerjaan'] = 'orangTua.pekerjaan_'.$suffix;
            $columns[$label.' - Penghasilan'] = 'orangTua.penghasilan_'.$suffix;
            $columns[$label.' - Kebutuhan Khusus'] = 'orangTua.berkebutuhan_'.$suffix;
        }
        foreach (['Nama Lengkap'=>'nama_wali','NIK'=>'nik_wali','Tahun Lahir'=>'tahun_lahir_wali','Hubungan'=>'hubungan_wali',
            'Pendidikan'=>'pendidikan_wali','Pekerjaan'=>'pekerjaan_wali','Penghasilan'=>'penghasilan_wali'] as $label=>$field) {
            $columns['Wali - '.$label] = 'orangTua.'.$field;
        }
        $html = '<html><head><meta charset="UTF-8"><style>td{mso-number-format:"\\@";}th{font-weight:bold;color:#1f2937}.siswa{background:#bfdbfe}.ayah{background:#bbf7d0}.ibu{background:#fbcfe8}.wali{background:#fde68a}</style></head><body><h3>Data Siswa '.$escape($kelas->nama_kelas).'</h3><table border="1"><thead><tr>';
        foreach(array_keys($columns) as $h){$group=str_starts_with($h,'Ayah -')?'ayah':(str_starts_with($h,'Ibu -')?'ibu':(str_starts_with($h,'Wali -')?'wali':'siswa'));$html.='<th class="'.$group.'">'.$escape($h).'</th>';}$html.='</tr></thead><tbody>';
        foreach($students as $i=>$s){$html.='<tr>';foreach($columns as $label=>$path){$value=$path===null?$i+1:(str_starts_with((string)$path,'orangTua.')?'':data_get($s,$path));if($value instanceof \Carbon\CarbonInterface)$value=$value->format('d-m-Y');if($path==='berkebutuhan_khusus')$value=$value?'Ya':'Tidak';if($path==='jenis_kelamin')$value=$value==='L'?'Laki-laki':'Perempuan';$html.='<td>'.$escape($value).'</td>';}$html.='</tr>';}
        $html.='</tbody></table></body></html>';
        $filename='data-siswa-'.str($kelas->nama_kelas)->slug().'-'.now()->format('Ymd').'.xls';
        return response($html)->header('Content-Type','application/vnd.ms-excel; charset=UTF-8')->header('Content-Disposition','attachment; filename="'.$filename.'"');
    }

    public function downloadSiswa(Siswa $siswa)
    {
        $siswa->load(['kelas','orangTua']);
        $studentFields = [
            'Nama Lengkap'=>'nama_lengkap','Jenis Kelamin'=>'jenis_kelamin','NISN'=>'nisn','NIK / No. KITAS'=>'nik','Nomor KK'=>'no_kk',
            'Tempat Lahir'=>'tempat_lahir','Tanggal Lahir'=>'tanggal_lahir','Nomor Registrasi Akta Lahir'=>'no_akta','Agama dan Kepercayaan'=>'agama',
            'Kewarganegaraan'=>'kewarganegaraan','Nama Negara'=>'nama_negara','Berkebutuhan Khusus'=>'jenis_kebutuhan_khusus',
            'Alamat Jalan'=>'alamat','RT'=>'rt','RW'=>'rw','Nama Dusun'=>'dusun','Nama Kelurahan / Desa'=>'desa_kelurahan','Kecamatan'=>'kecamatan',
            'Kode Pos'=>'kode_pos','Lintang'=>'lintang','Bujur'=>'bujur','Tempat Tinggal'=>'status_tempat_tinggal','Moda Transportasi'=>'moda_transportasi',
            'Anak Keberapa'=>'anak_ke','Pekerjaan'=>'pekerjaan','Apakah Punya KIP'=>'punya_kip','Tetap Akan Menerima KIP'=>'terima_kip',
            'Alasan Menolak PIP'=>'alasan_tolak_pip','Nomor Telepon Rumah'=>'no_telepon_rumah','Nomor HP'=>'no_hp','Email'=>'email',
            'Tinggi Badan (cm)'=>'tinggi_badan','Berat Badan (kg)'=>'berat_badan','Lingkar Kepala (cm)'=>'lingkar_kepala',
            'Jarak ke Sekolah (km)'=>'jarak_sekolah','Waktu Tempuh (jam)'=>'waktu_jam','Waktu Tempuh (menit)'=>'waktu_menit',
            'Jumlah Saudara Kandung'=>'jumlah_saudara_kandung','Jenis Kesejahteraan'=>'jenis_kesejahteraan','Nomor Kartu'=>'no_kartu',
            'Nama di Kartu'=>'nama_di_kartu','Kompetensi Keahlian'=>'kompetensi_keahlian','Jenis Pendaftaran'=>'jenis_pendaftaran',
            'NIS / Nomor Induk Peserta Didik'=>'nipd','Tanggal Masuk Sekolah'=>'tanggal_masuk_sekolah','Sekolah Asal'=>'sekolah_asal',
            'Nomor Peserta UN SMP/MTs'=>'no_peserta_un','Nomor Seri Ijazah SMP/MTs'=>'no_seri_ijazah','Nomor SKHUN SMP/MTs'=>'no_skhun',
            'Keluar Karena'=>'keluar_karena','Tanggal Keluar'=>'tanggal_keluar','Alasan Keluar'=>'alasan_keluar',
            'Kelas Sekarang'=>'kelas.nama_kelas','Status Siswa'=>'status_siswa',
        ];
        $familyFields = fn (string $person) => [
            'Nama '.ucfirst($person).' Kandung'=>'orangTua.nama_'.$person,'NIK '.ucfirst($person)=>'orangTua.nik_'.$person,
            'Tahun Lahir'=>'orangTua.tahun_lahir_'.$person,'Pendidikan'=>'orangTua.pendidikan_'.$person,
            'Pekerjaan'=>'orangTua.pekerjaan_'.$person,'Penghasilan Bulanan'=>'orangTua.penghasilan_'.$person,
            'Berkebutuhan Khusus'=>'orangTua.berkebutuhan_'.$person,
        ];
        $sections = [
            'Data Siswa'=>$studentFields,
            'Data Ayah'=>$familyFields('ayah'),
            'Data Ibu'=>$familyFields('ibu'),
            'Data Wali'=>[
                'Nama Wali'=>'orangTua.nama_wali','NIK Wali'=>'orangTua.nik_wali','Tahun Lahir'=>'orangTua.tahun_lahir_wali',
                'Pendidikan'=>'orangTua.pendidikan_wali','Pekerjaan'=>'orangTua.pekerjaan_wali','Penghasilan Bulanan'=>'orangTua.penghasilan_wali',
            ],
        ];
        $escape = fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $html = '<html><head><meta charset="UTF-8"><style>td{mso-number-format:"\\@";padding:5px}.title{font-size:16px;font-weight:bold;color:#fff}.siswa{background:#2563eb}.ayah{background:#16a34a}.ibu{background:#db2777}.wali{background:#d97706}.label{font-weight:bold;background:#f3f4f6}.colon{text-align:center;font-weight:bold;width:25px}</style></head><body><table border="1">';
        foreach ($sections as $title => $fields) {
            $class = strtolower(str_replace('Data ', '', $title));
            $colors = ['siswa'=>'#2563eb','ayah'=>'#16a34a','ibu'=>'#db2777','wali'=>'#d97706'];
            $html .= '<tr><td colspan="3" class="title '.$class.'" style="background:'.$colors[$class].';color:#ffffff;font-weight:bold">'.$escape($title).'</td></tr>';
            foreach ($fields as $label => $path) {
                $value = data_get($siswa, $path);
                if ($value instanceof \Carbon\CarbonInterface) $value = $value->format('d-m-Y');
                if ($path === 'jenis_kelamin') $value = $value === 'L' ? 'Laki-laki' : ($value === 'P' ? 'Perempuan' : '');
                $html .= '<tr><td class="label">'.$escape($label).'</td><td class="colon">:</td><td>'.$escape($value).'</td></tr>';
            }
            $html .= '<tr><td colspan="3"></td></tr>';
        }
        $html .= '</table></body></html>';
        $filename = 'data-siswa-'.str($siswa->nama_lengkap)->slug().'-'.now()->format('Ymd').'.xls';

        return response($html)->header('Content-Type','application/vnd.ms-excel; charset=UTF-8')->header('Content-Disposition','attachment; filename="'.$filename.'"');
    }

    private function saveRelatedData(Request $request, Siswa $siswa): void
    {
        $family = $request->validate([
            'ayah.nama_ayah' => ['nullable','string','max:200'], 'ayah.nik_ayah' => ['nullable','string','max:16'],
            'ayah.tahun_lahir_ayah' => ['nullable','integer','min:1900','max:'.now()->year], 'ayah.no_telp_ayah' => ['nullable','string','max:20'],
            'ayah.pendidikan_ayah' => ['nullable','string','max:100'], 'ayah.pekerjaan_ayah' => ['nullable','string','max:100'],
            'ayah.penghasilan_ayah' => ['nullable','string','max:50'], 'ayah.berkebutuhan_ayah' => ['nullable','array'],
            'ayah.berkebutuhan_ayah.*' => ['string','max:100'],
            'ibu.nama_ibu' => ['nullable','string','max:200'], 'ibu.nik_ibu' => ['nullable','string','max:16'],
            'ibu.tahun_lahir_ibu' => ['nullable','integer','min:1900','max:'.now()->year], 'ibu.no_telp_ibu' => ['nullable','string','max:20'],
            'ibu.pendidikan_ibu' => ['nullable','string','max:100'], 'ibu.pekerjaan_ibu' => ['nullable','string','max:100'],
            'ibu.penghasilan_ibu' => ['nullable','string','max:50'], 'ibu.berkebutuhan_ibu' => ['nullable','array'],
            'ibu.berkebutuhan_ibu.*' => ['string','max:100'],
            'wali.nama_wali' => ['nullable','string','max:200'], 'wali.nik_wali' => ['nullable','string','max:16'],
            'wali.tahun_lahir_wali' => ['nullable','integer','min:1900','max:'.now()->year], 'wali.hubungan_wali' => ['nullable','string','max:100'],
            'wali.pendidikan_wali' => ['nullable','string','max:100'], 'wali.pekerjaan_wali' => ['nullable','string','max:100'],
            'wali.penghasilan_wali' => ['nullable','string','max:50'],
            'lampiran.*' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);

        $parentData = collect(['ayah','ibu','wali'])->flatMap(fn ($key) => $family[$key] ?? [])->map(function ($value) {
            if (! is_array($value)) return $value;
            $values = collect($value)->filter()->unique();
            if ($values->count() > 1) $values = $values->reject(fn ($item) => str_starts_with($item, '01)'));
            return $values->implode(', ');
        })->all();
        if (collect($parentData)->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty()) {
            $siswa->orangTua()->updateOrCreate([], $parentData);
        }

        foreach ($request->file('lampiran', []) as $jenis => $file) {
            if (!$file) continue;
            if ($old = $siswa->lampiran()->where('jenis_dokumen', $jenis)->first()) Storage::disk('public')->delete($old->path);
            $path = $file->store('lampiran-siswa/'.$siswa->id, 'public');
            $siswa->lampiran()->updateOrCreate(['jenis_dokumen' => $jenis], ['path'=>$path,'nama_asli'=>$file->getClientOriginalName(),'mime_type'=>$file->getMimeType(),'ukuran'=>$file->getSize()]);
        }
    }

}
