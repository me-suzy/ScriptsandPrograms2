<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>

<?php
// Include Configuration file
include_once($DOCUMENT_ROOT . "/includes/config.inc.php");

$popup="window.open";

  if ($killer) {
     foreach ($clean_delete as $key => $value) {
      $Query = "DELETE FROM tblTgp WHERE id='$key'";
         $result = mysql_query($Query, $conn);
     }
  die("<h2><center>Files Deleted</center></h2>");
  }

if ($choice) {
   $query = "select * from tblTgp WHERE category='$choice' AND accept='yes' ORDER BY date DESC";

   $result = mysql_query ($query)
           or die ("Query failed");
}

if ($result) {
echo "<center><a href=\"check.list.php\">Return to Check List</a><br>";
echo "<form name=\"form1\" method=\"post\" action=\"$PHP_SELF\">";
echo "<table width=100% border=1 cellspacing=0 cellpadding=0>";
echo "<tr><td colspan=6 bgcolor =\"$bgcolor\"><div align=\"center\"><h2>Comus Link Check Results</h2></div></td></tr>";
echo "<tr><td width=72><b>URL</b></td>
      <td width=72><b>Recip</b></td>
	  <td width=72><b>Bad Word</b></td>
      <td width=72><b>Pop-up</b></td>
      <td width=72><b>Date</b></td>
      <td width=20><b>Delete</b></td>
      </tr>";


         while ($r = mysql_fetch_array($result)) { 

            $url = $r["url"];
            $date = $r["date"];
            $id =  $r["id"];
         
         $open = @fopen("$url", "r");
               if(!$open){ 
                  $msg1 = "404";
         }else{ // else 1
               
               $read = fread($open, 1500);
               fclose($open);
               
               $recipcheck= substr_count($read, "$recip");
            
               if(!$recipcheck){
                  $msg1 = "<font color=red>No</font>";
               }else{
                  $msg1 = "Yes";
               }
               $msg2 = 'clean';
               $msg3 = 'clean';
             if ($badwordcheck == 'Yes'){
                  $ckbad = explode(",", "$badword");
                  while(list($v) = each($ckbad)){
                  $ckbad[$v] = trim($ckbad[$v]);
                  $badcheck= substr_count($read, "$ckbad[$v]");
                     if($badcheck){
                     $msg2 = "<font color=red>badword</font>"; 
                     }
                  }
             }
             if ($popcheck == 'No'){
                  $badpop = substr_count($read, "$popup");
                     if($badpop){
                        $msg3 = "<font color=red>Popup</red>";
                     }
             }        
        } // end else 1
      echo "<tr><td width=450>";
   echo "<a href=\"$url\">$url</a>";
   echo "</td>
      <td width=72>$msg1&nbsp</td>
      <td width=72>$msg2&nbsp</td>
      <td width=72>$msg3&nbsp</td>
      <td width=72>$date&nbsp</td>
      <td width=20><input type=\"checkbox\" name=\"clean_delete[$id]\" value=\"checkbox\"></td>
      </tr>";
      } // end while loop
      
   } // end result if
echo "<tr> 
    <td colspan=6><div align=\"center\">
        <input type=\"submit\" name=\"killer\" value=\"Delete Posts\">
      </div></td>
        </tr></table>";
echo "</form>";
?>

