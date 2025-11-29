@extends('layouts.default')
@section('title', 'Menu Permissions')

@section('content')
<ol class="breadcrumb float-xl-end">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Menu Permissions</li>
</ol>
<h1 class="page-header">Menu Permissions <small>Kelola level akses per route</small></h1>

@if (session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="panel panel-inverse">
  <div class="panel-heading">
    <h4 class="panel-title">Konfigurasi Akses</h4>
  </div>
  <div class="panel-body">
    <form action="{{ route('menu-permissions') }}" method="POST">
      @csrf
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-nowrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Route Name</th>
              <th>Judul</th>
              <th>Levels Diizinkan</th>
            </tr>
          </thead>
          <tbody>
          @php $i=1; $overrides = \App\Models\MenuPermission::all()->keyBy('route_name'); @endphp
          @foreach ($menuItems as $item)
            @php
              $route = $item['route-name'] ?? null;
              if (!$route) continue;
              $title = $item['title'] ?? $route;
              $levelsCfg = $item['levels'] ?? null;
              $rolesCfg = $item['roles'] ?? null;
              $levelsFromRoles = [];
              if ($rolesCfg) {
                foreach ((array)$rolesCfg as $r) {
                  if ($r==='admin') $levelsFromRoles[] = 1;
                  elseif ($r==='user') $levelsFromRoles[] = 2;
                  elseif ($r==='viewer') $levelsFromRoles[] = 3;
                }
              }
              $baseLevels = $levelsCfg ?: $levelsFromRoles ?: [];
              $ov = $overrides[$route] ?? null;
              $current = $ov ? (array)($ov->allowed_levels ?? []) : (array)$baseLevels;
              $has1 = in_array(1, $current);
              $has2 = in_array(2, $current);
              $has3 = in_array(3, $current);
            @endphp
            <tr>
              <td>{{ $i++ }}</td>
              <td><code>{{ $route }}</code></td>
              <td>{{ $title }}</td>
              <td>
                <label class="me-3"><input type="checkbox" name="permissions[{{ $route }}][]" value="1" {{ $has1 ? 'checked' : '' }}> Admin</label>
                <label class="me-3"><input type="checkbox" name="permissions[{{ $route }}][]" value="2" {{ $has2 ? 'checked' : '' }}> User</label>
                <label class="me-3"><input type="checkbox" name="permissions[{{ $route }}][]" value="3" {{ $has3 ? 'checked' : '' }}> Viewer</label>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
    </form>
  </div>
</div>
@endsection
