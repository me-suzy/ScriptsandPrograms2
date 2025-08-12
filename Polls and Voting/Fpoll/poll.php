<?php 

// Author: PHPFront.com © 2005
// License: Free (GPL)
//
// Version: 1.1
//
// Created: 8.12.2005 
//
// More information and downloads 
// available at http://www.PHPFront.com
//
// #### poll.php ####



include("admincp/config.php");



$user_ip = $_SERVER['REMOTE_ADDR'];

$ipquery = mysql_query("SELECT * FROM fpoll_ips WHERE ip='$user_ip'");
$select_banned = mysql_num_rows($ipquery);

if($select_banned){

	//display results
	
	
	$poll = mysql_fetch_array(mysql_query("select * from fpoll_poll"));
	$question = $poll['question'];
	
	$countvotes = mysql_query("select votes from fpoll_options");
	while ($row = mysql_fetch_assoc($countvotes)) {
    	$totalvotes += $row["votes"];
	}
			
	echo("<div class=poll>$question<br /><br />");
	
	$get_questions = mysql_query("select * from fpoll_options");
	while($r=mysql_fetch_array($get_questions)){
	
	
		extract($r);
		$per = $votes * 100 / $totalvotes;
		$per = floor($per);
		
		echo htmlspecialchars($field); 
		?> <strong><? echo("$votes"); ?></strong><br />
		<div style="background-color: <? echo config(bg1); ?>;"><div style="color: <? echo config(text); ?>; font-size: <? echo config(size); ?>px; text-align: right;background-color: <? echo config(bg2); ?>; width: <? echo($per); ?>%;"><? echo("$per%"); ?></div></div>
		<?
			
	}
	
	echo("<br />Total votes: <strong>$totalvotes</strong></div>"); 
	
	
	
	
	
}else{





//if the submit button was pressed
if($_POST['submit']){
	
	//grab vars
	$vote = $_POST['vote'];
	$refer = $_POST['refer'];
	
		
	//update numbers
	$update_totalvotes = "UPDATE fpoll_poll SET totalvotes = totalvotes + 1";
	$insert = mysql_query($update_totalvotes);
	
	$update_votes = "UPDATE fpoll_options SET votes = votes + 1 WHERE id = $vote";
	$insert = mysql_query($update_votes);
			
	//add ip to stop multiple voting
	$ip = $_SERVER['REMOTE_ADDR'];
	$addip = mysql_query("INSERT INTO fpoll_ips (ip)". "VALUES ('$ip')"); 

	
	//send the user back to thepage they were just viewing
	header("Location: $refer");
	
	
		
}	

	$uri = $_SERVER['REQUEST_URI'];
	
	//display the form!
	?><div class="poll"><form action="/Fpoll/poll.php" method="post"><?
		
	$poll = mysql_fetch_array(mysql_query("select * from fpoll_poll"));
	$question = $poll['question'];
			
	echo("$question<br /><br />");
	
	
	$getcurrent = mysql_query("select * from fpoll_options ORDER by id");
	while($r=mysql_fetch_array($getcurrent)){
		
		extract($r);
		
		?><input type="radio" name="vote" value="<? echo($id); ?>" class="radiobutton" /> <? echo($field); ?><br /><?
		
	}	
		
		
	?>
	<input type="hidden" name="refer" value="<? echo $_SERVER['PHP_SELF']; ?>" />
	<input type="submit" name="submit" value="Submit" /> 
	</form></div>
	<?	
	
	
	
}



?>