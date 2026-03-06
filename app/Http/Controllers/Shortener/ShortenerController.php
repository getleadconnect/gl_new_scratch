<?php
namespace App\Http\Controllers\Shortener;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Carbon\Carbon;
use App\Common\Common;
//use App\Common\Variables;
use Jenssegers\Agent\Agent;

use App\Models\ScratchLink;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\CampaignGift;

use App\Models\ScratchLinkHistory;

use App\Models\Setting;
use App\Models\User;

use App\Traits\GeneralTrait;


use Auth;
use Flash;

use Session;

class ShortenerController extends Controller
{
	
	use GeneralTrait;
	
	/*public function __construct()
	{
		$this->middleware('cors');
	}*/
	
    public function index($id,$code)
    {
		$user_id=$id;

		$messageText = "Oops this link is Invalid";
		$scratchlink=ScratchLink::where('user_id',$user_id)->where('short_code',$code)->where('status',1)->first();

        if(!empty($scratchlink)){

                //$scratchlink = $scratchlink->where('user_id',$user_id)->where('status',1)->first();
		
				if(!$scratchlink){
                    $messageText = "Oops!! This link is invalid.";
                    return view('gl-scratch-web.short-link.invalid',compact('messageText'));
                }
				
				$offerListing = CampaignGift::where('campaign_id', $scratchlink->campaign_id)
								->where('balance_count', '>', '0')->where('status',1)->first();
				if(!$offerListing)
				{
					$messageText = "Oops!! This offer is closed.";
					return view('gl-scratch-web.short-link.invalid',compact('messageText')); 
				}

				
				$offer=Campaign::where('id',$scratchlink->campaign_id)->first();	
				if($offer)
				{
					if( $offer->status!=1)
					{
						$messageText = "Oops!! This offer is inactive.";
						return view('gl-scratch-web.short-link.invalid',compact('messageText')); 
					}
					else
					{
						$result=$this->checkCampaignExpired($offer->id);
						if($result==false)
						{
							$messageText = "Oops!! This link is expired!!!.";
							return view('gl-scratch-web.short-link.invalid',compact('messageText'));
						}
					}
				}
				else
				{
					$messageText = "Oops!! This link is Invalid.";
					return view('gl-scratch-web.short-link.invalid',compact('messageText')); 
				}
				
                $agent = new Agent();        
                $device = $agent->device();
                $os = $agent->platform();
                $browser = $agent->browser();
				
                if($agent->isMobile()){
                    $device_type = ScratchLinkHistory::MOBILE;
                }
                elseif($agent->isTablet()){
                    $device_type = ScratchLinkHistory::TABLET;
                }
            
                elseif($agent->isPhone()){
                    $device_type = ScratchLinkHistory::PHONE;
                }
                elseif($agent->isDesktop()){
                    $device_type = ScratchLinkHistory::DESKTOP;
                }
                elseif($agent->isRobot()){
                    $device_type = ScratchLinkHistory::ROBOT;
                }
				
                $ip =Common::getClientIp();

                $history = new ScratchLinkHistory();
                $history->scratch_link_id = $scratchlink->id;
                $history->date = Carbon::now();
                $history->ip_address = $ip;
                $history->device = $device;
                $history->os = $os;
                $history->browser = $browser; 
                $history->device_type = $device_type;
				
                // $ip = '2409:4073:296:6513:d1d8:abed:535c:c4d9';
                /***Start ip-api.com */
				
                try{
                    $response = file_get_contents('http://ip-api.com/json/'.$ip);
                    $response = json_decode($response);
                  
                    if($response->status == 'success')
                    {
                        $history->country =$response->country ;
                        $history->city = $response->city;
                        $history->region = $response->regionName;
                        $history->area_code = $response->zip;
                        $history->country_code = $response->countryCode;
                        $history->latitude = $response->lat;
                        $history->logitude = $response->lon;
                        $history->timezone = $response->timezone; 
                        $history->ip_address = $response->query;
    
                        /***end ip-api.com */
    
                    }else{
                         /*** Geo Location */
    
                        $ipdat = @json_decode(file_get_contents( 
                        "http://www.geoplugin.net/json.gp?ip=".$ip ));                
                        $history->country = $ipdat->geoplugin_countryName ;
                        $history->city = $ipdat->geoplugin_city;
                        $history->region = $ipdat->geoplugin_region;
                        $history->area_code = $ipdat->geoplugin_areaCode;
                        $history->country_code = $ipdat->geoplugin_countryCode;
                        $history->continent = $ipdat->geoplugin_continentName;
                        $history->latitude = $ipdat->geoplugin_latitude;
                        $history->logitude = $ipdat->geoplugin_longitude;
                        $history->currency = $ipdat->geoplugin_currencyCode;
                        $history->timezone = $ipdat->geoplugin_timezone; 

                        /***end Geo Location */
                    }
                }catch(\Exception $e){
                   \Log::info($e->getMessage()); 
                }
            $history->save();
            $scratchlink->click_count++;
            $scratchlink->save();

            $branches=Branch::where('user_id',$scratchlink->user_id)->get();        
            $expired = false;

            $offerList =[];
            $user=User::where('id',$scratchlink->user_id)->where('status', 1)->first();    
			
			$set=Setting::where('settings_type','otp_enabled')->where('user_id',$scratchlink->user_id)->first();
			if($set)
				$otp_enabled=$set->settings_value;
			else
				$otp_enabled="Disabled";
						
            if($user){       
                $offer=Campaign::where('user_id',$user_id)->where('status',1)->where('id',$scratchlink->campaign_id)->first(); 
                if($offer){
					
                    return view('gl-scratch-web.scratch.index', compact(['user','offer','scratchlink','branches','otp_enabled']));
                }else{
                    $messageText = "Oops!! There is no offers added.";
                    return view('gl-scratch-web.short-link.invalid',compact('messageText'));
                }
            }
			
        }else{
            $messageText = "Oops!! This is invalid offer.";
            return view('gl-scratch-web.short-link.invalid',compact('messageText','user_id'));
        }
        return view('gl-scratch-web.short-link.invalid',compact('messageText','user_id'));
    }
	
    public function form()
    {
        return view('gl-scratch-web.short-link.terms');
    }
	
    public function terms(Request $request){
        return view('gl-scratch-web.short-link.terms');
    }
	
    public function thankyou(Request $request){
        return view('gl-scratch-web.short-link.thankyou1');
    }
   
}
