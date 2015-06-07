/*
This is a sample PHP Script for logging into a WSO2 Identity Server Protected Resource. 
It retrieves the SAML Assertions from the WSO2 Identity Server and then uses the SAML Response 
to make HTTP GET/POST requests.
*/


<?php

unlink("/tmp/cookies.txt");
$USERNAME = "user@domain.com";
$PASSWORD = "password";
$RESOURCE_URL = "https://app.domain.com/home.jsp";
$WSO2_IS_URL = "https://is.domain.com/commonauth";
$ASSERTION_CONSUMER_URL = "https://app.domain.com/home.jsp";
$ASSERTION_CONSUMER_URL = "https://app.domain.com/home.jsp";



$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $RESOURCE_URL); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($curl, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

$result = curl_exec ($curl);
curl_close ($curl);



$sessionDataKey = strstr($result, "sessionDataKey\" value='");
$sessionDataKey = str_replace("sessionDataKey\" value='", "", $sessionDataKey);
$sessionDataKey = substr($sessionDataKey, 0, strpos($sessionDataKey, "'/>"));
$sessionDataKey = urlencode($sessionDataKey);


$RelayState = strstr($result, "RelayState=");
$RelayState = str_replace("RelayState=", "", $RelayState);
$RelayState = substr($RelayState, 0, strpos($RelayState, "\">"));
$RelayState = urlencode($RelayState);




$POSTVARS = "username={$USERNAME}&password=$PASSWORD&sessionDataKey=$sessionDataKey";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $WSO2_IS_URL); // Retrieve the SAML Token from the WSO2 Identity Server
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS    ,$POSTVARS);
curl_setopt($curl, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($curl, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

$result = curl_exec ($curl);
curl_close ($curl);

$SAMLResponse = strstr($result, "'SAMLResponse' value='");
$SAMLResponse = str_replace("'SAMLResponse' value='", "", $SAMLResponse);
$SAMLResponse = substr($SAMLResponse, 0, strpos($SAMLResponse, "'>"));
$SAMLResponse = urlencode($SAMLResponse );



$POSTVARS = "SAMLResponse=$SAMLResponse&RelayState=$RelayState";


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $ASSERTION_CONSUMER_URL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $POSTVARS);
curl_setopt($curl, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($curl, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');



$result = curl_exec ($curl);



curl_close ($curl);

?>
