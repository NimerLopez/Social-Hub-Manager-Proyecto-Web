<?php

namespace App\Tools;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Date;
use Storage;



class ValidateSchedule
{
    public function ValidateDate($schedules)
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
        $scheduleHour = substr($schedule->hour, 0, 5);
        if ($currentDay==$schedule->day && $currentTime==$scheduleHour) {
            Storage::append("archivo.txt",$currentDay." ".  $scheduleHour. "" . $currentTime);
            return true;
        }
        return false;
    }
    
}
