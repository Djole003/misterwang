<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant;

class OpenRestaurant extends Command
{
    protected $signature = 'restaurant:open';
    protected $description = 'Otvara restoran (cron / admin)';

    public function handle()
    {
        Restaurant::query()->update([
            'is_active' => true
        ]);

        $this->info('✅ Restoran je OTVOREN');
        return 0;
    }
}
