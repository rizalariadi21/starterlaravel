<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@include('includes.head')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="route-name" content="{{ Route::currentRouteName() }}">
	<meta name="user-id" content="{{ Auth::check() ? Auth::id() : '' }}">
	<meta name="session-lifetime" content="{{ config('session.lifetime') }}">
</head>
@php
	$bodyClass = (!empty($appBoxedLayout)) ? 'boxed-layout ' : '';
	$bodyClass .= (!empty($paceTop)) ? 'pace-top ' : $bodyClass;
	$bodyClass .= (!empty($bodyClass)) ? $bodyClass . ' ' : $bodyClass;
	$appSidebarHide = (!empty($appSidebarHide)) ? $appSidebarHide : '';
	$appHeaderHide = (!empty($appHeaderHide)) ? $appHeaderHide : '';
	$appSidebarTwo = (!empty($appSidebarTwo)) ? $appSidebarTwo : '';
	$appSidebarSearch = (!empty($appSidebarSearch)) ? $appSidebarSearch : '';
	$appSidebarWide = (!empty($appSidebarWide)) ? $appSidebarWide : '';
	$appTopMenu = (!empty($appTopMenu)) ? $appTopMenu : '';
	
	$appClass = (!empty($appClass)) ? $appClass .' ' : '';
	$appClass .= (!empty($appTopMenu)) ? 'app-with-top-menu ' : '';
	$appClass .= (!empty($appHeaderHide)) ? 'app-without-header ' : ' app-header-fixed ';
	$appClass .= (!empty($appSidebarEnd)) ? 'app-with-end-sidebar ' : '';
	$appClass .= (!empty($appSidebarWide)) ? 'app-with-wide-sidebar ' : '';
	$appClass .= (!empty($appSidebarHide)) ? 'app-without-sidebar ' : '';
	$appClass .= (!empty($appSidebarMinified)) ? 'app-sidebar-minified ' : '';
	$appClass .= (!empty($appSidebarTwo)) ? 'app-with-two-sidebar app-sidebar-end-toggled ' : '';
	$appClass .= (!empty($appSidebarHover)) ? 'app-with-hover-sidebar ' : '';
	$appClass .= (!empty($appContentFullHeight)) ? 'app-content-full-height ' : '';
	
	$appContentClass = (!empty($appContentClass)) ? $appContentClass : '';
@endphp
<body class="{{ $bodyClass }}">
	@include('includes.component.page-loader')
	
	<div id="app" class="app app-sidebar-fixed {{ $appClass }}">
		
		@includeWhen(!$appHeaderHide, 'includes.header')
		
		@includeWhen($appTopMenu, 'includes.top-menu')
		
		@includeWhen(!$appSidebarHide, 'includes.sidebar')
		
		@includeWhen($appSidebarTwo, 'includes.sidebar-right')
		
		<div id="content" class="app-content {{ $appContentClass }}">
			@yield('content')
		</div>
		
		@include('includes.component.scroll-top-btn')
		
		@includeWhen(!isset($appThemePanel) || $appThemePanel, 'includes.component.theme-panel')
		
	</div>
	
	@yield('outside-content')
	@include('includes.page-js')
	<script>
	(function(){
	  var route = document.querySelector('meta[name="route-name"]')?.content || '';
	  var userId = document.querySelector('meta[name="user-id"]')?.content || '';
	  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
	  if (!route || !userId) return;
	  var start = Date.now();
	  var url = window.location.href;
	  function payload(event, extra){
	    return JSON.stringify({route: route, user_id: userId || null, event: event, started_at: new Date(start).toISOString(), duration: extra && extra.duration || null, url: url});
	  }
	  fetch('/menu-activity', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': csrf}, body: payload('open')}).catch(function(){});
	  var sent = false;
	  function sendClose(){
	    if (sent) return; sent = true;
	    var dur = Math.round((Date.now()-start)/1000);
	    var data = new Blob([payload('close',{duration:dur})], {type:'application/json'});
	    if (navigator.sendBeacon) { navigator.sendBeacon('/menu-activity', data); }
	    else { fetch('/menu-activity', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': csrf}, body: payload('close',{duration:dur})}).catch(function(){}); }
	  }
	  window.addEventListener('visibilitychange', function(){ if (document.visibilityState==='hidden') sendClose(); });
	  window.addEventListener('pagehide', sendClose);
	  window.addEventListener('beforeunload', sendClose);
	})();
	</script>
	<script>
	(function(){
	  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
	  var userId = document.querySelector('meta[name="user-id"]')?.content || '';
	  if (!userId) return;
	  var idleMin = parseInt(document.querySelector('meta[name="session-lifetime"]')?.content||'0')||120;
	  var idleMs = Math.max(1, Math.min(idleMin, 30))*60*1000;
	  var t;
	  function reset(){ clearTimeout(t); t = setTimeout(function(){
	    fetch('/logout',{method:'POST', headers:{'X-CSRF-TOKEN': csrf}}).finally(function(){ location.href='/login'; });
	  }, idleMs); }
	  ['click','mousemove','keydown','scroll','touchstart'].forEach(function(e){ window.addEventListener(e, reset, {passive:true}); });
	  reset();
	})();
	</script>
</body>
</html>
