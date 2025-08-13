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
// print.php
// Author: Carlos Sánchez
// Created: 05/02/02
//
// Description: Format the article in a printable way.
//
//
//------------------------------------------------------------------//

?>
<?
session_start();

include("config.php");

$db = new My_db;
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

$t->set_file("print","print.htm");

//--- GET variables ---//

$id_article = $_GET['id_article'];

$consulta = sprintf("SELECT subject,name,user_agent,body,from_header,date FROM `myng_%s` WHERE id_article='%s'",real2table($_SESSION['grp_name']),$id_article);
$db->query($consulta);
$db->next_record();


$t->set_var("version",MYNG_VERSION);
$t->set_var("group_name",table2real($Group['name']));
$t->set_var("subject",$db->Record['subject']);
$t->set_var("from",$db->Record['from_header']);
$t->set_var("name",$db->Record['name']);
$t->set_var("user_agent",$db->Record['user_agent']);
$date = date(_MYNGDATEDISPLAY,$db->Record['date']);
$t->set_var("date",$date);
// Article compression functions
if($_SESSION['conf_system_zlib_yn'] == "Y"){
        $body = gzuncompress($db->Record['body']);
        $body = stripslashes($body);
        $t->set_var("body",$body);
}else{
        $t->set_var("body",$db->Record['body']);
}

$actual_date = date("M d Y, H:i:s",time());
$t->set_var("actual_date",$actual_date);

// CSS styles directory and file
$t->set_var("style_dir",$_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme']."/styles");
$t->set_var("file_style","style.css");
$t->set_var("images_dir",$_SESSION['conf_system_prefix']."images/");

// Page Text
$t->set_var("_myngprint_article",_MYNGPRINT_ARTICLE);
$t->set_var("_myngsubject",_MYNGSUBJECT);
$t->set_var("_myngname",_MYNGAUTHOR);
$t->set_var("_myngfrom",_MYNGFROM);
$t->set_var("_myngdate",_MYNGDATE);
$t->set_var("_mynguser_agent",_MYNGUSER_AGENT);
$t->set_var("_mynggenerated_by",_MYNGGENERATED_BY);



$t->parse("out","print");
// Show the web
$t->p("out");
?>


