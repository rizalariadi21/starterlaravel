@extends('layouts.default')
@section('title', 'App Settings')

@section('content')
<ol class="breadcrumb float-xl-end">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">App Settings</li>
</ol>
<h1 class="page-header">App Settings <small>Konfigurasi halaman login</small></h1>

@if (session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="panel panel-inverse">
  <div class="panel-heading">
    <h4 class="panel-title">Login Page</h4>
  </div>
  <div class="panel-body">
    <form action="{{ route('app-settings-save') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">News Image URL</label>
          <input type="text" class="form-control" name="settings[login.news_image_url]" value="{{ $values['login.news_image_url'] ?? '' }}" placeholder="/assets/img/login-bg/login-bg-11.jpg" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Upload News Image</label>
          <input type="file" class="form-control" name="login_news_image_file" accept="image/*" />
          <div class="form-text">Maksimal 5MB (jpg, jpeg, png, webp, avif)</div>
          @if(!empty($values['login.news_image_url']))
          <div class="form-text">Current: <a href="{{ $values['login.news_image_url'] }}" target="_blank">{{ $values['login.news_image_url'] }}</a></div>
          @endif
        </div>
        <div class="col-md-6">
          <label class="form-label">Icon Class</label>
          <input type="text" class="form-control" name="settings[login.icon_class]" value="{{ $values['login.icon_class'] ?? '' }}" placeholder="fa fa-sign-in-alt" />
        </div>
        <div class="col-md-12">
          <label class="form-label">Icon Picker</label>
          @php
            $icons = [
              'fa fa-home','fa fa-user','fa fa-users','fa fa-lock','fa fa-key','fa fa-sign-in-alt','fa fa-sign-out-alt','fa fa-cog','fa fa-cogs','fa fa-wrench',
              'fa fa-shield-alt','fa fa-info-circle','fa fa-question-circle','fa fa-exclamation-circle','fa fa-exclamation-triangle','fa fa-bell','fa fa-bell-slash','fa fa-envelope','fa fa-paper-plane','fa fa-phone',
              'fa fa-globe','fa fa-map','fa fa-location-arrow','fa fa-compass','fa fa-search','fa fa-search-plus','fa fa-search-minus','fa fa-plus','fa fa-minus','fa fa-check',
              'fa fa-check-circle','fa fa-times','fa fa-times-circle','fa fa-edit','fa fa-pencil-alt','fa fa-save','fa fa-print','fa fa-copy','fa fa-cut','fa fa-paste',
              'fa fa-link','fa fa-unlink','fa fa-upload','fa fa-download','fa fa-file','fa fa-file-alt','fa fa-folder','fa fa-folder-open','fa fa-table','fa fa-list',
              'fa fa-list-ul','fa fa-list-ol','fa fa-bars','fa fa-star','fa fa-heart','fa fa-bookmark','fa fa-book','fa fa-calendar','fa fa-clock','fa fa-image',
              'fa fa-camera','fa fa-video','fa fa-database','fa fa-server','fa fa-cloud','fa fa-shopping-cart','fa fa-credit-card','fa fa-money-bill','fa fa-chart-bar','fa fa-chart-line',
              'fa fa-chart-pie','fa fa-terminal','fa fa-power-off','fa fa-trash','fa fa-anchor'
            ];
            $sel = $values['login.icon_class'] ?? '';
          @endphp
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#iconPickerModal">Pilih Icon</button>
            <span id="iconPreview" class="badge bg-light text-dark">
              <i class="{{ $sel }}"></i> <span class="ms-1">{{ $sel }}</span>
            </span>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Brand Name</label>
          <input type="text" class="form-control" name="settings[login.brand_name]" value="{{ $values['login.brand_name'] ?? '' }}" placeholder="Color Admin" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Brand Subtitle</label>
          <input type="text" class="form-control" name="settings[login.brand_subtitle]" value="{{ $values['login.brand_subtitle'] ?? '' }}" placeholder="Bootstrap 5 Responsive Admin Template" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Page Title</label>
          <input type="text" class="form-control" name="settings[login.page_title]" value="{{ $values['login.page_title'] ?? '' }}" placeholder="Login Page" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Caption Title</label>
          <input type="text" class="form-control" name="settings[login.caption_title]" value="{{ $values['login.caption_title'] ?? '' }}" placeholder="Color Admin App" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Caption Text</label>
          <textarea class="form-control" name="settings[login.caption_text]" rows="3" placeholder="Deskripsi singkat">{{ $values['login.caption_text'] ?? '' }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Login Button Text</label>
          <input type="text" class="form-control" name="settings[login.button_text]" value="{{ $values['login.button_text'] ?? '' }}" placeholder="Sign me in" />
        </div>
        <div class="col-md-12">
          <label class="form-label">Copyright</label>
          <input type="text" class="form-control" name="settings[login.copyright]" value="{{ $values['login.copyright'] ?? '' }}" placeholder="My Company © 2025" />
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>
<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pilih Icon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex flex-wrap gap-2" id="iconPickerGrid">
          @foreach($icons as $ic)
            <button type="button" class="btn btn-outline-secondary icon-choice @if($sel===$ic) active @endif" data-icon="{{ $ic }}">
              <i class="{{ $ic }}"></i>
            </button>
          @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
  </div>

<script>
(function(){
  var grid=document.getElementById('iconPickerGrid');
  var input=document.querySelector('input[name="settings[login.icon_class]"]');
  var preview=document.getElementById('iconPreview');
  if(!grid || !input) return;
  grid.addEventListener('click',function(e){
    var t=e.target.closest('.icon-choice'); if(!t) return;
    var ic=t.getAttribute('data-icon');
    input.value=ic;
    if(preview){ preview.innerHTML = '<i class="'+ic+'"></i> <span class="ms-1">'+ic+'</span>'; }
    var act=grid.querySelectorAll('.icon-choice.active'); act.forEach(function(b){b.classList.remove('active')});
    t.classList.add('active');
    var modalEl=document.getElementById('iconPickerModal');
    if(window.bootstrap && modalEl){ var m=bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl); m.hide(); }
  });
})();
</script>
<style>
.icon-choice{width:48px;height:48px;display:flex;align-items:center;justify-content:center}
.icon-choice i{font-size:20px}
.icon-choice.active{border-color:#0d6efd;background:#e7f1ff}
</style>
@endsection
