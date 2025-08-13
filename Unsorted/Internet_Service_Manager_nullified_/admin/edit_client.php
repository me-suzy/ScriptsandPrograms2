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
   //update the master client db..
   mysql_query("UPDATE clients SET bill_to_contact='$cl_bill_to_contact', primary_contact='$cl_primary_contact', name='$cl_name', billing_method='$cl_billing_method', comments='$cl_comments' WHERE id='$client_id'");

   //now the clients contacts!
   foreach($co_control as $id){
            mysql_query("UPDATE contacts SET firstname='".$co_firstname[$id]."',lastname='".$co_lastname[$id]."',email='".$co_email[$id]."', phone='".$co_phone[$id]."', phone2='".$co_phone2[$id]."', address='".$co_address[$id]."', username='".$co_username[$id]."', password='".$co_password[$id]."', comments='".$co_comment[$id]."', title='".$co_title[$id]."' WHERE id='$id'");
   }
   
      echo '<script language="javascript">
                 window.location="client_list.php?client_id='.$client_id.'";
            </script>';
}

if($contact){
    $ces=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='$contact'"));
    $client_id=$ces[client_id];
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
echo '<form action="edit_client.php?client_id='.$res[id].'&contact='.$contact.'" method="post">';
echo '<font face="'.$admin_font.'" size="2">You are editing the client profile of <B>'.$res[name].':</B><P>';
?>
<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>" size="2">Client
        Name: </font></div>
    </td>
    <td width="168">
      <input type="text" name="cl_name" value="<?echo $res[name];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">
        Bill To: </font></font></font></div>
    </td>
    <td width="168">
    <select name="cl_bill_to_contact">
    <?
      $all_contacts=mysql_query("SELECT * FROM contacts WHERE client_id='".$res[id]."' ORDER BY firstname");
      while($con=mysql_fetch_array($all_contacts)){
                if($con[id]==$res[bill_to_contact]){
                 echo '<option value="'.$con[id].'" selected>'.$con[firstname].' '.$con[lastname].' ('.$con[title].')</option>';
                 }else{
                 echo '<option value="'.$con[id].'">'.$con[firstname].' '.$con[lastname].' ('.$con[title].')</option>';
               }
      }

      
    ?>
    </select>
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Billing
        Method: </font></font></font></div>
    </td>
    <td width="168">
      <select name="cl_billing_method">
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
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Primary
        Contact: </font></font></font></div>
    </td>
    <td width="168">
         <select name="cl_primary_contact">
    <?
      $all_contacts=mysql_query("SELECT * FROM contacts WHERE client_id='".$res[id]."' ORDER BY firstname");
      while($con=mysql_fetch_array($all_contacts)){
                if($con[id]==$res[primary_contact]){
                 echo '<option value="'.$con[id].'" selected>'.$con[firstname].' '.$con[lastname].' ('.$con[title].')</option>';
                 }else{
                 echo '<option value="'.$con[id].'">'.$con[firstname].' '.$con[lastname].' ('.$con[title].')</option>';
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
      <textarea name="cl_comments" cols="40" rows="5"><?echo $res[comments];?></textarea>
    </td>
  </tr>
</table>

<P><table width=100%>
<tr><td width=50 height="1" bgcolor="<?echo $admin_color_2;?>"></td></tr></table>

<?
 $c=mysql_query("SELECT * FROM contacts WHERE client_id='".$res[id]."'");
 
 while($con=mysql_fetch_array($c)){
    echo '<P><B>'.$con[firstname].' '.$con[lastname];
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
    <input type=hidden name="co_control[<?echo $con[id];?>]" value="<?echo $con[id];?>">
      <input type="text" name="co_firstname[<?echo $con[id];?>]" value="<?echo $con[firstname];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">
        Lastname: </font></font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_lastname[<?echo $con[id];?>]" value="<?echo $con[lastname];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Email:
        </font></font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_email[<?echo $con[id];?>]" value="<?echo $con[email];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Job
        Title : </font></font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_title[<?echo $con[id];?>]" value="<?echo $con[title];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Address:
        </font></font></div>
    </td>
    <td width="168">
      <textarea name="co_address[<?echo $con[id];?>]" cols="40" rows="5"><?echo $con[address];?></textarea>
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>" size="2">Primary
        Phone: </font></div>
    </td>
    <td width="168">
      <input type="text" name="co_phone[<?echo $con[id];?>]" value="<?echo $con[phone];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>" size="2">Secondary
        Phone: </font></div>
    </td>
    <td width="168">
      <input type="text" name="co_phone2[<?echo $con[id];?>]" value="<?echo $con[phone2];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Username:
        </font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_username[<?echo $con[id];?>]" value="<?echo $con[username];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Password:
        </font></font></div>
    </td>
    <td width="168">
      <input type="text" name="co_password[<?echo $con[id];?>]" value="<?echo $con[password];?>">
    </td>
  </tr>
  <tr>
    <td width="65">&nbsp;</td>
    <td width="267" valign="top">
      <div align="right"><font face="<?echo $admin_font;?>"><font size="2">Comments:
        </font></font></div>
    </td>
    <td width="168">
      <textarea name="co_comment[<?echo $con[id];?>]" cols="40" rows="5"><?echo $con[comments];?> </textarea>
    </td>
  </tr>
</table>
<table width=80%><tr><td width=50 height="1" bgcolor="<?echo $admin_color_2;?>"></td></tr></table>
    <?

 }



echo '<input type=submit name="SAVEUPDATES" value="SAVE IT NOW!"></form>';
include "footer.php";
?>
