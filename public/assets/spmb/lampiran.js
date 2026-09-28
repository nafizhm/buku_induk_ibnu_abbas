(() => {
    const dialog = document.getElementById('preview-dialog');
    const stage = document.getElementById('preview-stage');
    const zoomControls = document.getElementById('image-zoom');
    let scale = 1;
    let activeImage = null;
    let opener = null;
    const updateZoom = () => {
        if (!activeImage) return;
        activeImage.style.width = `${scale * 100}%`;
        document.getElementById('zoom-level').textContent = `${Math.round(scale * 100)}%`;
        document.getElementById('zoom-out').disabled = scale <= 1;
        document.getElementById('zoom-in').disabled = scale >= 4;
    };
    document.addEventListener('click', event => {
        const trigger = event.target.closest('.preview-trigger');
        if (!trigger?.dataset.url) return;
        opener = trigger;
        const pdf = trigger.dataset.mime === 'application/pdf';
        document.getElementById('preview-title').textContent = trigger.dataset.title || 'Preview berkas';
        document.getElementById('preview-open').href = trigger.dataset.url;
        document.getElementById('preview-hint').textContent = pdf
            ? 'Jika PDF tidak tampil di HP, ketuk Buka penuh untuk melihatnya.'
            : 'Gunakan + untuk memperbesar, lalu geser gambar untuk melihat detail.';
        stage.replaceChildren();
        activeImage = null;
        zoomControls.hidden = pdf;
        if (pdf) {
            const frame = document.createElement('iframe');
            frame.title = trigger.dataset.title || 'Preview PDF';
            frame.src = trigger.dataset.url;
            stage.append(frame);
        } else {
            activeImage = document.createElement('img');
            activeImage.alt = trigger.dataset.title || 'Preview gambar';
            activeImage.src = trigger.dataset.url;
            stage.append(activeImage);
            scale = 1;
            updateZoom();
        }
        document.body.classList.add('preview-open');
        dialog.showModal();
        stage.scrollTo(0, 0);
    });
    document.getElementById('zoom-in').addEventListener('click', () => { scale = Math.min(4, scale + .5); updateZoom(); });
    document.getElementById('zoom-out').addEventListener('click', () => { scale = Math.max(1, scale - .5); updateZoom(); });
    document.getElementById('zoom-reset').addEventListener('click', () => { scale = 1; updateZoom(); stage.scrollTo(0, 0); });
    document.getElementById('preview-close').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('close', () => {
        stage.replaceChildren();
        activeImage = null;
        document.body.classList.remove('preview-open');
        opener?.focus();
    });
    let pendingUploads = 0;
    window.addEventListener('beforeunload', event => {
        if (pendingUploads) { event.preventDefault(); event.returnValue = ''; }
    });
    const bindCard = card => {
        const form = card.querySelector('.upload-actions');
        const input = form.querySelector('input[type="file"]');
        const selected = card.querySelector('.selected-preview');
        const feedback = card.querySelector('.upload-feedback');
        const message = card.querySelector('.upload-message');
        const button = form.querySelector('button');
        // Tombol manual tetap tersedia jika JavaScript tidak berjalan; tampil lagi untuk mencoba ulang saat gagal.
        button.hidden = true;
        let objectUrl;
        let uploading = false;
        const upload = async () => {
            if (uploading || !form.reportValidity()) return;
            const body = new FormData(form);
            uploading = true;
            pendingUploads++;
            card.setAttribute('aria-busy', 'true');
            card.querySelectorAll('input,button').forEach(control => { control.disabled = true; });
            feedback.hidden = false;
            feedback.className = 'upload-feedback loading';
            message.textContent = 'Mengunggah dan menyimpan berkas… Mohon tunggu.';
            let replaced = false;
            try {
                const response = await fetch(form.action, {method: 'POST', body, headers: {'Accept': 'application/json'}});
                const result = await response.json().catch(() => ({}));
                if (!response.ok || !result.html) {
                    throw new Error(result.errors ? Object.values(result.errors).flat().join(' ')
                        : response.status === 419 ? 'Sesi berakhir. Muat ulang halaman lalu pilih berkas kembali.'
                        : response.status === 413 ? 'Berkas terlalu besar. Pilih berkas maksimal 5 MB.'
                        : response.status === 429 ? 'Terlalu banyak unggahan. Tunggu sebentar lalu coba lagi.'
                        : 'Berkas belum berhasil disimpan. Silakan coba lagi.');
                }
                const template = document.createElement('template');
                template.innerHTML = result.html.trim();
                const nextCard = template.content.querySelector('.upload-card');
                if (!nextCard) throw new Error('Muat ulang halaman untuk memeriksa hasil upload.');
                card.replaceWith(nextCard);
                replaced = true;
                bindCard(nextCard);
                const done = nextCard.querySelector('.upload-feedback');
                done.hidden = false;
                done.className = 'upload-feedback saved';
                done.querySelector('.upload-message').textContent = '✓ Berkas berhasil disimpan. Gunakan Hapus berkas untuk menghapusnya.';
                if (objectUrl) URL.revokeObjectURL(objectUrl);
            } catch (error) {
                feedback.className = 'upload-feedback failed';
                message.textContent = error instanceof TypeError ? 'Koneksi terputus. Periksa koneksi lalu coba unggah kembali.' : error.message;
                button.hidden = false;
                button.textContent = 'Coba unggah lagi';
            } finally {
                uploading = false;
                pendingUploads--;
                if (!replaced) {
                    card.removeAttribute('aria-busy');
                    card.querySelectorAll('input,button').forEach(control => { control.disabled = false; });
                }
            }
        };
        input.addEventListener('change', () => {
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            selected.hidden = true;
            const file = input.files[0];
            const allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (input.accept.includes('application/pdf')) allowed.push('application/pdf');
            input.setCustomValidity(file && file.size > 5 * 1024 * 1024 ? 'Ukuran berkas maksimal 5 MB.'
                : file && file.type && !allowed.includes(file.type) ? 'Pilih gambar JPG, PNG, WebP atau PDF sesuai jenis dokumen.' : '');
            if (!file || !input.reportValidity()) return;
            objectUrl = URL.createObjectURL(file);
            const pdf = file.type === 'application/pdf';
            const img = selected.querySelector('img');
            img.hidden = pdf;
            if (!pdf) img.src = objectUrl;
            else img.removeAttribute('src');
            selected.querySelector('.pdf-placeholder').hidden = !pdf;
            Object.assign(selected.querySelector('.preview-trigger').dataset, {url: objectUrl, mime: file.type, title: file.name});
            selected.hidden = false;
            upload();
        });
        form.addEventListener('submit', event => {
            event.preventDefault();
            upload();
        });
        const deleteForm = card.querySelector('.delete-attachment');
        deleteForm?.addEventListener('submit', event => {
            if (pendingUploads) {
                event.preventDefault();
                window.alert('Tunggu hingga semua berkas selesai diunggah sebelum menghapus.');
                return;
            }
            if (!window.confirm(`Hapus ${deleteForm.dataset.title}? Berkas perlu diunggah kembali agar dokumen lengkap.`)) {
                event.preventDefault();
                return;
            }
            const button = deleteForm.querySelector('button');
            button.dataset.originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Menghapus…';
        });
    };
    document.querySelectorAll('.upload-card').forEach(bindCard);
    window.addEventListener('pageshow', () => {
        document.querySelectorAll('button[data-original-text]').forEach(button => {
            button.disabled = false;
            button.textContent = button.dataset.originalText;
        });
    });
})();
