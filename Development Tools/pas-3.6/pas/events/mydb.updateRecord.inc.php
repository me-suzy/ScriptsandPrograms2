<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

/**   Event Mydb.udateRecord
  * @modulegroup MyDB2
  *
  * Record the data from a Form.
  * All the variables recieved must be in the following format
  * $fields[fieldname] = Value
  * $doSave is a Inter event parameter. Any event executed before mydb.updateRecord
  * can set $doSave to no to stop the data from being saved in the database.
  * <br>- param array fields All the fields and there values
  * <br>- param string $primarykey contains the sql where statement to select the field to update.
  * <br>- param string table name of the table where to update
  * <br>- param string setmessage allow to customize the message var sent to the urlnext page.
  *
  * @package PASEvents
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004
  * @version 3.0
  */
/*
$strInsertError = "Erreur lors de la mise a jour de l'enregistrement ";
$strUpdateOk = "L'enregistrement a ete mit a jour" ;
 */
 
 global $strInsertError, $strUpdateOk, $strCancel, $strUpdateCancel;
 if (!isset($strInsertError)) {
     $strInsertError = "An error occured while updating the record";
 }
 if (!isset($strUpdateOk)) { 
     $strUpdateOk = "The record has been updated" ;
 }
 if (!isset($strCancel)) {
     $strCancel = "Cancel";
 }
 if (!isset($strUpdateCancel)) {
     $strUpdateCancel = "The Update of the record as been canceled"; 
 }
 
 $disp = new Display($goto) ;
if ($submitbutton != $strCancel) {
        reset($fields) ;
        
        //echo $doSave ;
        if ($doSave == "yes") {
        $primarykey = stripslashes($primarykey) ;
        $urlerror = $this->getMessagePage() ;
        $valuelist = '';
        while (list($key, $val) = each($fields)) {
                if($val != "null") $val = "'$val'";
                $valuelist .= "`$key` = $val, ";
        }
        $valuelist = ereg_replace(', $', '', $valuelist);
        $query = "UPDATE `$table` SET $valuelist WHERE $primarykey";
        $sql_query = $query;
        $qSaveData = new sqlQuery($dbc) ;
        $result = $qSaveData->query($query) ;
        //  $uniqid = $qSaveData->getInsertId() ; PL 20030516 not used
        if (!$result) {
                $error = $qSaveData->getError();
                $this->addParam("recordupdated", "no");
                $disp->setPage($urlerror) ;
                $disp->addParam("message", $strInsertError.$error) ;
                $this->setDisplayNext($disp);
        } else {
                $disp->setPage(urldecode($goto)) ;
                if (strlen($setmessage) > 0) {
                $strUpdateOk = $setmessage;
                }
                $this->addParam("recordupdated", "yes");
                $disp->addParam("message", $strUpdateOk) ;
        //    $disp->addParam("updateid", $uniqid) ; ; PL 20030516 not used could be replace with value of primary key
                $disp->addParam("update", "yes") ;
                $this->setDisplayNext($disp) ;
        }
        $disp->save("displayUpdateRecord") ;
        }
} else {
        $disp->setPage(urldecode($goto)) ;
        $disp->addParam("message", $strUpdateCancel) ;
        $disp->addParam("update", "no") ;
        $this->setDisplayNext($disp) ;
}
?>
