@php
  $educationOptions = [
    '01) Tidak Sekolah'=>'01) Tidak Sekolah','02) Putus SD'=>'02) Putus SD','03) SD Sederajat'=>'03) SD Sederajat',
    '04) SMP Sederajat'=>'04) SMP Sederajat','05) SMA Sederajat'=>'05) SMA Sederajat','06) D1'=>'06) D1',
    '07) D2'=>'07) D2','08) D3'=>'08) D3','09) D4/S1'=>'09) D4/S1','10) S2'=>'10) S2','11) S3'=>'11) S3'];
  $jobOptions = [
    '01) Tidak Bekerja'=>'01) Tidak Bekerja','02) Nelayan'=>'02) Nelayan','03) Petani'=>'03) Petani',
    '04) Peternak'=>'04) Peternak','05) PNS/TNI/POLRI'=>'05) PNS/TNI/POLRI','06) Karyawan Swasta'=>'06) Karyawan Swasta',
    '07) Pedagang Kecil'=>'07) Pedagang Kecil','08) Pedagang Besar'=>'08) Pedagang Besar','09) Wiraswasta'=>'09) Wiraswasta',
    '10) Wirausaha'=>'10) Wirausaha','11) Buruh'=>'11) Buruh','12) Pensiunan'=>'12) Pensiunan','13) Meninggal Dunia'=>'13) Meninggal Dunia'];
  $incomeOptions = [
    '01) < Rp500.000'=>'01) < Rp500.000','02) Rp500.000-Rp999.999'=>'02) Rp500.000-Rp999.999',
    '03) Rp1.000.000-Rp1.999.999'=>'03) Rp1.000.000-Rp1.999.999','04) Rp2.000.000-Rp4.999.999'=>'04) Rp2.000.000-Rp4.999.999',
    '05) Rp5.000.000-Rp20.000.000'=>'05) Rp5.000.000-Rp20.000.000','06) > Rp20.000.000'=>'06) > Rp20.000.000',
    '07) Tidak Berpenghasilan'=>'07) Tidak Berpenghasilan'];
  $specialOptions = [
    '01) Tidak'=>'01) Tidak','02) Netra (A)'=>'02) Netra (A)','03) Rungu (B)'=>'03) Rungu (B)',
    '04) Grahita Ringan (C)'=>'04) Grahita Ringan (C)','05) Grahita Sedang (C1)'=>'05) Grahita Sedang (C1)',
    '06) Daksa Ringan (D)'=>'06) Daksa Ringan (D)','07) Daksa Sedang (D1)'=>'07) Daksa Sedang (D1)',
    '09) Wicara (F)'=>'09) Wicara (F)','10) Tuna Ganda (G)'=>'10) Tuna Ganda (G)','11) Hiperaktif (H)'=>'11) Hiperaktif (H)',
    '12) Cerdas Istimewa (I)'=>'12) Cerdas Istimewa (I)','13) Bakat Istimewa (J)'=>'13) Bakat Istimewa (J)',
    '14) Kesulitan Belajar (K)'=>'14) Kesulitan Belajar (K)','15) Narkoba (N)'=>'15) Narkoba (N)',
    '16) Indigo (O)'=>'16) Indigo (O)','17) Down Syndrome (P)'=>'17) Down Syndrome (P)','18) Autis (Q)'=>'18) Autis (Q)'];

  if ($familySection === 'wali') {
    $title='Data Wali';
    $fields=[
      ['nama_wali','Nama Wali','text',[],false], ['nik_wali','NIK Wali','text'], ['tahun_lahir_wali','Tahun Lahir','number'],
      ['pendidikan_wali','Pendidikan','select',$educationOptions], ['pekerjaan_wali','Pekerjaan','select',$jobOptions],
      ['penghasilan_wali','Penghasilan Bulanan','select',$incomeOptions],
    ];
  } else {
    $label=ucfirst($familySection); $title='Data '.$label.' Kandung';
    $fields=[
      ['nama_'.$familySection,'Nama '.$label.' Kandung','text',[],true], ['nik_'.$familySection,'NIK '.$label,'text'],
      ['tahun_lahir_'.$familySection,'Tahun Lahir','number'], ['pendidikan_'.$familySection,'Pendidikan','select',$educationOptions],
      ['pekerjaan_'.$familySection,'Pekerjaan','select',$jobOptions], ['penghasilan_'.$familySection,'Penghasilan Bulanan','select',$incomeOptions],
      ['berkebutuhan_'.$familySection,'Berkebutuhan Khusus','multiple',$specialOptions],
    ];
  }
@endphp

<div class="subview active dapodik-form" id="sub-{{ $familySection }}">
  <section class="dapodik-section">
    <div class="dapodik-section-head"><span>01</span><h2>{{ $title }}</h2></div>
    <div class="dapodik-grid">
      @foreach($fields as $field)
      @php
        [$name,$label,$type]=array_slice($field,0,3);$options=$field[3]??[];$required=$field[4]??false;
        $value=old($familySection.'.'.$name,data_get($orangTua,$name));$selected=is_array($value) ? $value : collect(explode(', ',(string)$value))->filter()->all();
      @endphp
      <div class="form-group {{ $type==='multiple' ? 'field-wide' : '' }}">
        <label for="family-{{ $name }}">{{ $label }} @if($required)<span class="req">*</span>@endif</label>
        @if($type==='select')
          <select id="family-{{ $name }}" name="{{ $familySection }}[{{ $name }}]" @required($required)><option value="">Pilih {{ $label }}</option>@foreach($options as $v=>$text)<option value="{{ $v }}" @selected((string)$value===(string)$v)>{{ $text }}</option>@endforeach</select>
        @elseif($type==='multiple')
          <select id="family-{{ $name }}" name="{{ $familySection }}[{{ $name }}][]" multiple size="6">@foreach($options as $v=>$text)<option value="{{ $v }}" @selected(in_array($v,$selected,true))>{{ $text }}</option>@endforeach</select><small class="field-help">Dapat dipilih lebih dari satu.</small>
        @else
          <input id="family-{{ $name }}" type="{{ $type }}" name="{{ $familySection }}[{{ $name }}]" value="{{ $value }}" @required($required)>
        @endif
      </div>
      @endforeach
    </div>
  </section>
  @if($showSaveButton ?? true)
  <button type="button" class="btn-primary profile-save dapodik-save-button" data-section="{{ $familySection }}" @if($returnToProfile ?? false) data-return-profile="true" @endif>Simpan {{ $title }}</button>
  @endif
</div>
