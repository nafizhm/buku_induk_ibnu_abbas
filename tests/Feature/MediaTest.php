<?php

namespace Tests\Feature;

use App\Models\HakAkses;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use DatabaseTransactions;

    private function loginAdmin(string $jenis): HakAkses
    {
        $access = HakAkses::where('lihat', 1)->where('tambah', 1)
            ->whereHas('menu', fn ($query) => $query->where('route_name', 'admin.media.'.$jenis.'.index'))->firstOrFail();
        $this->actingAs(User::findOrFail($access->id_user));

        return $access;
    }

    public function test_photo_crud_files_and_landing_categories(): void
    {
        Storage::fake('local');
        $this->loginAdmin('foto');
        $this->get('/admin/media/foto')->assertOk()->assertSee('Media / Foto')->assertSee('id="media-modal"', false);
        $this->postJson('/admin/media/foto', ['judul' => 'Foto uji media', 'kategori' => 'SD', 'foto' => UploadedFile::fake()->image('foto.jpg')])->assertOk();
        $media = Media::where('judul', 'Foto uji media')->firstOrFail();
        $old = $media->path;
        Storage::disk('local')->assertExists($old);
        $this->get($media->foto_url)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/spmb')->assertOk()->assertSee('Foto uji media');
        $this->get('/spmb-smp')->assertOk()->assertDontSee('Foto uji media');
        $this->putJson('/admin/media/foto/'.$media->id, ['judul' => 'Foto pindah SMP', 'kategori' => 'SMP'])->assertOk();
        $this->assertSame($old, $media->fresh()->path);
        $this->get('/spmb')->assertDontSee('Foto pindah SMP');
        $this->get('/spmb-smp')->assertSee('Foto pindah SMP');
        $this->postJson('/admin/media/foto/'.$media->id, ['_method' => 'PUT', 'judul' => 'Foto pengganti', 'kategori' => 'SMP', 'foto' => UploadedFile::fake()->image('baru.png')])->assertOk();
        Storage::disk('local')->assertMissing($old);
        $new = $media->fresh()->path;
        Storage::disk('local')->assertExists($new);
        $this->delete('/admin/media/foto/'.$media->id)->assertRedirect('/admin/media/foto');
        Storage::disk('local')->assertMissing($new);
        $this->get(route('media.foto', $media->id))->assertNotFound();
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    public function test_video_crud_validation_and_category_filter(): void
    {
        $this->loginAdmin('video');
        $this->get('/admin/media/video')->assertOk()->assertSee('Kode video YouTube');
        $this->postJson('/admin/media/video', ['judul' => 'Video uji media', 'kategori' => 'SMP', 'youtube_code' => 'abcdefghijk'])->assertOk();
        $media = Media::where('judul', 'Video uji media')->firstOrFail();
        $this->get('/spmb-smp')->assertSee('https://www.youtube-nocookie.com/embed/abcdefghijk', false);
        $this->get('/spmb')->assertDontSee('Video uji media');
        $this->get('/admin/media/video?kategori=SD')->assertDontSee('Video uji media');
        $this->putJson('/admin/media/video/'.$media->id, ['judul' => 'Video baru', 'kategori' => 'SD', 'youtube_code' => '123456789_-'])->assertOk();
        $this->get('/spmb')->assertSee('https://www.youtube-nocookie.com/embed/123456789_-', false);
        $this->get('/spmb-smp')->assertDontSee('Video baru');
        $this->postJson('/admin/media/video', ['judul' => 'Salah', 'kategori' => 'SMA', 'youtube_code' => 'https://youtu.be/abcdefghijk'])->assertUnprocessable()->assertJsonValidationErrors(['kategori', 'youtube_code']);
        $this->postJson('/admin/media/foto', ['judul' => 'Salah', 'kategori' => 'SD', 'foto' => UploadedFile::fake()->create('script.svg', 1, 'image/svg+xml')])->assertUnprocessable()->assertJsonValidationErrors('foto');
        $this->putJson('/admin/media/foto/'.$media->id, [])->assertNotFound();
        $this->delete('/admin/media/video/'.$media->id)->assertRedirect('/admin/media/video');
        $this->get('/spmb')->assertDontSee('Video baru');
    }

    public function test_permissions_and_empty_landing_sections(): void
    {
        $this->get('/admin/media/foto')->assertRedirect(route('login'));
        $access = $this->loginAdmin('foto');
        $photo = Media::where('jenis', 'foto')->firstOrFail();
        $access->update(['tambah' => 0, 'edit' => 0, 'hapus' => 0]);
        $this->postJson('/admin/media/foto', [])->assertForbidden();
        $this->putJson('/admin/media/foto/'.$photo->id, [])->assertForbidden();
        $this->deleteJson('/admin/media/foto/'.$photo->id)->assertForbidden();
        $access->update(['lihat' => 0]);
        $this->get('/admin/media/foto')->assertForbidden();
        Media::where('kategori', 'SMP')->delete();
        $this->get('/spmb-smp')->assertOk()->assertDontSee('VIDEO PROFIL')->assertDontSee('SUASANA SEKOLAH');
    }
}
