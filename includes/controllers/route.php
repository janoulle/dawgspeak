<?php
require_once("../helpers/db.php");
require_once("../helpers/word.php");
require_once("../phpflickr/phpFlickr.php");

/**
 * Function to undo effects of magic quotes
 * Returns the $_POST value matching the provided key
 * @param String $var key in $_POST variable
 * @return String $val value matching $_POST['key']
 */
function get_post_var($var){
	$val = filter_var($_POST[$var],FILTER_SANITIZE_MAGIC_QUOTES);
	return $val;
}


/**
 *
 * Processing POST requests
 *
 */
function doPost(){
	$word = get_post_var("word");
	$value = get_post_var("gword");
	if ($word && strlen($word) > 0){
		$db = new DB();
		$words = $db->getDefinitions($word);
		$result = "[";
		foreach($words as $word){
			$result .= $word . ",";
		}
		if (strlen($result) > 1){
			$result = substr($result,0,strlen($result)-1);
		}
		$result .= "]";
		return $result;
	}else if ($value && strlen($value) > 0){
		//Flick image
		$flickr = new phpFlickr("093a6143a8a706e62511db8b29c7b3f6");
		$args = array("tags"=>urlencode($value), "privacy_filter"=> 1, "per_page" => 1, "page"=> 1);
		$tag = $flickr->photos_search($args);
		$embURL = null;
		$img = null;
		foreach ($tag['photo'] as $photo) {
			$embURL = "http://farm".$photo['farm'].".staticflickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_m.jpg";
			$img = "<div class=\"screenshot\"><a href=\"".$embURL."\"><img src=\"".$embURL."\" title=\"".$photo['title']."\" alt=\"".$photo['title']."\" /></a></div>";
		}
        return $img;
	}else{
		return "[]";
	}
}

/**
 * processing GET requests
 *
 */
function doGet(){
	return "[]";
}

$requestType = $_SERVER['REQUEST_METHOD'];
if ($requestType === 'POST') {
	echo doPost();
}else if ($requestType === 'GET'){
	echo doGet();
}else{
	echo "Unknown request.";
}
?>
