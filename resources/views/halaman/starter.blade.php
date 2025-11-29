@extends('layouts.default', ['appThemePanel' => false])
@section('title', 'Dokumentasi Aplikasi')
@section('content')
<ol class="breadcrumb float-xl-end">
	<li class="breadcrumb-item"><a href="/">Home</a></li>
	<li class="breadcrumb-item active">Dashboard</li>
</ol>
<h1 class="page-header">Dokumentasi Aplikasi <small>Hak akses & fitur per level</small></h1>
<div class="row">
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-teal">
			<div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
			<div class="stats-content">
				<div class="stats-title">VISITS</div>
				<div class="stats-number">0</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-blue">
			<div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
			<div class="stats-content">
				<div class="stats-title">PROFIT</div>
				<div class="stats-number">0</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-indigo">
			<div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
			<div class="stats-content">
				<div class="stats-title">ORDERS</div>
				<div class="stats-number">0</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6">
		<div class="widget widget-stats bg-gray-900">
			<div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
			<div class="stats-content">
				<div class="stats-title">COMMENTS</div>
				<div class="stats-number">0</div>
			</div>
		</div>
	</div>
</div>
<div class="row mt-3">
  <div class="col-xl-12">
    @php $lv = Auth::user()->level ?? null; @endphp
    @if($lv === 1)
    <div class="panel panel-inverse">
      <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-user-shield me-2"></i> Ringkasan Level 1 — Admin</h4></div>
      <div class="panel-body">
        <ul class="mb-2">
          <li>Kelola pengguna: tambah/edit/hapus, set peran & status (<a href="{{ route('pengguna-index') }}">Pengguna</a>).</li>
          <li>Konfigurasi aplikasi: brand name, page title, caption, icon picker (modal), copyright, background upload ≤ 5MB (<a href="{{ route('app-settings') }}">App Settings</a>).</li>
          <li>Kelola izin menu per level (<a href="{{ route('menu-permissions') }}">Menu Permissions</a>).</li>
          <li>Lihat Audit Logs & Login Logs.</li>
        </ul>
        <p class="text-muted mb-0">Admin memiliki akses penuh ke seluruh menu APLIKASI.</p>
      </div>
    </div>
    @elseif($lv === 2)
    <div class="panel panel-inverse">
      <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-user me-2"></i> Ringkasan Level 2 — User</h4></div>
      <div class="panel-body">
        <ul class="mb-2">
          <li>Akses modul operasional sesuai izin menu.</li>
          <li>Tidak dapat mengubah App Settings atau izin menu.</li>
          <li>Dapat melihat item yang diizinkan sesuai konfigurasi.</li>
        </ul>
        <p class="text-muted mb-0">Hak akses dikontrol oleh konfigurasi menu (APLIKASI).</p>
      </div>
    </div>
    @elseif($lv === 3)
    <div class="panel panel-inverse">
      <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-user-tag me-2"></i> Ringkasan Level 3 — Viewer</h4></div>
      <div class="panel-body">
        <ul class="mb-2">
          <li>Hak baca saja pada modul yang diizinkan.</li>
          <li>Tidak dapat mengubah data, tanpa akses ke App Settings.</li>
          <li>Visibilitas menu bergantung pada konfigurasi per level.</li>
        </ul>
        <p class="text-muted mb-0">Ideal untuk akses monitoring/report.</p>
      </div>
    </div>
    @else
    <div class="panel panel-inverse">
      <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-user me-2"></i> Ringkasan Pengguna</h4></div>
      <div class="panel-body">
        <p class="mb-0">Masuk untuk melihat ringkasan sesuai level.</p>
      </div>
    </div>
    @endif
  </div>
</div>

<div class="row mt-3">
  <div class="col-xl-12">
    <div class="panel panel-inverse">
      <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-cogs me-2"></i> Ringkasan Fitur Utama</h4></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-6">
            <h5 class="mb-2">App Settings (DB-driven)</h5>
            <ul>
              <li>Brand & Page Title dipakai di header dan tag &lt;title&gt;.</li>
              <li>Caption Title/Text untuk halaman login.</li>
              <li>Icon Picker (FontAwesome) dalam modal, 70+ ikon umum lintas proyek.</li>
              <li>Upload background login ke public storage (≤ 5MB) otomatis mengisi URL.</li>
              <li>Copyright ditampilkan di footer login.</li>
            </ul>
          </div>
          <div class="col-md-6">
            <h5 class="mb-2">Autentikasi & Navigasi</h5>
            <ul>
              <li>User yang sudah login diarahkan dari `/login` ke `/starter`.</li>
              <li>Menu “APLIKASI” berisi sub menu: Pengguna, Audit Logs, App Settings, Menu Permissions, Login Logs.</li>
              <li>Menu terlihat sesuai level pengguna (1/2/3).</li>
              <li>Aktivitas menu dicatat melalui `menu_logs` untuk analitik.</li>
            </ul>
</div>
</div>
</div>
</div>

<div class="row mt-3">
  <div class="col-xl-12">
    <div class="panel panel-inverse">
      <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-list me-2"></i> Akses Anda</h4></div>
      <div class="panel-body">
        @php
          $lv = Auth::user()->level ?? null;
          $menus = \App\Models\MenuItem::buildMenuArrayWithFallback() ?? [];
          $acc = [];
          foreach ($menus as $m) {
            if (!empty($m['caret']) && !empty($m['sub_menu'])) {
              $subs = [];
              foreach ($m['sub_menu'] as $s) {
                if (empty($s['levels']) || in_array($lv, $s['levels'])) { $subs[] = $s; }
              }
              if (!empty($subs)) { $m['sub_menu'] = $subs; $acc[] = $m; }
            } else {
              if (empty($m['levels']) || in_array($lv, $m['levels'])) { $acc[] = $m; }
            }
          }
        @endphp
        @foreach($acc as $g)
          @if(!empty($g['caret']) && !empty($g['sub_menu']))
            <h5 class="mb-2"><i class="{{ $g['icon'] }}"></i> {{ $g['title'] }}</h5>
            <div class="d-flex flex-wrap gap-2 mb-3">
              @foreach($g['sub_menu'] as $sm)
                <a href="{{ $sm['url'] }}" class="btn btn-outline-primary btn-sm"><i class="{{ $sm['icon'] }}"></i> {{ $sm['title'] }}</a>
              @endforeach
            </div>
          @else
            <a href="{{ $g['url'] }}" class="btn btn-outline-primary btn-sm me-2"><i class="{{ $g['icon'] }}"></i> {{ $g['title'] }}</a>
          @endif
        @endforeach
      </div>
    </div>
  </div>
</div>
  </div>
</div>
@endsection
