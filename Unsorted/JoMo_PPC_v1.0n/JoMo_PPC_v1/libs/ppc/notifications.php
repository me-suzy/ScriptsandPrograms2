<?
/*
###############################
#
# JoMo Easy Pay-Per-Click Search Engine v1.0
#
#
###############################
#
# Date                 : September 16, 2002
# supplied by          : CyKuH [WTN]
# nullified by         : CyKuH [WTN]
#
#################
#
# This script is copyright L 2002-2012 by Rodney Hobart (JoMo Media Group),
All Rights Reserved.
#
# The use of this script constitutes acceptance of any terms or conditions,
#
# Conditions:
#  -> Do NOT remove any of the copyright notices in the script.
#  -> This script can not be distributed or resold by anyone else than the
author, unless special permisson is given.
#
# The author is not responsible if this script causes any damage to your
server or computers.
#
#################################

*/
?>
<?php
/**
notifications
*/

/**
notify task:
	taskID, notifyName, memberID, memberType, taskDate, isDone
	
*/


$notifyEvents = array("LowMoney", "OutMoney", "NotActiveAccount");

$checkEventFuncs = array(
	"LowMoney"=>"checkLowMoney",
	"OutMoney"=>"checkOutMoney",
	"NotActiveAccount"=>"checkNotActiveAccount"
);

$notifyFuncs = array(
	"LowMoney"=>"notifyLowMoney",
	"OutMoney"=>"notifyOutMoney",
	"NotActiveAccount"=>"notifyNotActiveAccount",
);


function isNotify($option){
	global $dbObj;
   $dbSet=new xxDataset($dbObj);

   $dbSet->open("SELECT isEnable FROM notifications WHERE notifyName='".$option."'");
   $row = $dbSet->fetchArray();
   return $row["isEnable"]==1;
}

function getNotifyFreq($option){
	global $dbObj;
   $dbSet=new xxDataset($dbObj);

   $dbSet->open("SELECT * FROM notifications WHERE notifyName='".$option."'");
   $row = $dbSet->fetchArray();
   return $row["freq"];
}

function getNotification($option){
	global $dbObj;
   $dbSet=new xxDataset($dbObj);

   $dbSet->open("SELECT * FROM notifications WHERE notifyName='".$option."'");
   $row = $dbSet->fetchArray();
   return $row;
}

function addTask($result){
	global $dbObj;
   	$dbSet=new xxDataset($dbObj);
	
	$strColumns=""; $strValues="";
    makeInsertList($strColumns,$strValues,$result,array("taskID","taskDate"));
    
    if (isset($result["taskDate"])){
    	$strColumns.=", taskDate"; $strValues.=", ".$result["taskDate"];
    }
    $table="notifytasks";
    $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.") VALUES (".$strValues." )");
    return $id;
}

function updateTask($taskID, $result){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        $strSet="";
        makeUpdateList($strSet,$result,array("taskID"));

        $table="notifytasks";
        $id = $dbSet->execute("UPDATE ".$table." SET $strSet WHERE task = $taskID");

        return $id;
}

function getTask($taskID){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        $table="notifytasks";
        $dbSet->open("SELECT * FROM ".$table." WHERE task = $taskID");

        return $dbSet->fetchArray();
}

function getTasks($notifyName, $memberID, $isDone = 1, $memberType="member"){
	global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        $table="notifytasks";
        $dbSet->open("SELECT *, UNIX_TIMESTAMP(taskDate) as unixtimestamp FROM ".$table." 
        	WHERE notifyName = '".$notifyName."' AND memberID=$memberID AND memberType='".$memberType."' AND isDone=$isDone
        	ORDER BY taskDate ASC");
		$tasks = array();
		while ($row=$dbSet->fetchArray()){
			$tasks[]=$row;
		}
		
        return $tasks;
}

function delTask($taskID){
	global $dbObj ;
    $dbSet=new xxDataset($dbObj);
    
    $dbSet->execute("DELETE FROM notifytasks WHERE taskID=$taskID");
}

function delTasks($notifyName, $memberID, $memberType){
	global $dbObj ;
    $dbSet=new xxDataset($dbObj);
    
    $dbSet->execute("DELETE FROM notifytasks 
    	WHERE notifyName='".$notifyName."' AND memberID=$memberID AND memberType='".$memberType."'");
    	
}

function delDoneTasks($notifyName, $memberID, $memberType){
	global $dbObj ;
    $dbSet=new xxDataset($dbObj);
    
    $dbSet->execute("DELETE FROM notifytasks 
    	WHERE isDone=1 AND notifyName='".$notifyName."' AND memberID=$memberID AND memberType='".$memberType."'");
    	
	
}

function delUndoneTasks($notifyName, $memberID, $memberType){
	global $dbObj ;
    $dbSet=new xxDataset($dbObj);
    
    $dbSet->execute("DELETE FROM notifytasks 
    	WHERE isDone=0 AND notifyName='".$notifyName."' AND memberID=$memberID AND memberType='".$memberType."'");   	
}


function sendForgotPassword($email, $type="member"){
    global $dbObj ;
    $dbSet=new xxDataset($dbObj);
    
    	$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);

 
    // check email
    $dbSet->open("SELECT * FROM $table WHERE email='".$email."'");
    $member = $dbSet->fetchArray();
    $n = $dbSet->numRows();
    if ($n==0) return false;
    
    // send email
    $html = "your forgotten password: <BR>"."login: ".$member["login"]."<BR>"."pwd:".$member["password"];
    $text = "your forgotten password: "."\nlogin: ".$member["login"]."\n"."pwd:".$member["password"];
    
    $toName = $member["firstName"]." ".$member["lastName"];
    $subject = "forgotten password";
    sendMailProfile($toName, $email,getOption("notificationName"),getOption("notificationEmail"),$subject,$html,$text,array(),array());
    
    return true;
   
 }

 function notifyWelcome($memberID, $type="member"){
    global $dbObj;
    $dbSet=new xxDataset($dbObj);

    	$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);
 
 	$member=getMember($memberID, $type);
 	$member=$member["info"];
	$email=$member["email"];
	
	// check admin option "notifyWelcome"
	if (isNotify("Welcome")==0){
		return false;
	}
	
    // send email
    $html = "Welcome, ".$member["firstName"]." ".$member["lastName"]."!<BR>"."login:".$member["login"]."<br>pwd:".$member["password"];
    $text = "Welcome, ".$member["firstName"]." ".$member["lastName"]."!\n"."login:".$member["login"]."\npwd:".$member["password"];
    $toName = $member["firstName"]." ".$member["lastName"];
    sendMailProfile($toName, $email,getOption("notificationName"),getOption("notificationEmail"),"welcome",$html,$text,array(),array());
    
    return true;
 }
 
 function sendmail_to_user($firstname, $lastname, $email, $subject, $message, $fromName="", $fromEmail=""){
  	$html = "<b>Hello, $firstname $lastname.</b><br></p>$message<p>";
  	$text = "Hello, $firstname $lastname.\n$message";
  	if ($fromName=="")
  		$from = getOption("notificationName");
  	if ($fromEmail=="")
  		$from = getOption("notificationEmail");
  	
  	$name = $firstname." ".$lastname;
  	sendMailProfile($name, $email,$fromName,$fromEmail, $subject,$html,$text,array(),array());
  	return true;
 }
 
 function notifyOutMoney($memberID){
 	global $dbObj;
    $dbSet=new xxDataset($dbObj);

	$type = "member";
    $table = $acctable=$columnID="";
	getMemberTables($type, $table,$acctable,$columnID);
 
 	$member=getMember($memberID, $type);
 	$info=$member["info"]; $account=$member["account"];
	$email=$info["email"];
	
	// check admin option "notifyWelcome"
	if (isNotify("OutMoney")==0){
		return false;
	}
	
    // send email
    $html = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."Your account is out of funds";
    $text = " ".$info["firstName"]." ".$info["lastName"]."!\n"."Your account is out of funds";
    
    $toName = $info["firstName"]." ".$info["lastName"];
    $subject = "money is out";
    sendMailProfile($toName, $email,getOption("notificationName"),getOption("notificationEmail"),$subject,$html,$text,array(),array());
    
    return true;
 	
 }
 
 function notifyLowMoney($memberID){
 	global $dbObj;
    $dbSet=new xxDataset($dbObj);

	$type = "member";
    $table = $acctable=$columnID="";
	getMemberTables($type, $table,$acctable,$columnID);
 
 	$member=getMember($memberID, $type);
 	$info=$member["info"]; $account=$member["account"];
	$email=$info["email"];
	
	// check admin option 
	if (isNotify("LowMoney")==0){
		return false;
	}
	
    // send email
    $html = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."Your money is low: $".$account["balance"];
    $text = " ".$info["firstName"]." ".$info["lastName"]."!\n"."Your money is low: $".$account["balance"];
    
    $toName = $info["firstName"]." ".$info["lastName"];
    $subject = "low money";
    sendMailProfile($toName, $email,getOption("notificationName"),getOption("notificationEmail"),$subject,$html,$text,array(),array());
    
    return true;
 }
 
 function notifyNotActiveAccount($memberID){
 	global $dbObj;
    $dbSet=new xxDataset($dbObj);

	$type = "member";
    $table = $acctable=$columnID="";
	getMemberTables($type, $table,$acctable,$columnID);
 
 	$member=getMember($memberID, $type);
 	$info=$member["info"]; $account=$member["account"];
	$email=$info["email"];
	
	// check admin option 
	if (isNotify("NotActiveAccount")==0){
		return false;
	}
	
    // send email
    $html = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."Your account is not active. <br>".
    	"You have ".$account["balance"];
    $text = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."Your account is not active. \n".
    	"You have ".$account["balance"];

    $toName = $info["firstName"]." ".$info["lastName"];
    $subject = "account is not active";
    sendMailProfile($toName, $email,getOption("notificationName"),getOption("notificationEmail"),$subject,$html,$text,array(),array());

  	
  	//echo "$html";
    return true;
 }
 
 
function notifyAdminAdd($memberID, $cost){
 	global $dbObj;
    $dbSet=new xxDataset($dbObj);

	$type = "member";
    $table = $acctable=$columnID="";
	getMemberTables($type, $table,$acctable,$columnID);
 
 	$member=getMember($memberID, $type);
 	$info=$member["info"]; $account=$member["account"];
	$email=$info["email"];
	
	// check admin option 
	if (isNotify("Payment")==0){
		return false;
	}
	
    // send email
    $html = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."Admin added/withdrawed $cost to your account. <br>".
    	"Now you have ".$account["balance"];
    $text = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."Admin added/withdrawed $cost to your account. <br>".
    	"Now you have ".$account["balance"];

    $toName = $info["firstName"]." ".$info["lastName"];
    $subject = "account changed";
    sendMailProfile($toName, $email,getOption("notificationName"),getOption("notificationEmail"),$subject,$html,$text,array(),array());

    return true;
 }
 
 function notifyDeposit($memberID, $cost){
 	global $dbObj;
    $dbSet=new xxDataset($dbObj);

	$type = "member";
    $table = $acctable=$columnID="";
	getMemberTables($type, $table,$acctable,$columnID);
 
 	$member=getMember($memberID, $type);
 	$info=$member["info"]; $account=$member["account"];
	$email=$info["email"];
	
	// check admin option 
	if (isNotify("Payment")==0){
		return false;
	}
	
    // send email
    $html = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."You have deposited $cost to your account. <br>".
    	"Now you have $".$account["balance"];
    $text = " ".$info["firstName"]." ".$info["lastName"]."!<BR>"."You have deposited $cost to your account. <br>".
    	"Now you have $".$account["balance"];

    $toName = $info["firstName"]." ".$info["lastName"];
    $subject = "account changed";
    sendMailProfile($toName, $email,getOption("notificationName"),getOption("notificationEmail"),$subject,$html,$text,array(),array());
  	
  	//echo "$html";
    return true;
 }
  
 
/**
check for event - if account is low money
*/
function checkLowMoney($memberID, $memberType="member"){
	$lowMoney = getOption("lowMoney");
	$member = getMember($memberID, $memberType);
	$balance = $member["account"]["balance"];
	
	//dprint("func- check event low money, member=$memberID, balance=$balance");
	
	if (0<=$balance && $balance < $lowMoney){
		return 1;
	}
	else return 0;
}

/**
check for event - if account is out 
*/
function checkOutMoney($memberID, $memberType="member"){
	$member = getMember($memberID, $memberType);
	$balance = $member["account"]["balance"];
	
	//dprint("check- event out money");
	
	if ($balance <= 0){
		return 1;
	}
	else return 0;
}


/**
check for event - if account is not active
*/
function checkNotActiveAccount($memberID, $memberType="member"){
	$member = getMember($memberID, $memberType);
	$balance = $member["account"]["balance"];
	
	if ($member["account"]["isActive"] == 0){
		return 1;
	}
	else return 0;
}

function checkEvent($event, $memberID, $memberType="member"){
	global $checkEventFuncs;
	
	$func = $checkEventFuncs[$event];
	return $func($memberID, $memberType);
}

function notify($notifyName, $memberID, $memberType="member"){
    global $dbObj;
    $dbSet=new xxDataset($dbObj);
    
    global $notifyFuncs;

   	$notifyFunc = $notifyFuncs[$notifyName];
   	return $notifyFunc($memberID, $memberType);
   	
}

/**
check tasks of specified type for the member
*/
function checkTasks($notifyName, $memberID, $memberType="member"){
	global $notifyFuncs;
	global $dbObj;
	$dbSet=new xxDataset($dbObj);
	   
	// get member
	$member = getMember($memberID, $memberType);
	$memberID = $member["info"]["memberID"];
	
	$notification = getNotification($notifyName);
	
	// check admin option
	if (isNotify($notifyName)==0) return false;
	
	// check event
	//dprint("check event $notifyName");
	if (checkEvent($notifyName, $memberID, $memberType)==0){
		// delete all tasks for this event
		delTasks($notifyName, $memberID, "member");
	 	return 0;
	}
	//dprint("event $notifyName is set");
	
	// check notifyType
	$notifyType = $notification["notifyType"];
	
	// get done tasks of this type for this member
	$doneTasks = getTasks($notifyName,$memberID,1, "member");

	$freqdays = getNotifyFreq($notifyName); // in days
	$freq = $freqdays * 24*60*60;
	//$freq = $freqdays * 60;  // for debug
	
	$curtime = time();
	
	if (!empty($doneTasks) && sizeof($doneTasks)>0){
		// del all before tasks	
		$lastDoneTask = $doneTasks[0];
		$t = $lastDoneTask["unixtimestamp"];
		//dprint("del old done tasks $notifyName, $memberID, "	);
		$dbSet->execute("DELETE FROM notifytasks WHERE 
			notifyName='".$notifyName."' AND memberID=$memberID AND UNIX_TIMESTAMP(taskDate)<$t");
	}

	// get undone tasks of this type for this member
	$undoneTasks = getTasks($notifyName,$memberID,0, "member");
	
	/*
	if (!empty($undoneTasks) && sizeof($undoneTasks)>0){
		$lastUndoneTask = $undoneTasks[0];
		$t = $lastDoneTask["unixtimestamp"];
		// del before undone tasks
		$dbSet->execute("DELETE FROM notifytasks WHERE UNIX_TIMESTAMP(taskDate)<$t AND isDone=0");
		
		// do the task
		notify($notifyName, $member["info"]["memberID"], $memberType);
		delTasks($notifyName, $memberID, "member");
		addTask(array("notifyName"=>$notifyName, "memberID"=>$memberID, "memberType"=>"member", "isDone"=>1, "taskDate"=>"NOW()"));
		return;		
	}
	*/
	

	// check freq
		
	//print_r($tasks);
					
	// no tasks
	if (sizeof($doneTasks)==0 || empty($doneTasks)){
		//dprint("no prev tasks");
		notify($notifyName, $member["info"]["memberID"], $memberType);
		delTasks($notifyName, $memberID, "member");
		addTask(array("notifyName"=>$notifyName, "memberID"=>$memberID, "memberType"=>"member", "isDone"=>1, "taskDate"=>"NOW()"));
		return;
	}
	else{
		if ($notifyType=="once"){
			return;
		}

		$lastDoneTask = $doneTasks[0];
		$taskDate = $lastDoneTask["unixtimestamp"];
		
		// check freq			
		if ($curtime-$taskDate > $freq){
			notify($notifyName, $memberID, $memberType);
			delTasks($notifyName, $memberID, "member");
			addTask(array("notifyName"=>$notifyName, "memberID"=>$memberID, "memberType"=>"member", "isDone"=>1, "taskDate"=>"NOW()"));
		}
	}

}

function checkAllNotifyTasks(){
		global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        global $notifyEvents;

		// get all members
		$dbSet->open("SELECT m.*,ma.balance, ma.isActive 
			FROM members m INNER JOIN memberaccounts ma ON m.memberID=ma.memberID");
		$members=array();
		while ($row=$dbSet->fetchArray()){
			$members[]=$row;
		}
		
		for ($i=0; $i<sizeof($members); $i++){
			$member = $members[$i];
			//dprint("check member ".$member["memberID"]);
			// check all events for the member
			foreach ($notifyEvents as $event){
				checkTasks($event, $member["memberID"], "member");
			}
		}
}

?>
