<?php

namespace App\Notification;

use App\Services\WhatsappGiftConfirmMsgService;

class WhatsappGiftConfirmSend
{

    protected $WhatsappGiftConfirmMsgService;

    public function __construct(WhatsappGiftConfirmMsgService $whatsappConfirmService)
    {
        $this->WhatsappGiftConfirmMsgService = $whatsappConfirmService;
    }

    /**
     * @param mixed $mobile_no
     * @param mixed $otp
     * @return mixed
     */
	 
    public function giftConfirmMessageSendToWhatsapp($data)
    {
        return $this->WhatsappGiftConfirmMsgService->sendGiftConfirmMessageToWhatsapp($data);
    }
}