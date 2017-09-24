<?php

// spotify-search-csv.php 
// v1.1

if ( !is_set($_GET['c']) ) echo 'No credentials'; exit;

define(DEBUG, 0);
define(CLIENT, base64_encode($_GET['c']))
define(SEARCH_URL, 'https://api.spotify.com/v1/search');
define(TOKEN_URL,  'https://accounts.spotify.com/api/token');
$token = false;

if (DEBUG) echo $_GET['q'] ."<br>\n";

$spotify_rest_data = array(
	'query' 	=> urlencode( $_GET['q'] ),
	'offset'	=> '0',
	'limit'		=> '1',
	'type' 		=> 'track',
);

$response = json_decode( call_api('GET', SEARCH_URL, $spotify_rest_data) );

if ( $response->{'tracks'} ) {
	if (DEBUG) echo $response->{'tracks'}->{'href'} ."<br>\n";

 	$response_artist 	= $response->{'tracks'}->{'items'}[0]->{'artists'}[0]->{'name'};
 	$response_track 	= $response->{'tracks'}->{'items'}[0]->{'name'};
 	$response_uri		 	= $response->{'tracks'}->{'items'}[0]->{'uri'};
 	$response_url		 	= $response->{'tracks'}->{'items'}[0]->{'external_urls'}->{'spotify'};

	echo "\"$response_track\",\"$response_artist\",$response_url,$response_uri";

}




////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////////////
// http://stackoverflow.com/questions/9802788/call-a-rest-api-in-php
function call_api($method, $url, $headers=array(), $data=false) {
	$curl = curl_init();
	
	switch ($method) {
	  case "POST":
	    curl_setopt($curl, CURLOPT_POST, 1);
	    if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    break;
	  case "PUT":
      curl_setopt($curl, CURLOPT_PUT, 1);
      break;
	  default:
      if ($data) $url = sprintf("%s?%s", $url, psu_build_query($data));
	}
	
	if (DEBUG) echo "$url<br>\n";
	
	// Optional Authentication:
	//    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	//    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
	
  curl_setopt($curl, CURLOPT_HTTPHEADER,      $headers)
	curl_setopt($curl, CURLOPT_URL,             $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,  1);
	
	$result = curl_exec($curl);
	curl_close($curl);	
	return $result;
}



function psu_get_spotify_token() {
  global $token;
  
  if (!$token) {
    $header = array('Authorization: Basic '.CLIENT));
    $response = json_decode( call_api('POST', TOKEN_URL, $header );
  	if (DEBUG) echo "$response<br>\n";
    if ( $response->{'token_type'} == 'bearer' && $response->{'access_token'} != '' ) $token = $response->{'access_token'}
  }
  return $token;

}

////////////////////////////////////////////////////////////////////////////////////////
// ladskjf
function psu_build_query($a) {
	$result = '';
	foreach ($a AS $key => $value) {
		$result .= '&' . $key . '=' . $value;
	}	
	return substr($result, 1);
}

?>