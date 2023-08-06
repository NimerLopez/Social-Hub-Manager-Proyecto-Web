<?php

namespace App\Http\Controllers;

use App\Models\Postqueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class PostsQueueController extends Controller
{
    public function sendToPostQueueReddit(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'subreddit' => 'required|string',
        ]);
        $userId = Auth::id();
        // Crear un nuevo registro en la tabla "postqueue"
        Postqueue::create([
            'redsocial' => 'Reddit', // Suponemos que la red social es Reddit en este caso
            'id_usuario' => $userId, // Suponemos que el ID del usuario es 1 en este caso (ajustar según tu lógica de usuarios)
            'title' => $request->input('title'),
            'message' => $request->input('content'),
            'group' => $request->input('subreddit'),
        ]);
        // Redireccionar o hacer cualquier otra acción después de guardar
        return redirect()->route('publicaciones.index')->with('success', 'Esta publicacion se envio a cola de Reddit.');   
    }
    public function sendToPostQueueLinkedin(Request $request)
    {
        $validated = $request->validate([
            'linkedin_text' => 'required|string|max:255',
        ]);
        $userId = Auth::id();
        // Crear un nuevo registro en la tabla "postqueue"
        Postqueue::create([
            'redsocial' => 'Linkedin', // Suponemos que la red social es Reddit en este caso
            'id_usuario' => $userId, // Suponemos que el ID del usuario es 1 en este caso (ajustar según tu lógica de usuarios)
            'title' => '',
            'message' => $request->input('linkedin_text'),
            'group' => '',
        ]);
        // Redireccionar o hacer cualquier otra acción después de guardar
        return redirect()->route('publicaciones.index')->with('success', 'Esta publicacion se envio a cola de Linkedin.');   
    }
}
