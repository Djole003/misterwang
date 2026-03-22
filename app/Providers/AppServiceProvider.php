<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Restaurant;
use App\Models\AddOn;
use App\Models\RestaurantContact;
use App\Models\DeliveryZone;

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

            // 🔹 KONTAKT PODACI
            $restaurantContact = $restaurantId
                ? RestaurantContact::where('restaurant_id', $restaurantId)->first()
                : null;

            // 🔹 ZONE
            $deliveryZones = $restaurantId
                ? DeliveryZone::where('restaurant_id', $restaurantId)->get()
                : collect();

            // 🔥 SAFE ADDONS (NEĆE VIŠE PUCI)
            try {
                $addons = AddOn::all();
            } catch (\Exception $e) {
                $addons = collect();
            }

            $view->with([
                'currentRestaurant' => $currentRestaurant,
                'restaurantContact' => $restaurantContact,
                'deliveryZones'     => $deliveryZones,
                'addons'            => $addons
            ]);
        });
    }
}