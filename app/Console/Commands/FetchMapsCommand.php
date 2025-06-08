<?php

namespace App\Console\Commands;

use App\Models\Map;
use App\Models\Statistics;
use App\Services\ValorantApiService;
use Illuminate\Console\Command;

class FetchMapsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'valomizer:fetch-maps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get maps from the Valorant API and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle(ValorantApiService $api)
    {
        $maps = $api->getMaps();

        if (empty($maps)) {
            $this->info('No maps found or API request failed.');
            return;
        }

        $this->info("Found " . count($maps) . " maps. Processing...");
        $progressBar = $this->output->createProgressBar(count($maps));
        $progressBar->start();

        $newMaps = 0;
        $updatedMaps = 0;

        foreach ($maps as $map) {
            $existingMap = Map::where('uuid', $map['uuid'])->first();

            if ($existingMap) {
                $existingMap->update($map);
                $updatedMaps++;
            } else {
                $existingMap['active'] = true;
                $existingMap['gamemode'] = 'competitive';
                Map::create($map);
                $newMaps++;
            }

            $progressBar->advance();
        }

        $statistics = Statistics::where('key', 'fetched_maps')->first();
        if ($statistics) {
            $statistics->touch();
            $statistics->save();
        } else {
            Statistics::create([
                'key' => 'fetched_maps',
                'comment' => "Fetched {$newMaps} new maps and updated {$updatedMaps} existing maps."
            ]);
        }


        $progressBar->finish();
        $this->info("\nAll maps have been processed successfully.");
        $this->info("New Maps: {$newMaps}, Updated Maps: {$updatedMaps}");
    }
}
