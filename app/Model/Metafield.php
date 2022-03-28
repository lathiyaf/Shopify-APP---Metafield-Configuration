<?php

namespace App\Model;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Metafield extends Model
{
    use UsesUuid;
    protected $table = 'metafields';

    public function belongsToConfiguration(){
        return $this->belongsTo(MetafieldConfiguration::class, 'metafield_configuration_id', 'id' )->orderBy('sort_order','asc');
    }
}
