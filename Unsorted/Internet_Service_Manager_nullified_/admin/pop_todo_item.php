<HTML>
<HEAD>
<TITLE>ToDo Items!</TITLE>
<script language="javascript">
     function changeclient(id)
     {
        window.location="pop_select_contact.php?client="+id;
     }

     function changecontact(id)
     {
              window.location="pop_select_contact.php?contactchosen="+id
     }
     
</script>
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

if($complete){
mysql_query("UPDATE todo SET completed='".time()."' WHERE admin_id='$admin_id' && id='$complete'");
echo '<script language="javascript">
window.close()
</script>';
}

if($incomplete){
mysql_query("UPDATE todo SET completed='0' WHERE admin_id='$admin_id' && id='$incomplete'");
echo '<script language="javascript">
window.close()
</script>';
}

if($action=="add"){
$date=mktime($hh,$ttm,0,$mm,$dd,$yy);
if($details && $title && $date){
mysql_query("INSERT INTO todo SET details='$details', title='$title', date='$date', admin_id='$admin_id'");
echo '<script language="javascript">
window.close()
</script>';
}
//new!
echo '<font face="'.$admin_font.'" size="2"><B>Add New Todo Item: </B><P>';
echo '<table cellpading="4"><form action="pop_todo_item.php?action=add" method="post">
<tr><td><font face="'.$admin_font.'" size="2">Title: </td><td><input type="text" size="35" name="title"></td></tr>
<tr><td><font face="'.$admin_font.'" size="2">Date:<BR><font size="1"> (mm/dd/yyyy)</font> </td><td>
 <select name="mm"><option value="">--</option>';

                
list($month,$day,$year,$hour,$minute)=explode("/",date("m/d/y/H/i", $id));

	    for($m=1; $m<13; $m++){
                  $sel="";if($m==$month){$sel="SELECTED";}
				  echo '<option '.$sel.' value="'.$m.'">'.$m.'</option>';
        }
      echo '</select>
      <select name="dd"><option value="">--</option>';
        for($d=1; $d<32; $d++){
                   $sel="";if($d==$day){$sel="SELECTED";}
                  echo '<option '.$sel.' value="'.$d.'">'.$d.'</option>';
        }
      echo '
      </select>
      <select name="yy"><option value="">--</option>';
        for($y=2000; $y<2020; $y++){
                       $sel="";if($y=="20$year"){$sel="SELECTED";}
	              echo '<option '.$sel.' value="'.$y.'">'.$y.'</option>';
        }
      echo '
      </select>
	  
</td></tr>
<tr><td valign="top"><font face="'.$admin_font.'" size="2">Time:<BR><font size="1"> (hh/mm)</font> </td><td>
 <select name="hh">';
        
		for($t=1; $t<25; $t++){
                       $sel="";if($t==$hour){$sel="SELECTED";}
						if($t<=12){$td="$t am";}else{$td=($t-12)."pm";}		
	              echo '<option '.$sel.' value="'.$t.'">'.$td.'</option>';
        }
      echo '
      </select>
 <select name="ttm">';
        
		for($m=0; $m<60; $m++){
                       $sel="";if($m==$minute){$sel="SELECTED";}
	              echo '<option '.$sel.' value="'.$m.'">'.$m.'</option>';
        }
      echo '
      </select>
</td></tr>
<tr><td valign="top"><font face="'.$admin_font.'" size="2">Details: </td><td><textarea cols=40 rows=5 name=details></textarea></td></tr>
<tr><td></td><td><input type="submit" name="go" value="Add ToDo Item!"></td></form></tr>

</table>';

}else{

$res=mysql_fetch_array(mysql_query("SELECT * FROM todo WHERE id='$id' && admin_id='$admin_id'"));
echo '<table cellpading="4">
<tr><td><font face="'.$admin_font.'" size="2">Title: '.$res[title].'</td><td></td></tr>
<tr><td><font face="'.$admin_font.'" size="2">Date: '.date("m/d/y g:i a", $res[date]).' </td><td></td></tr>
<tr><td><font face="'.$admin_font.'" size="2">Details: '.$res[details].'</td><td></td></tr>
<tr><td><font face="'.$admin_font.'" size="2">Completed: ';
if($res[completed]){
echo date("m/d/y g:i a", $res[completed]);
echo ' <a href="pop_todo_item.php?incomplete='.$res[id].'">Mark as In-Complete</a>';
}else{
echo "NOT COMPLETED";
echo ' <a href="pop_todo_item.php?complete='.$res[id].'">Mark as Complete</a>';
}
echo '</td><td></td></tr>
</table>';


}

   
?>
</BODY>
</HTML>
