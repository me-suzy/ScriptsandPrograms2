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

	// Is the user logged in?
	if($_SESSION['loggedin'])
	{
		AlreadyRegistered();
	}

	// Is the GD and FreeType libraries installed?
	if(!function_exists('imagettfbbox'))
	{
		// No. Disable image verification.
		$CFG['reg']['verify_img'] = FALSE;
	}

	// Are they coming for the first time or submitting information?
	if($_REQUEST['posting'])
	{
		// Get the submitted information.
		$aReg['username'] = $_REQUEST['username'];
		$aReg['passworda'] = $_REQUEST['passworda'];
		$aReg['passwordb'] = $_REQUEST['passwordb'];
		$aReg['emaila'] = $_REQUEST['emaila'];
		$aReg['emailb'] = $_REQUEST['emailb'];
		$aReg['verifyimg'] = $_REQUEST['verifyimg'];
		$aReg['website'] = $_REQUEST['website'];
		$aReg['aim'] = $_REQUEST['aim'];
		$aReg['icq'] = $_REQUEST['icq'];
		$aReg['msn'] = $_REQUEST['msn'];
		$aReg['yahoo'] = $_REQUEST['yahoo'];
		$aReg['referrer'] = $_REQUEST['referrer'];
		$aReg['birthmonth'] = (int)$_REQUEST['birthmonth'];
		$aReg['birthdate'] = (int)$_REQUEST['birthdate'];
		$aReg['birthyear'] = (int)$_REQUEST['birthyear'];
		if($aReg['birthyear'] == 0) $aReg['birthyear'] = '';
		$aReg['bio'] = $_REQUEST['bio'];
		$aReg['location'] = $_REQUEST['location'];
		$aReg['interests'] = $_REQUEST['interests'];
		$aReg['occupation'] = $_REQUEST['occupation'];
		$aReg['signature'] = $_REQUEST['signature'];
		$aReg['allowmail'] = (int)(bool)$_REQUEST['allowmail'];
		$aReg['invisible'] = (int)(bool)$_REQUEST['invisible'];
		$aReg['publicemail'] = (int)(bool)$_REQUEST['publicemail'];
		$aReg['autologin'] = (int)(bool)$_REQUEST['autologin'];
		$aReg['enablepms'] = (int)(bool)$_REQUEST['enablepms'];
		$aReg['pmnotifya'] = (int)(bool)$_REQUEST['pmnotifya'];
		$aReg['pmnotifyb'] = (int)(bool)$_REQUEST['pmnotifyb'];
		$aReg['showsigs'] = (int)(bool)$_REQUEST['showsigs'];
		$aReg['showavatars'] = (int)(bool)$_REQUEST['showavatars'];
		$aReg['threadview'] = abs((int)$_REQUEST['threadview']);
		$aReg['perpage'] = abs((int)$_REQUEST['perpage']);
		$aReg['weekstart'] = abs((int)$_REQUEST['weekstart']);
		$aReg['timeoffset'] = (int)$_REQUEST['timeoffset'];
		$aReg['dst'] = (int)(bool)$_REQUEST['dst'];
		$aReg['dsth'] = (int)$_REQUEST['dsth'];
		$aReg['dstm'] = (int)$_REQUEST['dstm'];

		// Validate the information, and submit it to the database if everything's okay.
		$aError = ValidateInfo();
	}
	else
	{
		// Coming for the first time, so let's default the form elements.
		$aReg['website'] =  'http://';
		$aReg['birthmonth'] = 0;
		$aReg['birthdate'] = 0;
		unset($aReg['birthyear']);
		$aReg['allowmail'] = TRUE;
		$aReg['invisible'] = FALSE;
		$aReg['publicemail'] = FALSE;
		$aReg['autologin'] = FALSE;
		$aReg['enablepms'] = TRUE;
		$aReg['pmnotifya'] = TRUE;
		$aReg['pmnotifyb'] = TRUE;
		$aReg['showsigs'] = TRUE;
		$aReg['showavatars'] = TRUE;
		$aReg['threadview'] = 0;
		$aReg['perpage'] = 0;
		$aReg['weekstart'] = 0;
		$aReg['timeoffset'] = $CFG['time']['display_offset'];
		$aReg['dst'] = $CFG['time']['dst'];
		$aReg['dsth'] = floor($CFG['time']['dst_offset'] / 3600);
		$aReg['dstm'] = ($CFG['time']['dst_offset'] - ($aReg['dsth'] * 3600)) / 60;
	}

	// Extract information out of the time offset value.
	list($iOffsetHour, $iOffsetMinute) = sscanf($aReg['timeoffset'], '%d:%u');

	// Header.
	$strPageTitle = ' :: New User Registration';
	require('includes/header.inc.php');

	if(is_array($aError))
	{
		DisplayErrors($aError);
	}
?>

<FORM style="margin: 0px;" name=theform action="register.php" method=post>
<INPUT type=hidden name=posting value=1>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;">
	<B>Required Information</B>
	<DIV class=smaller>Passwords are case-sensitive. Only your username will be public by default.</DIV>
</TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Desired Username</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=username size=25 maxlength=15 value="<?php echo(htmlspecialchars($aReg['username'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Password</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=password name=passworda size=25 maxlength=15 value="<?php echo(htmlspecialchars($aReg['passworda'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Password Again</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=password name=passwordb size=25 maxlength=15 value="<?php echo(htmlspecialchars($aReg['passwordb'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>E-Mail Address</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=emaila size=25 maxlength=128 value="<?php echo(htmlspecialchars($aReg['emaila'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>E-Mail Address Again</B>
		<DIV class=smaller>Enter your e-mail address again for confirmation.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=emailb size=25 maxlength=128 value="<?php echo(htmlspecialchars($aReg['emailb'])); ?>"></TD>
</TR>

<?php
	// Is image verification enabled?
	if($CFG['reg']['verify_img'])
	{
?>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Verification Image</B>
		<DIV class=smaller>Enter the text to verify that this registration is not being performed by an automated process.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<IMG src="regimage.php<?php if(SID){echo('?'.stripslashes(SID));} ?>" alt="Verification Image"><BR>
		<IMG src="images/space.png" width=1 height=3 alt=""><BR>
		<INPUT class=tinput type=text name=verifyimg size=10 maxlength=7 style="width: 210px; text-align: center">
	</TD>
</TR>

<?php
	}
?>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;">
	<B>Optional Information</B>
	<DIV class=smaller>Everything here will be public.</DIV>
</TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Web Site</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=website size=25 maxlength=128 value="<?php if($_REQUEST['posting']){echo(htmlspecialchars($aReg['website']));}else{echo('http://');} ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>AIM Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=aim size=25 maxlength=16 value="<?php echo(htmlspecialchars($aReg['aim'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>ICQ Number</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=icq size=25 maxlength=24 value="<?php echo(htmlspecialchars($aReg['icq'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>MSN Messenger Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=msn size=25 maxlength=128 value="<?php echo(htmlspecialchars($aReg['msn'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Yahoo! Messenger Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=yahoo size=25 maxlength=50 value="<?php echo(htmlspecialchars($aReg['yahoo'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Referrer</B>
		<DIV class=smaller>Enter the username of the <?php echo(htmlspecialchars($CFG['general']['name'])); ?> member who referrered you here (if any).</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=referrer size=25 maxlength=15 value="<?php echo(htmlspecialchars($aReg['referrer'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Birthday</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<TABLE cellpadding=2 cellspacing=0 border=0>
		<TR>
			<TD align=left class=smaller>&nbsp;Month</TD>
			<TD align=left class=smaller>&nbsp;Day</TD>
			<TD align=left class=smaller>&nbsp;Year</TD>
		</TR>
		<TR>
			<TD>
				<SELECT name=birthmonth>
					<OPTION value=0<?php if($aReg['birthmonth']==0){echo(' selected');} ?>></OPTION>
					<OPTION value=1<?php if($aReg['birthmonth']==1){echo(' selected');} ?>>January</OPTION>
					<OPTION value=2<?php if($aReg['birthmonth']==2){echo(' selected');} ?>>February</OPTION>
					<OPTION value=3<?php if($aReg['birthmonth']==3){echo(' selected');} ?>>March</OPTION>
					<OPTION value=4<?php if($aReg['birthmonth']==4){echo(' selected');} ?>>April</OPTION>
					<OPTION value=5<?php if($aReg['birthmonth']==5){echo(' selected');} ?>>May</OPTION>
					<OPTION value=6<?php if($aReg['birthmonth']==6){echo(' selected');} ?>>June</OPTION>
					<OPTION value=7<?php if($aReg['birthmonth']==7){echo(' selected');} ?>>July</OPTION>
					<OPTION value=8<?php if($aReg['birthmonth']==8){echo(' selected');} ?>>August</OPTION>
					<OPTION value=9<?php if($aReg['birthmonth']==9){echo(' selected');} ?>>September</OPTION>
					<OPTION value=10<?php if($aReg['birthmonth']==10){echo(' selected');} ?>>October</OPTION>
					<OPTION value=11<?php if($aReg['birthmonth']==11){echo(' selected');} ?>>November</OPTION>
					<OPTION value=12<?php if($aReg['birthmonth']==12){echo(' selected');} ?>>December</OPTION>
				</SELECT>
			</TD>
			<TD>
				<SELECT name=birthdate>
					<OPTION value=0<?php if($aReg['birthdate']==0){echo(' selected');} ?>></OPTION>
					<OPTION value=1<?php if($aReg['birthdate']==1){echo(' selected');} ?>>1</OPTION>
					<OPTION value=2<?php if($aReg['birthdate']==2){echo(' selected');} ?>>2</OPTION>
					<OPTION value=3<?php if($aReg['birthdate']==3){echo(' selected');} ?>>3</OPTION>
					<OPTION value=4<?php if($aReg['birthdate']==4){echo(' selected');} ?>>4</OPTION>
					<OPTION value=5<?php if($aReg['birthdate']==5){echo(' selected');} ?>>5</OPTION>
					<OPTION value=6<?php if($aReg['birthdate']==6){echo(' selected');} ?>>6</OPTION>
					<OPTION value=7<?php if($aReg['birthdate']==7){echo(' selected');} ?>>7</OPTION>
					<OPTION value=8<?php if($aReg['birthdate']==8){echo(' selected');} ?>>8</OPTION>
					<OPTION value=9<?php if($aReg['birthdate']==9){echo(' selected');} ?>>9</OPTION>
					<OPTION value=10<?php if($aReg['birthdate']==10){echo(' selected');} ?>>10</OPTION>
					<OPTION value=11<?php if($aReg['birthdate']==11){echo(' selected');} ?>>11</OPTION>
					<OPTION value=12<?php if($aReg['birthdate']==12){echo(' selected');} ?>>12</OPTION>
					<OPTION value=13<?php if($aReg['birthdate']==13){echo(' selected');} ?>>13</OPTION>
					<OPTION value=14<?php if($aReg['birthdate']==14){echo(' selected');} ?>>14</OPTION>
					<OPTION value=15<?php if($aReg['birthdate']==15){echo(' selected');} ?>>15</OPTION>
					<OPTION value=16<?php if($aReg['birthdate']==16){echo(' selected');} ?>>16</OPTION>
					<OPTION value=17<?php if($aReg['birthdate']==17){echo(' selected');} ?>>17</OPTION>
					<OPTION value=18<?php if($aReg['birthdate']==18){echo(' selected');} ?>>18</OPTION>
					<OPTION value=19<?php if($aReg['birthdate']==19){echo(' selected');} ?>>19</OPTION>
					<OPTION value=20<?php if($aReg['birthdate']==20){echo(' selected');} ?>>20</OPTION>
					<OPTION value=21<?php if($aReg['birthdate']==21){echo(' selected');} ?>>21</OPTION>
					<OPTION value=22<?php if($aReg['birthdate']==22){echo(' selected');} ?>>22</OPTION>
					<OPTION value=23<?php if($aReg['birthdate']==23){echo(' selected');} ?>>23</OPTION>
					<OPTION value=24<?php if($aReg['birthdate']==24){echo(' selected');} ?>>24</OPTION>
					<OPTION value=25<?php if($aReg['birthdate']==25){echo(' selected');} ?>>25</OPTION>
					<OPTION value=26<?php if($aReg['birthdate']==26){echo(' selected');} ?>>26</OPTION>
					<OPTION value=27<?php if($aReg['birthdate']==27){echo(' selected');} ?>>27</OPTION>
					<OPTION value=28<?php if($aReg['birthdate']==28){echo(' selected');} ?>>28</OPTION>
					<OPTION value=29<?php if($aReg['birthdate']==29){echo(' selected');} ?>>29</OPTION>
					<OPTION value=30<?php if($aReg['birthdate']==30){echo(' selected');} ?>>30</OPTION>
					<OPTION value=31<?php if($aReg['birthdate']==31){echo(' selected');} ?>>31</OPTION>
				</SELECT>
			</TD>
			<TD><INPUT class=tinput type=text name=birthyear size=4 maxlength=4 value="<?php if($aReg['birthyear']){echo($aReg['birthyear']);} ?>"></TD>
		</TR>
		</TABLE>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Biography</B>
		<DIV class=smaller>A few details about yourself</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=bio size=25 maxlength=255 value="<?php echo(htmlspecialchars($aReg['bio'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Location</B>
		<DIV class=smaller>Where are you currently residing?</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=location size=25 maxlength=48 value="<?php echo(htmlspecialchars($aReg['location'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Interests</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=interests size=25 maxlength=255 value="<?php echo(htmlspecialchars($aReg['interests'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Occupation</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=occupation size=25 maxlength=255 value="<?php echo(htmlspecialchars($aReg['occupation'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Signature</B>
		<DIV class=smaller>This will appear at the bottom of each of your posts.<BR><BR><A href="info.php?action=vbcode">vB Code</A> is <B>on</B>.<BR>[img] tags are <B>on</B>.<BR><A href="info.php?action=smilies">Smilies</A> are <B>on</B>.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<TEXTAREA class=medium name=signature rows=5 cols=35><?php echo(htmlspecialchars($aReg['signature'])); ?></TEXTAREA>
		<DIV class=smaller>[<A href="javascript:alert('The maximum permitted length is 255 characters.\n\nYour signature is '+document.theform.signature.value.length+' characters long.');">Check signature length.</A>]</DIV>
	</TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Account Preferences</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Allow administrators and moderators to send you e-mail notices?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=allowmail value=1<?php if($aReg['allowmail']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=allowmail value=0<?php if(!$aReg['allowmail']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Browse the forums in Invisible Mode?</B>
		<DIV class=smaller>If you select Yes, only administrators will be able to tell if you are online or not.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=invisible value=1<?php if($aReg['invisible']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=invisible value=0<?php if(!$aReg['invisible']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Make your e-mail address public?</B>
		<DIV class=smaller>If you select Yes, users will be able to see your e-mail address (in your profile).</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=publicemail value=1<?php if($aReg['publicemail']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=publicemail value=0<?php if(!$aReg['publicemail']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Automatically login when you return?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=autologin value=1<?php if($aReg['autologin']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=autologin value=0<?php if(!$aReg['autologin']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Enable private messages?</B>
		<DIV class=smaller>If you select Yes, you will be able to send and receive private messages to and from other <?php echo(htmlspecialchars($CFG['general']['name'])); ?> members.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=enablepms value=1<?php if($aReg['enablepms']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=enablepms value=0<?php if(!$aReg['enablepms']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Notify you via e-mail when new private messages are received?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=pmnotifya value=1<?php if($aReg['pmnotifya']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=pmnotifya value=0<?php if(!$aReg['pmnotifya']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Pop up a box when new private messages become available?</B>
		<DIV class=smaller>If you select Yes, while browsing the forums a warning box will pop up on your screen when new private messages are available.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=pmnotifyb value=1<?php if($aReg['pmnotifyb']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=pmnotifyb value=0<?php if(!$aReg['pmnotifyb']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Show users' signatures with their posts?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=showsigs value=1<?php if($aReg['showsigs']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=showsigs value=0<?php if(!$aReg['showsigs']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Show users' avatars with their posts?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=showavatars value=1<?php if($aReg['showavatars']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=showavatars value=0<?php if(!$aReg['showavatars']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Default Thread View</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<SELECT name=threadview class=small>
			<OPTION value=0<?php if($aReg['threadview']==0){echo(' selected');} ?>>Use forum default.</OPTION>
			<OPTION value=1<?php if($aReg['threadview']==1){echo(' selected');} ?>>Show threads from the last day.</OPTION>
			<OPTION value=2<?php if($aReg['threadview']==2){echo(' selected');} ?>>Show threads from the last 2 days.</OPTION>
			<OPTION value=3<?php if($aReg['threadview']==3){echo(' selected');} ?>>Show threads from the last 5 days.</OPTION>
			<OPTION value=4<?php if($aReg['threadview']==4){echo(' selected');} ?>>Show threads from the last 10 days.</OPTION>
			<OPTION value=5<?php if($aReg['threadview']==5){echo(' selected');} ?>>Show threads from the last 20 days.</OPTION>
			<OPTION value=6<?php if($aReg['threadview']==6){echo(' selected');} ?>>Show threads from the last 30 days.</OPTION>
			<OPTION value=7<?php if($aReg['threadview']==7){echo(' selected');} ?>>Show threads from the last 45 days.</OPTION>
			<OPTION value=8<?php if($aReg['threadview']==8){echo(' selected');} ?>>Show threads from the last 60 days.</OPTION>
			<OPTION value=9<?php if($aReg['threadview']==9){echo(' selected');} ?>>Show threads from the last 75 days.</OPTION>
			<OPTION value=10<?php if($aReg['threadview']==10){echo(' selected');} ?>>Show threads from the last 100 days.</OPTION>
			<OPTION value=11<?php if($aReg['threadview']==11){echo(' selected');} ?>>Show threads from the last year.</OPTION>
			<OPTION value=12<?php if($aReg['threadview']==12){echo(' selected');} ?>>Show all threads.</OPTION>
		</SELECT>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Default Posts Per Page</B>
		<DIV class=smaller>The number of posts that are shown on each page of thread</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<SELECT name=perpage class=small>
			<OPTION value=0<?php if($aReg['perpage']==0){echo(' selected');} ?>>Use forum default.</OPTION>
			<OPTION value=5<?php if($aReg['perpage']==5){echo(' selected');} ?>>Show 5 posts per page.</OPTION>
			<OPTION value=10<?php if($aReg['perpage']==10){echo(' selected');} ?>>Show 10 posts per page.</OPTION>
			<OPTION value=20<?php if($aReg['perpage']==20){echo(' selected');} ?>>Show 20 posts per page.</OPTION>
			<OPTION value=30<?php if($aReg['perpage']==30){echo(' selected');} ?>>Show 30 posts per page.</OPTION>
			<OPTION value=40<?php if($aReg['perpage']==40){echo(' selected');} ?>>Show 40 posts per page.</OPTION>
		</SELECT>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Start Of The Week</B>
		<DIV class=smaller>Select the day on which weeks start in your culture so that the forum calendar will appear correct for you.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<SELECT name=weekstart class=small>
			<OPTION value=0<?php if($aReg['weekstart']==0){echo(' selected');} ?>>Sunday</OPTION>
			<OPTION value=1<?php if($aReg['weekstart']==1){echo(' selected');} ?>>Monday</OPTION>
			<OPTION value=2<?php if($aReg['weekstart']==2){echo(' selected');} ?>>Tuesday</OPTION>
			<OPTION value=3<?php if($aReg['weekstart']==3){echo(' selected');} ?>>Wednesday</OPTION>
			<OPTION value=4<?php if($aReg['weekstart']==4){echo(' selected');} ?>>Thursday</OPTION>
			<OPTION value=5<?php if($aReg['weekstart']==5){echo(' selected');} ?>>Friday</OPTION>
			<OPTION value=6<?php if($aReg['weekstart']==6){echo(' selected');} ?>>Saturday</OPTION>
		</SELECT>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Time Offset</B>
		<DIV class=smaller>The time is now <?php echo(gmdate('g:ia', $CFG['globaltime'])); ?> GMT. Select the appropriate offset so the displayed times will be correct for you.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<SELECT name=timeoffset class=small>
			<OPTION value="-43200"<?php if($aReg['timeoffset'] == -43200){echo(' selected');} ?>>[GMT -12:00] International Date Line West</OPTION>
			<OPTION value="-39600"<?php if($aReg['timeoffset'] == -39600){echo(' selected');} ?>>[GMT -11:00] Midway Islands, Samoa</OPTION>
			<OPTION value="-36000"<?php if($aReg['timeoffset'] == -36000){echo(' selected');} ?>>[GMT -10:00] Hawaii-Aleutian Time</OPTION>
			<OPTION value="-32400"<?php if($aReg['timeoffset'] == -32400){echo(' selected');} ?>>[GMT -09:00] Alaska Time</OPTION>
			<OPTION value="-28800"<?php if($aReg['timeoffset'] == -28800){echo(' selected');} ?>>[GMT -08:00] Pacific Time (US &amp; Canada)</OPTION>
			<OPTION value="-25200"<?php if($aReg['timeoffset'] == -25200){echo(' selected');} ?>>[GMT -07:00] Mountain Time (US &amp; Canada)</OPTION>
			<OPTION value="-21600"<?php if($aReg['timeoffset'] == -21600){echo(' selected');} ?>>[GMT -06:00] Central Time (US &amp; Canada)</OPTION>
			<OPTION value="-18000"<?php if($aReg['timeoffset'] == -18000){echo(' selected');} ?>>[GMT -05:00] Eastern Time (US &amp; Canada)</OPTION>
			<OPTION value="-14400"<?php if($aReg['timeoffset'] == -14400){echo(' selected');} ?>>[GMT -04:00] Atlantic Time (Canada)</OPTION>
			<OPTION value="-12600"<?php if($aReg['timeoffset'] == -12600){echo(' selected');} ?>>[GMT -03:30] Newfoundland Time</OPTION>
			<OPTION value="-10800"<?php if($aReg['timeoffset'] == -10800){echo(' selected');} ?>>[GMT -03:00] Brasilia, Buenos Aires, Greenland</OPTION>
			<OPTION value="-7200"<?php if($aReg['timeoffset'] == -7200){echo(' selected');} ?>>[GMT -02:00] Mid-Atlantic Time</OPTION>
			<OPTION value="-3600"<?php if($aReg['timeoffset'] == -3600){echo(' selected');} ?>>[GMT -01:00] Azores, Cape Verde Island</OPTION>
			<OPTION value="0"<?php if($aReg['timeoffset'] == 0){echo(' selected');} ?>>[GMT +00:00] Western Europe Time</OPTION>
			<OPTION value="3600"<?php if($aReg['timeoffset'] == 3600){echo(' selected');} ?>>[GMT +01:00] Central Europe Time</OPTION>
			<OPTION value="7200"<?php if($aReg['timeoffset'] == 7200){echo(' selected');} ?>>[GMT +02:00] Eastern Europe Time</OPTION>
			<OPTION value="10800"<?php if($aReg['timeoffset'] == 10800){echo(' selected');} ?>>[GMT +03:00] Eastern Africa Time</OPTION>
			<OPTION value="12600"<?php if($aReg['timeoffset'] == 12600){echo(' selected');} ?>>[GMT +03:30] Middle East Time</OPTION>
			<OPTION value="14400"<?php if($aReg['timeoffset'] == 14400){echo(' selected');} ?>>[GMT +04:00] Near East Time</OPTION>
			<OPTION value="16200"<?php if($aReg['timeoffset'] == 16200){echo(' selected');} ?>>[GMT +04:30] Kabul Time</OPTION>
			<OPTION value="18000"<?php if($aReg['timeoffset'] == 18000){echo(' selected');} ?>>[GMT +05:00] Pakistan-Lahore Time</OPTION>
			<OPTION value="19800"<?php if($aReg['timeoffset'] == 19800){echo(' selected');} ?>>[GMT +05:30] India Time</OPTION>
			<OPTION value="20700"<?php if($aReg['timeoffset'] == 20700){echo(' selected');} ?>>[GMT +05:45] Kathmandu Time</OPTION>
			<OPTION value="21600"<?php if($aReg['timeoffset'] == 21600){echo(' selected');} ?>>[GMT +06:00] Bangladesh Time</OPTION>
			<OPTION value="25200"<?php if($aReg['timeoffset'] == 25200){echo(' selected');} ?>>[GMT +07:00] Christmas Island Time</OPTION>
			<OPTION value="28800"<?php if($aReg['timeoffset'] == 28800){echo(' selected');} ?>>[GMT +08:00] China-Taiwan Time</OPTION>
			<OPTION value="32400"<?php if($aReg['timeoffset'] == 32400){echo(' selected');} ?>>[GMT +09:00] Japan Time</OPTION>
			<OPTION value="34200"<?php if($aReg['timeoffset'] == 34200){echo(' selected');} ?>>[GMT +09:30] Australia Central Time</OPTION>
			<OPTION value="36000"<?php if($aReg['timeoffset'] == 36000){echo(' selected');} ?>>[GMT +10:00] Australia Eastern Time</OPTION>
			<OPTION value="39600"<?php if($aReg['timeoffset'] == 39600){echo(' selected');} ?>>[GMT +11:00] Soloman Time</OPTION>
			<OPTION value="43200"<?php if($aReg['timeoffset'] == 43200){echo(' selected');} ?>>[GMT +12:00] New Zealand Time</OPTION>
		</SELECT>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Are you currently observing Daylight Saving Time/Summer Time?</B>
		<DIV class=smaller>Adjust this setting if the forum times appear off, despite the Time Offset being correct.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<INPUT type=radio name=dst value=0<?php if(!$aReg['dst']){echo(' checked');} ?>>No. &nbsp; <INPUT type=radio name=dst value=1<?php if($aReg['dst']){echo(' checked');} ?>>Yes. Adjustment is <INPUT class=tinput style="font-size: 11px; text-align: right;" type=text name=dsth size=1 maxlength=2 value="<?php echo($aReg['dsth']); ?>">:<INPUT class=tinput style="font-size: 11px;" type=text name=dstm size=2 maxlength=2 value="<?php printf('%02u', $aReg['dstm']); ?>"> hours.
	</TD>
</TR>

</TABLE>

<CENTER><BR><INPUT class=tinput type=submit name=submit value="Submit"></CENTER>
</FORM>

<?php
	// Footer.
	require('includes/footer.inc.php');

	// Finish.
	exit;
?>

<?php
function Success($iUserID)
{
	global $CFG;

	// Get the information about the user that was just created.
	$aSQLResult = mysql_fetch_array(sqlquery("SELECT * FROM member WHERE id=$iUserID"), MYSQL_ASSOC);

	// We're logged in by default.
	$_SESSION['loggedin'] = TRUE;

	// Store the member information into the session.
	$_SESSION['userid'] = $aSQLResult['id'];
	$_SESSION['username'] = $aSQLResult['username'];
	$_SESSION['password'] = $aSQLResult['password'];
	$_SESSION['autologin'] = $aSQLResult['autologin'];
	$_SESSION['showsigs'] = $aSQLResult['showsigs'];
	$_SESSION['showavatars'] = $aSQLResult['showavatars'];
	$_SESSION['threadview'] = $aSQLResult['threadview'];
	$_SESSION['postsperpage'] = $aSQLResult['postsperpage'] ? $aSQLResult['postsperpage'] : $CFG['default']['postsperpage'];
	$_SESSION['threadsperpage'] = $aSQLResult['threadsperpage'] ? $aSQLResult['threadsperpage'] : $CFG['default']['threadsperpage'];
	$_SESSION['weekstart'] = $aSQLResult['weekstart'];
	$_SESSION['timeoffset'] = $aSQLResult['timeoffset'];
	$_SESSION['dst'] = (bool)$aSQLResult['dst'];
	$_SESSION['dstoffset'] = (int)$aSQLResult['dstoffset'];
	$_SESSION['lastactive'] = $aSQLResult['lastactive'];
	$_SESSION['usergroup'] = $aSQLResult['usergroup'];

	// Delete any guest entries from the session table.
	sqlquery('DELETE FROM session WHERE id=\''.session_id().'\'');

	// Render the page.
	Msg("<b>Thank you for registering.</b><br><br><font class=\"smaller\">You should be redirected to the forum index momentarily. Click <a href=\"index.php\">here</a><br>if you do not want to wait any longer or if you are not redirected.</font>", 'index.php', 'center');
}

function ValidateInfo()
{
	global $CFG, $aReg;

	// Username
	if($aReg['username'] == '')
	{
		// They didn't specify a username.
		$aError[] = 'You must specify a desired username.';
	}
	else if(strlen($aReg['username']) > 16)
	{
		// The username they specified is too long.
		$aError[] = 'The username you specified is longer than 16 characters.';
	}
	else if(trim($aReg['username']) != $aReg['username'])
	{
		// Their username contains whitespace at the beginning and/or end.
		$aError[] = 'Usernames must not begin or end with whitespace.';
	}
	$strUsername = mysql_real_escape_string($aReg['username']);

	// Password
	if($aReg['passworda'] != $aReg['passwordb'])
	{
		// The two passwords they specified are not the same.
		$aError[] = 'The passwords you specified do not match.';
	}
	else if($aReg['passworda'] == '')
	{
		// They didn't specify a password.
		$aError[] = 'You must specify a password.';
	}
	else if(strlen($aReg['passworda']) > 16)
	{
		// The password they specified is too long.
		$aError[] = 'The password you specified is longer than 16 characters.';
	}
	$strPassword = md5($aReg['passworda']);

	// E-Mail Address
	if($aReg['emaila'] != $aReg['emailb'])
	{
		// The two e-mail addresses they specified are not the same.
		$aError[] = 'The e-mail addresses you specified do not match.';
	}
	else if($aReg['emaila'] == '')
	{
		// They didn't specify an e-mail address.
		$aError[] = 'You must specify an e-mail address.';
	}
	else if(strlen($aReg['emaila']) > 128)
	{
		// The e-mail address they specified is too long.
		$aError[] = 'The e-mail address you specified is longer than 128 characters.';
	}
	else if(!preg_match("/^(([^<>()[\]\\\\.,;:\s@\"]+(\.[^<>()[\]\\\\.,;:\s@\"]+)*)|(\"([^\"\\\\\r]|(\\\\[\w\W]))*\"))@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([a-z\-0-9]+\.)+[a-z]{2,}))$/i", $aReg['emaila']))
	{
		// The "e-mail address" they specified does not match the format of a typical e-mail address.
		$aError[] = 'The e-mail address you specified is not a valid address.';
	}
	$strEMail = mysql_real_escape_string($aReg['emaila']);

	// Image verification.
	if(($CFG['reg']['verify_img'] == TRUE) && ($aReg['verifyimg'] != $_SESSION['randstr']))
	{

		$aError[] = 'The value you entered for the image verification is incorrect.';
		unset($_SESSION['randstr']);
	}

	// Web Site
	$aURL = @parse_url($aReg['website']);
	if(($aReg['website'] == 'http://') || ($aReg['website'] == ''))
	{
		// Either they specified nothing, or they left it at the default "http://".
		$aReg['website'] = '';
	}
	else if(!$aURL['scheme'])
	{
		// Default to HTTP.
		$aReg['website'] = "http://{$aReg['website']}";
	}
	if(strlen($aReg['website']) > 128)
	{
		// The Web site they specified is too long.
		$aError[] = 'The Web site you specified is longer than 128 characters.';
	}
	else
	{
		$strWebsite = mysql_real_escape_string($aReg['website']);
	}

	// AIM
	if(strlen($aReg['aim']) > 16)
	{
		// The AIM handle they specified is too long.
		$aError[] = 'The AIM handle you specified is longer than 16 characters.';
	}
	$strAIM = mysql_real_escape_string($aReg['aim']);

	// ICQ
	if(strlen($aReg['icq']) > 24)
	{
		// The ICQ number they specified is too long.
		$aError[] = 'The ICQ number you specified is longer than 24 characters.';
	}
	$strICQ = mysql_real_escape_string($aReg['icq']);

	// MSN
	if(strlen($aReg['msn']) > 128)
	{
		// The MSN Messenger handle they specified is too long.
		$aError[] = 'The MSN Messenger handle you specified is longer than 128 characters.';
	}
	$strMSN = mysql_real_escape_string($aReg['msn']);

	// Yahoo!
	if(strlen($aReg['yahoo']) > 50)
	{
		// The Yahoo! handle they specified is too long.
		$aError[] = 'The Yahoo! handle you specified is longer than 50 characters.';
	}
	$strYahoo = mysql_real_escape_string($aReg['yahoo']);

	// Referrer
	if(strlen($aReg['referrer']) > 16)
	{
		// The referrer they specified is too long.
		$aError[] = 'The referrer\'s username you specified is longer than 16 characters.';
	}
	$strReferrer = mysql_real_escape_string($aReg['referrer']);

	// Birthday
	if(($aReg['birthmonth'] < 0) || ($aReg['birthmonth'] > 12))
	{
		// The birthmonth they specified is invalid.
		$aError[] = 'The birthmonth you specified is not a valid month.';
	}
	else if(($aReg['birthmonth']) && ($aReg['birthdate'] == 0) && ($aReg['birthyear'] == ''))
	{
		// They specified a month but no date or year.
		$aError[] = 'If you specify a birthmonth, you must also specify your birthdate and/or birthyear.';
	}
	if(($aReg['birthdate'] < 0) || ($aReg['birthdate'] > 31))
	{
		// The birthdate they specified is invalid.
		$aError[] = 'The birthdate you specified is not a valid date.';
	}
	else if(($aReg['birthdate']) && ($aReg['birthmonth'] == 0))
	{
		// They specified a date but no month.
		$aError[] = 'If you specify a birthdate, you must also specify a birthmonth.';
	}
	if(($aReg['birthyear'] != '') && (($aReg['birthyear'] < 1900) || ($aReg['birthyear'] > date('Y'))))
	{
		// The birthyear they specified is invalid.
		$aError[] = 'The birthyear you specified is not a valid year.';
	}
	if($aReg['birthyear'] == '')
	{
		$aReg['birthyear'] = 0;
	}
	$strBirthday = sprintf('%04u-%02u-%02u', $aReg['birthyear'], $aReg['birthmonth'], $aReg['birthdate']);

	// Biography
	if(strlen($aReg['bio']) > 255)
	{
		// The biography they specified is too long.
		$aError[] = 'The biography you specified is longer than 255 characters.';
	}
	$strBio = mysql_real_escape_string($aReg['bio']);

	// Location
	if(strlen($aReg['location']) > 48)
	{
		// The location they specified is too long.
		$aError[] = 'The location you specified is longer than 48 characters.';
	}
	$strLocation = mysql_real_escape_string($aReg['location']);

	// Interests
	if(strlen($aReg['interests']) > 255)
	{
		// The interests they specified is too long.
		$aError[] = 'The value you specified for interests is longer than 255 characters.';
	}
	$strInterests = mysql_real_escape_string($aReg['interests']);

	// Occupation
	if(strlen($aReg['occupation']) > 255)
	{
		// The occupation they specified is too long.
		$aError[] = 'The occupation you specified is longer than 255 characters.';
	}
	$strOccupation = mysql_real_escape_string($aReg['occupation']);

	// Signature
	if(strlen($aReg['signature']) > 255)
	{
		// The signature they specified is too long.
		$aError[] = 'The signature you specified is longer than 255 characters.';
	}
	$strSignature = mysql_real_escape_string($aReg['signature']);

	// Default Thread View
	if($aReg['threadview'] > 12)
	{
		// They specified an invalid choice for the default thread view.
		$iThreadView = 0;
	}
	else
	{
		$iThreadView = $aReg['threadview'];
	}

	// Default Posts Per Page
	if($aReg['perpage'] < 0)
	{
		// They specified an invalid choice for the default posts per day.
		$iPerPage = 0;
	}
	else
	{
		$iPerPage = $aReg['perpage'];
	}

	// Start Of The Week
	if($aReg['weekstart'] > 6)
	{
		// They specified an invalid day for the start of the week.
		$iWeekStart = 0;
	}
	else
	{
		$iWeekStart = $aReg['weekstart'];
	}

	// Time Offset
	if(($aReg['timeoffset'] > 43200) || ($aReg['timeoffset'] < -43200))
	{
		// They specified an invalid time for the time offset.
		$strTimeOffset = $CFG['time']['display_offset'];
	}
	else
	{
		$strTimeOffset = $aReg['timeoffset'];
	}

	// DST Offset
	$iDSTOffset = ($aReg['dsth'] * 3600) + ($aReg['dstm'] * 60);
	if(($iDSTOffset > 65535) || ($iDSTOffset < 0))
	{
		$iDSTOffset = 0;
	}

	// Do they have any errors?
	if(is_array($aError))
	{
		return $aError;
	}

	// Include database variables.
	require('includes/db.inc.php');

	// Is there already a user with the desired username?
	$sqlResult = sqlquery("SELECT id FROM member WHERE username LIKE '$strUsername'");
	if(mysql_fetch_array($sqlResult))
	{
		// Yep, a user already exists. Let them know the bad news.
		$aError[] = 'There is already a user with that username. Please specify a different one.';
	}

	// Is there already a user with the specified e-mail address?
	$sqlResult = sqlquery("SELECT id FROM member WHERE email LIKE '$strEMail'");
	if(mysql_fetch_array($sqlResult))
	{
		// Yep, e-mail address is already in use. Let them know the bad news.
		$aError[] = 'There is already a user with that e-mail address. Please specify a different one.';
	}

	// Do they have any errors?
	if(is_array($aError))
	{
		return $aError;
	}

	// Register the user!
	$dJoined = gmdate('Y-m-d');
	sqlquery("INSERT INTO member(username, password, email, datejoined, website, aim, icq, msn, yahoo, referrer, birthday, bio, location, interests, occupation, signature, allowmail, invisible, publicemail, enablepms, pmnotifya, pmnotifyb, threadview, postsperpage, weekstart, timeoffset, dst, dstoffset, postcount, showsigs, showavatars, autologin, usergroup) VALUES('$strUsername', '$strPassword', '$strEMail', '$dJoined', '$strWebsite', '$strAIM', '$strICQ', '$strMSN', '$strYahoo', '$strReferrer', '$strBirthday', '$strBio', '$strLocation', '$strInterests', '$strOccupation', '$strSignature', {$aReg['allowmail']}, {$aReg['invisible']}, {$aReg['publicemail']}, {$aReg['enablepms']}, {$aReg['pmnotifya']}, {$aReg['pmnotifyb']}, $iThreadView, $iPerPage, $iWeekStart, $strTimeOffset, {$aReg['dst']}, $iDSTOffset, 0, {$aReg['showsigs']}, {$aReg['showavatars']}, {$aReg['autologin']}, 1)");

	// Show them the success page.
	Success(mysql_insert_id());
}

function AlreadyRegistered()
{
	global $CFG;

	// Get the information of each forum, for our Forum Jump later.
	$sqlResult = sqlquery("SELECT id, type, parent, disporder, name FROM board WHERE type IN (0, 1) ORDER BY disporder ASC");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Is this a 'Type 0' or a 'Type 1' forum?
		switch($aSQLResult['type'])
		{
			// Type 0: Category
			case 0:
			{
				// Store the category information into the Category array.
				$iCategoryID = $aSQLResult['id'];
				$aCategory[$iCategoryID] = htmlspecialchars($aSQLResult['name']);
				break;
			}

			// Type 1: Forum
			case 1:
			{
				// Store the forum information into the Forum array.
				$iForumID = $aSQLResult['id'];
				$aForum[$iForumID][0] = $aSQLResult['parent'];
				$aForum[$iForumID][1] = htmlspecialchars($aSQLResult['name']);
				break;
			}
		}
	}

	// Header.
	require('includes/header.inc.php');
?>

<BR><BR><BR>
<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="70%" cellspacing=1 cellpadding=4 border=0 align=center>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>"><FONT class=medium color="<?php echo($CFG['style']['table']['heading']['txtcolor']); ?>"><B>OvBB Message</B></TD></TR>
	<TR><TD class=medium bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" style="text-align: justify;">Our records show that you have already registered at this forum under the name of <CODE><?php echo(htmlspecialchars($_SESSION['username'])); ?></CODE>. If you have lost your password, click <A href="forgotdetails.php">here</A>. If you would like to modify your profile, click <A href="usercp.php?section=profile">here</A>.</TD></TR>
</TABLE>
<BR>

<TABLE width="100%" cellspacing=0 cellpadding=0 border=0 align=center>
<TR>
	<TD width="50%"></TD>
	<TD align=left class=smaller nowrap>
		<B>Forum jump</B>:<BR>
		<SELECT onchange="window.location=(this.options[this.selectedIndex].value);">
			<OPTION>Please select one:</OPTION>
<?php
	// Print out all of the forums.
	reset($aCategory);
	while(list($iCategoryID) = each($aCategory))
	{
		// Print the category.
		echo("			<OPTION value=\"forumdisplay.php?forumid=$iCategoryID\">{$aCategory[$iCategoryID]}</OPTION>\n");

		// Print the forums under this category.
		reset($aForum);
		while(list($iForumID) = each($aForum))
		{
			// Only process this forum if it's under the current category.
			if($aForum[$iForumID][0] == $iCategoryID)
			{
				// Print the forum.
				echo("			<OPTION value=\"forumdisplay.php?forumid=$iForumID\">-- {$aForum[$iForumID][1]}</OPTION>\n");
			}
		}
	}
?>
		</SELECT>
	</TD>
	<TD width="50%"></TD>
</TR>
</TABLE><BR><BR><BR>

<?php
	// Footer.
	require('includes/footer.inc.php');
	exit;
}
?>