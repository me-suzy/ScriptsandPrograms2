<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="editprojects"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.

include "header.php";

    $res=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
  if($res[account_balance]<=0){$res[account_balance]=0;}
  echo '<table width="629" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td colspan="4"><font face="'.$admin_font.'" size="2"><b>Client
      Profile: '.$res[name].' (Balance: '.$payment_unit.$res[account_balance].')</b></font></td>

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

echo '<font face="'.$admin_font.'" size="2"><B>Adding Project..</B>';

if($general){
//add stages!
      //insert the general data into the projects table..
      foreach($admins as $admin){
                     $theadmins.="$admin,";
      }
      mysql_query("INSERT INTO projects SET bill_in='$bill_in', client_id='$client_id', project_name='$project_name', start_date='".time()."', finish_date='".mktime (0,0,0,$finish_mm,$finish_dd,$finish_yy)."', status='$status', description='$description', project_manager='$project_manager', admins='$theadmins', comments='$comments'");

echo '<script language="javascript">
window.location="edit_project.php?projectid='.mysql_insert_id().'&addnew='.$stages.'"
</script>';
}else{
//general details..
echo " General details<P>";
echo '<form action="add_project.php?general=done" method="post">';

echo '<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Project
        Name: </font></font></font></div>
    </td>
    <td width="244">
      <input type="text" name="project_name" size=50>
    </td>
  </tr>
  <tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Client: </font></font></font></div>
    </td>
    <td width="244">
      <select name="client_id">';
              $clients=mysql_query("SELECT * FROM clients ORDER BY name");
                    while($cl=mysql_fetch_array($clients)){
                         if($cl[id]==$client_id){
                            echo '<option SELECTED value="'.$cl[id].'">'.$cl[name].'</option>';
                            }else{
                            echo '<option value="'.$cl[id].'">'.$cl[name].'</option>';
                            }
                    }
      echo '</select>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Start
        Date: </font></font></font></div>
    </td>
    <td width="244"><font face="'.$admin_font.'" size="2">'.date("F j, Y, g:i a").'</td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Finish
        Date (mm/dd/yyyy): </font></font></font></div>
    </td>
    <td width="244">
      <select name="finish_mm"><option value="">--</option>';
        for($m=1; $m<13; $m++){
                  echo '<option value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="finish_dd"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                  echo '<option value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="finish_yy"><option value="">--</option>';
        for($y=2000; $y<2020; $y++){
                  echo '<option value="'.$y.'">'.$y.'</option>';
        }
      echo '
      </select>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Status:
        </font></font></font></div>
    </td>
    <td width="244">
      <select name="status">
      <option value="0">Active</option>
      <option value="1">Suspended</option>
      <option value="2">Completed</option>
      <option value="3" SELECTED>Proposal</option>
      </select>
    </td>
  </tr>
  
      <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Bill After:
        </font></font></font></div>
    </td>
    <td width="244">
      <select name="bill_in">';
      $billing_methods=array(0=>"Dont Set", 1=>"Project Completion", 2=>"Each Stage");
          foreach($billing_methods as $methodid=>$methodname){
               echo '<option value="'.$methodid.'">'.$methodname.'</option>';
          }

      echo '
      </select>
    </td>
  </tr>
  
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Description:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="description" cols="50" rows="5"></textarea>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Project
        Manager: </font></font></font></div>
    </td>
    <td width="244">
      <select name="project_manager">';
              $admins=mysql_query("SELECT * FROM admins ORDER BY firstname");
                    while($ad=mysql_fetch_array($admins)){
                         if($admin_id==$ad[id]){
                            echo '<option SELECTED value="'.$ad[id].'">'.$ad[firstname].' '.$ad[lastname].' ('.$ad[title].')</option>';
                            }else{
                            echo '<option value="'.$ad[id].'">'.$ad[firstname].' '.$ad[lastname].'('.$ad[title].')</option>';
                            }
                    }
      echo '</select>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Admins:
        </font></font></font></div>
    </td>
    <td width="244"> <font face="'.$admin_font.'" size=1>';
                  $admins=mysql_query("SELECT * FROM admins ORDER BY firstname");
                    while($ad=mysql_fetch_array($admins)){
                         echo '<input type="checkbox" name="admins['.$ad[id].']" value="'.$ad[id].'">'.$ad[firstname].' '.$ad[lastname].'('.$ad[title].')<BR>';
                    }
    echo '

    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Comments:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="comments" cols="50" rows="5"></textarea>
    </td>
  </tr>

  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Stages:
        </font></font></font></div>
    </td>
    <td width="244">
      <input type=text name=stages size=4 value="1">
    </td>
  </tr>
  
    <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">
        </font></font></font></div>
    </td>
    <td width="244">
      <input type=submit name=continue value="Continue..">
    </td>
  </tr>
</table>
';

}

include "footer.php";
?>
