<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */
 
class basic_context_normal {
	var $_clear = false;
	var $_header = '';
	var $name = 'normal';
	var $_loosedtd = true;
	var $_footer = '';
	
	function basic_context_normal() {
	}
	
	function setloosedtd($value) {
		$this->_loosedtd = $value;
	}
	
	function clearall() {
		$this->_clear = true;
	}
	
	function addheader($str) {
		$this->_header .= $str;
	}
	
	function addfooter($str) {
		$this->_footer .= $str;
	}
	
	function addonload($str) {
		$this->addFooter("<script type='text/javascript'>EventManager.Add(window, 'load', " . $str . ");</script>");
	}
	
	function addonunload($str) {
		$this->addFooter("<script type='text/javascript'>EventManager.Add(window, 'unload', " . $str . ");</script>");
	}
	
	function header_start() {
		if ($this->_clear) return '';
		/* 
		Triedit doesnt like the usual doctype, so leave it out in documentsection editor 
		*/
		if ($this->_loosedtd) $result .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
		else $result .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
		$result .= '<html><head>'.$this->header_content();
		return $result;
	}

	function header_content() {
		global $system_url;
		$result = '
<meta http-equiv="Content-Language" content="da">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<link rel="stylesheet" type="text/css" href="'.$system_url.'css/gui.css">
<script type="text/javascript" src="'.$system_url.'js/mozilla_compat.js"></script>
<script type="text/javascript" src="'.$system_url.'js/eventmanager.js"></script>';

		$result .= $this->_header;
		return $result;	
	}
		
	function footer_content() {
		return $this->_footer;
	}
	
	function header_end() {
		if ($this->_clear) return '';
		return '</head>';
	}
	  
	function body() {
		if ($this->_clear) return '';
		return '<body>';
	}
	
	function footer() {
		if ($this->_clear) return '';
		$result = $this->footer_content();
		$result .= "</body></html>";
		return $result;
	}

}



class basic_context_dialog {
	var $_clear = false;
	var $_header = '';
	var $name = 'dialog';
	
	function basic_context_normal() {
	}
	
	function clearall() {
		$this->_clear = true;
	}
	
	function addheader($str) {
		$this->_header .= $str;
	}
	
	function addonload($str) {
		$this->addFooter("<script type='text/javascript'>EventManager.Add(window, 'load', " . $str . ");</script>");
	}
	
	function addonunload($str) {
		$this->addFooter("<script type='text/javascript'>EventManager.Add(window, 'unload', " . $str . ");</script>");
	}
	
	function header_start() {
		if ($this->_clear) return '';
		$result .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
		<style type="text/css">
			body { background-color: buttonface; font-family: Tahoma; font-size: 8pt; }
			select { font-family: Tahoma; font-size: 8pt; }
			input { font-family: Tahoma; font-size: 8pt; }
			td { font-family: Tahoma; font-size: 8pt; }
		</style>
<meta http-equiv="Content-Language" content="da">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<script type="text/javascript" src="'.$system_url.'js/mozilla_compat.js"></script>
<script type="text/javascript" src="'.$system_url.'js/eventmanager.js"></script>';

		$result .= $this->_header;
		return $result;
	}
	
	function header_end() {
		if ($this->_clear) return '';
		return '</head>';
	}
	  
	function body() {
		if ($this->_clear) return '';
		return '<body>';
	}
	
	function footer() {
		if ($this->_clear) return '';
		return '</body></html>';
	}

}


class basic_context_largedialog extends basic_context_normal {
	
	function header_content() {
		$result = parent::header_content();
		$result .= '
		<style type="text/css">
		<!--
		body {      
  			margin: 0px;
  			background: ThreeDFace;
		}
		-->
		</style>
		';
		return $result;	
	}

}


function getcontext($otype,$context='normal') {
	if (file_exists($otype."_context_".$context.".php")) {
		@require_once($otype."_context_".$context.".php");
	}
	if (class_exists($otype.'_context_'.$context)) {
		$s = $otype.'_context_'.$context;
	} else {
		$s = 'basic_context_'.$context;
	}
	return new $s;
}

?>