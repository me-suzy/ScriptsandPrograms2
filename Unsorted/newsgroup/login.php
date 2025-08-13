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
// login.php
// Created: 4-11-2000
//
// Author: Carlos Sánchez

// Description: Log an user into the system.
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


if($_POST['login']=="ok"){

        // The user wants to login
        // Create an User object
        $logged_user = new User($_POST['id_user']);
        // Log in the user
        //echo $_POST['challenge'].$_POST['response'];
        $answer = $logged_user->login_user($_POST['challenge'],$_POST['response']);

        if($answer['ok']=="1"){

                // The user got logged correctly

                //$left_bar = "my_bar.htm";
                $left_bar = manage_login(time(),$t,$db);
                $system_info = $answer['message'];
                $main = "my.htm";
                $t->set_file("main",$main);
                $t->set_var("id_user",$_SESSION['usr_name']);
                if($_SESSION['conf_sec_protect_email_yn'] == 'Y'){
                    $t->set_var("email",protect_email($_SESSION['usr_email']));
                }else{
                	$t->set_var("email",$_SESSION['usr_email']);
                }
                
                $t->set_var("country",$_SESSION['usr_country']);

                // Page Text

                $t->set_var("_myngwelcome_user",_MYNGWELCOME_USER);
                $t->set_var("_myngpost",_MYNGPOST);
                $t->set_var("_myngreply",_MYNGREPLY);
                $t->set_var("_myngsave",_MYNGSAVE);
                $t->set_var("_myngstats",_MYNGSTATS);
                $t->set_var("_myngsubscribe",_MYNGSUBSCRIBE);
                $t->set_var("_myngoptions",_MYNGOPTIONS);
                $t->set_var("_myngprofile",_MYNGPROFILE);
                $t->set_var("_myngnew_articles",_MYNGNEW_ARTICLES);
                $t->set_var("_myngto_articles",_MYNGTO_ARTICLES);
                $t->set_var("_myngedit_your",_MYNGEDIT_YOUR);
                $t->set_var("_myngto_groups",_MYNGTO_GROUPS);
                $t->set_var("_myngyour_articles",_MYNGYOUR_ARTICLES);
                $t->set_var("_mynguser_name",_MYNGUSER_NAME);
                $t->set_var("_myngemail",_MYNGEMAIL);
                $t->set_var("_myngcountry",_MYNGCOUNTRY);
                $t->set_var("_myngview_your",_MYNGVIEW_YOUR);
                $t->set_var("_mynghome",_MYNGHOME);

				$finish = finish_time($start);
				$t->set_var("page_time",$finish);

                // Show all the page
                show_layout($t,$left_bar,$system_info,MYNG_VERSION);

        }else{

                // There's been an error                                                
                $left_bar = manage_login(time(),$t,$login_switch,$db);
                $system_info = $answer['message'];
                $main = "error.htm";
                $t->set_file("main",$main);
                $t->set_var("error_message",$answer['message']);
                
                $finish = finish_time($start);
				$t->set_var("page_time",$finish);
                
                // Show all the page
                show_layout($t,$left_bar,$system_info,$myng['version']);

        }

}

if($_GET['logout'] == "ok"){

        // Delete the user entry from the people_online_table
        $user = new User($_SESSION['usr_name']);
        $user->go_offline();

        // We kill the current session
        $_SESSION = array();	// New Session destroy method from PHP 4.1
        session_destroy();

        // Init another MyNewsGroups new Session
        init();
        
        $left_bar = manage_login($current_time,$t,$db);
        $system_info = _MYNGLOGGED_OUT;
        $main = "index.htm";
        $t->set_file("main",$main);
        
        $finish = finish_time($start);
		$t->set_var("page_time",$finish);
        
        // Show all the page
        show_layout($t,$left_bar,$system_info,MYNG_VERSION);


}

?>