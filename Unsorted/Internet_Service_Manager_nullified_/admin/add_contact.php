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

if($delete){
 mysql_query("DELETE FROM contacts WHERE id='$delete'");
             echo '<script language="javascript">
                 window.location="client_list.php?client_id='.$client_id.'";
            </script>';
            exit;
}


if($SAVEUPDATES){
            mysql_query("INSERT INTO contacts SET client_id='$client_id', firstname='".$co_firstname."',lastname='".$co_lastname."',email='".$co_email."', phone='".$co_phone."', phone2='".$co_phone2."', address='".$co_address."', username='".$co_username."', password='".$co_password."', comments='".$co_comment."', title='".$co_title."'");
            $res=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
            if(!mysql_num_rows(mysql_query("SELECT * FROM contacts WHERE id='".$res[primary_contact]."'"))){
                 //there is no valid primary contact so well make this one it!
                 mysql_query("UPDATE clients SET primary_contact='".mysql_insert_id()."' WHERE id='$client_id'");
            }
            echo '<script language="javascript">
                 window.location="client_list.php?client_id='.$client_id.'";
            </script>';
}

    $res=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
  if($res[account_balance]<=0){$res[account_balance]=0;}
  echo '<table width="629" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td colspan="4"><font face="'.$admin_font.'" size="2"><b>Client
      Profile: '.$res[name].' (Balance: '.$payment_unit.$res[account_balance].')</b></font></td>

    <td width="130">&nbsp;</td>
    <td width="120">&nbsp;</td>
    <td width="115">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" height="3"></td>
  </tr>
  <tr bgcolor="#006699">
        <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="client_list.php?client_id='.$client_id.'" class="other_text">Profile</a> </div>
    </td>
    <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="billing_overview.php?client_id='.$client_id.'" class="other_text">Billing</a> </div>
    </td>
    <td width="125">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_project.php?client_id='.$client_id.'" class="other_text">Add Project</a></div>
    </td>
    <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="edit_client.php?client_id='.$client_id.'" class="other_text">Edit Client</a></div>
    </td>
    <td width="120">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="support_overview.php?client_id='.$client_id.'" class="other_text">Support</a></div>
      </td>
      <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_contact.php?client_id='.$client_id.'" class="other_text">Add Contact</a></div>
    </td>
  </tr>
  <tr>
    <td width="115">&nbsp;</td>
    <td width="125">&nbsp;</td>
    <td width="130">&nbsp;</td>
    <td width="120">&nbsp;</td>
    <td width="115">&nbsp;</td>
  </tr>
</table>
';

echo '<form action="add_contact.php?client_id='.$res[id].'" method="post">';
if($new){
 echo '<B><font face="'.$admin_font.'" size="2">Add primary contact for new client, you can add more later!<P>';
}else{
 echo '<B><font face="'.$admin_font.'" size="2">Add additonal contacts for client...<P>';
}
?>
  <a name="client<?echo $con[id];?>"></a>
    <table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>" size="2">Firstname:
        </font></div>
    </td>
    <td width="168">
      <input type="text" name="co_firstname">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">
        Lastname: </font></font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_lastname">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Email:
        </font></font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_email">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Job
        Title : </font></font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_title">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Address:
        </font></font></div>
    </td>
    <td width="168">
      <textarea name="co_address" cols="40" rows="5"></textarea>
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>" size="2">Primary
        Phone: </font></div>
    </td>
    <td width="168">
      <input type="text" name="co_phone">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>" size="2">Secondary
        Phone: </font></div>
    </td>
    <td width="168">
      <input type="text" name="co_phone2">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Username:
        </font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_username">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Password:
        </font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_password">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267" valign="top">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Comments:
        </font></font></div>
    </td>
    <td width="168">
      <textarea name="co_comment" cols="40" rows="5"></textarea>
    </td>
  </tr>
</table>
<table width=80%><tr><td width=50 height="1" bgcolor="<?echo $admin_color_2;?>"></td></tr></table>
<?






echo '<input type=submit name="SAVEUPDATES" value="SAVE IT NOW!"></form>';
include "footer.php";
?>
