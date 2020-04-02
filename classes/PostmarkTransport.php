<?php

namespace SchultenMedia\Postmark\Classes;

use Log;
use ApplicationException;
use Swift_Transport;
use Swift_Mime_SimpleMessage;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;
use Swift_Mime_ContentEncoder_Base64ContentEncoder;
use Swift_Events_EventListener;
use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;


class PostmarkTransport implements Swift_Transport {


    public function __construct() {

    }

    /**
     * Stub since Postmark API is stateless
     */
    public function isStarted() {
        return true;
    }


    /**
     * Stub since Postmark API is stateless
     */
    public function start() {
        return true;
    }

    /**
     * Stub since Postmark API is stateless
     */
    public function stop() {
        return true;
    }

    /**
     * Stub since Postmark API is stateless
     */
    public function ping() {
        return true;
    }

    /**
     * Not implemented
     */
    public function registerPlugin(Swift_Events_EventListener $plugin) {}

    /**
     * Send an email
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null) {
        try {

            $server_token = \System\Models\MailSetting::get('postmark_server_token');
            $sender_signature = \System\Models\MailSetting::get('postmark_sender_signature');

            // Send an email:
            $client = new \Postmark\Transport($server_token);
            $mailer = new Swift_Mailer($client);
            $message->setFrom($sender_signature);
            $mailer->send($message); // Exception is throw when response !== 200

        } catch(PostmarkException $ex){
            // If client is able to communicate with the API in a timely fashion,
            // but the message data is invalid, or there's a server error,
            // a PostmarkException can be thrown.
            Log::alert($ex);
            throw new PostmarkException('Failed to send email. Check event log for more info. Message: '.json_decode($ex->message, true)['error']['message']);

        } catch(Exception $generalException){
            // A general exception is thrown if the API
            // was unreachable or times out.
            Log::alert($generalException);
        }

    }
}
