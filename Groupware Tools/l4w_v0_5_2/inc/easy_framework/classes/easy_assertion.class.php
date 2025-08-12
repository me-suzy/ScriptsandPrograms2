<?php

/**
 * Class standard_assertion (Evandor Application System)
 * 
 * This is the default class to manage assertions. In config/easy.config.php
 * the default function to call when assertions are thrown is defined:
 * 
 *     assert_options(ASSERT_CALLBACK, "easy_assert_callback");
 * 
 * easy_assert_callback calls $easy->assertion->throw_assertion($file, $line, $tmp);,
 * with $easy->assertion being this class (by default).
 *
 * @author       Carsten Graef <evandor@gmx.de>
 * @copyright    evandor media 2005
 * @package      EasyFramework
 */

/**
*
* @version      $Id: easy_assertion.class.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/
class standard_assertion {

    /**
     * Should the program be continued in case of assertion?
     *
     * @var boolean
     * @access private
     */
	var $continue_if_thrown = false;

    /**
     * Message to be shown if program stops because of assertion
	 * 
     * @var class
     * @access private
     */
    var $message_on_exit    = ASSERTION_EXIT_MESSAGE;

    /**
     * Instance of Easy. Provided in the constructor
     *
     * @var object
     * @access private
     */
    var $easy_instance;

	/**
	* constructor for class standard_assertion
	*
	* @access       public
	* @param object $easy Current instance of Easy
	* @return void
	* @since        0.1
	* @version      0.1
	**/
    function standard_assertion (&$easy) {
    	$this->easy_instance = $easy;
    }

	/**
	* the function to be called if there has been an exception. The 
	* exception is logged with the default logging_style depending 
	* on the configuration in the config-file (if not changed by the
	* application itself).
	* 
	* You don't have to call this function yourself, just use 
	* assert ('statement')
	*
	* @access       public
	* @param string $file In which file did the error occur
	* @param string $line In which line did the error occur
	* @param string $code Which code caused the error
	* @todo  easy_instance still needed
	* @return void
	* @since        0.1
	* @version      0.1
	**/
	function throw_assertion ($file, $line, $code) {

    	//$this->easy_instance->logger->log ("Assertion called in $file on line $line", 1);
		($this->continue_if_thrown) ? $continue = "yes - Code execution continued" : $continue = "stopped execution of code";
		$trace  = "<table>\n";
		$trace .= "<tr><td><span onClick='javascript:document.all.backtrace.style.display=true'><u>".$code[count($code)-1]."</u></span><td></tr>\n";
		$trace .= "</table>\n";
		$trace .= "<span name='backtrace' id='backtrace' style='display:true'><table>\n";
		for ($i=0; $i < count($code)-2; $i++) {
			$part = "";
			if (isset ($code[$i]['file']))     $part .= $code[$i]['file']." (".$code[$i]['line'].")<br>\n";
			if (isset ($code[$i]['function'])) $part .= "<b>".$code[$i]['function']."</b>";
			if (isset ($code[$i]['type']))     $part .= " - Type: <i>".$code[$i]['type']."</i>";
			if (isset ($code[$i]['class']))    $part .= " - Class: <i>".$code[$i]['class']."</i>";
			$part .= "<br>";
			$bgcolor = "#ffffff";
			if (($i%2) == 0) $bgcolor = "#eeeeee";
			$trace .= "<tr><td bgcolor='$bgcolor'>".$part."<td></tr>\n";			
		}
		$trace .= "</table></span>\n";
		$request = '<table>';
		foreach ($_REQUEST AS $key => $value)
		    $request .= "<tr><td><b>".$key."</b></td><td>".$value."</td></tr>\n";
		$request .= "</table>\n";
		
		echo "
    		  <table cellpadding=2 cellspacing=0 style='border-style:solid;border-width:1px;border-color:#AA0000'>
				<tr>
					<td colspan=2 style='background-color:#AA0000;'>
					    <img src='file://".EASY_FRAMEWORK_DIR."/img/exclamation.gif' align='top'>&nbsp;
						<font color='white'><b>Easy Assertion failed!</b></font>
					</td>
				</tr>
        		<tr><td><b>File:</b></td><td>$file</td></tr>
                <tr><td><b>Line:</b></td><td>$line</td></tr>
                <tr><td valign=top><b>Backtrace:</b></td><td>$trace</td></tr>
                <tr><td valign=top><b>Request:</b></td><td>$request</td></tr>
                <tr><td><b>Continuation.:</b></td><td>$continue</td></tr>
              </table>
              ";

    	if (!$this->continue_if_thrown) {
            $this->easy_instance->logger->log ("Exection stopped");
            //if (EASY_DEBUG) echo "(Easy Debug Msg.) Execution stopped as of assertion";
            die ($this->message_on_exit);
    	}
    }

	/**
	* continue after thrown assertion 
	*
	* @access       public
	* @param bool $cont_if_thrown continue after thrown assertion
	* @return void
	* @since        0.1
	* @version      0.1
	**/
    function set_proceeding ($cont_if_thrown) {
	    assert(is_bool($cont_if_thrown));
    	$this->continue_if_thrown = $cont_if_thrown;
    }

	/**
    * shutdown function. Empty in this version. Is called by easy
	* when script terminates.
	*
	* @access       public
	* @return void
	* @since        0.1
	* @version      0.1
	**/
    function shutdown () {
    }

}

?>
