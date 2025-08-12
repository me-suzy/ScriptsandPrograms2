<?php
	
	if(isset($_GET['delt'])){
	
		$GLOBALS['db']->execQuery("delete from group_message where group_id = ".$_GET['gid']);
			$GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we delete this overview","index.php?a=editgm&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
	}

	if(isset($_POST['submit'])){
	
	
	$GLOBALS['db']->checkTyped($_POST['group_message'],"You must enter text for your overview");
	$GLOBALS['db']->checkLen($_POST['group_message'],10000,"News must be less than 10000 characters");
	
	switch($_POST['submit']){
	
		case "Modify Overview":
		
		
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("delete from group_message where group_id = ".$_GET['gid']);
			$GLOBALS['db']->execQuery("insert into group_message(group_message,group_id) values('".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['group_message']),ENT_QUOTES))."',".$_GET['gid'].")");
			$GLOBALS['page']->head("wordcircle","","Be patient");
			$GLOBALS['page']->pleaseWait("Please wait while we edit your overview","index.php?a=view&gid=".$_GET['gid']."&inc=".$GLOBALS['short_increment']);
			include("v_footer.php");
			exit;
			}
	}
	}

	$GLOBALS['page']->head("wordcircle","","This page is for modifying your overview");

	$user = new user();
	
	
   
  
$result = $GLOBALS['db']->execQuery("select group_message from group_message where group_id = ".$_GET['gid']);
	$group_message[0] = "no overview saved yet";
	while ($row = mysql_fetch_assoc($result)) { 
	   $group_message[0] = html_entity_decode($row["group_message"]);
   }
   $group_text = str_replace('<br>',CHR(13).CHR(10), trim($group_message[0]));
		

	$GLOBALS['page']->tableStart("","100%","TAB","Modify Overview");
	echo("<br>");
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
	$GLOBALS['page']->textarea($group_text,"group_message","inputs",5,60,"Modify Overview:",1);
	$GLOBALS['page']->submit("Modify Overview","inputs");
		echo("<tr><td>&nbsp;</td><td><a href='index.php?a=editgm&delt=1&gid=".$_GET['gid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this overview text?'); return confirmDownload;\">[Delete this overview by clicking here]</a></td></tr>");
echo("<tr><td>&nbsp;</td><td><a href='index.php?a=view&gid=".$_GET['gid']."'>[Cancel by clicking here]</a></td></tr>");
	$GLOBALS['page']->tableEnd("FORM");
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo($group_message[0]);
	$GLOBALS['page']->tableEnd("TEXT");
	
   	$GLOBALS['page']->tableEnd("TAB");      
	
?>