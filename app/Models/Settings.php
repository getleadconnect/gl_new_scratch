<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{

    //use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'settings';

    protected $fillable = [
        'settings_type', 'settings_value', 'status','user_id'
    ];


}
