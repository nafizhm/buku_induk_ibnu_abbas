@extends('admin.layout')
@section('content')
    @include('admin.siswa.form', ['title' => 'Edit Data Siswa', 'action' => route('siswa.update', $siswa->id), 'method' => 'PUT'])
@endsection
