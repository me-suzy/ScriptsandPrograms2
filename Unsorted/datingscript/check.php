<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               check.php                        #
# File purpose            Check for add user to DB         #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
include_once 'templates/'.C_TEMP.'/header.php';

if(!isset($id)||!isset($code)||!is_numeric($id)||!ereg('^[0-9A-Za-z]',$code)) { 
// Code must content only chars and numbers :)
   printm($w[1].'3');
   }

/// Check for users with code expired and remove this users
$temp=mysql_query("SELECT id FROM ".C_MYSQL_TEMP." WHERE date < DATE_SUB(NOW(), INTERVAL ".C_REG_DAYS." DAY)");
while($i=mysql_fetch_array($temp)) {
if(isset($i['id']) && is_numeric($i['id'])) {
   $tmp=mysql_query("SELECT pic1, pic2, pic3 FROM ".C_MYSQL_MEMBERS." WHERE id='".$i['id']."'");
    while($j=mysql_fetch_array($tmp)) {
         for($k=1;$k<=3;$k++) $tmpm='pic'.$k;   
         if(($j[$tmpm] != '') && (is_file(C_PATH.'/members/uploads/'.$j[$tmpm]))) unlink (C_PATH.'/members/uploads/'.$j[$tmpm]);
    }
}
mysql_query("DELETE FROM ".C_MYSQL_MEMBERS." WHERE id='".$i['id']."'") or die(mysql_error());
mysql_query("DELETE FROM ".C_MYSQL_TEMP." WHERE id='".$i['id']."'") or die(mysql_error());
}
/////////////


   
$temp=mysql_query("SELECT count(*) as total FROM ".C_MYSQL_TEMP." WHERE id='".$id."' AND code='".$code."' AND date > DATE_SUB(NOW(), INTERVAL ".C_REG_DAYS." DAY)");
$row=mysql_fetch_array($temp);
$count = $row['total'];
if($count != '0') {
   mysql_query("DELETE FROM ".C_MYSQL_TEMP." WHERE id='".$id."' AND code='".$code."'");
   if(C_CHECK_REGISTER == '3') {
      $status='1';
      $str=$w[159];
   } 
   else {
      $status='7';
      $w[46];
   } 
mysql_query("UPDATE ".C_MYSQL_MEMBERS." SET status='".$status."' WHERE id='".$id."'");
$result = mysql_query('SELECT email, password FROM '.C_MYSQL_MEMBERS.' WHERE id = \''.$id.'\'');
   while($i=mysql_fetch_array($result)) {
$tm=array(C_SNAME);
$subject=template($w[195],$tm);
switch (C_ID) {
  
  case '2':
    $sendid=$i['email'];
  break;
  default: 
    $sendid=$id;
  break;
  }
$tm=array($sendid,$i['password'],C_SNAME);
$message=template($w[197],$tm);
sendmail(C_FROMM,$i['email'],$subject,$message,'text');
}
printm($str.$sendid);
}

else {
     unset($id);
     printm($w[161]);
}
include_once 'templates/'.C_TEMP.'/footer.php';
?>