<?php
include('./vendor/autoload.php');
include('./config.php');
$file = fopen("dialStatusLogs.txt","a");

fwrite($file,json_encode($_POST));
fwrite($file,"\n");
fclose($file);
use Twilio\TwiML\VoiceResponse;
$response = new VoiceResponse();
$callStatus = isset($_POST["CallStatus"]) ? $_POST["CallStatus"] : "";
$dialStatus = isset($_POST["DialCallStatus"]) ? $_POST["DialCallStatus"] : "";
if ($callStatus != "completed") {
    if ($dialStatus == "no-answer") {
        $response->say("The person you are trying to reach is unavailable to take calls. Please try again later");
    } elseif ($dialStatus == "busy") {
        $response->say("The person you are trying to reach is busy. Please try again later");
    }
}
print $response;
