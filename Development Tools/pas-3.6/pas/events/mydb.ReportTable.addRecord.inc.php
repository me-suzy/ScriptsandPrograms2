<?php 
$errormessage = "deprecate dont use it anymore";
exit();

// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**   Event Mydb.ReportTable.addRecord
   *  Event Mydb.ReportTable.addRecord
   *  This event redirect to the page with a form so the user can enter the new record.
   *  Event Mydb.ReportTable.addRecord
   * @package PASEvents
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0     
   */
  /**
  *  Test of doc for module
  */

  $strMissingArgument = "Error coudn't add the record, missing arguments" ;
   
  if(strlen($goto)>0 && strlen($formpage)>0 && strlen($table)>0) {
    if (ereg("\?", $goto)) { $sep = "&"; } else { $sep="?";}
    $urlnext = $goto.$sep."orderdir=".$orderdir."&orderfield=".$orderfield."&recordpos=".$recordpos."&addrecord=yes" ;
    $goto = urlencode($urlnext) ;
    $url = $formpage."?table=$table&addrecord=yes&goto=".$goto ;
    $this->setUrlNext($url) ;
  } else {
    $errorurl = $this->getMessagePage()."?message=".$strMissingArgument ;
  }

?>
