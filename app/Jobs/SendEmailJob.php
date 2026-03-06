<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Mail\Mailer;
use Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $postdata;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->postdata = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $data=$this->postdata;
        
        if(isset($data['file_name'])){
            $file = $data['file_name'];
            Mail::to($data['email'])->send(new $file($data));
        }else{
            Mail::send("emails.".$data['template'], $data, function($message) use ($data)
                {
                $message
                    ->from('info@getlead.co.uk',config('app.name'))
                    ->to($data['email'],$data['name'])
                    ->subject($data['subject']);
                }); 
        }
    }
}
