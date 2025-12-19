
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductionController;

Route::get('/', fn()=>redirect('/dashboard'));
Route::get('/dashboard', fn()=>view('dashboard'));
Route::get('/produksi/input', fn()=>view('production.input'));
Route::get('/downtime/input', fn()=>view('downtime.input'));
Route::get('/produksi/input', [ProductionController::class, 'create']);
Route::post('/produksi/store', [ProductionController::class, 'store']);
