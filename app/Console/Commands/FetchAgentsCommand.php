<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\Statistics;
use App\Services\ValorantApiService;
use Illuminate\Console\Command;

class FetchAgentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'valomizer:fetch-agents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get agents from the Valorant API and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle(ValorantApiService $api)
    {
        $agents = $api->getAgents();

        if (empty($agents)) {
            $this->info('No agents found or API request failed.');

            return;
        }

        $this->info('Found '.count($agents).' agents. Processing...');
        $progressBar = $this->output->createProgressBar(count($agents));
        $progressBar->start();

        $newAgents = 0;
        $updatedAgents = 0;
        foreach ($agents as $agent) {
            $existingAgent = Agent::where('uuid', $agent['uuid'])->first();

            if ($existingAgent) {
                $existingAgent->update($agent);
                $updatedAgents++;

            } else {
                $agent['active'] = $agent['isPlayableCharacter'] ?? false;
                Agent::create($agent);
                $newAgents++;
            }

            $progressBar->advance();
        }

        $statistics = Statistics::where('key', 'fetched_agents')->first();
        if ($statistics) {
            $statistics->touch();
            $statistics->save();
        } else {
            Statistics::create([
                'key' => 'fetched_agents',
                'comment' => "Fetched {$newAgents} new agents and updated {$updatedAgents} existing agents.",
            ]);
        }

        $progressBar->finish();

        $this->info("\nAll agents have been processed successfully.");
        $this->info("\nFound $newAgents new Agents and updated $updatedAgents Agents.");
    }
}
