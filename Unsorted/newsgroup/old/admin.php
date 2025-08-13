<?
//------------------------------------------------------------------//
// admin.php
// Author: Carlos Sánchez
// Created: 23/06/01
// Last Modified: 18/02/01
//
// Description: Admin Page. Here we can add or delete newsgroups.
//
//------------------------------------------------------------------//
?>
<?
session_start();

// Systemcheck standalone or postnuke
if(LOADED_AS_MODULE=="1") {
	// Define Modulename for Postnuke
	$ModName = basename( dirname( __FILE__ ) );
	$myng['system'] = "postnuke";
	// Include the postnuke config file
	include("modules/$ModName/config.php");
} else {
	$myng['system'] = "standalone";
	// Include the standard config file, with all the configuration and files required.
	include("config.php");
}

// Templates
$t = new Template($myng_dir['themes']."/".$myng['theme']."/templates/");

// Set up the language
modules_get_language();

$db=new My_db;
$db->connect();

//Registramos el momento actual
$current_time=time();

if(session_is_registered("Session")){

        //--------- We are in a session! -------------------------------//
        // Create an user instance
        $user = new User($Session['user']['id_user']);
        // Check the online status and update the timestamps
        $user->is_online();
        // Clean the people_online table
        clean_people_online($db,$current_time);

        // Show the new interface (online)
        $left_bar = manage_login($current_time,$t,$login_switch,$db);
        //$left_bar = "my_bar.htm";

        // Check if the user is the System Administrator

        // Get the user's passwd from the DB.
        $user->passwd = $user->getdb_passwd();

        if($user->id_user != $myng['administrator'] || $user->passwd != md5($myng['admin_passwd'])){

                // Show the Error Page. Only the System Admin can view this page.
                $system_info = _MYNGADMIN_LOGIN;
                $main = "error.htm";
                $t->set_file("main",$main);
                $t->set_var("error_message",_MYNGADMIN_LOGIN);
                // Show all the page
                show_layout($t,$left_bar,$system_info,$myng['version']);
                exit();

        }

}else{
        //-------- User not logged ------------------------------------//
        $challenge=md5(uniqid($myng['cadena']));
        $t->set_var("secret_challenge",$challenge);
        //$left_bar = manage_login($current_time,$t,$login_switch,$db);
        $left_bar = "login.htm";
        // Login Text
        $t->set_var("_mynglogin",_MYNGLOGIN);
        $t->set_var("_myngpassword",_MYNGPASSWORD);
        $t->set_var("_myngregister",_MYNGREGISTER);
        $system_info = _MYNGADMIN_LOGIN;
        $main = "error.htm";
        $t->set_file("main",$main);
        $t->set_var("error_message",_MYNGADMIN_LOGIN);
        // Show all the page
        show_layout($t,$left_bar,$system_info,$myng['version']);
        exit();
}



// Which task do we need to perform?

//----------------------------------------------------//
// Txarly's old Add a Group - Add a group to the System (System Admin) 
//----------------------------------------------------//
if(isset($add_group)){

   $result = add_group($group_name,$server,$num_articles,$zip_articles);

   if($result[0] == "false"){
        // Can't connect to News Server
        // Show the Error Page. Only the System Admin can view this page.
        $system_info = $result[1];
        //$system_info = _MYNGCON_ERROR;
        $t->set_var("error_message",$system_info);
        $main = "error.htm";
        $t->set_file("main",$main);
        // Show all the page
        show_layout($t,$left_bar,$system_info,$version);
        exit();

   }
}

//----------------------------------------------------//
// Ewger's Hack -> Add a group to the System (Stable?)
//----------------------------------------------------//
if(isset($groups_to_add)){
  
   for($i=0;$i<count($groups_to_add);$i++){
        $result = add_group($groups_to_add[$i],$server,$num_articles,$myng['compression']);

	if($result[0] == "false"){
        	// Can't connect to News Server
	        // Sow the Error Page. Only the System Admin can view this page.
	        $system_info = $result[1];
	        //$system_info = _MYNGCON_ERROR;
	        $t->set_var("error_message",$system_info);
	        $main = "error.htm";
        	$t->set_file("main",$main);
	        // Show all the page
        	show_layout($t,$left_bar,$system_info,$myng['version']);
      		exit();
	}
   }
}

//----------------------------------------------------//
// Delete a group from the System (System Admin)
//----------------------------------------------------//
if(isset($groups_to_delete)){
   for($i=0;$i<count($groups_to_delete);$i++){
       del_group($groups_to_delete[$i]);
   }
}

//----------------------------------------------------//
// Delete some articles from a group (System Admin)
//----------------------------------------------------//


if(isset($delete_articles)){
        del_articles($num_articles,$group_name);
}


//----------------------------------------------------//
// Edit a group from the System (System Admin)
//----------------------------------------------------//
if(isset($group_to_edit)){

        if($posting == "yes"){

                $consulta = "UPDATE myng_newsgroup SET allow_post = '1' WHERE group_name='".$group_to_edit."'";
                $db->query($consulta);

        }elseif($posting == "no"){

                $consulta = "UPDATE myng_newsgroup SET allow_post = '0' WHERE group_name='".$group_to_edit."'";
                $db->query($consulta);

        }

}



//----------------------------------------------------//
// Add a server to the System (System Admin)
//----------------------------------------------------//

if(isset($add_server)){
        add_server($host,$port,$login,$passwd);
}

//----------------------------------------------------//
// Delete a server from the System (System Admin)
//----------------------------------------------------//
if(isset($server_to_delete)){
   for($i=0;$i<count($server_to_delete);$i++){
       del_server($server_to_delete[$i]);
   }
}



$system_info = _MYNGADMIN;
$main = "admin.htm";
$t->set_file("main",$main);

$t->set_block("main","list_groups_block","list_groups_block_handle");
$t->set_block("main","view_list_add_menu_block","view_list_add_menu_block_handle");
$t->set_block("main","view_input_add_menu_block","view_input_add_menu_block_handle");
$t->set_block("main","delete_block","delete_block_handle");
$t->set_block("main","choose_server_block","choose_server_block_handle");
$t->set_block("main","delete_server_block","delete_server_block_handle");
$t->set_block("main","delete_articles_block","delete_articles_block_handle");
$t->set_block("main","edit_group_block","edit_group_block_handle");


// Build the Main Template
$db=new My_db;
$db->connect();

//------------------------------------------------------------------//
//
// Build add_menu dynamic
//
//------------------------------------------------------------------//

// Build the choose server to add group menu.
$consulta = sprintf("SELECT * FROM myng_server");
$db->query($consulta);

// Looking at Checkbox was set for list view or input view
if (isset($add_menu_view)) { //listview
 
  $t->parse("view_list_add_menu_block_handle","view_list_add_menu_block",true);
  
  if (isset($server)){ // server was selected in form
    $t->set_var("server_name",$server);
    $t->parse("choose_server_block_handle","choose_server_block",true);
    $db->next_record();
    do { 
      if ($server != $db->Record['host']) { // server must be first in select box
        $t->set_var("server_name",$db->Record['host']);
        $t->parse("choose_server_block_handle","choose_server_block",true);
      }  
    } while($db->next_record());  
  } else {  //getting servernames and setting first name to -------- (passive)
       $db->next_record();  
       $t->set_var("server_name","--------");
       $t->parse("choose_server_block_handle","choose_server_block",true);
       do { 
         $t->set_var("server_name",$db->Record['host']);
         $t->parse("choose_server_block_handle","choose_server_block",true);
       } while($db->next_record());  
  }
  
} else { // inputview

  $t->parse("view_input_add_menu_block_handle","view_input_add_menu_block",true);
  
  $db->next_record(); 
  //$t->set_var("server_name","--------");
  //$t->parse("choose_server_block_handle","choose_server_block",true);
  do { 
    $t->set_var("server_name",$db->Record['host']);
    $t->parse("choose_server_block_handle","choose_server_block",true);
  } while($db->next_record());   

}


// Build the list of NewsGroups from NewsServer to add group menu
// -- Eugeny's Hack --//
// Causes server to crash whith slow connections
// I'll comment this till found a proper solution
// 

/*
if (isset($server)) { 
   $list = list_groups($server);
   foreach($list as $value) {
       $t->set_var("group_name",$value);
       $t->parse("list_groups_block_handle","list_groups_block",true);
   }
}
*/

// Build the Delete NewsGroup list
$consulta = sprintf("SELECT * FROM myng_newsgroup");
$db->query($consulta);

while($db->next_record()){

    $t->set_var("group_name",$db->Record['group_name']);
    $t->set_var("num_articles",$db->Record['num_messages']);
    if($db->Record['allow_post'] == "0"){
        $t->set_var("allow_yn","N");
    }else{
        $t->set_var("allow_yn","Y");
    }
    $t->parse("delete_block_handle","delete_block",true);
    $t->parse("delete_articles_block_handle","delete_articles_block",true);
    $t->parse("edit_group_block_handle","edit_group_block",true);

}


// Build the delete server menu.
$consulta = sprintf("SELECT * FROM myng_server");
$db->query($consulta);

while($db->next_record()){

    $t->set_var("server_name",$db->Record['host']);
    $t->parse("delete_server_block_handle","delete_server_block",true);
}

//Page Text

$t->set_var("_myngadmin",_MYNGADMIN);
$t->set_var("_myngenter_group_name",_MYNGENTER_GROUP_NAME);
$t->set_var("_myngadd_group",_MYNGADD_GROUP);
$t->set_var("_myngchoose_server",_MYNGCHOOSE_SERVER);
$t->set_var("_mygarticles2fetch",_MYNGARTICLES2FETCH);
$t->set_var("_myngadd",_MYNGADD);
$t->set_var("_myngdelete_group",_MYNGDELETE_GROUP);
$t->set_var("_myngdelete_articles",_MYNGDELETE_ARTICLES);
$t->set_var("_myngdelete_lastn",_MYNGDELETE_LASTN);
$t->set_var("_myngdelete",_MYNGDELETE);
$t->set_var("_myngedit_group",_MYNGEDIT_GROUP);
$t->set_var("_myngallow_posting",_MYNGALLOW_POSTING);
$t->set_var("_myngyes",_MYNGYES);
$t->set_var("_myngno",_MYNGNO);
$t->set_var("_myngedit",_MYNGEDIT);
$t->set_var("_myngadd_server",_MYNGADD_SERVER);
$t->set_var("_mynghost",_MYNGHOST);
$t->set_var("_myngport",_MYNGPORT);
$t->set_var("_mynglogin",_MYNGLOGIN);
$t->set_var("_myngpassword",_MYNGPASSWORD);
$t->set_var("_myngdelete_server",_MYNGDELETE_SERVER);
$t->set_var("_myngall",_MYNGALL);


$challenge=md5(uniqid($myng['cadena']));
$t->set_var("secret_challenge",$challenge);
// Show all the page
show_layout($t,$left_bar,$system_info,$myng['version']);


?>
