<?php 
/**
 * Indexer result
 * @package Indexer
 * @author Oliver Kührig
 */
class IndexerResult {
	var $id;
	var $keys;
	var $score;
	var $text;
	
	function IndexerResult() {
		$this->id = 0;
		$this->keys = array();
		$this->score = 0;
		$this->text = "";
	}
}

/**
 * Indexer generates indexfiles
 * @package Indexer
 * @author Oliver Kührig
 * @version 1.0, 2004-10-07
 */
class Indexer {
	
    /**
     * id of the input object
     * @var integer
     */
	var $id = 0;
	
    /**
     * array container for the type and values
     * @var array
     */
	var $arrFields = array();
	
    /**
     * array container for the parameters
     * @var array
     */
	var $arrParams = array();
	
    /**
     * array container for the relevant words
     * @var array
     */
	var $arrWords = array();
	
    /**
     * array container for the index
     * @var array
     */
	var $arrIndex = array();
	
    /**
     * array container for the blacklist words
     * @var array
     */
	var $arrBlackList = array();
	
    /**
     * lock file
     * @var string
     */
	var $lockFile = "idx.lock";
	
    /**
     * log file
     * @var string
     */
	var $logFile = "idx.log";
	
    /**
     * path to the indexer
     * @var string
     */
	var $pathToIndexer = "./";
	
    /**
     * path to the object files
     * @var string
     */
	var $pathObjectFiles = "./object/";
	
    /**
     * path to the index files
     * @var string
     */
	var $pathIndexFiles = "./index/";
	
    /**
     * searchword
     * @var string
     */
	var $searchword = "";
	
    /**
     * count searchwords
     * @var integer
     */
	var $countSearchwords = 0;
	
    /**
     * returns the results beginning with offset
     * @var integer
     */
	var $searchOffset = 0;
	
    /**
     * number of objects to return
     * @var integer
     */
	var $searchRowCount = 0;
	
    /**
     * minimum percent value to add to the key words
     * @var integer
     */
	var $textSimilarityInPercent = 100;
	
    /**
     * text length to return in the resulting search array
     * @var integer
     */
	var $textLength = 120;
	
    /**
     * negative offset in the text  to return in the resulting search array
     * @var integer
     */
	var $textOffset = 30;
	
    /**
     * delimiter for the returning text at the beginning and the end
     * @var string
     */
	var $textDelimiter = "...";
	
    /**
     * negative offset in the text  to return in the resulting search array
     * @var string
     */
	var $textHighlight = '<span style="border-bottom: 1px dotted;"><strong>\1</strong></span>';
	
    /**
     * array container for the matching index lines
     * @var array
     */
	var $arrSearchIds = array();
	
    /**
     * count all results without limit
     * @var integer
     */
	var $countAllResults = 0;
	
    /**
     * array container for the resulting search words
     * @var array
     */
	var $arrResult = array();
	
    /**
     * start time
     * @var float
     */
	var $scriptStartTime = 0;
	
    /**
     * stop time
     * @var float
     */
	var $scriptStopTime = 0;
	
    /**
     * array unicode to htmlchar matching table
     * @var array
     */
	var $unicode_array = array(
		"&#8211;" => "-",
		"&#8212;" => "-",
		"" => "-",
		"&#8216;" => "'",
		"" => "'",
		"&#8217;" => "'",
		"" => "'",
		"&#8230;" => "...",
		"" => "...",
		"" => "\"",
		"&#8220;" => "\"",
		"" => "\"",
		"&#8221;" => "\"",
		"" => "\"",
		"&#8222;" => "\"",
	);
	
	
	// ********************************************************************************************************
	
	
    /**
     * convert the special html code in normal chars
     * @access private
     * @param string $string input string
     * @return string $trans_tbl converted string
     */
	function unhtmlentities($string) {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		$trans_tbl = strtr($string, $trans_tbl);
		return $trans_tbl;
	}
	
    /**
     * emilinates the returns and convert the text
     * @access private
     * @param string $text input string
     * @return string $text converted text
     */
	function convertString2Line($text) {
		$text = str_replace("\r\n", "\n", $text);
		$text = str_replace("\n", "#$#", $text);
		
		$text = strtr($text, $this->unicode_array);
		
		$text = $this->unhtmlentities($text);
		return $text;
	}
	
    /**
     * emilinates the special returns
     * @access private
     * @param string $text input string
     * @return string $text converted text
     */
	function convertLine2String($text) {
		$text = str_replace("#$#", "\n", $text);
		return $text;
	}
	
    /**
     * load the object file and return the data
     * @access private
     * @param integer $id unique id to load a file
     * @return array $arrData content of the object file as array by line
     */
	function loadObjectFile($id) {
		$filename = "obj".$id.".txt";
		$arrData = file($this->pathToIndexer.$this->pathObjectFiles.$filename);
		
		return $arrData;
	}
	
    /**
     * write the object file
     * @access private
     * @return void
     */
	function writeObjectFile() {
		$filename = "obj".$this->id.".txt";
		$filename = $this->pathToIndexer.$this->pathObjectFiles.$filename;
		
		// Sichergehen, dass die Datei existiert und beschreibbar ist
		if (is_writable( $this->pathToIndexer.$this->pathObjectFiles)) {
			$handle = fopen($filename, "w");
			
			// write Parameters
			for ($i=0; $i<count($this->arrParams); $i++) {
				fwrite($handle, "#param#".$this->arrParams[$i][0]."|".$this->arrParams[$i][1]."\n");
			}
			
			// write Fields
			for ($i=0; $i<count($this->arrFields); $i++) {
				fwrite($handle, $this->arrFields[$i][0]."|".$this->arrFields[$i][1]."\n");
			}
			
			fclose($handle);
			
		} else {
			$this->logging("ERROR:".$filename." not writable");
		}
	}
	
    /**
     * delete the object file
     * @access private
     * @return void
     */
	function deleteObjectFile() {
		$filename = "obj".$this->id.".txt";
		$filename = $this->pathToIndexer.$this->pathObjectFiles.$filename;
		
		if (file_exists($filename)) {
			if (!unlink($filename)) {
				$this->logging("ERROR:".$filename." not deletable");
			}
		}
	}
	
    /**
     * loads the indexfile and edit the lines
     * @access private
     * @return void
     */
	function editIndexFile() {
		$this->arrIndex = array();
		$inputFile = "idx.php";
		$outputFile = "idx_temp.php";
		$path = $this->pathToIndexer.$this->pathIndexFiles;
		
		if (!file_exists($path.$inputFile)) {
			$createHandle = @fopen ($path.$inputFile, "w");
			if (!$createHandle) {
				$this->logging("ERROR:cannot create ".$path.$inputFile."");
			} else {
				$this->logging("FILE:".$path.$inputFile." created");
				@fclose($createHandle);
			}
		}
		
		if (file_exists($path.$inputFile)) {
			$inputHandle = fopen ($path.$inputFile, "r");
			$outputHandle = fopen ($path.$outputFile, "w");
			while (!feof($inputHandle)) {
				$line = chop(fgets($inputHandle, 1024));
				if (!empty($line)) {
					// work on the line
					$line = $this->editLine($line);
					
					// write final line into output
					if (!empty($line)) fwrite($outputHandle, $line."\n");
				}
			}
			
			// insert new keywords
			foreach ($this->arrWords as $key=>$count) {
				if ($count>0) {
					$line = $key."|".$count." ".$this->id;
					fwrite($outputHandle, $line."\n");
				}
			}
			
			fclose ($outputHandle);
			fclose ($inputHandle);
			
			unlink($path.$inputFile);
			rename($path.$outputFile, $path.$inputFile);
			chmod($path.$inputFile, 0777);
		} else {
			$this->logging("ERROR:".$path.$inputFile." not existing");
		}
		
		//print_r($this->arrIndex);
	}
	
    /**
     * empty the indexfile
     * @access public
     * @return void
     */
	function emptyIndexFile() {
		$filename = "idx.php";
		$path = $this->pathToIndexer.$this->pathIndexFiles;
		
		if (file_exists($path.$filename)) {
			$this->logging("INDEX:".$path.$filename." emptied");
			$handle = fopen ($path.$filename, "w");
			fclose ($handle);
		} else {
			$this->logging("ERROR:".$path.$filename." not existing");
		}
	}
	
    /**
     * loads the blacklist into the array arrBlackList
     * @access private
     * @return void
     */
	function loadBlackList() {
		$filename = $this->pathToIndexer."blacklist.php";
		if (file_exists($filename)) {
			$handle = fopen ($filename, "r");
			while (!feof($handle)) {
				$this->arrBlackList[] = chop(fgets($handle, 4096));
			}
			fclose ($handle);
		} else {
			$this->logging("ERROR:".$filename." not existing");
		}
	}
	
    /**
     * convert the line of the indexfile
     * @access private
     * @param string $line textline from indexfile
     * @return string $line edited textline from indexfile
     */
	function deleteIdFromArray($arr) {
		$arrTemp = array();
		for ($j=0; $j<count($arr); $j++) {
			if ($arr[$j] != $this->id) $arrTemp[] = $arr[$j];
		}
		return $arrTemp;
	}
	
    /**
     * convert the line of the indexfile
     * @access private
     * @param string $line textline from indexfile
     * @return string $line edited textline from indexfile
     */
	function editLine($line) {
		//$line = "Banane|4 7 8 9|1 3 2 1";
		$arrNewAreas = array();
		
		$arrAreas = explode("|", $line);
		$key = $arrAreas[0];
		
		if (!empty($key)) {
			// walk through count areas
			
			if (isset($this->arrWords[$key]) and $this->arrWords[$key] > 0) {
				$inserted = false;
				for ($i=1; $i<count($arrAreas); $i++) {
					// extract count area
					$arrCountLine = explode(" ", $arrAreas[$i]);
					// seperat ids into array
					$arrIds = array_slice ($arrCountLine, 1);
					// count value
					$count = $arrCountLine[0];
					
					$arrTemp = $this->deleteIdFromArray($arrIds);
					// if keyword match with count insert
					if ($this->arrWords[$key] == $count) {
						$arrTemp[] = $this->id;
						$inserted = true;
					}
					// put the filtered ids in new array
					if (count($arrTemp) > 0) $arrNewAreas[] = $count." ".implode(" ", $arrTemp);	// build new area
				}
				// if not inserted -> new count
				if (!$inserted) {
					$arrNewAreas[] = $this->arrWords[$key]." ".$this->id;
				}
				$this->arrWords[$key] = 0;
			} else {
				// delete current id from array
				for ($i=1; $i<count($arrAreas); $i++) {
					// extract count area
					$arrCountLine = explode(" ", $arrAreas[$i]);
					// seperat ids into array
					$arrIds = array_slice ($arrCountLine, 1);
					// count value
					$count = $arrCountLine[0];
					
					if (in_array($this->id, $arrIds)) {
						$arrTemp = $this->deleteIdFromArray($arrIds);
						// put the filtered ids in new array
						if (count($arrTemp) > 0) $arrNewAreas[] = $count." ".implode(" ", $arrTemp);	// build new area
					} else {
						$arrNewAreas[] = $arrAreas[$i];	// if didn't change -> leave as it was
					}
				}
			
			}
			
		}
		
		if (count($arrNewAreas)) $newLine = $key."|".implode("|", $arrNewAreas);
		else $newLine = "";
		
		
		return $newLine;
	}
	
    /**
     * clean the words, by removing 'dirty' chars
     * @access private
     * @param string $strWord word to examine
     * @return string $strWord clean word or empty string
     */
	function cleanWord($strWord) {
		// entferne alle Zeichen ausser Buchstaben und Bindestrich
		$strWord = preg_replace("/(.*)&#(.*);(.*)/i", "\\1\\3", $strWord);	// entferne HTML-Hex-Wörter
		preg_match_all("/[\w-]/", $strWord, $arrText);
		$strWord = implode("", $arrText[0]);
		
		// lösche Wort, falls in Blacklist
		if (in_array(strtolower($strWord), $this->arrBlackList)) {
			$strWord = "";
		}
		
		return $strWord;
	}
	
    /**
     * parse one line to examine the words and insert them into the arrWords-array
     * @access private
     * @param string $text complete textline
     * @return void
     */
	function parseLine($text) {
		// convert text
		$text = strtolower($text);
		$text = $this->unhtmlentities($text);
		$text = str_replace("#$#", " ", $text);
		$text = str_replace(".", " ", $text);
		$text = str_replace(",", " ", $text);
		//$text = str_replace(";", " ", $text);
		$text = str_replace(":", " ", $text);
		//$text = preg_replace("/(.*)&#(.*);(.*)/i", "\\1 \\3", $text);	// entferne HTML-Hex-Wörter
		
		// explode text into array
		$arr = explode(" ", $text);
		for ($i=0; $i<count($arr); $i++) {
			if (strlen($arr[$i]) > 3) {
				$strWord = $this->cleanWord($arr[$i]);
				if ($strWord != "") 
					if (isset($this->arrWords[$strWord])) $this->arrWords[$strWord]++; else $this->arrWords[$strWord] = 1;
			}
		}
		
		ksort($this->arrWords);
	}
	
    /**
     * parse the object for relevant words to add to the index
     * @access private
     * @return void
     */
	function parseRelevantWords() {
		$this->arrWords = array();
		
		for ($i=0; $i<count($this->arrFields); $i++) {
			$this->parseLine($this->arrFields[$i][1]);
		}
		//print_r($this->arrWords);
	}
	
    /**
     * fill the array arrFields
     * @access public
     * @param string $type type of the object
     * @param string $value value of the object
     * @return void
     */
	function addField($type, $value) {
		$value = $this->convertString2Line($value);
		//echo $value."<br>";
		$this->arrFields[] = array($type, $value);
	}
	
    /**
     * fill the array arrParams
     * @access public
     * @param string $type type of the object
     * @param string $value value of the object
     * @return void
     */
	function addParameter($type, $value) {
		$value = $this->convertString2Line($value);
		//echo $value."<br>";
		$this->arrParams[] = array($type, $value);
	}
	
    /**
     * creates the lock-file or deletes the file after $maxLockFileAge seconds
     * @access private
     * @return void
     */
	function startLockIndex() {
		$maxLockFileAge = 10;
		$file = $this->pathToIndexer.$this->pathIndexFiles.$this->lockFile;
		$now = time();
		
		while (file_exists($file)) {
			if ((time() - filemtime($file)) > $maxLockFileAge) {
				$this->logging("LOCK:unlink after ".$maxLockFileAge." sec");
				unlink($file);
			}
			sleep(1);
		}
		
		// schreibe Lock-File
		$handle = @fopen ($file, "w");
		if (!$handle) echo "<font color=\"#FF0000\">Directory not writable. Please be sure enable read/write/execute-rights to the directories 'index' and 'object'.</font><br><br>";
		@fclose($handle);
	}
	
    /**
     * deletes the lock-file
     * @access private
     * @return void
     */
	function stopLockIndex() {
		$file = $this->pathToIndexer.$this->pathIndexFiles.$this->lockFile;
		if (!@unlink($file)) {
			$this->logging("ERROR:".$file." not deletable");
		}
	}
	
	/**
	* removes the index for the setted id
	* @return boolean $success returns true or false, if operation went correct or not.
	* @access public
	*
	*/
	function removeIndex() {
		$this->startTimer();
		$this->startLockIndex();
		
		$this->deleteObjectFile();
		$this->editIndexFile();
		
		$this->stopLockIndex();
		
		$duration = $this->stopTimer(2);
		//echo "Removing ID: ".$this->id." (Duration: ".$duration." sec)<br>";
		$this->logging("REMOVE:".$this->id." DURATION:".$duration);
	}
	
    /**
     * start the indexing process
     * @access public
     * @return void
     */
	function index() {
		$this->startTimer();
		$this->startLockIndex();
		
		$this->writeObjectFile();
		$this->loadBlackList();
		$this->parseRelevantWords();
		$this->editIndexFile();
		
		
		$this->stopLockIndex();
		
		$duration = $this->stopTimer(2);
		$this->logging("INDEX:".$this->id." DURATION:".$duration);
		
		//echo "Indexing ID: ".$this->id." (Duration: ".$duration." sec)<br>";
		//echo "Mem: ".memory_get_usage()."<br>";
	}
	
	
	// ********************************************************************************************************
	
	
    /**
     * Initialize the class so that the data is in a known state.
     * @access public
     * @return void
     */
	function Indexer() {
	}
	
    /**
     * append the text in the logfile
     * @access private
     * @param string $text text to append to the logfile
     * @return void
     */
	function logging($text) {
		$file = $this->pathToIndexer.$this->pathIndexFiles.$this->logFile;
		$now = date ("Y-m-d H:i:s");
		
		$line = $now." ".$text;
		
		$handle = @fopen ($file, "a");
		@fwrite($handle, $line."\n");
		@fclose($handle);
	}
	
    /**
     * set the current id
     * @access public
     * @param integer $id id of the object
     * @return void
     */
	function setId($id) {
		$this->id = $id;
	}
	
    /**
     * set the searchword
     * @access public
     * @param string $val searchword
     * @return void
     */
	function setSearchword($val) {
		$arrVal = explode(" ", $val);
		for ($i=0; $i<count($arrVal); $i++) {
			if ($this->searchword == "") $this->searchword = chop(trim($arrVal[$i]));
			else $this->searchword .= "&&".chop(trim($arrVal[$i]));
		}
		$this->countSearchwords = count(explode("&&", $this->searchword));
	}
	
    /**
     * set the parameter to filter the search result
     * @access public
     * @param string $type type
     * @param string $val val
     * @return void
     */
	function setParameter($type, $val) {
		$this->arrParams[] = array("type"=>$type, "val"=>$val);
	}
	
    /**
     * set the text similarity in percent
     * @access public
     * @param integer $val percent value
     * @return void
     */
	function setTextSimilarityInPercent($val) {
		$this->textSimilarityInPercent = $val;
	}
	
    /**
     * set the cutted text length in the returning array
     * @access public
     * @param integer $val length of the cutted text
     * @return void
     */
	function setTextLength($val) {
		$this->textLength = $val;
	}
	
    /**
     * set the offset of the keyword in the cutted text
     * @access public
     * @param integer $val offset of th keyword in the cutted text
     * @return void
     */
	function setTextOffset($val) {
		$this->textOffset = $val;
	}
	
    /**
     * set the delimiter of the cutted text at the beginning and the end
     * @access public
     * @param string $val string of delimiter
     * @return void
     */
	function setTextDelimiter($val) {
		$this->textDelimiter = $val;
	}
	
    /**
     * set HTML Tag around the keyword in the text
     * @access public
     * @param string $val highlighting tag
     * @return void
     */
	function setTextHighlight($val) {
		$this->textHighlight = $val;
	}
	
    /**
     * set path to indexer
     * @access public
     * @param string $val path to the indexer
     * @return void
     */
	function setPathToIndexer($val) {
		if (substr ($val, -1) != "/")  $val .= "/";
		$this->pathToIndexer = $val;
	}
	
    /**
     * set searchOffset and searchRowCount to make the resultset smaller
     * @access public
     * @param integer $offset start of returning the result
     * @param integer $rowcount number of results to return
     * @return void
     */
	function setLimit($offset, $rowcount) {
		$this->searchOffset = $offset;
		$this->searchRowCount = $rowcount;
	}
	
	
	// ********************************************************************************************************
	
	
    /**
     * load the text return the text clipping
     * @access private
     * @param integer $id unique id
     * @param array $arrKeys key string in array
     * @return string $snippet small text clipping
     */
	function getTextClipping($arrObjectFile, $arrKeys) {
		//print_r($arrObjectFile);
		
		$text = "";
		for ($i=0; $i<count($arrObjectFile); $i++) {
			if (!ereg("#param#", $arrObjectFile[$i])) {
				// hole erste Pos von der pipe
				$pos = strlen($arrObjectFile[$i]) - strlen(stristr($arrObjectFile[$i], "|"));
				$myText = substr($arrObjectFile[$i], $pos+1);
				
				$text .= $this->convertLine2String($myText)."\n";
			}
		}
		
		// suche erstes Vorkommen des zu suchenden Wortes
		$pos = $this->str_contains($text, $arrKeys[0], true);
		for ($i=1; $i<count($arrKeys); $i++) {
			$newPos = $this->str_contains($text, $arrKeys[$i], true);
			if ($newPos < $pos) $pos = $newPos;
		}
		
		$start = 0;
		if ($pos) {
			if ($pos > $this->textOffset) {
				$start = $pos - $this->textOffset;
			}
		}
		$stop = min($start + $this->textLength, strlen($text));
		
		$snippet = $this->strClippingWholeWords($text, $start, $stop, $this->textDelimiter);
		$snippet = $this->str_highlight($snippet, $arrKeys, $this->STR_HIGHLIGHT_SIMPLE, $this->textHighlight);
		
		return $snippet;
	}
	
    /**
     * build object from the array arrSearchIds
     * @access private
     * @return void
     */
	function buildObjects() {
		foreach ($this->arrSearchIds as $id=>$arrValue) {
			$arrObjectFile = $this->loadObjectFile($id);
			
			$filter = true;
			if (count($this->arrParams) > 0) {
				for ($i=0; $i<count($this->arrParams); $i++) {
					$found = false;
					for ($j=0; $j<count($arrObjectFile); $j++) {
						$line = $arrObjectFile[$j];
						if (ereg("#param#", $line)) {
							$line = substr($line, 7);
							$arrCurrentParam = explode("|", $line);
							if (($arrCurrentParam[0] == $this->arrParams[$i]['type']) and (chop($arrCurrentParam[1]) == $this->arrParams[$i]['val'])) $found = true;
						}
					}
					$filter = $filter && $found;
				}
			}
			if ($filter) {
				$obj = new IndexerResult();
				$obj->id = $id;
				$obj->keys = $arrValue['key'];
				$obj->score = $arrValue['score'];
				$obj->text = $this->getTextClipping($arrObjectFile, $arrValue['key']);
				
				$this->arrResult[] = $obj;
			}
		}
		
		$this->countAllResults = count($this->arrResult);
		
		// limit
		if (($this->searchOffset !=0) or ($this->searchRowCount !=0)) {
			$this->arrResult = array_slice($this->arrResult, $this->searchOffset, $this->searchRowCount);
		}
	}
	
    /**
     * eliminate ids, which are not in all searchwords
     * @access private
     * @return void
     */
	function chooseSearchIds() {
		// remove ids
		foreach ($this->arrSearchIds as $id=>$arrValue) {
			//echo count($arrValue['count'])."<br>";
			if (count($arrValue['count']) != $this->countSearchwords) {
				// if not all searchwords match to this id -> remove
				unset($this->arrSearchIds[$id]);
			}
		}
		arsort($this->arrSearchIds);
		
		//print_r($this->arrSearchIds);
	}
	
    /**
     * fill the keywordline into array
     * @access private
     * @return void
     */
	function fillSearchIds($line, $percent, $intSearchword) {
		$arr = explode("|", $line);
		$key = $arr[0];
		for ($i=1; $i<count($arr); $i++) {
			$countLine = $arr[$i];
			$arrCountLine = explode(" ", $countLine);
			$matches = $arrCountLine[0];
			for ($j=1; $j<count($arrCountLine); $j++) {
				$id = $arrCountLine[$j];
				
				if (isset($this->arrSearchIds[$id])) {
					$myScore = $this->arrSearchIds[$id]['score'] + ($percent*$matches);
					$myKey = $this->arrSearchIds[$id]['key'];
					$myKey[] = $key;
					$myCount = $this->arrSearchIds[$id]['count'];
					if (!in_array($intSearchword, $myCount)) $myCount[] = $intSearchword;
				} else {
					$myScore = $percent*$matches;
					$myKey = array();
					$myKey[] = $key;
					$myCount = array();
					$myCount[] = $intSearchword;
				}
				$this->arrSearchIds[$id] = array("score"=>$myScore, "key"=>$myKey, "count"=>$myCount);
			}
		}
	}
	
    /**
     * get the percent of similarity
     * @access private
     * @return float $prozent percent of similarity
     */
	function getPercentForKeyword($key, $searchword) {
		if ($this->textSimilarityInPercent < 100) {
			if (ereg(strtolower($searchword), $key)) $prozent = 100; 
			else $prozent = $this->get_lcs(strtolower($searchword), $key);
		} else {
			if (ereg(strtolower($searchword), $key)) $prozent = 100; else $prozent = 0;
		}
		return $prozent;
	}
	
    /**
     * loads the indexfile into array
     * @access private
     * @return void
     */
	function loadIndexFileForSearching() {
		$this->arrIndex = array();
		$filename = "idx.php";
		$path = $this->pathToIndexer.$this->pathIndexFiles;
		
		$arrSearchwords = explode("&&", $this->searchword);
		$arrIds = array();
		
		if (file_exists($path.$filename)) {
			$handle = fopen ($path.$filename, "r");
			while (!feof($handle)) {
				$line = chop(fgets($handle, 1024));
				if (!empty($line)) {
					$arr = explode("|", $line);
					$key = $arr[0];
					
					for ($i=0; $i<count($arrSearchwords); $i++) {
						$percent = $this->getPercentForKeyword($key, $arrSearchwords[$i]);
						if ($percent >= $this->textSimilarityInPercent) 
							$this->fillSearchIds($line, $percent, $i);
					}
				}
			}
			fclose ($handle);
		} else {
			$this->logging("ERROR:".$path.$filename." not existing");
		}
		
		//print_r($this->arrSearchIds);
	}
	
    /**
     * get count of all results without limit
     * @access public
     * @return integer $this->countAllResults number of all results without limit
     */
	function getCountAllResults() {
		return $this->countAllResults;
	}
	
    /**
     * start the searching process
     * @access public
     * @param string $searchword set the searchword
     * @return array $this->arrSearchIndex search result. 2D-Assozitive array of assoziations 'score' (the hitting percentage), 'key' (the founded word), 'id' (the id of the object), 'text' (a text snippet where the founded word is in)
     */
	function search($searchword="") {
		$this->startTimer();
		if ($searchword != "") $this->setSearchword($searchword);
		
		$this->loadIndexFileForSearching();
		$this->chooseSearchIds();
		$this->buildObjects();
		
		$duration = $this->stopTimer(2);
		//echo "Search for '".$this->searchword."' (Duration: ".$duration." sec)<br>";
		$this->logging("SEARCH:".$this->searchword." DURATION:".$duration);
		
		//print_r($this->arrResult);
		return $this->arrResult;
	}
	
	
	// ********************************************************************************************************
	
	
	/**
	* @access private
	*
	*/	
	function LCS_Length($s1, $s2) {
		$m = strlen($s1);
		$n = strlen($s2);
		
		//this table will be used to compute the LCS-Length, only 128 chars per string are considered
		$LCS_Length_Table = array(array(128),array(128));
		
		
		//reset the 2 cols in the table
		for($i=1; $i < $m; $i++) $LCS_Length_Table[$i][0]=0;
		for($j=0; $j < $n; $j++) $LCS_Length_Table[0][$j]=0;
		
		for ($i=1; $i <= $m; $i++) {
			for ($j=1; $j <= $n; $j++) {
				if ($s1[$i-1]==$s2[$j-1]) {
					$LCS_Length_Table[$i][$j] = $LCS_Length_Table[$i-1][$j-1] + 1;
				} else {
					$help1 = (isset($LCS_Length_Table[$i-1][$j])) ? $LCS_Length_Table[$i-1][$j] : 0;
					$help2 = (isset($LCS_Length_Table[$i][$j-1])) ? $LCS_Length_Table[$i][$j-1] : 0;
					if ($help1 >= $help2) {
						$LCS_Length_Table[$i][$j] = $help1;
					} else {
						$LCS_Length_Table[$i][$j] = $help2;
					}
				}
			}
		}
		return $LCS_Length_Table[$m][$n];
	}
	
	/**
	* @access private
	*
	*/	
	function str_lcsfix($s) {
		$s = str_replace(" ","",$s);
		$s = ereg_replace("[éèêëËÊÉÈ]","e", $s);
		$s = ereg_replace("[àáâãäåÄÅÃÂÁÀ]","a", $s);
		$s = ereg_replace("[ìíîïÏÎÍÌ]","i", $s);
		$s = ereg_replace("[òóôõöÖÕÔÓ]","o", $s);
		$s = ereg_replace("[ÜÛÚÙùúûü]","u", $s);
		$s = ereg_replace("[Ç]","c", $s);
		return $s;
	}
	
	/**
	* @access private
	*
	*/	
	function get_lcs($s1, $s2) {
		//ok, now replace all spaces with nothing
		$s1 = strtolower($this->str_lcsfix($s1));
		$s2 = strtolower($this->str_lcsfix($s2));
		
		$lcs = $this->LCS_Length($s1,$s2); //longest common sub sequence
		
		$ms = (strlen($s1) + strlen($s2)) / 2;
		
		return (($lcs*100)/$ms);
	}
	
    /**
     * searchs the needle in the haystack and returns the position
     * @access private
     * @param string $haystack text where you want to find the needle text
     * @param string $needle text you want to find in the haystack
     * @param boolean $ignoreCase ignore case-sensitivity true or false
     * @return integer $needlePos position of the found position
     */
	function str_contains($haystack, $needle, $ignoreCase = false) {
		if ($ignoreCase) {
			$haystack = strtolower($haystack);
			$needle  = strtolower($needle);
		}
		$needlePos = strpos($haystack, $needle);
		return ($needlePos === false ? false : ($needlePos+1));
	}
	
    /**
     * cut the text in whole words between minimum from start and stop
     * @access private
     * @param string $text text to clip
     * @param integer $start start of clipping
     * @param integer $stop stop of clipping
     * @return string $text clipped text
     */
	function strClippingWholeWords($text, $start, $stop, $delimiter="...") {
		$length = strlen($text);
		
		// suche Wortanfang
		$i = $start;
		while (!ereg("( |:)", substr($text, $i, 1)) AND $i>=0) {
			$i--;
		}
		$start = $i+1;
		
		// suche Wortende
		$i = $stop;
		while (!ereg("( |:|,|\.|\n)", substr($text, $i, 1)) AND $i<$length) {
			$i++;
		}
		$stop = $i;
		
		//echo "Start und Stop: ".$start." ".$stop."<br>";
		
		$text = substr($text, $start, $stop-$start);
		if ($start > 0) $text = $delimiter.$text;
		if ($stop < $length) $text .= $delimiter;
		return $text;
	}
	
	/**
	* Perform a simple text replace
	* This should be used when the string does not contain HTML
	* (off by default)
	*/
	var $STR_HIGHLIGHT_SIMPLE = 1;
	
	/**
	* Only match whole words in the string
	* (off by default)
	*/
	var $STR_HIGHLIGHT_WHOLEWD = 2;
	
	/**
	* Case sensitive matching
	* (on by default)
	*/
	var $STR_HIGHLIGHT_CASESENS = 4;
	
	/**
	* Don't match text within link tags
	* This should be used when the replacement string is a link
	* (off by default)
	*/
	var $STR_HIGHLIGHT_SKIPLINKS = 8;
	
	/**
	* Highlight a string in text without corrupting HTML tags (http://aidan.dotgeek.org/lib/?file=function.str_highlight.php)
	*
    * @access private
	* @author      Aidan Lister <aidan@php.net>
	* @version     3.0.0
	* @param       string          $text           Haystack - The text to search
	* @param       array|string    $needle         Needle - The string to highlight
	* @param       bool            $options        Bitwise set of options
	* @param       array           $highlight      Replacement string
	* @return      Text with needle highlighted
	*/
	function str_highlight ($text, $needle, $options = null, $highlight = null) {
		// Default highlighting
		if ($highlight === null) {
			$highlight = '<strong>\1</strong>';
		}
		
		// Select pattern to use
		if ($options & $this->STR_HIGHLIGHT_SIMPLE) {
			$pattern = '#(%s)#';
		} elseif ($options & $this->STR_HIGHLIGHT_SKIPLINKS) {
			// This is not working yet
			$pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
		} else {
			$pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
		}
		
		// Case sensitivity
		if ($options ^ $this->STR_HIGHLIGHT_CASESENS) {
			$pattern .= 'i';
		}
		
		$needle = (array) $needle;
		foreach ($needle as $needle_s) {
			$needle_s = preg_quote($needle_s);
			
			// Escape needle with optional whole word check
			if ($options & $this->STR_HIGHLIGHT_WHOLEWD) {
				$needle_s = '\b' . $needle_s . '\b';
			}
			
			$regex = sprintf($pattern, $needle_s);
			$text = preg_replace($regex, $highlight, $text);
		}
		
		return $text;
	}
	
	/**
	* sets the starttime for the internal runtime counter
	* @access protected
	*/
	function startTimer() { 
	    $microtime=explode(" ", microtime()); 
    	$this->scriptStartTime=$microtime[1]+$microtime[0]; 
    }  // end func startTimer
	
	/**
	* sets the stoptime for the internal runtime counter
	* @access protected
	* @param int $decimals presicion of the returned runtime
	* @return real $runtime runtime of the script between start and stop of the timer.
	*/
	function stopTimer($decimals = 5) { 
	    $microtime=explode(" ", microtime()); 
    	$this->scriptStopTime =$microtime[1]+$microtime[0]; 
	    return number_format(($this->scriptStopTime -$this->scriptStartTime),$decimals); 
    }// end func stopTimer
}
?>