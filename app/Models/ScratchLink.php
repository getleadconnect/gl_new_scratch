<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScratchLink extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'scratch_links';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
