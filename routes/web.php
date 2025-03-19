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
use App\Http\Controllers\ShoppingListController; // Nuevo controlador para la lista de compras
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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

    // ✅ Rutas CRUD para la sección de Servizi
    Route::resource('servizi', ServizioController::class);

    // ✅ Rutas CRUD para la gestión de usuarios
    Route::resource('users', UserController::class);

    // ✅ Rutas CRUD para la gestión de Compiti (Deberes del hogar)
    Route::resource('compiti', CompitoController::class)->parameters([
        'compiti' => 'compito'
    ]);

    Route::patch('/compiti/{compito}/toggle', [CompitoController::class, 'toggleStatus'])->name('compiti.toggle');

    // ✅ Rutas CRUD para la gestión de Animali
    Route::resource('animali', AnimaleController::class)->parameters([
        'animali' => 'animale'
    ]);

    // ✅ Rutas CRUD para la gestión del Inventario de Animali
    Route::prefix('animali/{animale}/inventarioanimali')->group(function () {
        Route::get('/', [InventarioAnimaleController::class, 'index'])->name('inventarioanimali.index');
        Route::get('/create', [InventarioAnimaleController::class, 'create'])->name('inventarioanimali.create');
        Route::post('/', [InventarioAnimaleController::class, 'store'])->name('inventarioanimali.store');
        Route::get('/{inventario}/edit', [InventarioAnimaleController::class, 'edit'])->name('inventarioanimali.edit');
        Route::put('/{inventario}', [InventarioAnimaleController::class, 'update'])->name('inventarioanimali.update');
        Route::delete('/{inventario}', [InventarioAnimaleController::class, 'destroy'])->name('inventarioanimali.destroy');
    });

    // ✅ Rutas CRUD para la gestión de Gastos de los Animali (Spese Animali)
    Route::prefix('animali/{animale}/speseanimali')->group(function () {
        Route::get('/', [SpesaAnimaleController::class, 'index'])->name('speseanimali.index');
        Route::get('/create', [SpesaAnimaleController::class, 'create'])->name('speseanimali.create');
        Route::post('/', [SpesaAnimaleController::class, 'store'])->name('speseanimali.store');
        Route::get('/{spesa}/edit', [SpesaAnimaleController::class, 'edit'])->name('speseanimali.edit');
        Route::put('/{spesa}', [SpesaAnimaleController::class, 'update'])->name('speseanimali.update');
        Route::delete('/{spesa}', [SpesaAnimaleController::class, 'destroy'])->name('speseanimali.destroy');
    });

    // ✅ Rutas CRUD para la gestión de Visite Veterinarie
    Route::prefix('animali/{animale}/visite')->group(function () {
        Route::get('/', [VisitaVeterinariaController::class, 'index'])->name('visite.index');
        Route::get('/create', [VisitaVeterinariaController::class, 'create'])->name('visite.create');
        Route::post('/', [VisitaVeterinariaController::class, 'store'])->name('visite.store');
        Route::get('/{visita}', [VisitaVeterinariaController::class, 'show'])->name('visite.show');
        Route::get('/{visita}/edit', [VisitaVeterinariaController::class, 'edit'])->name('visite.edit');
        Route::put('/{visita}', [VisitaVeterinariaController::class, 'update'])->name('visite.update');
        Route::delete('/{visita}', [VisitaVeterinariaController::class, 'destroy'])->name('visite.destroy');
    });

    // ✅ Rutas para la gestión de Prodotti - Optimizado
    Route::resource('prodotti', ProdottiController::class)->parameters([
        'prodotti' => 'prodotto'  // Corregir el nombre del parámetro para que coincida con la convención de Laravel
    ])->except(['show']);

    // ✅ Rutas adicionales para el módulo de productos
    Route::post('/prodotti/scan', [ProdottiController::class, 'scan'])->name('prodotti.scan');
    Route::post('/prodotti/update-quantity', [ProdottiController::class, 'updateQuantity'])->name('prodotti.update-quantity');
    
    // ✅ Ruta para agregar productos con bajo stock a la lista de compras
    Route::post('/prodotti/add-to-shopping-list', [ProdottiController::class, 'addToShoppingList'])->name('prodotti.add-to-shopping');
    
    // ✅ Nueva ruta para la integración con Bring!
    Route::post('/bring/redirect', [BringRedirectController::class, 'redirect'])->name('bring.redirect');
    
    // ✅ Rutas para la gestión de la lista de compras
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






