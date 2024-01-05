<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMeet extends Model
{
    use HasFactory;

    protected $primaryKey = 'roommeet_id';
    protected $table ='meetingrooms';
    protected $fillable = [
        'roommeet_id',
        'user_id',
        'doctor_id',
        'room_id',

        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(DOctor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
