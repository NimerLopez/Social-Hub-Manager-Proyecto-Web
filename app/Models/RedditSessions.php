<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RedditSessions extends Model
{
    use HasFactory;
    protected $fillable = ['reddit_access_token', 'id_usuario'];
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function getRedditAccess($userId)
    {
        $access = DB::table('reddit_sessions')
            ->where('id_usuario', $userId)
            ->value('reddit_access_token');
        return $access;
    }
}
