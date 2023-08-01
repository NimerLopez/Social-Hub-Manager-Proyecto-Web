<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $historial = Historial::where('user_id', $userId)->get();
        return view('Historial.index', compact('historial'));
    }
}
