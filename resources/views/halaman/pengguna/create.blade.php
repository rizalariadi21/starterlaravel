@extends('layouts.default')
@section('title', 'Tambah Pengguna')
@section('content')
<ol class="breadcrumb float-xl-end">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('pengguna-index') }}">Pengguna</a></li>
  <li class="breadcrumb-item active">Tambah</li>
</ol>
<h1 class="page-header">Tambah Pengguna</h1>

<div class="panel panel-inverse">
  <div class="panel-body">
    <form action="{{ route('pengguna-store') }}" method="POST" class="form">
      @csrf
      <div class="mb-3">
        <label class="form-label">Nama Pengguna</label>
        <input type="text" name="pengguna" value="{{ old('pengguna') }}" class="form-control" required>
        @error('pengguna')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Institusi</label>
        <input type="text" name="institusi" value="{{ old('institusi') }}" class="form-control">
        @error('institusi')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">No HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control">
        @error('no_hp')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control" required>
        @error('username')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="position-relative">
          <input type="password" name="password" class="form-control" id="passwordCreate" required>
          <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-gray-600" id="togglePasswordCreate" aria-label="Show password">
            <i class="fa fa-eye" id="togglePasswordCreateIcon"></i>
          </button>
        </div>
        @error('password')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Level</label>
        <select name="level" class="form-select" required>
          <option value="">-- pilih level --</option>
          <option value="1" @if(old('level')==1) selected @endif>Admin</option>
          <option value="2" @if(old('level')==2) selected @endif>User</option>
          <option value="3" @if(old('level')==3) selected @endif>Viewer</option>
        </select>
        @error('level')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="1" @if(old('status','1')=='1') selected @endif>Aktif</option>
          <option value="0" @if(old('status')=='0') selected @endif>Nonaktif</option>
        </select>
        @error('status')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
      <a href="{{ route('pengguna-index') }}" class="btn btn-default">Batal</a>
    </form>
  </div>
</div>
<script>
(function(){
  var btn = document.getElementById('togglePasswordCreate');
  var input = document.getElementById('passwordCreate');
  var icon = document.getElementById('togglePasswordCreateIcon');
  if(btn && input && icon){
    btn.addEventListener('click', function(){
      var isText = input.getAttribute('type') === 'text';
      input.setAttribute('type', isText ? 'password' : 'text');
      icon.className = isText ? 'fa fa-eye' : 'fa fa-eye-slash';
      btn.setAttribute('aria-label', isText ? 'Show password' : 'Hide password');
    });
  }
})();
</script>
@endsection
