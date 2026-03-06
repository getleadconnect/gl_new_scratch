<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SentServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $postData;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->postData = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
		try{
	
		$endpoint = "https://app.getlead.co.uk/api/gl-website-contacts";
		$client = new \GuzzleHttp\Client();

				$params=[
					"token"=>$this->postData['token'],
					"name"=>$this->postData['name'],
					"countrycode"=>$this->postData['country_code'],
					"mobileno"=>$this->postData['mobileno'],
					"email"=>$this->postData['email'],
					"feedback"=>null,
					"source"=>$this->postData['source'],
					"Referred By"=>null,
					"company_name"=>$this->postData['company_name'],
					"address"=>$this->postData['address']??null,
					"remarks"=>$this->postData['remarks']??null,
				];
					
		$response = $client->request('GET', $endpoint, ['query' => $params]);
		$statusCode = $response->getStatusCode();
		$content=json_decode($response->getBody()->getContents(), true);

		return $content;
		
		}
		catch(\Exception $e)
		{
			\Log::info("customer details send failed -> ".$e->getMessage());
		}
    }
	
}
