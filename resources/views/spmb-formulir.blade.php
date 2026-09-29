<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Dapodik · SPMB Ibnu Abbas</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f7f5fb;color:#30283f;font:16px/1.5 system-ui,sans-serif}main{max-width:850px;margin:auto;padding:24px 16px 40px}header{margin-bottom:24px}h1{font-size:26px;margin:6px 0}header p{margin:8px 0;color:#655b73}.badge{font-size:12px;font-weight:700;letter-spacing:1px;color:#6d3db3}.dapodik-section{background:white;border:1px solid #e5deef;border-radius:14px;margin-bottom:20px;overflow:hidden}.dapodik-section-head{display:flex;align-items:center;gap:12px;background:#f0eafa;padding:14px 18px}.dapodik-section-head span{background:#7141b5;color:white;border-radius:50%;width:32px;height:32px;display:grid;place-items:center;font-size:13px}.dapodik-section-head h2{font-size:17px;margin:0}.dapodik-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;padding:20px}.field-wide{grid-column:1/-1}label{display:block;font-size:14px;font-weight:600;margin-bottom:6px}input,select,textarea{width:100%;min-width:0;padding:11px;border:1px solid #cfc4dd;border-radius:8px;background:white;color:#30283f;font:inherit}textarea{min-height:100px}input:focus,select:focus,textarea:focus{outline:2px solid #8651cf;outline-offset:2px}.req{color:#b42318}.field-help{display:block;color:#71667e;margin-top:6px;font-size:12px}.actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:20px}button{border:0;border-radius:10px;padding:14px 22px;font:600 15px system-ui;cursor:pointer;background:#7141b5;color:white}button.secondary{background:#ede5f7;color:#59318b}button:not(:disabled):hover{background:#5e329b}button.secondary:not(:disabled):hover{background:#e2d5f2}button:focus-visible{outline:3px solid #8651cf;outline-offset:3px}button:disabled{opacity:.6;cursor:wait}#feedback{white-space:pre-line;border-radius:10px;padding:14px;margin:16px 0;background:#f0eafa}#feedback.error{background:#fff0ed;color:#a42620}[aria-invalid=true]{border-color:#b42318}.note{color:#6b6078;font-size:14px}@media(max-width:600px){.dapodik-grid{grid-template-columns:1fr;padding:16px}.field-wide{grid-column:auto}h1{font-size:23px}.actions button{flex:1;padding:14px 10px}}
    </style>
</head>
<body>
<main>
    <header>
        <a href="{{ route('spmb.formulir', $spmb->token) }}" style="display:block;padding:10px 0;margin-bottom:12px;color:#693ca8;text-decoration:none;font-size:14px;font-weight:600">← Kembali ke Registrasi</a>
        <span class="badge">SPMB · RUMAH QUR'AN IBNU ABBAS</span>
        <h1>{{ $spmb->status === 'selesai' ? 'Edit Data Formulir' : 'Formulir Peserta Didik' }}</h1>
        <p>Lengkapi data Dapodik calon santri <strong>{{ $spmb->nama }}</strong>.</p>
        <p>Jenjang pendaftaran: <strong>{{ $spmb->jenjang }}</strong></p>
        @if($spmb->status === 'selesai')
            <p class="note">Perbarui data yang diperlukan, lalu klik Simpan Perubahan.</p>
        @elseif(config('spmb.allow_incomplete_forms'))
            <p class="note">Untuk sementara, formulir dapat disimpan atau dikirim meskipun belum lengkap. Simpan draf jika ingin melanjutkan pengisian nanti.</p>
        @else
            <p class="note">Isian bertanda <span class="req">*</span> wajib dilengkapi sebelum dikirim. Data wali bersifat opsional. Simpan draf untuk melanjutkan melalui tautan WA yang sama.</p>
        @endif
    </header>
    <div id="feedback" role="status" aria-live="polite" hidden></div>
    <form id="dapodik-form" action="{{ route('spmb.formulir.store', $spmb->token) }}" method="post">
        @csrf
        @include('orang-tua.partials.profil-siswa-dapodik', ['showSaveButton' => false, 'allowIncomplete' => config('spmb.allow_incomplete_forms')])
        @foreach(['ayah', 'ibu', 'wali'] as $section)
            @include('orang-tua.partials.profil-keluarga-dapodik', ['familySection' => $section, 'showSaveButton' => false, 'allowIncomplete' => config('spmb.allow_incomplete_forms')])
        @endforeach
        <p class="note">Periksa kembali data sebelum disimpan. Data dapat diperbarui melalui tombol Edit Data Formulir.</p>
        <div class="actions">
            @if($spmb->status !== 'selesai')<button type="submit" class="secondary" value="draft" formnovalidate>Simpan Draf</button>@endif
            <button type="submit" value="selesai">{{ $spmb->status === 'selesai' ? 'Simpan Perubahan' : 'Kirim Formulir' }}</button>
        </div>
    </form>
</main>
<script>
    const form = document.getElementById('dapodik-form');
    const feedback = document.getElementById('feedback');
    const spmbUrl = form.action;
    @if($spmb->jenjang === 'SMP')
    const gender = form.elements.namedItem('jenis_kelamin');
    gender.querySelectorAll('option').forEach(option => { if (option.value !== 'L') option.remove(); });
    gender.value = 'L';
    @endif
    let dirty = false;
    form.addEventListener('input', () => { dirty = true; });
    form.addEventListener('change', () => { dirty = true; });
    window.addEventListener('beforeunload', event => { if (dirty) { event.preventDefault(); event.returnValue = ''; } });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const selesai = event.submitter?.value === 'selesai';
        if (selesai && !window.confirm(@json($spmb->status === 'selesai' ? 'Simpan perubahan formulir? Pastikan seluruh data sudah benar.' : 'Kirim formulir? Pastikan seluruh data sudah benar.'))) return;
        const body = new FormData(form);
        body.set('_selesai', selesai ? '1' : '0');
        // Kirim pilihan kosong juga agar pilihan lama dapat dihapus saat menyimpan ulang.
        form.querySelectorAll('select[multiple]').forEach(select => {
            if (!select.selectedOptions.length) body.append(select.name, '');
        });
        form.querySelectorAll('[aria-invalid]').forEach(field => field.removeAttribute('aria-invalid'));
        const buttons = form.querySelectorAll('button');
        buttons.forEach(button => button.disabled = true);
        feedback.hidden = false;
        feedback.className = '';
        feedback.textContent = 'Menyimpan formulir…';
        try {
            const response = await fetch(spmbUrl, {method: 'POST', body, headers: {'Accept': 'application/json'}});
            const result = await response.json().catch(() => ({}));
            if (!response.ok) {
                let firstInvalid;
                Object.keys(result.errors || {}).forEach(key => {
                    const parts = key.split('.');
                    const name = parts.shift() + parts.map(part => '[' + part + ']').join('');
                    const field = form.elements.namedItem(name) || form.elements.namedItem(name + '[]');
                    if (field?.setAttribute) { field.setAttribute('aria-invalid', 'true'); firstInvalid ||= field; }
                });
                firstInvalid?.focus();
                throw new Error(result.errors ? Object.values(result.errors).flat().join('\n') : (response.status === 419 ? 'Sesi kedaluwarsa. Muat ulang halaman lalu coba kembali.' : result.message || 'Formulir gagal disimpan. Silakan coba kembali.'));
            }
            dirty = false;
            if (result.selesai) { window.location.assign(spmbUrl); return; }
            feedback.textContent = 'Draf berhasil disimpan. Anda dapat melanjutkan melalui tautan WA yang sama.';
        } catch (error) {
            feedback.className = 'error';
            feedback.textContent = error.message || 'Koneksi terputus. Silakan coba kembali.';
        } finally {
            buttons.forEach(button => button.disabled = false);
            feedback.scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    });
</script>
</body>
</html>
