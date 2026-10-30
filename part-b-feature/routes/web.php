<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| 파일 반출 승인 요청 화면(jQuery 기반)을 여기에 라우팅하세요.
|
*/

Route::get('/', function () {
    return redirect('/export-requests');
});

// 지원자가 구현:
// Route::get('/export-requests', [ExportRequestViewController::class, 'index']);
