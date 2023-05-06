<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeletedImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'hide image';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDateTime = Carbon::now()->addHours(3);

        $images = DB::table('images')
        ->where('expired_at', '<=>', $currentDateTime->toDateTimeString())
        ->get();
    
        foreach ($images as $image) {
            DB::table('images')
                ->where('id', $image->id)
                ->update(['is_appear' => false]);
        }
        return Command::SUCCESS;
    }
}
