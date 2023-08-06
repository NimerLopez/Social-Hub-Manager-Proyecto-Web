<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postqueue extends Model
{
    use HasFactory;
    protected $table = 'postqueue';
    protected $fillable = ['redsocial', 'id_usuario', 'title', 'message', 'group'];

    public function scopeByRedSocial($query, $redSocial)
    {
        return $query->where('redsocial', $redSocial);
    }
}
