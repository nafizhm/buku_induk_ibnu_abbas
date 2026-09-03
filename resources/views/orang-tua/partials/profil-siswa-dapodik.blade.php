<div class="subview active dapodik-form" id="sub-siswa">
  @php
    $groups = [
      'Data Pribadi' => [
        ['nama_lengkap','Nama Lengkap','text',[],true],
        ['jenis_kelamin','Jenis Kelamin','select',['L'=>'01) Laki-laki','P'=>'02) Perempuan'],true],
        ['nisn','NISN','text'], ['nik','NIK / No. KITAS (untuk WNA)','text'], ['no_kk','Nomor KK','text'],
        ['tempat_lahir','Tempat Lahir','text'], ['tanggal_lahir','Tanggal Lahir','date'],
        ['no_akta','Nomor Registrasi Akta Lahir','text'],
        ['agama','Agama dan Kepercayaan','select',[
          '01) Islam'=>'01) Islam','02) Kristen/Protestan'=>'02) Kristen/Protestan','03) Katolik'=>'03) Katolik',
          '04) Hindu'=>'04) Hindu','05) Buddha'=>'05) Buddha','06) Konghucu'=>'06) Konghucu',
          '07) Kepercayaan Kepada Tuhan YME'=>'07) Kepercayaan Kepada Tuhan YME']],
        ['kewarganegaraan','Kewarganegaraan','select',['Indonesia (WNI)'=>'Indonesia (WNI)','Asing (WNA)'=>'Asing (WNA)']],
        ['nama_negara','Nama Negara (untuk WNA)','text'],
        ['jenis_kebutuhan_khusus','Berkebutuhan Khusus','multiple',[
          '01) Tidak'=>'01) Tidak','02) Netra (A)'=>'02) Netra (A)','03) Rungu (B)'=>'03) Rungu (B)',
          '04) Grahita Ringan (C)'=>'04) Grahita Ringan (C)','05) Grahita Sedang (C1)'=>'05) Grahita Sedang (C1)',
          '06) Daksa Ringan (D)'=>'06) Daksa Ringan (D)','07) Daksa Sedang (D1)'=>'07) Daksa Sedang (D1)',
          '09) Wicara (F)'=>'09) Wicara (F)','10) Tuna Ganda (G)'=>'10) Tuna Ganda (G)',
          '11) Hiperaktif (H)'=>'11) Hiperaktif (H)','12) Cerdas Istimewa (I)'=>'12) Cerdas Istimewa (I)',
          '13) Bakat Istimewa (J)'=>'13) Bakat Istimewa (J)','14) Kesulitan Belajar (K)'=>'14) Kesulitan Belajar (K)',
          '15) Narkoba (N)'=>'15) Narkoba (N)','16) Indigo (O)'=>'16) Indigo (O)',
          '17) Down Syndrome (P)'=>'17) Down Syndrome (P)','18) Autis (Q)'=>'18) Autis (Q)']],
      ],
      'Alamat Tempat Tinggal' => [
        ['alamat','Alamat Jalan','textarea'], ['rt','RT','text'], ['rw','RW','text'], ['dusun','Nama Dusun','text'],
        ['desa_kelurahan','Nama Kelurahan / Desa','text'], ['kecamatan','Kecamatan','text'], ['kode_pos','Kode Pos','text'],
        ['lintang','Lintang','number'], ['bujur','Bujur','number'],
        ['status_tempat_tinggal','Tempat Tinggal','select',[
          '01) Bersama Orang Tua'=>'01) Bersama Orang Tua','02) Wali'=>'02) Wali','03) Kos'=>'03) Kos',
          '04) Asrama'=>'04) Asrama','05) Panti Asuhan'=>'05) Panti Asuhan']],
        ['moda_transportasi','Moda Transportasi','select',[
          '01) Jalan Kaki'=>'01) Jalan Kaki','02) Kendaraan Pribadi'=>'02) Kendaraan Pribadi',
          '03) Kendaraan Umum/Angkot/Pete-pete'=>'03) Kendaraan Umum/Angkot/Pete-pete',
          '04) Jemputan Sekolah'=>'04) Jemputan Sekolah','05) Kereta Api'=>'05) Kereta Api','06) Ojek'=>'06) Ojek',
          '07) Andong/Bendi/Sado/Dokar/Delman/Becak'=>'07) Andong/Bendi/Sado/Dokar/Delman/Becak',
          '08) Perahu Penyeberangan/Rakit/Getek'=>'08) Perahu Penyeberangan/Rakit/Getek','99) Lainnya'=>'99) Lainnya']],
        ['anak_ke','Anak Keberapa','number'],
        ['pekerjaan','Pekerjaan (untuk warga belajar)','select',[
          '01) Tidak Bekerja'=>'01) Tidak Bekerja','02) Nelayan'=>'02) Nelayan','03) Petani'=>'03) Petani',
          '04) Peternak'=>'04) Peternak','05) PNS/TNI/POLRI'=>'05) PNS/TNI/POLRI','06) Karyawan Swasta'=>'06) Karyawan Swasta',
          '07) Pedagang Kecil'=>'07) Pedagang Kecil','08) Pedagang Besar'=>'08) Pedagang Besar',
          '09) Wiraswasta'=>'09) Wiraswasta','10) Wirausaha'=>'10) Wirausaha','11) Buruh'=>'11) Buruh','12) Pensiunan'=>'12) Pensiunan']],
      ],
      'Kartu Indonesia Pintar (KIP)' => [
        ['punya_kip','Apakah Punya KIP','select',['01) Ya'=>'01) Ya','02) Tidak'=>'02) Tidak']],
        ['terima_kip','Tetap Akan Menerima KIP','select',['01) Ya'=>'01) Ya','02) Tidak'=>'02) Tidak']],
        ['alasan_tolak_pip','Alasan Menolak PIP','select',[
          '01) Dilarang Pemda karena menerima bantuan serupa'=>'01) Dilarang Pemda karena menerima bantuan serupa',
          '02) Menolak'=>'02) Menolak','03) Sudah Mampu'=>'03) Sudah Mampu']],
      ],
      'Kontak' => [
        ['no_telepon_rumah','Nomor Telepon Rumah','tel'], ['no_hp','Nomor HP','tel'], ['email','Email','email'],
      ],
      'Data Periodik' => [
        ['tinggi_badan','Tinggi Badan (cm)','number'], ['berat_badan','Berat Badan (kg)','number'],
        ['lingkar_kepala','Lingkar Kepala (cm)','number'], ['jarak_sekolah','Jarak Tempat Tinggal ke Sekolah (km)','number'],
        ['waktu_jam','Waktu Tempuh ke Sekolah (jam)','number'], ['waktu_menit','Waktu Tempuh ke Sekolah (menit)','number'],
        ['jumlah_saudara_kandung','Jumlah Saudara Kandung','number'],
      ],
      'Kesejahteraan Peserta Didik' => [
        ['jenis_kesejahteraan','Jenis Kesejahteraan','select',[
          '01) PKH'=>'01) PKH','02) PIP'=>'02) PIP','03) Kartu Perlindungan Sosial'=>'03) Kartu Perlindungan Sosial',
          '04) Kartu Keluarga Sejahtera'=>'04) Kartu Keluarga Sejahtera','05) Kartu Kesehatan'=>'05) Kartu Kesehatan']],
        ['no_kartu','Nomor Kartu','text'], ['nama_di_kartu','Nama di Kartu','text'],
      ],
      'Registrasi Peserta Didik' => [
        ['kompetensi_keahlian','Kompetensi Keahlian','text'],
        ['jenis_pendaftaran','Jenis Pendaftaran','select',[
          '01) Siswa Baru'=>'01) Siswa Baru','02) Pindahan'=>'02) Pindahan','03) Kembali Bersekolah'=>'03) Kembali Bersekolah']],
        ['nipd','NIS / Nomor Induk Peserta Didik','text'], ['tanggal_masuk_sekolah','Tanggal Masuk Sekolah','date'],
        ['sekolah_asal','Sekolah Asal','text'], ['no_peserta_un','Nomor Peserta UN SMP/MTs','text'],
        ['no_seri_ijazah','Nomor Seri Ijazah SMP/MTs','text'], ['no_skhun','Nomor SKHUN SMP/MTs','text'],
      ],
      'Pendaftaran Keluar' => [
        ['keluar_karena','Keluar Karena','select',[
          '01) Mutasi'=>'01) Mutasi','02) Dikeluarkan'=>'02) Dikeluarkan','03) Mengundurkan Diri'=>'03) Mengundurkan Diri',
          '04) Putus Sekolah'=>'04) Putus Sekolah','05) Wafat'=>'05) Wafat','06) Hilang'=>'06) Hilang']],
        ['tanggal_keluar','Tanggal Keluar','date'], ['alasan_keluar','Alasan Keluar','textarea'],
      ],
    ];
  @endphp

  @foreach($groups as $group => $fields)
    <section class="dapodik-section">
      <div class="dapodik-section-head"><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><h2>{{ $group }}</h2></div>
      <div class="dapodik-grid">
      @foreach($fields as $field)
        @php
          [$name,$label,$type]=array_slice($field,0,3); $options=$field[3]??[]; $required=$field[4]??false;
          $value=old($name,data_get($siswa,$name)); if($value instanceof \Carbon\CarbonInterface)$value=$value->format('Y-m-d');
          $selectedMultiple=is_array($value) ? $value : collect(explode(', ',(string)$value))->filter()->all();
          $wide=in_array($type,['textarea','multiple'],true) || in_array($name,['nama_lengkap','alamat'],true);
        @endphp
        <div class="form-group {{ $wide ? 'field-wide' : '' }}">
          <label for="dapodik-{{ $name }}">{{ $label }} @if($required)<span class="req">*</span>@endif</label>
          @if($type==='textarea')
            <textarea id="dapodik-{{ $name }}" name="{{ $name }}">{{ $value }}</textarea>
          @elseif($type==='select')
            <select id="dapodik-{{ $name }}" name="{{ $name }}" @required($required)><option value="">Pilih {{ $label }}</option>@foreach($options as $optionValue=>$optionLabel)<option value="{{ $optionValue }}" @selected((string)$value===(string)$optionValue)>{{ $optionLabel }}</option>@endforeach</select>
          @elseif($type==='multiple')
            <select id="dapodik-{{ $name }}" name="{{ $name }}[]" multiple size="6">@foreach($options as $optionValue=>$optionLabel)<option value="{{ $optionValue }}" @selected(in_array($optionValue,$selectedMultiple,true))>{{ $optionLabel }}</option>@endforeach</select>
            <small class="field-help">Dapat dipilih lebih dari satu.</small>
          @else
            <input id="dapodik-{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" @if($type==='number') step="any" @endif @required($required)>
          @endif
        </div>
      @endforeach
      </div>
    </section>
  @endforeach
  @if($showSaveButton ?? true)
  <button type="button" class="btn-primary profile-save dapodik-save-button" data-section="siswa">Simpan Profil Siswa</button>
  @endif
</div>
