<?php
require_once '../helpers/db.php';
require_once '../helpers/word.php';
require_once '../../includes/wordnik/Swagger.php';
require_once '../../../../creds/wordnik.inc';
$client = new APIClient(WORDNIK_API, 'http://api.wordnik.com/v4');
$result = array();
/**
 * Function to undo effects of magic quotes
 * Returns the $_POST value matching the provided key
 * @param String $var key in $_POST variable
 * @return String $val value matching $_POST['key']
 */
function getPost($var){
	$val = filter_var($_POST[$var],FILTER_SANITIZE_MAGIC_QUOTES);
	return $val;
}


/**
 * https://github.com/wordnik/wordnik-php
 */
function getTopWord($word){
	global $client, $result;
	$wordApi = new WordApi($client);
	$example = $wordApi->getTopExample($word);
	$result['errorMessage'] = "";
	$result['word'] = $example->text;
	return json_encode($result);
}


$requestType = $_SERVER['REQUEST_METHOD'];
if ($requestType === 'POST') {
	$action = getPost("action");
	if ($action){
		if (strcmp($action,"useWordnik") == 0){
			$word = getPost("word");
			if ($word){
				echo getTopWord($word);
			}else{
				$result['errorMessage'] = "Invalid word.";
				echo json_encode($result);
			}
		}else{
			$result['errorMessage'] = "Invalid POST request.";
			echo json_encode($result);
		}		
	}else{
		$result['errorMessage'] = "Invalid POST action requested.";
		echo json_encode($result);
	}
}else{
	$result['errorMessage'] = "Unknown request.";
	echo json_encode($result);
}
?>
