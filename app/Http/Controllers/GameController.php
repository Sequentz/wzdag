<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PlayLog;

class GameController extends Controller
{
    public function playGame(Request $request)
    {
        $ipAddress = $request->ip(); // Haal het IP-adres van de gebruiker op
        $today = Carbon::now()->startOfDay(); // Huidige datum zonder tijd

        // Zoek naar een record met dit IP-adres
        $playLog = PlayLog::where('ip_address', $ipAddress)
            ->where('last_played', '>=', $today)
            ->first();

        if ($playLog) {
            return response()->json([
                'message' => 'Je hebt vandaag al gespeeld. Kom morgen terug!'
            ], 403);
        }

        // Sla de huidige speeltijd op in de database
        PlayLog::updateOrCreate(
            ['ip_address' => $ipAddress],
            ['last_played' => Carbon::now()]
        );

        return response()->json([
            'message' => 'Succes, je kunt spelen!'
        ], 200);
    }
}
