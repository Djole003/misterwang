<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeliveryZone;

class ImportMunicipalities extends Command
{
    protected $signature = 'zones:import';
    protected $description = 'Import delivery zones from GADM GeoJSON';

    public function handle()
    {
        $path = storage_path('app/gadm41_SRB_2.json');

        if (!file_exists($path)) {
            $this->error("GeoJSON file not found in storage/app/");
            return;
        }

        $json = json_decode(file_get_contents($path), true);

        $this->info("File loaded successfully.");


        $municipalities = [

            // SLAVIJA
            3 => [
                'Zelena' => 'Vračar',
                'Zuta' => ['StariGrad', 'Palilula'],
                'Narandzasta' => 'SavskiVenac',
                'Crvena' => 'Čukarica',
            ],

            // VRACAR
            2 => [
                'Zelena' => 'Vračar',
                'Zuta' => ['Voždovac', 'Zvezdara'],
                'Narandzasta' => 'SavskiVenac',
                'Crvena' => 'Palilula',
            ],

            // MILJAKOVAC
            1 => [
                'Zelena' => 'Rakovica',
                'Zuta' => 'Čukarica',
                'Narandzasta' => 'Voždovac',
                'Crvena' => 'SavskiVenac',
            ],

            // NOVI BEOGRAD
            4 => [
                'Zelena' => 'NoviBeograd',
                'Zuta' => 'Zemun',
                'Narandzasta' => 'Surčin',
                'Crvena' => 'Čukarica',
            ],
        ];

        DeliveryZone::truncate();

        foreach ($municipalities as $restaurantId => $zones) {

            foreach ($zones as $zoneName => $opstine) {

                $opstine = is_array($opstine) ? $opstine : [$opstine];

                foreach ($opstine as $opstina) {

                    $geometry = $this->extractMunicipality($json, $opstina);

                    if (!$geometry) {
                        $this->warn("Municipality not found: $opstina");
                        continue;
                    }

                    $polygon = $this->convertToPolygon($geometry);

                    DeliveryZone::create([
                        'restaurant_id' => $restaurantId,
                        'name' => $zoneName,
                        'polygon' => $polygon,
                        'price' => 0,
                        'minimum_amount' => 0,
                    ]);
                }
            }
        }

        $this->info("Zones imported successfully.");
    }

    private function extractMunicipality($json, $name)
    {
        foreach ($json['features'] as $feature) {
            if ($feature['properties']['NAME_2'] === $name) {
                return $feature['geometry'];
            }
        }
        return null;
    }

    private function convertToPolygon($geometry)
    {
        $coordinates = $geometry['coordinates'][0][0];

        $polygon = [];

        foreach ($coordinates as $coord) {
            $polygon[] = [
                'lat' => $coord[1],
                'lng' => $coord[0],
            ];
        }

        return json_encode($polygon);
    }
}