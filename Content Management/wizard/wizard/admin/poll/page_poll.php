<?php 
/*  
    Poll Displayed on Public Page
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

$user_ip = $_SERVER['REMOTE_ADDR'];

 
 $id = $_GET['id'];
 
//poll text and color settings
$db = new DB();
$db->query("select * from ". DB_PREPEND . "poll_config");
$settings = $db->next_record();
$width = $settings['width'];
$border = $settings['border'];
$header = $settings['header'];
$headtext = $settings['headtext'];
$background = $settings['background'];
$strip = $settings['strip'];
$percent = $settings['percent'];
$size = $settings['size'];
$text = $settings['text'];

//check to see if user has already voted
$db = new DB();
$db->query("SELECT * FROM ". DB_PREPEND . "poll_ip WHERE ip='$user_ip'");
$voted = $db->num_rows();

echo "<table border=\"1\" bordercolor=\"".$border."\" width=\"".$width."\" bgcolor=\"".$background."\" cellspacing=\"0\" cellpadding=\"5\" >";
$width = $width - 10;
echo "<tr><td><table border=\"0\" width=\"".$width ."\" bgcolor=\"".$background."\" cellspacing=\"0\" cellpadding=\"0\" >";
echo "<tr><img src=\"../images/common/spacer.gif\" width=\"1\" height=\"2\" /><td style=\"line-height:1.3em; font-size:9px;\" height=\"5\" bgcolor=\"".$header."\"><center><strong><font style=\"font-weight:bold\" color=\"".$headtext."\" size=\"1.1em\">Poll</strong></center></td></tr>";
echo "<tr><td style=\"line-height:1.4em; font-size:.8em;\" cellspacing=\"0\" cellpadding=\"0\" height=\"5\" >";

if($voted){

	//display results
	
	
	$db = new DB();
	$db->query("select * from ". DB_PREPEND . "poll_question");
	$result = $db->next_record();
	$question = $result['question'];
	
	$db = new DB();
	$db->query("select votes from ". DB_PREPEND . "poll_answers");
	while ($row = $db->next_record()) {
    	$totalvotes += $row["votes"];
	}
			
	echo "<br />" . $question . "<br /><br />";
	
	$db = new DB();
	$db->query("select * from ". DB_PREPEND . "poll_answers");
	while($r= $db->next_record()){
	
	
		extract($r);
		$per = $votes * 100 / $totalvotes;
		$per = floor($per);
		
		echo htmlspecialchars($field); 
		?> &nbsp;&nbsp;<strong><? echo("$votes"); ?></strong><br />
		<div style="background-color:<? echo $strip; ?>;"><div style="color:<? echo $text; ?>; text-align: right;background-color:<? echo $percent; ?>; font-size: <? echo $size; ?>em; width: <? echo($per); ?>%;"><? echo("$per%"); ?></div></div>
		<?
			
	}
	
	echo("<center><br />Votes: <strong>$totalvotes</strong></center>"); 
	
	
	
	
	
}else{


	$uri = $_SERVER['REQUEST_URI'];
	
	
?>
	
	<form action="<? echo CMS_WWW . "/admin/poll/page_pollPro.php";  ?>" method="post">
	
<?php
		
	$db = new DB();
	$db->query("select * from ". DB_PREPEND . "poll_question");
	$result = $db->next_record();
	$question = $result['question'];
	
			
	echo("<br />" . $question . "<br /><br />");
	
	
	$db = new DB();
	$db->query("select * from ". DB_PREPEND . "poll_answers ORDER by id");
	while($r= $db->next_record()){
		 
		extract($r);
		
		?><input type="radio" name="vote" value="<? echo($id); ?>" class="radiobutton" /> <? echo($field); ?><br /><?
		
	}	
		
	echo "<br />";	
	?>
	<input type="hidden" name="pageloc" value="<? echo $_SERVER['PHP_SELF'] . "?id=" . $id; ?>" />
	<input type="submit" name="submit" value="Submit" /> 
	</form>
	<?	
	
	
	
}


echo "</td></tr></table></td></tr></table>";
?>