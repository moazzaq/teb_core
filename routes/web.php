<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AreasController;
use App\Http\Controllers\Admin\CasesController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TreatmentsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
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


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//require __DIR__ . '/auth.php';

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {



    Route::middleware(['auth:admin'])->prefix('admin')->as('admin.')->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('/users', UserController::class);
        Route::get('/api-users', [UserController::class, 'users_api'])->name('users.api');
        Route::get('/profile', [UserController::class, 'edit_profile'])->name('users.profile.edit');
        Route::post('/profile', [UserController::class, 'update_profile'])->name('users.profile.update');
        Route::get('/profile-security', [UserController::class, 'edit_security'])->name('users.profile.edit-password');
        Route::post('/profile-security', [UserController::class, 'update_security'])->name('users.profile.update-password');

        Route::resource('/categories', CategoryController::class);
        Route::get('/api-categories', [CategoryController::class, 'categories_api'])->name('categories.api');

        Route::resource('/sub-categories', SubCategoryController::class);
        Route::get('/api-sub-categories', [SubCategoryController::class, 'sub_categories_api'])->name('sub_categories.api');

        Route::resource('/countries', CountriesController::class);
        Route::get('/api-countries', [CountriesController::class, 'countries_api'])->name('countries.api');

        Route::resource('/cities', CitiesController::class);
        Route::get('/api-cities', [CitiesController::class, 'cities_api'])->name('cities.api');

        Route::resource('/areas', AreasController::class);
        Route::get('/api-areas', [AreasController::class, 'areas_api'])->name('areas.api');

        Route::resource('/treatments', TreatmentsController::class);
        Route::get('/api-treatments', [TreatmentsController::class, 'treatments_api'])->name('treatments.api');

        Route::resource('/cases', CasesController::class);
        Route::get('/api-cases', [CasesController::class, 'cases_api'])->name('cases.api');

        Route::get('/get-sub-category-by-category/{category}', [AdminController::class, 'get_sub_categories'])->name('get_sub_categories');
    });

});
