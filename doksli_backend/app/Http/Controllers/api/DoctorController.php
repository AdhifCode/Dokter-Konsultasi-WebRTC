<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DoctorController extends Controller
{
    public function registerDoctor(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialist_id' => 'required|exists:specialists,specialist_id',
            'SIP' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'experience' => 'required',
            'room_title' => 'required',
            'room_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find($request->input('user_id'));

        if ($user->role_id != 3) {
            $user->role_id = 3;
            $user->save();
        }
        $sipImage = $request->file('SIP')->store('sip_images', 'public');

        $doctor = Doctor::create([
            'user_id' => $request->input('user_id'),
            'specialist_id' => $request->input('specialist_id'),
            'SIP' => $sipImage,
            'experience' => $request->input('experience'),
        ]);

            if (!$doctor->doctor_id) {
                return response()->json([
                    'message' => 'Gagal membuat entitas Doctor',
                ], 500);
            }

        $roomImage = $request->file('room_image')->store('room_images', 'public');
        $SecretKey = Str::random(10);
        // $roomImageUrl = Storage::url($roomImage);

        $room = Room::create([
            'room_title' => $request->input('room_title'),
            // 'room_image' => $roomImageUrl, 
            'room_image' => $roomImage, 
            'doctor_id' => $doctor->doctor_id,
            'secret_key' => $SecretKey,
        ]);

        $doctorId = $doctor->doctor_id;

        DB::table('users')
            ->where('id', $user->id)
            ->update(['doctor_id' => $doctorId]);

        return response()->json([
            'message' => 'Registrasi dokter berhasil',
            'user' => $user,
            'doctor' => $doctor,
            'room' => $room,
        ], 201);
    }
}
