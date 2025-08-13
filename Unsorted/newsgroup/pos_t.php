<?
// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //

//------------------------------------------------------------------//
// post.php
// Author: Carlos Sánchez
// Created: 29/08/01
//
// Description: With this form, we can POST new messages to a group
//              or reply to an existing article.
//
// Notes:	This script is quite a mess. We'll separate all 
//			the different funtions in separate scripts or libraries.
// 			
//------------------------------------------------------------------//

?>
<?
session_start();

include("config.php");

$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Set up the language
modules_get_language();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");	
}

//Registramos el momento actual
$current_time=time();

$news_groups = array();

// ------ Check if the login system is activated or not ------- //
if($_SESSION['conf_system_login_yn'] == "Y"){


	if(isset($_SESSION['usr_name'])){
	
        	//--------- We are in a session! -------------------//
    
        	// Create an user instance
        	$user = new User($_SESSION['usr_name']);
        	// Check the online status and update the timestamps
        	$user->is_online();
        	// Clean the people_online table
        	clean_people_online($db,$current_time);
        	// Show the new interface (online)
        	$left_bar = manage_login($current_time,$t,$db);
        	//$left_bar = "my_bar.htm";
        	$t->set_var("name",$user->id_user);
        	$t->set_var("email",$user->email);



	}else{
        	//-------- User not logged ------------------------------//
        	// Redirect to the error page
			header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=3");		                

	}

}else{

   // There's no login system
   $left_bar = "poweredby.htm";
   // With no login system, the users can post, and
   // they can lie in their name and email!!.

}

// Check the group write protection
//echo $_['Group']['allow_post'];
if($_SESSION['grp_allow_post_yn'] == "N"){	
	
    // Redirect to the error page
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=4");		                

}

$t->set_file("post","post.htm");
$t->set_file("post_ok","post_ok.htm");

//$t->set_file("main",$main);




// -- POST Variables -- //

$body = $_POST['body'];
$email = $_POST['email'];
$name = $_POST['name'];
$subject = $_POST['subject'];


if (isset($_POST['references'])) {
	
	$references = $_POST['references'];
	//echo $references;
}

if (isset($_POST['type'])) {
  $type = $_POST['type'];	
}

if (isset($_POST['group'])) {  
  $group = $_POST['group'];	
}

if (isset($_POST['grp_id'])) {
  $grp_id = $_POST['grp_id'];	
}

if (isset($_POST['newsgroups'])) {
  $newsgroups = $_POST['newsgroups'];	
}

// ---- GET variables ------ //

if (!isset($_GET['references']) && !isset($_POST['references'])) {	
	$references = false;
}

if(isset($_GET['references'])){	
	$references = $_GET['references'];
}

if (isset($_GET['type'])) {
  $type = $_GET['type'];	  	
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];	  	
}


if (isset($_GET['group'])) {
  $group = $_GET['group'];	
}

if (isset($_GET['grp_id'])) {
  $grp_id = $_GET['grp_id'];	
}

if (isset($_GET['newsgroups'])) {	
  $newsgroups = $_GET['newsgroups'];	
  $group = $_GET['newsgroups'];	
}

//echo $type;

//---------- Type: NEW ------------------------------//
if ($type=="new") {
  $subject="";
  $bodyzeile="";
  $show=1;
}
//---------------------------------------------------//

	
//---------- Type: POST ------------------------------//

if ($type=="post") {

	
		// Don't show the form
        $show=0;
        
        if (trim($body)=="") {
                $type="retry";
                $system_info = _MYNGMISSING_MESSAGE;
                $error_message = _MYNGMISSING_MESSAGE;
        }
        if (trim($email)=="") {
                $type="retry";
                $system_info = _MYNGMISSING_EMAIL;
                $error_message = _MYNGMISSING_EMAIL;
        }
        
        if (!validate_email(trim($email))) {
                $type="retry";
                $system_info = _MYNGWRONG_EMAIL;
                $error_message = _MYNGWRONG_EMAIL;
        }
        
        if (trim($name)=="") {
                $type="retry";
                $system_info = _MYNGMISSING_NAME;
                $error_message = _MYNGMISSING_NAME;
        }
        if (trim($subject)=="") {
                $type="retry";
                $system_info = _MYNG_MISSING_SUBJECT;
                $error_message = _MYNG_MISSING_SUBJECT;
        }
        $t->set_var("error_message",$error_message);

        if ($type=="post") {
        		
                // If the readonly switch is true, you cannot post to any group.
                // The same happens if you try to post a message to a readonly group.

                // $readonly deprecated in v0.5 !!
                //if (!$readonly) {

                        $references = rawurldecode($references);
                        // With this function we post the article
                        flush();
						
                        $consulta = sprintf("SELECT * from myng_server where serv_host='%s'",$_SESSION['serv_host']);                                           
                        $db->query($consulta);

                        while($db->next_record()){

                                // Fetch the server's data
                                $server = $db->Record['serv_host'];
                                $port = $db->Record['serv_port'];
                                $login = $db->Record['serv_login'];
                                $passwd = $db->Record['serv_passwd'];

                        }

                        $ns=OpenNNTPconnection($server,$port,$login,$passwd);

                        if($ns == false){
                                // Connection Error!
                                $system_info = _MYNGCON_ERROR;
                                $main = "error.htm";
                                $t->set_file("main",$main);
                                closeNNTPconnection($ns);
                                flush();
                                //return false;
                        }else{
                                // Connection OK!                                                                                                
                                $newsgroups = table2real($newsgroups);
                                //echo "Ref:".$references;
                                $message = verschicken(stripslashes($subject),$email." (".$name.")",$newsgroups,$references,$body,$ns);

                                // We try to post the message!
                                if (substr($message,0,3)=="240") {										
                                        // Well Done! It's all OK!
                                        $system_info = _MYNGMESSAGE_POSTED;

                                        // Download the new message
                                        // Deprecated with the new Indexing and Downloading system
                                        // $news_groups = read_groups($ns,$server,$port,$news_groups,$_SESSION['conf_system_login_yn']);
                                        
                                        // We should add a new way od downloading the new message automatically!!

                                        // Dont show the Form
                                        $show = 0;
                                        $group_real = table2real($group);
                                        $t->set_var("group",$group_real);
                                        $t->set_var("name",$name);
                                        $t->set_var("email",$email);
                                        $t->set_var("subject",$subject);
                                        $body = split("\n",$body);
                                        for($j=0; $j<=count($body)-1; $j++) {
                                                if (trim($body[$j])!="") {
                                                        $bodyzeile=$bodyzeile.$body[$j]."\n";
                                                } else {
                                                        $bodyzeile.="\n";
                                                }
                                        }

                                        $t->set_var("body",$bodyzeile);

                                        $url = "tree.php?grp_id=".$grp_id;
                                        $t->set_var("url",modifyLink($url));
                                        $t->set_var("email_protected",$email);

                                        $main = "post_ok.htm";
                                        $t->set_file("main",$main);

                                        // Page Text

                                        $t->set_var("_myngposting_complete",_MYNGPOSTING_COMPLETE);
                                        $t->set_var("_myngthankx",_MYNGTHANKX);
                                        $t->set_var("_myngposted_successfully",_MYNGPOSTED_SUCCESFULLY);
                                        $t->set_var("_mynginfo_submitted",_MYNGINFO_SUBMITTED);
                                        $t->set_var("_myngsubject",_MYNGSUBJECT);
                                        $t->set_var("_myngname",_MYNGNAME);
                                        $t->set_var("_myngemail",_MYNGEMAIL);
                                        $t->set_var("_myngbody",_MYNGBODY);
                                        $t->set_var("_myngclick2return",_MYNGCLICK2RETURN);
                                        $t->set_var("_myngemail_spam_protected",_MYNGEMAIL_SPAM_PROTECTED);
                                        $t->set_var("_myngemail_changed",_MYNGEMAIL_CHANGED);

                                        // Update our posting statistics.
                                        // Debug this!!
                                        $consulta = sprintf("UPDATE `myng_subscription` SET subs_posted = subs_posted + 1 WHERE subs_usr_id='%s' AND subs_grp_id='%s'",$_SESSION['usr_id'],table2real($group));
                                        $db->query($consulta);


                                } else {                                		
                                        // Oh NO! The message couldn't be posted
                                        $type="retry";

                                        $system_info = _MYNGERROR_SERVER;
                                        $error_message = _MYNGERROR_SERVER;
                                        $t->set_var("error_message",$error_message);
                                }
                                closeNNTPconnection($ns);
                                flush();
                        }

				// $readonly deprecated in v0.5                        
				/*
                } else {

                        $main = "error.htm";
                        $t->set_file("main",$main);
                        $system_info = _MYNGREAD_ONLY;
                        $error_message = _MYNGREAD_ONLY;
                        $t->set_var("error_message",$error_message);                        
                }
                */
                
        }// End of 'if($type=="post")'

}
//---------------------------------------------------//

//---------- Type: REPLY ------------------------------//
if ($type=="reply") {

	// Reply to an existing POST. We have to query to the database and get some Info.
 	// We don't need to query the server !!.
 	
 	$head=readHeader_from_DB($group,$id);
  	$body=readBody_from_DB($group,$id);

  	// This is just for showing the name of the sender and a message
  	// like 'Pepe Wrote:'
  	if ($head->name != "") {
       	$bodyzeile=$head->name;
  	} else {
        $bodyzeile=$head->from;
  	}

  	// Article compression functions
  	if($_SESSION['conf_system_zlib_yn'] == "Y"){
  		//echo $body;
        $body = gzuncompress($body);
        $body = stripslashes($body);
  	}	

  	$bodyzeile=$bodyzeile._MYNGWROTE;
  	// Put off the special HTML tags.
  	$body = strip_tags($body);
  	$body = stripslashes($body);
  	$body = split("\n",$body);

  	for ($i=0; $i<=count($body)-1; $i++) {
        if (trim($body[$i])!="") {
    	    $bodyzeile=$bodyzeile."> ".$body[$i]."\n";
        } else {
            $bodyzeile.="\n";
        }
	}
  	$subject=$head->subject;
  	if (isset($head->followup) && ($head->followup != "")) {
        $newsgroups=$head->followup;
  	} else {
        $newsgroups=$head->newsgroups;
  	}
  	splitSubject($subject);
  	$subject="Re: ".$subject;
	//  if (substr(strtoupper($subject),0,2)!="RE") $subject="Re: ".$subject;
  	$show=1;
  	$references=false;
  	if (isset($head->references[0])) {
        for ($i=0; $i<=count($head->references)-1; $i++) {
    	    $references .= $head->references[$i]." ";
        }
	}

  	$references .= $head->id;
}


//---------------------------------------------------//


//---------- Type: RETRY ------------------------------//
if ($type=="retry") {
  $show=1;
  $body = stripslashes($body);
  $bodyzeile=$body;
  //echo $bodyzeile;
}
//---------------------------------------------------//

//-------------- SHOW -------------------------------//

// Show the Form

if ($show==1) {

		//echo $_SESSION['serv_host'];
		//echo $_SESSION['serv_host'];
        if ($_SESSION['conf_sec_test_group_yn'] == "Y") {
                $newsgroups = table2real($newsgroups);
                $testnewsgroups=testgroups($grp_id);
        } else {
                $testnewsgroups=$newsgroups;
        }

        if ($testnewsgroups == "") {
                //If testgroup = true you can't post to groups not registered in MyNewsGroups :)
                $main = "error.htm";
                $t->set_file("main",$main);
                $system_info = _MYNGFOLLOWUP_NOT_ALLOWED.$newsgroups;
                $error_message = _MYNGFOLLOWUP_NOT_ALLOWED;
                $t->set_var("error_message",$error_message.$newsgroups);

        } else {
        	
                //$newsgroups=$testnewsgroups;

                //if (isset($error)) echo "<p>$error</p>"; ??

                // Template Form part.
                $t->set_var("name",$_SESSION['usr_name']);

                $t->set_var("subject",htmlentities(stripslashes($subject)));
                if (isset($name))
                        $t->set_var("name",htmlentities(stripslashes($name)));
                if (isset($_SESSION['usr_email'])){
                	if($_SESSION['conf_sec_protect_email_yn'] == 'Y'){
                        $email = protect_email($_SESSION['usr_email']);
                	}else{
                		$email = $_SESSION['usr_email'];
                	}
                        $t->set_var("email",$email);
                        // We make the user's data read only, the user can't lie!
                        $t->set_var("is_readonly","readonly");
                }
                
                if (isset($bodyzeile))
                    $t->set_var("body",$bodyzeile);
                    
                $t->set_var("newsgroups",$newsgroups);
                $t->set_var("references",htmlentities(rawurlencode($references)));
                $t->set_var("group",$group);
                $t->set_var("grp_id",$grp_id);

                // Page Text
                $t->set_var("_myngfill_form2post",_MYNGFILL_FORM2POST);
                $t->set_var("_myngemail_mustbe_real",_MYNGEMAIL_MUSTBE_REAL);
                $t->set_var("_myngsubject",_MYNGSUBJECT);
                $t->set_var("_myngname",_MYNGNAME);
                $t->set_var("_myngemail",_MYNGEMAIL);
                $t->set_var("_myngpost",_MYNGPOST);

                $system_info = _MYNGPOST_ARTICLE;
                $main = "post.htm";
                $t->set_file("main",$main);


        	}
}


//-----------------------------------------------------------------------------------//



$challenge=md5(uniqid($_SESSION['conf_sec_secret_string']));
$t->set_var("secret_challenge",$challenge);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);


?>