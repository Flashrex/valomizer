<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Map extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    public $fillable = [
        'uuid',
        'displayName',
        'narrativeDescription',
        'tacticalDescription',
        'coordinates',
        'displayIcon',
        'listViewIcon',
        'listViewIconTall',
        'splash',
        'stylizedBackgroundImage',
        'premierBackgroundImage	',
        'assetPath',
        'mapUrl',
        'xMultiplier',
        'yMultiplier',
        'xScalarToAdd',
        'yScalarToAdd',
        'callouts',
        'gamemode',
        'active',
    ];

    protected $casts = [
        'callouts' => 'array',
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    protected $hidden = [];
}
