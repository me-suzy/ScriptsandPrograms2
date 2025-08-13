<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="editclients"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.
include "header.php";

if($SAVEUPDATES){
 mysql_query("INSERT INTO clients SET comments='$comment', billing_method='$billing_method', name='$name', date_added='".time()."'");
 echo '<script language="javascript">
 window.location="add_contact.php?new=1&client_id='.mysql_insert_id().'"
 </script>';
}

echo '<form action="add_client.php" method="post">';
echo '<font face="'.$admin_font.'" size="2"><B>You are adding a client!</B><P>';
?>
<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>" size="2">Client
        Name: </font></div>
    </td>
    <td width="168">
      <input type="text" name="name">
    </td>
  </tr>

  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Billing
        Method: </font></font></font></div>
    </td>
    <td width="168">
      <select name="billing_method">
           <?
            foreach($billing_methods as $meth){
                                     if($meth==$res[billing_method]){
                                     echo '<option value="'.$meth.'" SELECTED>'.$meth.'</option>';
                                     }else{
                                     echo '<option value="'.$meth.'">'.$meth.'</option>';
                                     }
            }
           ?>
      </select>

    </td>
  </tr>

  <tr>
    <td width="65">&nbsp;</td>
    <td width="267" valign="top">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Comments:
        </font></font></font></div>
    </td>
    <td width="168">
      <textarea name="comments" cols="40" rows="5"></textarea>
    </td>
  </tr>
</table>

<P><table width=100%>
<tr><td width=50 height="1" bgcolor="<?echo $admin_color_2;?>"></td></tr></table>
<input type=submit name="SAVEUPDATES" value="SAVE IT NOW!"></form>
<?
include "footer.php";
?>
