<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeliveryZoneService;

class DeliveryController extends Controller
{
    public function check(Request $request)
    {
        if (!$request->address) {
            return response()->json([
                'success' => false,
                'message' => 'Adresa nije uneta'
            ]);
        }

        $coords = $this->getCoordinatesFromAddress($request->address);

        if (!$coords) {
            return response()->json([
                'success' => false,
                'message' => 'Nije moguće odrediti lokaciju'
            ]);
        }

        $zoneService = new DeliveryZoneService();
        $zone = $zoneService->getZoneForCoordinates($coords[0], $coords[1]);

        if (!$zone) {
            return response()->json([
                'success' => false,
                'message' => 'Ne vršimo dostavu na ovu adresu'
            ]);
        }

        return response()->json([
            'success' => true,
            'zone'    => $zone['name'],
            'price'   => $zone['price']
        ]);
    }

    private function getCoordinatesFromAddress($address)
    {
        $query = urlencode($address . ', Beograd, Srbija');
        $url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1";

        $opts = [
            "http" => [
                "header" => "User-Agent: MisterWangApp/1.0\r\n"
            ]
        ];

        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);

        if (!$response) return null;

        $data = json_decode($response, true);

        if (empty($data)) return null;

        return [
            (float)$data[0]['lat'],
            (float)$data[0]['lon']
        ];
    }
}
