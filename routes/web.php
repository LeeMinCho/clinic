<?php

use App\Http\Livewire\ClinicParamedicComponent;
use App\Http\Controllers\DashboardController;
use App\Http\Livewire\RegistrationComponent;
use App\Http\Livewire\MenuScreenComponent;
use App\Http\Livewire\ParamedicComponent;
use App\Http\Controllers\AuthController;
use App\Http\Livewire\MenuUserComponent;
use App\Http\Livewire\PatientComponent;
use App\Http\Livewire\ClinicComponent;
use App\Http\Livewire\ScreenComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\MenuComponent;
use App\Http\Livewire\UserComponent;

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

Route::get('/', [AuthController::class, 'index'])->name('home');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user', UserComponent::class)->name('user');
    Route::get('/paramedic', ParamedicComponent::class)->name('paramedic');
    Route::get('/patient', PatientComponent::class)->name('patient');
    Route::get('/menu', MenuComponent::class)->name('menu');
    Route::get('/screen', ScreenComponent::class)->name('screen');
    Route::get('/registration', RegistrationComponent::class)->name('registration');
    Route::get('/clinic', ClinicComponent::class)->name('clinic');
});

Route::post('/screen/getScreen', [MenuScreenComponent::class, 'getScreen'])->name('screen.getScreen');
Route::post('/user/getUser', [MenuUserComponent::class, 'getUser'])->name('user.getUser');
Route::post('/patient/getPatient', [RegistrationComponent::class, 'getPatient'])->name('patient.getPatient');
Route::post('/paramedic/getParamedic', [RegistrationComponent::class, 'getParamedic'])->name('paramedic.getParamedic');
Route::post('/clinic-paramedic/getParamedic', [ClinicParamedicComponent::class, 'getParamedic'])->name('clinic-paramedic.getParamedic');
