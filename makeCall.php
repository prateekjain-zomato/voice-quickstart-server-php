<?php
/*
 * Creates an endpoint that can be used in your TwiML App as the Voice Request Url.
 *
 * In order to make an outgoing call using Twilio Voice SDK, you need to provide a
 * TwiML App SID in the Access Token. You can run your server, make it publicly
 * accessible and use `/makeCall` endpoint as the Voice Request Url in your TwiML App.
 */
include('./vendor/autoload.php');
include('./config.php');

use Twilio\TwiML\VoiceResponse;
$callerId = 'client:quick_start';
$to = isset($_POST["To"]) ? $_POST["To"] : "";
$from = isset($_POST["From"]) ? $_POST["From"] : "";
// $callerId
if (!empty($from)) {
  $callerId = sprintf('client:%s', $from);
}
$file = fopen("makeCallLogs.txt","a");
fwrite($file,json_encode($_POST));
fwrite($file,"\n");
fclose($file);
$response = new VoiceResponse;
$customParams = isset($_POST["customParams"]) ? $_POST["customParams"] : "";
if (!isset($to) || empty($to)) {
  $response->say('Please dial to a valid client.');
} else {
  $dial = $response->dial(
    '',
    array(
       'callerId' => $callerId,
    ));
    $client = $dial
      ->setAnswerOnBridge(true) // for call synchronization of events between caller and receiver
      ->setAction('https://'.$_SERVER['HTTP_HOST'].'/dialStatus.php')
      ->setMethod('POST')
      ->client($to, [
          'statusCallbackEvent' => 'initiated ringing answered completed',
          'statusCallback' => 'https://'.$_SERVER['HTTP_HOST'].'/CallStatus.php',
          'statusCallbackMethod' => 'POST'
      ])
      ->parameter(['name' => 'customParams', 'value' => $customParams]);
      /*
        For POC only, we are not doing any validation on customeParams, it makes more sense to receive stringified json from the caller
        and extract all the required params, do sanitizations and validations and then communicate only the needed params 
        separately to the receiver.
      */
}
print $response;