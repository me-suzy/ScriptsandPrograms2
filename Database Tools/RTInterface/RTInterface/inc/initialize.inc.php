<?php

/****************************************************************************
This function initialize all the session vars used later by other functions.
$_SESSION["current_table"] -> Name of the current table
$_SESSION["current_page"] -> Current page showed
$_SESSION["current_rows_per_page"] -> Number of rows per page. Default 25
$_SESSION["order_by"] -> Name of the column used to order by
$_SESSION["detail_id"] -> Value of the pkey for the object selected
$_SESSION["detail_type"] -> Type of the pkey for the object selected
$_SESSION["new_function"] -> Boolean type. Useful to destroy the current session to build up new one.
$_SESSION["submit_*"] -> Useful to identify the current operation(insert,update,delete and search)
$_SESSION["ins_array"] -> This array contains insert data
$_SESSION["up_array"] -> This array contains update data
$_SESSION["search_array"] -> This array contains search data
****************************************************************************/

require("cfg.inc.php");
global $default_rows_per_page, $use_trans_sid;

if($use_trans_sid)
ini_set('session.use_trans_sid', 1);


session_start();


//If this is a new session the existing session is destroyed and recreated

if (isset($_GET['new_function']))
$_SESSION["new_function"] = $_GET['new_function'];
else if(!isset($_SESSION["new_function"]))
$_SESSION["new_function"] = 'false';

if($_SESSION["new_function"] == 'true'){
	session_destroy();
	session_start();
}

if (isset($_POST['submit_delete'])){
	$_SESSION["submit_delete"] = true;
}
else
$_SESSION["submit_delete"] = false;

if (isset($_POST['submit_insert'])){
	unset($_POST['submit_insert']);
	$_SESSION["submit_insert"] = true;
	$_SESSION["ins_array"] = $_POST;
}
else
$_SESSION["submit_insert"] = false;

if (isset($_POST['submit_update'])){
	unset($_POST['submit_update']);
	$_SESSION["submit_update"] = true;
	$_SESSION["up_array"] = $_POST;
}
else
$_SESSION["submit_update"] = false;



if (isset($_POST['submit_search']) ){
	unset($_POST['submit_search']);
	$_SESSION["submit_search"] = true;
	$_SESSION["search_array"] = $_POST;
}
else
$_SESSION["submit_search"] = false;

// Continue a previous search
if(isset($_GET['submit_search']))
$_SESSION["submit_search"] = true;

if (isset($_GET['current_table']))
$_SESSION["current_table"] = $_GET['current_table'];
else if(!isset($_SESSION["current_table"]))
$_SESSION["current_table"] = $table_list[0];

if (isset($_GET['detail_id']))
$_SESSION["detail_id"] = $_GET['detail_id'];
else if(!isset($_SESSION["detail_id"]))
$_SESSION["detail_id"] = "detail_id";

if (isset($_GET['detail_type']))
$_SESSION["detail_type"] = $_GET['detail_type'];
else if(!isset($_SESSION["detail_type"]))
$_SESSION["detail_type"] = "detail_type";

if (isset($_GET['current_rows_per_page']))
$_SESSION["current_rows_per_page"] = $_GET['current_rows_per_page'];
else if(!isset($_SESSION["current_rows_per_page"])){
	$_SESSION["current_rows_per_page"] = $default_rows_per_page;
}

if (isset($_GET['current_page']))
$_SESSION["current_page"] = $_GET['current_page'];
else if(!isset($_SESSION["current_page"]))
$_SESSION["current_page"] = 1;

if (isset($_GET['order_by'])){

		// We decide if the order will be ascendent or descendent too (odd mouse click or even mouse click)

	$tempOrderBy = " ".$_SESSION['order_by'];  
	if (!strpos($tempOrderBy, $_GET['order_by'])){
		$_SESSION["order_by"] = $_GET['order_by']." DESC";
	}
	else if (strpos($tempOrderBy, "DESC" )){
		$_SESSION["order_by"] = $_GET['order_by']." ASC";
	}
	else{
		$_SESSION["order_by"] = $_GET['order_by']." DESC";
	}

	$_SESSION["order_by"] = substr($_SESSION["order_by"], 0); 
}
else if(!isset($_SESSION["order_by"]))
$_SESSION["order_by"] = '';

global $table_list;

require_once("./inc/db.inc.php");
foreach($table_list as $value){
require_once("./classes/".$value.".class.inc.php");
}
require_once("./inc/functions.inc.php");


?>