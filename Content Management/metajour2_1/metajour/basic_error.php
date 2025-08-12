<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage handler
 */
require_once('basic_user.php');

class basic_error {
	var $error = array();
	var $disable = false;
	function setError($erroridx, $detail = '') {
		if (!$this->disable) {
			$arr = array();
			$arr['erroridx'] = $erroridx;
			$arr['errortxt'] = $this->getErrorText($erroridx);
			$arr['detail'] = $detail;
			// to avoid listing of call to errorhandler
			$tmp = debug_backtrace();
			#array_shift($tmp);
			$arr['backtrace'] = $tmp;
			$this->error[] = $arr;
		}
	}
	
	function removeLastError() {
		array_pop($this->error);
	}
	
	function clearErrors() {
		$this->error = array();
	}
	
	function hasError() {
		return sizeof($this->error);
	}
	
	function disable() {
		$this->disable = true;
	}
	
	function enable() {
		$this->disable = false;
	}
	
	function getErrorText($erroridx) {
		global $LANG;
		static $langloaded = false;
		if (!$langloaded) {
			$uh = &getUserHandler();
			$language = $uh->getGuiLanguage();
			$deflang = 'en';
			if (file_exists('lang/error.'.$language.'.php')) {
				require_once('lang/error.'.$language.'.php');
			} elseif (file_exists('lang/error.'.$deflang.'.php')) {
				require_once('lang/error.'.$deflang.'.php');
			}
			$langloaded = true;
		}
		return isset($LANG[$erroridx]) ? $LANG[$erroridx] : $erroridx;
	}
	
	function outputError() {
		foreach ($this->error as $err) {
			$result .= $err['errortxt'].'\n';
			$result .= $err['detail'].'\n';
		}
		return $result;
	}
	
	function htmlError() {
		foreach ($this->error as $err) {
			$result .= $err['errortxt']."<br>\n";
		}
		return $result;
	}
	
	function detailHtmlError() {
		$result = "<div style='text-align: left; font-family: Message-box;'>\n";
		foreach ($this->error as $err) {
			$result .= '<font size="+1">'.$err['errortxt']."</font><br>\n";
			$result .= '<b>Detail: </b>'.$err['detail']."<br>\n";
			$result .= $this->backtrace($err['backtrace'])."<br>\n";
		}
		$result .= "</div>\n";
		return $result;
	}

	function backtrace($btr) {
		$output = "<BR><b>Backtrace:</b>\n";
		$backtrace = $btr;
	
		foreach ($backtrace as $bt) {
			$args = '';
			if (is_array($bt['args'])) {
				foreach ($bt['args'] as $a) {
					if ($args !== '') {
						$args .= ', ';
					}
					switch (gettype($a)) {
						case 'integer':
						case 'double':
							$args .= $a;
							break;
						case 'string':
							$a = htmlspecialchars(substr($a, 0, 64)).((strlen($a) > 64) ? '...' : '');
							$args .= "\"$a\"";
							break;
						case 'array':
							$args .= 'Array('.count($a).')';
							break;
						case 'object':
							$args .= 'Object('.get_class($a).')';
							break;
						case 'resource':
							$args .= 'Resource('.strstr($a, '#').')';
							break;
						case 'boolean':
							$args .= $a ? 'True' : 'False';
							break;
						case 'NULL':
							$args .= 'Null';
							break;
						default:
							$args .= 'Unknown';
					}
				}
				$output .= "<br />\n";
				$output .= "<b>file:</b><font color=\"blue\"> {$bt['file']}</font> at line <font color=\"blue\">{$bt['line']}</font><br />\n";
				$output .= "<b>call:</b><font color=\"red\"> {$bt['class']}{$bt['type']}{$bt['function']}($args)</font><br />\n";
			}
			return $output;
		}
	}
}

function &GetErrorHandler() {
	static $_errorhandler = null;
	if (null == $_errorhandler) {
		$_errorhandler = new basic_error;
	}
	return $_errorhandler;
}

function errorHandler($errno, $errstr, $errfile, $errline) {

	if ($errno == E_NOTICE) return true;
		
	
	if (error_reporting() != 0) {
		$eh =& getErrorHandler();
				
		if ($errno == E_WARNING || $errno == E_USER_ERROR || $errno == E_USER_WARNING) { 
			if ($errstr != '') {
				$eh->setError('error_php_error', $errstr . " at $errfile line $errline");
			}
		}
		
		if ($errno == E_USER_ERROR) {
			if (!$eh->disable) {
				while (@ob_end_clean());
				echo $eh->detailHtmlError();
				die();
			}
		}
		
	}
	
	return true;
}

function fatalError($detail = '') {
	trigger_error($detail, E_USER_ERROR);
}

set_error_handler('errorHandler');

?>