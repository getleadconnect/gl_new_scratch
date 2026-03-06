<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait CrmApiService
{
	
  public function sendCustomerDetailsToCrm($data) 
  {	
	try
	{
		$endpoint = "https://app.getlead.co.uk/api/gl-website-contacts";
		$client = new \GuzzleHttp\Client();

				$params=[
					"token"=>$data['token'],
					"name"=>$data['name'],
					"countrycode"=>$data['country_code'],
					"mobileno"=>$data['mobileno'],
					"email"=>$data['email'],
					"feedback"=>null,
					"source"=>$data['source'],
					"Referred By"=>null,
					"company_name"=>$data['company_name'],
					"address"=>$data['address']??null,
					"remarks"=>$data['remarks']??null,
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
