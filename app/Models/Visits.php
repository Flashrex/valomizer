<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

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
        'crawler'
    ];

    public static function getIgnoredUserAgents(): array
    {
        return static::$ignored_user_agents;
    }
}
