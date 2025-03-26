<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\ServizioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompitoController;
use App\Http\Controllers\AnimaleController;
use App\Http\Controllers\InventarioAnimaleController;
use App\Http\Controllers\SpesaAnimaleController;
use App\Http\Controllers\VisitaVeterinariaController;
use App\Http\Controllers\ProdottiController;
use App\Http\Controllers\BringRedirectController;
use App\Http\Controllers\ShoppingListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);

    Route::get('dashboard', [HomeController::class, 'home'])->name('dashboard');

    Route::get('billing', function () {
        return view('billing');
    })->name('billing');

    Route::get('profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('user-management', function () {
        return view('laravel-examples/user-management');
    })->name('user-management');

    Route::get('tables', function () {
        return view('tables');
    })->name('tables');

    Route::get('/logout', [SessionsController::class, 'destroy'])->name('logout');

    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);

    Route::resource('servizi', ServizioController::class);

    Route::resource('users', UserController::class);

    Route::resource('compiti', CompitoController::class)->parameters([
        'compiti' => 'compito'
    ]);

    Route::patch('/compiti/{compito}/toggle', [CompitoController::class, 'toggleStatus'])->name('compiti.toggle');

    Route::resource('animali', AnimaleController::class)->parameters([
        'animali' => 'animale'
    ]);

    Route::prefix('animali/{animale}/inventarioanimali')->group(function () {
        Route::get('/', [InventarioAnimaleController::class, 'index'])->name('inventarioanimali.index');
        Route::get('/create', [InventarioAnimaleController::class, 'create'])->name('inventarioanimali.create');
        Route::post('/', [InventarioAnimaleController::class, 'store'])->name('inventarioanimali.store');
        Route::get('/{inventario}/edit', [InventarioAnimaleController::class, 'edit'])->name('inventarioanimali.edit');
        Route::put('/{inventario}', [InventarioAnimaleController::class, 'update'])->name('inventarioanimali.update');
        Route::delete('/{inventario}', [InventarioAnimaleController::class, 'destroy'])->name('inventarioanimali.destroy');
    });

    Route::prefix('animali/{animale}/speseanimali')->group(function () {
        Route::get('/', [SpesaAnimaleController::class, 'index'])->name('speseanimali.index');
        Route::get('/create', [SpesaAnimaleController::class, 'create'])->name('speseanimali.create');
        Route::post('/', [SpesaAnimaleController::class, 'store'])->name('speseanimali.store');
        Route::get('/{spesa}/edit', [SpesaAnimaleController::class, 'edit'])->name('speseanimali.edit');
        Route::put('/{spesa}', [SpesaAnimaleController::class, 'update'])->name('speseanimali.update');
        Route::delete('/{spesa}', [SpesaAnimaleController::class, 'destroy'])->name('speseanimali.destroy');
    });

    Route::prefix('animali/{animale}/visite')->group(function () {
        Route::get('/', [VisitaVeterinariaController::class, 'index'])->name('visite.index');
        Route::get('/create', [VisitaVeterinariaController::class, 'create'])->name('visite.create');
        Route::post('/', [VisitaVeterinariaController::class, 'store'])->name('visite.store');
        Route::get('/{visita}', [VisitaVeterinariaController::class, 'show'])->name('visite.show');
        Route::get('/{visita}/edit', [VisitaVeterinariaController::class, 'edit'])->name('visite.edit');
        Route::put('/{visita}', [VisitaVeterinariaController::class, 'update'])->name('visite.update');
        Route::delete('/{visita}', [VisitaVeterinariaController::class, 'destroy'])->name('visite.destroy');
    });

    Route::resource('prodotti', ProdottiController::class)->parameters([
        'prodotti' => 'prodotto'
    ])->except(['show']);

    Route::post('/prodotti/scan', [ProdottiController::class, 'scan'])->name('prodotti.scan');
    Route::post('/prodotti/update-quantity', [ProdottiController::class, 'updateQuantity'])->name('prodotti.update-quantity');
     
    Route::post('/prodotti/add-to-shopping-list', [ProdottiController::class, 'addToShoppingList'])->name('prodotti.add-to-shopping');
     
    Route::post('/bring/redirect', [BringRedirectController::class, 'redirect'])->name('bring.redirect');
     
    Route::get('/shopping-list', [ShoppingListController::class, 'index'])->name('shopping.list');
    Route::post('/shopping-list', [ShoppingListController::class, 'store'])->name('shopping.store');
    Route::post('/shopping-list/add-manual', [ShoppingListController::class, 'addManualItem'])->name('shopping.add-manual');
    Route::put('/shopping-list/{id}/purchase', [ShoppingListController::class, 'markAsPurchased'])->name('shopping.purchase');
    Route::put('/shopping-list/{id}/quantity', [ShoppingListController::class, 'updateQuantity'])->name('shopping.update-quantity');
    Route::delete('/shopping-list/{id}', [ShoppingListController::class, 'destroy'])->name('shopping.destroy');
    Route::delete('/shopping-list/clear-purchased', [ShoppingListController::class, 'clearPurchased'])->name('shopping.clear-purchased');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');






