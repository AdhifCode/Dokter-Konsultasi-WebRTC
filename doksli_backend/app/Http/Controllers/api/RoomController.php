<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{

    public function updateTotalFavorites($roomId)
    {
        $totalFavorites = Favorite::where('room_id', $roomId)->count();

        Room::where('room_id', $roomId)->update(['total_favorites' => $totalFavorites]);
    }

    public function getAllRooms()
    {
        $rooms = Room::with(['doctor.user', 'doctor.specialist'])->get();

        $transformedRooms = $rooms->map(function ($room) {
            return [
                'room_id' => $room->room_id,
                'user_image' => $room->doctor->user->image,
                'room_image' => $room->room_image,
                'name' => $room->doctor->user->name,
                'title' => $room->room_title,
                'specialist' => $room->doctor->specialist->name,
                'experience' => $room->doctor->experience,
                'total_favorites' => $room->total_favorites,
            ];
        });

        return response()->json(['rooms' => $transformedRooms]);
    }

    public function getRoomById($roomId)
    {
        $room = Room::with(['doctor.user', 'doctor.specialist'])
            ->where('room_id', $roomId)
            ->first();

        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $transformedRoom = [
            'room_id' => $room->room_id,
            'doctor_id' => $room->doctor->doctor_id,
            'user_image' => $room->doctor->user->image,
            'room_image' => $room->room_image,
            'name' => $room->doctor->user->name,
            'title' => $room->room_title,
            'specialist' => $room->doctor->specialist->name,
            'total_favorites' => $room->total_favorites,
        ];

        return response()->json(['room' => $transformedRoom]);
    }    

    public function getTop3RoomsByFavorites()
    {
        $top3Rooms = Room::with(['doctor.user', 'doctor.specialist'])
            ->leftJoin('favorites', 'rooms.room_id', '=', 'favorites.room_id')
            ->select(
                'rooms.room_id',
                'rooms.room_image',
                'rooms.room_title',
                'rooms.doctor_id',
                DB::raw('COUNT(favorites.room_id) as favorites_count')
            )
            ->groupBy('rooms.room_id', 'rooms.room_image', 'rooms.room_title', 'rooms.doctor_id')
            ->orderByDesc('favorites_count')
            ->take(3)
            ->get();

        $transformedRooms = $top3Rooms->map(function ($room) {
            return [
                'room_id' => $room->room_id,
                'user_image' => $room->doctor->user->image,
                'room_image' => $room->room_image,
                'name' => $room->doctor->user->name,
                'title' => $room->room_title,
                'specialist' => $room->doctor->specialist->name,
                'total_favorites' => $room->favorites_count,
            ];
        });

        return response()->json(['top_rooms' => $transformedRooms]);
    }

}
