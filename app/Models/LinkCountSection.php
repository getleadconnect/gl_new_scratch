<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkCountSection extends Model
{
    //
    
    protected $primaryKey = 'id';
    protected $table = 'link_count_section';

    protected $guarded=[];
}
