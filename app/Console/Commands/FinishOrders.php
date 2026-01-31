<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class FinishOrders extends Command
{
    protected $signature = 'orders:finish';
    protected $description = 'Automatski završava dostavljene narudžbine';

    public function handle()
    {
        $updated = Order::where('status', 'dostavlja_se')
            ->whereNotNull('ready_at')
            ->where('ready_at', '<=', now())
            ->update([
                'status' => 'zavrsena',
                'updated_at' => now(),
            ]);

        $this->info("Završeno narudžbina: {$updated}");
    }
}
