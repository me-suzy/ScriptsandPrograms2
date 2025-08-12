
	<?php
	

	$user = new user();
	
	
	
	if(isset($_GET['did'])){
	
	//see if they are subscribing to a topic
	
	if(isset($_GET['subscribe'])){
	
		if($_GET['subscribe'] == 1){
		
		$GLOBALS['db']->execQuery("insert into user_discussions(user_id,discussion_id) values(".$GLOBALS['user']->user_id.",".$_GET['did'].")");
		
		}else{
		
		$GLOBALS['db']->execQuery("delete from user_discussions where user_id=".$GLOBALS['user']->user_id." and discussion_id = ".$_GET['did']);
		
		}
	
	}
	
	//see if they are trying to delete a message...
	if(isset($_GET['delmess'])){
	
		$GLOBALS['db']->execQuery("delete from messages where message_id = ".$_GET['mid']." and discussion_id = ".$_GET['did']." and topic_id = ".$_GET['tid']." and (created_by = ".$GLOBALS['user']->user_id." or ".(($GLOBALS['owner']==true)?"1=1":"1=0").")");
		$rest = $GLOBALS['db']->execQuery("select total_messages from discussions where discussion_id = ".$_GET['did']);
		$newMess = mysql_result($rest,0);
			if($newMess==""){$newMess=0;}
		$GLOBALS['db']->execQuery("update discussions set total_messages = ".($newMess)." where discussion_id =".$_GET['did']);
		$GLOBALS['page']->pleaseWait("Please wait while we delete this message","index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']);
			include("v_footer.php");
			exit;
	}
	
	//see if they are trying to delete a topic...
		if(isset($_GET['deltopic'])){
		$result = $GLOBALS['db']->execQuery("select count(message_id) as countm from messages where topic_id = ".$_GET['tid']);
		$mess = 0;
		while ($row = mysql_fetch_assoc($result)) { 
	    $mess = $row['countm'];
		}
		$GLOBALS['db']->execQuery("delete from topics where topic_id = ".$_GET['tid']." and discussion_id = ".$_GET['did']." and (created_by = ".$GLOBALS['user']->user_id." or  ".(($GLOBALS['owner']==1)?"1=1":"1=0"). ")");
$GLOBALS['db']->execQuery("delete from messages where topic_id = ".$_GET['tid']." and discussion_id = ".$_GET['did']." and (created_by = ".$GLOBALS['user']->user_id." or  ".(($GLOBALS['owner']==1)?"1=1":"1=0").")");
		
		$rest = $GLOBALS['db']->execQuery("select total_messages from discussions where discussion_id = ".$_GET['did']);
		$newMess = mysql_result($rest,0); 
		if($newMess==""){$newMess=0;}
		
		
		$GLOBALS['db']->execQuery("update discussions set total_messages = ".($newMess - $mess)." where discussion_id =".$_GET['did']);
		$GLOBALS['page']->pleaseWait("Please wait while we delete this topic","index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']);
			include("v_footer.php");
			exit;
	}
	
		if(isset($_POST['submit'])){
	
	switch($_POST['submit']){
	
		case "Add Topic":
		
		$GLOBALS['db']->checkTyped($_POST['topic'],"You must enter text for your topic");
		$GLOBALS['db']->checkLen($_POST['topic'],50,"Topics must be less than 50 characters");
				
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("insert into topics(topic,group_id,discussion_id,created_by) values('".trim($_POST['topic'])."',".$_GET['gid'].",".$_GET['did'].",".$GLOBALS['user']->user_id.")");
			$new_id = mysql_insert_id();
			$GLOBALS['db']->execQuery("insert into messages(message,topic_id,group_id,discussion_id,created_by,created_on) values('".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['message']),ENT_QUOTES))."',".$new_id.",".$_GET['gid'].",".$_GET['did'].",".$GLOBALS['user']->user_id.",now())");

			$rest = $GLOBALS['db']->execQuery("select total_messages from discussions where discussion_id = ".$_GET['did']);
		    $newMess = mysql_result($rest,0); 
			if($newMess==""){$newMess=0;}
		

			$GLOBALS['db']->execQuery("update discussions set total_messages = ".($newMess +1).", last_message = now() where discussion_id =".$_GET['did']);
			$GLOBALS['db']->execQuery("update topics set last_message = now() where topic_id =".$new_id);

			//send this topic to everyone that subscribed...
			$resz = $GLOBALS['db']->execQuery("select email from user_discussions,users where discussion_id = ".$_GET['did']." and user_discussions.user_id = users.user_id");
			$arrayMail = array();
			while ($row = mysql_fetch_assoc($resz)) { 
	 	 	array_push($arrayMail,$row['email']);
			}
			if(count($arrayMail)>0){
			$subject = "Wordcircle message notification"; 
			$message = 'This is an automated message from the wordcircle learning community server. A new message has been posted by '.$GLOBALS['user']->first_name.' '.$GLOBALS['user']->last_name.' in a topic that you subscribed to.  Please visit the website and join the conversation. Do not respond to this message, it is automated and not from a human being.';
			$headers = "From: Wordcircle <do-not-respond@yahoo.com>\r\n"; 

				if (mail(implode(",",$arrayMail), $subject, $message,$headers)){
				//it worked!
				}else{
				$GLOBALS['error'][0] = 'email was not sent - mailserver is not configured';
				}
			}
			

			$GLOBALS['page']->pleaseWait("Please wait while we add this topic","index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$new_id);
			include("v_footer.php");
			exit;
			}
		break;
		case "Edit Topic":
	
		$GLOBALS['db']->checkTyped($_POST['topic'],"You must enter text for your topic");
		$GLOBALS['db']->checkLen($_POST['topic'],50,"Topics must be less than 50 characters");
		//CHECK TO MAKE SURE THIS IS THE CREATOR!!!!!!!
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("update topics set topic = '". trim($_POST['topic'])."' where discussion_id = ".$_GET['did']." and topic_id = ".$_GET['tid']);
			$GLOBALS['page']->pleaseWait("Please wait while we edit this topic","index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']);
			include("v_footer.php");
			exit;
			}
		break;
		
		case "Add Message":

		$GLOBALS['db']->checkTyped($_POST['message'],"You must enter text for your message");
		$GLOBALS['db']->checkLen($_POST['message'],9000000,"messages must be less than 9000000 characters");
		if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("insert into messages(message,topic_id,group_id,discussion_id,created_by,created_on) values('".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['message']),ENT_QUOTES))."',".$_GET['tid'].",".$_GET['gid'].",".$_GET['did'].",".$GLOBALS['user']->user_id.",now())");
			$GLOBALS['db']->execQuery("update discussions set total_messages = total_messages + 1, last_message = now() where discussion_id =".$_GET['did']);
			$GLOBALS['db']->execQuery("update topics set last_message = now() where topic_id =".$_GET['tid']);

			//send this topic to everyone that subscribed...
			$resz = $GLOBALS['db']->execQuery("select email from user_discussions,users where discussion_id = ".$_GET['did']." and user_discussions.user_id = users.user_id");
			$arrayMail = array();
			while ($row = mysql_fetch_assoc($resz)) { 
	 	 	array_push($arrayMail,$row['email']);
			}
			if(count($arrayMail)>0){
			$subject = "Wordcircle message post notification"; 
			$message = 'This is an automated message from the wordcircle learning community server. A new message has been posted by '.$GLOBALS['user']->first_name.' '.$GLOBALS['user']->last_name; 
				if (mail(implode(",",$arrayMail), $subject, $message)){
				//it worked!
				}else{
				$GLOBALS['error'][0] = 'email was not sent - mailserver is not configured';
				}
			}

			$GLOBALS['page']->pleaseWait("Please wait while we add this message","index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']);		
		
			
			
			include("v_footer.php");
			exit;
			}
		break;
		
		case "Edit Message":
		
		$GLOBALS['db']->checkTyped($_POST['message'],"You must enter text for your message");
		$GLOBALS['db']->checkLen($_POST['message'],30000,"messages must be less than 30000 characters");
		if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("update  messages set message = '".str_replace(CHR(13).CHR(10), '<br>', trim(htmlspecialchars($_POST['message']),ENT_QUOTES))."' where message_id = ".$_GET['mid']);
			$GLOBALS['page']->pleaseWait("Please wait while we update this message","index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']);
			include("v_footer.php");
			exit;
			}
		
		break;
	}
	}
	
	
   

	if (isset($_GET['tid']) and (!isset($_GET['revise']))){

//show messages for this topic

	
   
  
	
	$message = array();
	$i=0;
	$result = $GLOBALS['db']->execQuery("select message_id, topic, message, first_name, last_name, email, date_format(created_on,'%m.%d.%y %h:%i %p') as created_on, messages.created_by from messages, topics, users where messages.discussion_id = ".$_GET['did']." and topics.topic_id = ".$_GET['tid']." and topics.topic_id = messages.topic_id and messages.group_id = ".$_GET['gid']." and users.user_id = messages.created_by order by messages.created_on asc, message_id asc",true);
	while ($row = mysql_fetch_assoc($result)) { 
	   $message[$i]['<a href="index.php?a=discuss&gid='.$_GET['gid'].'&did='.$_GET['did'].'">'.$row['topic'].'</a>'] = "<table width='100%'><tr><td><img src='icon_message.gif' width='16' height='14' alt='' border=0 hspace=1 align='top'> <strong>Date: </strong>".$row["created_on"]."<strong> By: </strong><a href='index.php?a=members&uid=".$row['created_by']."&gid=".$_GET['gid']."'>".$row["first_name"]." ".$row["last_name"]."</a> <strong>email: </strong><a href='mailto:".$row['email']."'>".$row['email']."</a><br>".html_entity_decode($row["message"])."</td><td align='right' valign='top'>".(($row["created_by"] == $GLOBALS['user']->user_id or ($GLOBALS['owner'] == true))?"<a href='index.php?a=discuss&tid=".$_GET['tid']."&gid=".$_GET['gid']."&did=".$_GET['did']."&mid=".$row['message_id']."&revisemess=1#form'><img src='icon_edit.gif' width='16' height='16' alt='edit this message' border='0'>edit</a> <a href='index.php?a=discuss&delmess=1&gid=".$_GET['gid']."&did=".$_GET['did']."&mid=".html_entity_decode($row['message_id'])."&tid=".$_GET['tid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this message?'); return confirmDownload;\"><img src='icon_delete.gif' width='16' height='16' alt='delete this message'  border='0'>remove</a></div>":'<img src="icon_singlepx.gif">')."</td></tr></table>";

	   $i++;
   }
   
   	$linkArray[0] = "index.php?a=editdiss&gid=".$_GET['gid'];
   	$GLOBALS['page']->tableStart("","100%","TAB","<img src='icon_message.gif' width='16' height='14' alt='' border=0 hspace=1 align='top'> Messages",$linkArray);
	
	$result = $GLOBALS['db']->execQuery("select discussion_name, topic from topics,discussions where discussions.discussion_id = topics.discussion_id and topic_id = ".$_GET['tid']);
	while ($row = mysql_fetch_assoc($result)) { 
	   echo("<div align='center'><br>
	   <a href='index.php?a=discuss&gid=".$_GET['gid']."'><img src='icon_group.gif' width='14' height='17' align='top' hspace=1 border=0> Discussion list</a> &middot; <a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."'><img src='icon_folder.gif' width='16' height='13' border=0 align='top' hspace=1> ".$row['discussion_name']."</a> &middot; <img src='icon_message.gif' width='16' height='14' alt='' border=0 hspace=1 align='top'> ".$row['topic']."</div>");
   }
	if (isset($_GET['revisemess'])){
		$dmess = "";
		$result2 = $GLOBALS['db']->execQuery("select message from messages where message_id = ".$_GET['mid']);
		while ($row2 = mysql_fetch_assoc($result2)) { 
		$dmess = str_replace('<br>',CHR(13).CHR(10), html_entity_decode(trim($row2['message'])));
   		}	
		

		$GLOBALS['page']->tableStart("","100%","FORM");
		//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
		$GLOBALS['page']->textarea($dmess,"message","inputs",10,100,"Edit Message:",1);
		$GLOBALS['page']->submit("Edit Message","inputs");
				echo("<tr><td>&nbsp;</td><td><a href='index.php?a=discuss&delmess=1&gid=".$_GET['gid']."&did=".$_GET['did']."&mid=".$_GET['mid']."&tid=".$_GET['tid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this message?'); return confirmDownload;\">[Delete this message by clicking here]</a></td></tr>");
echo("<tr><td>&nbsp;</td><td><a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']."'>[Cancel by clicking here]</a></td></tr>");
		$GLOBALS['page']->tableEnd("FORM");
		
		}
		
		if (isset($_GET['newm'])){
		 
		$GLOBALS['page']->tableStart("","100%","TEXT");
		  echo("<a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']."'><img src='icon_list.gif' width='18' height='18' alt='' border=0> Return to message list</a><br>
");
		$GLOBALS['page']->tableEnd("TEXT");
		$GLOBALS['page']->tableStart("","100%","FORM");
		//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
		$GLOBALS['page']->textarea("","message","inputs",10,100,"Enter Message:",1);
		$GLOBALS['page']->submit("Add Message","inputs");
		$GLOBALS['page']->tableEnd("FORM");
		
		
		}
	 if(!isset($_GET['newm'])){
	$GLOBALS['page']->tableStart("","100%","TEXT");
	 
	
	 echo("<a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']."&newm=1'><img src='icon_respond.gif' width='16' height='14' alt='' border=0> Add your response by clicking here</a><br>
");
		$GLOBALS['page']->tableEnd("TEXT");
		}	

	if(count($message)>0){
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($message,"odd","even","12%",$GLOBALS['db']->numberOfRows);
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no messages for this topic");
	$GLOBALS['page']->tableEnd("TEXT");
	} 
	
	if(!isset($_GET['newm'])){
	$GLOBALS['page']->tableStart("","100%","TEXT");
	 
	 
	 echo("<br>
	 <a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']."&newm=1'><img src='icon_respond.gif' width='16' height='14' alt='' border=0> Add your response by clicking here</a><br>
");
		$GLOBALS['page']->tableEnd("TEXT");
		}
		$GLOBALS['page']->tableEnd("TAB");
	
  

	

}else{

//show topics for this discussion

 //see if this user is already subscribed
	$result100 = $GLOBALS['db']->execQuery("select user_id from user_discussions where discussion_id = ".$_GET['did']." and user_id = ".$GLOBALS['user']->user_id);
	if (mysql_num_rows($result100)>0){
	$subscribed = 1;
	}else{
	$subscribed = 0;
	};

	$result = $GLOBALS['db']->execQuery("select topic_id,discussion_name,created_by,first_name,last_name,email,topic,date_format(topics.last_message,'%m.%d.%y') as last_message from topics,discussions,users where discussions.discussion_id = ".$_GET['did']." and created_by = user_id and topics.discussion_id = discussions.discussion_id order by topics.last_message desc, topic_id desc");
	$i = 0;
	$topics = array();
	while ($row = mysql_fetch_assoc($result)) { 
	   $topics[$i]["<a href='index.php?a=discuss&gid=".$_GET['gid']."'>".$row['discussion_name']."</a><img src='icon_singlepx.gif' width='10' height='1'>".(($subscribed==0)?"<a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&subscribe=1'><img src='icon_email.gif' width='16' height='16' alt='topic subscription' border='0'>subscribe by email":"<a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&subscribe=0'><img src='icon_email.gif' width='16' height='16' alt='topic subscription' border='0'>unsubscribe")."</a>"] = "<a href='index.php?a=discuss&tid=".$row['topic_id']."&gid=".$_GET['gid']."&did=".$_GET['did']."'><img src='icon_folder.gif' width='16' height='13' border=0 align='top' hspace=1> ".$row["topic"]."</a>";
	   $topics[$i]['creator'] = $row["first_name"] . " " . $row["last_name"];
	      $topics[$i]['last post'] = $row["last_message"];
		   $topics[$i][''] = ((($row["created_by"] == $GLOBALS['user']->user_id) or ($GLOBALS['owner'] == true))?"<a href='index.php?a=discuss&tid=".$row['topic_id']."&gid=".$_GET['gid']."&did=".$_GET['did']."&revise=1#form'><img src='icon_edit.gif' width='16' height='16' alt='Edit' border='0' alt='edit this topic'>edit</a> <a href='index.php?a=discuss&deltopic=1&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$row['topic_id']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this topic\\nNote: All messages for the topic will also be deleted?'); return confirmDownload;\"><img src='icon_delete.gif' width='16' height='16' alt='delete this item' border='0'>remove</a>":'');
	   $i++;
   }
   
	$linkArray[0] = "index.php?a=editdiss&gid=".$_GET['gid'];
   	$GLOBALS['page']->tableStart("","100%","TAB","<img src='icon_folder.gif' width='16' height='13' border=0 align='top' hspace=1>Topics",$linkArray);
	

	$result = $GLOBALS['db']->execQuery("select discussion_name from discussions where discussion_id = ".$_GET['did']);
	while ($row = mysql_fetch_assoc($result)) { 
	   echo("<br>
	   <div align='center'><a href='index.php?a=discuss&gid=".$_GET['gid']."'><img src='icon_group.gif' width='14' height='17' align='top' hspace=1 border=0>Discussion list</a> &middot; <img src='icon_folder.gif' width='16' height='13' border=0 align='top' hspace=1>".$row['discussion_name']."</div>");
	}

	
	if(isset($_GET['revise'])){

		$dtopic = "";
		$result = $GLOBALS['db']->execQuery("select topic from topics where topic_id = ".$_GET['tid']);
		while ($row = mysql_fetch_assoc($result)) { 
		$dtopic = str_replace('<br>',CHR(13).CHR(10), trim($row['topic']));
   		}	
	
		echo("<br>");
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
	$GLOBALS['page']->text($dtopic,"topic","inputs","Edit Topic Name:",30,1);
	
	$GLOBALS['page']->submit("Edit Topic","inputs");
	echo("<tr><td>&nbsp;</td><td><a href='index.php?a=discuss&deltopic=1&gid=".$_GET['gid']."&did=".$_GET['did']."&tid=".$_GET['tid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this topic?'); return confirmDownload;\">[Delete this topic by clicking here]</a></td></tr>");
echo("<tr><td>&nbsp;</td><td><a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."'>[Cancel by clicking here]</a></td></tr>");
	$GLOBALS['page']->tableEnd("FORM");
	$GLOBALS['page']->tableEnd("TAB");

	include("v_footer.php");
	exit;
	
	}


	if(isset($_GET['newt'])){
	  	
	$GLOBALS['page']->tableStart("","100%","FORM");
	$GLOBALS['page']->tableStart("","100%","TEXT");
   echo("<a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."'><img src='icon_list.gif' width='18' height='18' alt='' border=0> Return to topic list</a><br>
");
	$GLOBALS['page']->text("","topic","inputs","Enter Topic Name:",30,1);
	$GLOBALS['page']->textarea("","message","inputs",10,100,"Write the first message:",1);
	$GLOBALS['page']->submit("Add Topic","inputs");
	$GLOBALS['page']->tableEnd("FORM");
	$GLOBALS['page']->tableEnd("TAB");

	include("v_footer.php");
	exit;
	}
	
	
		$GLOBALS['page']->tableStart("","100%","TEXT");
   echo("
   <a href='index.php?a=discuss&gid=".$_GET['gid']."&did=".$_GET['did']."&newt=1'><img src='icon_save.gif' width='18' height='18' alt='' border=0> Create a new topic</a><br><br>
<strong>To view messages choose a <img src='icon_folder.gif' width='16' height='13' alt=''> topic name from the list below</strong>
");
	$GLOBALS['page']->tableEnd("TEXT");
	
	
	if(count($topics)>0){
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($topics,"odd","even","35%",$GLOBALS['db']->numberOfRows);
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("<strong>There are no topics for this discussion</strong>");
	$GLOBALS['page']->tableEnd("TEXT");
	}

	$GLOBALS['page']->tableEnd("TAB");
	}
	
	echo("<br>");
	
	}else{
	
	//viewing the dicussion list (only teachers can add new discussions

	//start discuss list
	$result = $GLOBALS['db']->execQuery("select discussion_id,discussion_name,date_format(last_message,'%m.%d.%y') as  last_message,total_messages, categories.category_id, category_name,categories.order_number from discussions left outer join categories on  categories.category_id = discussions.category_id where discussions.group_id = ".$_GET['gid']." order by order_number asc, last_message",true);
	$i = 0;
	$diss = array();
		$catArray = array();
	while ($row = mysql_fetch_assoc($result)) {
	
	   if($row['category_name'] <> "" and !in_array($row['category_name'],$catArray)){
	   $diss[$i]['&nbsp;'] = '<strong>&nbsp;&nbsp;'.$row['category_name'].'</strong>';
	    $diss[$i]['last post'] = ""; 
	   $diss[$i]['total posts'] = "";
	    $diss[$i][''] ="";
	   array_push($catArray,$row['category_name']);
	   $i++;
	   }
	 
       $did[$i]['discussion id'] = $row["discussion_id"]; 
	   $diss[$i][''] = "<img src='icon_group.gif' width='15' height='17' alt='' align='top'> ".(($_GET['a']=='view')?" <a href='index.php?a=discuss&did=".$row['discussion_id']."&gid=".$_GET['gid']."'>".substr($row["discussion_name"],0,23)."...</a>":"<a href='index.php?a=discuss&did=".$row['discussion_id']."&gid=".$_GET['gid']."'>".$row["discussion_name"])."</a>";
       $diss[$i]['last post'] = $row["last_message"]; 
	   $diss[$i]['total posts'] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$row["total_messages"];
	   $i++;
   }
	
	$linkArray[0] = "index.php?a=editdiss&gid=".$_GET['gid'];
   	$GLOBALS['page']->tableStart("","100%","TAB","<a class='tabanchor'  href='index.php?a=discuss&gid=".$_GET['gid']."'>Discussions</a>",$linkArray);
	
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo('');
	$GLOBALS['page']->tableEnd("TEXT");
	if(count($diss)>0){
	;
	
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($diss,"odd","even","60%",$GLOBALS['db']->numberOfRows,"discuss");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no discussions available");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	$GLOBALS['page']->tableEnd("TAB");
	
}
	
?>