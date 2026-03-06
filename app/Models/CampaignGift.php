<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignGift extends Model
{
    protected $guarded = [];

    protected $table = 'campaign_gifts';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
