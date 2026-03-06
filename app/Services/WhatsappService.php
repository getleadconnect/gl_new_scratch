<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repositories\WhatsappRepository;

class WhatsappService
{
    protected $whatsappRepository;

    public function __construct(WhatsappRepository $whatsappRepository)
    {
        $this->whatsappRepository = $whatsappRepository;
    }

    public function sendOtp($data){
        return $this->whatsappRepository->sendWhatasappOtp($data);
    }
}