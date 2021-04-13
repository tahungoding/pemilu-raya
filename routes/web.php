<?php

use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\MainController as AdminController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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


Route::prefix('login')->middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:voter')->name('logout');
Route::group(['middleware' => ['admin']], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::resource('elections', ElectionController::class)->except('create', 'edit');
        Route::get('/election/clear', [ElectionController::class, 'clear'])->name('elections.clear');
        Route::get('/elections/{election}/running/{runningStatus?}', [ElectionController::class, 'running'])->name('elections.running');
        Route::get('/elections/{election}/archive', [ElectionController::class, 'archive'])->name('elections.archive');
        Route::get('/elections/{election}/reset-voting', [ElectionController::class, 'resetVoting'])->name('elections.reset_voting');

        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::resource('elections', ElectionController::class)->except('create', 'edit');
        Route::get('/elections/{election}/{archived}', [ElectionController::class, 'archive'])->name('elections.archive');

        Route::resource('users', UserController::class)->except('create', 'edit');
        Route::get('/clearAllUser', [UserController::class, 'clearAll'])->name('users.clearAll');

        Route::resource('candidates', CandidateController::class);
        Route::get('/clearAll', [CandidateController::class, 'clearAll'])->name('candidates.clearAll');

        Route::resource('voters', VoterController::class)->except('create', 'edit', 'show');
        Route::prefix('voters')->group(function () {
            Route::get('/clear', [VoterController::class, 'clear'])->name('voters.clear');
            Route::post('/import', [VoterController::class, 'import'])->name('voters.import');
            Route::get('/download-format', [VoterController::class, 'downloadFormat'])->name('voters.download_format');
        });
    });
});