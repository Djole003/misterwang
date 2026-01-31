<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Middleware\EnsureRestaurantSelected;
use App\Http\Middleware\RestrictAdminToRestaurant;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\RestaurantSelectController;

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\RestaurantStatusController;

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| ROOT – IZBOR LOKALA
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('index');
    }

    return app(RestaurantSelectController::class)->index();

})->name('select.restaurant');


Route::post('/izaberi-lokal', function () {

    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('index');
    }

    return app(RestaurantSelectController::class)->select(request());

})->name('select.restaurant.store');


/*
|--------------------------------------------------------------------------
| CELOKUPAN SAJT – MORA BITI IZABRAN LOKAL
|--------------------------------------------------------------------------
*/
Route::middleware([EnsureRestaurantSelected::class])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | JAVNI DEO
    |--------------------------------------------------------------------------
    */
    Route::get('/pocetna', [ProductController::class, 'index'])->name('index');

    Route::get('/jelovnik', [ProductController::class, 'jelovnikPoKategorijama'])->name('jelovnik');

    Route::get('/jelovnik/kategorija/{slug}', [ProductController::class, 'showCategory'])
        ->name('jelovnik.kategorija');

    Route::get('/jela/{id}', [ProductController::class, 'showWithSuggestions'])
        ->name('dish.showWithSuggestions');

    Route::get('/kontakt', [ContactController::class, 'show'])->name('contact.show');

    Route::post('/kontakt/recenzija', [ContactController::class, 'submitReview'])
        ->name('contact.review.submit');


    /*
    |--------------------------------------------------------------------------
    | KORPA / PORUDŽBINE
    |--------------------------------------------------------------------------
    */
    Route::get('/korpa', [OrderController::class, 'showCart'])->name('order.cart');

    Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');

    Route::delete('/korpa/ukloni/{index}', [OrderController::class, 'removeFromOrder'])
        ->name('order.remove');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');

    Route::post('/poruci/zavrsi', [OrderController::class, 'submitOrder'])
        ->name('order.submit');

    Route::get('/thankyou', [OrderController::class, 'thankyou'])->name('order.thankyou');


    /*
    |--------------------------------------------------------------------------
    | TIP PORUDŽBINE
    |--------------------------------------------------------------------------
    */
    Route::get('/select-order-type/{type}', function ($type) {

        if (!in_array($type, ['delivery', 'takeaway'])) {
            $type = 'delivery';
        }

        session(['order_type' => $type]);

        return response()->json(['status' => 'ok']);
    });


    Route::post('/check-delivery-zone', [OrderController::class, 'checkDeliveryZone'])
        ->name('delivery.zone.check');

    Route::post('/delivery/check', [DeliveryController::class, 'check'])
        ->name('delivery.check');


    /*
    |--------------------------------------------------------------------------
    | EDITOR – PROMENA LOKALA
    |--------------------------------------------------------------------------
    */
    Route::post('/switch-restaurant', function (\Illuminate\Http\Request $request) {

        if (auth()->user()->role !== 'editor') {
            abort(403);
        }

        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id'
        ]);

        session(['restaurant_id' => $request->restaurant_id]);

        return back();

    })->name('admin.switchRestaurant');


    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL – EDITOR
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:editor'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/users', [AdminUserController::class, 'index'])
                ->name('users.index');

            Route::post('/users/{id}/role', [AdminUserController::class, 'updateRole'])
                ->name('users.updateRole');

            Route::post('/users/{id}/toggle-active', [AdminUserController::class, 'toggleActive'])
                ->name('users.toggleActive');
        });


    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL – ADMIN + EDITOR
    |--------------------------------------------------------------------------
    */
    Route::middleware([
        'auth',
        'role:admin,editor',
        RestrictAdminToRestaurant::class
    ])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ✅ DASHBOARD — VEZAN ZA SESSION RESTAURANT
        Route::get('/dashboard', function () {

            $restaurantId = session('restaurant_id');

            $restaurantOpen = DB::table('restaurant_status')
                ->where('restaurant_id', $restaurantId)
                ->value('is_open');

            return view('admin.dashboard', compact('restaurantOpen'));

        })->name('dashboard');


        // ✅ OTVORI / ZATVORI RESTORAN
        Route::post('/restaurant/toggle', [RestaurantStatusController::class, 'toggle'])
            ->name('restaurant.toggle');


        // PRODUCTS
        Route::resource('products', AdminProductController::class);

        Route::post(
            'products/{product}/toggle-availability',
            [AdminProductController::class, 'toggleAvailability']
        )->name('products.toggleAvailability');


        // ORDERS
        Route::resource('orders', AdminOrderController::class)
            ->except(['show', 'create', 'store']);

        Route::post('orders/{order}/accept', [AdminOrderController::class, 'accept'])
            ->name('orders.accept');

        Route::post('orders/{order}/ready', [AdminOrderController::class, 'ready'])
            ->name('orders.ready');

        Route::get('/orders/history', [AdminOrderController::class, 'history'])
            ->name('orders.history');
    });


    /*
    |--------------------------------------------------------------------------
    | KORISNIK
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->group(function () {

        Route::get('/profile/orders', [\App\Http\Controllers\UserOrderController::class, 'index'])
            ->name('user.orders.index');

        Route::post('/profile/orders/{order}/repeat', [\App\Http\Controllers\UserOrderController::class, 'repeat'])
            ->name('orders.repeat');
    });

});
