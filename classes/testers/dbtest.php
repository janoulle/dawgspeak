<?php
require_once("../helpers/db.php");
require_once("../helpers/word.php");

$db = new DB();
$words = $db->getDefinitions("cunt");
print_r($words);
$words = $db->getDefinitions("12");
print_r($words);
var_dump($words);
foreach($words as $word){
	echo $word ."\n";
}
?>
