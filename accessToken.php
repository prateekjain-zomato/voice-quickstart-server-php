<?php
/*
 * Creates an access token with VoiceGrant using your Twilio credentials.
 */
include('./vendor/autoload.php');
include('./config.php');
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

// Use identity and room from query string if provided
$identity = isset($_SERVER["HTTP_IDENTITY"]) ? $_SERVER["HTTP_IDENTITY"] : NULL;
$app_type = isset($_SERVER["HTTP_APP_TYPE"]) ? $_GET["HTTP_APP_TYPE"] : NULL;
if (!isset($identity) || empty($identity)) {
  $identity = isset($_POST["identity"]) ? $_POST["identity"] : "alice";
}
// Create access token, which we will serialize and send to the client
$token = new AccessToken($ACCOUNT_SID, 
                         $API_KEY, 
                         $API_KEY_SECRET, 
                         3600*24, 
                         $identity
);

// Grant access to Video
$pushCredentials = $app_type == 1 ? $APPLE_PUSH_CREDENTIAL_SID : $PUSH_CREDENTIAL_SID;
$grant = new VoiceGrant();
$grant->setOutgoingApplicationSid($APP_SID);
$grant->setIncomingAllow(true);
$grant->setPushCredentialSid($pushCredentials);
$token->addGrant($grant);

echo json_encode([
  "access_token" => $token->toJWT()
]);
