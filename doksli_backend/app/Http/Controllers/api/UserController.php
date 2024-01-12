<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{   

    public function getUserById($id)
{
    try {
        $user = User::with(['role', 'doctor.room'])->findOrFail($id);
    
        return response()->json([
            'data' => $user,
            'message' => 'Berhasil ambil data user dengan ID ' . $id,
            'success' => true,
            'status' => 200,
        ], 200);
    } catch (\Throwable $e) {
        return response()->json([
            'data' => null,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'success' => false,
            'status' => 500,
        ], 500);
    }
    
}

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string',
                'password' => 'required|string|min:6',
                'role_id' => 'nullable|exists:roles,role_id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $input = $request->all();

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('user_images', 'public');
                $input['image'] = $imagePath;
            }
            $user = User::create($input);
    
            return response()->json([
                'data' => $user,
                'message' => 'Berhasil menambahkan data user',
                'success' => true,
                'status' => 201,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'data' => null,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'success' => false,
                'status' => 500,
            ], 500);
        }
    }

    public function update(Request $request, $id)
{
    try {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'password' => 'string|min:6',
            'role_id' => 'nullable|exists:roles,role_id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $input = $request->all();

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $imagePath = $request->file('image')->store('user_images', 'public');
            $input['image'] = $imagePath;
            
        } elseif ($user->image === null) {
            $uploadImage = $request->file('image')->store('user_images', 'public');
            $input['image'] = $uploadImage;
        }

        $user->update($input);

        return response()->json([
            'data' => $user,
            'message' => 'Berhasil update data user',
            'success' => true,
            'status' => 200,
        ], 200);
    } catch (\Throwable $e) {
        return response()->json([
            'data' => null,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'success' => false,
            'status' => 500,
        ], 500);
    }
}


    public function getAllUsers()
    {
        try {
            $users = User::with('role')->get();

            return response()->json([
                'data' => $users,
                'message' => 'Berhasil ambil semua data user',
                'success' => true,
                'status' => 200,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'data' => null,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'success' => false,
                'status' => 500,
            ], 500);
        }
    }
    
    public function getChartData()
    {
        try {
            $users = User::select('created_at')->get();
    
            $chartData = $users->groupBy(function ($user) {
                return $user->created_at->format('Y-m-d');
            })->map(function ($groupedData, $date) {
                $totalCount = $groupedData->count();
                $formattedDate = "Date.UTC(" . $groupedData[0]->created_at->year . ", " . ($groupedData[0]->created_at->month - 1) . ", " . $groupedData[0]->created_at->day . ")";
                return [$formattedDate, $totalCount];
            });
    
            return response()->json([
                'data' => $chartData->values(),
                'message' => 'Berhasil ambil data chart',
                'success' => true,
                'status' => 200,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'data' => null,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'success' => false,
                'status' => 500,
            ], 500);
        }
    }

}
