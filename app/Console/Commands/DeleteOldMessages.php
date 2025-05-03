<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use Carbon\Carbon;

class DeleteOldMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all chat messages older than 24 hours';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = Message::where('created_at', '<', Carbon::now()->subDay())->delete();
        $this->info("Deleted $deleted old messages.");
    }
}
