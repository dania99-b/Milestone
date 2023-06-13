<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PublishAdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishing image';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $currentDateTime = Carbon::now()->addHours(3);

        $advertisements = DB::table('advertisments')
        ->where('expired_at', '>=', $currentDateTime->toDateTimeString())
        ->get();
    
        foreach ($advertisements as $advertisement) {
            DB::table('advertisments')
                ->where('id', $advertisement->id)
                ->update(['is_shown' => true]);
        }
        return Command::SUCCESS;
    }
}
