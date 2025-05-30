<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'valomizer:fetch-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch agents and maps from the Valorant API and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('valomizer:fetch-agents');
        $this->call('valomizer:fetch-maps');
        $this->info('All data fetched successfully.');
    }
}
