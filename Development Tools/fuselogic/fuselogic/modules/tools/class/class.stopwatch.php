<?

class stopwatch{

  var $time = array();
	var $event = array();
	var $i;
	
	function stopwatch($microtime = 0){
	    $this->i = 0;		
		  stopwatch::Start($microtime); 
	}
	
	function GetMicrotime($microtime = 0){  // From PHP manual
	    if($microtime !== 0){
			    list($usec, $sec) = explode(" ",$microtime);
			}else{
		      list($usec, $sec) = explode(" ",microtime()); 
		  }
		  return ((float)$usec + (float)$sec); 
	}

	function Start($microtime = 0){	  
		   $this->timeStart = stopwatch::GetMicrotime($microtime);		
	}
	
	function Time(){
		return stopwatch::GetMicrotime() - $this->timeStart;
	}
		
  function Record($event = 'No Event Name'){
	  $this->time[$this->i]  = stopwatch::Time();
		$this->event[$this->i] = $event;
		$this->i = $this->i + 1;
	}
		
	function ShowAll(){
	  $result = '<br>';
		for($i=0;$i<$this->i;$i++){
		   $result = $result . $this->time[$i] .' sec / '. $this->event[$i].'<br>';			  
		}
		return $result;
	}

}

?>
