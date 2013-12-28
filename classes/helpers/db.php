<?php
require_once dirname(__FILE__) . '/../../../../creds/credentials.inc';
require_once dirname(__FILE__) . './word.php';
class DB{
	private $getdefinitions;
	private $gettypes;
	private $errorMessage;

	/**
	 * Default constructor
	 */
	function __construct(){
		try{
			$this->dbconn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
			$this->getdefinition = $this->dbconn->prepare("SELECT * from DawgSpeak where word = ?");
			$this->gettypes = $this->dbconn->prepare("SELECT * from DawgSpeak where type = ?");
		}catch(Exception $e){
			$this->errorMessage = $e->getMessage();
		}
	}

	/**
	 *
	 * @param String $tp type e.g. noun, adverb, etc
	 * @return array() of Word object
	 */
	function getTypes($tp){
		try{
			$words = array();
			if (!($this->gettypes)){
				$this->errorMessage = "Prepare failed: (" . $this->dbconn->errno . ") " . $this->dbconn->error;
			}else if (!($this->gettypes->bind_param("s",$tp))){
				$this->errorMessage = "Binding parameters failed: (" . $this->gettypes->errno . ") " . $this->gettypes->error;
			}else if (!($this->gettypes->execute())){
				$this->errorMessage = "Execute failed: (" . $this->gettypes->errno . ") " . $this->gettypes->error;
			}else if (!(($stored = $this->gettypes->store_result())) && $this->dbconn->errno){
				//switched from using fetch() to store_result() because of mysql error 2014 about commands being out of sync
				//storeresult buffers the fetched data
				$this->errorMessage = "Fetch failed (DB): (" . $this->dbconn->errno . ") " . $this->dbconn->error;
				$this->errorMessage .= "Fetch failed (STMT): (" . $this->gettypes->errno . ") " . $this->gettypes->error;
			}else if (!($this->gettypes->bind_result($word,$type,$definition))){
				$this->errorMessage = "Binding results failed: (" . $this->gettypes->errno . ") " . $this->gettypes->error;
			}else{
				if ($stored){
					while($this->gettypes->fetch()){
						$temp = new Word($word,$type,$definition);
						$words[] = $temp;
					}
				}else{
					$this->errorMessage = "Error storing results of getShortName.";
				}
			}
			$this->gettypes->free_result();
			return $words;
		}catch(Exception $e){
			$this->errorMessage = $e->getMessage();
		}
		return null;
	}

	/**
	 *
	 * @param String $tp type e.g. noun, adverb, etc
	 * @return array() of Word object
	 */
	function getDefinitions($wd){
		try{
			$words = array();
			if (!($this->getdefinition)){
				$this->errorMessage = "Prepare failed: (" . $this->dbconn->errno . ") " . $this->dbconn->error;
			}else if (!($this->getdefinition->bind_param("s",$wd))){
				$this->errorMessage = "Binding parameters failed: (" . $this->getdefinition->errno . ") " . $this->getdefinition->error;
			}else if (!($this->getdefinition->execute())){
				$this->errorMessage = "Execute failed: (" . $this->getdefinition->errno . ") " . $this->getdefinition->error;
			}else if (!(($stored = $this->getdefinition->store_result())) && $this->dbconn->errno){
				//switched from using fetch() to store_result() because of mysql error 2014 about commands being out of sync
				//storeresult buffers the fetched data
				$this->errorMessage = "Fetch failed (DB): (" . $this->dbconn->errno . ") " . $this->dbconn->error;
				$this->errorMessage .= "Fetch failed (STMT): (" . $this->getdefinition->errno . ") " . $this->getdefinition->error;
			}else if (!($this->getdefinition->bind_result($word,$type,$definition))){
				$this->errorMessage = "Binding results failed: (" . $this->getdefinition->errno . ") " . $this->getdefinition->error;
			}else{
				if ($stored){
					while($this->getdefinition->fetch()){
						$temp = new Word($word,$type,$definition);
						$words[] = $temp;
					}
				}else{
					$this->errorMessage = "Error storing results of getShortName.";
				}
			}
			$this->getdefinition->free_result();
			return $words;
		}catch(Exception $e){
			$this->errorMessage = $e->getMessage();
		}
		return null;
	}

	function getErrorMessage(){
		return $this->errorMessage;
	}

	function setErrorMessage($msg){
		$this->errorMessage = $msg;
	}
}
?>
