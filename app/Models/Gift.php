<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $guarded = [];

    protected $table = 'gifts';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
