<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminKegiatanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_full_kegiatan_crud_and_automatic_invitations_work(): void
    {
        $user = User::query()->firstOrFail();
        $this->actingAs($user);

        $this->get(route('kegiatan.index'))->assertOk()->assertSee('Data Kegiatan');

        $this->post(route('kegiatan.store'), [
            'tgl_kegiatan' => '2026-09-01', 'nama_kegiatan' => 'Kajian Parenting Uji',
            'zona_waktu' => 'WITA', 'status' => 'aktif',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $kegiatan = Kegiatan::where('nama_kegiatan', 'Kajian Parenting Uji')->firstOrFail();
        $this->assertSame(Siswa::where('status_siswa', 'Aktif')->count(), $kegiatan->presensi()->count());
        $this->assertSame($kegiatan->presensi()->count(), $kegiatan->presensi()->distinct('qr_code')->count('qr_code'));

        $this->get(route('kegiatan.show', $kegiatan))->assertOk()->assertSee('Peserta Kegiatan');
        $this->get(route('kegiatan.export', $kegiatan))->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8');

        $this->put(route('kegiatan.update', $kegiatan), [
            'tgl_kegiatan' => '2026-09-02', 'nama_kegiatan' => 'Kajian Parenting Diperbarui',
            'zona_waktu' => 'WIB', 'status' => 'non aktif',
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('kegiatan', ['id' => $kegiatan->id, 'nama_kegiatan' => 'Kajian Parenting Diperbarui', 'status' => 'non aktif']);

        $this->delete(route('kegiatan.destroy', $kegiatan))->assertRedirect(route('kegiatan.index'));
        $this->assertDatabaseMissing('kegiatan', ['id' => $kegiatan->id]);
        $this->assertDatabaseMissing('presensi_kegiatan', ['kegiatan_id' => $kegiatan->id]);

        $menuId = DB::table('menu')->where('route_name', 'kegiatan.index')->value('id');
        $this->assertNotNull($menuId);
        $this->assertDatabaseHas('hak_akses', ['id_user' => $user->id, 'id_menu' => $menuId, 'lihat' => 1]);
    }
}
