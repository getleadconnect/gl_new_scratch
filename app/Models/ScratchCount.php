<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;

class ScratchCount extends Model
{
   // use SoftDeletes;

	protected $guarded = [];  
	
    protected $primaryKey = 'id';
	
    protected $table = 'scratch_counts';



public static function getTotalScratchCount($user_id)
{
	$t_count=self::where('user_id',$user_id)->pluck('total_count')->first();
	return $t_count;
}

public static function getUsedScratchCount($user_id)
{
	$u_count=self::where('user_id',$user_id)->pluck('used_count')->first();
	return $u_count;
}

public static function getBalanceScratchCount($user_id)
{
	$b_count=self::where('user_id',$user_id)->pluck('balance_count')->first();
	return $b_count;
}

public function users()
{
	return $this->belongsTo(User::class,'user_id')->select('id','name');
}


}
