<?
//------------------------------------------------------------------//
// thread.php
// Author: Carlos Sánchez
// Created: 23/06/01
// Last Modified: 09/09/01
//
// Description: We show all the articles in a thread.
//
//
// <Deprecated> Maybe will be added in next releases!!.
//
//
//------------------------------------------------------------------//
?>
<?
session_start();
// Database abstraction Lib.
include "db_mysql.inc.php";
// Template Lib.
include "template.inc.php";
// Search Engine
include ("lib/KwIndex.lib.php");
// Core.
include "core.php";
// System Configuration
include "config.php";
// User Class
include("class/class_usuario.php");

$t = new Template("templates/");

$db=new My_db;
$db->connect();

// Check the current time
$current_time=time();

// Manage the login module
$left_bar = manage_login($current_time,$t,$login_switch,$db);

$system_info = "Welcome to MyNewsGroups :) v ".$myng['version'];
$main = "thread.htm";
$t->set_file("main",$main);

$t->set_block("main","tree_block","tree_block_handle");
$t->set_block("main","body_block","body_block_handle");

// Show the thread of an article
// We pass the '$t' to mix templates and recursive functions
$depth = 0;
$i=0;

expand_thread($parent,$group_name,$t,$depth,$myng['compression'],$server);

$t->set_var("group_url",rawurlencode($group_name));
$t->set_var("begin_number",$begin);
$group_name_real = strtr($group_name,"_",".");

$t->set_var("group_name",$group_name_real);
$t->set_var("server",$server);


$post_url = "post.php?newsgroups=".rawurlencode($group_name)."&type=new"."&server=".rawurlencode($server);
$t->set_var("post_url",$post_url);

$challenge=md5(uniqid($myng['cadena']));
$t->set_var("secret_challenge",$challenge);

// Show all the page
show_layout($t,$left_bar,$system_info,$myng['version']);




?>