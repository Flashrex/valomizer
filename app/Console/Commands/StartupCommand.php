<?php

namespace App\Console\Commands;

use App\Models\Statistics;
use Illuminate\Console\Command;

class StartupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:startup-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets executed on application startup to update statistics and fetch data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Executing startup command...');

        Statistics::updateOrCreate(
            ['key' => 'startup'],
            ['comment' => '']
        );

        $this->call(FetchAgentsCommand::class);
        $this->call(FetchMapsCommand::class);

        $this->info('Startup command executed successfully.');
    }
}
