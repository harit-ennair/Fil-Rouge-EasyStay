<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    /**
     * Geocode an address to get latitude and longitude
     *
     * @param string $address
     * @return array|null
     */
    public function geocode(string $address): ?array
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY', '');
        
        if (empty($apiKey)) {
            // For development, you can return a default location if no API key is set
            return [
                'lat' => 0,
                'lng' => 0,
                'error' => 'No Google Maps API key configured'
            ];
        }
        
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $apiKey,
            ]);
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['results'][0]['geometry']['location'])) {
                return $data['results'][0]['geometry']['location'];
            }
            
            return [
                'lat' => 0,
                'lng' => 0,
                'error' => $data['error_message'] ?? 'Failed to geocode address'
            ];
            
        } catch (\Exception $e) {
            return [
                'lat' => 0,
                'lng' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
}