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

include "config.inc.php";

//session_start();

PageStart();

	
CheckLogin();

	$text = "Adding question";
	$tpl -> assign(array("TITLE" => "$text", "QUESTION" => "", "ANSWER" => ""));
	
		

	if (strlen($question) > 0 and strlen($answer) > 0)
	{
		$result = mysql_db_query($mysql_faq_db, "select max(id) AS maxid from $mysql_faq_table") or die(mysql_error());
		$row = mysql_fetch_array($result);
		$id = $row[maxid] + 1;

		$result = mysql_db_query($mysql_faq_db, "insert into $mysql_faq_table(question, answer, date, id) values('$question', '$answer', '$date', '$id')") or die(mysql_error());

		$text = "Question was added.";
		$tpl -> assign(array("TITLE" => "$text","ERROR_MESSAGE" => "$text"));
		$tpl -> parse(BODY_MESSAGE, addq);
		$tpl -> parse(BODY, error);
	}
	else
	{
		$text = "Add question";
		$tpl -> assign(array("TITLE" => "$text","ERROR_MESSAGE" => "$text"));
		$tpl -> parse(BODY_MESSAGE, addq);
		$tpl -> parse(BODY, error);
	}

	
PageFinish();
	
	
?>
