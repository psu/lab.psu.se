<?php

// nifty-spotify-search-script v1

$spotify_rest_url = 'https://api.spotify.com/v1/search';
$spotify_rest_data = array(
	'query' 	=> urlencode( $_GET['q'] ),
	'offset'	=> '0',
	'limit'		=> '1',
	'type' 		=> 'track',
);

$response = json_decode( call_api('GET', $spotify_rest_url, $spotify_rest_data) );

if ( $response->{'tracks'}->{'total'} != 0 ) {

 	$response_track 	= $response->{'tracks'}->{'items'}[0]->{'name'};
 	$response_artist 	= $response->{'tracks'}->{'items'}[0]->{'artists'}[0]->{'name'};
// 	$response_url		 	= $response->{'tracks'}->{'items'}[0]->{'external_urls'}->{'spotify'};
 	$response_uri		 	= $response->{'tracks'}->{'items'}[0]->{'uri'};

	echo "\"$response_track\",\"$response_artist\",$response_uri";

} elseif ( is_object($response) ) {
	echo '(no result)';
} else {
	echo '<h1>nifty-spotify-search-script v1</h1><p>&copy; 2016 Pontus Sund&eacute;n</p><p><a href="http://www.psu.se/post/136954179915/how-to-convert-a-playlist-to-spotify">Read the tutorial on my blog</a></p>';
}


////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////
// http://stackoverflow.com/questions/9802788/call-a-rest-api-in-php
function call_api($method, $url, $data = false) {

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
	
	// Optional Authentication:
	//    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	//    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
	
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($curl);
	
	curl_close($curl);
	
	return $result;
}

////////////////////////////////////////////////////////////////////////////////////////
// 
function psu_build_query($a) {
	$result = '';
	foreach ($a AS $key => $value) {
		$result .= '&' . $key . '=' . $value;
	}	
	return substr($result, 1);
}

?>