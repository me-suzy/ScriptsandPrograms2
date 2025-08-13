<?php

/*

piFAQ
http://pifaq.sourceforge.net
Copyright (c), 1999 - 2002 - Pavel Ivanov (pavel_i@yahoo.com)                  


This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation (version 2 or later).                                  

This program is distributed in the hope that it will be useful,      
but WITHOUT ANY WARRANTY; without even the implied warranty of       
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
GNU General Public License for more details.                         

You should have received a copy of the GNU General Public License    
along with this program; if not, write to the Free Software          
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.   

*/

// Edit this
$mysql_faq_db = "rabota_other"; // Mysql database name
$mysql_host = "localhost"; // Mysql host
$mysql_user = "root"; //Mysql username
$mysql_pass = ""; // Mysql password
$mysql_faq_table = "faq"; // Mysql table (in table.sql it's - "faq")

$pathtopifaq = "/faq/pifaq-0.0.2-1"; // Dirictory where is piFAQ based 

$admin_mail = "admin@admin"; // Admin email
$admin_login = "admin"; // Username to add/edit questions
$admin_pass = "admin"; // Password to add/edit questions

// Are you shure you wont edit it? $)
// Don't toch it if you are not shure.
include("class.FastTemplate.php");
$tpl = new FastTemplate("tpl");

$date = date("Y-m-d H-i-s");

// Connecting to mysql
mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die(mysql_error());


function PageStart()
{
	global $tpl;
	
	$tpl->define(array("index" => "index.tpl", "body" => "body.tpl", "error" => "error.tpl", "add" => "add.tpl", "login" => "login.tpl", "addq" => "addq.tpl", "copy" => "copy.tpl", "edit" => "edit.tpl"));
	$tpl -> define_dynamic("question-answer", "body");
	$tpl -> define_dynamic("edit-question", "edit");
}

Function PageFinish()
{
	global $tpl, $pifaqlogin, $pifaqpass, $admin_pass, $admin_login;
	
	if ($pifaqlogin != $admin_login or $pifaqpass != $admin_pass)
	{
		$tpl -> assign(array("LOGOUT" => ""));
	}
	else
	{
		$tpl -> assign(array("LOGOUT" => "Logout"));
	}
		
	$tpl -> parse(COPY, copy);
	$tpl -> parse(index, index);
	$tpl -> FastPrint();
}

Function CheckLogin()
{
	global $tpl, $pifaqlogin, $pifaqpass, $admin_login, $admin_pass, $SCRIPT_URL;
	
	if ($pifaqlogin != $admin_login or $pifaqpass != $admin_pass)
	{
		setcookie("pifaqlogin");
		setcookie("pifaqpass");
		
		$tpl -> assign(array("TITLE" => "Identify your self", "BACK" => $SCRIPT_URL));
		$tpl -> parse(BODY, login);
		PageFinish();
		exit;
	}
}

?>
