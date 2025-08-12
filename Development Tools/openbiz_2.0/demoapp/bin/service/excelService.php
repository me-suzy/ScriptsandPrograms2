<?php
/**
 * @package PluginService
 */
/**
 * excelService - 
 * class excelService is the plug-in service of printing bizform to excel 
 * 
 * @package PluginService
 * @author rocky swen 
 * @copyright Copyright (c) 2003
 * @version $Id$
 * @access public 
 */
class excelService
{
   /**
    * excelService::excelService()
    * 
    * @param void
    * @return void 
    */
   function excelService() {}

   /**
    * excelService::renderHTML() - render the excel output with HTML format
    * 
    * @param string $objname object name which is the bizform name
    * @return void 
    */
   function renderHTML($objname)
   {
      header("Content-Type: application/vnd.ms-excel");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    
      global $g_BizSystem;  
      // get the current UI bizobj
      $bizform = $g_BizSystem->GetObjectFactory()->GetObject($objname);    // get the existing bizform object 
      $bizobj = $bizform->GetDataObj();

      echo "<table>";
      echo "<tr>";
      $keylist = $bizform->m_RecordRow->GetSortControlKeys();
      foreach($keylist as $key) {
         echo "<td>".$bizform->GetControl($key)->m_DisplayName."</td>";
      }
      echo "</tr>";
      $oldCacheMode = $bizobj->GetCacheMode(); 
      $bizobj->SetCacheMode(0);    // turn off cache mode, not affect the current cache
      $bizobj->RunSearch(-1);  // don't use page search
      while (1)
      {
        $recArray = $bizobj->GetRecord(1);
        if (!$recArray) break;
        echo "<tr>";
        foreach($keylist as $key) {
           echo "<td>".$recArray[$bizform->GetControl($key)->m_BizFieldName]."</td>";
        }
        echo "</tr>";
      }
      $bizobj->SetCacheMode($oldCacheMode); 
      echo "</table>";
   }
   
   /**
    * excelService::renderCSV() - render the excel output with CSV format
    * 
    * @param string $objname object name which is the bizform name
    * @return void 
    */
   function renderCSV($objname)
   {
      header("Content-Type: application/vnd.ms-excel");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      
      global $g_BizSystem;  
      // get the current UI bizobj
      $bizform = $g_BizSystem->GetObjectFactory()->GetObject($objname);    // get the existing bizform object 
      $bizobj = $bizform->GetDataObj();

      $keylist = $bizform->m_RecordRow->GetSortControlKeys();
      $line = "";
      foreach($keylist as $key) {
         $line .= $bizform->GetControl($key)->m_DisplayName."\t";
      }
      echo trim($line)."\n";
      
      $bizobj->SetCacheMode(0);    // turn off cache mode, not affect the current cache
      $bizobj->RunSearch(-1);  // don't use page search
      while (1)
      {
        $recArray = $bizobj->GetRecord(1);
        if (!$recArray) break;
        $line = "";
        foreach($keylist as $key) {
           $line .= $recArray[$bizform->GetControl($key)->m_BizFieldName]."\t";
        }
        echo trim($line)."\n";
      }
      $bizobj->SetCacheMode(1); 
   }
}

?>