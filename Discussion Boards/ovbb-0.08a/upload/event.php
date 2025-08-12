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

	// What are they wanting to do?
	switch($_REQUEST['action'])
	{
		// View event.
		case 'view':
		{
			ViewEvent();
			break;
		}

		// Add new event.
		case 'add':
		{
			AddEvent();
			break;
		}
	}

// *************************************************************************** \\

function AddEvent()
{
	global $CFG, $aPermissions;

	// Default values.
	$bParseURLs = FALSE;
	$bParseEMails = TRUE;
	$bDisableSmilies = FALSE;

	// What kind of event do they want to create?
	switch($_REQUEST['type'])
	{
		case 'public':
		{
			$strType = ' Public';
			$bPublicEvent = TRUE;

			// Does the user have authorization to add public events?
			if(!$aPermissions['cmakepubevent'])
			{
				// No. Let them know the bad news.
				Unauthorized();
			}

			break;
		}

		case 'private':
		{
			$strType = ' Private';
			$bPublicEvent = FALSE;
				// Does the user have authorization to add private events?
			if(!$aPermissions['cmakeevent'])
			{
				// No. Let them know the bad news.
				Unauthorized();
			}

			break;
		}

		default:
		{
			$bPublicEvent = (bool)$_REQUEST['type'];
			$strType = $bPublicEvent ? ' Public' : ' Private';
				// Does the user have authorization to add events?
			if((($bPublicEvent) && (!$aPermissions['cmakepubevent'])) || ((!bPublicEvent) && (!$aPermissions['cmakeevent'])))
			{
				// No. Let them know the bad news.
				Unauthorized();
			}

			break;
		}
	}

	// Are they submitting?
	if($_REQUEST['submit'] == 'Submit Event')
	{
		// Yup.
		$aError = SubmitEvent((int)$bPublicEvent);
	}

	// Get the date they passed (if any) and check it.
	if(checkdate($_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year']))
	{
		// Valid date; use the values they gave us.
		$iMonth = (int)$_REQUEST['month'];
		$iDay = (int)$_REQUEST['day'];
		$iYear = (int)$_REQUEST['year'];
	}
	else
	{
		// Invalid date; use today's date.
		$iMonth = date('n');
		$iDay = date('d');
		$iYear = date('Y');
	}

	// Get the smilies installed.
	require('includes/smilies.inc.php');

	// Get the post icons installed.
	require('includes/posticons.inc.php');

	// Header.
	$strPageTitle = " :: Calendar :. New$strType Event";
	require('includes/header.inc.php');
?>

<SCRIPT language="JavaScript" type="text/javascript">
<!--
function vbcode(code)
{
	inserttext = prompt("Enter text to be formatted:\n[" + code + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		document.theform.eventinfo.value = document.theform.eventinfo.value + "[" + code + "]" + inserttext + "[/" + code + "]";
	}
	document.theform.message.focus();
}

function vbcode2(code, option)
{
	inserttext = prompt("Enter the text to be formatted:\n[" + code + "=" + option + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		document.theform.eventinfo.value = document.theform.eventinfo.value + "[" + code + "=" + option + "]" + inserttext + "[/" + code + "]";
	}

	document.theform.tsize.selectedIndex = 0;
	document.theform.tfont.selectedIndex = 0;
	document.theform.tcolor.selectedIndex = 0;
	document.theform.eventinfo.focus();
}

function smilie(smilie)
{
	document.theform.eventinfo.value = document.theform.eventinfo.value + smilie;
	document.theform.eventinfo.focus();
}
//-->
</SCRIPT>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="calendar.php">Calendar</A> &gt; New<?php echo($strType); ?> Event</B></TD>
</TR>
</TABLE>

<?php
	// Display any errors that were generated when submitting (if they did, that is).
	if(is_array($aError))
	{
		DisplayErrors($aError);

		// The user had submitted something, so out with the default values and in with what they submitted.
		$strSubject = $_REQUEST['subject'];
		$strEventInfo = $_REQUEST['eventinfo'];
		$iMonth = (int)$_REQUEST['month'];
		$iDay = (int)$_REQUEST['day'];
		$iYear = (int)$_REQUEST['year'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];
	}
	else
	{
		echo('<br>');
	}
?>

<form style="margin: 0px;" name="theform" action="event.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="action" value="add">
<input type="hidden" name="type" value="<?php echo((int)$bPublicEvent); ?>">

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>New<?php echo($strType); ?> Event</B></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Logged In As</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php if($_SESSION['loggedin']){echo(htmlspecialchars($_SESSION['username']).' <FONT class=smaller>[<A href="logout.php">Logout</A>]</FONT>');}else{echo('<I>Not logged in.</I> <FONT class=smaller>[<A href="login.php">Login</A>]</FONT>');} ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap><B>Date</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller>
		<TABLE cellpadding=2 cellspacing=0 border=0>
		<TR>
			<TD align=left class=smaller>&nbsp;Month</TD>
			<TD align=left class=smaller>&nbsp;Day</TD>
			<TD align=left class=smaller>&nbsp;Year</TD>
		</TR>
		<TR>
			<TD>
				<SELECT name=month>
					<OPTION value=1<?php if($iMonth==1){echo(' selected');} ?>>January</OPTION>
					<OPTION value=2<?php if($iMonth==2){echo(' selected');} ?>>February</OPTION>
					<OPTION value=3<?php if($iMonth==3){echo(' selected');} ?>>March</OPTION>
					<OPTION value=4<?php if($iMonth==4){echo(' selected');} ?>>April</OPTION>
					<OPTION value=5<?php if($iMonth==5){echo(' selected');} ?>>May</OPTION>
					<OPTION value=6<?php if($iMonth==6){echo(' selected');} ?>>June</OPTION>
					<OPTION value=7<?php if($iMonth==7){echo(' selected');} ?>>July</OPTION>
					<OPTION value=8<?php if($iMonth==8){echo(' selected');} ?>>August</OPTION>
					<OPTION value=9<?php if($iMonth==9){echo(' selected');} ?>>September</OPTION>
					<OPTION value=10<?php if($iMonth==10){echo(' selected');} ?>>October</OPTION>
					<OPTION value=11<?php if($iMonth==11){echo(' selected');} ?>>November</OPTION>
					<OPTION value=12<?php if($iMonth==12){echo(' selected');} ?>>December</OPTION>
				</SELECT>
			</TD>
			<TD>
				<SELECT name=day>
					<OPTION value=1<?php if($iDay==1){echo(' selected');} ?>>1</OPTION>
					<OPTION value=2<?php if($iDay==2){echo(' selected');} ?>>2</OPTION>
					<OPTION value=3<?php if($iDay==3){echo(' selected');} ?>>3</OPTION>
					<OPTION value=4<?php if($iDay==4){echo(' selected');} ?>>4</OPTION>
					<OPTION value=5<?php if($iDay==5){echo(' selected');} ?>>5</OPTION>
					<OPTION value=6<?php if($iDay==6){echo(' selected');} ?>>6</OPTION>
					<OPTION value=7<?php if($iDay==7){echo(' selected');} ?>>7</OPTION>
					<OPTION value=8<?php if($iDay==8){echo(' selected');} ?>>8</OPTION>
					<OPTION value=9<?php if($iDay==9){echo(' selected');} ?>>9</OPTION>
					<OPTION value=10<?php if($iDay==10){echo(' selected');} ?>>10</OPTION>
					<OPTION value=11<?php if($iDay==11){echo(' selected');} ?>>11</OPTION>
					<OPTION value=12<?php if($iDay==12){echo(' selected');} ?>>12</OPTION>
					<OPTION value=13<?php if($iDay==13){echo(' selected');} ?>>13</OPTION>
					<OPTION value=14<?php if($iDay==14){echo(' selected');} ?>>14</OPTION>
					<OPTION value=15<?php if($iDay==15){echo(' selected');} ?>>15</OPTION>
					<OPTION value=16<?php if($iDay==16){echo(' selected');} ?>>16</OPTION>
					<OPTION value=17<?php if($iDay==17){echo(' selected');} ?>>17</OPTION>
					<OPTION value=18<?php if($iDay==18){echo(' selected');} ?>>18</OPTION>
					<OPTION value=19<?php if($iDay==19){echo(' selected');} ?>>19</OPTION>
					<OPTION value=20<?php if($iDay==20){echo(' selected');} ?>>20</OPTION>
					<OPTION value=21<?php if($iDay==21){echo(' selected');} ?>>21</OPTION>
					<OPTION value=22<?php if($iDay==22){echo(' selected');} ?>>22</OPTION>
					<OPTION value=23<?php if($iDay==23){echo(' selected');} ?>>23</OPTION>
					<OPTION value=24<?php if($iDay==24){echo(' selected');} ?>>24</OPTION>
					<OPTION value=25<?php if($iDay==25){echo(' selected');} ?>>25</OPTION>
					<OPTION value=26<?php if($iDay==26){echo(' selected');} ?>>26</OPTION>
					<OPTION value=27<?php if($iDay==27){echo(' selected');} ?>>27</OPTION>
					<OPTION value=28<?php if($iDay==28){echo(' selected');} ?>>28</OPTION>
					<OPTION value=29<?php if($iDay==29){echo(' selected');} ?>>29</OPTION>
					<OPTION value=30<?php if($iDay==30){echo(' selected');} ?>>30</OPTION>
					<OPTION value=31<?php if($iDay==31){echo(' selected');} ?>>31</OPTION>
				</SELECT>
			</TD>
			<TD><INPUT class=tinput type=text name=year size=4 maxlength=4 value="<?php echo((int)$iYear); ?>"></TD>
		</TR>
		</TABLE>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Subject</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=subject size=40 maxlength=64 value="<?php echo(htmlspecialchars($strSubject)); ?>"></TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap><B>vB Code</B> <FONT class=smaller>[<A href="#">What's this?</A>]</FONT></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<INPUT class=tinput type=button onClick="vbcode('b');" onMouseOver="document.theform.status.value='Insert bold text.';" onMouseOut="document.theform.status.value='';" value=" B "><INPUT class=tinput type=button onClick="vbcode('i');" onMouseOver="document.theform.status.value='Insert italic text.';" onMouseOut="document.theform.status.value='';" value=" I "><INPUT class=tinput type=button onClick="vbcode('u');" onMouseOver="document.theform.status.value='Insert underlined text.';" onMouseOut="document.theform.status.value='';" value=" U ">
		<SELECT name=tsize onChange="vbcode2('size', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the size of your text.';" onMouseOut="document.theform.status.value='';">
			<OPTION value=0>SIZE</OPTION>
			<OPTION value=1>Small</OPTION>
			<OPTION value=3>Large</OPTION>
			<OPTION value=4>Huge</OPTION>
		</SELECT><SELECT name=tfont onChange="vbcode2('font', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the font of your text.';" onMouseOut="document.theform.status.value='';">
			<OPTION value=0>FONT</OPTION>
			<OPTION value="arial">Arial</OPTION>
			<OPTION value="courier">Courier</OPTION>
			<OPTION value="times new roman">Times New Roman</OPTION>
		</SELECT><SELECT name=tcolor onChange="vbcode2('color', this.options[this.selectedIndex].value);" onMouseOver="document.theform.status.value='Alter the color of your text.';" onMouseOut="document.theform.status.value='';">
			<OPTION value=0>COLOR</OPTION>
			<OPTION value="skyblue" style="color: skyblue">Sky Blue</OPTION>
			<OPTION value="royalblue" style="color: royalblue">Royal Blue</OPTION>
			<OPTION value="blue" style="color: blue">Blue</OPTION>
			<OPTION value="darkblue" style="color: darkblue">Dark Blue</OPTION>
			<OPTION value="orange" style="color: orange">Orange</OPTION>
			<OPTION value="orangered" style="color: orangered">Orange-Red</OPTION>
			<OPTION value="crimson" style="color: crimson">Crimson</OPTION>
			<OPTION value="red" style="color: red">Red</OPTION>
			<OPTION value="firebrick" style="color: firebrick">Firebrick</OPTION>
			<OPTION value="darkred" style="color: darkred">Dark Red</OPTION>
			<OPTION value="green" style="color: green">Green</OPTION>
			<OPTION value="limegreen" style="color: limegreen">Lime Green</OPTION>
			<OPTION value="seagreen" style="color: seagreen">Sea Green</OPTION>
			<OPTION value="deeppink" style="color: deeppink">Deep Pink</OPTION>
			<OPTION value="tomato" style="color: tomato">Tomato</OPTION>
			<OPTION value="coral" style="color: coral">Coral</OPTION>
			<OPTION value="purple" style="color: purple">Purple</OPTION>
			<OPTION value="indigo" style="color: indigo">Indigo</OPTION>
			<OPTION value="burlywood" style="color: burlywood">Burlywood</OPTION>
			<OPTION value="sandybrown" style="color: sandybrown">Sandy Brown</OPTION>
			<OPTION value="sienna" style="color: sienna">Sienna</OPTION>
			<OPTION value="chocolate" style="color: chocolate">Chocolate</OPTION>
			<OPTION value="teal" style="color: teal">Teal</OPTION>
			<OPTION value="silver" style="color: silver">Silver</OPTION>
		</SELECT><BR>
		<INPUT class=tinput type=button onClick="vbcode('url');" onMouseOver="document.theform.status.value='Insert a hypertext link.';" onMouseOut="document.theform.status.value='';" value="http://"><INPUT class=tinput type=button onClick="vbcode('email');" onMouseOver="document.theform.status.value='Insert an e-mail link.';" onMouseOut="document.theform.status.value='';" value=" @ "><INPUT class=tinput type=button onClick="vbcode('img');" onMouseOver="document.theform.status.value='Insert a linked image.';" onMouseOut="document.theform.status.value='';" value="Image">
		<INPUT class=tinput type=button onClick="vbcode('code');" onMouseOver="document.theform.status.value='Insert source code or monospaced text.';" onMouseOut="document.theform.status.value='';" value="Code"><INPUT class=tinput type=button onClick="vbcode('php');" onMouseOver="document.theform.status.value='Insert text with PHP syntax highlighting.';" onMouseOut="document.theform.status.value='';" value="PHP"><INPUT class=tinput type=button onClick="list();" onMouseOver="document.theform.status.value='Insert an ordered list.';" onMouseOut="document.theform.status.value='';" value="List"><INPUT class=tinput type=button onClick="vbcode('quote');" onMouseOver="document.theform.status.value='Insert a quotation.';" onMouseOut="document.theform.status.value='';" value="Quote"><BR>
		<INPUT style="color: <?php echo($CFG['style']['forum']['txtcolor']); ?>; border-width: 0px; border-style: hidden; font-family: verdana, arial, helvetica, sans-serif; font-size: 10px; background-color: <?php echo($CFG['style']['table']['cellb']); ?>;" type=text name=status value="This toolbar requires JavaScript." size=48 readonly>
	</TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap>
		<B>Event Information</B><BR><BR><BR>

		<TABLE cellpadding=3 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="border-width: 2px; border-style: outset;" align=center>
			<TR>
				<TD colspan=3 align=center bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=small style="border-width: 1px; border-style: inset"><B>Smilies</B></TD>
			</TR>
<?php
	// Display the Smilie table.
	SmilieTable($aSmilies);
?>
		</TABLE>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<TEXTAREA class=tinput name=eventinfo cols=70 rows=20><?php echo(htmlspecialchars($strEventInfo)); ?></TEXTAREA>
		<DIV class=smaller>[<A href="javascript:alert('The maximum permitted length is 10000 characters.\n\nYour event information is '+document.theform.eventinfo.value.length+' characters long.');">Check length.</A>]</DIV>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Options</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<TABLE cellpadding=0 cellspacing=0 border=0>
		<TR>
			<TD valign=top><INPUT type=checkbox name=parseurls<?php if($bParseURLs){echo(' checked');} ?> disabled></TD>
			<TD width="100%" class=smaller><B>Automatically parse URLs?</B> This will automatically put [url] and [/url] around Internet addresses.</TD>
		</TR>
		<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
		<TR>
			<TD valign=top><INPUT type=checkbox name=parseemails<?php if($bParseEMails){echo(' checked');} ?>>
			<TD width="100%" class=smaller><B>Automatically parse e-mail addresses?</B> This will automatically put [email] and [/email] around e-mail addresses.</TD>
		</TR>
		<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
		<TR>
			<TD valign=top><INPUT type=checkbox name=dsmilies<?php if($bDisableSmilies){echo(' checked');} ?>>
			<TD width="100%" class=smaller><B>Disable smilies in this event?</B> This will disable the automatic parsing of smilie codes into smilie images.</TD>
		</TR>
	</TABLE>
	</TD>
</TR>

</TABLE>

<CENTER><BR><INPUT class=tinput type=submit name=submit value="Submit Event" accesskey="s"></CENTER>
</FORM><BR>

<SCRIPT language="JavaScript" type="text/javascript">
<!--
	document.theform.status.value='';
//-->
</SCRIPT>

<?php
	// Footer.
	require('includes/footer.inc.php');
}

// *************************************************************************** \\

// The user hit the Submit Event button, so that's what we'll try to do.
function SubmitEvent($bPublicEvent)
{
	global $CFG;

	// Get the posted values.
	$iMonth = (int)$_REQUEST['month'];
	$iDay = (int)$_REQUEST['day'];
	$iYear = (int)$_REQUEST['year'];
	$strSubject = $_REQUEST['subject'];
	$strEventInfo = $_REQUEST['eventinfo'];
	$bParseURLs = (int)(bool)$_REQUEST['parseurls'];
	$bParseEMails = (int)(bool)$_REQUEST['parseemails'];
	$bDisableSmilies = (int)(bool)$_REQUEST['dsmilies'];

	// Subject
	if(trim($strSubject) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify the event subject.';
	}
	else if(strlen($strSubject) > 64)
	{
		// The subject they specified is too long.
		$aError[] = 'The subject you specified is longer than 64 characters.';
	}
	$strSubject = mysql_real_escape_string($strSubject);

	// Event Information
	if(trim($strEventInfo) == '')
	{
		// They either put in only whitespace or nothing at all.
		$aError[] = 'You must specify the event information.';
	}
	else if(strlen($strEventInfo) > 10000)
	{
		// The event information they specified is too long.
		$aError[] = 'The event information you specified is longer than 10000 characters.';
	}
	if(bParseEMails)
	{
		$strEventInfo = ParseEMails($strEventInfo);
	}
	$strEventInfo = mysql_real_escape_string($strEventInfo);

	// Date
	if(!checkdate($iMonth, $iDay, $iYear))
	{
		// They specified an invalid Gregorian date.
		$aError[] = 'The date you specified is an invalid date. The month, date, and year are all required.';
	}
	$strDate = sprintf('%04d-%02d-%02d', $iYear, $iMonth, $iDay);

	// Is there a user logged in?
	if(!$_SESSION['loggedin'])
	{
		// No user logged in.
		$aError[] = 'You must be logged in to add events.';
	}

	// If there was an error, let's return it.
	if($aError)
	{
		return $aError;
	}

	// Get the IP address of the user.
	$iAuthorIP = ip2long($_SERVER['REMOTE_ADDR']);

	// Add the event into the event table.
	sqlquery("INSERT INTO event(author, date, title, body, public, dsmilies, ipaddress) VALUES({$_SESSION['userid']}, '$strDate', '$strSubject', '$strEventInfo', $bPublicEvent, $bDisableSmilies, $iAuthorIP)");

	// Finally, we need to get the ID of the event we just created.
	$iEventID = mysql_insert_id();

	// Update the user.
	Msg("<b>The event was successfully added.<br />You should be redirected momentarily.</b><br /><br /><font class=\"smaller\">Click <a href=\"event.php?action=view&eventid=$iEventID\">here</a> if you do not want to wait any longer or if you are not redirected.</font>", "event.php?action=view&eventid=$iEventID", 'center');
}

// *************************************************************************** \\

function ViewEvent()
{
	global $CFG, $aPermissions;

	// What event do they want?
	$iEventID = mysql_real_escape_string($_REQUEST['eventid']);

	// Get the information for this event.
	$sqlResult = sqlquery("SELECT author, date, title, body, public, dsmilies FROM event WHERE id=$iEventID");
	if(!(list($iAuthor, $strDate, $strTitle, $strEventInfo, $bPublic, $bDisableSmilies) = mysql_fetch_row($sqlResult)))
	{
		Msg("Invalid event specified. If you followed a link that was on this Web site to get here, please notify the <a href=\"mailto:{$CFG['general']['admin']['email']}\">Webmaster</a>.");
	}
	$strTitle = htmlspecialchars($strTitle);
	$strEventInfo = htmlspecialchars($strEventInfo);

	// Are we allowed to view this event?
	if((!$bPublic) && ($iAuthor != $_SESSION['userid']))
	{
		// Nope. Give them the Unauthorized page.
		Unauthorized();
	}

	// Get the smilies installed.
	require('includes/smilies.inc.php');

	// Parse the message.
	$strEventInfo = ParseMessage($strEventInfo, $bDisableSmilies);

	// Header.
	$strPageTitle = " :: Calendar :. $strTitle";
	require('includes/header.inc.php');
?>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="calendar.php">Calendar</A> &gt; <?php echo($strTitle); ?></B>
</TR>
</TABLE><BR>

<BR><BR>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellspacing=1 cellpadding=4 border=0 align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=center><FONT class=smaller color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B><?php echo($strTitle); ?></B></FONT></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium valign=top><B>Type</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php if($bPublic){echo('Public');}else{echo('Private');} ?> Event</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium valign=top><B>Date</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><?php echo(date('m-d-Y', strtotime($strDate))); ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium valign=top nowrap><B>Event Information</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strEventInfo); ?></TD>
</TR>

</TABLE>
<BR><BR>

<?php
	// Footer.
	require('includes/footer.inc.php');
}
?>