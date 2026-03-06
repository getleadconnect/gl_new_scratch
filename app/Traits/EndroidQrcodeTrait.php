<?php

namespace App\Traits;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

use Session;
use Log;

trait EndroidQrcodeTrait
{
    
	public function generateQrCode($link,$path)   //to generate qrcode for web link
    {
		try
		{
	 
		$qrCode = new QrCode(
				data:$link,
				encoding: new Encoding('UTF-8'),
				errorCorrectionLevel: ErrorCorrectionLevel::High,
				size: 300,
				margin: 10,
				roundBlockSizeMode: RoundBlockSizeMode::Margin,
				foregroundColor: new Color(0, 0, 0),
				backgroundColor: new Color(255, 255, 255)
			);
	 
		 $writer = new PngWriter();
		 $result = $writer->write($qrCode);
		 header('Content-Type: '.$result->getMimeType());
		 $res=$result->saveToFile($path);
		 return $res;
		}
		catch(\Exception $e)
		{
			\Log::info($e->getMessage());
			return false;
		}
  }


}
