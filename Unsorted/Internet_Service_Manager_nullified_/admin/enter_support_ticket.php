<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="support"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.

include "header.php";



if($go){
 mysql_query("INSERT INTO support_tickets SET date='".time()."', name='$name', email='$email', priority='$priority', subject='$subject', allocated='$allocate'");
 echo '<script language="javascript">
       window.location="support.php";
 </script>';
}

echo '<font face="'.$admin_font.'" size="2"><B>Enter new support ticket..</b>';

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


			//-->
</script>
<?

echo '<P><table cellpadding="4">';
echo '<form name="newticket">';
echo '<tr><td width=20></td><td><font face="'.$admin_font.'" size="2">Enter request for: </td><td>
<select onChange=forentered(this.value)>
<option value="">Unknown Person</option>
<option value="1">Known Contact</option>
</select>
</td></tr>';

echo '<tr><td width=20></td><td><font face="'.$admin_font.'" size="2">Name: </td><td>
<input type=text name="name">
</td></tr>';

echo '<tr><td width=20></td><td><font face="'.$admin_font.'" size="2">Email: </td><td>
<input type=text name="email">
</td></tr>';

echo '<tr><td width=20></td><td><font face="'.$admin_font.'" size="2">Priority: </td><td>
<select name="priority">';
     foreach($support_priorities as $prid=>$prname){
                                 echo '<option value="'.$prid.'">'.$prname.'</option>';
     }
echo '</select>
</td></tr>';

echo '<tr><td width=20></td><td><font face="'.$admin_font.'" size="2">Subject: </td><td>
<input type=text name="subject" size=35>
</td></tr>';

echo '<tr><td width=20></td><td valign="top"><font face="'.$admin_font.'" size="2">Details: </td><td>
<textarea cols=40 rows=5 name="details"></textarea>
</td></tr>';

echo '<tr><td width=20></td><td valign="top"><font face="'.$admin_font.'" size="2">Allocate to: </td><td>
<select name=allocate><option value=0>-- All</option>';
      $suptechs=mysql_query("SELECT * FROM admins WHERE privelages LIKE '%support,%'");
             while($st=mysql_fetch_array($suptechs)){
                  $sel="";if($admin_id==$st[id]){$sel="SELECTED";}
                   echo '<option '.$sel.' value="'.$st[id].'">'.$st[firstname].' '.$st[lastname].'</option>';
             }
      echo '</select>
</td></tr></table><P>';

echo '<input type=submit name="go" value="Submit Support Ticket">';

echo '</form>';

include "footer.php";
?>
