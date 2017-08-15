<?php
namespace App\Repositories;
use Mail;
use Exception;

class SendMailRepository extends BaseRepository {

    public function __construct() {

    }

    function sendMail($mailSendingDetails = array()) {

        if(isset($mailSendingDetails['to']) && isset($mailSendingDetails['view'])){
            
           try{
                $mailSendingDetails['data']['url'] = config('custom.siteUrl');
                $data = $mailSendingDetails['data'];
                Mail::send(['html' => $mailSendingDetails['view']],$data,function($message) use ($mailSendingDetails)
                {
                    $fromEmail = isset($mailSendingDetails['from'])?$mailSendingDetails['from']:config('mail.from')['address'];
                    $fromName = isset($mailSendingDetails['fromName'])?$mailSendingDetails['fromName']:config('mail.from')['name'];
                    $subject = isset($mailSendingDetails['subject'])?$mailSendingDetails['subject']:'Welcome to Atlas Search!';
                    $message->from($fromEmail, $fromName);
                    $message->to($mailSendingDetails['to']);

                    $message->subject($subject);

                    if(isset($mailSendingDetails['cc']))
                        $message->cc($mailSendingDetails['cc']);

                    if(isset($mailSendingDetails['bcc']))
                        $message->bcc($mailSendingDetails['bcc']);

                });
                return 1;
            } catch(Exception $e){
                return 0;
            }
            
        }else{
            return 0;
        }


    }
}
