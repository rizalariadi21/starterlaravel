@extends('layouts.default', [
	'paceTop' => true,
	'appSidebarHide' => true,
	'appHeaderHide' => true,
	'appContentClass' => 'p-0',
	'appThemePanel' => false
])

@section('title', \App\Models\Setting::get('login.page_title', 'Login Page'))

@section('content')
	<!-- BEGIN login -->
	<div class="login login-with-news-feed">
		<!-- BEGIN news-feed -->
        <div class="news-feed">
            <div class="news-image" style="background-image: url({{ $newsImageUrl }})"></div>
            <div class="news-caption">
                <h4 class="caption-title">{{ $captionTitle }}</h4>
                <p>{{ $captionText }}</p>
            </div>
        </div>
		<!-- END news-feed -->
		
		<!-- BEGIN login-container -->
		<div class="login-container">
			<!-- BEGIN login-header -->
			<div class="login-header mb-30px">
                <div class="brand">
                    <div class="d-flex align-items-center">
                        <span class="logo"></span>
                        <b>{{ $brandName }}</b>
                    </div>
                    <small>{{ $brandSubtitle }}</small>
                </div>
                <div class="icon">
                    <i class="{{ $iconClass }}"></i>
                </div>
			</div>
			<!-- END login-header -->
			
			<!-- BEGIN login-content -->
			<div class="login-content">
				<form action="/login" method="POST" class="fs-13px">
					@csrf
					@if ($errors->has('login'))
						<div class="alert alert-danger">{{ $errors->first('login') }}</div>
					@endif
					<div class="form-floating mb-15px">
						<input name="username" type="text" class="form-control h-45px fs-13px" placeholder="Username" id="username" />
						<label for="username" class="d-flex align-items-center fs-13px text-gray-600">Username</label>
					</div>
					<div class="form-floating mb-15px position-relative">
						<input name="password" type="password" class="form-control h-45px fs-13px" placeholder="Password" id="password" />
						<label for="password" class="d-flex align-items-center fs-13px text-gray-600">Password</label>
						<button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-gray-600" id="togglePassword" aria-label="Show password">
							<i class="fa fa-eye" id="togglePasswordIcon"></i>
						</button>
					</div>
					<script>
					(function(){
					  var btn = document.getElementById('togglePassword');
					  var input = document.getElementById('password');
					  var icon = document.getElementById('togglePasswordIcon');
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

					@php($lockRemaining = session('lockRemaining', isset($lockRemaining) ? $lockRemaining : 0))
					@php($disabled = $lockRemaining > 0)
					<div class="mb-15px">
                        <button id="loginSubmitBtn" type="submit" class="btn btn-theme d-block h-45px w-100 btn-lg fs-14px" @if($disabled) disabled @endif><i class="fa fa-sign-in-alt me-2"></i> {{ $loginButtonText }}</button>
						@if($disabled)
							<div id="lockMessage" class="text-danger mt-2">Tunggu <span id="lockCountdown">{{ $lockRemaining }}</span> detik sebelum mencoba lagi.</div>
							<script>
							(function(){
							  var remaining = Number({{ $lockRemaining }});
							  if(!isNaN(remaining) && remaining > 0){
							    var btn = document.getElementById('loginSubmitBtn');
							    var msg = document.getElementById('lockMessage');
							    var span = document.getElementById('lockCountdown');
							    function tick(){
							      if(remaining <= 0){
							        if(btn) btn.removeAttribute('disabled');
							        if(msg) msg.classList.add('d-none');
							        return;
							      }
							      if(span) span.textContent = remaining;
							      remaining--;
							      setTimeout(tick, 1000);
							    }
							    tick();
							  }
							})();
							</script>
						@endif
					</div>

					<hr class="bg-gray-600 opacity-2" />
                    <div class="text-gray-600 text-center text-gray-500-darker mb-0">
                        &copy; {{ $copyright }}
                    </div>
				</form>
			</div>
			<!-- END login-content -->
        </div>
        <!-- END login-container -->
    </div>
    <!-- END login -->
    {{-- <div class="text-center text-gray-600">
        &copy; {{ $copyright }}
    </div> --}}
@endsection
