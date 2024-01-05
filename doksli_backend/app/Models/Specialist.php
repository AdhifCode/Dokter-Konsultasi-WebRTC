<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasFactory;
    protected $primaryKey = 'specialist_id';
    protected $table ='specialists';
    protected $fillable = [
        'specialist_id',
        'name'
    ];

    public function doctor(){
        return $this->belongsTo(Doctor::class, 'specialist_id');
    }
}
