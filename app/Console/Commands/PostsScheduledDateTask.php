<?php

namespace App\Console\Commands;

use App\Models\Postqueue;
use App\Tools\PostQueueSend;
use App\Tools\ValidateSchedule;
use Illuminate\Console\Command;
use Storage;

class PostsScheduledDateTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:taskdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicia la publicacion por fecha';

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
        $usersqueue = Postqueue::all();
        $validateIns= new ValidateSchedule;
        $sendPostIns= new PostQueueSend;

        foreach ($usersqueue as  $queue) {
            if ( $queue) {
                $response=$validateIns->ValidateAllDate($queue);
                if ($response) {
                    $datapost=$sendPostIns->ValidatePostDateSocialNetwork($queue);
                    $text="existe fecha ". date("Y-m-d H:i:s") . "]";
                    Storage::append("archivo.txt",$text);
                }else{
                    $text="No existe fecha";
                    Storage::append("archivo.txt",$text);
                }
                
            }
        }
        $text="Segunda tareax";   
        Storage::append("archivo.txt",$text);
        return 0;
    }
}
