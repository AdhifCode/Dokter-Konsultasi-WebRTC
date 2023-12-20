<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    public function createRoom(Request $request)
    {
        $user = auth()->user(); // Mendapatkan user yang sedang login

        if ($user->hasRole('3')) {
            // Membuat ruangan3doctor baru untuk doctor
            $room = Room::create([
                'doctor_id' => $user->id,
                'patient_id' => null, // Ruangan belum memiliki pasien
            ]);

            return response()->json(['room_id' => $room->id], 201);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function joinRoom(Request $request, $roomId)
    {
        $user = auth()->user(); // Mendapatkan user yang sedang login

        $room = Room::findOrFail($roomId);

        if ($user->hasRole('patient') && $room->patient_id === null) {
            // Memberikan izin masuk ke pasien jika ruangan tidak memiliki pasien
            $room->update(['patient_id' => $user->id]);

            return response()->json(['message' => 'Permission granted'], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
