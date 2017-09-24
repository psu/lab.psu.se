<?php

////////////////////////////////////////////////////////////////////////////////////////
// spotify-search-csv.php 
// v1.1

////////////////////////////////////////////////////////////////////////////////////////
// DEFINES
define(DEBUG,            0);
define(ENDPOINT_TOKEN,   'https://accounts.spotify.com/api/token');
define(ENDPOINT_SEARCH,  'https://api.spotify.com/v1/search');
define(AUTH_BASIC,       'Authorization: Basic ');
define(AUTH_BEARER,      'Authorization: Bearer ');
define(DATA_TOKEN,       'grant_type=client_credentials');

////////////////////////////////////////////////////////////////////////////////////////
// PARAMETERS
$get_q = isset($_GET['q']) ? (string)$_GET['q'] : '';
$get_c = isset($_GET['c']) ? (string)$_GET['c'] : '';
if ( $get_c == '' )
  exit('No credentials found in GET.<p>Use:<br>c=(spotify client id):(spotify client secret)<p>https://developer.spotify.com/my-applications');
if ( $get_q == '' )
  exit('No query found in GET.<p>Use:<br>q=track: (track name) artist:(artist name)');

////////////////////////////////////////////////////////////////////////////////////////
// INIT
$client = base64_encode($get_c);
$token = false;
//__debug($get_q, $client);

////////////////////////////////////////////////////////////////////////////////////////
// CALL SPOTIFY SEARCH
$data = psu_build_query(array(
	'query'   => urlencode( $get_q ),
	'offset'	=> '0',
	'limit'		=> '1',
	'type' 		=> 'track',
));
__debug('SEARCH DATA',$data);

$response = json_decode( psu_curl_rest_call(
  'GET', 
  ENDPOINT_SEARCH, 
  array( AUTH_BEARER . psu_spotify_get_token() ),
  $data
));
__debug('SEARCH RESPONSE',$response);

if ( $response->{'tracks'} ) {
 	$response_artist 	= $response->{'tracks'}->{'items'}[0]->{'artists'}[0]->{'name'};
 	$response_track 	= $response->{'tracks'}->{'items'}[0]->{'name'};
 	$response_uri		 	= $response->{'tracks'}->{'items'}[0]->{'uri'};
 	$response_url		 	= $response->{'tracks'}->{'items'}[0]->{'external_urls'}->{'spotify'};
  // Output result
	echo "\"$response_track\",\"$response_artist\",$response_url,$response_uri";
}

__debug('The end');
exit(0);


////////////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
// Populate and/or return global var $token
function psu_spotify_get_token() {
  global $client, $token;
  if (!$token) {
    $response = json_decode( psu_curl_rest_call('POST', ENDPOINT_TOKEN, array(AUTH_BASIC.$client), DATA_TOKEN) );
    __debug('GET TOKEN',$response);  
    if ( $response->{'token_type'} == 'Bearer' && $response->{'access_token'} != '' ) {
      $token = $response->{'access_token'};
    }    
  }
  return $token;
}

////////////////////////////////////////////////////////////////////////////////////////
// Known limitation: param $headers must be populated with empty array (or more)
//
// Based on code found here:
// http://stackoverflow.com/questions/9802788/call-a-rest-api-in-php
function psu_curl_rest_call($method, $url, $headers, $data=false) {
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
      if ($data) $url .= "?$data";
	}

  //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
  curl_setopt($curl, CURLOPT_HTTPHEADER,      $headers);
	curl_setopt($curl, CURLOPT_URL,             $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,  1);
	
	$result = curl_exec($curl);
	curl_close($curl);	
	return $result;
}

////////////////////////////////////////////////////////////////////////////////////////
// Build a query string (without the '?') from an array
function psu_build_query($query_array) {
	$result = '';
	foreach ($query_array AS $key => $value) {
		$result .= '&' . $key . '=' . $value;
	}	
	return substr($result, 1);
}

////////////////////////////////////////////////////////////////////////////////////////
// Simple debug function, needs constant DEBUG
function __debug(...$messages) {
  if (DEBUG) {
    $line_break = "<br>\n";
    $line_start = '-- ';
    $output = '';
    foreach ($messages as $m) {
      $m = (is_array($m)||is_object($m)) ? print_r($m, true) : $m;
      $m = ($m == '')   ? '(empty)'         : $m;
      $output .= $line_start . $m . $line_break;
    }
    echo $line_break . $output;
  }
}



?>