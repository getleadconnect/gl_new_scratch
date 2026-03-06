<?php

namespace App\Http\Controllers\Shortener;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BackendModel\WhatsappLink;
use App\BackendModel\WhatsappLinkHistory as History;
use Jenssegers\Agent\Agent;
use App\Common\Common;
use Carbon\Carbon;


class WhatsappLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $code)
    {
        

        $link_details =WhatsappLink::where('code',$code)->whereStatus(WhatsappLink::ACTIVE)->first();
        if($link_details){
            $agent = new Agent();        
            $device = $agent->device();
            $os = $agent->platform();
            $browser = $agent->browser();
            if($agent->isMobile()){
                $device_type = History::MOBILE;
            }
            elseif($agent->isTablet()){
                $device_type = History::TABLET;
            }
        
            elseif($agent->isPhone()){
                $device_type = History::PHONE;
            }
            elseif($agent->isDesktop()){
                $device_type = History::DESKTOP;
            }
            elseif($agent->isRobot()){
                $device_type = History::ROBOT;
            }

        
            $ip =Common::getClientIp();
            $link_history = new History();
            $link_history->whatsapp_link_id = $link_details->id;
            $link_history->date = Carbon::now();
            $link_history->ip_address = $ip;
            $link_history->device = $device;
            $link_history->os = $os;
            $link_history->browser = $browser; 
            $link_history->device_type = $device_type;
             /***Start ip-api.com */

             $response = file_get_contents('http://ip-api.com/json/'.$ip);
             $response = json_decode($response);
         
             if($response->status == 'success')
             {
                 $link_history->country =$response->country ;
                 $link_history->city = $response->city;
                 $link_history->region = $response->regionName;
                 $link_history->area_code = $response->zip;
                 $link_history->country_code = $response->countryCode;
                 $link_history->latitude = $response->lat;
                 $link_history->logitude = $response->lon;
                 $link_history->timezone = $response->timezone; 
                 $link_history->ip_address = $response->query;

                 /***end ip-api.com */

             }else{

                    /*** Geo Location */
                    $ipdat = @json_decode(file_get_contents( 
                        "http://www.geoplugin.net/json.gp?ip=" . $ip)); 
                    $link_history->country = $ipdat->geoplugin_countryName ;
                    $link_history->city = $ipdat->geoplugin_city;
                    $link_history->region = $ipdat->geoplugin_region;
                    $link_history->area_code = $ipdat->geoplugin_areaCode;
                    $link_history->country_code = $ipdat->geoplugin_countryCode;
                    $link_history->continent = $ipdat->geoplugin_continentName;
                    $link_history->latitude = $ipdat->geoplugin_latitude;
                    $link_history->logitude = $ipdat->geoplugin_longitude;
                    $link_history->currency = $ipdat->geoplugin_currencyCode;
                    $link_history->timezone = $ipdat->geoplugin_timezone; 

                    /***end Geo Location */
             }
            $link_history->save();
            
                $link_details->count ++ ;
                $link_details->save();
                $message = rawurlencode($link_details->message);     
        
            $url = "https://wa.me/".$link_details->mobile."?text=".$message;
            return redirect()->to($url);
        }
        $url_b = ('http://getlead.co.uk');
        return redirect()->to($url_b);
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
