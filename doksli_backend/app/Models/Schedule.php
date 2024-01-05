<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'order_id',
        'doctor_id',
    ];

    public function name()
    {
        return $this->belongsTo(User::class, 'name');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
