<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;
use MongoDB\BSON\UTCDateTime;

class Visits extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'identifier',
        'last_visit',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    protected static $ignored_user_agents = [
        'Blackbox Exporter',
        'Googlebot',
        'Bingbot',
        'curl',
        'bot',
        'spider',
        'crawler',
    ];

    public static function getIgnoredUserAgents(): array
    {
        return static::$ignored_user_agents;
    }

    /**
     *  Get unique visits since a given date.
     */
    public static function getUnique(Carbon $since) : Collection {
        return Visits::raw(function ($collection) use ($since) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'created_at' => ['$gte' => new UTCDateTime($since->getTimestamp() * 1000)],
                    ],
                ],
                [
                    '$sort' => ['created_at' => -1],
                ],
                [
                    '$group' => [
                        '_id' => '$ip',
                        'doc' => ['$first' => '$$ROOT'],
                    ],
                ],
                [
                    '$replaceRoot' => ['newRoot' => '$doc'],
                ],
            ]);
        });
    }
}
