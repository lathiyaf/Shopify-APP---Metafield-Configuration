<?php

namespace App\Model;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class GlobalMetafield extends Model
{
    use UsesUuid;
    protected $table = 'global_metafields';
}
