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

	global $tpl, $mysql_faq_db, $mysql_faq_table;
	
	PageStart();
	
	$result = mysql_db_query($mysql_faq_db, "select * from $mysql_faq_table order by date") or die(mysql_error());
	
	
	for ($i; $i <= mysql_numrows($result) - 1; $i++)
	{
		$row = mysql_fetch_array($result);
		
		$tpl -> assign(array("QUESTION" => "$row[question]", "ANSWER" => "$row[answer]"));
		$tpl -> parse(SMTH, ".question-answer");
	}
	
	$tpl -> assign(array("TITLE" => "FAQ"));
	$tpl -> parse(BODY, body);
	
	PageFinish(); 


?>
