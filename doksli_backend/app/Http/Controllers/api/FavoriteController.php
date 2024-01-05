<?php

namespace App\Http\Controllers\api;

use App\Models\Favorite;
use App\Models\Room;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request, $roomId)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json(['error' => 'User ID not provided'], 400);
        }

        $favorite = Favorite::where('user_id', $userId)->where('room_id', $roomId)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Room removed from favorites';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'room_id' => $roomId,
            ]);
            $message = 'Room added to favorites';
        }

        $room = Room::find($roomId);
        $room->updateTotalFavorites();

        $isFavorite = Favorite::where('user_id', $userId)->where('room_id', $roomId)->exists();

        return response()->json(['message' => $message, 'is_favorite' => $isFavorite]);
    }

    public function getFavoriteStatus(Request $request, $roomId)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json(['error' => 'User ID not provided'], 400);
        }

        $isFavorite = Favorite::where('user_id', $userId)->where('room_id', $roomId)->exists();

        // \Log::info("User ID: $userId, Room ID: $roomId, Is Favorite: " . ($isFavorite ? 'true' : 'false'));

        return response()->json(['is_favorite' => $isFavorite]);
    }

    public function getUserFavorites(Request $request)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json(['error' => 'User ID not provided'], 400);
        }

        $userFavorites = Favorite::where('user_id', $userId)->with('room.doctor.user', 'room.doctor.specialist')->get();

        $transformedFavorites = $userFavorites->map(function ($favorite) {
            $room = $favorite->room;
            $doctor = $room->doctor;

            return [
                'room_id' => $room->room_id,
                'name' => $doctor->user->name,
                'title' => $room->room_title,
                'specialist' => $doctor->specialist->name,
                'total_favorites' => $room->total_favorites,
            ];
        });

        return response()->json(['favorites' => $transformedFavorites]);
    }
}
