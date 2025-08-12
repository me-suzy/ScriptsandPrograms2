<?php


/**
*
* pre-alpha!
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

   /**
    *
    * @version      $Id: email_string.class.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      EasyFramework
    */
class email_string extends easy_string {
	
	var $onWarningMsg = "Validation failed";
    var $onErrorMsg   = "";
    var $ConfirmMsg   = "";
        
    function email_string ($value) {
        $this->easy_string ($value); // call of parents constructor	
		$this->js_evaluation_code = $this->calc_js_evaluation_code ();
    }
    
    function validate () { 
		if (!$this->NULL_ALLOWED  && is_null($this->data)) return false;
        if (!$this->EMPTY_ALLOWED && $this->data == "")    return false;
        if ( $this->NULL_ALLOWED  && is_null($this->data)) return true;
        if ( $this->EMPTY_ALLOWED && $this->data == "")    return true;
        return $this->is_email($this->data);
 	}
 	
 	// === Javascript alerts, errors and warnings ===================
 	function cs_validation_error   () {}		
    
    function cs_validation_warning () {
    	$js['function']  = JS_VALIDATION_FUNCTION;
    	$js['condition'] = "!isEmail(document.Formular.email.value)";
    	$js['onTrue']    = "alert ('".$this->onWarningMsg."')";
    	$js['helperFct'] = $this->getJS_EmailValidation();
    	$js['freeCode']  = "";
    	return $js;
    }
    
    function cs_validation_confirm () {}
    
 	// === Helper Functions =========================================
	function is_email($email){
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",
			$email));
	} 
	function calc_js_evaluation_code () {
	    $js = 'validRegExp = /^[^@]+@[^@]+.[a-z]{2,}$/i;'."\n";
		$js .= 'if (value.search(validRegExp) == -1) return false;'."\n";      
		$js .= "return true;\n";
		return $js;
	}
	
	function getJS_EmailValidation () {
		return "
function isEmail(str) {
    validRegExp = /^[^@]+@[^@]+.[a-z]{2,}$/i;
    if (str.search(validRegExp) == -1) {
		return false;      
    } 
    return true;
}
		";
	}

}

?>
