<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;

class EmailController extends Controller
{
    public static function sendEmail($emails =[], $subject = '', $body = '')
    {
        if (empty($emails)) {
            return;
        }
        \Mail::raw($body, function ($message) use ($subject, $emails) {
            if(isset($emails['student']) && !empty($emails['student'])) {
                $message->to($emails['student']);
            }
            if(isset($emails['parent']) && !empty($emails['parent'])) {
                $message->cc($emails['parent']);
            }
            $message->subject($subject);
        });
    }

}
