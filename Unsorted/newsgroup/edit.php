<?
//------------------------------------------------------------------//
// edit.php
// Author: Carlos Sánchez
// Created: 14/01/02
// Last Modified: 14/01/02
//
// Description: TODO
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");

// Templates
$t = new Template($myng_dir['themes']."/".$myng['theme']."/templates/");

// Set up the language
modules_get_language();

$db=new My_db;
$db->connect();

//Registramos el momento actual
$current_time=time();

// Check if the login system is activated or not
if($login_switch){


if(session_is_registered("Session")){

        //--------- We are in a session! -------------------------------//

        // Create an user instance
        $user = new Usuario($Session['user']['id_user']);
        // Check the online status and update the timestamps
        $user->is_online();
        // Clean the people_online table
        clean_people_online($db,$current_time);
        // Show the new interface (online)
        $left_bar = manage_login($current_time,$t,$login_switch,$db);

        //$left_bar = "my_bar.htm";
        $t->set_var("name",$user->id_user);
        $t->set_var("email",$user->email);



}else{
        //-------- User not logged ------------------------------------//
        // Manage the login module
        $left_bar = manage_login($current_time,$t,$login_switch,$db);
        //$left_bar = "login.htm";
        $challenge=md5(uniqid($myng['cadena']));
        $t->set_var("secret_challenge",$challenge);
        $system_info = "You must log in to use this feature.";
        $main = "error.htm";
        $t->set_file("main",$main);
        $t->set_var("error_message","You must log in to use this feature.");
        // Show all the page
        show_layout($t,$left_bar,$system_info,$myng['version']);
        exit();

}

}else{

   // There's no login system
   $left_bar = "poweredby.htm";
   $challenge=md5(uniqid($myng['cadena']));
   $t->set_var("secret_challenge",$challenge);
   $system_info = "You must log in to use this feature.";
   $main = "error.htm";
   $t->set_file("main",$main);
   $t->set_var("error_message","You must log in to use this feature.");
   // Show all the page
   show_layout($t,$left_bar,$system_info,$myng['version']);
   exit();

}


$system_info = "Profile";

$main = "profile.htm";
$t->set_file("main",$main);



// Show all the page
show_layout($t,$left_bar,$system_info,$myng['version']);
?>


