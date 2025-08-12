<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */
 
Class celesteStringReverse {

  var $_string;
  var $html;
  var $smile;
  var $ceTag;
  
  function celesteStringReverse( $html, $smile, $ceTag ) {
  	$this->html = $html;
  	$this->smile = $smile;
  	$this->ceTag = $ceTag;
  }
  
  function setString(&$string) {
  	$this->_string = $string;
  }
  
  function parse() {

  	$s =& $this->_string;
    if ($this->html) $this->unConvertHtml($s);
    if ($this->smile) $this->unConvertSmileTags($s);
    if ($this->ceTag) $this->unConvertceTag($s);

    return $s;
  }

  function unConvertHtml(&$s) {
    $s = str_replace('<br>', "", $s);
    $s = str_replace('<br />', "", $s);
  }

  function unConvertSmileTags(&$s) {
    include (DATA_PATH.'/settings/smile.inc.php');
    //$s =& preg_replace("/<!--SF (.+) Begin-->.+<!--SF \\1 End-->/sU", '\\1', $s);

    $s = str_replace($smileImgs, $smileTags, $s);
  }

  function unConvertceTag(&$s) {
    $s = preg_replace('/\<\!-- CETagParser ~(img|flash)=(.+)~ --\>.+\<\!-- \/CETagParser --\>/sU', '[\\1]\\2[/\\1]', $s);
    $s = preg_replace('/\<\!-- CETagParser ~(.+)~ --\>.+\<\!-- \/CETagParser --\>/sU', '[\\1]', $s);

    $s = str_replace('<ul type=square>', '[list]', $s);
    $s = str_replace('</ul>', '[/list]', $s);
    $s = str_replace('<li>', '[*]', $s);
    $s = preg_replace('/<ol type=(1|a)>(.+?)<\/ol>/', '[list=\\1]\\2[/list=\\1]', $s);

    $s = preg_replace('/<(\/|)(b|i|u|center)>/', '[\\1\\2]', $s);
    $s = str_replace('<b>', '[b]', $s);
    $s =& str_replace('&nbsp;&nbsp;', "\t", $s);
    $s =& str_replace('&nbsp;', ' ', $s);
  }

}
?>