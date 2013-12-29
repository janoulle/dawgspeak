<?php
require_once dirname(__FILE__) . '/../../../creds/dhpath.inc';
require_once dirname(__FILE__) . '/../../../creds/twitter_dawgspeak.inc';
$tokenFile = false;
$result = array();
/**
 * http://php.net/manual/en/function.stream-context-create.php
 * http://stackoverflow.com/questions/5647461/how-do-i-send-a-post-request-with-php
 * Get a new bearer token from Twitter
 * 
 * @return JSON response
 */
function requestBearerToken(){
	$url = "https://api.twitter.com/oauth2/token";
	$keys = urlencode(CONSUMER_KEY) . ":" . urlencode(CONSUMER_SECRET);
	$encoded = "Basic " . base64_encode($keys);
	$body = "grant_type=client_credentials";
	
	//"Content-type", "application/x-www-form-urlencoded;charset=UTF-8");
	
	$opts = array(
	  'http'=>array(
	    'method'=>"POST",
	    'header'=>"Content-type: application/x-www-form-urlencoded;charset=UTF-8\r\n" .
	              "Authorization: " . $encoded . "\r\n",
		'content'=> $body
	  )
	);
	
	$context = stream_context_create($opts);
	$fp = fopen($url, 'r', false, $context);
	$response = stream_get_contents($fp);
	return $response;
}

function saveBearerToken($response){
	$file =	DAWGSPEAK_BEARER_TOKEN_FILE;
	$result = @file_put_contents($file, $response);
	return $result;
}

/**
 * Gets fresh data
 */
function getToken(){
	$res = requestBearerToken();
	saveBearerToken($res);
	getTokenFile();
}

function getTokenFile(){
	global $tokenFile;
	$tokenFile = @file_get_contents(DAWGSPEAK_BEARER_TOKEN_FILE);
	if ($tokenFile === false){
		error_log("Error reading the bearer_token.json file from disk.", 1,"janeullah@gmail.com");
	}else{
		//check bearer type
		$jsonified = json_decode($tokenFile);
		if (strcmp($jsonified->token_type, "bearer") != 0){
			error_log("Token type received is NOT of bearer type.", 1,"janeullah@gmail.com");	
		}else{
			return $tokenFile;
		}
	}
}


function getTokenType(){
	global $tokenFile;
	$tokenFile = @file_get_contents(DAWGSPEAK_BEARER_TOKEN_FILE);
	$jsonified = json_decode($tokenFile);
	return $jsonified->token_type;
}

function getTokenCode(){
	global $tokenFile;
	$tokenFile = @file_get_contents(DAWGSPEAK_BEARER_TOKEN_FILE);
	$jsonified = json_decode($tokenFile);
	return $jsonified->access_token;
}

function testToken(){
	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
	$params = http_build_query(array('count' => 10, 'screen_name' => 'janetalkstech'));
	
	$header = 'Authorization: Bearer ' . getTokenCode() . '';
	$built_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?count=10&screen_name=janetalkstech";
	
	$ch = curl_init();	
	//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_HTTPHEADER,array($header));
	curl_setopt($ch, CURLOPT_URL,$built_url);
	$content = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($httpCode == 200){
		echo "Success!";
		print_r($content); 
	}else{
		print curl_error($ch);
	}
	print_r($httpCode);
	print_r($content);
}

//https://api.twitter.com/1.1/search/tweets.json?q=%23freebandnames
function getTweets($word, $tok){
	$data = array();
	$header = 'Authorization: Bearer ' . $tok . '';
	$built_url = "https://api.twitter.com/1.1/search/tweets.json?q=" . $word . "&result_type=popular&count=4";
	
	$ch = curl_init();	
	//http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_HTTPHEADER,array($header));
	curl_setopt($ch, CURLOPT_URL,$built_url);
	$content = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($httpCode == 200){
		$data['errorMessage'] = "";
		$data['content'] =  $content; 
	}else{
		$data['errorMessage'] = curl_error($ch);
	}
	return $data;
}

$requestType = $_SERVER['REQUEST_METHOD'];
if ($requestType === 'GET') {
	$action = $_GET['action'];
	if ($action && strcmp($action,"getTweets") == 0){
		$tok = getTokenFile();
		$word = $_GET['word'];
		if ($tok !== false){
			if ($word){
				$jsonified = json_decode($tok);
				$data = getTweets($word,$jsonified->access_token);
				if (strlen($data['errorMessage']) == 0){
					echo $data['content'];
					//echo json_encode($data['content']);
				}else{
					$result['errorMessage'] = "Error retrieving tweet examples";
					echo json_encode($result);
				}
			}else{
				$result['errorMessage'] = "Missing parameter";
				echo json_encode($result);
			}
		}else{
			$result['errorMessage'] = "Problem obtaining bearer token";
			echo json_encode($result);
		}
	}else{
		$result['errorMessage'] = "Invalid GET action requested.";
		echo json_encode($result);
	}
}else{
	$result['errorMessage'] = "Invalid request type";
	echo json_encode($result);
}

?>