<?php

namespace App\Http\Controllers;

use App\Models\HakAkses;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    private function permissions(Request $request, string $action = 'lihat'): HakAkses
    {
        $access = HakAkses::where('id_user', auth()->id())->whereHas('menu',
            fn ($query) => $query->where('route_name', 'admin.media.'.$request->route('jenis').'.index'))->first();
        abort_unless($access && $access->lihat && $access->{$action}, 403);

        return $access;
    }

    public function index(Request $request)
    {
        $permissions = $this->permissions($request);
        $jenis = $request->route('jenis');
        $request->validate(['kategori' => ['nullable', Rule::in(['SD', 'SMP'])]]);
        $items = Media::where('jenis', $jenis)->when($request->filled('kategori'),
            fn ($query) => $query->where('kategori', $request->kategori))->latest('id')->paginate(15)->withQueryString();

        return view('admin.media.index', compact('permissions', 'jenis', 'items'));
    }

    private function save(Request $request, Media $media)
    {
        $jenis = $request->route('jenis');
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(['SD', 'SMP'])],
            'foto' => $jenis === 'foto' ? [$media->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'] : ['prohibited'],
            'youtube_code' => $jenis === 'video' ? ['required', 'regex:/\A[A-Za-z0-9_-]{11}\z/'] : ['prohibited'],
        ], ['youtube_code.regex' => 'Isi kode video YouTube 11 karakter, bukan URL.', 'foto.max' => 'Ukuran foto maksimal 5 MB.']);
        unset($data['foto']);
        $oldPath = $media->path;
        $newPath = null;
        if ($request->hasFile('foto')) {
            $newPath = $request->file('foto')->store('media/foto', 'local');
            abort_unless($newPath, 500, 'Foto gagal disimpan. Silakan coba kembali.');
            $data['path'] = $newPath;
        }
        try {
            $media->fill($data + ['jenis' => $jenis])->save();
        } catch (\Throwable $e) {
            if ($newPath) Storage::disk('local')->delete($newPath);
            throw $e;
        }
        if ($newPath && str_starts_with($oldPath ?? '', 'media/foto/')) {
            Storage::disk('local')->delete($oldPath);
        }

        if ($request->expectsJson()) {
            $request->session()->flash('success', 'Media berhasil disimpan.');
            return response()->json(['message' => 'Media berhasil disimpan.']);
        }

        return redirect()->route('admin.media.'.$jenis.'.index')->with('success', 'Media berhasil disimpan.');
    }

    public function store(Request $request)
    {
        $this->permissions($request, 'tambah');

        return $this->save($request, new Media);
    }

    public function update(Request $request, Media $media)
    {
        $this->permissions($request, 'edit');
        abort_unless($media->jenis === $request->route('jenis'), 404);

        return $this->save($request, $media);
    }

    public function destroy(Request $request, Media $media)
    {
        $this->permissions($request, 'hapus');
        abort_unless($media->jenis === $request->route('jenis'), 404);
        $media->delete();
        if (str_starts_with($media->path ?? '', 'media/foto/')) Storage::disk('local')->delete($media->path);

        return redirect()->route('admin.media.'.$request->route('jenis').'.index')->with('success', 'Media berhasil dihapus.');
    }

    public function foto(Media $media)
    {
        abort_unless($media->jenis === 'foto' && str_starts_with($media->path ?? '', 'media/foto/'), 404);
        abort_unless(Storage::disk('local')->exists($media->path), 404);

        return response()->file(Storage::disk('local')->path($media->path), ['X-Content-Type-Options' => 'nosniff']);
    }

    public function landing(Request $request)
    {
        $isSmp = $request->routeIs('spmb.smp');
        $media = Media::where('kategori', $isSmp ? 'SMP' : 'SD')->orderBy('id')->get();

        return response()->view('spmb', ['isSmp' => $isSmp, 'photos' => $media->where('jenis', 'foto'), 'videos' => $media->where('jenis', 'video'),
            'quotaAvailability' => \App\Support\SpmbQuota::availability(),
        ])->header('Cache-Control', 'no-store, private');
    }
}
