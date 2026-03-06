<?php

namespace App\Jobs;

use App\Contracts\FcmServiceInterface;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $ids;
    protected $description;
    protected $title;
    protected $data;
    protected $key;

    public $tries = 3;
    public $timeout = 120;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ids,$title,$description,$data,$key=null)
    {
        $this->ids = $ids;
        $this->title = $title;
        $this->description = $description;
        $this->data = $data;
        $this->key = $key;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    // public function handle()
    // {
        // $Ids = $this->ids;
        // if(sizeof($Ids)>0){
        //     $chunks=array_chunk($Ids,50);

        /*********** Old API Depricated ********************************* */ 
            // $msg = array
            // (
            // 'body' 	=> $this->description,
            // 'title'	=> $this->title,
            // 'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            // 'icon'	=> 'default',/*Default Icon*/
            // 'sound' => 'alarm.mp3'/*Default sound*/
            // );
            // foreach($chunks as $registrationIds){
            //     $fields = array
            //         (
            //             'registration_ids'		=> $registrationIds,
            //             'notification'	=> $msg,
            //             'data' => $this->data
            //         );
            
            //     if(!$this->key){
            //         $this->key = config('appdata.FCM_KEY');
            //     }
            //     $headers = array
            //         (
            //             'Authorization: key=' . $this->key,
            //             'Content-Type: application/json'
            //         );
            //     #Send Reponse To FireBase Server	
            //     $ch = curl_init();
            //     curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            //     curl_setopt( $ch,CURLOPT_POST, true );
            //     curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            //     curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            //     curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            //     curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            //     $result = curl_exec($ch );
            //     curl_close( $ch );
            //     #Echo Result Of FireBase Server
            //     echo $result;
            // }
        /*********** Old API Depricated ********************************* */ 
    // }

    public function handle(FcmServiceInterface $fcmService){
        $Ids = $this->ids;
        if(sizeof($Ids)>0){
            // Get the single access token for the project
            $accessToken = $fcmService->getAccessToken();
            foreach($Ids as $userToken){
                $fcmService->sendNotification($userToken,$accessToken,$this->title,$this->description,$this->data);
            }
        }
    }

}
