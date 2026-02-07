<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Restaurant;
use App\Models\AddOn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $restaurantId = session('restaurant_id');

            $currentRestaurant = $restaurantId
                ? Restaurant::find($restaurantId)
                : null;

            $view->with([
                'currentRestaurant' => $currentRestaurant,
                'addons' => AddOn::all()
            ]);
        });
    }
}
