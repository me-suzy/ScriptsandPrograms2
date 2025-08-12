<?php

$user = new user();

if(isset($_POST['course']) and $GLOBALS['admin_email'] == $GLOBALS['user']->email){
			
			if(count($GLOBALS['error'])==0){
			$courses = $_POST['course'];
			$GLOBALS['page']->head("wordcircle","","Be patient",0);
			$GLOBALS['page']->pleaseWait("Please wait while we delete these courses","index.php?a=index");
		foreach ($courses as $courseitem){
		$GLOBALS['db']->execQuery("delete from groups where group_id =".$courseitem);
		$GLOBALS['db']->execQuery("delete from discussions where group_id = ".$courseitem);
		$GLOBALS['db']->execQuery("delete from messages where group_id =".$courseitem);
		$GLOBALS['db']->execQuery("delete from topics where group_id =".$courseitem);
		$GLOBALS['db']->execQuery("delete from thoughts where group_id =".$courseitem);
		
		//kill the directories under this course
	
			$dir = "c".$courseitem;	
			$fh = new filer();
			$fh->recursiveDelete($dir);
		 }

			
			
			include("v_footer.php");
			exit;
			}
}

if(isset($_POST['submit']) or isset($_GET['key'])){
	if(isset($_POST['submit'])){$rightButton = strstr($_POST['submit'], 'Someone');}else{$rightButton=true;} 
	if($rightButton<>false or isset($_GET['key'])){
		
		if(isset($_GET['key'])){$akey = $_GET['key'];}else{$akey=$_POST['key'];}
		
		
		$GLOBALS['db']->CheckTyped($akey,"you must enter a course key");
		if(count($GLOBALS['error'])==0){
			//see if that course exists and then give it to them...
			
			$result = $GLOBALS['db']->execQuery("select groups.group_id from groups where code = '" . trim($akey). "'");
				if(mysql_num_rows($result)){
					while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)){
					$gid = $myrow['group_id'];
					}
				//now make sure that they do not have it in their list already
				
				$result3 = $GLOBALS['db']->execQuery("select groups.group_id from groups,user_groups where user_id = ".$GLOBALS['user']->user_id." and code = '" . $akey . "' and user_groups.group_id = groups.group_id");
				if(mysql_num_rows($result3)==0){
				
				$GLOBALS['db']->execQuery("insert into user_groups(user_id,group_id) values(".$GLOBALS['user']->user_id.",".$gid.")");
				}else{$GLOBALS['error'][0]="Course is already in your list";}
				}else{$GLOBALS['error'][0]="Course key does not exist";}
			if(count($GLOBALS['error'])==0){
			$GLOBALS['page']->head("wordcircle","","Be patient",0);
			$GLOBALS['page']->pleaseWait("Updating your course list","index.php");
			include("v_footer.php");
			exit;
			}
		}
	}else{
	//if you are a teacher... add the course name to the db
	
	$GLOBALS['db']->CheckTyped($_POST['group_name'],"you must enter a course name");
	$GLOBALS['db']->CheckLen($_POST['group_name'],100,"course names must have less than 100 characters");
		if(count($GLOBALS['error'])==0){
		//function code ($minlength, $maxlength, $useupper, $usespecial, $usenumbers) 
		$code=$GLOBALS['page']->code();
		
		$result = $GLOBALS['db']->execQuery("insert into groups (group_name,owner_id,code,public) values ('" . trim($_POST['group_name']) . "',".$GLOBALS['user']->user_id .",'".strtoupper($code)."',".$_POST['public'].")");
		
		 $new_group_id = mysql_insert_id();
		 
		  mkdir("c".$new_group_id);

		//put this course into this person's list...
		
		$result2 = $GLOBALS['db']->execQuery("insert into user_groups (group_id,user_id) values (" . $new_group_id  . " , " . $GLOBALS['user']->user_id . ")" );

		$GLOBALS['page']->head("wordcircle","","Be patient",0);
		$GLOBALS['page']->pleaseWait("Creating a new course called ".trim($_POST['group_name'])."<br>and updating your course list","index.php");
			include("v_footer.php");
			exit;
		}
	}	
}

$GLOBALS['page']->head("wordcircle","","Click on one of your courses to get started",0);

	
	
	if(!isset($_GET['frm'])){
	$GLOBALS['page']->tableStart("","100%","TAB","My Course List");


	$result = $GLOBALS['db']->execQuery("select groups.group_id,code, public, group_name from groups, user_groups where user_groups.user_id = " . $GLOBALS['user']->user_id . " and groups.group_id = user_groups.group_id order by group_name");
	$res = array();
	$i=0;
	while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)){

	$res[$i]['click on a course\'s name to get started'] = "<a href='index.php?a=view&gid=".$myrow['group_id']."&inc=4' class='".(($i%2==0)?"even":"odd")."'>".$myrow['group_name']."</a> 	". (($myrow['public']==1)?'<span class="publicprivate">[PUBLIC]</span>':'<span class="publicprivate">[PRIVATE]</span>');
	$res[$i]['key'] = $myrow['code'];
	$res[$i][''] = "<a href='index.php?a=remove&gid=".$myrow['group_id']."'><img src='icon_delete.gif' width='16' height='16' alt='remove this course' border='0'>remove</a>";
	$i++;
	}
	
	if($GLOBALS['admin_email'] == $GLOBALS['user']->email){

	
	$result11 = $GLOBALS['db']->execQuery("select groups.group_id,code, public, group_name from groups order by group_name");
	$res11 = array();
	$i=0;
	while ($myrow = mysql_fetch_array($result11, MYSQL_ASSOC)){

	$res11[$i]['course'] = "<a href='index.php?a=view&gid=".$myrow['group_id']."' class='".(($i%2==0)?"even":"odd")."'>".$myrow['group_name']."</a> 	". (($myrow['public']==1)?'<span class="publicprivate">[PUBLIC]</span>':'<span class="publicprivate">[PRIVATE]</span>');
	//get directory size...
	$file_handler = new filer();
	$dir = "c" . $myrow['group_id'];
	$totalsize=0;
	if ($dirstream = @opendir($dir)) {
	while (false !== ($filename = readdir($dirstream))) {
	if ($filename!="." && $filename!="..")
	{
	if (is_file($dir."/".$filename))
	$totalsize+=filesize($dir."/".$filename);
	if (is_dir($dir."/".$filename))
	$totalsize+=$file_handler->dir_size($dir."/".$filename);
			}
		}
		closedir($dirstream);
	}
	$res11[$i]['usage'] = number_format(($totalsize / 1024),2) . " KB";
	$res11[$i][''] = "<input type='checkbox' name='course[]' value='".$myrow['group_id']."'>";
	$i++;
	}
	}
	
	//function rows($columnArray=array(), $oddClass, $evenClass, $width)
		
	;
	if(count($res)>0){
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($res,"odd","even","65%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	
		echo("<strong>&middot;List Is Empty&middot;</strong>");

	$GLOBALS['page']->tableEnd("TEXT");
	}
	
	$GLOBALS['page']->tableEnd("TAB");

	
	$GLOBALS['page']->tableStart("","100%","TEXT");
	
	echo("<br>
	<strong>Instructions:</strong><br>
	
  There are two ways to add courses to your course list: (choose one)<br>
   <strong><a href='index.php?frm=1'>(A)</a></strong> <a href='index.php?frm=yours'>add someone else's course to
  your list
  by entering their course key</a><br>
  <strong><a href='index.php?frm=1'>(B)</a> </strong><a href='index.php?frm=mine'>create a new course yourself</a>");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	if(isset($_GET['frm'])){
	$GLOBALS['page']->tableStart("","100%","TAB","Add a Course");
	}
	if(isset($_GET['frm']) and $_GET['frm'] == 'yours'){
	echo("<br><a href='index.php'>Go back to my course list</a><br><h3>Add someone else&rsquo;s course</h3><strong>Note:</strong> you must have their course key<br><br>
	&nbsp;<a href='index.php?a=courses' class='publink'>Public courses are listed on the Course List page here</a><br>
<br>
");
	$GLOBALS['page']->tableStart("","100%","FORM");
	echo("<tr><td> &nbsp;&nbsp;<strong>Key:</strong> <input type='text' name='key' class='inputs' size='10' value='".(isset($_POST['key'])? $_POST['key']:'' )."'></td><td><input type='submit' name='submit' value='Add Someone Else&rsquo;s Course To My List' class='inputs'></td></tr>");
	$GLOBALS['page']->tableEnd("FORM");
	}
	if(isset($_GET['frm']) and $_GET['frm'] == 'mine'){
	echo("<br><a href='index.php'>Go back to my course list</a><br><h3>Add (create) your own course</h3>
	&nbsp;&nbsp;<strong>Note:</strong> use a descriptive name (<em>Alaska University - Mr. Rudner - Physics 302</em>)");	
	$GLOBALS['page']->tableStart("","100%","FORM");
	$GLOBALS['page']->text("","group_name","inputs","Course name:",45,1);
	//function radio($radioSuperArray,$dflt,$name,$desc,$class,$chngeOnPost=0){
	echo("<tr><td>&nbsp;</td><td><strong>If this is a test course please put TEST in the course name</strong><br>
<br>
</td></tr><tr><td align='right' valign='top'>Select one:</td><td><input type='radio' class='inputs' name='public' value='0' ".((!isset($_POST['public']))?'checked':'')." ".(((isset($_POST['public'])) and ($_POST['public']==0))?'checked':'').">This is a <strong>private</strong> course - invitation only<br>
<input type='radio' class='inputs' name='public' value='1' ".(((isset($_POST['public'])) and ($_POST['public']==1))?'checked':'').">This is a <strong>public course</strong> - it will be listed on the course list
</td></tr><tr><td>&nbsp;</td><td><br>
<input type='submit' name='submit' class='inputs' value='Create My Own Course / Add It To My List'></td></tr>");
	$GLOBALS['page']->tableEnd("FORM");
	//function text($value,$name,$class,$desc,$size,$chngeOnPost=0)
	}
	if(isset($_GET['frm'])){
		$GLOBALS['page']->tableEnd("TAB");
	}


	if(!isset($_GET['frm'])){
	if($GLOBALS['admin_email'] == $GLOBALS['user']->email){
	echo("<br>");
	$GLOBALS['page']->tableStart("","100%","TAB","For Sys Admin Only");
	if(count($res11)>0){
	$GLOBALS['page']->tableStart("","100%","FORM");
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($res11,"odd","even","65%");
	$GLOBALS['page']->tableEnd("GRID");
	echo("<input type='submit' name='delete' value='delete' onClick=\"confirmDownload = confirm('Are you sure you want to delete these courses?'); return confirmDownload;\">");
	$GLOBALS['page']->tableEnd("FORM");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	
		echo("<strong>&middot;List Is Empty&middot;</strong>");

	$GLOBALS['page']->tableEnd("TEXT");
	}
	$GLOBALS['page']->tableEnd("TAB");
	}
	}


?>
