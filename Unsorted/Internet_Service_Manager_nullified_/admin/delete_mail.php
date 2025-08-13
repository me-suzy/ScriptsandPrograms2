<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";
include "auth.php";
if($confirm){
  mysql_query("DELETE FROM emails WHERE id='$id'");
  header("Location: email_inbox.php?folder=$folder");

}else{
 include "header.php";
 echo '<font face="'.$admin_font.'" size="2">Are you sure you with to delete the message?<P>';
 
 echo '<a href="delete_mail.php?folder='.$folder.'&id='.$id.'&confirm=yes" class="left_menu">Yes</a> | <a href="email_inbox.php" class="left_menu">No</a>';

}

include "footer.php";
?>
