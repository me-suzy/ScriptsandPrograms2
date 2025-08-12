<?php 
$errormessage = "deprecate dont use it anymore";
exit();

// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**   Event mydb.ReportTable.editRecord
   * 
   * Send the update request to the formrecordedit.php page.
   * 	 
   * <br>- <b>param</b> String formpage name of the page containing the form by default formrecordedit.php
   * <br>- <b>param</b> String goto page to display after executing the form
   * <br>- <b>param</b> String table name of the table where the records will be added/edited or deleted
   * <br>- <b>param</b> String primarykey part of the sqlstatement required to query the updated or deleted record.  
   *	 
   * @package PASEvents
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0   
   * @version %g%%i%
   */ 

  $strMissingArgument = "Error coudn't edit the record, missing arguments" ;
   
  if(strlen($goto)>0 && strlen($formpage)>0 && strlen($primarykey)>0) {
    $urlnext = $goto."?orderdir=".$orderdir."&orderfield=".$orderfield."&recordpos=".$recordpos ;
    $goto = urlencode($urlnext) ;
    $url = $formpage."?primarykey=".urlencode(stripslashes($primarykey))."&table=$table&goto=".$goto ;
    $this->setUrlNext($url) ;
  } else {
    $errorurl = $this->getMessagePage()."?message=".$strMissingArgument ;
  }

?>
