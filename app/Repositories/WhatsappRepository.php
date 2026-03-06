<?php

namespace App\Repositories;

//use App\Jobs\SentServiceJob;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WhatsappRepository
{
    public $url;
    public $bussinessId;
    public $token;

    public function __construct()
    {
        $this->bussinessId = 107390568652882;
        $this->url = 'https://graph.facebook.com/v19.0/'.$this->bussinessId.'/messages';
        $this->token = 'EAAtUQaQveEkBO0gmcNGs7gwa5Q6tch09XviFFSevZAlfUePAuiBHqrY42EdhicnxrQZAPsowjXEARlQaUz2AmoWu7T8rxAxQfWZAE4SjaWvLmazWYd2gscSgC8A1p3dcsJKELfZBW0Kdw9aY3bEYi1PIXSDGjVZA78MCg4Mn0yw76DJYe3rl772KVMgDvKQzp3Sk6svkZB9MhhPkDu';
    }

    public function sendWhatasappOtp($data)
    {
        // Send the message
        $params = [
            "messaging_product" => "whatsapp",
            "to" => $data['mobile_no'],
            "type" => "template"
        ];
        $params['template'] = [
            "name" => "getleadotp",
            "language" => [
                "code" => "en"
            ]
        ];
        $components['components'] = [
            [
                "type" => "body",
                "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $data['otp']
                                ]
                            ]
                ],
                [
                    "type" => "button",
                    "sub_type" => "url",
                    "index" => 0,
                    "parameters" => [ // Optional
                        [
                            "type" => "text",
                            "text" => $data['otp']
                        ]
                    ]
                ]
            ];
        $params['template']["components"] = $components['components'];
		
		try {
           
		   $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->token
            ];

			//SentServiceJob::dispatch($this->url, $params,$headers);
			
			$client = new Client();
            $response = $client->request('POST', $this->url, [
                'json' => $params,
                'headers' => $headers,
            ]);
            
			$result=json_decode($response->getBody(), true);
			//return $result['messages'][0]['message_status'];  //will return 'accepted'
			return $result;

        } catch (\Exception $e) {
            Log::info('Sent service job failed: ' . $e->getMessage());
            return $e->getMessage();
		}

    }
	
	
//send whats app confirm message
	
	
	public function sendWhatsappConfirmMessage($data)
    {
        // Send the message
        $params = [
            "messaging_product" => "whatsapp",
            "to" => $data['mobile_no'],
            "type" => "template"
        ];
        $params['template'] = [
            "name" => "scratch_confim",
            "language" => [
                "code" => "en"
            ]
        ];
	
		
        $components['components'] = [
            [
                "type" => "body",
				"parameters" => [
									[
										"type" => "text",
										"text" => $data['customer']
									],
									[
										"type" => "text",
										"text" => $data['gift_name']
									],
									[
										"type" => "text",
										"text" => $data['campaign']
									],
									
									[
										"type" => "text",
										"text" => $data['reference_id']
									],
									
                            ]
                ],
 
            ];
        $params['template']["components"] = $components['components'];
		
		try {
           
		   $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->token
            ];

			//SentServiceJob::dispatch($this->url, $params,$headers);
			
			$client = new Client();
            $response = $client->request('POST', $this->url, [
                'json' => $params,
                'headers' => $headers,
            ]);
            
			$result=json_decode($response->getBody(), true);
			//return $result['messages'][0]['message_status'];  //will return 'accepted'
			return $result;

        } catch (\Exception $e) {
            Log::info('Sent service job failed: ' . $e->getMessage());
            \Log::info($e->getMessage());			
			return $e->getMessage();
		}

    }
	
	
	
	
	
	
	
	
	
	
	
	
}