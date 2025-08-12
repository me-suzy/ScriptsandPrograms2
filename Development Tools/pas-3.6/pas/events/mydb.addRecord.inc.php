<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

/**   Event Mydb.addRecord
  *
  * Record the data from a Form.
  * All the variables recieved must be in the following format
  * <br>$fields[fieldname] = Value
  * <br>$doSave is a Inter event parameter. Any event executed before mydb.addRecord
  * can set $doSave to no to stop the data from being saved in the database.
  * Every event executed after this one can check if the record has been inserted by looking at the
  * inter event param: recordinserted (yes/no).
  * <br>- param array fields All the fields and there values
  * <br>- param string table name of the table where to insert
  * <br>- param string setmessage allow to customize the message var sent to the urlnext page.
  * Optional :
  * <br>- param string errorpage page where to display the error message
  *
  * @package PASEvents
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004
  * @version 3.0  * @copyright SQLFusion
  */
/*
$strInsertError = "Erreur lors de l'insertion de l'enregistrement ";
$strInsertOk = "L'enregistrement a ete inserer " ;
 */
global $strInsertError, $strInsertOk, $strAddCancel, $strCancel;
if (!isset($strInsertError)) {
    $strInsertError = "Error while inserting the record ";
}
if (!isset($strInsertOk)) {
    $strInsertOk = "The record has been inserted " ;
}
if (!isset($strAddCancel)) {
$strAddCancel = "The insertion of the record as been canceled"; 
}
if (!isset($strCancel)) {
$strCancel = "Cancel";
}

if (strlen($errorpage)>0) {
    $urlerror = $errorpage;
} else {
    $urlerror = $this->getMessagePage() ;
}
$disp = new Display($goto) ;
if ($submitbutton != $strCancel)  {
        if ($doSave == "yes") {    
        $fieldlist = '';
        $valuelist = '';
        $qGetFields = new sqlQuery($this->dbc) ;
        $qGetFields->setTable($table) ;
        $tableFields = $qGetFields->getTableField() ;
        
        while (list($key, $fieldname) = each($tableFields)) {
                if (strlen($fields[$fieldname])>0) {
                        $fieldlist .= "`$fieldname`, ";
                        if ($fields[$fieldname] == "null") { 
                            $val = $fields[$fieldname]; 
                       // } elseif (is_numeric($fields[$fieldname])) {
                       //     $val = $fields[$fieldname]; 
                        } else {
                            $val = "'$fields[$fieldname]'";
                        }
                        $valuelist .= "$val, ";
                }
        }
        $fieldlist = ereg_replace(', $', '', $fieldlist);
        $valuelist = ereg_replace(', $', '', $valuelist);
        $query = "INSERT INTO `$table` ($fieldlist) VALUES ($valuelist)";
        $message = urlencode($strInsertOk) ;
        $sql_query = $query;
        $qSaveData = new sqlQuery($this->dbc) ;
        $result = $qSaveData->query($query) ;
        $uniqid = $qSaveData->getInsertId($table, "id".$table) ;
        $this->addParam("insertid", $uniqid);
        if (!$result) {
                $error = $qSaveData->getError();
                $this->addParam("recordinserted", "no");
                $disp->setPage($urlerror) ;
                $disp->addParam("message",$strInsertError.$error) ;
        } else {
                $disp->setPage($goto) ;
                if (strlen($setmessage) > 0) {
                $strInsertOk = $setmessage;
                }
                $this->addParam("recordinserted", "yes");
                $disp->addParam("message",$strInsertOk) ;
                $disp->addParam("insertid", $uniqid) ;
                $disp->addParam("updage", "no") ;
        }
        $this->setDisplayNext($disp) ;
        }
        $disp->save("displayAddRecord") ;
} else {
        $disp->setPage(urldecode($goto)) ;
        $disp->addParam("message", $strAddCancel) ;
        $disp->addParam("update", "no") ;
        $this->setDisplayNext($disp) ;
        
}
?>