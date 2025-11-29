<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;

class MainController extends Controller {
    public function dashboardV1() {
        return view('pages/dashboard-v1');
    }
    public function dashboardV2() {
        return view('pages/dashboard-v2');
    }
    public function dashboardV3() {
        return view('pages/dashboard-v3');
    }
    
    public function aiChat() {
        return view('pages/ai-chat');
    }
    public function aiImageGenerator() {
        return view('pages/ai-image-generator');
    }
    
    public function emailInbox() {
        return view('pages/email-inbox');
    }
    public function emailCompose() {
        return view('pages/email-compose');
    }
    public function emailDetail() {
        return view('pages/email-detail');
    }
    
    public function widget() {
        return view('pages/widget');
    }
    
    public function uiGeneral() {
        return view('pages/ui-general');
    }
    public function uiTypography() {
        return view('pages/ui-typography');
    }
    public function uiTabsAccordions() {
        return view('pages/ui-tabs-accordions');
    }
    public function uiUnlimitedNavTabs() {
        return view('pages/ui-unlimited-nav-tabs');
    }
    public function uiModalNotification() {
        return view('pages/ui-modal-notification');
    }
    public function uiWidgetBoxes() {
        return view('pages/ui-widget-boxes');
    }
    public function uiMediaObject() {
        return view('pages/ui-media-object');
    }
    public function uiButtons() {
        return view('pages/ui-buttons');
    }
    public function uiIconFontAwesome() {
        return view('pages/ui-icon-fontawesome');
    }
    public function uiIconBootstrapIcons() {
        return view('pages/ui-icon-bootstrap-icons');
    }
    public function uiIconDuotone() {
        return view('pages/ui-icon-duotone');
    }
    public function uiIconSimpleLineIcons() {
        return view('pages/ui-icon-simple-line-icons');
    }
    public function uiIconIonicons() {
        return view('pages/ui-icon-ionicons');
    }
    public function uiTreeView() {
        return view('pages/ui-tree-view');
    }
    public function uiLanguageBarIcon() {
        return view('pages/ui-language-bar-icon');
    }
    public function uiSocialButtons() {
        return view('pages/ui-social-buttons');
    }
    public function uiIntroJs() {
        return view('pages/ui-intro-js');
    }
    public function uiOffcanvasToasts() {
        return view('pages/ui-offcanvas-toasts');
    }
    
    public function bootstrap5() {
        return view('pages/bootstrap-5');
    }
    
    public function formElements() {
        return view('pages/form-elements');
    }
    public function formPlugins() {
        return view('pages/form-plugins');
    }
    public function formSliderSwitcher() {
        return view('pages/form-slider-switcher');
    }
    public function formValidation() {
        return view('pages/form-validation');
    }
    public function formWizards() {
        return view('pages/form-wizards');
    }
    public function formWysiwyg() {
        return view('pages/form-wysiwyg');
    }
    public function formXEditable() {
        return view('pages/form-x-editable');
    }
    public function formMultipleFileUpload() {
        return view('pages/form-multiple-file-upload');
    }
    public function formSummernote() {
        return view('pages/form-summernote');
    }
    public function formDropzone() {
        return view('pages/form-dropzone');
    }
    
    public function tableBasic() {
        return view('pages/table-basic');
    }
    public function tableManageDefault() {
        return view('pages/table-manage-default');
    }
    public function tableManageButtons() {
        return view('pages/table-manage-buttons');
    }
    public function tableManageColreorder() {
        return view('pages/table-manage-colreorder');
    }
    public function tableManageFixedColumn() {
        return view('pages/table-manage-fixed-column');
    }
    public function tableManageFixedHeader() {
        return view('pages/table-manage-fixed-header');
    }
    public function tableManageKeytable() {
        return view('pages/table-manage-keytable');
    }
    public function tableManageResponsive() {
        return view('pages/table-manage-responsive');
    }
    public function tableManageRowreorder() {
        return view('pages/table-manage-rowreorder');
    }
    public function tableManageScroller() {
        return view('pages/table-manage-scroller');
    }
    public function tableManageSelect() {
        return view('pages/table-manage-select');
    }
    public function tableManageCombine() {
        return view('pages/table-manage-combine');
    }
    
    public function posCustomerOrder() {
        return view('pages/pos-customer-order');
    }
    public function posKitchenOrder() {
        return view('pages/pos-kitchen-order');
    }
    public function posCounterCheckout() {
        return view('pages/pos-counter-checkout');
    }
    public function posTableBooking() {
        return view('pages/pos-table-booking');
    }
    public function posMenuStock() {
        return view('pages/pos-menu-stock');
    }
    
    public function emailTemplateSystem() {
        return view('pages/email-template-system');
    }
    public function emailTemplateNewsletter() {
        return view('pages/email-template-newsletter');
    }
    
    public function chartFlot() {
        return view('pages/chart-flot');
    }
    public function chartJs() {
        return view('pages/chart-js');
    }
    public function chartD3() {
        return view('pages/chart-d3');
    }
    public function chartApex() {
        return view('pages/chart-apex');
    }
    
    public function landing() {
        return view('pages/landing');
    }
    
    public function calendar() {
        return view('pages/calendar');
    }
    
    public function mapVector() {
        return view('pages/map-vector');
    }
    public function mapGoogle() {
        return view('pages/map-google');
    }
    
    public function galleryV1() {
        return view('pages/gallery-v1');
    }
    public function galleryV2() {
        return view('pages/gallery-v2');
    }
    
    public function pageBlank() {
        return view('pages/page-blank');
    }
    public function pageWithFooter() {
        return view('pages/page-with-footer');
    }
    public function pageWithFixedFooter() {
        return view('pages/page-with-fixed-footer');
    }
    public function pageWithoutSidebar() {
        return view('pages/page-without-sidebar');
    }
    public function pageWithRightSidebar() {
        return view('pages/page-with-right-sidebar');
    }
    public function pageWithMinifiedSidebar() {
        return view('pages/page-with-minified-sidebar');
    }
    public function pageWithTwoSidebar() {
        return view('pages/page-with-two-sidebar');
    }
    public function pageFullHeight() {
        return view('pages/page-full-height');
    }
    public function pageWithWideSidebar() {
        return view('pages/page-with-wide-sidebar');
    }
    public function pageWithLightSidebar() {
        return view('pages/page-with-light-sidebar');
    }
    public function pageWithMegaMenu() {
        return view('pages/page-with-mega-menu');
    }
    public function pageWithTopMenu() {
        return view('pages/page-with-top-menu');
    }
    public function pageWithBoxedLayout() {
        return view('pages/page-with-boxed-layout');
    }
    public function pageWithMixedMenu() {
        return view('pages/page-with-mixed-menu');
    }
    public function boxedLayoutWithMixedMenu() {
        return view('pages/page-boxed-layout-with-mixed-menu');
    }
    public function pageWithTransparentSidebar() {
        return view('pages/page-with-transparent-sidebar');
    }
    public function pageWithSearchSidebar() {
        return view('pages/page-with-search-sidebar');
    }
    public function pageWithHoverSidebar() {
        return view('pages/page-with-hover-sidebar');
    }
    
    public function extraTimeline() {
        return view('pages/extra-timeline');
    }
    public function extraComingSoon() {
        return view('pages/extra-coming-soon');
    }
    public function extraSearchResult() {
        return view('pages/extra-search-result');
    }
    public function extraInvoice() {
        return view('pages/extra-invoice');
    }
    public function extraErrorPage() {
        return view('pages/extra-error-page');
    }
    public function extraProfile() {
        return view('pages/extra-profile');
    }
    public function extraScrumBoard() {
        return view('pages/extra-scrum-board');
    }
    public function extraCookieAcceptanceBanner() {
        return view('pages/extra-cookie-acceptance-banner');
    }
    public function extraOrders() {
        return view('pages/extra-orders');
    }
    public function extraOrderDetails() {
        return view('pages/extra-order-details');
    }
    public function extraProducts() {
        return view('pages/extra-products');
    }
    public function extraProductDetails() {
        return view('pages/extra-product-details');
    }
    public function extraFileManager() {
        return view('pages/extra-file-manager');
    }
    public function extraPricingPage() {
        return view('pages/extra-pricing-page');
    }
    public function extraMessengerPage() {
        return view('pages/extra-messenger-page');
    }
    public function extraDataManagement() {
        return view('pages/extra-data-management');
    }
    public function extraSettingsPage() {
        return view('pages/extra-settings-page');
    }
    
    public function loginV1() {
        return view('pages/login-v1');
    }
    public function loginV2() {
        return view('pages/login-v2');
    }
    public function loginV3() {
        return view('pages/login-v3');
    }
    public function registerV3() {
        return view('pages/register-v3');
    }
    
    public function helperCss() {
        return view('pages/helper-css');
    }

    public function login(Request $request) {
        if (Auth::check()) { return redirect('/starter'); }
        $lockUntil = Cache::get('login_lock:' . $request->ip());
        $lockRemaining = ($lockUntil && $lockUntil > now()->timestamp) ? ($lockUntil - now()->timestamp) : 0;
        $newsImageUrl = \App\Models\Setting::get('login.news_image_url', '/assets/img/login-bg/login-bg-11.jpg');
        $captionTitle = \App\Models\Setting::get('login.caption_title', 'Color Admin App');
        $captionText  = \App\Models\Setting::get('login.caption_text', 'Download the Color Admin app for iPhone®, iPad®, and Android™. Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $brandName    = \App\Models\Setting::get('login.brand_name', 'Color Admin');
        $brandSubtitle= \App\Models\Setting::get('login.brand_subtitle', 'Bootstrap 5 Responsive Admin Template');
        $iconClass    = \App\Models\Setting::get('login.icon_class', 'fa fa-sign-in-alt');
        $loginButtonText = \App\Models\Setting::get('login.button_text', 'Sign me in');
        $copyright    = \App\Models\Setting::get('login.copyright', 'Color Admin All Right Reserved 2025');
        return view('halaman/login', [
            'lockRemaining' => $lockRemaining,
            'newsImageUrl' => $newsImageUrl,
            'captionTitle' => $captionTitle,
            'captionText'  => $captionText,
            'brandName'    => $brandName,
            'brandSubtitle'=> $brandSubtitle,
            'iconClass'    => $iconClass,
            'loginButtonText' => $loginButtonText,
            'copyright'    => $copyright,
        ]);
    }

    public function loginPost(\App\Http\Requests\LoginRequest $request) {
        $ip = $request->ip();
        $ua = (string) $request->header('User-Agent');
        $validated = $request->validated();
        $username = $validated['username'];
        $lockUntil = Cache::get("login_lock:$ip");
        if ($lockUntil && $lockUntil > now()->timestamp) {
            $remaining = $lockUntil - now()->timestamp;
            DB::table('login_logs')->insert([
                'username' => $username,
                'user_id' => null,
                'ip' => $ip,
                'success' => false,
                'message' => 'locked',
                'user_agent' => $ua,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect('/login')->withErrors(['login' => 'Terlalu banyak percobaan. Coba lagi dalam ' . $remaining . ' detik'])->with('lockRemaining', $remaining);
        }

        $credentials = [
            'username' => $username,
            'password' => $validated['password'],
            'status' => 1,
        ];
        $remember = (bool) ($validated['remember'] ?? false);
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Cache::forget("login_attempts:$ip");
            Cache::forget("login_lock:$ip");
            DB::table('login_logs')->insert([
                'username' => $username,
                'user_id' => Auth::id(),
                'ip' => $ip,
                'success' => true,
                'message' => 'login success',
                'user_agent' => $ua,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect('/starter');
        }

        $attempts = Cache::get("login_attempts:$ip", 0) + 1;
        Cache::put("login_attempts:$ip", $attempts, 120);
        if ($attempts >= 5) {
            $lockUntil = now()->addSeconds(60)->timestamp;
            Cache::put("login_lock:$ip", $lockUntil, 60);
            Cache::forget("login_attempts:$ip");
            $remaining = $lockUntil - now()->timestamp;
            DB::table('login_logs')->insert([
                'username' => $username,
                'user_id' => null,
                'ip' => $ip,
                'success' => false,
                'message' => 'locked: too many attempts',
                'user_agent' => $ua,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect('/login')->withErrors(['login' => 'Terlalu banyak percobaan. Coba lagi dalam ' . $remaining . ' detik'])->with('lockRemaining', $remaining);
        }

        DB::table('login_logs')->insert([
            'username' => $username,
            'user_id' => null,
            'ip' => $ip,
            'success' => false,
            'message' => 'invalid credentials (attempt ' . $attempts . ')',
            'user_agent' => $ua,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect('/login')->withErrors(['login' => 'Username atau password salah']);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function starter() {
        return view('halaman/starter');
    }

    public function penggunaIndex() {
        $users = DB::table('pengguna')->select('id_pengguna','pengguna','username','level')->get();
        return view('halaman/pengguna-index', ['users' => $users]);
    }

    public function auditLogs() {
        $logs = AuditLog::query()
            ->leftJoin('pengguna', 'pengguna.id_pengguna', '=', 'audit_logs.actor_id')
            ->select('audit_logs.*', 'pengguna.pengguna as actor_name', 'pengguna.username as actor_username', 'pengguna.level as actor_level')
            ->orderBy('audit_logs.created_at','desc')
            ->limit(200)
            ->get();
        return view('halaman/audit/index', ['logs' => $logs]);
    }

    public function loginLogs() {
        return view('halaman/login_logs/index');
    }

    public function menuActivity(Request $request) {
        $data = $request->validate([
            'route' => 'nullable|string|max:191',
            'user_id' => 'nullable|integer',
            'event' => 'required|string|in:open,close',
            'started_at' => 'nullable|string',
            'duration' => 'nullable|integer',
            'url' => 'nullable|string|max:255',
        ]);
        $userId = Auth::id();
        DB::table('menu_logs')->insert([
            'user_id' => $userId ?: ($data['user_id'] ?? null),
            'route_name' => $data['route'] ?? null,
            'event' => $data['event'],
            'started_at' => isset($data['started_at']) ? \Carbon\Carbon::parse($data['started_at']) : now(),
            'ended_at' => ($data['event'] === 'close' && isset($data['duration'])) ? now() : null,
            'duration_seconds' => $data['duration'] ?? null,
            'ip' => $request->ip(),
            'user_agent' => (string) $request->header('User-Agent'),
            'url' => $data['url'] ?? $request->fullUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['ok' => true]);
    }

    public function health() {
        $db = false; $cache = false; $queue = null;
        try { \Illuminate\Support\Facades\DB::select('SELECT 1'); $db = true; } catch (\Throwable $e) { $db = false; }
        try { \Illuminate\Support\Facades\Cache::put('health_check', 'ok', 5); $cache = (\Illuminate\Support\Facades\Cache::get('health_check') === 'ok'); } catch (\Throwable $e) { $cache = false; }
        $queue = config('queue.default');
        return response()->json(['db' => $db, 'cache' => $cache, 'queue' => $queue, 'time' => now()->toISOString()]);
    }

    public function menuPermissionsIndex() {
        $menus = config('menus.global') ?: config('sidebar.menu');
        $flat = [];
        $stack = is_array($menus) ? $menus : [];
        while ($stack) {
            $item = array_shift($stack);
            if (!is_array($item)) continue;
            $flat[] = $item;
            if (!empty($item['sub_menu'])) {
                foreach ($item['sub_menu'] as $sub) { $stack[] = $sub; }
            }
        }
        return view('halaman/menu_permissions/index', ['menuItems' => $flat]);
    }

    public function menuPermissionsSave(\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'permissions' => 'array',
        ]);
        $perms = (array) ($data['permissions'] ?? []);
        foreach ($perms as $route => $levels) {
            $levels = array_values(array_unique(array_map('intval', (array) $levels)));
            \App\Models\MenuPermission::updateOrCreate(
                ['route_name' => $route],
                ['allowed_levels' => $levels]
            );
        }
        return redirect()->back()->with('status', 'Menu permissions updated');
    }

    public function appSettingsIndex() {
        $keys = [
            'login.news_image_url',
            'login.caption_title',
            'login.caption_text',
            'login.brand_name',
            'login.brand_subtitle',
            'login.icon_class',
            'login.button_text',
            'login.page_title',
            'login.copyright',
        ];
        $values = [];
        foreach ($keys as $k) { $values[$k] = \App\Models\Setting::get($k, ''); }
        return view('halaman/app_settings/index', ['values' => $values]);
    }

    public function appSettingsSave(\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'settings' => 'array',
            'login_news_image_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:5120',
        ]);
        $settings = (array) ($data['settings'] ?? []);
        if ($request->hasFile('login_news_image_file')) {
            try {
                if (!file_exists(public_path('storage'))) { \Illuminate\Support\Facades\Artisan::call('storage:link'); }
            } catch (\Throwable $e) {}
            try {
                $path = $request->file('login_news_image_file')->store('login', 'public');
                $url = asset('storage/' . $path);
                $settings['login.news_image_url'] = $url;
            } catch (\Throwable $e) {}
        }
        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            \Illuminate\Support\Facades\Cache::forget('setting:' . $key);
        }
        return redirect()->back()->with('status', 'Settings updated');
    }
}
