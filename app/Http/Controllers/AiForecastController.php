<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiForecastController extends Controller
{
    public function index()
    {
        // Κλήση του Flask API
        try {
            $response = Http::post('http://127.0.0.1:5000/forecast', [
                'sales' => [100, 200, 150, 300, 250] // dummy data
            ]);

            if ($response->successful()) {
                $forecast = $response->json();
                return view('ai-forecast', ['forecast' => $forecast]);
            } else {
                return view('ai-forecast', ['error' => 'Flask API returned error']);
            }

        } catch (\Exception $e) {
            return view('ai-forecast', ['error' => $e->getMessage()]);
        }
    }
}
