<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant;

class CloseRestaurant extends Command
{
    protected $signature = 'restaurant:close';
    protected $description = 'Zatvara restoran (cron / admin)';

    public function handle()
    {
        Restaurant::query()->update([
            'is_active' => false
        ]);

        $this->info('⛔ Restoran je ZATVOREN');
        return 0;
    }
}
