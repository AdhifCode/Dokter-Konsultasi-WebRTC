<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function viewOrdersByDoctor($doctorId)
    {
        try {
            $orders = Schedule::where('doctor_id', $doctorId)->get();

            $formattedOrders = $orders->map(function ($order) {
                return [
                    'order_id' => $order->order_id,
                    'name' => $order->name,
                    'start_time' => $order->start_time,
                    'end_time' => $order->end_time,
                ];
            });

            return response()->json(['message' => 'Orders retrieved successfully', 'orders' => $formattedOrders]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve orders', 'error' => $e->getMessage()], 500);
        }
    }
}
