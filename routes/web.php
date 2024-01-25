<?php

use App\Models\Item;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\VisualSearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/qrcode/{id}.svg', function ($id) {
    $item = Item::findOrFail($id);
    $qrCode = QrCode::size(128)->generate($item->web_url . '?utm_source=ornament-explorer');
    return response($qrCode)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'max-age=15552000');
})->where('id', '[A-Za-z0-9.\-]+');

Route::get('/preview/{id}', function ($id) {
    $imagePath = storage_path("app/images/{$id}.jpg");
    if (file_exists($imagePath)) {
        return response()->file($imagePath);
    }

    abort(404);
})->name('image.show');

Route::get('/visual-search', [VisualSearchController::class, 'index']);
Route::post('/visual-search', [VisualSearchController::class, 'search']);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
