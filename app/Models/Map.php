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
        'callouts'
    ];

    protected $casts = [
        'callouts' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
