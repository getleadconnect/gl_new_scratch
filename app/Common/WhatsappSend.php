<?php

namespace App\Common;

use App\Services\WhatsappService;

class WhatsappSend
{

    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * @param mixed $mobile_no
     * @param mixed $otp
     * @return mixed
     */
	 
    public function sendWhatsappOtp($data)
    {
        return $this->whatsappService->sendOtp($data);
    }
}