<?php

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/starter', 'MainController@starter')->name('starter')->middleware(['auth','menu.level']);
Route::get('/dashboard/v2', 'MainController@dashboardV2')->name('dashboard-v2');
Route::get('/login', 'MainController@login')->name('login');
Route::post('/login', 'MainController@loginPost')->middleware('throttle:5,1');
Route::middleware(['auth', 'level:1'])->group(function () {
    Route::get('/pengguna', 'PenggunaController@index')->name('pengguna-index');
    Route::get('/pengguna/create', 'PenggunaController@create')->name('pengguna-create');
    Route::post('/pengguna', 'PenggunaController@store')->name('pengguna-store');
    Route::get('/pengguna/{pengguna}/edit', 'PenggunaController@edit')->name('pengguna-edit');
    Route::put('/pengguna/{pengguna}', 'PenggunaController@update')->name('pengguna-update');
    Route::delete('/pengguna/{pengguna}', 'PenggunaController@destroy')->name('pengguna-destroy');
    Route::get('/menu-permissions', 'MainController@menuPermissionsIndex')->name('menu-permissions');
    Route::post('/menu-permissions', 'MainController@menuPermissionsSave')->name('menu-permissions-save');
    Route::get('/app-settings', 'MainController@appSettingsIndex')->name('app-settings');
    Route::post('/app-settings', 'MainController@appSettingsSave')->name('app-settings-save');
    Route::get('/menu-items', 'MenuItemController@index')->name('menu-items-index');
    Route::post('/menu-items', 'MenuItemController@store')->name('menu-items-store');
    Route::get('/menu-items/{menuItem}', 'MenuItemController@show')->name('menu-items-show');
    Route::put('/menu-items/{menuItem}', 'MenuItemController@update')->name('menu-items-update');
    Route::delete('/menu-items/{menuItem}', 'MenuItemController@destroy')->name('menu-items-destroy');
    Route::post('/menu-items/move', 'MenuItemController@move')->name('menu-items-move');
});
Route::get('/audit-logs', 'MainController@auditLogs')->name('audit-logs')->middleware(['auth','menu.level']);
Route::get('/login-logs', 'MainController@loginLogs')->name('login-logs')->middleware(['auth','menu.level']);
Route::get('/logout', 'MainController@logout');
Route::post('/logout', 'MainController@logout')->name('logout');
Route::get('/health', 'MainController@health')->name('health')->middleware(['auth','level:1']);
Route::post('/menu-activity', 'MainController@menuActivity')->name('menu-activity')->middleware('auth');
Route::get('/menu-activity', function(){ return response()->view('errors.403', [], 403); })->name('menu-activity-get');

Route::get('/maintenance/cache-clear', function(\Illuminate\Http\Request $request){
    $token = env('CACHE_CLEAR_TOKEN');
    if (!$token || $request->query('token') !== $token) { return response('Forbidden', 403); }
    try { \Illuminate\Support\Facades\Artisan::call('config:clear'); } catch (\Throwable $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('cache:clear'); } catch (\Throwable $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('route:clear'); } catch (\Throwable $e) {}
    try { \Illuminate\Support\Facades\Artisan::call('view:clear'); } catch (\Throwable $e) {}
    foreach (['config.php','packages.php','services.php','routes.php','events.php'] as $f) {
        $p = base_path('bootstrap/cache/'.$f);
        if (file_exists($p)) { @unlink($p); }
    }
    return response('OK', 200);
})->name('maintenance-cache-clear');