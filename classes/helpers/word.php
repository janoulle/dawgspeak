<?php
class Word{
	private $word;
	private $type;
	private $definition;

	/**
	 * Default constructor
	 * @param String $wd
	 * @param String $tp
	 * @param String $def
	 */
	function __construct($wd,$tp,$def){
		$this->word = $wd;
		$this->type = $tp;
		$this->definition = htmlentities($def);
	}

	/**
	 * Return the stored word
	 * @return String $word
	 */
	function getWord(){
		return $this->word;
	}

	function setWord($wd){
		$this->word = $wd;
	}

	/**
	 * Return the stored type of the word
	 * @return String $type type of the word
	 */
	function getType(){
		return $this->type;
	}

	/**
	 * Setter for the type
	 * @param String $tp
	 */
	function setType($tp){
		$this->type = $tp;
	}

	/**
	 * Return the stored definition of the word
	 * @return String $definition definition of the word
	 */
	function getDefinition(){
		return $this->definition;
	}

	/**
	 * Setter for the definition
	 * @param String $def
	 */
	function setDefinition($def){
		$this->definition = $def;
	}

	/**
	 * Return a JSON representation of the word object
	 * @return String $str JSON representation of the Word object
	 */
	function __toString(){
		$result = array();
		$result['word'] = $this->word;
		$result['type'] = $this->type;
		$result['definition'] = $this->definition;
		return json_encode($result);
	}
}
?>
