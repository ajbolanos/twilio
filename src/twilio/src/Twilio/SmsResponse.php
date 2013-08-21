<?php
namespace Twilio;

class SmsResponse{
    protected $maxLength = 140;
    protected $message = array();

    function __construct()
    {

    }

    function getMessages()
    {
        return $this->message;
    }

    /**
     * Set a message per sms.
     * Allows for multiple sms messages to be sent.
     *
     * @param $message
     */
    function setMessage($message)
    {
        // Validate character limit and split into multiple messages.
        $messages = $this->checkCharLimit($message);
        if(!empty($messages))
        {
            foreach($messages as $m)
            {
                $this->message[] = $m;
            }
        }
    }

    /**
     * Will split messages into multiple messages if
     * the message goes beyond the message max length.
     *
     * @param $message
     * @return array
     */
    function checkCharLimit($message)
    {
        return array($message);
    }


    function send()
    {
        $messages = $this->message;
        if(!empty($messages)){
            header("content-type: text/xml");

            $sms = '';
            foreach($messages as $m)
            {
                $sms .= "<Sms>{$m}</Sms>";
            }

            return '<?xml version="1.0" encoding="UTF-8"?><Response>'.$sms.'</Response>';
        }
        return FALSE;
    }
}