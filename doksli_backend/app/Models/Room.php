<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $primaryKey = 'room_id';
    protected $table ='rooms';
    protected $fillable = [
        'room_id',
        'room_title',
        'room_image',
        'doctor_id',
        'room_capacity',
        'total_favorites'
    ];
    public function doctor(){
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function updateTotalFavorites()
    {
        $totalFavorites = Favorite::where('room_id', $this->room_id)->count();
        $this->total_favorites = $totalFavorites;
        $this->save();
    }

    public function updateRoomCapacity()
    {
        $capacity = RoomMeet::where('room_id', $this->room_id)->count();
        $this->room_capacity = $capacity;
        $this->save();
    }
    
}
