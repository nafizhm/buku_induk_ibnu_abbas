<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrangTuaProfilSiswaDapodikTest extends TestCase
{
    use DatabaseTransactions;

    public function test_profile_landing_shows_account_and_dapodik_button_without_summary(): void
    {
        $user = User::whereHas('siswa')->firstOrFail();
        $this->actingAs($user);

        $this->get(route('orang-tua.profil'))
            ->assertOk()->assertSee('Akun Wali Murid')->assertSee($user->username)
            ->assertSee('Password Baru')->assertSee('Isi Data Dapodik')
            ->assertDontSee('Ringkasan Kelengkapan Profil');

        $this->postJson(route('orang-tua.profil.update', 'akun'), [
            'akun' => ['nama' => 'Wali Pengujian', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123'],
        ])->assertOk()->assertJsonPath('ok', true);

        $user->refresh();
        $this->assertSame('Wali Pengujian', $user->nama);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_dapodik_button_page_shows_all_pdf_groups_at_once(): void
    {
        $user = User::whereHas('siswa')->firstOrFail();
        $this->actingAs($user);

        $this->get(route('orang-tua.profil', ['form' => 'dapodik']))
            ->assertOk()->assertSee('Formulir Peserta Didik')
            ->assertSee('Data Pribadi')->assertSee('Data Ayah Kandung')
            ->assertSee('Data Ibu Kandung')->assertSee('Data Wali')
            ->assertSee('data-return-profile="true"', false)
            ->assertDontSee('Ringkasan Kelengkapan Profil');
    }

    public function test_dapodik_groups_render_and_all_groups_can_be_saved(): void
    {
        $user = User::whereHas('siswa')->firstOrFail();
        $siswa = $user->siswa()->firstOrFail();
        $this->actingAs($user);

        $this->get(route('orang-tua.profil', ['form' => 'siswa']))
            ->assertOk()
            ->assertSee('Data Pribadi')
            ->assertSee('Alamat Tempat Tinggal')
            ->assertSee('Data Periodik')
            ->assertSee('Kartu Indonesia Pintar (KIP)')
            ->assertSee('Kontak')
            ->assertSee('Registrasi Peserta Didik')
            ->assertSee('Pendaftaran Keluar')
            ->assertSee('01) Islam')
            ->assertSee('99) Lainnya')
            ->assertDontSee('Foto Santri')
            ->assertDontSee('Kelas / Halaqah')
            ->assertDontSee('Nama Panggilan')
            ->assertDontSee('Riwayat Kesehatan');

        $response = $this->postJson(route('orang-tua.profil.update', 'siswa'), [
            'nama_lengkap' => $siswa->nama_lengkap,
            'jenis_kebutuhan_khusus' => ['01) Tidak'],
            'no_akta' => 'AKTA-TEST-001',
            'alamat' => 'Jalan Pengujian', 'rt' => '001', 'lintang' => -1.25, 'bujur' => 116.85,
            'tinggi_badan' => 140, 'waktu_jam' => 1, 'waktu_menit' => 15,
            'punya_kip' => '01) Ya', 'jenis_kesejahteraan' => '02) PIP', 'no_kartu' => 'KIP-TEST',
            'no_telepon_rumah' => '0542123456', 'no_hp' => '081234567890', 'email' => 'wali@example.test',
            'jenis_pendaftaran' => '01) Siswa Baru', 'tanggal_masuk_sekolah' => '2026-07-01',
            'sekolah_asal' => 'Sekolah Asal Uji', 'keluar_karena' => null, 'tanggal_keluar' => null,
        ]);

        $response->assertOk()->assertJsonPath('ok', true);
        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id, 'no_akta' => 'AKTA-TEST-001', 'alamat' => 'Jalan Pengujian',
            'waktu_menit' => 15, 'punya_kip' => '01) Ya', 'jenis_kesejahteraan' => '02) PIP',
            'jenis_pendaftaran' => '01) Siswa Baru',
        ]);
    }

    public function test_parent_and_guardian_forms_follow_pdf_fields_and_store_codes(): void
    {
        $user = User::whereHas('siswa')->firstOrFail();
        $siswa = $user->siswa()->firstOrFail();
        $this->actingAs($user);

        $this->get(route('orang-tua.profil', ['form' => 'ayah']))
            ->assertOk()->assertSee('01) Tidak Sekolah')->assertSee('07) Tidak Berpenghasilan')
            ->assertDontSee('No. HP / WhatsApp');

        $this->postJson(route('orang-tua.profil.update', 'ayah'), [
            'ayah' => [
                'nama_ayah' => 'Ayah Dapodik', 'nik_ayah' => '1234567890123456', 'tahun_lahir_ayah' => 1980,
                'pendidikan_ayah' => '09) D4/S1', 'pekerjaan_ayah' => '09) Wiraswasta',
                'penghasilan_ayah' => '04) Rp2.000.000-Rp4.999.999',
                'berkebutuhan_ayah' => ['02) Netra (A)', '03) Rungu (B)'],
            ],
        ])->assertOk()->assertJsonPath('ok', true);

        $this->assertDatabaseHas('orang_tua', [
            'siswa_id' => $siswa->id, 'pendidikan_ayah' => '09) D4/S1',
            'pekerjaan_ayah' => '09) Wiraswasta', 'berkebutuhan_ayah' => '02) Netra (A), 03) Rungu (B)',
        ]);

        $this->get(route('orang-tua.profil', ['form' => 'wali']))
            ->assertOk()->assertSee('Data Wali')->assertDontSee('Hubungan dengan Santri');
    }
}
