<?php
	
	if(isset($_GET['delt'])){
	
		$GLOBALS['db']->execQuery("delete from thoughts where thoughts_id = ".$_GET['nid']);
			$GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we delete this news item","index.php?a=editn&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
	}

	if(isset($_POST['submit'])){
	
	
	$GLOBALS['db']->checkTyped($_POST['thoughts'],"You must enter text for your news");
	$GLOBALS['db']->checkLen($_POST['thoughts'],2000,"News must be less than 2000 characters");
	
	switch($_POST['submit']){
	
		case "Add News":
		
		
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("insert into thoughts(thoughts,created_on,group_id) values('".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['thoughts'],ENT_QUOTES)))."',now(),".$_GET['gid'].")");
			$GLOBALS['page']->head("wordcircle","","Be patient");
			$GLOBALS['page']->pleaseWait("Please wait while we add this news item","index.php?a=editn&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
			}
		break;
		case "Edit News":
		
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("update thoughts set thoughts = '". str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['thoughts'],ENT_QUOTES)))."' where thoughts_id = ".$_GET['nid']);
			$GLOBALS['page']->head("wordcircle","","Be patient");
			$GLOBALS['page']->pleaseWait("Please wait while we edit this news item","index.php?a=editn&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
			}
		break;
	}
	}

	$GLOBALS['page']->head("wordcircle","","This page is for modifying and creating news items");

	$user = new user();
	
	
   
 
$result = $GLOBALS['db']->execQuery("select thoughts_id,thoughts,date_format(created_on,'%m.%d.%y') as created_on from thoughts where group_id = ".$_GET['gid']." order by created_on asc, thoughts_id desc");
	$i = 0;
	$thoughts = array();
	while ($row = mysql_fetch_assoc($result)) { 
	   $thoughts[$i]['news item'] = html_entity_decode($row["thoughts"]);
	    $thoughts[$i]['posted'] = $row["created_on"];
	    $thoughts[$i][''] = "<a href='index.php?a=editn&nid=".$row['thoughts_id']."&gid=".$_GET['gid']."#form' class='".(($i%2==0)?"even":"odd")."'><img src='icon_edit.gif' width='16' height='16' alt='edit this news item' border='0'>edit</a> <a href='index.php?a=editn&delt=1&gid=".$_GET['gid']."&nid=".$row['thoughts_id']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this news item?'); return confirmDownload;\"><img src='icon_delete.gif' width='14' height='16' alt='delete this document' border=0>delete</a>";
	   $i++;
   }
   
	

	$GLOBALS['page']->tableStart("","100%","TAB","Modify News");
	
		if (isset($_GET['nid'])){
		$ntitle = "";
		$nthoughts = "";
		
		$result = $GLOBALS['db']->execQuery("select thoughts from thoughts where group_id = ".$_GET['gid']." and thoughts_id = ".$_GET['nid']);
		while ($row = mysql_fetch_assoc($result)) { 
		$nthoughts = str_replace('<br>',CHR(13).CHR(10), trim($row['thoughts']));
   		}	
	

	echo("<a name='form'></a>");
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
	$GLOBALS['page']->textarea($nthoughts,"thoughts","inputs",13,100,"Edit News:",1);
	$GLOBALS['page']->submit("Edit News","inputs");
		echo("<tr><td>&nbsp;</td><td><a href='index.php?a=editn&delt=1&gid=".$_GET['gid']."&nid=".$_GET['nid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this news item?'); return confirmDownload;\">[Delete this news item by clicking here]</a></td></tr>");
echo("<tr><td>&nbsp;</td><td><a href='index.php?a=editn&gid=".$_GET['gid']."'>[Cancel by clicking here]</a></td></tr>");
	$GLOBALS['page']->tableEnd("FORM");
	
	}else{
   

	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("<strong>Use the form below to add news</strong>");
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
	$GLOBALS['page']->textarea("","thoughts","inputs",13,100,"Enter News Item:",1);
	$GLOBALS['page']->submit("Add News","inputs");
	$GLOBALS['page']->tableEnd("FORM");

	}

	
	if(count($thoughts)>0){
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($thoughts,"odd","even","50%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There is no news at this time");
	$GLOBALS['page']->tableEnd("TEXT");
	}

   
      	$GLOBALS['page']->tableEnd("TAB");      
	
?>