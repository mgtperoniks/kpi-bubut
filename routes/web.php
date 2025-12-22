
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\TrackingOperatorController;
use App\Http\Controllers\TrackingMachineController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\TrackingDowntimeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;

Route::get('/', fn()=>redirect('/dashboard'));
Route::get('/produksi/input', fn()=>view('production.input'));
Route::get('/downtime/input', fn()=>view('downtime.input'));

Route::get('/produksi/input', [ProductionController::class, 'create']);
Route::post('/produksi/store', [ProductionController::class, 'store']);

Route::get('/tracking/operator', [TrackingOperatorController::class, 'index']);
Route::get('/tracking/operator/{operator}/{date}', [TrackingOperatorController::class, 'show']);

Route::get('/tracking/mesin', [TrackingMachineController::class, 'index']);
Route::get('/tracking/mesin/{machine}/{date}', [TrackingMachineController::class, 'show']);

Route::get('/reject', [RejectController::class, 'index']);
Route::get('/reject/input', [RejectController::class, 'create']);
Route::post('/reject/store', [RejectController::class, 'store']);

Route::get('/downtime', [TrackingDowntimeController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/export/operator/{date}', [ExportController::class, 'operatorKpi']);
Route::get('/export/machine/{date}', [ExportController::class, 'machineKpi']);
Route::get('/export/downtime/{date}', [ExportController::class, 'downtime']);
