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
    protected $signature = 'valomizer:startup';

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

        $startup = Statistics::where('key', 'startup')->first();

        if (! $startup) {
            Statistics::create([
                'key' => 'startup',
                'comment' => 'Started Application',
            ]);
        } else {
            $startup->touch();
            $startup->save();
        }

        $this->call(FetchAgentsCommand::class);
        $this->call(FetchMapsCommand::class);

        $this->info('Startup command executed successfully.');
    }
}
