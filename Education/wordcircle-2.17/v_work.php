<?php

//edit a journal entry...
if(isset($_GET['eid'])){
	
	if(isset($_POST['submit'])){
	//update was made
	
	
$GLOBALS['db']->execQuery("update messages set message = '".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['journal'],ENT_QUOTES)))."' where message_id = ".$_GET['eid']." and group_id = ".$_GET['gid']." and (created_for = ".$GLOBALS['user']->user_id." or ".(($GLOBALS['owner'] == $GLOBALS['user']->user_id)?"1=1":"1=0").")");


$GLOBALS['page']->pleaseWait("Please wait while we update this text entry",'index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid']);
			include("v_footer.php");
			exit;
	
	}
	
	//show an edit box with this entry...
	
	
	$res = $GLOBALS['db']->execQuery("select * from messages where message_id = ".$_GET['eid']." and project_id = ".$_GET['pid']." and group_id = ".$_GET['gid']." and (created_for = ".$GLOBALS['user']->user_id." or ".(($GLOBALS['owner'] == $GLOBALS['user']->user_id)?"1=1":"1=0").")");
	
	$GLOBALS['page']->tableStart("","100%","FORM");
	
	$dmess = str_replace('<br>',CHR(13).CHR(10), html_entity_decode(mysql_result($res,0,"message")));
		//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
		$GLOBALS['page']->textarea($dmess,"journal","inputs",10,100,"Edit Text:",1);
		$GLOBALS['page']->submit("Edit Text","inputs");
	$GLOBALS['page']->tableEnd("FORM");
	
			include("v_footer.php");
			exit;
	
}

if(isset($_GET['rid'])){
	
$GLOBALS['db']->execQuery("delete from messages where message_id = ".$_GET['rid']." and group_id = ".$_GET['gid']." and (created_for = ".$GLOBALS['user']->user_id." or ".(($GLOBALS['owner'] == $GLOBALS['user']->user_id)?"1=1":"1=0").")");

$GLOBALS['page']->pleaseWait("Please wait while we delete this text entry",'index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid']);
			include("v_footer.php");
			exit;

}

if(isset($_GET['jid'])){

if($_GET['type'] == 'priv'){$private = "Y";}else{$private = "N";}

$GLOBALS['db']->execQuery("update messages set privateYN = '".$private."' where message_id = ".$_GET['jid']." and group_id = ".$_GET['gid']);

$GLOBALS['page']->pleaseWait("Please wait while we update this text entry",'index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid']);
			include("v_footer.php");
			exit;

}

if (isset($_GET['f'])){

	if ($_GET['type'] == "priv"){
	$fold = "mpriv".$_GET['mid'];
	}else{
	$fold = "mpub".$_GET['mid'];
	}
		
	 if (@unlink("c".$_GET['gid'].'/'.$fold.'/'.$_GET['f'])) {
		$GLOBALS['page']->pleaseWait("Please wait while we delete this file",'index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid']);
			include("v_footer.php");
			exit;
    } else {
      $GLOBALS['error'][0] = "An error occurred deleting your file";
    }
}

if(isset($_GET['file'])){

	if ($_GET['type'] == "pub"){
	$oldfold = "mpriv".$_GET['mid'];
	$newfold = "mpub".$_GET['mid'];
	}else{
	$oldfold = "mpub".$_GET['mid'];
	$newfold = "mpriv".$_GET['mid'];
	}

   if (file_exists("c".$_GET['gid']."/".$oldfold."/".$_GET['file']))
  {
  	rename ("c".$_GET['gid']."/".$oldfold."/".$_GET['file'], "c".$_GET['gid']."/".$newfold."/".$_GET['file']) 
 	 or die ("Could not move file"); }
	 
	 $GLOBALS['page']->pleaseWait("Please wait while the file is moved",'index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid']);

	$GLOBALS['db']->execQuery("delete from project_action where project_id = ".$_GET['pid']." and user_id =".$GLOBALS['user']->user_id." and group_id =".$_GET['gid']);

	$GLOBALS['db']->execQuery("insert into project_action(project_id, user_id, last_action, group_id) values (".$_GET['pid'].",".$GLOBALS['user']->user_id.",now(),".$_GET['gid'].")");
			
			$GLOBALS['db']->execQuery("update projects set last_action = now() where project_id = ".$_GET['pid']." and group_id = ".$_GET['gid']);		
			
			include("v_footer.php");
			exit;

}

if(isset($_POST['submit'])){

if (isset($_POST['journal'])){
		
	

	$GLOBALS['db']->checkTyped($_POST['journal'],"You must enter text for your entry");
	
		
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("insert into messages(message,project_id,group_id,created_by,created_for,privateYN,created_on) values('".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['journal'],ENT_QUOTES)))."',".$_GET['pid'].",".$_GET['gid'].",".$GLOBALS['user']->user_id.",".$_GET['mid'].",'".$_POST['privateYN']."',now())");
			$GLOBALS['page']->pleaseWait("Please wait while this entry is added",'index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid']);

			
			$GLOBALS['db']->execQuery("delete from project_action where project_id = ".$_GET['pid']." and user_id =".$GLOBALS['user']->user_id." and group_id =".$_GET['gid']);
			$GLOBALS['db']->execQuery("insert into project_action(project_id, user_id, last_action, group_id) values (".$_GET['pid'].",".$GLOBALS['user']->user_id.",now(),".$_GET['gid'].")");
			;
			$GLOBALS['db']->execQuery("update projects set last_action = now() where project_id = ".$_GET['pid']." and group_id = ".$_GET['gid']);		

			include("v_footer.php");
			exit;
			}
			
		
}else{	

	// Upload processor script 
	

$GLOBALS['db']->checkNameSpace($_FILES['aFile']['name'],"File names cannot have spaces or special characters in them");
$GLOBALS['db']->checkFileTypes($_FILES['aFile']['name'],"This file type is now allowed");

if(count($GLOBALS['error']) == 0){

 if ($_POST['type'] == "private"){$fold = "mpriv".$_GET['mid'];}else{$fold = "mpub".$_GET['mid'];}

 if (copy($_FILES['aFile']['tmp_name'], "c".$_GET['gid']."/".$fold."/".$_FILES['aFile']['name'])) {
      if (move_uploaded_file($_FILES['aFile']['tmp_name'], "c".$_GET['gid']."/".$fold."/".$_FILES['aFile']['name'])) {
        @chmod($_FILES['aFile']['name'], "c".$_GET['gid']."/".$fold."/".$_FILES['aFile']['name'], 0777);
		$GLOBALS['page']->pleaseWait("Please wait while this file is added",'index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid']);
			include("v_footer.php");
			
			$GLOBALS['db']->execQuery("delete from project_action where project_id = ".$_GET['pid']." and user_id =".$GLOBALS['user']->user_id." and group_id =".$_GET['gid']);
			$GLOBALS['db']->execQuery("insert into project_action(project_id, user_id, last_action, group_id) values (".$_GET['pid'].",".$GLOBALS['user']->user_id.",now(),".$_GET['gid'].")");
			
			$GLOBALS['db']->execQuery("update projects set last_action = now() where project_id = ".$_GET['pid']." and group_id = ".$_GET['gid']);		
			
			exit;
      } else {
      $GLOBALS['error'][0] = 'an error occurred uploading your file';
      }
    } else {
   	  $GLOBALS['error'][0] = 'an error occurred uploading your file';
    }
}
}
}

if (isset($_GET['add'])){
$GLOBALS['page']->tableStart("","100%","TAB","Add Work");
$GLOBALS['page']->tableStart("","100%","TEXT");
echo('<a href="index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid'].'">Go back</a><br>
	<br>
	');
echo('<img src="icon_locked.gif" width="11" height="14" alt=""> Private work can only be seen by the teacher & student<br><img src="icon_unlocked.gif" width="12" height="15" alt=""> Public work can be seen by anyone in the course<br>
<br>

');
	
		
	echo("<script language='JavaScript'>
		<!-- Copyright 2000 by William and Will Bontrager.
		counter=-1;
		function count() {
		counter++;
		if(counter > 1) {
		if(counter > 2) { return false; }
		alert('This operation will take some time\\n\\nIf you keep re-submitting the page it will just take longer!');
		return false;
		}
		return true;
		} // -->
		</script>");	
	echo('Use form <strong>(a)</strong> to upload files and form <strong>(b)</strong> to save a text entry<br><br>
<strong>(a)</strong> upload a file
	<form method="post" action="index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid'].'&add=1" enctype="multipart/form-data" onSubmit="count()"> 
<input type="radio" name="type" value="private" checked><img src="icon_locked.gif" width="11" height="14" alt=""> private<br>
<input type="radio" name="type" value="public"><img src="icon_unlocked.gif" alt="" width="12" height="15" border="0"> 	public<br>
<input type="file" name="aFile" size="20" class="inputs">
<input type="submit" name="submit" value="submit" class="inputs"> 
</form>');
echo('<hr><strong>(b)</strong> save a text entry<br>
	<form method="post" action="index.php?a=work&mid='.$_GET['mid'].'&gid='.$_GET['gid'].'&pid='.$_GET['pid'].'&add=1" onSubmit="count()"> 
	<table>
	<tr><td><strong>Author: </strong>'.$GLOBALS['user']->first_name.' '.$GLOBALS['user']->last_name.'<br>
<input type="radio" name="privateYN" value="Y" checked><img src="icon_locked.gif" width="11" height="14" alt=""> private<br>
<input type="radio" name="privateYN" value="N"><img src="icon_unlocked.gif" alt="" width="12" height="15" border="0"> 	public<br>
<textarea class="inputs" cols="100" rows="10" name="journal"></textarea></td></tr>
<tr><td><input type="submit" name="submit" value="submit" class="inputs"></td></tr></table>
</form>');
	$GLOBALS['page']->tableEnd("TEXT");
	
	$GLOBALS['page']->tableEnd("TAB");
	
   
include("v_footer.php");
exit;

}

    echo("<img src='icon_list.gif' width='16' height='16' alt=''> <a href='index.php?a=projects&gid=".$_GET['gid']."'>Go back to your project list</a> &middot; <img src='icon_document.gif' width='16' height='16' alt=''> <a href='index.php?a=projects&gid=".$_GET['gid']."&pid=".$_GET['pid']."'>Go back to project details</a><br><br>
");


$res = $GLOBALS['db']->execQuery("select first_name, last_name from users where user_id = ".$_GET['mid']);

echo("You are viewing work in ".mysql_result($res,0,"first_name")." ".mysql_result($res,0,"last_name")."'s project space".(($_GET['mid'] == $GLOBALS['user']->user_id or $GLOBALS['owner'] == $GLOBALS['user']->user_id)?"
<br><br>

<a href='index.php?a=work&mid=".$_GET['mid']."&gid=".$_GET['gid']."&pid=".$_GET['pid']."&add=1'><img src='icon_save.gif' width='18' height='18' border=0 hspace=1 align='top'>Click here to upload files or add text entries</a>":""));

echo("<br>");


	$result = $GLOBALS['db']->execQuery("select project_name,project from projects where projects.group_id = ".$_GET['gid']." and projects.project_id = ".$_GET['pid']." and (".(($GLOBALS['owner'] == $GLOBALS['user']->user_id or $_GET['mid'] == $GLOBALS['user']->user_id)?"1=1":"1=0").")");

	if (!file_exists("c".$_GET['gid'])) { 
       mkdir("c".$_GET['gid']);} 
	   
	   //now for public...
	   
	   if (!file_exists("c".$_GET['gid']."/mpub".$_GET['mid'])) { 
       mkdir("c".$_GET['gid']."/mpub".$_GET['mid']);} 
	   //and for private...
	   
	   if (!file_exists("c".$_GET['gid']."/mpriv".$_GET['mid'])) { 
       mkdir("c".$_GET['gid']."/mpriv".$_GET['mid']);} 
	   
	   
	   
 $i=0;
 $doc=array();
 $path = "c".$_GET['gid']."/mpub".$_GET['mid']."/";    // Open the folder    
 $dir_handle = @opendir($path) or die("Unable to open $path");  
   // Loop through the files    
   while ($file = readdir($dir_handle)) {    if($file == "." || $file == ".." || $file == "index.php" )        continue;
   $doc[$i]['<img src="icon_unlocked.gif" width="12" height="15" alt="">'.' public files'] = "<a href='c".$_GET['gid']."/mpub".$_GET['mid']."/".$file."'><img src='icon_file.gif' width='14' height='16' alt='' border='0' align='top'>".$file."</a><br />";
	if($GLOBALS['user']->user_id == $_GET['mid'] or $GLOBALS['user']->user_id == $GLOBALS['owner']){
	$doc[$i][''] = "<a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&file=".$file."&type=priv'><img src='icon_both.gif' width='22' height='15' border=0 hspace=1 align='top'>make private</a> <a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&f=".$file."&type=pub' onClick=\"confirmDownload = confirm('Are you sure you want to delete this file?'); return confirmDownload;\"'><img src='icon_delete.gif' width='14' height='16' alt='delete this file' border=0>delete</a>";
	}
   $i++;}    // Close   
   closedir($dir_handle);
   
 $i=0;
 $doc2=array();
 $path = "c".$_GET['gid']."/mpriv".$_GET['mid']."/";    // Open the folder    
 $dir_handle = @opendir($path) or die("Unable to open $path");  
   // Loop through the files    
   while ($file = readdir($dir_handle)) {    if($file == "." || $file == ".." || $file == "index.php" )        continue;
   $doc2[$i]["<img src='icon_locked.gif' width='11' height='14' alt=''>".' private files'] = "<a href='c".$_GET['gid']."/mpriv".$_GET['mid']."/".$file."'><img src='icon_file.gif' width='14' height='16' alt='' border='0' align='top'>".$file."</a><br />";
	if($GLOBALS['user']->user_id == $_GET['mid'] or $GLOBALS['user']->user_id == $GLOBALS['owner']){
	$doc2[$i][''] = "<a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&file=".$file."&type=pub'><img src='icon_both.gif' width='22' height='15' border=0 hspace=1 align='top'>make  public</a> <a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&f=".$file."&type=priv' onClick=\"confirmDownload = confirm('Are you sure you want to delete this file?'); return confirmDownload;\"'><img src='icon_delete.gif' width='14' height='16' alt='delete this file' border=0>delete</a>";
	}
   $i++;}    // Close   
   closedir($dir_handle);
   
  
   $resmess = $GLOBALS['db']->execQuery("select message_id,message,date_format(created_on,'%m.%d.%y %h:%i %p') as created_on,first_name,last_name,privateYN from messages,users where project_id = ".$_GET['pid']." and created_for = ".$_GET['mid']." and created_by = users.user_id order by created_on asc");
	$journal = array();
	$i=0;
	while ($row = mysql_fetch_assoc($resmess)) {
	    $journal[$i]['text entries'] = "<strong>Author: </strong>".$row['first_name']." ".$row['last_name']." <strong>Date: </strong>".$row['created_on']." ".(($row['privateYN'] == "N")?"<img src='icon_unlocked.gif' width='12' height='15'> (public)":"<img src='icon_locked.gif' width='11' height='14'> (private)").(($GLOBALS['user']->user_id == $_GET['mid'] or $GLOBALS['user']->user_id == $GLOBALS['owner'])?"<br>

".(($row['privateYN'] == "Y")?"

<a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&jid=".$row['message_id']."&type=pub'><img src='icon_both.gif' width='22' height='15' alt='' border=0 align='top' hspace='1'>make public</a>":"<a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&jid=".$row['message_id']."&type=priv'><img src='icon_both.gif' width='22' height='15' alt='' border=0 align='top' hspace='1'>make private</a>")."

 <a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&eid=".$row['message_id']."'><img src='icon_edit.gif' width='16' height='16' border=0>edit&nbsp;</a> <a href='index.php?a=work&pid=".$_GET['pid']."&gid=".$_GET['gid']."&mid=".$_GET['mid']."&rid=".$row['message_id']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this text entry?'); return confirmDownload;\"><img src='icon_delete.gif' width='16' height='16' border=0>remove</a>":"")."<br>".html_entity_decode($row['message']);
	$i++;	  
   }
   
  
echo('<br>');
	$GLOBALS['page']->tableStart("","100%","TAB",'Student Work');
echo('<img src="icon_locked.gif" width="11" height="14" alt=""> Private work can only be seen by the teacher & student<br><img src="icon_unlocked.gif" width="12" height="15" alt=""> Public work can be seen by anyone in the course<br>
<br>

');
    	
	if(mysql_num_rows($result)>0){

	
	
	;
	
	$GLOBALS['page']->tableStart("","100%","GRID");
	if(count($doc2)>0){
	$GLOBALS['page']->rows($doc2,"odd","even","40%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There is no private work available");
	$GLOBALS['page']->tableEnd("TEXT");
	}	
	
	
	
	echo("<br>");
	

	
	}
	
	
	;
	if(count($doc)>0){
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($doc,"odd","even","40%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There is no public work available");
	$GLOBALS['page']->tableEnd("TEXT");
	}	
	echo("<br>");
	;
	
	$GLOBALS['page']->tableStart("","100%","GRID");
	if(count($journal)>0){
	$GLOBALS['page']->rows($journal,"odd","even","40%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no text entries available");
	$GLOBALS['page']->tableEnd("TEXT");
	}	
	$GLOBALS['page']->tableEnd("TAB");
	

	
?>