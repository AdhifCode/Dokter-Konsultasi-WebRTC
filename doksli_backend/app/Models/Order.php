<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';
    protected $table ='orders';
    protected $fillable = [
        'order_id',
        'user_id',
        'doctor_id',
        'room_id',
        'time_start',
        'time_end',
        'notes'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function room(){
        return $this->belongsTo(Room::class, 'room_id');
    }
}
