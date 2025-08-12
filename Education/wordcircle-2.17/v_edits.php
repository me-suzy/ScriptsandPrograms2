<?php
	
	
	if(isset($_GET['dels'])){
	
		$GLOBALS['db']->execQuery("delete from calendar where calendar_id = ".$_GET['sid']);
			$GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we delete this calendar item","index.php?a=edits&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
	}
	
	if(isset($_POST['submit'])){
	

	$GLOBALS['db']->checkTyped($_POST['calendar'],"You must enter text for your calendar item");
	$GLOBALS['db']->checkLen($_POST['calendar'],3000,"Details must be less than 3000 characters");
	
	switch($_POST['submit']){
	
		case "Add Calendar Item":
		
		
		$time = "00:00:00";
		
			if(count($GLOBALS['error'])==0){
			
				if(isset($_POST['hour'])){
				$hr = $_POST['hour'];
				
					if($_POST['ampm'] == 'pm'){
					$hr += 12;
					}
				
				$time = $hr.":".$_POST['minute'].":00";
				
				}
			
			$GLOBALS['db']->execQuery("insert into calendar(calendar,dateOf,group_id) values('".str_replace(CHR(13).CHR(10),'<br>', trim(htmlspecialchars($_POST['calendar'],ENT_QUOTES)))."','".$_POST['y'].'/'.$_POST['m'].'/'.$_POST['d'].' '.$time."',".$_GET['gid'].")");
			$GLOBALS['page']->head("wordcircle","","Be patient");
			$GLOBALS['page']->pleaseWait("Please wait while we add this calendar item","index.php?a=edits&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
			}
		break;
		case "Edit Calendar Item":
		

			if(count($GLOBALS['error'])==0){
			
				$time = "00:00:00";
					if(isset($_POST['hour'])){
					$hr = $_POST['hour'];
					
						if($_POST['ampm'] == 'pm'){
						$hr += 12;
						}
					
					$time = $hr.":".$_POST['minute'].":00";
					
					}
			
			
			$GLOBALS['db']->execQuery("update calendar set calendar = '".str_replace(CHR(13).CHR(10),'<br>', trim(htmlspecialchars($_POST['calendar'],ENT_QUOTES)))."', dateOf = '".$_POST['y'].'/'.$_POST['m'].'/'.$_POST['d'].' '.$time."' where calendar_id = ".$_GET['sid']);
			$GLOBALS['page']->head("wordcircle","","Be patient");
			$GLOBALS['page']->pleaseWait("Please wait while we edit this calendar item","index.php?a=edits&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
			}
		
		break;
	}
	}

	$GLOBALS['page']->head("wordcircle","","Use this page to edit and create calendar items");

	$user = new user();
	
	
	

   

$result = $GLOBALS['db']->execQuery("select calendar_id,calendar,date_format(dateOf,'%m.%d.%y %h:%i %p') as dateOf from calendar where group_id = ".$_GET['gid']." order by dateOf desc, calendar_id desc");
	$i = 0;
	$calendar = array();
	while ($row = mysql_fetch_assoc($result)) { 
	
	   $calendar[$i]['date'] = $row["dateOf"];
	   $calendar[$i]['details'] = $row['calendar'];
	   $calendar[$i][''] = "<a href='index.php?a=edits&sid=".$row['calendar_id']."&gid=".$_GET['gid']."' class='".(($i%2==0)?"even":"odd")."'><img src='icon_edit.gif' width='16' height='16' alt='edit this item' border='0'>edit</a> <a href='index.php?a=edits&dels=1&gid=".$_GET['gid']."&sid=".$row['calendar_id']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this calendar item?'); return confirmDownload;\"><img src='icon_delete.gif' width='14' height='16' alt='delete this item' border=0>delete</a>";
	   $i++;
   }

	
	
	$GLOBALS['page']->tableStart("","100%","TAB","Modify Calendar");
	
	if (isset($_GET['sid'])){
		$ntitle = "";
		$nthoughts = "";
		
		$result = $GLOBALS['db']->execQuery("select calendar,date_format(dateOf,'%Y') as y, date_format(dateOf,'%m') as m, date_format(dateOf,'%d') as d, date_format(dateOf,'%T') as t from calendar where group_id = ".$_GET['gid']." and calendar_id = ".$_GET['sid']);
		while ($row = mysql_fetch_assoc($result)) { 
		$ncalendar = str_replace('<br>',CHR(13).CHR(10), trim($row['calendar']));
		$nd = $row["d"];
		$ny = $row["y"];
		$nm = $row["m"];
		$t = $row['t'];
   		}	
	
	echo("<br>");
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
	$datass = getdate();
	$GLOBALS['page']->datebox($nd,$nm,$ny,"d","m","y",$datass['year'],($datass['year']+8),1,"Date:","inputs",true,$t);
	$GLOBALS['page']->textarea($ncalendar,"calendar","inputs",5,67,"Details:",1);
	$GLOBALS['page']->submit("Edit Calendar Item","inputs");
	echo("<tr><td>&nbsp;</td><td><a href='index.php?a=edits&dels=1&gid=".$_GET['gid']."&sid=".$_GET['sid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this calendar item?'); return confirmDownload;\">[Delete this calendar item by clicking here]</a></td></tr>");
echo("<tr><td>&nbsp;</td><td><a href='index.php?a=edits&gid=".$_GET['gid']."'>[Cancel by clicking here]</a></td></tr>");
	$GLOBALS['page']->tableEnd("FORM");
	}else{
   
	$GLOBALS['page']->tableStart("","100%","TEXT");
echo("<strong>Use this form to add calendar items</strong>");
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableStart("","100%","FORM");
	
	//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
	$datass = getdate();
	$GLOBALS['page']->datebox(0,0,0,"d","m","y",$datass['year'],($datass['year']+8),1,"Date:","inputs",true);
	$GLOBALS['page']->textarea("","calendar","inputs",5,67,"Details:",1);
	$GLOBALS['page']->submit("Add Calendar Item","inputs");
	$GLOBALS['page']->tableEnd("FORM");
	
	}
	
	if(count($calendar)>0){
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($calendar,"odd","even","30%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no calendar items at this time");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	$GLOBALS['page']->tableEnd("TAB");
   

	
?>