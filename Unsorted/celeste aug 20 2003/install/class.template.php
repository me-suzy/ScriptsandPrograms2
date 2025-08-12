<?php
/**
 * Project Source File
 * Celeste V2003
 * Jun 28, 2003
 * Celeste Dev Team - Lvxing / Xinshi
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

Class celesteTemplate {

  var $table;
  var $root    = '';
  var $preloads = array();
  var $template = array();
  var $compress = 0;
  var $c = '';

  // global vars
  var $varkeys  = array();
  var $varvals  = array();
  var $d;

  var $_varkeys = array();
  var $_varvals = array();
  
  function celesteTemplate($compress = 0, $table = 'celeste_template') {
  	global $DB, $thisprog;
    $this->table = $table;
    $this->compress = $compress;
    $this->d =& $DB;
    $this->c = str_replace('::', '', $thisprog);
  }

  function preLoad($templateName) {
    if(is_array($templateName)) {
      foreach ( $templateName as $eachTemplateName ) {
        $this->preloads[$eachTemplateName] = $eachTemplateName;
        $this->c .= substr($eachTemplateName, 0, 1);
      }
    } else {
      $this->preloads[$templateName] = $templateName;
      $this->c .= substr($templateName, 0, 1);
    }
  }
  
  function setRoot(&$Objectname) {
    $this->root =& $Objectname;
  }
  
  function retrieve() {
    if(!count($this->preloads)) return;
    if (SET_USE_TEMPLATE_CACHE && cacheExists($this->c.'_tpl')) 
      $this->template =& getCache($this->c.'_tpl', 1);
    else {
      $rs = $this->d->query(sprintf(
        "SELECT DISTINCT name,template FROM %s where (name IN ('%s'))",
        $this->table, implode("','", $this->preloads)));

      while($rs->next_record())
        $this->template[$rs->get('name')] =& str_replace($this->_varkeys, $this->_varvals, $rs->get('template'));

      $rs->free();
      !SET_USE_TEMPLATE_CACHE || storeCache($this->c.'_tpl', $this->template, 1);
    }
  }

  function get($templateName) {
  	if (empty($this->template[$templateName])) { 
      die('Template Error: Template (\''.$templateName.'\') Not loaded'); 
      }
    return new templateElement($this->template[$templateName]);
  }

  function getException($templateName) {
  	if (empty($this->template['exception_'.$templateName])) { 
      if (SET_USE_TEMPLATE_CACHE) $this->template['exception_'.$templateName] =  $this->d->result("SELECT template FROM ". $this->table." where name = 'exception_".$templateName."'");
        if (empty($this->template['exception_'.$templateName])) die('Template Error: Template (\''.$templateName.'\') Not loaded'); 
      //print("SELECT template FROM ". $this->table." where name = 'exception_".$templateName."'");
    }
    return $this->template['exception_'.$templateName];
  }
  
  function getString($templateName) {
    return $this->template[$templateName];
  }
  
  function set($varname, $value = '') {
    //$value = preg_replace('/{([^ \t\r\n}]+)}/', "{ \\1 }", $value);
    $this->varkeys[$varname] = '{'.$varname.'}';
    $this->varvals[$varname] =& $value;
  }

  function setGlobal($varname, $value = '') {
    //$value = preg_replace('/{([^ \t\r\n}]+)}/', "{ \\1 }", $value);
    $this->_varkeys[$varname] = '{'.$varname.'}';
    $this->_varvals[$varname] =& $value;
  }

  function pparse($append = false) {
  	global $celeste;

    //Celeste_end_handle();
    if(SET_BENCH_TIME) {
      $foot =& $this->get('footer');
      $foot->set('bench', $celeste->timer->benchmark());
      $this->root->set('footer', $foot->parse());
    } else {
      $root->set('footer', $this->getString('footer'));
    }
    $content =& $this->root->parse();
    $content =& str_replace('{ ', '{', $content);

	//header ('Cache-Control: no-cache, must-revalidate');
	//header ('Pragma: no-cache');

    global $HTTP_ACCEPT_ENCODING;

    $gzip_pos = strpos($HTTP_ACCEPT_ENCODING, 'gzip');
    if(!($this->compress && $gzip_pos!==false && ($gzip_pos - strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') != 2)
       && function_exists("crc32") and function_exists("gzcompress")))
      echo $content;
    else
    {

      header('Content-Encoding: gzip');

      $content .= $bench;
      $content .= '<br><center>Celeste Template Engine Info: Gzip <b>Enabled</b> [ Level '.$this->compress.' ]</center>';
      print pack('cccccccc',0x1f,0x8b,0x08,0x00,0x00,0x00,0x00,0x00);
      $Size = strlen($content);
      $Crc = crc32($content);
      $content = gzcompress($content, $this->compress);
      $content = substr($content, 0, strlen($content) - 4);
      print $content.pack('V',$Crc).pack('V',$Size);
    }
    flush();
  }

}


Class templateElement {
  var $varkeys  = array();
  var $varvals  = array();
  var $template = null;
  var $final    = null; //public
  var $additionScope = null;

  function templateElement(&$template) {
    $this->template =& $template;
  }
  function getContent() {
    return $this->final;
  }
  function set($varname, $value = '') {

    //$value = preg_replace('/{([^ \t\r\n}]+)}/', "{ \\1 }", $value);

    $this->varkeys[$varname] = '{'.$varname.'}';
    $this->varvals[$varname] =& $value;
  }

  function setarray(&$array) {
    foreach ( $array as $var => $value ) {
      $this->varkeys[$var] = '{'.$var.'}';
      $this->varvals[$var] = $value;
    }
  }

  function parseBlock($blockName, &$block) {
    $this->set($blockName, $block->final);
  }
  
  function appendChild(&$child) {
    $child->addVarScope( $this );
  }

  function addVarScope(&$sc) {
    if (!is_array($this->additionScope)) {
      $this->additionScope = array();
    }
    $this->additionScope[] =& $sc;
  }
    
  function get($varname) {
    return $this->varvals[$varname];
  }

  function parse($append = false) {
    ($append) ? $this->final .= $this->subst() : $this->final =& $this->subst();
    $this->final = preg_replace('/{[^ \<\>\t\r\n}]+}/', '', $this->final);
    return $this->final;
  }

  function subst() {
  	global $t;

    $tmp = str_replace($this->varkeys, $this->varvals, $this->template);
    $tmp = str_replace($t->varkeys, $t->varvals, $tmp);

    if (is_array($this->additionScope)) {
      foreach($this->additionScope as $obj)
      $tmp = str_replace($obj->varkeys, $obj->varvals, $tmp);
    }
    return $tmp;
  }
}

//print "Init template";
Global $t, $user;
$t = new celesteTemplate(SET_GZIP_LEVEL, SET_TEMPLATE_TABLE);
$t->setGlobal('_title', SET_TITLE);
$t->setGlobal('_width', SET_TABLE_WIDTH);
$t->setGlobal('_pagebgc',SET_BG_COLOR);
$t->setGlobal('_borderc',SET_BORDER_COLOR);
$t->setGlobal('_topicrowc',SET_TOPICROW_COLOR);
$t->setGlobal('_caterowc',SET_CATEROW_COLOR);
$t->setGlobal('_mainc1',SET_MAIN_COLOR1);
$t->setGlobal('_mainc2',SET_MAIN_COLOR2);
$t->setGlobal('_inc', SET_INNER_COLOR);

if ($user->properties['readmode']) {
  $t->set('_readMode', ($user->properties['readmode']==1 ? 'flat' : 'threaded'));
} else {
  $t->set('_readMode', SET_DEFAULT_READMODE);
}
