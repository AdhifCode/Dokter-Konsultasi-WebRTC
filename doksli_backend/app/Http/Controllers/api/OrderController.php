<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Schedule;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'room_id' => 'required|exists:rooms,room_id',
            'time_start' => 'required|date_format:Y-m-d H:i',
            'time_end' => 'required|date_format:Y-m-d H:i',
            'notes' => 'nullable|string',
        ]);

        $existingOrder = Order::where('doctor_id', $validatedData['doctor_id'])
            ->where('time_start', '<=', $validatedData['time_end'])
            ->where('time_end', '>=', $validatedData['time_start'])
            ->first();

        if ($existingOrder) {
            return response()->json(['message' => 'Time slot is already booked'], 400);
        }

        try {
            // Create order
            $order = Order::create($validatedData);

            // Create schedule
            $scheduleData = [
                'name' => $order->user->name,
                'start_time' => $order->time_start,
                'end_time' => $order->time_end,
                'order_id' => $order->order_id,
                'doctor_id' => $order->doctor_id,
            ];

            Schedule::create($scheduleData);

            return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 500);
        }
    }

public function getBookedTimeSlots($room_id)
{
    try {
        $bookedTimeSlots = Order::where('room_id', $room_id)
            ->select('time_start', 'time_end')
            ->get();

        return response()->json(['message' => 'Booked time slots retrieved successfully', 'booked_time_slots' => $bookedTimeSlots]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to retrieve booked time slots', 'error' => $e->getMessage()], 500);
    }
}

public function getUserOrders($user_id)
{
    try {
        $userOrders = Order::where('user_id', $user_id)
            ->with(['room', 'room.doctor.user',])
            ->get();

        return response()->json(['message' => 'User orders retrieved successfully', 'user_orders' => $userOrders]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to retrieve user orders', 'error' => $e->getMessage()], 500);
    }
}


}
