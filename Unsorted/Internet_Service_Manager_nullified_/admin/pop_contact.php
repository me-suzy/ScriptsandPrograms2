<HTML>
<HEAD>
<TITLE>Contact Details</TITLE>
</HEAD>
<BODY bgcolor="#efefef">
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
  $r=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='$contact_id'"));
  echo '<font face="'.$admin_font.'" size="2">';
  echo '<B>'.$r[firstname].' '.$r[lastname].' - '.$r[title].'</B><BR>';
  echo '<font size=1>';
                           $des=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$r[id]."'"));
                       if($des[primary_contact]==$r[id]){
                                                         echo ' (Primary Contact) <BR>';
                         }
  echo '</font><BR>';
  echo $r[email].'<BR>';
  echo 'Phone: '.$r[phone].'<BR>';
  echo 'Phone 2: '.$r[phone2].'<BR>';
  echo 'Address: '.nl2br($r[address]).'<BR><BR>';
  echo 'Comments: '.nl2br($r[comments]).'<BR><BR>';
  echo '<font size=1><a href="javascript: window.close()">Close Me</a></font>';
?>
</BODY>
</HTML>
