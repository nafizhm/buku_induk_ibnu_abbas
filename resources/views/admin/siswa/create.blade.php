@extends('admin.layout')
@section('content')
    @include('admin.siswa.form', ['title' => 'Tambah Data Siswa', 'action' => route('siswa.store'), 'method' => 'POST'])
@endsection
