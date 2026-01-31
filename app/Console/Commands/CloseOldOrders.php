<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CloseOldOrders extends Command
{
    /**
     * Naziv komande
     */
    protected $signature = 'orders:close-old';

    /**
     * Opis
     */
    protected $description = 'Automatski zatvara stare porudžbine';

    public function handle()
    {
        $limitTime = Carbon::now()->subMinutes(60);

        $orders = Order::where('status', 'Primljena')
            ->where('created_at', '<=', $limitTime)
            ->get();

        foreach ($orders as $order) {
            $order->status = 'Otkazana';
            $order->save();
        }

        $this->info('Zatvoreno porudžbina: ' . $orders->count());

        return 0;
    }
}
