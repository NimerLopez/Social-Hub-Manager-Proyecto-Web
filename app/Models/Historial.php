<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;
    protected $table = 'historial'; 
    protected $primaryKey = 'id'; 
    public $timestamps = true; 
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
