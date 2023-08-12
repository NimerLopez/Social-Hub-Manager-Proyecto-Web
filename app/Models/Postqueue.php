<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Postqueue extends Model
{
    use HasFactory;
    protected $table = 'postqueue';
    protected $fillable = ['redsocial', 'id_usuario', 'linkedin_user_id', 'message', 'group','title','scheduled_date','scheduled_time'];

    public function scopeByRedSocial($query, $redSocial)
    {
        return $query->where('redsocial', $redSocial);
    }
    public function getOldestPostsByUser($userId)
    {
        $oldestPostsByUser = DB::table($this->table)
            ->where('id_usuario', $userId)
            ->whereNull('scheduled_date') //valida que la fecha sea null
            ->orderBy('created_at', 'asc')//treae el ultimo que se publico
            ->first();     
        return $oldestPostsByUser;
    }
    public function getAllUserIds()
    {
        $userIds = DB::table($this->table)
            ->select('id_usuario')
            ->distinct()
            ->pluck('id_usuario');
        return $userIds;
    }
}
