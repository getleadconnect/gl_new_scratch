<?php

namespace App\Traits;


use App\Models\User;
use App\Models\ScratchCount;
use App\Models\Campaign;

use Session;
use Carbon\Carbon;

trait GeneralTrait
{
    	
	public function checkUserStatus($id)
	{
		$user_id=$id;
		$scnt=ScratchCount::where('user_id',$user_id)->pluck('balance_count')->first();
		$user=User::where('id',$user_id)->first();
		
		if($user->subscription_end_date!='')
		{
			$subscription_date = Carbon::create($user->subscription_end_date)->addDays(1)->format('Y-m-d');
		}
		else
		{
			$subscription_date='';
		}
				
		$result=true;
		
		if($user->subscription_start_date=='' || $user->subscription_end_date=='')	 
		 {
			Session::put('msg_title','You have no Subscription');
			Session::flash('msg_swal',"Please subscribe now!!!");
			$result=false;
		 }
		 else if($subscription_date<=date('Y-m-d'))	 
		 {
			Session::put('msg_title','Subscription Expired!!!');
			Session::flash('msg_swal',"Re-new your subscription.");
			$result=false;
		 }
		 
		 else if($scnt=='' || $scnt<=0)
		 {
			Session::put('msg_title','Insufficient Scratches.');
			Session::flash('msg_swal',"Purchase scratches now.");
			$result=false;
		 }
		
		 return $result;
	}
	
		
	public function checkScratchCountStatus($id)
	{
		$user_id=$id;
		$scnt=ScratchCount::where('user_id',$user_id)->pluck('balance_count')->first();
		$result=true;
		if($scnt=='' || $scnt<=0)
		 {
			Session::put('msg_title','Insufficient Scratches.');
			Session::flash('msg_swal',"Purchase scratches now.");
			$result=false;
		 }
		
		 return $result;
	}
	
			
	public function checkCampaignExpired($campaign_id)
	{
		$sof=Campaign::where('id',$campaign_id)->first();
		if($sof->end_date=='' || $sof->end_date==null)
		{
			$result=false;
		}
		else
		{
			$end_date = Carbon::create($sof->end_date)->addDays(1)->format('Y-m-d');
			
			 if($end_date<=date('Y-m-d'))	 
			 {
				$result=false;
			 }
			 else 
			 {
				 $result=true;
			 }
		}
		
		return  $result;
	}
	
	
	
}
