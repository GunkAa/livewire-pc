<?php


use App\Livewire\Home;
use App\Livewire\PcManager;
use App\Livewire\UserManager;
use App\Livewire\AssignmentManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('components.layouts.app');
});

Route::get('/home', Home::class)->name('home');
Route::get('/users', UserManager::class)->name('users');
Route::get('/pcs', PcManager::class)->name('pcs');
Route::get('/assignments', AssignmentManager::class )->name('assignments');
Route::get('/pc-assignments-edit-day', [AssignmentManager::class, 'editDay'])->name('pc-assignments-edit-day');


