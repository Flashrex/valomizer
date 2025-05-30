<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    public $fillable = [
        'uuid',
        'displayName',
        'description',
        'developerName',
        'releaseDate',
        'characterTags',
        'displayIcon',
        'displayIconSmall',
        'bustPortrait',
        'fullPortrait',
        'fullPortraitV2',
        'killfeedPortrait',
        'background',
        'backgroundGradientColors',
        'assetPath',
        'isFullPortraitRightFacing',
        'isPlayableCharacter',
        'isAvailableForTest',
        'isBaseContent',
        'role',
        'recruitmentData',
        'abilities',
        'voiceLine'
    ];

    protected $casts = [
        'backgroundGradientColors' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
