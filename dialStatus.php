<?php
include('./vendor/autoload.php');
include('./config.php');
$file = fopen("dialStatusLogs.txt","a");

fwrite($file,json_encode($_POST));
fwrite($file,"\n");
fclose($file);
use Twilio\TwiML\VoiceResponse;
$response = new VoiceResponse();
$response->say("Dial status logged ");
print $response;
