<?php

/**
 * Example twilio response script.
 *
 *
 * Data sent by twilio
 * @see: http://www.twilio.com/docs/api/rest/sms
array (
'AccountSid' => '',
'Body' => 'lc1',
'ToZip' => '94949',
'FromState' => 'CA',
'ToCity' => 'NOVATO',
'SmsSid' => '',
'ToState' => 'CA',
'To' => '4155551212',
'ToCountry' => 'US',
'FromCountry' => 'US',
'SmsMessageSid' => 'SM52ac363647f6a94ad51a233578cdad3d',
'ApiVersion' => '2008-08-01',
'FromCity' => 'SAN JOSE',
'SmsStatus' => 'received',
'From' => '4085551212',
'FromZip' => '95113',
)
 */


require_once '../src/autoload.php';

use Twilio\SmsResponse;

if(($_REQUEST['AccountSid'] != TWILIO_SID) || empty($_REQUEST['From']))
{
    // Log error Return 400 Bad Request error
    header("HTTP/1.0 400 Bad Request");
}
else{
    // Capture SMS $_REQUEST information for logging in DB
    $sms_in = array(
        'sid'  => $_REQUEST['SmsSid'],   // Twilio SMS ID
        'from' => $_REQUEST['From'], //The phone number that initiated the message
        'to'   => $_REQUEST['To'], //The phone number that received the message
        'body' => $_REQUEST['Body'], //The text body of the SMS message. Up to 160 characters long.
    );

    switch(strtoupper($sms_in['body'])){
        case 'END':
        case 'STOP':
        case 'QUIT':

            /**
             * Logic for the un-subscribe can be handled in different ways.
             *
             * For this example we will pretend the logic is already there.
             * If the number is found in DB and succesfully removed the function will return TRUE
             *
             * If an error occurs it will return an array with in array('error'=>'error_type').
             * ie.
             * array('error' => 'not_found');
             * array('error' => 'db_error');
             *
             */

            // $unsubscribed = Twilio::unsubscribe_phone_number($sms_in['from']);
            $unsubscribed = TRUE; // Example of function returning true (phone number found in DB.)

            // Unsubscribed Successfully
            if($unsubscribed === TRUE)
            {
                $response = 'You have been unsubscribed. You will receive no more messages.';
            }
            // An error occured while unsubscibing
            else
            {
                // If the error key value pair isn't passed will give an unknown_error
                $error_type = isset($unsubscribed['error']) ? $unsubscribed['error'] : 'unknown_error';

                switch($error_type){
                    case 'not_found':
                        $response = 'Your phone number was not found.';
                        break;

                    case 'unknown_error':
                    case 'db_error':
                        // Log error could add logic to re-attempt to unsubscribe the phone number and contact admin

                        // Will pass a genaric error
                        $response = 'An error occured and an admin has been contacted. Your phone number will be removed shortly.';
                        break;
                }
            }

            break;

        case 'HELP':
            $response = 'This is an example help message. Reply STOP to unsubscribe.';
            break;

        default:

            if(empty($sms_in['body'])){
                $response = 'Your message was empty. Please try again.';
            }
            // Logic would be needed here to create a message.
            // ie. Get message from DB
            else{
                $response = 'Example response message';
            }
    }

    // Create xml response
    $TwilioResponse = new SmsResponse();
    $TwilioResponse->setMessage($response);
    // Returns xml response
    echo $TwilioResponse->send();
}
