<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeleteAdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hide advertisement';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDateTime = Carbon::now()->addHours(3);

        $advertisements = DB::table('advertisments')
        ->where('expired_at', '<=>', $currentDateTime->toDateTimeString())
        ->get();
    
        foreach ($advertisements as $advertisement) {
            DB::table('advertisments')
                ->where('id', $advertisement->id)
                ->update(['is_shown' => false]);
        }
        return Command::SUCCESS;
    }
}
