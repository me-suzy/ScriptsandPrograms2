<?php
	
	$user = new user();
	
	
		
		if(isset($_GET['uid'])){
		
		
		$resss = $GLOBALS['db']->execQuery("select user_groups.group_id from user_groups where ".		(($GLOBALS['user']->email == $GLOBALS['admin_email'])?"1=1":"user_groups.user_id = ".$GLOBALS['user']->user_id));
		$idList = "0";
		while ($row = mysql_fetch_assoc($resss)){
		$idList .= "," . $row['group_id'];
		}
		
		
				$message = array();
			
			$i=0;
			$result3 = $GLOBALS['db']->execQuery("select count(messages.message_id) from messages,users,topics where messages.group_id in (".$idList.") and users.user_id = messages.created_by and users.user_id = ".$_GET['uid']." and messages.topic_id = topics.topic_id");

			$totalRecords = mysql_result($result3,0);

			$result = $GLOBALS['db']->execQuery("select message_id, messages.discussion_id,topic,messages.topic_id,message, first_name, last_name,messages.group_id,email, date_format(created_on,'%m.%d.%y %h:%i %p') as created_on, messages.created_by from messages, topics, users where messages.group_id in (".$idList.") and users.user_id = messages.created_by and users.user_id = ".$_GET['uid']." and messages.topic_id = topics.topic_id order by messages.created_on desc",true);
			while ($row = mysql_fetch_assoc($result)) { 
			   $message[$i]['message list for '.$row['first_name']." ".$row['last_name']." (most recent message listed first)"] = "<img src='icon_message.gif' width='16' height='14' alt='' border=0 hspace=1 align='top'> <strong>Date: </strong>".$row["created_on"]." <strong>Topic: </strong><a href='index.php?a=discuss&gid=".$row['group_id']."&tid=".$row['topic_id']."&did=".$row['discussion_id']."'>".$row['topic']."</a> <strong> By: </strong>".$row["first_name"]." ".$row["last_name"]." <strong>email: </strong> <a href='mailto:".$row['email']."'>".$row['email']."</a> <br>".$row["message"];
			   $i++;
		   }
	 	$GLOBALS['page']->tableStart("","100%","TAB","List of messages by user");
		if(count($message)>0){
	echo("Total messages posted by this person:<strong>".$totalRecords."</strong>");	
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($message,"odd","even","12%",$GLOBALS['db']->numberOfRows);
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("This person has not posted messages in the discussion area");
	$GLOBALS['page']->tableEnd("TEXT");
	} 
	$GLOBALS['page']->tableEnd("TAB");
		
		exit;
		}
		
		
	
		if (isset($_POST['submit'])){
		
		
		
		//make sure the email is not a duplicate (user only)
		
		$result11 = $GLOBALS['db']->execQuery("select users.user_id,email from users where email = '".$_POST['email']."' and email <> '".$GLOBALS['user']->email."'".(($GLOBALS['user']->email == $GLOBALS['admin_email'])?' and 1=0':''));

		$num_rows11 = mysql_num_rows($result11);
	
		
		if( ($GLOBALS['user']->user_id <> $_GET['mid']) and ($GLOBALS['user']->email <> $GLOBALS['admin_email']) ){$GLOBALS['error'][0] = "You do not have access to edit this user";}

		if( ($num_rows11 > 0)){$GLOBALS['error'][0] = "This email address already exists";}

		if( ($_POST['email'] == $GLOBALS['admin_email'])){
		$GLOBALS['error'][0] = "That email address is reserved";
		}
		
		$GLOBALS['db']->checkTyped($_POST['first_name'],"You must enter a first name");
		$GLOBALS['db']->checkNames($_POST['first_name'],"Names must not have special characters");
		$GLOBALS['db']->checkLen($_POST['first_name'],30,"First name must be less than 30 characters");
		$GLOBALS['db']->checkTyped($_POST['last_name'],"You must enter a last name");
		$GLOBALS['db']->checkNames($_POST['first_name'],"Names must not have special characters");
		$GLOBALS['db']->checkLen($_POST['last_name'],30,"Last name must be less than 30 characters");
		$GLOBALS['db']->checkTyped($_POST['email'],"You must enter an email address");
		$GLOBALS['db']->checkLen($_POST['email'],50,"email address must be less than 50 characters");
		$GLOBALS['db']->checkEmail($_POST['email'],"email address contains invalid characters");
		$GLOBALS['db']->checkTyped($_POST['email2'],"You must re-enter an email address");
		$GLOBALS['db']->checkTyped($_POST['pword'],"You must enter a password");
		$GLOBALS['db']->checkLen($_POST['pword'],15,"Passwords must be less than 16 characters");
		$GLOBALS['db']->checkTyped($_POST['pword2'],"You must re-enter a password");
		$GLOBALS['db']->compareTwo($_POST['pword2'],$_POST['pword'],"The passwords do not match");
		$GLOBALS['db']->compareTwo($_POST['email'],$_POST['email2'],"The emails do not match");
		
			if(count($GLOBALS['error'])==0){
			
			$GLOBALS['db']->execQuery("update users set first_name = '" . trim($_POST['first_name']) . "', last_name = '" . trim($_POST['last_name']) . "',pword = '" . strtolower(trim($_POST['pword'])) . "', email = '" . strtolower(trim($_POST['email'])). "' where user_id = ".$_GET['mid']);
			$GLOBALS['page']->pleaseWait("Please wait while we update this user","index.php?a=members".((isset($_GET['gid']))?"&gid=".$_GET['gid']:""));
			include("v_footer.php");
			exit;
			}
}
	
	
	$users = array();

	
	$resss = $GLOBALS['db']->execQuery("select user_groups.group_id from user_groups where ".		(($GLOBALS['user']->email == $GLOBALS['admin_email'])?"1=1":"user_groups.user_id = ".$GLOBALS['user']->user_id));
	$idList = "0";
	while ($row = mysql_fetch_assoc($resss)){
	$idList .= "," . $row['group_id'];
	}

	$result = $GLOBALS['db']->execQuery("select users.user_id,first_name,last_name,email,owner_id,pword, group_name from users, user_groups, groups where users.user_id = user_groups.user_id  and user_groups.group_id = groups.group_id and user_groups.group_id in (".$idList.") order by ".((isset($_GET['o']))?'last_name asc, group_name asc':'group_name asc,last_name asc'));
	$i=0;
	while ($row = mysql_fetch_assoc($result)) {
	   $users[$i]['<a href="index.php?a=members'.(isset($_GET['gid'])?"&gid=".$_GET['gid']:"").'">course</a>'] = $row['group_name'];
	   $users[$i]['<a href="index.php?a=members'.(isset($_GET['gid'])?"&gid=".$_GET['gid']:"").'&o=1">name</a>'] = "<a href='index.php?a=members&uid=".$row['user_id'].(isset($_GET['gid'])?"&gid=".$_GET['gid']:"")."'>". $row['first_name']." ".$row['last_name'].(($row['owner_id'] == $row['user_id'])?"<strong>*</strong>":"")."</a>";
	   $users[$i]['email'] = $row["email"];
	   $users[$i][''] = ( (($GLOBALS['owner']==true) or $GLOBALS['user']->user_id == $row['user_id'])?"<a href='index.php?a=members&mid=".$row['user_id'].((isset($_GET['gid']))?"&gid=".$_GET['gid']:"")."#form' style='text-decoration:none'><img src='icon_edit.gif' width='16' height='16' alt='edit this user' border='0'>edit</a> ":"");
	   $i++;	
	}
	
	
	$GLOBALS['page']->tableStart("","100%","TAB","Member Info");
	
	if(isset($_GET['mid'])){
	
	
		$formFill = array();
		$result3 = $GLOBALS['db']->execQuery("select distinct users.user_id,first_name,last_name,email,pword from users where users.user_id =  ".(($GLOBALS['user']->email == $GLOBALS['admin_email'])?$_GET['mid']:$GLOBALS['user']->user_id));   
   	
   
   $zigg = array();
   while ($row = mysql_fetch_assoc($result3)) { 
   $zigg['first_name'] = $row['first_name'];
   $zigg['last_name'] = $row['last_name'];
   $zigg['email'] = $row['email'];
   $zigg['pword'] = $row['pword'];
   }
	
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function text($value,$name,$class,$desc,$size,$chngeOnPost=0
	//function checkbox($checkSuperArray,$name,$desc,$class,$chngeOnPost){
	
	echo("<br>");
	$GLOBALS['page']->text($zigg['first_name'],"first_name","inputs","First Name:",30,1);
	$GLOBALS['page']->text($zigg['last_name'],"last_name","inputs","Last Name:",30,1);
	$GLOBALS['page']->text($zigg['email'],"email","inputs","Email:",30,1);
	$GLOBALS['page']->text($zigg['email'],"email2","inputs","Type Email again:",30,1);
	$GLOBALS['page']->password($zigg['pword'],"pword","inputs","Password:",30,1);
	$GLOBALS['page']->password($zigg['pword'],"pword2","inputs","Type Password again:",30,1);
	
	$GLOBALS['page']->submit("Edit User Info","inputs");
	$GLOBALS['page']->tableEnd("FORM");
	
	}
	
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($users,"odd","even","");
	$GLOBALS['page']->tableEnd("GRID");
	
	$GLOBALS['page']->tableEnd("TAB");
	echo("<br>");
?>