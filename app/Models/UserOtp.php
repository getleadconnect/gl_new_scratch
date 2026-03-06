<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    protected $fillable = ['user_id','otp','otp_type','number','expiry'];
    
    /**
     * otp_type = ['signup','login','scratch_web','scratch_api']
     */
}
