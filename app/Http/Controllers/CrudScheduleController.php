<?php

namespace App\Http\Controllers;

use App\Tools\PostQueueSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Date;

class CrudScheduleController extends Controller
{
    public function index(){
        $userId = Auth::id();
        $schedules = Schedule::where('user_id', $userId)->get();
        return view('Schedule.index', compact('schedules'));
    }

    public function store(Request $request){
        $validated = $request->validate([//valida los campos llenos
            'day' => 'required|string',
            'time' => 'required|date_format:H:i',
        ]);
        $user_id = Auth::id();
        $day = $validated['day'];
        $time = $validated['time'];
       
        if (Schedule::isDuplicate($user_id, $day, $time)) {
            // Si existe un horario duplicado muestra el error
            return back()->withErrors(['error' => 'Ya existe un horario con el mismo día y hora.']);
        }
        // Crear una nueva instancia del mo   delo Schedule con los datos validados y el user_id
        $schedule = new Schedule([
            'user_id' => $user_id,
            'day' => $day,
            'hour' => $time,
        ]);
        $schedule->save();

        if (!$schedule->save()) {
            return back()->withErrors(['error' => 'Error al guardar el horario en la base de datos.']);
        }
        return back()->with(['status' => 'Registro exitoso en la base de datos.']);
    }
     
    public function delete($id){
        $schedule = Schedule::find($id);
        if ($schedule) {
            // Elimina el horario
            $schedule->delete();           
            return back()->with('status', 'Horario eliminado correctamente.');
        } else {         
            return back()->with('error', 'No se pudo encontrar el horario para eliminar.');
        }
    }
    public function editView($id)
    {
        $schedule = Schedule::find($id);
        //Verificar que el horario exista y pertenezca al usuario autenticado
        if (!$schedule || $schedule->user_id !== Auth::id()) {//verifica que el horario sea de el          
            return back()->withErrors(['error' => 'El horario no fue encontrado o no tienes permiso para editarlo.']);
        }
        return view('Schedule.edit', compact('schedule'));
    }
    public function edit(Request $request,$id){
        $validated = $request->validate([//valida los campos llenos
            'day' => 'required|string',
            'time' => 'required|date_format:H:i',
        ]);
    
        $user_id = Auth::id();
        $day = $validated['day'];
        $time = $validated['time'];

        $schedule = Schedule::findOrFail($id);//busca el horario a modificar

        if (Schedule::isDuplicate($user_id, $day, $time)) {
            // Si existe un horario duplicado muestra el error
            return back()->withErrors(['error' => 'Ya existe un horario con el mismo día y hora.']);
        }
        //Se cambian atributos
        $schedule->day = $day;
        $schedule->hour = $time;
        $schedule->save();

        if (!$schedule->save()) {
            return back()->withErrors(['error' => 'Error al guardar el horario en la base de datos.']);
        }
        return redirect()->route('shedule.create')->with(['status' => 'Modificacion exitoso en la base de datos.']);
    }
}
