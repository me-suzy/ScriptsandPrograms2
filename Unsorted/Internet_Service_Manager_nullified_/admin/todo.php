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
include "header.php";

function printsmallcalender($month, $year){
//globalise some things...
global $admin_font, $admin_color_2;
//first day of the month..
echo '<table border=0 bgcolor="'.$admin_color_2.'" bordercolordark="'.$admin_color_2.'" bordercolorlight="white"><tr><td colspan=5 bgcolor="'.$admin_color_2.'"><font face="'.$admin_font.'" size="1"><B>'.$month." 20".$year.'</td></tr><tr height=20>';
$weekstarts=0;
$weekdays=array(0=>"Sun", 1=>"Mon", 2=>"Tues", 3=>"Wed", 4=>"Thur", 5=>"Fri", 6=>"Sat");
$today=$weekstarts;
for($i=0; $i<7; $i++){
if($today>6){$today=0;}
echo '<td bgcolor="'.$admin_color_2.'" width=20><font face="'.$admin_font.'" size="1"><B>'.$weekdays[$today].'</td>';
$today++;	
}

$daym=1;
$thisday=0;
while(checkdate($month, $daym, "20$year")){
$dayweek=date("w", mktime(0,0,0,$month,$daym,$year));


for($i=1; $i<8; $i++){
	if($thisday==7){$thisday=0;}
	if($thisday==$weekstarts){
	echo '</tr><tr>';
	}
	
	if($thisday==$dayweek){
		echo '<td><font face="'.$admin_font.'" size="1"><a href="todo.php?the_day='.$month.'/'.$daym.'/'.$year.'">'.$daym.'</a></td>';
		$thisday++;
		break;
	}else{
		//blank cell
		echo '<td><font face="'.$admin_font.'" size="1"></td>';
		$thisday++;
	}



}

$daym++;
}
echo '</tr></table>';
//end function printsmallcalender()
}

  ?>
  <script language="JavaScript">
			<!--
   
   function popItem(id, act){
     
     dfgdfgfdg=window.open('pop_todo_item.php?id='+id+'&action='+act,'ToDoItem','width=500,height=300,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');

  }

  
			//-->
</script>
        <?

if(!$the_day){
//use todays date..
$the_day=date("m/d/y");
}

list($month,$day,$year)=explode("/",$the_day);



echo '<center><table cellspacing=0 callpadding=2><tr><td valign=top>';
if($month==1){
$year2="20$year";
$month2=12;$year2=$year2-1;
$year2=substr($year2, 2);
}else{
$month2=$month-1;
$year2=$year;
}
printsmallcalender($month2, $year2);
echo '</td><td width=40></td><td valign=top>';
//print the small month calender!
printsmallcalender($month, $year);

echo '</td><td width=40></td><td valign=top>';
if($month==12){
$year3="20$year";
$month3=1;$year3=$year3+1;
$year3=substr($year3, 2);
}else{
$month3=$month+1;
$year3=$year;
}
printsmallcalender($month3, $year3);
echo '</td></tr><tr><td colspan=5><BR><center><font size="2" face="'.$admin_font.'"><a class="left_menu" href="todo.php?the_day='.$month2.'/1/'.$year2.'">Prev Months</a> | <a class="left_menu" href="todo.php">Today</a> | <a class="left_menu" href="todo.php?the_day='.$month3.'/1/'.$year3.'">Next Months</a></td></tr></table>';
echo '<table width="100%"><tr><td colspan=3>';
//print the daily calender..
$int=3600; //one hour!
$start_of_day=mktime(0,0,0,$month,$day,$year)+7200;
echo '<table cellpadding=2><tr><td colspan=2><font size="1" face="'.$admin_font.'"><B>'.date("F j, Y",$start_of_day).'</td></tr>';
for($time=$start_of_day; $time<$start_of_day+86400; $time=$time+$int){
$atime=$time-3600;
if($color==$admin_color_2){$color="#ffffff";}else{$color=$admin_color_2;}
echo '<tr bgcolor="'.$color.'"><td><font size="1" face="'.$admin_font.'">'.date("g:i a", $atime).'</td>';
$items=mysql_query("SELECT * FROM todo WHERE admin_id='$admin_id' && date>'".($time-$int-1)."' && date<'$time'");
$allitems="";
while($i=mysql_fetch_array($items)){
$b="";if($i[completed]<10){$b="<B>";}
$allitems.=$b.'<a href="javascript: popItem('.$i[id].', \'view\')">'.$i[title].'</a>;&nbsp</B>';
}
$allitems.='<a href="javascript: popItem('.$atime.', \'add\')">Add Item</a>';
echo '<td><font size="1" face="'.$admin_font.'">'.$allitems.'</td>';
echo '</tr>';

}

echo '</table></td></tr></table>';








include "footer.php";
?>

