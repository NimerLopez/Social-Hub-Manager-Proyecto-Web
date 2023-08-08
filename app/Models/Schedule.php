<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'day', 'hour'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function isDuplicate($user_id, $day, $time)
    {
        return static::where('user_id', $user_id)
            ->where('day', $day)
            ->where('hour', $time)
            ->exists();
    }
    public static function getByUserId($user_id)
    {
        return static::where('user_id', $user_id)->get();
    }
}
