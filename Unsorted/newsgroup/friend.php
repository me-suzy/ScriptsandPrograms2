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
// friend.php
// Author: Carlos Sánchez
// Created: 06/02/02
//
// Description: Email the article to a friend.
//
//
//------------------------------------------------------------------//

?>
<?

session_start();

include("config.php");

// Set up the language
modules_get_language();

$db=new My_db;
$db2 = new My_db;
$db->connect();
$db2->connect();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");


//Registramos el momento actual
$current_time=time();

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");	
}

// Check if the login system is activated or not
if($_SESSION['conf_system_login_yn'] == "Y"){

	if(isset($_SESSION['usr_name'])){

        	//--------- We are in a session! -------------------------------//

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
	        //-------- User not logged ------------------------------------//	        
	        // Redirect to the error page
			header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=3");		                

	}


}else{

   // Redirect to the error page
   header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=3");		                

}

//--- GET Variables ---//
$id_article = $_GET['id_article'];

//--- POST Variables --//
$email = $_POST['email'];
$friend_email = $_POST['friend_name'];
$subject = $_POST['subject'];
$body_to_send = $_POST['body_to_send'];
$action = $_POST['action'];
//$id_article = $_POST['id_article'];


if($action == "send_email"){

		$id_article = $_POST['id_article'];
			
        // Name
        // Add regular expressions check for malicious code!
        $friend_name = strip_tags($friend_name);

        // Put the friend's name in the text
        $body_to_send = ereg_replace(_MYNGFRIEND_NAME,$friend_name,$body_to_send);
        $body_to_send = StripSlashes($body_to_send);

        // Check the entries of the form

        // Email
        $friend = new User("","",$email,"","");
        $answer = $friend->validateEmail($email);
        if(!$answer[0]){
                // Incorrect email address
                $email_valid = 0;
                $t->set_var("error_message",$answer[1]);
        }else{
                $email_valid = 1;
        }



        if($email_valid){

                // Send the email
                $from_header = "From: ".$_SESSION['usr_name']."<".$_SESSION['usr_email'].">";

                if(mail($email,$subject,$body_to_send,$from_header)){

                        // OK!
                        $system_info = _MYNGEMAIL_SENT;
                        $error_message = _MYNGEMAIL_SENT;
                        $t->set_var("error_message",$error_message);
                        $main = "friend.htm";
                        $t->set_file("main",$main);

                }else{

                        // OK!
                        $system_info = _MYNGEMAIL_NOT_SENT;
                        $error_message = _MYNGEMAIL_NOT_SENT;
                        $t->set_var("error_message",$error_message);
                        $main = "friend.htm";
                        $t->set_file("main",$main);

                }

                //$body = $body_to_send;

        }

}


        $system_info = _MYNGEMAIL_FRIEND;

        $main = "friend.htm";
        $t->set_file("main",$main);

        $consulta = sprintf("SELECT 
        	subject,
         	name,
        	user_agent,
        	body,
        	from_header
         FROM `myng_%s` 
         WHERE id_article='%s'",real2table($_SESSION['grp_name']),$id_article);               
        
        $db->query($consulta);
        $db->next_record();

        $t->set_var("group_name",$_SESSION['grp_name']);
        $subject = $_SESSION['usr_email']._MYNGSENDYOU_FROM.table2real($_SESSION['grp_name']);
        $t->set_var("subject",$subject);
        $t->set_var("from",$db->Record['from_header']);
        $t->set_var("id_article",$id_article);

        // Article compression functions
        if($_SESSION['conf_system_zlib_yn'] == "Y"){
                $body = gzuncompress($db->Record['body']);
                $body = stripslashes($body);
                // Take the HTML tags out
                $body = strip_tags($body);
        }else{
                $body = $db->Record['body'];
        }

        // Complete the body's text
        $body = _MYNGDEAR_FRIEND.":\n\n".
                _MYNGYOUR_FRIEND.$_SESSION['usr_name']." (".$_SESSION['usr_email'].")".
                _MYNGWANT2SHARE.table2real($_SESSION['grp_name']).":\n\n".
                _MYNGSUBJECT.": ".$db->Record['subject']."\n".
                _MYNGFROM.": ".$db->Record['from_header']."\n".
                _MYNGAUTHOR.": ".$db->Record['name']."\n\n".
                "---------------------------------\n".$body."\n---------------------------------".
                "\n\n"._MYNGGENERATED_BY."MyNewsGroups :). \nhttp://mynewsgroups.sf.net \n'Share your knowledge'";

        $t->set_var("body",$body);

        // Page Text

        $t->set_var("_mynginsert_friend_info",_MYNGNSERT_FRIEND_INFO);
        $t->set_var("_myngmail_mustbe_real",_MYNGMAIL_MUSTBE_REAL);
        $t->set_var("_myngemail",_MYNGEMAIL);
        $t->set_var("_myngname",_MYNGNAME);
        $t->set_var("_myngemail_look",_MYNGEMAIL_LOOK);
        $t->set_var("_myngsubject",_MYNGSUBJECT);
        $t->set_var("_myngemail_it",_MYNGEMAIL_IT);

        $finish = finish_time($start);
		$t->set_var("page_time",$finish);
        
        // Show all the page
        show_layout($t,$left_bar,$system_info,MYNG_VERSION);


?>


