/* Portal Orang Tua — Rumah Qur'an Ibnu Abbas */
(function () {
  'use strict';

  const P = window.PORTAL || {};
  const routeMap = P.routes || {};
  const CSRF = P.csrf || '';
  const MONTH_NAMES = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

  const attendanceData = P.presensiData || {};
  const hafalanData = P.hafalanData || {};
  const calState = { presensi: { y: new Date().getFullYear(), m: new Date().getMonth() }, hafalan: { y: new Date().getFullYear(), m: new Date().getMonth() } };

  // ---------- Navigasi antar view ----------
  window.navigateView = function (name, sub) {
    if (!routeMap[name]) return;
    document.getElementById('pageLoading').classList.add('show');
    window.location.href = routeMap[name] + (sub ? '?tab=' + encodeURIComponent(sub) : '');
  };
  window.navigateProfileForm = function (form) {
    document.getElementById('pageLoading').classList.add('show');
    window.location.href = routeMap.profil + '?form=' + encodeURIComponent(form);
  };
  window.navigateProfileSummary = function () {
    document.getElementById('pageLoading').classList.add('show');
    window.location.href = routeMap.profil;
  };
  window.addEventListener('pageshow', () => document.getElementById('pageLoading').classList.remove('show'));

  window.toastr = window.toastr || {
    success: function () {},
    error: function (message) { if (message) alert(message); }
  };

  function showView(name) {
    const view = document.getElementById('view-' + name);
    if (!view) return;
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    view.classList.add('active');
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.toggle('active', b.dataset.view === name));
    document.querySelectorAll('.drawer-item[data-view]').forEach(b => b.classList.toggle('active', b.dataset.view === name));
    window.scrollTo({ top: 0, behavior: 'instant' });
  }

  function showSub(name) {
    document.querySelectorAll('.subview').forEach(v => v.classList.remove('active'));
    const target = document.getElementById('sub-' + name);
    if (target) target.classList.add('active');
    document.querySelectorAll('.subnav button').forEach(b => b.classList.toggle('active', b.dataset.sub === name));
  }
  window.showSub = showSub;

  // ---------- Drawer ----------
  window.openDrawer = function () {
    document.getElementById('drawer').classList.add('show');
    document.getElementById('drawerOverlay').classList.add('show');
  };
  window.closeDrawer = function () {
    document.getElementById('drawer').classList.remove('show');
    document.getElementById('drawerOverlay').classList.remove('show');
  };
  window.goFromDrawer = function (name) { closeDrawer(); navigateView(name); };

  // ---------- Filter kegiatan ----------
  window.filterKegiatan = function (btn, status) {
    document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.kegiatan-item').forEach(item => {
      item.style.display = (status === 'semua' || item.dataset.status === status) ? '' : 'none';
    });
  };

  // ---------- Radio pills ----------
  function initRadioPills() {
    document.querySelectorAll('.radio-pill').forEach(pill => {
      pill.addEventListener('click', () => {
        const group = pill.querySelector('input').name;
        document.querySelectorAll('input[name="' + group + '"]').forEach(inp => {
          inp.closest('.radio-pill').classList.remove('checked');
        });
        pill.querySelector('input').checked = true;
        pill.classList.add('checked');
      });
    });
  }

  // ---------- Wali toggle ----------
  window.toggleWali = function () {
    const same = document.getElementById('waliSameToggle').checked;
    document.getElementById('waliFields').style.display = same ? 'none' : 'block';
    document.getElementById('waliEmptyNote').style.display = same ? 'block' : 'none';
  };

  // ---------- Upload foto ----------
  window.previewPhoto = function (input, wrapId) {
    if (!input.files || !input.files[0]) return;
    document.getElementById(wrapId).innerHTML =
      '<img src="' + URL.createObjectURL(input.files[0]) + '" alt="Foto">';
  };

  function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.add('show');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => toast.classList.remove('show'), 2600);
  }

  // ---------- Simpan profil per section ----------
  function collectSection(pane, section) {
    const data = new FormData();
    data.append('_token', CSRF);
    pane.querySelectorAll('[name]').forEach(field => {
      if (section !== 'siswa' && !field.name.startsWith(section + '[')) return;
      if ((field.type === 'radio' || field.type === 'checkbox') && !field.checked) return;
      if (!field.disabled) data.append(field.name, field.value);
    });
    return data;
  }

  function initSaveButtons() {
    document.querySelectorAll('.profile-save').forEach(button => button.addEventListener('click', function () {
      const section = this.dataset.section;
      const pane = document.getElementById('sub-' + (section === 'ibu' ? 'ayah' : section));
      const data = collectSection(pane, section);
      this.disabled = true;
      this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
      document.getElementById('pageLoading').classList.add('show');

      fetch(routeMap.profilUpdate.replace('__SECTION__', section), {
        method: 'POST', body: data, headers: { 'Accept': 'application/json' }
      }).then(async response => {
        const payload = await response.json();
        if (!response.ok) throw payload;
        toastr.success(payload.message || 'Data berhasil disimpan.');
        setTimeout(navigateProfileSummary, 500);
      }).catch(error => {
        document.getElementById('pageLoading').classList.remove('show');
        this.disabled = false;
        this.textContent = 'Simpan';
        const message = error?.message || Object.values(error?.errors || {}).flat()[0] || 'Data gagal disimpan.';
        toastr.error(message);
      });
    }));
  }

  // ---------- Lampiran (dropzone realtime, tanpa submit) ----------
  function initLampiran() {
    function updateRowToDone(row, fileName, viewUrl, deleteUrl, isImage) {
      row.classList.add('done');
      row.classList.remove('uploading');
      if (isImage) row.classList.add('has-preview'); else row.classList.remove('has-preview');
      let preview = row.querySelector('.dropzone-preview');
      let overlay = row.querySelector('.dropzone-preview-overlay');
      if (isImage) {
        if (!preview) {
          preview = document.createElement('img');
          preview.className = 'dropzone-preview';
          preview.alt = fileName;
          row.insertBefore(preview, row.firstChild);
        }
        preview.src = viewUrl;
        if (!overlay) {
          overlay = document.createElement('div');
          overlay.className = 'dropzone-preview-overlay';
          preview.after(overlay);
        }
      } else {
        if (preview) preview.remove();
        if (overlay) overlay.remove();
        row.classList.remove('has-preview');
      }
      const icon = row.querySelector('.file-icon');
      if (icon) {
        icon.classList.add('done');
        icon.textContent = '✓';
        icon.style.display = isImage ? 'none' : '';
      }
      const status = row.querySelector('.f-status');
      if (status) { status.classList.add('done'); status.textContent = fileName; }
      const hint = row.querySelector('.f-hint');
      if (hint) hint.textContent = 'Tersimpan · klik untuk ganti';
      let delBtn = row.querySelector('.dropzone-remove');
      if (!delBtn) {
        delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.className = 'dropzone-remove portal-file-delete';
        delBtn.setAttribute('aria-label', 'Hapus');
        delBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18"/></svg>';
        row.appendChild(delBtn);
      }
      delBtn.dataset.url = deleteUrl;
      delBtn.style.display = 'grid';
    }

    function updateRowToEmpty(row) {
      row.classList.remove('done', 'uploading', 'dragover', 'has-preview');
      const preview = row.querySelector('.dropzone-preview');
      if (preview) preview.remove();
      const overlay = row.querySelector('.dropzone-preview-overlay');
      if (overlay) overlay.remove();
      const icon = row.querySelector('.file-icon');
      if (icon) { icon.classList.remove('done'); icon.textContent = '+'; icon.style.display = ''; }
      const status = row.querySelector('.f-status');
      if (status) { status.classList.remove('done'); status.textContent = 'Seret file ke sini atau klik'; }
      const hint = row.querySelector('.f-hint');
      if (hint) hint.textContent = 'JPG, PNG, PDF · maks 5MB';
      const delBtn = row.querySelector('.dropzone-remove');
      if (delBtn) delBtn.remove();
      const viewBtn = row.querySelector('.portal-file-view');
      if (viewBtn) viewBtn.remove();
      const oldDel = row.querySelector('.portal-file-delete:not(.dropzone-remove)');
      if (oldDel) oldDel.remove();
    }

    function uploadFile(file, row) {
      const max = 5 * 1024 * 1024;
      if (file.size > max) { toastr.error('File terlalu besar, maks 5MB.'); return; }
      if (!/^(image\/|application\/pdf)/.test(file.type) && !/\.(jpg|jpeg|png|pdf)$/i.test(file.name)) {
        toastr.error('Format harus JPG, PNG, atau PDF.');
        return;
      }
      const data = new FormData();
      data.append('_token', CSRF);
      data.append('jenis_dokumen', row.dataset.kind);
      data.append('file', file);
      row.classList.add('uploading');
      row.classList.remove('dragover');

      fetch(routeMap.lampiranUpload, { method: 'POST', body: data, headers: { 'Accept': 'application/json' } })
        .then(async response => {
          const result = await response.json();
          if (!response.ok) throw result;
          const f = result.file || {};
          const isImage = file.type.startsWith('image/') || /\.(jpg|jpeg|png)$/i.test(file.name);
          updateRowToDone(row, f.nama_asli || file.name, f.view_url || '', f.delete_url || '', isImage);
          toastr.success(result.message || 'Lampiran berhasil diunggah.');
        })
        .catch(error => {
          row.classList.remove('uploading');
          toastr.error(error?.message || Object.values(error?.errors || {}).flat()[0] || 'Upload gagal.');
        });
    }

    document.querySelectorAll('.portal-file-row.dropzone').forEach(row => {
      const input = row.querySelector('.portal-file-input');
      const trigger = row.querySelector('.portal-file-trigger');

      row.addEventListener('click', e => {
        if (e.target.closest('.portal-file-view') || e.target.closest('.portal-file-delete')) return;
        input.click();
      });
      if (trigger) trigger.addEventListener('click', e => { e.stopPropagation(); input.click(); });

      input.addEventListener('change', () => {
        if (input.files[0]) uploadFile(input.files[0], row);
        input.value = '';
      });

      row.addEventListener('dragover', e => { e.preventDefault(); row.classList.add('dragover'); });
      row.addEventListener('dragleave', e => {
        if (!row.contains(e.relatedTarget)) row.classList.remove('dragover');
      });
      row.addEventListener('drop', e => {
        e.preventDefault();
        row.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) uploadFile(file, row);
      });
    });

    // delegated view / delete (mendukung elemen yang baru dibuat realtime)
    document.addEventListener('click', e => {
      const view = e.target.closest('.portal-file-view');
      if (view) {
        e.stopPropagation();
        const modal = document.getElementById('portalFileModal');
        document.getElementById('portalFileFrame').src = view.dataset.url;
        modal.classList.add('show');
        return;
      }
      const del = e.target.closest('.portal-file-delete');
      if (del) {
        e.stopPropagation();
        if (!confirm('Hapus lampiran ini?')) return;
        const row = del.closest('.portal-file-row');
        del.disabled = true;
        fetch(del.dataset.url, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        }).then(async response => {
          const result = await response.json();
          if (!response.ok) throw result;
          updateRowToEmpty(row);
          toastr.success(result.message || 'Lampiran dihapus.');
        }).catch(error => {
          toastr.error(error?.message || 'Gagal menghapus lampiran.');
        }).finally(() => { del.disabled = false; });
      }
    });

    document.querySelector('#portalFileModal .btn-close')?.addEventListener('click', function () {
      const modal = document.getElementById('portalFileModal');
      modal.classList.remove('show');
      document.getElementById('portalFileFrame').src = 'about:blank';
    });
    document.getElementById('portalFileModal')?.addEventListener('click', function (e) {
      if (e.target === this) {
        this.classList.remove('show');
        document.getElementById('portalFileFrame').src = 'about:blank';
      }
    });
  }

  // ---------- Kalender ----------
  function pad(n) { return n < 10 ? '0' + n : '' + n; }
  function dateKey(y, m, d) { return y + '-' + pad(m + 1) + '-' + pad(d); }

  function renderCalendar(type) {
    const state = calState[type];
    const y = state.y, m = state.m;
    document.getElementById(type + 'CalTitle').textContent = MONTH_NAMES[m] + ' ' + y;
    const grid = document.getElementById(type + 'CalGrid');
    grid.innerHTML = '';

    const offset = (new Date(y, m, 1).getDay() + 6) % 7; // Monday-first
    const daysInMonth = new Date(y, m + 1, 0).getDate();
    const now = new Date();
    const todayStr = dateKey(now.getFullYear(), now.getMonth(), now.getDate());

    for (let i = 0; i < offset; i++) {
      const empty = document.createElement('div');
      empty.className = 'cal-day empty';
      grid.appendChild(empty);
    }

    for (let d = 1; d <= daysInMonth; d++) {
      const key = dateKey(y, m, d);
      const btn = document.createElement('button');
      btn.className = 'cal-day';
      btn.textContent = d;
      if (key === todayStr) btn.classList.add('today');

      if (type === 'presensi') {
        const status = attendanceData[key];
        if (status) btn.classList.add('st-' + status);
      } else if (type === 'hafalan') {
        if (hafalanData[key]) btn.classList.add('has-data');
        btn.addEventListener('click', () => selectHafalanDay(key, btn));
      }
      grid.appendChild(btn);
    }

    if (type === 'presensi') updateRecap(y, m);
  }

  window.calPrev = function (type) {
    const s = calState[type];
    s.m--; if (s.m < 0) { s.m = 11; s.y--; }
    renderCalendar(type);
  };
  window.calNext = function (type) {
    const s = calState[type];
    s.m++; if (s.m > 11) { s.m = 0; s.y++; }
    renderCalendar(type);
  };

  function updateRecap(y, m) {
    const counts = { ontime: 0, late: 0, izin: 0, sakit: 0, alpa: 0 };
    Object.keys(attendanceData).forEach(key => {
      const parts = key.split('-').map(Number);
      if (parts[0] === y && (parts[1] - 1) === m) counts[attendanceData[key]]++;
    });
    ['ontime', 'late', 'izin', 'sakit', 'alpa'].forEach(k => {
      const el = document.getElementById('recap' + k.charAt(0).toUpperCase() + k.slice(1));
      if (el) el.textContent = counts[k];
    });
  }

  function selectHafalanDay(key, btn) {
    document.querySelectorAll('#hafalanCalGrid .cal-day').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');

    const data = hafalanData[key];
    const panel = document.getElementById('hafalanDetail');
    const parts = key.split('-').map(Number);
    const dateLabel = parts[2] + ' ' + MONTH_NAMES[parts[1] - 1] + ' ' + parts[0];

    if (data) {
      panel.innerHTML =
        '<p class="dp-date">' + dateLabel + '</p>' +
        '<div class="detail-row">' +
          '<div class="detail-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg></div>' +
          '<div class="detail-text"><p class="dt-label">Hafalan Al-Qur\'an</p><p class="dt-value">QS. ' + data.surah + ' : ' + data.ayat + '</p></div>' +
        '</div>' +
        '<div class="detail-row">' +
          '<div class="detail-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H18a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6.5A2.5 2.5 0 0 1 4 17.5z"/><path d="M4 4.5v13A2.5 2.5 0 0 0 6.5 20H20"/></svg></div>' +
          '<div class="detail-text"><p class="dt-label">Hafalan Hadits</p><p class="dt-value">' + data.hadits + '</p></div>' +
        '</div>';
    } else {
      panel.innerHTML =
        '<p class="dp-date">' + dateLabel + '</p>' +
        '<p class="empty-note" style="padding:6px 0;">Belum ada catatan hafalan pada tanggal ini.</p>';
    }
  }

  // ---------- Init ----------
  document.addEventListener('DOMContentLoaded', function () {
    showView(P.activeView);

    const requestedSub = new URLSearchParams(window.location.search).get('tab');
    const requestedForm = P.profileForm;
    if (P.activeView === 'profil' && requestedForm && document.getElementById('sub-' + requestedForm)) {
      showSub(requestedForm);
    } else if (P.activeView === 'profil' && requestedSub && document.getElementById('sub-' + requestedSub)) {
      showSub(requestedSub);
    } else if (P.activeView === 'profil') {
      showSub(P.defaultSub || 'siswa');
    }
    document.getElementById('pageLoading').classList.remove('show');

    if (window.QRCode && document.getElementById('qrcode')) {
      new QRCode(document.getElementById('qrcode'), {
        text: P.qrText,
        width: 180,
        height: 180,
        colorDark: '#241242',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
      });
    }

    if (document.getElementById('presensiCalGrid')) renderCalendar('presensi');
    if (document.getElementById('hafalanCalGrid')) renderCalendar('hafalan');

    initRadioPills();
    initSaveButtons();
    initLampiran();
    document.querySelectorAll('.summary-row').forEach(row => row.addEventListener('click', function (event) {
      if (event.target.closest('a')) return;
      this.querySelector('.summary-link')?.click();
    }));
  });
})();
