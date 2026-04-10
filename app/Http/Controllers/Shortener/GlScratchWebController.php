<?php

namespace App\Http\Controllers\Shortener;

use App\Http\Controllers\Controller;

use App\Mail\GlScratchSuccessMail;
use App\Mail\VerifyEmailScratch;
use Illuminate\Http\Request;

use GuzzleHttp\Client;

use App\Models\ScratchLink;
use App\Models\Campaign;
use App\Models\CampaignGift;
use App\Models\ScratchCustomer;
use App\Models\ScratchLinkHistory;
use App\Models\Branch;
use App\Models\User;
use App\Models\Setting;
use App\Models\CompanyLogo;

//use App\Models\GlApiTokens;
use App\Models\UserOtp;

use App\Common\Common;
use App\Common\WhatsappSend;
use App\Services\WhatsappService;
use App\Jobs\SendEmailJob;
use App\Services\CrmApiService;
use App\Jobs\SentCrmServiceJob;

use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use App\Traits\GeneralTrait;

use Flash;
use Log;
use Mail;
use Auth;
use Session;


class GlScratchWebController extends Controller
{

   use  CrmApiService, GeneralTrait;
 
    public function index($id,$code)
    {
		
        $user_id=$id;

        $logo=CompanyLogo::where('user_id',$user_id)->where('type','logo')->where('is_active',1)->first();
        $favicon=CompanyLogo::where('user_id',$user_id)->where('type','favicon')->where('is_active',1)->first();

        $userStatus=User::where('id',$id)->where('status',1)->pluck('subscription_end_date')->first();
        
        if($userStatus==null) //checking for user account is active/expired
        {
            $messageText = "Oops!! This account is disabled!";
            return view('gl-scratch-web.short-link.invalid',compact('messageText'));
        }
        elseif(date($userStatus)<date('Y-m-d'))
        {
            $messageText = "Oops!! This account is expired!";
            return view('gl-scratch-web.short-link.invalid',compact('messageText'));
        }
        
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
                    return view('gl-scratch-web.scratch.index', compact(['user','offer','scratchlink','branches','otp_enabled','favicon','logo']));
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
       
   
    public function verifyMobile(Request $request)
    {
	
        if(request()->has('bill_no')){
            $check_num = ScratchCustomer::where('bill_no',request('bill_no'))->where('user_id',$request->vendor_id)->first();
            if($check_num){
                return response()->json(['msg' => "You already Scratched with this bill number. Please try with other.", 'status' => false]);
            }
        }

		//1634 is luxe lights
		if(request('vendor_id')!=1634)
		{
			$check_num = ScratchCustomer::join('campaign_gifts', 'scratch_customers.campaign_gift_id', '=', 'campaign_gifts.id')
			->join('scratch_links', 'campaign_gifts.campaign_id' ,'=', 'scratch_links.campaign_id')
			->where(function($q){
				if(request()->has('offer_id'))
					$q->where('campaign_gifts.campaign_id', request('offer_id'));
					$q->where('scratch_customers.country_code', request('country_code'))
					->where('scratch_customers.mobile', request('mobile'));    
				})
			->where('scratch_customers.user_id', request('vendor_id'))
			->first();
			
			if($check_num)
			{
				return response()->json(['msg' => "You have already used up your chance. Please try with a different number", 'status' => false]);
			}
		}
		
		$mobile = $request->country_code . $request->mobile;
  	            
        //bypass otp sending
        $otp_verify_status = $this->otpBypass(request('vendor_id'));

		if($otp_verify_status=="Disabled")
		{
			return response()->json(['msg' => "Enabled otp", 'status' => true]);
		}

		//otp send to whats app --------------------------------------------------
		
        try {

        //NIKKOY HONDA SEND OTP -----------------------------------------------------------------

            if(request('vendor_id')==1678)
            {

                $otp = rand(1111, 9999);

                $matchThese = ['number' => $request->mobile, 'user_id' => $request->vendor_id,'otp_type' => 'scratch_web'];
                UserOtp::updateOrCreate($matchThese, ['otp' => $otp]);

                try {
                    $dataSend = [
                        'mobile_no' => $mobile,
                        'otp' => $otp
                    ];
                    
                   $res=$this->nikkoyHondaSendOtp($dataSend);
                    
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }

                $code = basename(parse_url($request->link, PHP_URL_PATH));
                $link = ScratchLink::where('short_code',$code)->first();
                return response()->json(['msg' => "Please Wait For Your Otp", 'status' => true,'slink'=>$link,'code'=>$code]);

            }
        
        else {
                                                                                                                      $otp = rand(1111, 9999);

                $matchThese = ['number' => $request->mobile, 'user_id' => $request->vendor_id,'otp_type' => 'scratch_web'];
                UserOtp::updateOrCreate($matchThese, ['otp' => $otp]);
                            
                Session::put('number',$request->mobile);
                
                try {
                    $dataSend = [
                        'mobile_no' => $mobile,
                        'otp' => $otp
                    ];
                    
                    (new WhatsappSend(resolve(WhatsappService::class)))->sendWhatsappOtp($dataSend);
                    
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }

                $code = basename(parse_url($request->link, PHP_URL_PATH));
                $link = ScratchLink::where('short_code',$code)->first();
                return response()->json(['msg' => "Please Wait For Your Otp", 'status' => true,'slink'=>$link,'code'=>$code]);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }

    }
	
    public function otpBypass($userId)
    {
        $val=Setting::where('settings_type','otp_enabled')->where('user_id',$userId)
        ->pluck('settings_value')->first();
        return $val;
    }

	
    public function verifyOTP(Request $request)
    {
        // return response()->json(['msg' => "Token Expired.!! Try again", 'status' => true]);

        $requestOtp = $request->otp;
        $number = Session::get('number');
        if (!empty($number)) {
            if ($number == $request->mobile) {
                $otpOld = Session::get('otp');
                if (!empty($otpOld)) {
                    if ($otpOld == $requestOtp) {
                        return response()->json(['msg' => "OTP Verification successful", 'status' => true]);
                    } else {
                        return response()->json(['msg' => "Invalid OTP", 'status' => false]);
                    }
                } else {
                    Session::flush();
                    return response()->json(['msg' => "Some error occurred!! Try again..", 'status' => false]);
                }

            }

        } else {
            Session::flush();
            return response()->json(['msg' => "Token Expired.!! Try again", 'status' => false]);
        }
    }
	

public function nikkoyHondaSendOtp($data)
{
        try
        {
            $phoneid = 589291010945154;
                $otp_url = "https://api.msg.bonvoice.com/v3/".$phoneid."/messages";
                        
                $params=[
                    "messaging_product"=> "whatsapp",
                    "recipient_type"=> "individual",
                    "to"=> $data['mobile_no'],
                    "type"=> "template",
                    "template"=> [
                        "name"=> "auth_test_template",
                        "language"=> [
                            "code"=> "en"
                        ],
                        "components"=> [
                                [
                                    "type"=> "body",
                                    "parameters"=> [
                                        [
                                            "type"=> "text",
                                            "text"=> $data['otp']
                                        ]
                                    ]
                                ],
                                [
                                    "type"=> "button",
                                    "sub_type"=> "url",
                                    "index"=> "0",
                                    "parameters"=> [
                                        [
                                            "type"=> "payload",
                                            "payload"=> ""
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ];

                    $headers = [
                        'apikey' => 'f08d3ea7-3fa1-11f0-98fc-02c8a5e042bd',
                        'Content-Type' => 'application/json',
                    ];

                    $client = new Client();
                    $response = $client->request('POST', $otp_url, [
                        'json' => $params,
                        'headers' => $headers,
                    ]);
                    
                    $result=json_decode($response->getBody(), true);
                    \Log::info($result);
                    return $result;
        }
        catch(\Exception $e)
        {
            \Log::info($e->getMessage());
            return $e->getMessage();
        }
 }


    public function scratchCustomer(Request $request)
    {
		
        $mobile = $request->country_code . $request->mobile;

		$otp_verify_status=$this->otpBypass($request->vendor_id);

		$type_id= Campaign::where('id', $request->offer_id)->where('status',1)->pluck('type_id')->first();
        
		if($otp_verify_status=="Disabled")
		{
            $customer = new ScratchCustomer();
            $customer->user_id = $request->vendor_id;
			$customer->name = $request->name;
			$customer->country_code = $request->country_code;
			$customer->mobile = $request->mobile;
			$customer->cust_mobile = $mobile;
			$customer->status = ScratchCustomer::NOT_SCRATCHED;
            $customer->redeem = ScratchCustomer::NOT_REDEEMED;
            $customer->email = $request->email;
            $customer->branch_id = $request->branch;
			$customer->bill_no = $request->bill_no;
			$customer->short_code = $request->short_code;

            $giftListing = CampaignGift::where('campaign_id', $request->offer_id)
                          ->where('balance_count', '>', '0')->where('status',1)
                          ->inRandomOrder()->first();
						
            if ($giftListing) 
			{
                do {
                    $uniqueId = 'GW' . strtoupper(substr(uniqid(), 8));
                    $unique_flag = ScratchCustomer::where('unique_id', $uniqueId)->exists();
                } while ($unique_flag);
								
				if($giftListing->winning_status==1)
					$customer->win_status = 1;
				else
					$customer->win_status = 0;
				
                $customer->unique_id = $uniqueId;
				$customer->campaign_id = $giftListing->campaign_id;
                $customer->campaign_gift_id = $giftListing->id;
                $customer->offer_text = $giftListing->description;
				$customer->redeem_source='web';
				$customer->type_id=$type_id;
                $customer->save();

                $giftListing->customer_id = $customer->id;
                $giftListing->unique_id = $uniqueId;
                $giftListing->customer_name = $customer->name;
				
				$giftListing['image'] = url('uploads').'/'.$giftListing->gift_image;
				
                return response()->json(['status' => true, 'offerListing' => $giftListing]);
            }
			
            Session::flush();
            return response()->json(['msg' => "Scratch Offer is Completed", 'status' => false]);
        
		}
		else{
			
            $requestOtp = $request->otp;
            $otpOld = UserOtp::where('number',$request->mobile)->where('user_id',$request->vendor_id)->where('otp_type','scratch_web')->latest()->first();
            
            if (!empty($otpOld)) {
                if ($otpOld->otp == $requestOtp) {
                    $customer = new ScratchCustomer();
                    $customer->name = $request->name;
					$customer->user_id = $request->vendor_id;
					$customer->country_code = $request->country_code;
					$customer->mobile = $request->mobile;
					$customer->cust_mobile = $mobile;
					$customer->status = ScratchCustomer::NOT_SCRATCHED;
					$customer->redeem = ScratchCustomer::NOT_REDEEMED;
					$customer->email = $request->email;
					$customer->branch_id = $request->branch;
					$customer->bill_no = $request->bill_no;
					$customer->short_code = $request->short_code;

                    $giftListing = CampaignGift::where('campaign_id', $request->offer_id)
                                    ->where('balance_count','>', '0')->where('status',1)
                                    ->inRandomOrder()->first();
 										
                    if ($giftListing) {
                        do {
                            $uniqueId = 'GW' . strtoupper(substr(uniqid(), 8));
                            $unique_flag = ScratchCustomer::where('unique_id', $uniqueId)->exists();
                        } while ($unique_flag);
						
						
						if($giftListing->winning_status==1)
							$customer->win_status = 1;
						else
							$customer->win_status = 0;
						
                        $customer->unique_id = $uniqueId;
						$customer->campaign_id = $giftListing->campaign_id;
                        $customer->campaign_gift_id = $giftListing->id;
                        $customer->offer_text = $giftListing->description;
						$customer->redeem_source='web';
						$customer->type_id=$type_id;
						$customer->save();
                        
                        $giftListing->customer_id = $customer->id;
                        $giftListing->unique_id = $uniqueId;
                        $giftListing->customer_name = $customer->name;
						
                        $giftListing['image'] = url('uploads').'/'.$giftListing->gift_image;

                        return response()->json(['status' => true, 'offerListing' => $giftListing]);
                    }
                    return response()->json(['msg' => "Scratch Offer is Completed", 'status' => false]);
                } else {
                    return response()->json(['msg' => "Invalid OTP", 'status' => false]);
                }
            } else {
                Session::flush();
                return response()->json(['msg' => "Some error occurred!! Try again..", 'status' => false]);
            }
        }

        return response()->json(['msg' => "Token Expired.!! Try again", 'status' => false]);
        /* end SMS verification */
    }

    public function glScratched($id,$web_api=null)
    {

        $customer = ScratchCustomer::find($id);

        $user_id = User::getUserIdApi($customer->user_id);
       		
        $giftListing = CampaignGift::where('id', $customer->campaign_gift_id)->select('winning_status')->first();
        $customer->status = ScratchCustomer::SCRATCHED;
        $uniqueId = $customer->unique_id;
        $offetText = $customer->offer_text;
				
        /** .... Send email ...*/
        if ($giftListing->winning_status == 1) 
		{
           /*if ($customer->email != NULL) {
                try{
                    $content = $customer->name . ' Congratulations!! You have won ' . $offetText . '.And Your Redeem Id is ' . $uniqueId . '. Getlead';
                    $data = [
                        'email' => $customer->email,
                        'file_name' => 'App\Mail\GlScratchSuccessMail', // This is the class name of the Mailable
                        'content' => $content,
                    ];
					
                    dispatch(new SendEmailJob($data));
					
                }catch(\Exception $e){
                    \Log::info($e->getMessage());
                }
            } 
			*/
        }
		
		//send data to crm -------------------------------
		
		try{
			$apiToken=Setting::where('settings_type','crm_api_token')->where('user_id',$user_id)->first();
			
			if($apiToken)
			{
				if($apiToken->settings_value!="" and $apiToken->status==1)
				{
					$data=[
					  'token'=>$sdt->settings_value,
					  'name'=>$customer->name,
					  'email'=>$customer->email,
					  'country_code'=>$customer->country_code,
					  'mobileno'=>$customer->mobile,
					  'source'=>'Gl-Scratch',
					  'company_name'	=>$customer->company_name,
					];
					
					//------send partner to crm-----
					//$send_response=$this->sendCustomerDetailsToCrm($data);
					//------------------------------

					dispatch(new SentCrmServiceJob($data));
				}
			}
		}Catch(\Exception $e)
		{
			\Log::info($e->getMessage());
		}
		
		//-------------------------------------------------
		
        $giftListing = CampaignGift::where('id', $customer->campaign_gift_id)->where('balance_count','>', '0')->first();
        if($giftListing){
            $giftListing->balance_count--;
            $giftListing->save();
			
			$sl=ScratchLink::where('short_code',$customer->short_code)->first();
			if($sl->link_type=="Multiple")
			{
				$sl->status=0;
				$sl->save();
			}
        }

        $flag = $customer->save();
        if ($flag) {
            return response()->json(['msg' => "Success", 'status' => true]);
        }
        return response()->json(['msg' => "Sorry Somthing Went Wrong .!! Try again", 'status' => false]);
    }

    public function gotoApiScratch($code)
    {
        $scratchObject = ScratchCustomer::where('unique_id',$code);
        $expired = false;
        if($scratch = (clone $scratchObject)->first()){
            $giftList = CampaignGift::where('id', $scratch->campaign_gift_id)->first();
            $user=User::where('id',$scratch->user_id)->where('status', 1)->first();            
                $offer=Campaign::where('status',1)->where('id',$giftList->campaign_id)->first(); 
                    $giftList->uniqueId = $code;
                    $giftList->customer_id = $scratch->id;
                    $giftList->customer_name = $scratch->name;

        }else{
            return view('gl-scratch-web.short-link.invalid');
        }

        if((clone $scratchObject)->where('status' , ScratchCustomer::NOT_SCRATCHED)->where('redeem' , ScratchCustomer::NOT_REDEEMED)->first()){
            return view('gl-scratch-web.short-link.scratch-new-design',compact(['user','offer','offerList','expired']));
        }else{
            $expired = true;
             return view('gl-scratch-web.short-link.scratch-new-design',compact(['user','offer','offerList','expired']));
        }
    }
	
	public function getBranchAutocomplete($user_id)
    {
        $userId = User::getUserIdApi($user_id);
        if(request()->filled('term'))
            $branches = Branch::where('user_id',$userId)
                            ->select('id','branch_name')
                            ->where(function($q){
                                $q->where('branch_name','LIKE','%'.request('term').'%');
                            })
                            ->get();
        else
            $branches = [];
                
        return response()->json([ 'status' => 'success','data' => $branches]);
    }
    
}
