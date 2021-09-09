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
if (!isset($to) || empty($to)) {
  $response->say('Please dial to a valid client.');
} else {
  $dial = $response->dial(
    '',
    array(
       'callerId' => $callerId
    ));
  $dial
  ->setAnswerOnBridge(true)
  ->setAction("https://169b-122-161-69-248.ngrok.io/dialStatus.php")
  ->setMethod('POST')
  ->client($to);
}
print $response;