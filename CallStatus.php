<?php
include('./vendor/autoload.php');
include('./config.php');

use Twilio\TwiML\VoiceResponse;
$callerId = 'client:quick_start';
$to = isset($_POST["To"]) ? $_POST["To"] : "";
if (empty($to)) {
  $to = isset($_POST["to"]) ? $_POST["to"] : "";
}
$from = isset($_POST["From"]) ? $_POST["From"] : "";
// $callerId
if (!empty($from)) {
  $callerId = sprintf('client:%s', $from);
}
$file = fopen("callStatusLogs.txt","a");
fwrite($file,json_encode($_POST));
fwrite($file,"\n");
fclose($file);
