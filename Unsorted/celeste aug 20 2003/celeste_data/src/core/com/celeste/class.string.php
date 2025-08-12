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
 
class celesteStringFactory {

  var $_string = '';

  var $htmcode = 0;
  var $ceTag   = 1;
  var $aUrlTag = 1;
  var $imgcode = 1; /* set 1 to allow imgcode, set 2 to auto-parse the img code */
  var $flacode = 1; /* like imgcode */
  var $maxImg  = 0; /* max imgs in string, set -1 = do not limit */
  var $smile = 1;

  function celesteStringFactory( $ceTag, $autoAddUrlTags, $imgcode, $flacode, $htmcode, $maxImg = -1, $smile ) {
    $this->ceTag   = $ceTag;
    $this->imgcode = $imgcode;
    $this->flacode = $flacode;
    $this->htmcode = $htmcode;
    $this->aUrlTag = $autoAddUrlTags;
    $this->maxImg  = $maxImg;
    $this->smile = $smile;
  }
  
  function setceTag( $ceTag ) {
    $this->ceTag   = $ceTag;
  }
  
  function setImgcode( $imgcode ) {
    $this->imgcode = $imgcode;
  }
  
  function setAutoParseURL( $a ) {
    $this->aUrlTag = $a;
  }
  function setFlash( $f) {
    $this->flacode = $f;
  }
  function setHTML( $h) {
    $this->htmcode = $h;
  }
  function setMaxImg($m) {
    $this->maxImg = $m;
  }
  
  function setString( &$_string ) {
    $this->_string = $_string;
  }
  
  function setSmile( $s ) {
  	$this->smile =$s;
  }
  
  function _removeHTML() {
    $this->_string =& _removeHTML($this->_string);
  }

  function getHTML() {
    $this->_string =& str_replace('&quot;', '"', $this->_string);
    $this->_string =& str_replace('&#039;', '\'', $this->_string);
    $this->_string =& str_replace('&lt;', '<', $this->_string);
    $this->_string =& str_replace('&gt;', '>', $this->_string);
  }

  function parse() {
    $this->_string =& _replaceCensored($this->_string);

    if($this->htmcode) $this->getHTML();

    if($this->aUrlTag) $this->autoAddUrlTags();
    if($this->ceTag) {
      $this->_parseTag();
    }
    
    if ($this->smile) $this->convertSmileTags();

    $this->_string =& preg_replace('/c:(\\|\/)con(\\|\/)con/i', '', $this->_string);
    $this->_string =& preg_replace('/javascript:/i', 'Java Script &#58; ', $this->_string );
    $this->_string =& preg_replace('/CES=[a-z0-9]{32}/i', '', $this->_string);

    // convert spaces at line beginning
    //$this->_string =& preg_replace('/^( +)/me', 'str_repeat(" ", strlen("\1"))', $this->_string);

    //$this->_removeExcess();
    return $this->_string;
  }

  function autoAddUrlTags() {

    $this->_string =& preg_replace('/(^|\s)((https?|ftp|gopher|news|telnet):\/\/|www\.)(\w+\S+)/i',
                                  '\\1[url]\\2\\4[/url]',
                                  $this->_string);

    $this->_string =& str_replace('[url]www.', '[url]http://www.', $this->_string);

    $this->_string =& preg_replace('/\\[url\\](.{60,})\\[\\/url\\]/ieU',
                                  'celesteStringFactory::shortenUrlTag(\'\\1\')', $this->_string);
  
    if(strpos($this->_string, '@'))
      $this->_string =& preg_replace('/(^|\s)([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})/i',
                                    '\\1[email]\\2[/email]',
                                    $this->_string);
    if( $this->imgcode == 2 )
      $this->_string =& preg_replace('/\\[url\\](.+)\\.(gif|jpg|png|bmp)\\[\\/url\\]/iU',
                                    '[img]\\1.\\2[/img]', $this->_string);
    if( $this->flacode == 2 )
      $this->_string =& preg_replace('/\\[url\\](.+)\\.swf\\[\\/url\\]/iU',
                                    '[flash]\\1.swf[/flash]', $this->_string);
  }

  function _parseTag() {
  	
  	$aStr =& $this->_string;

    $pcre_ceTag_pattern = array(
      "/\[url\]([^\[]+?)\[\/url\]/is",
      "/\[url=([^\[]*)\](.+?)\[\/url\]/is",
      "/\\[email\\]([^\\[]+?)\\[\\/email\\]/i",
      "/\\[email=('|\"|&quot;|)([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})\\1\\](.+?)\[\\/email\]/is",
      "/\[color=([#0-9a-z]{1,10})\]/is",
      "/\\[size=('|\"|&quot;|)([0-9]{1})\\1\\]/i",
      "/\\[font=[\"]{0,1}([^\\[]*)[\"]{0,1}\\]/i",
      
      "/\[quote\](.*)\[code\](.+?)\[\/code\](.*)\[\/quote\]/is",
      "/\[quote\]\s*(.+?)\s*\[\/quote\]/is",
        
      "/\[code\]\s*(.+?)\s*\[\/code\]/ise");

    $pcre_ceTag_replace = array(
      "<!-- CETagParser ~url~ --><a href=\"\\1\" target=_blank><!-- /CETagParser -->\\1<!-- CETagParser ~/url~ --></a><!-- /CETagParser -->",
      "<!-- CETagParser ~url=\\1~ --><a href=\"\\1\" target=_blank><!-- /CETagParser -->\\2<!-- CETagParser ~/url~ --></a><!-- /CETagParser -->",
      "<!-- CETagParser ~email~ --><a href=\"mailto:\\1\"><!-- /CETagParser -->\\1<!-- CETagParser ~/email~ --></a><!-- /CETagParser -->",
      "<!-- CETagParser ~email=\\2~ --><a href=\"mailto:\\2\"><!-- /CETagParser -->\\4<!-- CETagParser ~/email~ --></a><!-- /CETagParser -->",
      "<!-- CETagParser ~color=\\1~ --><font color=\"\\1\"><!-- /CETagParser -->",
      "<!-- CETagParser ~size=\\2~ --><font size=\\2><!-- /CETagParser -->",
      "<!-- CETagParser ~font=\\2~ --><font face=\"\\2\"><!-- /CETagParser -->",

      "<!-- CETagParser ~quote~ --><br><table cellpadding=0 cellspacing=0 border=0 WIDTH=94% bgcolor='".SET_QUOTE_BORDER_COLOR."' align=center><tr><td><table width=100% cellpadding=5 cellspacing=1 border=0><TR><TD BGCOLOR='".SET_QUOTE_INNER_COLOR."'><!-- /CETagParser -->\\1 <!-- CETagParser --><br><!-- /CETagParser --> \\2 <!-- CETagParser --><br><!-- /CETagParser --> \\3<!-- CETagParser ~/quote~ --></td></tr></table></td></tr></table><br><!-- /CETagParser -->",
      "<!-- CETagParser ~quote~ --><br><table cellpadding=0 cellspacing=0 border=0 WIDTH=94% bgcolor='".SET_QUOTE_BORDER_COLOR."' align=center><tr><td><table width=100% cellpadding=5 cellspacing=1 border=0><TR><TD BGCOLOR='".SET_QUOTE_INNER_COLOR."'><!-- /CETagParser -->\\1<!-- CETagParser ~/quote~ --></td></tr></table></td></tr></table><br><!-- /CETagParser -->",
        
      "celesteStringFactory::convertCodesArea('\\1')");
    
    $aStr =& preg_replace($pcre_ceTag_pattern, $pcre_ceTag_replace, $aStr);

    $aStr =& str_replace('[list]', '<ul type=square>', $aStr);
    $aStr =& str_replace('[/list]', '</ul>', $aStr);
    $aStr =& str_replace('[list=1]', '<ol type=1>', $aStr);
    $aStr =& str_replace('[list=a]', '<ol type=A>', $aStr);
    $aStr =& str_replace('[/list=1]', '</ol>', $aStr);
    $aStr =& str_replace('[/list=a]', '</ol>', $aStr);
    $aStr =& str_replace('[*]', '<li>', $aStr);
    $aStr =& str_replace('[/size]', '<!-- CETagParser ~/size~ --></font><!-- /CETagParser -->', $aStr);
    $aStr =& str_replace('[/font]', '<!-- CETagParser ~/font~ --></font><!-- /CETagParser -->', $aStr);
    $aStr =& str_replace('[/color]', '<!-- CETagParser ~/color~ --></font><!-- /CETagParser -->', $aStr);

    $aStr =& str_replace('[b]', '<b>', $aStr);
    $aStr =& str_replace('[/b]', '</b>', $aStr);
    $aStr =& str_replace('[i]', '<i>', $aStr);
    $aStr =& str_replace('[/i]', '</i>', $aStr);
    $aStr =& str_replace('[u]', '<u>',$aStr);
    $aStr =& str_replace('[/u]', '</u>',$aStr);
    $aStr =& str_replace('[center]', '<center>', $aStr);
    $aStr =& str_replace('[/center]', '</center>', $aStr);

    if( $this->imgcode ) {
      $aStr =& preg_replace('/\\[img\\]([^\[]+)\\[\\/img\\]/i',
           '<!-- CETagParser ~img=\\1~ --><img src="\\1" border=0><!-- /CETagParser -->', $aStr, $this->maxImg);
    }

    if( $this->flacode ) {
      $aStr =& preg_replace('/\\[flash\\]([^\[]+)\\[\\/flash\\]/i', '<!-- CETagParser ~flash=\\1~ --><EMBED SRC=\\1 WIDTH=400 HEIGHT=300 PLAY=TRUE LOOP=TRUE QUALITY=HIGH></EMBED><!-- /CETagParser -->', $aStr);
      $aStr =& preg_replace('/\\[flash,\d+,\d+\\]([^\[]+)\\[\\/flash\\]/i', '<!-- CETagParser ~flash=\\3~ --><OBJECT CLASSID=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' WIDTH=\\1 HEIGHT=\\2><PARAM NAME=MOVIE VALUE=\\3><PARAM NAME=PLAY VALUE=TRUE><PARAM NAME=LOOP VALUE=TRUE><PARAM NAME=QUALITY VALUE=HIGH><EMBED SRC=\\3 WIDTH=\\1 HEIGHT=\\2 PLAY=TRUE LOOP=TRUE QUALITY=HIGH></EMBED></OBJECT><!-- /CETagParser -->', $aStr);
    }
    $this->_string =& $aStr;
    /***
     * extension
     */
    //if( !$haveLoadceTag ) {
      /***
       * load user defined ceTag
       */
      //loadUserSettings('ceTag');
      //$haveLoadceTag = true;
    //}
    //if($USER_SETTING['ceTag'][1]) {
    //  $aStr = preg_replace($USER_SETTING['ceTag'][1], $USER_SETTING['ceTag'][2], $aStr);
    //}
  }

  function encode(&$string) {
    return base64_encode($string);
  }

  function _replaceCensored(&$string) {
    return _replaceCensored($string);
  }


  function convertSmileTags() {
    static $smilingFaceCount, $smileTags, $smileImgs;

    include_once(DATA_PATH.'/settings/smile.inc.php');

    if(!empty($smileTags))
      $this->_string =& str_replace($smileTags, $smileImgs, $this->_string);
  }

  function shortenUrlTag($urlTags) {
    $str = substr($urlTags, 0, 25) . '......' . substr($urlTags, -25);
    return '[url='.$urlTags.']'.$str.'[/url]';
  }

  function convertCodesArea($codes) {
    $codes =& str_replace('\\"', '"', $codes);
    //$codes =& str_replace('<br />', '', $codes);
    $codes =& preg_replace("/(&#)([0-9]+)(;)/esiU", "chr(intval('\\2'))", $codes);
    $codes =& str_replace('  ', '&nbsp; ', $codes);
    $codes =& str_replace("\t", '&nbsp;&nbsp;', $codes);
    //return ('<TEXTAREA name=textfield rows="' .
    //       (($lines = substr_count($codes, "\n"))>20 ? 20 : ($lines+2)) .
    //       '" cols="80" class="code"><!-- /CETagParser -->' . $codes . '<!-- CETagParser ~/code~ --></textarea>');

      return ("<!-- CETagParser ~code~ --><br><table cellpadding=0 cellspacing=0 border=0 WIDTH=94% bgcolor='".SET_CODE_BORDER_COLOR."' align=center><tr><td><table width=100% cellpadding=5 cellspacing=1 border=0><TR><TD BGCOLOR='".SET_CODE_INNER_COLOR."'><!-- /CETagParser -->$codes<!-- CETagParser ~/code~ --></td></tr></table></td></tr></table><br><!-- /CETagParser -->");

  }


  /**
   *  possible feature suggested by deep
   */
  function _removeExcess($string) {
    if(!set_remove_excess) return $string;
    return preg_replace('/('.set_remove_charset.')\\1{'.(set_remove_excess-1).',}/i', str_repeat('\\1', set_remove_excess), $string);
  }


/**
 * keywords highlighting
 *  - diff keywords seperated by +
 */
function highlightingKeywords(&$string, $keywords) {
  static $keywordsPattern;

  if(strpos($keywords, '+')) {
    /**
     * multi-keywords
     */
    if(!$keywordsPattern) {
      $keywordsPattern = array();
      $keywordsList = explode('+', str_replace(' ', '', $keywords));
      foreach( $keywordsList as $keyword ) {
        if($keyword)
          $keywordsPattern[] = '|' . preg_quote($keyword) . '|';
      }
    }
    return preg_replace($keywordsPattern, '<font color=' . SET_HIGHLIGHT_COLOR . '>\\0</font>', $string);
  } else {
    /**
     * single one
     */
    return preg_replace(
                        ($keywordsPattern ? $keywordsPattern : '|' . preg_quote($keywords) . '|'),
                        '<font color=' . SET_HIGHLIGHT_COLOR . '>\\0</font>', $string);
  }
}

} // end of class 'celesteStringFactory'

?>