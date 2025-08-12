<?php
/*  
    Poll Processor
    (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include '../../inc/config_cms/configuration.php';	
include '../../inc/db/db.php';	


	$vote = $_POST['vote'];
	$pageloc = $_POST['pageloc'];
	
		
	//update numbers
	$db = new DB();
	$db->query("UPDATE ". DB_PREPEND . "poll_question SET totalvotes = totalvotes + 1");
	
	
	$db = new DB();
	$db->query("UPDATE ". DB_PREPEND . "poll_answers SET votes = votes + 1 WHERE id = $vote");
	
			
	//add ip to stop multiple voting
	$ip = $_SERVER['REMOTE_ADDR'];
	$db = new DB();
	$db->query("INSERT INTO ". DB_PREPEND . "poll_ip (ip)". "VALUES ('$ip')"); 
    
	
	//send the user back to the page they were just viewing
	header("Location: $pageloc");
	exit;
?>