<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "header.php";

if($sendemail && $email){
    $admin=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin_id'"));
 mysql_query("INSERT INTO emails SET attatchments='$attatchments', type='1', message='$message', from_email='".$admin[email]."', from_name='".$admin[firstname]." ".$admin[lastname]."', to_name='$name', to_email='$email', subject='$subject', date='".time()."', priority='$priority'");
 echo '<script language="javascript">
       alert("Email in sending queue!");
       window.location="email_inbox.php?folder=sent";
 </script>';
 exit;
}

?>
<script language="JavaScript">
			<!--
			var dfgdfgfdg;
			function forentered(id){
                              if(id==1){
                              window.name='opener';
				dfgdfgfdg=window.open('pop_select_contact.php?','popupwinPUP','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                        }
                       }
					  
		function popFileList(){

                              window.name='opener';
				dfgdfgfdg=window.open('pop_file_list.php?','FileList','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                        
                       }


			//-->
</script>
<?


echo '<font face="'.$admin_font.'" size="2"><B>Compose new email</B><P>';
$admin=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin_id'"));
echo '<table><form name="newticket" action="compose.php" method="post">';

echo '<tr><td><font face="'.$admin_font.'" size="2">From: </td><td><font face="'.$admin_font.'" size="2">'.$admin[firstname].' '.$admin[lastname].' &lt;'.$admin[email].'&gt;</td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">To: </td><td><font face="'.$admin_font.'" size="2"><input size=40 type=text name=name value="'.$to_name.'"> &lt;<input type=text name=email value="'.$to.'" size=35>&gt; <a href="javascript: forentered(1)">Select Contact</a></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Subject: </td><td><input value="'.$subject.'" type=text name=subject size=45></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Attatchments: </td><td><input type=text name=attatchments size=55>&nbsp;<font size="2"><a class="left_menu" href="javascript: popFileList()">Select</a></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Priority: </td><td><select name="priority">
<option value=3>Normal</option>
<option value=2>High</option>
<option value=1>Highest</option></select></td></tr>';
echo '<tr><td>&nbsp;</td><td></td></tr>';

echo '<tr><td></td><td><textarea cols=60 rows=10 name=message>'."\n\n\n".$admin[signature].'</textarea></td></tr>';
echo '<tr><td></td><td><input type=submit name="sendemail" value="Send email now"></td></tr>';

echo '</table>';




include "footer.php";
?>
