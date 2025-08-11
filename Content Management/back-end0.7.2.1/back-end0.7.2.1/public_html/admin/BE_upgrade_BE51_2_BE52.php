<?php
    
   require("../config.php");
   require("../functions.php");
    
   // Back-End.org authorization
   if (!empty($backEndPath)) {
      require_once("$backEndPath");
      page_open(array("sess" => "slashSess", "auth" => "slashAuth", "perm" => "slashPerm"));
      if (!$perm->have_perm("story")) {
         echo "You not have permission to go there!";
         exit;
      }
   }
    
   if (empty($petitionID))
      $petitionID = '1';
    
    
   // Insert New INT fields for unix time stamp
   $q0a = "ALTER TABLE $PETmain ADD creationDateINT INT UNSIGNED AFTER creationDate";
   echo "<pre>$q0a</pre>";
   $get_signatures = mysql_query($q0a, $db) OR die(mysql_error());
    
   $q0b = "ALTER TABLE $PETdata ADD signedDateINT INT UNSIGNED AFTER signedDate";
   echo "<pre>$q0b</pre>";
   $get_signatures = mysql_query($q0b, $db) OR die(mysql_error());
    
   $q0c = "ALTER TABLE $PETdata ADD verifyDateINT INT UNSIGNED AFTER verifyDate";
   echo "<pre>$q0c</pre>";
   $get_signatures = mysql_query($q0c, $db) OR die(mysql_error());
    
   $q = "SELECT $PETmain.indID, $PETmain.creationDate, $PETmain.creationDateINT, $PETdata.signedDate, $PETdata.signedDateINT, $PETdata.verifyDate, $PETdata.verifyDateINT FROM $PETmain LEFT JOIN $PETdata USING (indID) ";
   echo "<pre>$q</pre>";
   $get_signatures = mysql_query($q, $db) OR die(mysql_error());
    
   while ($get_rows = mysql_fetch_array($get_signatures)) {
      $indID = $get_rows[indID];
      $creationDate = '';
      $signedDate = '';
      $verifyDate = '';
       
      if (empty($get_rows[creationDateINT]) AND !ereg("0000-00-00", $get_rows[creationDate])) {
         $creationDate = strtotime ($get_rows[creationDate]);
         $q1 = "UPDATE $PETmain SET creationDate = '$creationDate' WHERE indID='$indID'";
         // echo "<br /> $indID cd " . $get_rows[creationDate] . " => $creationDate";
         // echo "<pre>$q1</pre>";
         $update1 = mysql_query($q1, $db) OR die(mysql_error());
      }
       
      // Valid Dates Signed & Verified Columns
      if (empty($get_rows[signedDateINT]) AND empty($get_rows[verifyDateINT]) AND !ereg("0000-00-00", $get_rows[signedDate]) AND !ereg("0000-00-00", $get_rows[verifyDate])) {
         $signedDate = strtotime ($get_rows[signedDate]);
         $verifyDate = strtotime ($get_rows[verifyDate]);
         $q2 = "UPDATE $PETdata SET signedDateINT = '$signedDate', verifyDateINT = '$verifyDate'  WHERE indID='$indID' AND petitionID='$petitionID' ";
         // echo "<br />$indID sd vd " . $get_rows[signedDate] . " => $signedDate | " . $get_rows[verifyDate] . " => $verifyDate";
         // echo "<pre>$q2</pre>";
         $update2 = mysql_query($q2, $db) OR die(mysql_error());
          
         // Valid Signed Date
      } elseif (empty($get_rows[signedDateINT]) AND !ereg("0000-00-00", $get_rows[verifyDate]) AND ereg("0000-00-00", $get_rows[signedDate])) {
         $verifyDate = strtotime ($get_rows[verifyDate]);
         $q3 = "UPDATE $PETdata SET verifyDateINT = '$verifyDate'  WHERE indID='$indID' AND petitionID='$petitionID' ";
         // echo "<br />$indID vd " . $get_rows[verifyDate] . " => $verifyDate";
         // echo "<pre>$q3</pre>";
         $update3 = mysql_query($q3, $db) OR die(mysql_error());
          
         // Valid Verified Date
      } elseif (empty($get_rows[verifyDateINT]) AND ereg("0000-00-00", $get_rows[verifyDate]) AND !ereg("0000-00-00", $get_rows[signedDate])) {
         $signedDate = strtotime ($get_rows[signedDate]);
         $q4 = "UPDATE $PETdata SET signedDateINT = '$signedDate'  WHERE indID='$indID' AND petitionID='$petitionID' ";
         // echo "<br />$indID sd " . $get_rows[signedDate] . " => $signedDate";
         // echo "<pre>$q4</pre>";
         $update4 = mysql_query($q4, $db) OR die(mysql_error());
          
         // Error
      } else {
         $signedDate = strtotime ($get_rows[signedDate]);
         $verifyDate = strtotime ($get_rows[verifyDate]);
         echo "<br />$indID ???? " . $get_rows[signedDate] . " => $signedDate | " . $get_rows[verifyDate] . " => $verifyDate";
      }
       
      echo " . ";
   }
    
   // Move Date->DateOld
   $q5a = "ALTER TABLE $PETmain CHANGE `creationDate` `creationDateOld` DATE DEFAULT '0000-00-00' NOT NULL";
   echo "<pre>$q5a</pre>";
   $get_signatures = mysql_query($q5a, $db);
    
   $q5b = "ALTER TABLE $PETdata CHANGE `signedDate` `signedDateOld` DATE DEFAULT '0000-00-00' NOT NULL";
   echo "<pre>$q5b</pre>";
   $get_signatures = mysql_query($q5b, $db);
    
   $q5c = "ALTER TABLE $PETdata CHANGE `verifyDate` `verifyDateOld` DATE DEFAULT '0000-00-00' NOT NULL";
   echo "<pre>$q5c</pre>";
   $get_signatures = mysql_query($q5c, $db);
    
   // Move DateInt->Date
   $q5d = "ALTER TABLE $PETmain CHANGE `creationDateINT` `creationDate` INT UNSIGNED";
   echo "<pre>$q5d</pre>";
   $get_signatures = mysql_query($q5d, $db);
    
   $q5e = "ALTER TABLE $PETdata CHANGE `signedDateINT` `signedDate` INT UNSIGNED";
   echo "<pre>$q5e</pre>";
   $get_signatures = mysql_query($q5e, $db);
    
   $q5f = "ALTER TABLE $PETdata CHANGE `verifyDateINT` `verifyDate` INT UNSIGNED";
   echo "<pre>$q5f</pre>";
   $get_signatures = mysql_query($q5f, $db);
    
?>
