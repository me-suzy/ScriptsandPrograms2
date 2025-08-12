<?php
	$user = new user();
	
	
	if(isset($_GET['chnge'])){
	
		
	
	
$result = $GLOBALS['db']->execQuery("select project_name,project from projects where projects.group_id = ".$_GET['gid']." and projects.project_id = ".$_GET['pid']." and (".(($GLOBALS['owner'] == $GLOBALS['user']->user_id)?"1=1":"projects.owner_id = ".$GLOBALS['user']->user_id).")");

	if(mysql_num_rows($result)==0){
	$GLOBALS['error'][0] = "You do not have privelages to edit this project";
	}else{
	
	
		if(isset($_POST['submit'])){
		
		
	
		$GLOBALS['db']->checkTyped($_POST['project_name'],"You must enter a project name");
		$GLOBALS['db']->checkLen($_POST['project'],5000,"project descriptions must be less than 5000 characters");

		if(count($GLOBALS['error'])==0){
		$GLOBALS['db']->execQuery("update projects set project_name = '".$_POST['project_name']."', project = '".$_POST['project']."' where project_id = ".$_GET['pid']);

		$GLOBALS['page']->pleaseWait("Please wait while this project is updated","index.php?a=projects&gid=".$_GET['gid']."&pid=".$_GET['pid']);	
			include("v_footer.php");
			exit;
		}
		
		}
	
	
	
	echo("<img src='icon_list.gif' width='16' height='16' alt='' align='top'> <a href='index.php?a=projects&gid=".$_GET['gid']."'>Go back to your project list</a> &middot; <img src='icon_document.gif' width='16' height='16' alt='' align='top'> <a href='index.php?a=projects&gid=".$_GET['gid']."&pid=".$_GET['pid']."' align='top' >Go back to project details</a><br><br>
");
	
	$GLOBALS['page']->tableStart("","100%","TAB","Create A Project");
	
	echo("<br>");
	$GLOBALS['page']->tableStart("","100%","FORM");
	$GLOBALS['page']->text(mysql_result($result,0,"project_name"),"project_name","inputs","Project Name:",30,1);
	$GLOBALS['page']->textarea(mysql_result($result,0,"project"),"project","inputs",4,60,"Overview:",1);
	$GLOBALS['page']->submit("Edit Project","inputs");
	$GLOBALS['page']->tableEnd("FORM");
	$GLOBALS['page']->tableEnd("TAB");
	include("v_footer.php");
	exit;
	
	
	}
	
	}
	
	
	if(isset($_GET['rem'])){
	
	
$result = $GLOBALS['db']->execQuery("select project_id from user_projects where user_projects.project_id = ".$_GET['pid']." and user_projects.user_id = ".$GLOBALS['user']->user_id);

	if(mysql_num_rows($result)==0){
	$GLOBALS['error'][0] = "You do not have privelages to remove this project";
	}else{
	
	$GLOBALS['db']->execQuery("delete from user_projects where user_projects.project_id = ".$_GET['pid']." and user_projects.user_id = ".$GLOBALS['user']->user_id);

	$GLOBALS['db']->execQuery("delete from project_action where project_id = ".$_GET['pid']." and user_id = ".$GLOBALS['user']->user_id);

		
		
		$GLOBALS['page']->pleaseWait("Please wait while this project is removed from your list","index.php?a=projects&gid=".$_GET['gid']);	
			include("v_footer.php");
			exit;
	
	}

	}
	
	
	
	
	if(isset($_GET['del'])){
	
			
$result = $GLOBALS['db']->execQuery("select project_id from projects where projects.group_id = ".$_GET['gid']." and projects.project_id = ".$_GET['pid']." and (".(($GLOBALS['owner'] == $GLOBALS['user']->user_id)?"1=1":"projects.owner_id = ".$GLOBALS['user']->user_id).")");

	if(mysql_num_rows($result)==0){
	$GLOBALS['error'][0] = "You do not have privelages to remove this project";
	}else{
		
		$GLOBALS['db']->execQuery("delete from projects where project_id = ".$_GET['pid']);
		$GLOBALS['db']->execQuery("delete from user_projects where project_id = ".$_GET['pid']);
		$GLOBALS['db']->execQuery("delete from project_action where project_id = ".$_GET['pid']);
		$GLOBALS['db']->execQuery("delete from messages where project_id = ".$_GET['pid']);
		
		
		$GLOBALS['page']->pleaseWait("Please wait while this project is deleted","index.php?a=projects&gid=".$_GET['gid']);	
			include("v_footer.php");
			exit;
		
	}
	
	}
	
	

	
	
	if(isset($_GET['aex'])){
	

	$GLOBALS['db']->execQuery("delete from user_projects where project_id = ".$_GET['pid']." and user_id = ".$GLOBALS['user']->user_id);
	$GLOBALS['db']->execQuery("insert into user_projects (project_id,user_id) values(".$_GET['pid'].", ".$GLOBALS['user']->user_id.")");
$GLOBALS['page']->pleaseWait("Please wait while this project is added to your list","index.php?a=projects&gid=".$_GET['gid']);	
			include("v_footer.php");
			exit;
	}
	
	if(isset($_GET['find'])){
	
	
$result = $GLOBALS['db']->execQuery("select projects.project_id,project_name,project,owner_id,first_name,last_name,project from users,projects where projects.group_id = ".$_GET['gid']." and projects.owner_id = users.user_id and (globalYN = 'N') order by project_name");

	$i = 0;
	$project = array();
	while ($row = mysql_fetch_assoc($result)) { ;
	   $project[$i]['select one'] = "<img src='icon_save.gif' width='16' height='16' alt=''><a href='index.php?a=projects&gid=".$_GET['gid']."&pid=".$row['project_id']."&aex=1'>".$row['project_name']."</a>";
	   $project[$i]['created by'] = $row['first_name']." ".$row['last_name'];
	   $project[$i]['details'] = html_entity_decode($row['project']);
	   $i++;
   }
   
   
	echo("<img src='icon_list.gif' width='16' height='16' alt='' zlign='top'> <a href='index.php?a=projects&gid=".$_GET['gid']."'>Click here to go back to project list</a><br>");
echo("<br><strong>Instructions:</strong> click on an voluntary project to add it to your project list<br>
<br>
");
   
   	$GLOBALS['page']->tableStart("","100%","TAB","Voluntary Projects");
	if(count($project)==0){
	echo("<br>
	&nbsp;&nbsp;No voluntary projects have been created yet<br>
	<br>
	");
	}else{
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($project,"odd","even","25%",$GLOBALS['db']->numberOfRows,"projects");
	$GLOBALS['page']->tableEnd("GRID");
	}
	$GLOBALS['page']->tableEnd("TAB");
	include("v_footer.php");
	exit;
	}
	
	if(isset($_GET['ex'])){
	if ($GLOBALS['owner'] <> $GLOBALS['user']->user_id){
	$GLOBALS['error'][0]="Access Denied";
	}

	if(count($GLOBALS['error'])==0){
	$GLOBALS['db']->execQuery("update projects set globalYN = 'Y',owner_id = ".$GLOBALS['user']->user_id." where group_id = ".$_GET['gid']." and project_id = ".$_GET['pid']);
	$GLOBALS['db']->execQuery("delete from user_projects where project_id = ".$_GET['pid']);
			$GLOBALS['page']->pleaseWait("Please wait while this project is updated","index.php?a=projects&gid=".$_GET['gid']."&pid=".$_GET['pid']);			
			include("v_footer.php");
			exit;
	}
	}
	
	if(isset($_GET['frm']) and $_GET['frm'] == 'create'){
	
		if(isset($_POST['submit'])){
	
		
		$GLOBALS['db']->checkTyped($_POST['project_name'],"You must enter a project name");
		$GLOBALS['db']->checkLen($_POST['project'],5000,"project descriptions must be less than 5000 characters");
		if(count($GLOBALS['error'])==0){
			$globalYN = (($GLOBALS['owner'] == $GLOBALS['user']->user_id and (!isset($_POST['extra'])))?"Y":"N");
		$GLOBALS['db']->execQuery("insert into projects(project_name,project,group_id,globalYN,owner_id) values('".trim($_POST['project_name'])."','".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['project'],ENT_QUOTES)))."',".$_GET['gid'].",'".$globalYN."',".$GLOBALS['user']->user_id.")");
		
		$lastInsert = mysql_insert_id();
		
		if($globalYN=='N'){
		$uid = $GLOBALS['user']->user_id;
		$GLOBALS['db']->execQuery("insert into user_projects(user_id,project_id) values (".$uid.",".$lastInsert.")");
		}
		

		$GLOBALS['page']->pleaseWait("Please wait while this project is created","index.php?a=projects&gid=".$_GET['gid']);	
			include("v_footer.php");
			exit;
		}
		
		}
	
	$GLOBALS['page']->tableStart("","100%","TAB","Create A Project");
	
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("<strong>Use this form to create a new project</strong><br>");
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableStart("","100%","FORM");
	$GLOBALS['page']->text("","project_name","inputs","Project Name:",30,1);
	$GLOBALS['page']->textarea("","project","inputs",4,60,"Overview:",1);
	if($GLOBALS['owner'] == $GLOBALS['user']->user_id){
	$arr[0]['Make this project <strong>voluntary</strong> (not mandatory)']='1';
	$GLOBALS['page']->checkbox($arr,"extra","","inputs",0);
	$GLOBALS['page']->submit("Create Project","inputs");
	}else{
	$GLOBALS['page']->submit("Create Project","inputs");
	echo("<tr><td align='right' valign='top'><br>
	Note:</td><td><br>
	<strong>This will be listed as a voluntary project.</strong> <br>
	Mandatory projects can only be created by course owners <br>
	You can ask the course owner to change it from a <br>
	voluntary project to a mandatory one at any time</td></tr>");
	}
	
	$GLOBALS['page']->tableEnd("FORM");
	$GLOBALS['page']->tableEnd("TAB");
	include("v_footer.php");
	exit;
	
	}
	
	if(isset($_GET['pid'])){
	
	
$result = $GLOBALS['db']->execQuery("select distinct  projects.project_id,project_name,owner_id,first_name,last_name,globalYN,project,date_format(last_action,'%m.%d.%y') as last_action from users,projects where projects.group_id = ".$_GET['gid']." and projects.project_id = ".$_GET['pid']." and projects.owner_id = users.user_id order by last_action desc");
	

	echo("<img src='icon_list.gif' width='16' height='16' alt='' align='top'> <a href='index.php?a=projects&gid=".$_GET['gid']."'>Click here to go back to project list</a><br><br>");
	
	$GLOBALS['page']->tableStart("","100%","TAB","Project Details");
	
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("<table width='100%'>");
	echo("<tr><td align='right' valign='top' nowrap><strong>Project name: </strong></td><td>".mysql_result($result,0,"project_name")."</td></tr>");
	echo("<tr><td align='right' valign='top'><strong>Details: </strong></td><td><img src='icon_singlepx.gif' width='1' height='1' alt=''>".html_entity_decode(mysql_result($result,0,"project"))."</td></tr>");
	echo("<tr><td align='right' valign='top' nowrap><strong>Recent Activity: </strong></td><td><img src='icon_singlepx.gif' width='1' height='1' alt=''>".((mysql_result($result,0,"last_action")=="")?"none":mysql_result($result,0,"last_action"))."</td></tr>");
	if(mysql_result($result,0,"globalYN") == 'N' and $GLOBALS['owner'] == $GLOBALS['user']->user_id){
	echo("<tr><td align='right'><strong>Type:</strong></td><td>Voluntary (created by  ".mysql_result($result,0,"first_name"). " ".mysql_result($result,0,"last_name").") </td></tr>");
	}elseif(mysql_result($result,0,"globalYN") == 'N'){
	echo("<tr><td align='right'><strong>Type:</strong></td><td>Voluntary (created by ".mysql_result($result,0,"first_name"). " ".mysql_result($result,0,"last_name").")</td></tr>");
	}else{
	echo("<tr><td align='right'><strong>Type:</strong></td><td>Mandatory (this project is assigned to everyone)</td></tr>");
	}
	if($GLOBALS['owner'] == $GLOBALS['user']->user_id or mysql_result($result,0,"globalYN") == 'N'){
	echo("<tr><td align='right'><strong>Options</strong>: </td><td>");
	if($GLOBALS['owner']==$GLOBALS['user']->user_id){
	echo("
	<a href='index.php?a=projects&del=1&&pid=".$_GET['pid']."&gid=".$_GET['gid']."' style='text-decoration:none' onClick=\"confirmDownload = confirm('Are you sure you want to remove this project?\\nALL student work for every student will be deleted forever!'); return confirmDownload;\"><img src='icon_delete.gif' width='16' height='16' alt='delete this item' border='0'>delete</a> ".((mysql_result($result,0,"globalYN")=="N")?"&nbsp;<a href='index.php?a=projects&rem=1&pid=".$_GET['pid']."&gid=".$_GET['gid']."' style='text-decoration:none' onClick=\"confirmDownload = confirm('Are you sure you want to remove this project from your list?'); return confirmDownload;\"><img src='icon_delete2.gif' width='16' height='16' alt='delete this item' border='0'>remove from my list</a> &nbsp;":"")."<a href='index.php?a=projects&chnge=1&pid=".$_GET['pid']."&gid=".$_GET['gid']."' style='text-decoration:none'><img src='icon_edit.gif' width='16' height='16' alt='edit project' border='0'>edit project</a>");
			 if(mysql_result($result,0,"globalYN") == "N"){echo("<tr><td><img src='icon_singlepx.gif' width='1' height='1' alt=''></td><td align='left'>
			<a href='index.php?a=projects&gid=".$_GET['gid']."&pid=".$_GET['pid']."&ex=1' onClick=\"confirmDownload = confirm('Are you sure you want to assign this project to everyone?'); return confirmDownload;\"' style='text-decoration:none'><img src='icon_exclam.gif' border='0' width='15' height='16' alt=''>&nbsp;change from <em>voluntary</em> to <em>mandatory</em><br></a></td></tr>");
			}
	 }elseif(mysql_result($result,0,"globalYN") == 'N'){
	 echo("<a href='index.php?a=projects&rem=1&pid=".$_GET['pid']."&gid=".$_GET['gid']."' style='text-decoration:none' onClick=\"confirmDownload = confirm('Are you sure you want to remove this project from your list?'); return confirmDownload;\"><img src='icon_delete.gif' width='16' height='16' alt='delete this item' border='0'>remove from my list</a> ");
		if(mysql_result($result,0,"owner_id") == $GLOBALS['user']->user_id){
		echo("<a href='index.php?a=projects&chnge=1&pid=".$_GET['pid']."&gid=".$_GET['gid']."' style='text-decoration:none'><img src='icon_edit.gif' width='16' height='16' alt='edit project' border='0'>edit project</a>");
		}
	 }
	echo("</td></tr>");
	}
	echo("</table>");
	
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableEnd("TAB");
	
	if(mysql_result($result,0,"globalYN")=='N' or mysql_result($result,0,"owner_id") == "0"){
	$globalYN = "N";
	}else{
	$globalYN = "Y";
	}
	
	if($globalYN == "N"){
	
		$result45 = $GLOBALS['db']->execQuery("select distinct users.user_id,first_name,last_name, date_format(last_action,'%m.%d.%y') as last_action from users, user_projects left outer join project_action on project_action.user_id = users.user_id where users.user_id = user_projects.user_id and user_projects.project_id = ".$_GET['pid']." and users.user_id <> ".$GLOBALS['user']->user_id." order by last_name desc");
	
	}else{
	
		$result45 = $GLOBALS['db']->execQuery("select distinct users.user_id,first_name,last_name, date_format(last_action,'%m.%d.%y') as last_action from user_groups,users left outer join project_action on project_action.user_id = users.user_id and project_action.project_id = ".$_GET['pid']." where users.user_id = user_groups.user_id and user_groups.group_id = ".$_GET['gid']." and users.user_id <> ".$GLOBALS['user']->user_id." order by last_name desc");
	
	}

	$i=0;
	
	//if this is not the owner, put the user in the first spot
	
	$result46 = $GLOBALS['db']->execQuery("select date_format(last_action,'%m.%d.%y') as last_action from users,project_action where project_action.user_id = users.user_id and project_action.project_id = ".$_GET['pid']." and users.user_id = ".$GLOBALS['user']->user_id);
	  $users[$i]['name'] = "<a href='index.php?a=work&mid=".$GLOBALS['user']->user_id."&gid=".$_GET['gid']."&pid=".$_GET['pid']."'>".$GLOBALS['user']->first_name." ".$GLOBALS['user']->last_name."</a>";
	   $users[$i]['recent activity'] = ((mysql_num_rows($result46)==0)?"none":mysql_result($result46,0,"last_action"));
	   $i++;	
	
	while ($row = mysql_fetch_assoc($result45)) {
	   $users[$i]['name'] = "<a href='index.php?a=work&mid=".$row['user_id']."&gid=".$_GET['gid']."&pid=".$_GET['pid']."'>".$row['first_name']." ".$row['last_name']."</a>";
	   $users[$i]['recent activity'] = (($row['last_action']=="")?"none":$row['last_action']);
	   $i++;	
	}
	;
	echo('<br>');
	$GLOBALS['page']->tableStart("","100%","TAB","Participants");
	echo("<br>To view someone's project work, choose a name from the list:");
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($users,"odd","even","70%",$GLOBALS['db']->numberOfRows,"thoughts");
	$GLOBALS['page']->tableEnd("GRID");
	$GLOBALS['page']->tableEnd("TAB");

	
	include("v_footer.php");
	exit;
	}	
	
	
	$result40 = $GLOBALS['db']->execQuery("select project_id from user_projects where user_id = ".$GLOBALS['user']->user_id);
	$idArray = array();
	$idArrNum = 0;
	while ($row = mysql_fetch_assoc($result40)){
	$idArray[$idArrNum] = $row['project_id'];
	$idArrNum++;
	}
	
	$idList = implode(",",$idArray);
	if($idList == ""){$idList = "0";}
	
$result = $GLOBALS['db']->execQuery("select projects.project_id,project_name,project,date_format(last_action,'%m.%d.%y') as last_action from projects where (globalYN = 'Y' or projects.project_id in (".$idList.")) and projects.group_id = ".$_GET['gid']."  order by last_action desc");
	$i = 0;
	$projects = array();
	while ($row = mysql_fetch_assoc($result)) {
	   $projects[$i]['project'.(($_GET['a']<>'view')?" (m=mandatory / v=voluntary)":"")] = '<img src="icon_proj.gif" width="14" height="14" alt="" hspace=1 align="top">'. (($_GET['a']=='view')?"<a href='index.php?a=projects&gid=".$_GET['gid']."&pid=".$row['project_id']."'>".substr($row['project_name'],0,30)."..."."</a>":"<a href='index.php?a=projects&gid=".$_GET['gid']."&pid=".$row['project_id']."'>".$row['project_name']."</a>").((in_array($row['project_id'],$idArray))?" <span style='font-size:10px'>[v]</span>":" <span style='font-size:10px'>[m]</span>");
	   $projects[$i]['activity'] = (($row['last_action']=="")?"none":$row['last_action']);
	   $i++;
   }
    
	$linkIt=false;
	if($_GET['a'] == 'view' and count($projects) >= 5){
	$linkIt = true;
	$newProj = array();
	for($x=0;$x<5;$x++){
	$newProj[$x] = $projects[$x];
	}
	$projects = $newProj;
	}
	
	if($_GET['a'] <> "view"){
	echo("
	<strong>Instructions: </strong>Choose a project from your list. You can add to your list by creating a new project or by viewing the voluntary list and selecting something that interests you<br>
	<br>
	");
	}
	
	$GLOBALS['page']->tableStart("","100%","TAB","<a class='tabanchor'  href='index.php?a=projects&gid=".$_GET['gid']."'>My Projects</a>");
	if(count($projects)>0){
	echo("<a href='index.php?a=projects&gid=".$_GET['gid']."&frm=create' style='text-decoration:none'><img src='icon_new.gif' width='16' height='16' alt='' border='0'></a> <a href='index.php?a=projects&gid=".$_GET['gid']."&frm=create'>Create a new project</a><br>
<a href='index.php?a=projects&gid=".$_GET['gid']."&frm=create' style='text-decoration:none'><img src='icon_look.gif' width='16' height='16' alt='' border='0' align='top'></a> <a href='index.php?a=projects&gid=".$_GET['gid']."&find=extra'>Voluntary projects list</a>");
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($projects,"odd","even","70%",$GLOBALS['db']->numberOfRows,"projects");
	$GLOBALS['page']->tableEnd("GRID");
	if($linkIt == true){echo("<a href='index.php?a=projects&gid=".$_GET['gid']."' class='more'>Click here for complete List</a>");}
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("<a href='index.php?a=projects&gid=".$_GET['gid']."&frm=create' style='text-decoration:none'><img src='icon_new.gif' width='16' height='16' alt='' border='0'></a> <a href='index.php?a=projects&gid=".$_GET['gid']."&frm=create'>Create a new project</a><br>
<a href='index.php?a=projects&gid=".$_GET['gid']."&frm=create' style='text-decoration:none'><img src='icon_look.gif' width='16' height='16' alt='' border='0' align='top'></a> <a href='index.php?a=projects&gid=".$_GET['gid']."&find=extra'>Voluntary projects list</a>");
	echo("<br><br>
	 &nbsp;&nbsp;Your project list is empty<br>
	");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	$GLOBALS['page']->tableEnd("TAB");
	
	?>