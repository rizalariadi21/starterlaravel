@extends('layouts.default')
@section('title', 'Pengguna')
@section('content')
<ol class="breadcrumb float-xl-end">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Pengguna</li>
</ol>
<h1 class="page-header">Pengguna <small>Manage data pengguna</small></h1>
<div class="panel panel-inverse">
    <div class="panel-heading d-flex align-items-center justify-content-between">
        <h4 class="panel-title mb-0">Daftar Pengguna</h4>
        <div class="panel-heading-btn d-flex align-items-center gap-2">
            <a href="{{ route('pengguna-create') }}" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>{{ $u->id_pengguna }}</td>
                        <td>{{ $u->pengguna }}</td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->level }}</td>
                        <td>
                            <a href="{{ route('pengguna-edit', $u->id_pengguna) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{ route('pengguna-destroy', $u->id_pengguna) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
