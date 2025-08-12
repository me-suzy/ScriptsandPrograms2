<?php

class no_spam{
    var $spamer;

		function no_spam($spamer = array()){
		    $this->spamer = $spamer;
		}
		
    function hitBack(){
        $count = count($this->spamer);
        for($i=0;$i<$count;$i++){
            if(preg_match("/".$this->spamer[$i]."/i",$_SERVER["HTTP_REFERER"])){
                $this->Location($_SERVER["HTTP_REFERER"]);				        		
            }	
        }
    }
    function redirectTo($url = ''){
        $count = count($this->spamer);
        for($i=0;$i<$count;$i++){
            if(preg_match("/".$this->spamer[$i]."/i",$_SERVER["HTTP_REFERER"])){
                $this->Location($url);				        		
            }	
        }
    }
    function Location($URL, $addToken = 0) {
		    $questionORamp = (strstr($URL, "?"))?"&":"?";
		    $location = ($addToken && substr($URL, 0, 7) != "http://")?$URL.$questionORamp.$SID:$URL; //append the sessionID ($SID) by default
		    ob_end_clean(); //clear buffer, end collection of content
		    if(headers_sent()){
			      print('<script language="JavaScript"><!--
	          location.replace("'.$location.'");
	          // --></script>
	          <noscript><META http-equiv="Refresh" content="0;URL='.$location.'"></noscript>');
		    }else{
			  header('Location: '.$location); //forward to another page
			  exit; //end the PHP processing
		    }
    }

}

?>