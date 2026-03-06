<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    protected $table = 'branches';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
