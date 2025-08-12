<?php

class browser{

    var $BROWSER_VER;
		var $BROWSER_AGENT;
		var $BROWSER_PLATFORM;
		
    function browser($array = array()){
		    $this->detect($array);
		}    

    function detect($array = array()){		    
        
				/*
	      Determine browser and version
        */
				
				$temp = isset($array['user_agent'])?$array['user_agent']:$_SERVER["HTTP_USER_AGENT"];
				$temp = strtolower($temp);				
				
        if(ereg('opera ([0-9].[0-9]{1,2})',$temp,$log_version)){
	          $this->BROWSER_VER=$log_version[1];
	          $this->BROWSER_AGENT='opera';
        }elseif(ereg('netscape/([0-9].[0-9]{1,2})',$temp,$log_version)){
	          $this->BROWSER_VER=$log_version[1];
	          $this->BROWSER_AGENT='netscape';
        }elseif (ereg( 'msie ([0-9].[0-9]{1,2})',$temp,$log_version)) {
	          $this->BROWSER_VER=$log_version[1];
	          $this->BROWSER_AGENT='ie';
        }elseif(ereg( 'mozilla/([0-9].[0-9]{1,2})',$temp,$log_version)) {
	          $this->BROWSER_VER=$log_version[1];
	          $this->BROWSER_AGENT='mozilla';
        }else{
	          $this->BROWSER_VER = 0;
	          $this->BROWSER_AGENT='other';
        }

        /*
	      Determine platform
        */
        $temp = isset($array['user_agent'])?$array['user_agent']:$_SERVER["HTTP_USER_AGENT"];
				
        if(strstr($temp,'Win')) {
	          $this->BROWSER_PLATFORM='windows';
        }elseif(strstr($temp,'Mac')) {
	          $this->BROWSER_PLATFORM='mac';
        }elseif(strstr($temp,'Linux')) {
	          $this->BROWSER_PLATFORM='linux';
        }elseif(strstr($temp,'Unix')) {
	          $this->BROWSER_PLATFORM='unix';
        }else{
	          $this->BROWSER_PLATFORM='other';
        }

    }

    function getPlatForm(){
        return $this->BROWSER_PLATFORM;
    }

    function getBrowser(){
        return $this->BROWSER_AGENT;
    }

    function getVersion(){
        return $this->BROWSER_VER;
    }

}

?>
