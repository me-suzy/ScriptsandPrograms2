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

	// Do they have authorization to view the list?
	if(!$aPermissions['cviewmembers'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What do they want to sort by?
	$strSortBy = strtolower($_REQUEST['sortby']);
	switch($strSortBy)
	{
		// They specified us something valid.
		case 'username':
		case 'datejoined':
		case 'postcount':
		{
			break;
		}

		// They don't know what they want. We'll sort by username.
		default:
		{
			$strSortBy = 'username';
			break;
		}
	}

	// What order do they want it sorted in?
	$strSortOrder = strtoupper($_REQUEST['sortorder']);
	if(($strSortOrder != 'ASC') && ($strSortOrder != 'DESC'))
	{
		// They don't know what they want. We'll sort ascending.
		$strSortOrder = 'ASC';
	}

	// How many users per page do they want to view?
	$iPerPage = (int)$_REQUEST['perpage'];
	if($iPerPage < 1)
	{
		// They don't know what they want. Give them 15 users per page.
		$iPerPage = 15;
	}

	// What page do they want to view?
	$iPage = (int)$_REQUEST['page'];
	if($iPage < 1)
	{
		// They don't know what they want. Give them the first page.
		$iPage = 1;
	}

	// Calculate the offsets.
	$iOffset = ($iPage * $iPerPage) - $iPerPage;

	// Initial characterization?
	if(ctype_alpha($_REQUEST['letter']) && (strlen($_REQUEST['letter']) == 1))
	{
		$strWhereClause = "WHERE username LIKE '{$_REQUEST['letter']}%'";
	}
	else if($_REQUEST['letter'] == '#')
	{
		$strWhereClause = "WHERE SUBSTRING(username FROM 1 FOR 1) NOT IN ('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z')";
	}

	// Get the total number of members.
	$sqlResult = sqlquery("SELECT COUNT(id) FROM member $strWhereClause");
	list($iNumberMembers) = mysql_fetch_row($sqlResult);

	// Get the members and all their information.
	$sqlResult = sqlquery("SELECT id, username, lastactive, loggedin, website, datejoined, postcount, invisible FROM member $strWhereClause ORDER BY $strSortBy $strSortOrder, id DESC LIMIT $iOffset, $iPerPage");

	// Calculate the number of pages.
	$iNumberPages = ceil($iNumberMembers / $iPerPage);

	// Header.
	$strPageTitle = ' :: Member List';
	require('includes/header.inc.php');
?>
<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; Member List</B></TD>
</TR>
</TABLE><BR>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing=1 cellpadding=2 border=0 align=center>
<TR><TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="100%">
	<TABLE width="100%" cellspacing=0 cellpadding=0 border=0>
	<TR>
		<TD align=center class=medium><A href="memberlist.php?letter=%23">#</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=a">A</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=b">B</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=c">C</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=d">D</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=e">E</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=f">F</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=g">G</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=h">H</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=i">I</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=j">J</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=k">K</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=l">L</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=m">M</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=n">N</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=o">O</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=p">P</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=q">Q</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=r">R</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=s">S</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=t">T</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=u">U</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=v">V</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=w">W</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=x">X</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=y">Y</A></TD>
		<TD align=center class=medium><A href="memberlist.php?letter=z">Z</A></TD>
	</TR>
	</TABLE>
</TD></TR>
</TABLE><BR>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" cellspacing=1 cellpadding=4 border=0 align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align=left colspan=5><FONT class=medium color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><B><?php echo(htmlspecialchars($CFG['general']['name'])); ?></B></FONT></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="40%" align=left valign=middle colspan=2>
		<TABLE cellspacing=0 cellpadding=0 border=0><TR>
			<TD><IMG src="images/space.png" width=15 height=1 alt=""></TD>
			<TD><IMG src="images/space.png" width=9 height=1 alt=""></TD>
			<TD align=left class=smaller><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="memberlist.php?letter=<?php echo(urlencode($_REQUEST['letter'])); ?>&amp;perpage=<?php echo($iPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=username&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='username')){echo('desc');}else{echo('asc');} ?>">Username</A></B><?php if($strSortBy == 'username'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy == 'username'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="20%" align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>Web Site</B></FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="20%" align=center>
		<TABLE cellpadding=0 cellspacing=0 border=0><TR>
			<TD class=smaller><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="memberlist.php?letter=<?php echo(urlencode($_REQUEST['letter'])); ?>&amp;perpage=<?php echo($iPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=datejoined&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='datejoined')){echo('desc');}else{echo('asc');} ?>">Join Date</A></B><?php if($strSortBy == 'datejoined'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy == 'datejoined'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" width="20%" align=center nowrap>
		<TABLE cellpadding=0 cellspacing=0 border=0><TR>
			<TD class=smaller><B><A class=underline style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;" href="memberlist.php?letter=<?php echo(urlencode($_REQUEST['letter'])); ?>&amp;perpage=<?php echo($iPerPage); ?>&amp;page=<?php echo($iPage); ?>&amp;sortby=postcount&amp;sortorder=<?php if(($strSortOrder=='ASC')&&($strSortBy=='postcount')){echo('desc');}else{echo('asc');} ?>">Post Count</A></B><?php if($strSortBy == 'postcount'){echo('&nbsp;');} ?></TD>
			<TD class=smaller><?php if($strSortBy == 'postcount'){if($strSortOrder=='ASC'){echo(' <IMG src="images/sort_asc.png" style="vertical-align: middle;" alt="Ascending">');}else{echo(' <IMG src="images/sort_desc.png" style="vertical-align: middle;" alt="Descending">');}} ?></TD>
		</TR></TABLE>
	</TD>
</TR>
<?php
	// Display the rows of the table.
	$strColor = $CFG['style']['table']['cella'];
	$i = 0;
	while(($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC)) && ($i < $iPerPage))
	{
		// Get the member information.
		$iID = $aSQLResult['id'];
		$strUsername = htmlspecialchars($aSQLResult['username']);
		$strWebsite = htmlspecialchars($aSQLResult['website']);
		$dateJoined = gmtdate('m-d-Y', strtotime($aSQLResult['datejoined']));
		$iPostCount = $aSQLResult['postcount'];

		// Is this member online or offline?
		// (Is their last activity within the last 300 seconds [5 minutes]?)
		if((($aSQLResult['lastactive'] + 300) >= $CFG['globaltime']) && ($aSQLResult['loggedin']) && (!$aSQLResult['invisible']))
		{
			// Yes, they are online.
			$strStatus = 'online';
		}
		else
		{
			// No, they are offline.
			$strStatus = 'offline';
		}
?>
<TR>
	<TD bgcolor="<?php echo($strColor); ?>" width="40%" align=left valign=middle colspan=2>
	<TABLE cellspacing=0 cellpadding=0 border=0>
	<TR>
		<TD align=center valign=middle><IMG src="images/<?php if($strStatus == 'offline'){echo('in');} ?>active.png" align=middle alt="<?php echo($strUsername); ?> is <?php echo($strStatus); ?>"></TD>
		<TD><IMG src="images/space.png" width=9 height=17 alt=""></TD>
		<TD align=left valign=middle><FONT class=medium><A href="profile.php?userid=<?php echo($iID); ?>"><?php echo($strUsername); ?></A></FONT></TD>
	</TR>
	</TABLE>
	</TD>
	<TD bgcolor="<?php echo($strColor); ?>" width="20%" align=center><?php if($strWebsite){echo('<A href="'.$strWebsite.'" target="_blank"><IMG src="images/user_www.png" alt="Visit '.$strUsername.'\'s Web site" border=0></A>');} ?></TD>
	<TD bgcolor="<?php echo($strColor); ?>" width="20%" align=center><FONT class=medium><?php echo($dateJoined); ?></FONT></TD>
	<TD bgcolor="<?php echo($strColor); ?>" width="20%" align=center><FONT class=medium><?php echo($iPostCount); ?></FONT></TD>
</TR>
<?php
		// Set the color.
		if($strColor == $CFG['style']['table']['cellb'])
		{
			$strColor = $CFG['style']['table']['cella'];
		}
		else
		{
			$strColor = $CFG['style']['table']['cellb'];
		}

		// Reset the Website.
		$strWebsite = '';

		// Increment the counter.
		$i++;
	}
?>

</TABLE>

<DIV class=small align=center><BR><B>Pages</B> (<?php echo("$iPage of $iNumberPages"); ?>):
<?php
	// Put a link to the first page and some elipses if the first page we list isn't 1.
	if(($iPage - 3) > 1)
	{
		echo(' <A href="memberlist.php?letter='.urlencode($_REQUEST['letter']).'&amp;perpage='.$iPerPage.'&amp;page=1&amp;sortby='.$strSortBy.'&amp;sortorder='.strtolower($strSortOrder).'">&laquo; First</A> ...');
	}

	// Show a left arrow if there are pages before us.
	if($iPage > 1)
	{
		echo(' <A href="memberlist.php?letter='.urlencode($_REQUEST['letter']).'&amp;perpage='.$iPerPage.'&amp;page='.($iPage-1).'&amp;sortby='.$strSortBy.'&amp;sortorder='.strtolower($strSortOrder).'">&laquo;</A>');
	}

	// Put up the numbers before us, if any.
	for($i = ($iPage - 3); $i < $iPage; $i++)
	{
		// Only print out the number if it's a valid page.
		if($i > 0)
		{
			echo(' <A href="memberlist.php?letter='.urlencode($_REQUEST['letter']).'&amp;perpage='.$iPerPage.'&amp;page='.($i).'&amp;sortby='.$strSortBy.'&amp;sortorder='.strtolower($strSortOrder).'">'.$i.'</A>');
		}
	}

	// Display our page number as a non-link in brackets.
	echo(" $iPage ");

	// Put up the numbers after us, if any.
	for($i = ($iPage + 1); $i < ($iPage + 4); $i++)
	{
		// Only print out the number if it's a valid page.
		if($i <= $iNumberPages)
		{
			echo(' <A href="memberlist.php?letter='.urlencode($_REQUEST['letter']).'&amp;perpage='.$iPerPage.'&amp;page='.($i).'&amp;sortby='.$strSortBy.'&amp;sortorder='.strtolower($strSortOrder).'">'.$i.'</A>');
		}
	}

	// Show a right arrow if there are pages after us.
	if($iNumberPages > $iPage)
	{
		echo(' <A href="memberlist.php?letter='.urlencode($_REQUEST['letter']).'&amp;perpage='.$iPerPage.'&amp;page='.($iPage+1).'&amp;sortby='.$strSortBy.'&amp;sortorder='.strtolower($strSortOrder).'">&raquo;</A>');
	}

	// Put some elipses and a link to the last page if the last page we list isn't the last.
	if(($iPage + 3) < $iNumberPages)
	{
		echo(' ... <A href="memberlist.php?letter='.urlencode($_REQUEST['letter']).'&amp;perpage='.$iPerPage.'&amp;page='.$iNumberPages.'&amp;sortby='.$strSortBy.'&amp;sortorder='.strtolower($strSortOrder).'">Last &raquo;</A>');
	}
?>
</DIV>
<?php
	// Footer.
	require('includes/footer.inc.php');
?>