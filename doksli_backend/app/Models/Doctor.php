<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $primaryKey = 'doctor_id';
    protected $table ='doctors';
    protected $fillable = [
        'doctor_id',
        'user_id',
        'specialist_id',
        'room_id',
        'SIP',//image
        'experience'
    ];


    public function specialist(){
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'doctor_id');
    }
}
