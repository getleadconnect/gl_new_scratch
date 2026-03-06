<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScratchLinkHistory extends Model
{
    use SoftDeletes;
	
    const MOBILE = 'Mobile';
    const TABLET = 'Tablet' ;
    const DESKTOP = 'Desktop';
    const PHONE = 'Phone' ;
    const ROBOT= 'Robot';
	
    protected $guarded=[];
    protected $table='scratch_link_histories';
    
}
