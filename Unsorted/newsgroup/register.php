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
// register.php
//
// Created: 4-11-2000
//
// Author:Carlos Sánchez
//
// Description: All the functions needed to register an user into the system.
//
//
// Notes:
//
// 1- LOOK OUT!! It's impossible for me now to change the value of a input type=password"
// with javascript, so here's the trick for not sending the password: Just put the input
// outside the form, including it into another form for compatibility with Netscape 4.x.
// Now it works!!
//
//------------------------------------------------------------------//

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

// Check if the user pushed 'register!'
if($_POST['registro_usuario'] == "ok"){

        // Instantiate an 'user' object, with the variables passed
        // through the form
        $new_user = new User($_POST['id_user'],$_POST['hashpasswd'],$_POST['email'],$_POST['visible'],$_POST['country']);

        // Call the required methods       
        $response = $new_user->register_user($_POST['hashpasswd2']);

        if($response['ok'] == 1){

                // The registration was succesfully completed
                $left_bar = manage_login(time(),$t,$db);
                $system_info = $response['message'];
                $main = "register_ok.htm";
                $t->set_file("main",$main);

                $t->set_var("id_user",$_POST['id_user']);
                $t->set_var("email",$_POST['email']);
                $t->set_var("country",$_POST['country']);

                // Registration OK text
                $t->set_var("_myngregistrationcomplete",_MYNGREGISTRATION_COMPLETE);
                $t->set_var("_myngthanks",_MYNGTHANKS);
                $t->set_var("_myngdata_stored",_MYNGDATA_STORED);
                $t->set_var("_myngedit_info",_MYNGEDIT_INFO);
                $t->set_var("_myngsubmitted_info",_MYNGSUBMITTED_INFO);
                $t->set_var("_mynguser_name",_MYNGUSER_NAME);
                $t->set_var("_myngemail",_MYNGEMAIL);
                $t->set_var("_myngcountry",_MYNGCOUNTRY);
                $t->set_var("_myngregister_welcome",_MYNGREGISTER_WELCOME);
                $t->set_var("_myngstaff",_MYNGSTAFF);
                $t->set_var("_mynglogin_transfer",_MYNGLOGIN_TRANSFER);
                $t->set_var("_myngsecure_login",_MYNGSECURE_LOGIN);
                $t->set_var("_myngcookies",_MYNGCOOKIES);

                // Show all the page
                show_layout($t,$left_bar,$system_info,MYNG_VERSION);

        }else{

                // Something has happended
                $left_bar =  $left_bar = manage_login(time(),$t,$db);
                $system_info = $response['message'];
                $main = "register.htm";
                $t->set_file("main",$main);

                $t->set_var("error","Error: ".$response['message']."<br>");
                $t->set_var("id_user",$id_user);
                $t->set_var("email",$email);
                //$t->set_var("country",$country);

                // Show the text of the template in the required language
                parse_text($t);

                // Show all the page
                show_layout($t,$left_bar,$system_info,MYNG_VERSION);
        }



}else{


        // Check if the login system is activated or not
        if($_SESSION['conf_system_login_yn']=="Y"){

                // We only display the required form
                $left_bar = manage_login(time(),$t,$db);

                // Only if left_bar = 'login' !!
                $challenge=md5(uniqid($_SESSION['conf_sec_secret_string']));
                $t->set_var("secret_challenge",$challenge);

                $system_info = _MYNGFILL_FORM;
                $main = "register.htm";
                $t->set_file("main",$main);

                // Show the text of the template in the required language
                parse_text($t);

                $finish = finish_time($start);
				$t->set_var("page_time",$finish);
                
                // Show all the page
                show_layout($t,$left_bar,$system_info,MYNG_VERSION);

        }else{

                // There's no login system
                $left_bar = manage_login($current_time,$t,$db);
                //$left_bar = "poweredby.htm";
                $challenge=md5(uniqid($myng['cadena']));
                $t->set_var("secret_challenge",$challenge);
                $system_info = _MYNGMUST_LOGIN;
                $main = "error.htm";
                $t->set_file("main",$main);
                $t->set_var("error_message",_MYNGMUST_LOGIN);
                
                $finish = finish_time($start);
				$t->set_var("page_time",$finish);
                
                // Show all the page
                show_layout($t,$left_bar,$system_info,MYNG_VERSION);
                exit();


        }

}


function parse_text(&$t){

        // Registration text
        $t->set_var("_myngfill_form",_MYNGFILL_FORM);
        $t->set_var("_myngall_fields",_MYNGALL_FIELDS);
        $t->set_var("_myngrequired",_MYNGREQUIRED);
        $t->set_var("_mynguser_name",_MYNGUSER_NAME);
        $t->set_var("_myngemail",_MYNGEMAIL);
        $t->set_var("_myngpassword",_MYNGPASSWORD);
        $t->set_var("_myngvisible",_MYNGVISIBLE);
        $t->set_var("_myngyes",_MYNGYES);
        $t->set_var("_myngno",_MYNGNO);
        $t->set_var("_myngpassword_again",_MYNGPASSWORD_AGAIN);
        $t->set_var("_mynginsert_valid_email",_MYNGINSERT_VALID_EMAIL);
        $t->set_var("_myngspam_protected",_MYNGSPAM_PROTECTED);
        $t->set_var("_myngpassword_transfer",_MYNGPASSWORD_TRANSFER);
        $t->set_var("_myngsecure_login",_MYNGSECURE_LOGIN);
        $t->set_var("_myngcountry",_MYNGCOUNTRY);


}

?>
