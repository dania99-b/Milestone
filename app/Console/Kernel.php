<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('Cron job started');                                                                                                                                            
          //  DB::table('guests')->delete();
            DB::table('guest_question_lists')->delete();
            Log::info('Cron job finished');
        })->everyMinute();

        $schedule->call(function () {
            $currentDateTime = Carbon::now()->addHours(3);
    
            $images = DB::table('images')
                ->where('published_at', '<=', $currentDateTime->toDateTimeString())
                ->get();
    
            foreach ($images as $image) {
                DB::table('images')
                    ->where('id', $image->id)
                    ->update(['is_show' => true]);
            }
        })->everyMinute();
        
        $schedule->command('ad:publish')->everyMinute();
        $schedule->command('ad:expired')->everyMinute();
        $schedule->command('image:publish')->everyMinute();
        $schedule->command('image:expired')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
