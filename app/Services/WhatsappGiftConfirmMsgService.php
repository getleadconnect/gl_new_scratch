<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repositories\WhatsappRepository;

class WhatsappGiftConfirmMsgService
{

    protected $whatsappRepository;

    public function __construct(WhatsappRepository $whatsappRepository)
    {
        $this->whatsappRepository = $whatsappRepository;
    }

    public function sendGiftConfirmMessageToWhatsapp($data){
        return $this->whatsappRepository->sendWhatsappConfirmMessage($data);
    }
	
}


