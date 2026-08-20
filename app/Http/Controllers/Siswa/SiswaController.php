<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Siswa::with('kelas')->latest('id'))->addIndexColumn()
                ->editColumn('jenis_kelamin', fn (Siswa $s) => $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan')
                ->addColumn('kelas_sekarang', fn (Siswa $s) => $s->kelas?->nama_kelas ?? '-')
                ->editColumn('status_siswa', fn (Siswa $s) => $s->status_siswa ?? '-')
                ->addColumn('action', fn (Siswa $s) => '<div class="d-flex justify-content-center gap-1"><a href="'.route('siswa.edit', $s->id).'" class="btn btn-sm btn-primary">Edit</a><button type="button" class="btn btn-sm btn-danger btn-delete" data-id="'.$s->id.'">Hapus</button></div>')
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
        return view('admin.siswa.edit', ['siswa' => Siswa::with(['ayah','ibu','wali','lampiran'])->findOrFail($id), 'kelas' => Kelas::orderBy('id_kelas')->get()]);
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

    private function validatedData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nipd' => ['required', 'string', 'max:20', Rule::unique('siswa', 'nipd')->ignore($id)],
            'nisn' => ['nullable', 'string', 'max:25', Rule::unique('siswa', 'nisn')->ignore($id)],
            'nik' => ['nullable', 'digits:16', Rule::unique('siswa', 'nik')->ignore($id)],
            'no_kk' => ['nullable', 'digits:16'], 'nama_lengkap' => ['required', 'string', 'max:200'],
            'nama_panggilan' => ['nullable', 'string', 'max:100'], 'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tempat_lahir' => ['required', 'string', 'max:100'], 'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'agama' => ['nullable', 'string', 'max:50'], 'kewarganegaraan' => ['nullable', 'string', 'max:50'],
            'anak_ke' => ['nullable', 'integer', 'min:1'], 'jumlah_saudara_kandung' => ['nullable', 'integer', 'min:0'],
            'jumlah_saudara_tiri' => ['nullable', 'integer', 'min:0'], 'jumlah_saudara_angkat' => ['nullable', 'integer', 'min:0'],
            'status_anak' => ['nullable', Rule::in(['Kandung', 'Tiri', 'Angkat'])], 'status_dalam_keluarga' => ['nullable', 'string', 'max:100'],
            'tahun_ajaran_masuk' => ['nullable', 'string', 'max:20'], 'tanggal_masuk_sekolah' => ['nullable', 'date'],
            'kelas_saat_masuk' => ['nullable', 'string', 'max:50'], 'status_siswa' => ['required', Rule::in(['Aktif', 'Lulus', 'Pindah', 'Keluar'])],
            'kelas_id' => ['nullable', 'exists:kelas,id_kelas'],
            'npsn_sekolah_asal' => ['nullable', 'string', 'max:20'], 'no_ijazah_sebelumnya' => ['nullable', 'string', 'max:100'],
            'no_skhun_sttb' => ['nullable', 'string', 'max:100'], 'alamat' => ['nullable', 'string'],
            'rt' => ['nullable', 'string', 'max:5'], 'rw' => ['nullable', 'string', 'max:5'], 'dusun' => ['nullable', 'string', 'max:100'],
            'desa_kelurahan' => ['nullable', 'string', 'max:100'], 'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten_kota' => ['nullable', 'string', 'max:100'], 'provinsi' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'], 'status_tempat_tinggal' => ['nullable', Rule::in(['Orang Tua', 'Wali', 'Kos', 'Pesantren', 'Lainnya'])],
            'jarak_sekolah' => ['nullable', 'numeric', 'min:0'], 'moda_transportasi' => ['nullable', 'string', 'max:100'],
            'no_hp_darurat' => ['nullable', 'string', 'max:20'], 'golongan_darah' => ['nullable', Rule::in(['A', 'B', 'AB', 'O'])],
            'tinggi_badan' => ['nullable', 'numeric', 'min:0', 'max:300'], 'berat_badan' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'lingkar_kepala' => ['nullable', 'numeric', 'min:0', 'max:200'], 'berkebutuhan_khusus' => ['required', 'boolean'],
            'jenis_kebutuhan_khusus' => ['nullable', 'string', 'max:255', 'required_if:berkebutuhan_khusus,1'],
            'riwayat_kesehatan' => ['nullable', 'string'],
        ]);
    }

    public function download(Request $request)
    {
        $data = $request->validate(['kelas_id' => ['required','exists:kelas,id_kelas']]);
        $kelas = Kelas::findOrFail($data['kelas_id']);
        $students = Siswa::with(['kelas','ayah','ibu','wali'])->where('kelas_id',$kelas->id_kelas)->orderBy('nama_lengkap')->get();
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
        $parentColumns=['Nama Lengkap'=>'nama_lengkap','NIK'=>'nik','Nomor KK'=>'no_kk','Tempat Lahir'=>'tempat_lahir','Tanggal Lahir'=>'tanggal_lahir',
            'Agama'=>'agama','Kewarganegaraan'=>'kewarganegaraan','Status Hidup'=>'status_hidup','Hubungan dengan Siswa'=>'hubungan_dengan_siswa',
            'Nomor HP'=>'no_hp','Nomor WhatsApp'=>'no_whatsapp','Email'=>'email','Alamat Sama dengan Siswa'=>'alamat_sama_dengan_siswa','Alamat'=>'alamat',
            'RT'=>'rt','RW'=>'rw','Desa/Kelurahan'=>'desa_kelurahan','Kecamatan'=>'kecamatan','Kabupaten/Kota'=>'kabupaten_kota','Provinsi'=>'provinsi',
            'Kode Pos'=>'kode_pos','Pendidikan Terakhir'=>'pendidikan_terakhir','Pekerjaan'=>'pekerjaan','Nama Instansi'=>'nama_instansi','Jabatan'=>'jabatan','Penghasilan'=>'penghasilan'];
        $waliColumns=['Sumber Wali'=>'sumber_wali','Nama Lengkap'=>'nama_lengkap','NIK'=>'nik','Hubungan dengan Siswa'=>'hubungan_dengan_siswa',
            'Tempat Lahir'=>'tempat_lahir','Tanggal Lahir'=>'tanggal_lahir','Nomor HP'=>'no_hp','Nomor WhatsApp'=>'no_whatsapp','Alamat'=>'alamat',
            'Pendidikan Terakhir'=>'pendidikan_terakhir','Pekerjaan'=>'pekerjaan','Penghasilan'=>'penghasilan'];
        $columns=['No'=>null]; foreach($studentColumns as $label=>$path)$columns[$label]=$path;
        foreach(['Ayah'=>'ayah','Ibu'=>'ibu'] as $label=>$relation)foreach($parentColumns as $name=>$path)$columns[$label.' - '.$name]=$relation.'.'.$path;
        foreach($waliColumns as $label=>$path)$columns['Wali - '.$label]='wali.'.$path;
        $html = '<html><head><meta charset="UTF-8"><style>td{mso-number-format:"\\@";}th{background:#d9eaf7;font-weight:bold;}</style></head><body><h3>Data Siswa '.$escape($kelas->nama_kelas).'</h3><table border="1"><thead><tr>';
        foreach(array_keys($columns) as $h)$html.='<th>'.$escape($h).'</th>'; $html.='</tr></thead><tbody>';
        foreach($students as $i=>$s){$html.='<tr>';foreach($columns as $label=>$path){$value=$path===null?$i+1:data_get($s,$path);if($value instanceof \Carbon\CarbonInterface)$value=$value->format('d-m-Y');if(in_array($path,['berkebutuhan_khusus','ayah.alamat_sama_dengan_siswa','ibu.alamat_sama_dengan_siswa'],true))$value=$value?'Ya':'Tidak';if($path==='jenis_kelamin')$value=$value==='L'?'Laki-laki':'Perempuan';$html.='<td>'.$escape($value).'</td>';}$html.='</tr>';}
        $html.='</tbody></table></body></html>';
        $filename='data-siswa-'.str($kelas->nama_kelas)->slug().'-'.now()->format('Ymd').'.xls';
        return response($html)->header('Content-Type','application/vnd.ms-excel; charset=UTF-8')->header('Content-Disposition','attachment; filename="'.$filename.'"');
    }

    private function saveRelatedData(Request $request, Siswa $siswa): void
    {
        $family = $request->validate([
            'ayah.nama_lengkap' => ['nullable','string','max:200'], 'ayah.nik' => ['nullable','digits:16'], 'ayah.no_kk' => ['nullable','digits:16'],
            'ayah.tempat_lahir' => ['nullable','string','max:100'], 'ayah.tanggal_lahir' => ['nullable','date'], 'ayah.agama' => ['nullable','string','max:50'],
            'ayah.kewarganegaraan' => ['nullable','string','max:50'], 'ayah.status_hidup' => ['nullable',Rule::in(['Hidup','Meninggal'])],
            'ayah.hubungan_dengan_siswa' => ['nullable','string','max:100'], 'ayah.no_hp' => ['nullable','string','max:20'], 'ayah.no_whatsapp' => ['nullable','string','max:20'],
            'ayah.email' => ['nullable','email','max:255'], 'ayah.alamat_sama_dengan_siswa' => ['nullable','boolean'], 'ayah.alamat' => ['nullable','string'],
            'ayah.rt' => ['nullable','string','max:5'], 'ayah.rw' => ['nullable','string','max:5'], 'ayah.desa_kelurahan' => ['nullable','string','max:100'],
            'ayah.kecamatan' => ['nullable','string','max:100'], 'ayah.kabupaten_kota' => ['nullable','string','max:100'], 'ayah.provinsi' => ['nullable','string','max:100'],
            'ayah.kode_pos' => ['nullable','string','max:10'], 'ayah.pendidikan_terakhir' => ['nullable','string','max:50'], 'ayah.pekerjaan' => ['nullable','string','max:100'],
            'ayah.nama_instansi' => ['nullable','string','max:150'], 'ayah.jabatan' => ['nullable','string','max:100'], 'ayah.penghasilan' => ['nullable','string','max:50'],
            'ibu.nama_lengkap' => ['nullable','string','max:200'], 'ibu.nik' => ['nullable','digits:16'], 'ibu.no_kk' => ['nullable','digits:16'],
            'ibu.tempat_lahir' => ['nullable','string','max:100'], 'ibu.tanggal_lahir' => ['nullable','date'], 'ibu.agama' => ['nullable','string','max:50'],
            'ibu.kewarganegaraan' => ['nullable','string','max:50'], 'ibu.status_hidup' => ['nullable',Rule::in(['Hidup','Meninggal'])],
            'ibu.no_hp' => ['nullable','string','max:20'], 'ibu.no_whatsapp' => ['nullable','string','max:20'], 'ibu.email' => ['nullable','email','max:255'],
            'ibu.alamat_sama_dengan_siswa' => ['nullable','boolean'], 'ibu.alamat' => ['nullable','string'], 'ibu.rt' => ['nullable','string','max:5'], 'ibu.rw' => ['nullable','string','max:5'],
            'ibu.desa_kelurahan' => ['nullable','string','max:100'], 'ibu.kecamatan' => ['nullable','string','max:100'], 'ibu.kabupaten_kota' => ['nullable','string','max:100'],
            'ibu.provinsi' => ['nullable','string','max:100'], 'ibu.kode_pos' => ['nullable','string','max:10'], 'ibu.pendidikan_terakhir' => ['nullable','string','max:50'],
            'ibu.pekerjaan' => ['nullable','string','max:100'], 'ibu.nama_instansi' => ['nullable','string','max:150'], 'ibu.jabatan' => ['nullable','string','max:100'], 'ibu.penghasilan' => ['nullable','string','max:50'],
            'wali.sumber_wali' => ['nullable',Rule::in(['Ayah','Ibu','Orang lain'])], 'wali.nama_lengkap' => ['nullable','string','max:200'],
            'wali.nik' => ['nullable','digits:16'], 'wali.hubungan_dengan_siswa' => ['nullable','string','max:100'], 'wali.tempat_lahir' => ['nullable','string','max:100'],
            'wali.tanggal_lahir' => ['nullable','date'], 'wali.no_hp' => ['nullable','string','max:20'], 'wali.no_whatsapp' => ['nullable','string','max:20'],
            'wali.alamat' => ['nullable','string'], 'wali.pendidikan_terakhir' => ['nullable','string','max:50'], 'wali.pekerjaan' => ['nullable','string','max:100'], 'wali.penghasilan' => ['nullable','string','max:50'],
            'lampiran.*' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);

        foreach (['ayah' => 'Ayah', 'ibu' => 'Ibu'] as $key => $jenis) {
            $data = $family[$key] ?? [];
            if (collect($data)->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty()) {
                $data['jenis'] = $jenis;
                $siswa->orangTua()->updateOrCreate(['jenis' => $jenis], $data);
            }
        }
        $wali = $family['wali'] ?? [];
        if (($wali['sumber_wali'] ?? null) === 'Ayah') $wali = $this->waliFromParent($siswa->ayah()->first(), 'Ayah');
        if (($wali['sumber_wali'] ?? null) === 'Ibu') $wali = $this->waliFromParent($siswa->ibu()->first(), 'Ibu');
        if (collect($wali)->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty()) $siswa->wali()->updateOrCreate([], $wali);

        foreach ($request->file('lampiran', []) as $jenis => $file) {
            if (!$file) continue;
            if ($old = $siswa->lampiran()->where('jenis_dokumen', $jenis)->first()) Storage::disk('public')->delete($old->path);
            $path = $file->store('lampiran-siswa/'.$siswa->id, 'public');
            $siswa->lampiran()->updateOrCreate(['jenis_dokumen' => $jenis], ['path'=>$path,'nama_asli'=>$file->getClientOriginalName(),'mime_type'=>$file->getMimeType(),'ukuran'=>$file->getSize()]);
        }
    }

    private function waliFromParent($parent, string $source): array
    {
        if (!$parent) return ['sumber_wali' => $source];
        return ['sumber_wali'=>$source,'nama_lengkap'=>$parent->nama_lengkap,'nik'=>$parent->nik,'hubungan_dengan_siswa'=>$source,
            'tempat_lahir'=>$parent->tempat_lahir,'tanggal_lahir'=>$parent->tanggal_lahir,'no_hp'=>$parent->no_hp,
            'no_whatsapp'=>$parent->no_whatsapp,'alamat'=>$parent->alamat,'pendidikan_terakhir'=>$parent->pendidikan_terakhir,
            'pekerjaan'=>$parent->pekerjaan,'penghasilan'=>$parent->penghasilan];
    }
}
