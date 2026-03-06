<?php

namespace App\Common;

use App\Jobs\EmailNotificationJobs;
use App\BackendModel\SendEmailHistory;

use Carbon\Carbon;


class SendEmail
{

    public function sendEmail($from, $to, $subject, $name, $content, $logo, $attachment, $userid)
    {

        $data['from'] = $from;
        $data['to'] = $to;
        $data['subject'] = $subject;
        $data['name'] = $name;
        $data['content'] = $content;
        $data['logo'] = $logo;
        $data['attachment'] = $attachment;
        $data['userid'] = $userid;

        $emailQueue = new SendEmailHistory();
        $emailQueue->fk_int_user_id = $userid;
        $emailQueue->vchr_email = $to;
        $emailQueue->vchr_subject = $subject;
        $emailQueue->int_status = SendEmailHistory::QUEUED;;
        $flag = $emailQueue->save();
        $data['queueid'] = $emailQueue->pk_int_email_history_id;
        $job = (new EmailNotificationJobs($data))->onQueue('email')->delay(Carbon::now()->addSeconds(2));
        dispatch($job);

    }
}
