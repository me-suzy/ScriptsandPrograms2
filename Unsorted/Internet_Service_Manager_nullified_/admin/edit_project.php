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

if($delete){
 mysql_query("DELETE FROM projects WHERE id='$delete'");
 mysql_query("DELETE FROM project_stages WHERE project_id='$delete'");
             echo '<script language="javascript">
                 window.location="client_list.php?client_id='.$client_id.'";
            </script>';
            exit;
}

if($GO){
 //gotta save some stuff!!
 
         //first save the projects general details!
          foreach($admins as $admin){
                     $theadmins.="$admin,";
          }
      mysql_query("UPDATE projects SET bill_in='$bill_in', project_name='$project_name', finish_date='".mktime (0,0,0,$finish_mm,$finish_dd,$finish_yy)."', status='$status', description='$description', project_manager='$project_manager', admins='$theadmins', comments='$comments' WHERE id='$projectid'");

      //now the stages!
      foreach($stages_control as $handler=>$exists){
              if($exists=="old"){
                      //its an old stage needing updating..
                      $stage_id=$existingid[$handler];
                      $finish_date=mktime(0,0,0,$st_finish_mm[$handler],$st_finish_dd[$handler],$st_finish_yy[$handler]);
                      $start_date=mktime(0,0,0,$st_start_mm[$handler],$st_start_dd[$handler],$st_start_yy[$handler]);
                      mysql_query("UPDATE project_stages SET stage_name='".$st_stage_name[$handler]."', description='".$st_description[$handler]."', details='".$st_details[$handler]."', start_date='".$start_date."', finish_date='".$finish_date."', comments='".$st_comments[$handler]."', cost='".$st_cost[$handler]."' WHERE id='$stage_id'");
              }else{
                      $finish_date=mktime(0,0,0,$st_finish_mm[$handler],$st_finish_dd[$handler],$st_finish_yy[$handler]);
                      $start_date=mktime(0,0,0,$st_start_mm[$handler],$st_start_dd[$handler],$st_start_yy[$handler]);
                      mysql_query("INSERT INTO project_stages SET project_id='".$projectid."', stage_name='".$st_stage_name[$handler]."', description='".$st_description[$handler]."', details='".$st_details[$handler]."', start_date='".$start_date."', finish_date='".$finish_date."', comments='".$st_comments[$handler]."', cost='".$st_cost[$handler]."'");
              }
      }
   $pr=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='$projectid'"));
      echo '<script language="javascript">
              window.location="client_list.php?client_id='.$pr[client_id].'";
      </script>';

exit;
}
$pr=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='$projectid'"));
    $res=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$pr[client_id]."'"));

echo '<table width="629" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td colspan="4"><font face="'.$admin_font.'" size="2"><b>Client
      Profile: '.$res[name].'</b></font></td>

    <td width="115">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" height="3"></td>
  </tr>
  <tr bgcolor="#006699">
        <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="client_list.php?client_id='.$pr[client_id].'" class="other_text">Profile</a> </div>
    </td>
    <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="billing_overview.php?client_id='.$pr[client_id].'" class="other_text">Billing</a> </div>
    </td>
    <td width="125">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_project.php?client_id='.$pr[client_id].'" class="other_text">Add Project</a></div>
    </td>
    <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="edit_client.php?client_id='.$pr[client_id].'" class="other_text">Edit Client</a></div>
    </td>
    <td width="120">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="support_overview.php?client_id='.$pr[client_id].'" class="other_text">Support</a></div>
      </td>
      <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_contact.php?client_id='.$pr[client_id].'" class="other_text">Add Contact</a></div>
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

echo '<font face="'.$admin_font.'" size="2"><B>Editing Project..</B><P>';


//general details..

echo "General details...<P>";
echo '<form action="edit_project.php?projectid='.$projectid.'" method="post">';

echo '<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Project
        Name: </font></font></font></div>
    </td>
    <td width="244">
      <input type="text" name="project_name" size=50 value="'.$pr[project_name].'">
    </td>
  </tr>
  <tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Client: </font></font></font></div>
    </td>
    <td width="244"><font face="'.$admin_font.'" size="2">';
          $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$pr[client_id]."'"));
          echo $client[name];
    echo '</td>
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
        $curfin=getdate($pr[finish_date]);
        for($m=1; $m<13; $m++){
                  $sel="";if($curfin[mon]==$m){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="finish_dd"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                  $sel="";if($curfin[mday]==$d){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="finish_yy"><option value="">--</option>';
        for($y=2000; $y<2020; $y++){
                  $sel="";if($curfin[year]==$y){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$y.'">'.$y.'</option>';
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
      <select name="status">';
      $project_states=array(0=>"Active", 1=>"Suspended", 2=>"Completed", 3=>"Proposal");
          foreach($project_states as $stateid=>$statename){
                                  $sel="";if($stateid==$pr[status]){$sel="SELECTED";}
               echo '<option '.$sel.' value="'.$stateid.'">'.$statename.'</option>';
          }
      
      echo '
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
                                  $sel="";if($methodid==$pr[bill_in]){$sel="SELECTED";}
               echo '<option '.$sel.' value="'.$methodid.'">'.$methodname.'</option>';
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
      <textarea name="description" cols="50" rows="5">'.$pr[description].'</textarea>
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
                         if($pr[project_manager]==$ad[id]){
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
                    $curads=explode(",", $pr[admins]);
                    while($ad=mysql_fetch_array($admins)){
                                                          $sel="";if(in_array($ad[id], $curads)){$sel="CHECKED";}
                         echo '<input '.$sel.' type="checkbox" name="admins['.$ad[id].']" value="'.$ad[id].'">'.$ad[firstname].' '.$ad[lastname].'('.$ad[title].')<BR>';
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
      <textarea name="comments" cols="50" rows="5">'.$pr[comments].'</textarea>
    </td>
  </tr>
<tr bgcolor="black"><td width=1></td><td width=1></td><td width=1></td></tr>
</table>
<P><B>Stages...</B><P>';




//current stages!

$stages=mysql_query("SELECT * FROM project_stages WHERE project_id='$projectid'");

while($st=mysql_fetch_array($stages)){
    $x++;
 echo '<input type="hidden" name="existingid['.$x.']" value="'.$st[id].'"><input type=hidden name="stages_control['.$x.']" value="old"><table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Stage
        Name: </font></font></font></div>
    </td>
    <td width="244">
      <input type="text" name="st_stage_name['.$x.']" value="'.$st[stage_name].'"> ';
          if($st[completed]==1){echo "<font size=1>Completed";}
      echo '</td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Description:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="st_description['.$x.']" cols="50" rows="5">'.$st[description].'</textarea>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Start
        Date: </font></font></font></div>
    </td>
    <td width="244">
        <select name="st_start_mm['.$x.']"><option value="">--</option>';
        $curfin=getdate($st[start_date]);
        for($m=1; $m<13; $m++){
                  $sel="";if($curfin[mon]==$m){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="st_start_dd['.$x.']"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                  $sel="";if($curfin[mday]==$d){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="st_start_yy['.$x.']"><option value="">--</option>';
        for($y=2000; $y<2020; $y++){
                  $sel="";if($curfin[year]==$y){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$y.'">'.$y.'</option>';
        }
      echo '
      </select>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Finish
        Date (mm/dd/yyyy): </font></font></font></div>
    </td>
    <td width="244">
  <select name="st_finish_mm['.$x.']"><option value="">--</option>';
        $curfin=getdate($st[finish_date]);
        for($m=1; $m<13; $m++){
                  $sel="";if($curfin[mon]==$m){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="st_finish_dd['.$x.']"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                  $sel="";if($curfin[mday]==$d){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="st_finish_yy['.$x.']"><option value="">--</option>';
        for($y=2000; $y<2020; $y++){
                  $sel="";if($curfin[year]==$y){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$y.'">'.$y.'</option>';
        }
      echo '
      </select>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Cost:
        </font></font></font></div>
    </td>
    <td width="244">';
      if($st[billed]!="1"){
         echo $payment_unit.'<input type="text" name="st_cost['.$x.']" value="'.$st[cost].'">';
      }else{
         echo '<font face="'.$admin_font.'" size=2>'.$payment_unit.$st[cost].'&nbsp;<font size=1>Cannot change, already billed!';
      }
    echo '</td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Details:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="st_details['.$x.']" cols="50" rows="5">'.$st[details].'</textarea>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Comments:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="st_comments['.$x.']" cols="50" rows="5">'.$st[comments].'</textarea>
    </td>
  </tr>
<tr bgcolor="black"><td width=1></td><td width=1></td><td width=1></td></tr></table><P>
';

}

for($s=0; $s<$addnew; $s++){
 $x++;
 echo '<a name="newstage"></a><input type=hidden name="stages_control['.$x.']" value="new">';
 echo '<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Stage
        Name: </font></font></font></div>
    </td>
    <td width="244">
      <input type="text" name="st_stage_name['.$x.']">
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Description:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="st_description['.$x.']" cols="50" rows="5"></textarea>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Start
        Date: </font></font></font></div>
    </td>
    <td width="244">
      <select name="st_start_mm['.$x.']"><option value="">--</option>';
        $curfin=getdate();
        for($m=1; $m<13; $m++){
                  $sel="";if($curfin[mon]==$m){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="st_start_dd['.$x.']"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                  $sel="";if($curfin[mday]==$d){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="st_start_yy['.$x.']"><option value="">--</option>';
        for($y=2000; $y<2020; $y++){
                  $sel="";if($curfin[year]==$y){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$y.'">'.$y.'</option>';
        }
      echo '
      </select>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Finish
        Date (mm/dd/yyyy): </font></font></font></div>
    </td>
    <td width="244">
         <select name="st_finish_mm['.$x.']"><option value="">--</option>';
        for($m=1; $m<13; $m++){
                  echo '<option value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="st_finish_dd['.$x.']"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                  echo '<option value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="st_finish_yy['.$x.']"><option value="">--</option>';
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
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Cost:
        </font></font></font></div>
    </td>
    <td width="244">
      <input type="text" name="st_cost['.$x.']">
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Details:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="st_details['.$x.']" cols="50" rows="5"></textarea>
    </td>
  </tr>
  <tr>
    <td width="28">&nbsp;</td>
    <td width="228" valign="top">
      <div align="right"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Comments:
        </font></font></font></div>
    </td>
    <td width="244">
      <textarea name="st_comments['.$x.']" cols="50" rows="5"></textarea>
    </td>
  </tr>
<tr bgcolor="black"><td width=1></td><td width=1></td><td width=1></td></tr><P></table>
';
}

echo '<input type=submit name="GO" value="SAVE IT ALL!">';

include "footer.php";
?>
