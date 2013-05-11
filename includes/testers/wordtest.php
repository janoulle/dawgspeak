<?php
require_once("../helpers/word.php");

$wd = new Word("hey","noun","Hey ye <&am\"p;  ^5 litt'sle lady");
echo $wd->getWord() . "-" . $wd->getType() . "-" . $wd->getDefinition() . "\n";
var_dump($wd);
echo $wd;
?>
