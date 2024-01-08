<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Specialist;

class MainPageController extends Controller
{
    public function roleCount()
    {
        $roleCounts = User::select('role_id')
            ->selectRaw('count(*) as count')
            ->groupBy('role_id')
            ->get();

        return response()->json(['role_counts' => $roleCounts]);
    }

    public function orderCount()
    {
        $orderCount = Order::count();

        return response()->json(['order_count' => $orderCount]);
    }

    public function getAllSpecialists()
    {
        $specialists = Specialist::all();

        return response()->json(['specialists' => $specialists]);
    }

}
