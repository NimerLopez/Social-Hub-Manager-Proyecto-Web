<?php

namespace App\Console\Commands;

use App\Models\Postqueue;
use App\Models\Schedule;
use App\Tools\ValidateSchedule;
use Illuminate\Console\Command;
use Storage;
use Illuminate\Support\Facades\Date;

class ScheduleTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicia la tarea de publicacion de cola';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usersqueue= new Postqueue;//instancia del modelo de colas
        $userIds = $usersqueue->getAllUserIds();//trae la lista de usuarios en cola
        $schedule_inst= new Schedule;//intacia al modelo de horarios
        $validateScheduleInst= new ValidateSchedule;
        foreach ($userIds as $userID) {
            $user_schedule = $schedule_inst->getByUserId($userID);//extrae los horarios por usuario
            if (count($user_schedule) > 0) {//si trae algo
                $scheduleval=$validateScheduleInst->ValidateDate($user_schedule);
                if ($scheduleval) {
                    $text="La hora es correcta";     
                    Storage::append("archivo.txt",$text);
                }else{
                    $text="La hora No es correcta";     
                    Storage::append("archivo.txt",$text);
                }
            //     try{
            //     if ($validateScheduleInst->validateSingleSchedule($user_schedule)) {
            //         $text="La hora es correcta";     
            //         Storage::append("archivo.txt",$text);
            //     }else{
            //         $text="La hora No es correcta";     
            //         Storage::append("archivo.txt",$text);
            //     }
            // }catch(\Exception $e){
            //     Storage::append("archivo.txt", $e->getMessage());
            // }
                $text="[" . date("Y-m-d H:i:s") . "]" . "2*Hola mi nombre es Nimer existen horarios".$userID;     
                Storage::append("archivo.txt",$text);
            }else{//si no tare nada imprime esto
                $text="[" . date("Y-m-d H:i:s") . "]" . "2*Hola mi nombre es Nimer No existen horarios".$userID;     
                Storage::append("archivo.txt",$text);
            }
        }
        
       
        return 0;
    } 
}
