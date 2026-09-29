<?php

namespace App\Http\Controllers;

use App\Models\HakAkses;
use App\Models\TemplatePesan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TemplateController extends Controller
{
    private function permissions(string $action = 'lihat'): HakAkses
    {
        $access = HakAkses::where('id_user', auth()->id())
            ->whereHas('menu', fn ($query) => $query->where('route_name', 'admin.template.index'))->first();
        abort_unless($access && $access->lihat && $access->{$action}, 403);

        return $access;
    }

    private function validated(Request $request, ?TemplatePesan $template = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'penggunaan' => ['nullable', Rule::in(['formulir_awal']), Rule::unique('template_pesan', 'penggunaan')->ignore($template?->id)],
            'isi' => ['required', 'string', 'max:10000', function ($attribute, $value, $fail) use ($request) {
                if ($request->input('penggunaan') === 'formulir_awal' && ! str_contains($value, '[[link]]')) {
                    $fail('Template formulir awal harus memuat kode [[link]].');
                }
                preg_match_all('/\[\[([^\]]*)\]\]/', $value, $matches);
                foreach ($matches[1] as $code) {
                    if (! in_array($code, ['link', 'nama', 'ortu', 'jenjang'], true)) {
                        $fail('Kode pengganti tidak dikenal. Gunakan [[link]], [[nama]], [[ortu]], atau [[jenjang]].');
                        break;
                    }
                }
            }],
        ], ['penggunaan.unique' => 'Template untuk formulir awal sudah tersedia. Edit template tersebut atau ubah penggunaannya terlebih dahulu.']);
    }

    public function index()
    {
        $permissions = $this->permissions();

        return view('admin.template.index', ['templates' => TemplatePesan::latest('id')->paginate(15), 'permissions' => $permissions]);
    }

    public function create()
    {
        $this->permissions('tambah');

        return view('admin.template.form', ['template' => new TemplatePesan]);
    }

    public function store(Request $request)
    {
        $this->permissions('tambah');
        TemplatePesan::create($this->validated($request));

        return redirect()->route('admin.template.index')->with('success', 'Template berhasil ditambahkan.');
    }

    public function show(TemplatePesan $template)
    {
        $permissions = $this->permissions();

        return view('admin.template.show', compact('template', 'permissions'));
    }

    public function edit(TemplatePesan $template)
    {
        $this->permissions('edit');

        return view('admin.template.form', compact('template'));
    }

    public function update(Request $request, TemplatePesan $template)
    {
        $this->permissions('edit');
        $template->update($this->validated($request, $template));

        return redirect()->route('admin.template.index')->with('success', 'Template berhasil diperbarui.');
    }

    public function destroy(TemplatePesan $template)
    {
        $this->permissions('hapus');
        $template->delete();

        return redirect()->route('admin.template.index')->with('success', 'Template berhasil dihapus.');
    }
}
