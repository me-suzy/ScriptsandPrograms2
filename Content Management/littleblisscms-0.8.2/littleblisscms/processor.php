<?php
/*
	<one line to give the program's name and a brief idea of what it does.>
	Copyright (C) 2005  Phillip Berry (Bliss Webhosting)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/* 
	File handles the processing of input data,
	User authentication, new user creation, content management
*/
session_start() ;
require_once("./cms_class.inc") ;
require_once("./authentication_class.inc") ;

$authentication = new authentication("./xml/users.xml") ;

//If user is logging in
if($_REQUEST['action'] == "login"){

	if($_POST['username'] == "" && $_POST['username'] == ""){
		$content = new cms_class("./xml/content.xml") ;
		$content->login_box() ;
	}
	//Define user and password
	$authentication->username = $_POST['username'] ;
	$authentication->password = $_POST['password'] ;
	
	//Authenticate user
	if(!$authentication->authenticate()){		
		echo "Authentication Failed ".$authentication->error ;
	}	
	else{
		//If succesful, cliose the login popup window and reload the parent window
		echo "<script language=\"Javascript\">
				window.opener.location.reload();
				window.close();
		   	   </script>\n\n" ;
	}
}
elseif($_REQUEST['action'] == "logout"){
	session_destroy() ;
	echo "<script language=\"Javascript\">
			history.back() ;
	   	   </script>\n\n" ;
}
/*********************CONTENT PROCESSING*********************/

//Generate or edit content
elseif(isset($_POST['content'])){
	$content = new cms_class("./xml/content.xml") ;
	$content->section_id = $_POST['id'] ;	
	//Check authentication
	if($authentication->check_authentication()){		
		if($content->check_edit_permission($_SESSION['username'],$_POST['id'])){
			$content->update_content($_POST['id'],$_POST['content']) ;			
		}
		header("Location: http://".$_POST['referrer']."?section=".$_POST['id']."&section_type=".$_POST['section_type']."&content_error=".$content->error) ;
		exit() ;
	}
	$content->login_box() ;
}

elseif($_POST['administrate'] == "delete_section"){
	
	$authentication->check_is_admin() ;	
	if($_POST['section'] == ""){
		header("Location: admin.php?admin_error=No section defined") ;
		exit() ;
	}
	$administration = new cms_class("./xml/content.xml") ;
	$administration->delete_section($_POST['section']) ;
	header("Location: admin.php") ;
	exit() ;
}

/******************USER PROCESSING***************/
//Create a new user
elseif($_POST['action'] == "new_user"){
	$authentication->check_is_admin() ;	
	$administration = new cms_class("./xml/users.xml") ;
	if($administration->new_user($_POST['username'],$_POST['password'])){
		header("Location: admin.php?&new_user=success") ;
		exit() ;
	}
	else{
		header("Location: admin.php?&error=".$this->error) ;
		exit() ;
	}
}

elseif(isset($_GET['delete_user'])){
	$authentication->check_is_admin() ;	
	if($_GET['delete_user'] == ""){
		header("Location: admin.php?admin_error=No user defined") ;
		exit() ;
	}
	$content = new cms_class("./xml/content.xml") ;
	if($content->remove_user_all_sections($_GET['delete_user'])){
		$content->write_xml_file() ;
		$administration = new cms_class("./xml/users.xml") ;
		if($administration->delete_user($_GET['delete_user'])){
			$administration->write_xml_file() ;
		}
	}
	$error = $administration->error ;
	header("Location: admin.php?error=".urlencode($error)."") ;
	exit() ;
}

elseif(isset($_POST['administrate']) && $_POST['administrate'] != "delete_section"){
	$authentication->check_is_admin() ;	
	if($_POST['username'] == "" || $_POST['section'] == ""){
		header("Location: admin.php?admin_error=No user or section defined") ;
		exit() ;
	}
	$administration = new cms_class("./xml/content.xml") ;

	if($_POST['administrate'] != ""){
		if($administration->alter_section_permission($_POST['username'],$_POST['section'],$_POST['administrate'])){
			if(!$administration->write_xml_file()){
				$error = $administration->error ;
			}
		}
	}
	header("Location: admin.php?error=$error") ;
	exit() ;
}
/*Change password*/
elseif(isset($_POST['new_password'])){
	$authentication->check_is_admin() ;	
	if($_POST['new_password'] == ""){
		header("Location: admin.php?admin_error=No user defined") ;
		exit() ;
	}
	if($_POST['user_name'] == ""){
		header("Location: admin.php?admin_error=No user defined") ;
		exit() ;
	}
	$administration = new cms_class("./xml/users.xml") ;
	if($administration->change_password($_POST['user_name'],$_POST['new_password'])){
		if(!$administration->write_xml_file()){
			$error = $administration->error ;
		}
	}
	header("Location: admin.php?error=$error") ;
	exit() ;
}
else{
	header("Location: /") ;
}
?>