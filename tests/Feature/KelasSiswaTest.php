<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KelasSiswaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);
        $this->actingAs(new User(['name' => 'Admin Uji']));
    }

    private function kelas(string $nama): Kelas
    {
        return Kelas::create(['nama_kelas' => $nama, 'tingkat' => '1', 'status' => 'aktif']);
    }

    private function siswa(?Kelas $kelas = null): Siswa
    {
        return Siswa::create(['nama_lengkap' => 'Siswa Uji', 'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Balikpapan', 'tanggal_lahir' => '2015-01-01', 'kelas_id' => $kelas?->id_kelas]);
    }

    public function test_students_can_be_added_transferred_and_removed_without_deleting_their_data(): void
    {
        $asal = $this->kelas('Asal');
        $tujuan = $this->kelas('Tujuan');
        foreach ([$this->siswa(), $this->siswa($asal)] as $siswa) {
            $this->post(route('kelas.siswa.store', $tujuan), ['siswa_id' => $siswa->id])
                ->assertRedirect(route('kelas.detail', $tujuan))->assertSessionHasNoErrors();
            $this->assertEquals($tujuan->id_kelas, $siswa->fresh()->kelas_id);
            $this->delete(route('kelas.siswa.destroy', [$tujuan, $siswa]))
                ->assertRedirect(route('kelas.detail', $tujuan));
            $this->assertNotNull($siswa->fresh());
            $this->assertNull($siswa->fresh()->kelas_id);
        }
    }

    public function test_removing_a_student_from_another_class_is_rejected(): void
    {
        $asal = $this->kelas('Asal');
        $siswa = $this->siswa($asal);
        $this->delete(route('kelas.siswa.destroy', [$this->kelas('Lain'), $siswa]))->assertNotFound();
        $this->assertEquals($asal->id_kelas, $siswa->fresh()->kelas_id);
    }

    public function test_invalid_student_selection_is_rejected(): void
    {
        $kelas = $this->kelas('Tujuan');
        foreach ([[], ['siswa_id' => 999999]] as $data) {
            $this->postJson(route('kelas.siswa.store', $kelas), $data)
                ->assertUnprocessable()->assertJsonValidationErrors('siswa_id');
        }
    }

    public function test_detail_shows_actions_and_excludes_current_members_from_selection(): void
    {
        $kelas = $this->kelas('Tujuan');
        $anggota = $this->siswa($kelas);
        $tersedia = $this->siswa();
        $this->get(route('kelas.detail', $kelas))->assertOk()
            ->assertSee('Tambah Siswa')->assertSee('Keluarkan')
            ->assertViewHas('pilihanSiswa', fn ($pilihan) => $pilihan->contains($tersedia) && !$pilihan->contains($anggota));
    }

    public function test_guests_cannot_change_class_membership(): void
    {
        auth()->logout();
        $kelas = $this->kelas('Tujuan');
        $siswa = $this->siswa($kelas);
        $this->post(route('kelas.siswa.store', $kelas), ['siswa_id' => $siswa->id])->assertRedirect(route('login'));
        $this->delete(route('kelas.siswa.destroy', [$kelas, $siswa]))->assertRedirect(route('login'));
        $this->assertEquals($kelas->id_kelas, $siswa->fresh()->kelas_id);
    }
}
