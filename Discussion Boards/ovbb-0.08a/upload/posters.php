<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2005 Jonathon J. Freeman                              //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the OvBB License Agreement v2 as published by the  //
//  OvBB Project at www.ovbb.org.                                            //
//                                                                           //
//***************************************************************************//

	// Initialize OvBB.
	require('includes/init.inc.php');

	// What thread do they want?
	$iThreadID = mysql_real_escape_string($_REQUEST['threadid']);

	// Get each poster in the thread and their number of posts in the thread.
	$i = 0;
	$sqlResult = sqlquery("SELECT member.id, member.username, COUNT(post.id) AS posts FROM member JOIN post ON (post.author = member.id) WHERE post.parent='$iThreadID' GROUP BY username ORDER BY postcount DESC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Store the information into the Master table.
		$aMaster[$i][0] = $aSQLResult['id'];
		$aMaster[$i][1] = $aSQLResult['username'];
		$aMaster[$i][2] = $aSQLResult['posts'];

		// Increment the index.
		$i++;

		// Add the posts to the total count.
		$iTotalPosts = $iTotalPosts + $aSQLResult['posts'];
	}

	// Is it a valid thread?
	if(!isset($aMaster))
	{
		// Nope.
		Msg("Invalid thread specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>

<META http-equiv="content-type" content="text/html; charset=utf-8">
<LINK rel="SHORTCUT ICON" href="favicon.ico">
<TITLE>Who Posted?</TITLE>

<STYLE type="text/css">
<!--
	BODY
	{
		Margin: 0px;
		Padding: 5px;
		scrollbar-arrow-color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
		scrollbar-base-color: <?php echo($CFG['style']['table']['section']['bgcolor']); ?>;
		font-family: verdana, arial, helvetica, sans-serif;
	}
	A:link, A:visited, A:active, A:hover
	{
		color: <?php echo($CFG['style']['l_normal']['l']); ?>;
	}

	A.cat:link, A.cat:visited, A.cat:active
	{
		color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
		text-decoration: none;
	}
	A.cat:hover
	{
		color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;
		text-decoration: underline;
	}

	A.underline:link, A.underline:visited, A.underline:active
	{
		text-decoration: none;
	}
	A.underline:hover
	{
		text-decoration: underline;
	}

	TEXTAREA, SELECT
	{
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 11px;
		background-color: #CFCFCF;
	}

	.tinput
	{
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 12px;
		background-color: #CFCFCF;
	}

	.smaller
	{
		font-size: 10px;
	}
	.small
	{
		font-size: 11px;
	}
	.medium
	{
		font-size: 13px;
	}
-->
</STYLE>

</HEAD>
<BODY bgcolor="<?php echo($CFG['style']['page']['bgcolor']); ?>">

<TABLE bgcolor="<?php echo($CFG['style']['forum']['bgcolor']); ?>" width="100%" cellpadding=10 cellspacing=0 border=0 align=center>
<TR><TD width="100%" class=medium>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing=1 cellpadding=4 border=0 align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align=left colspan=5><FONT class=medium color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><B>Total Posts: <?php echo($iTotalPosts); ?></B></FONT></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>"><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>User</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Posts</B></FONT></TD>
</TR>

<?php
	// Display the HTML table.
	$flagColor = TRUE;
	reset($aMaster);
	foreach($aMaster as $row)
	{
		$iUserID = $row[0];
		$strUsername = htmlspecialchars($row[1]);
		$iPosts = $row[2];

		// Set the color.
		if($flagColor)
		{
			$strColor = $CFG['style']['table']['cellb'];
			$flagColor = FALSE;
		}
		else
		{
			$strColor = $CFG['style']['table']['cella'];
			$flagColor = TRUE;
		}
?>

<TR>
	<TD bgcolor="<?php echo($strColor); ?>" class=medium><A href="profile.php?userid=<?php echo($iUserID); ?>" target="_blank"><?php echo($strUsername); ?></A></TD>
	<TD bgcolor="<?php echo($strColor); ?>" align=center class=medium><?php echo($iPosts); ?></TD>
</TR>

<?php
	}
?>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=center colspan=2 class=smaller><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="javascript:opener.location=('thread.php?threadid=<?php echo($iThreadID); ?>');self.close();">[Show thread &amp; close window.]</A></TD></TR>

</TABLE>

</TD></TR>
</TABLE>

</BODY>
</HTML>