<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LinkedinSessions extends Model
{
    use HasFactory;
    protected $fillable = ['linkedin_access_token', 'id_usuario', 'linkedin_user_id'];
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function getLinkedAccess($userId)
    {
        $access = DB::table('linkedin_sessions')
            ->select('linkedin_access_token', 'linkedin_user_id')
            ->where('id_usuario', $userId)->first();
        return $access;
    }
}
