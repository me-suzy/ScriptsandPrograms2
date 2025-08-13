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

PageStart();
	
CheckLogin();

$tpl -> assign(array("TITLE" => "Edit questions"));

if ($func == "del")
{
	DelQuestion();
}

if ($func == "edit")
{
	EditQuestion();
}

if ($func == "add")
{
	AddEdited();
}

// Delete question
Function DelQuestion()
{
	global $tpl, $mysql_faq_db, $mysql_faq_table, $id;
	mysql_db_query($mysql_faq_db, "delete from $mysql_faq_table where id='$id'") or die(mysql_error());
	$tpl -> assign(array("TITLE" => "Edit questions","ERROR_MESSAGE" => "Question #$id was deleted"));
}

// Edit question
Function EditQuestion()
{
	global $tpl, $mysql_faq_db, $mysql_faq_table, $id, $func, $question, $answer;
	
	$result = mysql_db_query($mysql_faq_db, "select * from $mysql_faq_table where id='$id'");

	// We hope thet question whirh this ID is exist :-)

	$row = mysql_fetch_array($result);


	$text = "Edit question.";
	$tpl -> assign(array("TITLE" => "$text","ERROR_MESSAGE" => "$text", "QUESTION" => "$row[question]", "ANSWER" => "$row[answer]", "ID" => "$row[id]"));
	$tpl -> parse(BODY_MESSAGE, addq);
	$tpl -> parse(BODY, error);
	PageFinish();
	exit;

}

Function AddEdited()
{
	global $tpl, $mysql_faq_db, $mysql_faq_table, $id, $func, $question, $answer, $date;
	
	if (strlen($question) > 0 and strlen($answer) > 0)
	{
		$result = mysql_db_query($mysql_faq_db, "update $mysql_faq_table set id='$id', question='$question', answer='$answer', date='$date' where id='$id'") or die(mysql_error());
	}
	
}

// Show questions
$result = mysql_db_query($mysql_faq_db, "select * from $mysql_faq_table order by date");

for ($i; $i <= mysql_num_rows($result) - 1; $i++)
{
	$row = mysql_fetch_array($result);
	
	$tpl -> assign(array("ID" => $row[id], "QUESTION" => $row[question], ANSWER => $row[answer], "DATE" => $row[date], "EDIT" => "<a href=edit.php?func=edit&id=$row[id]>Edit</a>","DELETE" => "<a href=edit.php?func=del&id=$row[id]>Delete</a>"));
	$tpl -> parse(SOMTHING, ".edit-question");
}


$tpl -> parse(BODY, edit);

PageFinish();

?>
