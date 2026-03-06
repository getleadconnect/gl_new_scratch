<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScratchCustomer extends Model
{

    const SCRATCHED=1;
    const NOT_SCRATCHED=0;
    const REDEEMED=1;
    const NOT_REDEEMED=0;
 
    protected $guarded = [];

    protected $table = 'scratch_customers';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
