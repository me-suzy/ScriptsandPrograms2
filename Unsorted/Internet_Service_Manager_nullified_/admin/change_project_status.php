<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="developer"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.
$project=$project_id;
include "header.php";

?> <script language="JavaScript">
			<!--

   function chooseclient(id)
   {
      window.location="change_project_status.php?client_id="+id;
   }

   function stage_status(status,stage)
   {
      window.location="change_project_status.php?stage="+stage+"&status="+status+"&client_id=<?echo $client_id;?>&project_id=<?echo $project_id;?>";
   }


   function chooseproject(id)
   {
      window.location="change_project_status.php?client_id=<?echo $client_id;?>&project_id="+id;
   }


			//-->
</script>
<?

if($stage){
 mysql_query("UPDATE project_stages SET completed='$status' WHERE id='$stage'");
 //if all the stages are complete mark the entire project as complete..
 if(mysql_num_rows(mysql_query("SELECT * FROM project_stages WHERE project_id='$project_id'"))==mysql_num_rows(mysql_query("SELECT * FROM project_stages WHERE completed='1' && project_id='$project_id'"))){
   mysql_query("UPDATE projects SET status='2' WHERE id='$project_id'");
 }else{
   mysql_query("UPDATE projects SET status='0' WHERE id='$project_id'");
 }
}

      echo '<form name="f"><font face="'.$admin_font.'" size="2">

      Select the client the project is for..<P>';
      echo '<select onChange="chooseclient(this.value)"><option value="">---</option>';

$pe=mysql_query("SELECT * FROM clients ORDER BY name");
while($p=mysql_fetch_array($pe)){
 $sel="";if($client_id==$p[id]){$sel="SELECTED";}
 echo '<option '.$sel.' value="'.$p[id].'">'.$p[name].'</option>';
}
echo '</select>';

if($client_id){
         echo '<P> Select the project..<P>';
      echo '<select onChange="chooseproject(this.value)"><option value="">---</option>';

$pe=mysql_query("SELECT * FROM projects WHERE status!='1' && client_id='$client_id'");
while($p=mysql_fetch_array($pe)){
 $sel="";if($project_id==$p[id]){$sel="SELECTED";}
 echo '<option '.$sel.' value="'.$p[id].'">'.$p[project_name].'</option>';
}
echo '</select><P>';
}

if($project_id){
     $st=mysql_query("SELECT * FROM project_stages WHERE project_id='$project_id'");
     echo '<table>';
     while($s=mysql_fetch_array($st)){
         $sel="";if($s[completed]==0){$sel="SELECTED";}
         echo '<tr><td><font face="'.$admin_font.'" size="2">'.$s[stage_name].'&nbsp;&nbsp;</td><td><select onChange="stage_status(this.value, '.$s[id].')">
          <option value="1">Completed</option>
          <option '.$sel.' value="0">Active</option>
         </select></td></tr>';
     }
     echo '</table>';
}


include "footer.php";
?>
