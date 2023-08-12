<?php

namespace App\Tools;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Date;
use Storage;



class ValidateSchedule
{
    public function ValidateDay($schedules)
    {
        foreach ($schedules as $schedule) {
            if ($this->validateSingleSchedule($schedule)) {
                return true;
            }
        }
        return false;
    }
    
    private function validateSingleSchedule($schedule)
    {
        $currentDay = Date::now()->dayName;
        $currentTime = date('H:i');
        $bdHour = substr($schedule->hour, 0, 5);
        if ($currentDay==$schedule->day && $currentTime==$bdHour) {
            Storage::append("archivo.txt",$currentDay." ".  $bdHour. "" . $currentTime);
            return true;
        }
        return false;
    }
    public function ValidateAllDate($queue){
        $currentDate = Date::now()->toDateString();
        $currentTime = date('H:i');
        $bdHour = substr($queue->scheduled_time, 0, 5);

        if ($currentDate == $queue->scheduled_date &&  $currentTime==$bdHour) {
            return true;
        }else{
            return false;
        }
    }
    
}
