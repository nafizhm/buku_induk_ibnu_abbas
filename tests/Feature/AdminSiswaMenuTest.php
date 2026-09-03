<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSiswaMenuTest extends TestCase
{
    use DatabaseTransactions;

    private function admin(): User
    {
        return User::query()->firstOrFail();
    }

    public function test_all_read_endpoints_in_admin_siswa_menu_work(): void
    {
        $siswa = Siswa::with('orangTua')->findOrFail(196);
        $this->actingAs($this->admin());

        $this->get(route('siswa.index'))->assertOk()->assertSee('Data Anak / Siswa')->assertSee('downloadClassForm')
            ->assertDontSee('Calon Siswa')
            ->assertDontSee('<th>NISN</th>', false)
            ->assertSee('width:45px', false);
        $this->getJson(route('siswa.index'), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertJsonFragment(['id' => $siswa->id])
            ->assertSee('Download Excel');
        $this->get(route('siswa.create'))->assertOk()->assertSee('Tambah Data Siswa');
        $this->get(route('siswa.show', $siswa->id))->assertOk()->assertJsonPath('data.id', $siswa->id);
        $this->get(route('siswa.edit', $siswa->id))
            ->assertOk()
            ->assertSee('Edit Data Siswa')
            ->assertSee($siswa->nama_lengkap)
            ->assertSee('Data Pribadi')
            ->assertSee('Kartu Indonesia Pintar (KIP)')
            ->assertSee('Kesejahteraan Peserta Didik')
            ->assertSee('Pendaftaran Keluar')
            ->assertSee('01) Tidak Sekolah')
            ->assertSee('07) Tidak Berpenghasilan')
            ->assertSee('attachmentPreviewModal')
            ->assertSee('btn-upload-attachment');

        if ($siswa->kelas_id) {
            $this->post(route('siswa.download'), ['kelas_id' => $siswa->kelas_id])
                ->assertOk()
                ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8')
                ->assertSee('<th class="siswa">Nama Lengkap</th>', false)
                ->assertSee('<th class="ayah">Ayah - Nama Lengkap</th>', false)
                ->assertSee('<th class="ibu">Ibu - Nama Lengkap</th>', false)
                ->assertSee('<th class="wali">Wali - Nama Lengkap</th>', false);
        }

        $this->get(route('siswa.download-one', $siswa))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8')
            ->assertSee('class="title siswa" style="background:#2563eb', false)
            ->assertSee('class="title ayah" style="background:#16a34a', false)
            ->assertSee('class="title ibu" style="background:#db2777', false)
            ->assertSee('class="title wali" style="background:#d97706', false)
            ->assertSee('<td class="colon">:</td>', false)
            ->assertSee($siswa->nama_lengkap);
    }

    public function test_class_detail_lists_all_students_without_datatable(): void
    {
        $kelas = Kelas::with('siswa')->has('siswa')->firstOrFail();
        $this->actingAs($this->admin());

        $response = $this->get(route('kelas.detail', $kelas))
            ->assertOk()
            ->assertSee('Siswa Kelas '.$kelas->nama_kelas)
            ->assertDontSee('DataTable(', false);

        foreach ($kelas->siswa as $siswa) {
            $response->assertSee($siswa->nama_lengkap);
        }
    }

    public function test_existing_student_can_be_updated_with_parent_data(): void
    {
        $siswa = Siswa::with('orangTua')->findOrFail(196);
        $payload = [
            'nipd' => $siswa->nipd ?: 'TEST-'.$siswa->id,
            'nisn' => $siswa->nisn,
            'nik' => $siswa->nik,
            'no_kk' => $siswa->no_kk,
            'nama_lengkap' => $siswa->nama_lengkap,
            'jenis_kelamin' => $siswa->jenis_kelamin ?: 'L',
            'tempat_lahir' => $siswa->tempat_lahir ?: 'Balikpapan',
            'tanggal_lahir' => $siswa->tanggal_lahir?->format('Y-m-d') ?: '2015-01-01',
            'status_siswa' => $siswa->status_siswa ?: 'Aktif',
            'kelas_id' => $siswa->kelas_id,
            'berkebutuhan_khusus' => (int) $siswa->berkebutuhan_khusus,
            'ayah' => ['nama_ayah' => $siswa->orangTua?->nama_ayah],
            'ibu' => ['nama_ibu' => $siswa->orangTua?->nama_ibu],
            'wali' => ['nama_wali' => $siswa->orangTua?->nama_wali],
        ];

        $this->actingAs($this->admin())
            ->put(route('siswa.update', $siswa->id), $payload)
            ->assertRedirect(route('siswa.index'))
            ->assertSessionHasNoErrors();
    }

    public function test_admin_can_upload_preview_and_delete_student_attachment(): void
    {
        Storage::fake('public');
        $siswa = Siswa::findOrFail(23);
        $this->actingAs($this->admin());

        $this->postJson(route('siswa.lampiran.upload', $siswa), [
            'jenis_dokumen' => 'foto_siswa',
            'file' => UploadedFile::fake()->image('foto-siswa.jpg'),
        ])->assertOk()->assertJsonPath('message', 'Lampiran berhasil diunggah.');

        $lampiran = $siswa->lampiran()->where('jenis_dokumen', 'foto_siswa')->firstOrFail();
        Storage::disk('public')->assertExists($lampiran->path);
        $this->get(route('siswa.lampiran.view', $lampiran))->assertOk()->assertHeader('content-type', 'image/jpeg');

        $this->deleteJson(route('siswa.lampiran.delete', $lampiran))
            ->assertOk()->assertJsonPath('message', 'Lampiran berhasil dihapus.');
        Storage::disk('public')->assertMissing($lampiran->path);
        $this->assertDatabaseMissing('lampiran_siswa', ['id' => $lampiran->id]);
    }

    public function test_student_can_be_created_and_deleted(): void
    {
        $payload = [
            'nipd' => 'TEST-ADMIN-SISWA',
            'nama_lengkap' => 'Siswa Uji Admin',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Balikpapan',
            'tanggal_lahir' => '2015-01-01',
            'status_siswa' => 'Aktif',
            'berkebutuhan_khusus' => 0,
            'ayah' => ['nama_ayah' => 'Ayah Uji'],
            'ibu' => ['nama_ibu' => 'Ibu Uji'],
        ];

        $this->actingAs($this->admin())
            ->post(route('siswa.store'), $payload)
            ->assertRedirect(route('siswa.index'))
            ->assertSessionHasNoErrors();

        $siswa = Siswa::where('nipd', 'TEST-ADMIN-SISWA')->firstOrFail();
        $this->assertSame('Ayah Uji', $siswa->orangTua?->nama_ayah);

        $this->deleteJson(route('siswa.destroy', $siswa->id))
            ->assertOk()
            ->assertJsonPath('status', 'success');
        $this->assertDatabaseMissing('siswa', ['id' => $siswa->id]);
    }
}
