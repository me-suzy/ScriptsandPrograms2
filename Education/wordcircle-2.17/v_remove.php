<?php



$GLOBALS['page']->head("wordcircle","","If you need help click the link below",0);


if (($GLOBALS['page']->checkSecurity() == false)or(!isset($_GET['gid']))){
	$GLOBALS['page']->tableStart("","100%","TAB","Welcome");
	$GLOBALS['page']->tableStart("","100%","TEXT","");
	$GLOBALS['page']->welcomeMessage();
	$GLOBALS['page']->tableEnd("TEXT");
}

else{
	$GLOBALS['page']->tableStart("","100%","TAB","Courses");
		
	$result = $GLOBALS['db']->execQuery("select groups.group_id, group_name from groups where (groups.owner_id = " . $GLOBALS['user']->user_id . " or ".(($GLOBALS['admin_email'] == $GLOBALS['user']->email and isset($_GET['admin']))?"1=1":"1=0").") and groups.group_id = ".$_GET['gid']);
	
	if(!isset($_GET['confirm'])){
	

		if(mysql_num_rows($result)>0){
		$GLOBALS['page']->tableStart("","100%","TEXT","");
		echo("Warning! You own this course<br><br>Removing it will delete all thoughts, calendar items, documents and discussions.<br><br>");
		echo("<a href='index.php'>Do not delete it</a>&nbsp;&nbsp;&nbsp;<a href='index.php?a=remove&gid=".$_GET['gid']."&confirm=1&admin=1'>Delete it</a>");
		$GLOBALS['page']->tableEnd("TEXT");
		}else{
		$GLOBALS['page']->tableStart("","100%","TEXT","");
		echo("Are you sure you want to remove this course?<br><br>You must know the course key in order to put it back<br><br>");
		echo("<a href='index.php'>Do not remove it</a>&nbsp;&nbsp;&nbsp;<a href='index.php?a=remove&gid=".$_GET['gid']."&confirm=1'>Remove it</a>");
		$GLOBALS['page']->tableEnd("TEXT");
		}
	}else{
	
	//delete and re-route...
	if(mysql_num_rows($result)>0){
	
		
		$GLOBALS['db']->execQuery("delete from groups where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from discussions where group_id = ".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from messages where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from topics where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from thoughts where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from categories where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from projects where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from project_action where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from thoughts where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from group_message where group_id =".$_GET['gid']);
		$GLOBALS['db']->execQuery("delete from user_projects where group_id =".$_GET['gid']);
		
		//kill the directories under this course
	
	$dir = "c".$_GET['gid'];	
	if ($handle = @opendir($dir))
  {
     while (($file = readdir($handle)) !== false)
     {
        if (($file == ".") || ($file == ".."))
        {
           continue;
        }
        if (is_dir($dir . '/' . $file))
        {
           // call self for this directory
		   $filer = new filer();
           $filer->recursiveDelete($dir . '/' . $file);
        }
        else
        {
           unlink($dir . '/' . $file); // remove this file
        }
     }
     @closedir($handle);
     rmdir ($dir);  
  }

	
	}
	
	$GLOBALS['db']->execQuery("delete from user_groups where group_id = ".$_GET['gid']." and user_id = ".$GLOBALS['user']->user_id);

	$GLOBALS['page']->pleaseWait("Please wait while we remove this course","index.php");
	
	}
$GLOBALS['page']->tableEnd("TAB");
echo("<br><br>");
	
}
	

?>
