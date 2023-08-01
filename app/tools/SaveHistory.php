<?php

namespace App\Tools;
use App\Models\Historial;
use Illuminate\Support\Facades\Auth; 



class SaveHistory
{
    public function save($tipo, $menss)
    {
        $user_id = Auth::id();
        $history = new Historial();
        $history->user_id = $user_id;
        $history->tipo = $tipo;
        $history->mensaje = $menss;
        $history->save();
    }
}
