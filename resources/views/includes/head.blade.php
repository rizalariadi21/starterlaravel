<meta charset="utf-8" />
<title>{{ \App\Models\Setting::get('login.brand_name', 'Color Admin') }} | @yield('title')</title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="/assets/plugins/animate.css/animate.min.css" rel="stylesheet" />
<link href="/assets/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
<link href="/assets/plugins/jquery-ui-dist/jquery-ui.min.css" rel="stylesheet" />
<link href="/assets/plugins/pace-js/themes/black/pace-theme-flash.css" rel="stylesheet" />
<link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
<script type="module" src="/assets/plugins/ionicons/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="/assets/plugins/ionicons/dist/ionicons/ionicons.js"></script>
@vite(['resources/scss/apple/styles.scss'])
<!-- ================== END BASE CSS STYLE ================== -->

@stack('css')
