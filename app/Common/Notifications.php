<?php

namespace App\Common;

use App\Jobs\EmailNotificationJobs;
use App\Common\SendEmail;
use App\Common\SendTelegram;
use App\Common\SingleSMS;

use App\BackendModel\NotificationTypeUsers;
use App\BackendModel\NotificationType;
use App\BackendModel\EnquiryType;
use App\PusherSetting;
use App\SmsPanel;
use App\User;
use App\UserFcmToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Notifications
{
    /**
     * @param $vendorId
     * @param $type
     */
    public function storeNotifications($vendorId, $type)
    {
        $exist = NotificationTypeUsers::where('vendor_id', $vendorId)->where('type', $type)->first();
        if (!$exist) {
            $notifications = new NotificationTypeUsers();
            $notifications->vendor_id = $vendorId;
            $notifications->type = $type;
            if($type==NotificationTypeUsers::SMS)
            {
                $notifications->status =NotificationTypeUsers::DEACTIVATE;
            }
            $notifications->save();
        }
    }

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $name
     * @param $content1
     * @param $content2
     * @param $logo
     * @param $attachment
     * @param $telegramId
     * @param $vendorId
     * @param $mobileNumber
     * @param $defaultRoute
     * @param $defaultSenderId
     */
    public function notifications($from, $to, $subject, $name, $content1, $content2, $logo, $attachment, $telegramId, $vendorId, $mobileNumber, $defaultRoute, $defaultSenderId,$sendData = null)
    {
        try{
            if($sendData['user_id']){
                if(User::getNotify($vendorId)){
                    if($sendData){
                        $existPusher = PusherSetting::active()->first();
                        $message = (is_array($sendData['message'])) ? $sendData['message']['text'] : $sendData['message'];
                        if($sendData['page'] == 'ivr'){
                            $did = $sendData['did'] ?? '';
                            $name = $sendData['name'] ?? '';
                            $staff = $sendData['staff'] ?? '';
                            $enq = $sendData['enquiry'];
                            $mobile = $sendData['mobile'];
                            $enq_id = ($enq)? $enq->pk_int_enquiry_id : null;
                        }else{
                            $name = null;
                            $staff = null;
                            $did = null;
                            $enq_id = null;
                            $mobile = null;
                        }
                        $this->sendPushNotificationWeb($sendData['user_id'],$existPusher,$message,$sendData['page'],$name,$staff,$did,$enq_id,$mobile);
                    }
                }
            }
        }catch(\Exception $e){
            Log::info($e->getMessage());
        }
    
        $existTelegram = NotificationTypeUsers::where('vendor_id', $vendorId)->where('status', NotificationTypeUsers::ACTIVATE)->where('type', NotificationType::TELEGRAM)->first();
        if ($existTelegram) {
            try{
                $telegram = new SendTelegram();
                if(is_array($content1))
                    $telegram->telegram($telegramId, $content1['text'],$content1['buttons']);
                else
                    $telegram->telegram($telegramId, $content1);
            }
            catch(\Exception $exp){
                Log::info($exp->getMessage());
            }
        }
    }

    public static function getUserTokens($user_id,$idArray=null)
    { 
        if($idArray){
            $check = UserFcmToken::whereIn('user_id',$idArray);
        }else{
            $check = UserFcmToken::where('user_id',$user_id);
        }

        if($check->count() > 0){
            $tokens = $check->pluck('token')->toArray();

            return $tokens;
        }else{
            $user = User::select('fcm_token')->find($user_id);
            if($user){
                if($user->fcm_token)
                    return [$user->fcm_token];
                else
                    return [];
            }else{
                return [];
            }
        }

        return [];

    }

    public static function sendPushNotificationWeb($user_id,$data,$message,$page,$name=null,$staff=null,$did=null,$enq_id = null,$mobile=null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('PUSH_NOTIFICATION_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>array(
              "user_id"=> (string)$user_id,
              "app_key"=> (string)$data->app_key,
              "app_id"=> (string)$data->app_id,
              "app_secret"=> (string)$data->app_secret,
              "cluster"=> (string)$data->cluster,
              "message"=> (string)$message,
              "page" => (string)$page,
              "name" => (string)$name,
              "staff" => (string)$staff,
              "did" => (string)$did,
              'enq_id' => $enq_id,
              'caller_number' => $mobile
              )
          ));

        $response = curl_exec($curl);
        curl_close($curl);
        // return $response;
    }
}
