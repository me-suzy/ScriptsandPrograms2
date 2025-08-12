<?php

$GLOBALS['page']->head("wordcircle","","This page is an overview of everything available for this course");

	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("<table cellpadding='0' align='center'>
	");
	$user = new user();
	
	if($GLOBALS['owner'] == true){echo("<tr><td colspan='3'>".$GLOBALS['user']->first_name.", you are the owner of this course. You can edit your overview, news, discussions and documents by clicking the <strong>modify</strong> icon above each box<br>
<br>
</td></tr>");}
	echo("
	<tr><td width='50%' valign='top'>
	");
	
	include("v_groupmess.php");
	
	include("v_documents.php");
	
	echo("<br>");
	
	include("v_projects.php");
	
	echo("
	</td><td>&nbsp;</td><td width='48%' valign='top'>");
	
	
	include("v_thoughts.php");
	
	include("v_discuss.php");
	
	echo("<br>");
	
	include("v_calendar.php");
	
	
	echo("</td></tr></table>");
	
	$GLOBALS['page']->tableEnd("TEXT");
?>