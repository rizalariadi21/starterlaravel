<?php

use Illuminate\Http\Request;

Route::prefix('v1')->group(function(){
    Route::post('/audit-logs', 'Api\\AuditController@auditLogs')->middleware('api.token')->name('api.v1.audit-logs');
    Route::get('/audit-logs', function(){ return response()->view('errors.403', [], 403); })->name('api.v1.audit-logs.get');
    Route::post('/pengguna', 'Api\\PenggunaController@index')->middleware('api.token')->name('api.v1.pengguna');
    Route::post('/login-logs', 'Api\\AuditController@loginLogs')->middleware('api.token')->name('api.v1.login-logs');
    Route::get('/login-logs', function(){ return response()->view('errors.403', [], 403); })->name('api.v1.login-logs.get');
});
