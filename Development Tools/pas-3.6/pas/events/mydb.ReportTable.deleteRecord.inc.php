<?php 
$errormessage = "deprecate dont use it anymore";
exit();

// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**   Event mydb.ReportTable.deleteRecord
   * 
   * Request a confirmaton and then Delete a record from a table
   * <br>- param String table name of the table where the records will be added/edited or deleted
   * <br>- param String primarykey part of the sqlstatement required to query the updated or deleted record. 
   * 	 
   * @package PASEvents
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004 
   * @version %g%%i%
   */
  
  
  $strConfirm = "Are you sure you want to delete the record" ;
  $strYes = "Yes" ;
  $strNo = "No" ;
  global $PHP_SELF ;
  if ($submityes == $strYes) {
    $qdelete = new sqlQuery($dbc) ;
    $qdelete->query("delete from $table where $primarykey") ;
    $goto = base64_decode($goto) ;
    $this->setUrlNext($goto) ; 
  } elseif ($submitno == $strNo) {
    $goto = base64_decode($goto) ;
    $this->setUrlNext($goto) ;  
  } else {
    // built confirm message
    $goto = $goto."?orderdir=".$orderdir."&orderfield=".$orderfield."&recordpos=".$recordpos ;
    $goto = base64_encode($goto) ;
    $message = $strConfirm."<br><form action=\"$PHP_SELF\" method=\"POST\">" ;
    $message .= "<input type=\"hidden\" name=\"mydb_events[]\" value=\"mydb.ReportTable.deleteRecord\">" ;
    $message .= "<input type=\"hidden\" name=\"goto\" value=\"$goto\"> ";
    $message .= "<input type=\"hidden\" name=\"primarykey\" value=\"".stripslashes($primarykey)."\">" ;
    $message .= "<input type=\"hidden\" name=\"table\" value=\"$table\"> ";
    $message .= "<input type=\"submit\" name=\"submityes\" value=\"$strYes\"> " ;
    $message .= "<input type=\"submit\" name=\"submitno\" value=\"$strNo\"> " ;
    $urlgo = $this->getMessagePage()."?message=".urlencode($message) ;
    $this->setUrlNext($urlgo) ;
  }
    
?>
