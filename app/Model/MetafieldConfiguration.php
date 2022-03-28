<?php

namespace App\Model;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetafieldConfiguration extends Model
{
    protected $table = 'metafield_configurations';

    use UsesUuid;

    public function hasManyMetafield(){
        return $this->hasMany(Metafield::class, 'metafield_configuration_id', 'id' )->select('id','metafield_configuration_id','value');
    }

    public function hasOneGlobalMetafield(){
        return $this->hasOne(GlobalMetafield::class, 'metafield_configuration_id', 'id' )->select('id','metafield_configuration_id','titles','add_in','value','status');
    }
}
