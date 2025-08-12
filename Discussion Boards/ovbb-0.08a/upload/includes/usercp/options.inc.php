<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; Edit Options</B></TD>
</TR>
</TABLE><BR>

<?php
	// User CP menu.
	PrintCPMenu();
?>

<BR>

<FORM style="margin: 0px;" name=theform action="usercp.php?section=options" method=post>
<INPUT type=hidden name=posting value=1>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 align=center class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Edit Options</B></TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Login &amp; Privacy</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Browse the forums in Invisible Mode?</B>
		<DIV class=smaller>If you select Yes, only administrators will be able to tell if you are online or not.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=invisible value=1<?php if($aUserInfo['invisible']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=invisible value=0<?php if(!$aUserInfo['invisible']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Automatically login when you return?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=autologin value=1<?php if($aUserInfo['autologin']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=autologin value=0<?php if(!$aUserInfo['autologin']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Make your e-mail address public?</B>
		<DIV class=smaller>If you select Yes, users will be able to see your e-mail address (in your profile).</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=publicemail value=1<?php if($aUserInfo['publicemail']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=publicemail value=0<?php if(!$aUserInfo['publicemail']){echo(' checked');} ?>>No.</TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Messaging &amp; Notification</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Allow administrators and moderators to send you e-mail notices?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=allowmail value=1<?php if($aUserInfo['allowmail']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=allowmail value=0<?php if(!$aUserInfo['allowmail']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Enable private messages?</B>
		<DIV class=smaller>If you select Yes, you will be able to send and receive private messages to and from other <?php echo(htmlspecialchars($CFG['general']['name'])); ?> members.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=enablepms value=1<?php if($aUserInfo['enablepms']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=enablepms value=0<?php if(!$aUserInfo['enablepms']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Notify you via e-mail when new private messages are received?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=pmnotifya value=1<?php if($aUserInfo['pmnotifya']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=pmnotifya value=0<?php if(!$aUserInfo['pmnotifya']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Pop up a box when new private messages become available?</B>
		<DIV class=smaller>If you select Yes, while browsing the forums a warning box will pop up on your screen when new private messages are available.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=pmnotifyb value=1<?php if($aUserInfo['pmnotifyb']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=pmnotifyb value=0<?php if(!$aUserInfo['pmnotifyb']){echo(' checked');} ?>>No.</TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Thread View Options</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Show users' signatures with their posts?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><INPUT type=radio name=showsigs value=1<?php if($aUserInfo['showsigs']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=showsigs value=0<?php if(!$aUserInfo['showsigs']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Show users' avatars with their posts?</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><INPUT type=radio name=showavatars value=1<?php if($aUserInfo['showavatars']){echo(' checked');} ?>>Yes. &nbsp; <INPUT type=radio name=showavatars value=0<?php if(!$aUserInfo['showavatars']){echo(' checked');} ?>>No.</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Default Thread View</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<SELECT name=threadview class=small>
			<OPTION value=0<?php if($aUserInfo['threadview']==0){echo(' selected');} ?>>Use forum default.</OPTION>
			<OPTION value=1<?php if($aUserInfo['threadview']==1){echo(' selected');} ?>>Show threads from the last day.</OPTION>
			<OPTION value=2<?php if($aUserInfo['threadview']==2){echo(' selected');} ?>>Show threads from the last 2 days.</OPTION>
			<OPTION value=3<?php if($aUserInfo['threadview']==3){echo(' selected');} ?>>Show threads from the last 5 days.</OPTION>
			<OPTION value=4<?php if($aUserInfo['threadview']==4){echo(' selected');} ?>>Show threads from the last 10 days.</OPTION>
			<OPTION value=5<?php if($aUserInfo['threadview']==5){echo(' selected');} ?>>Show threads from the last 20 days.</OPTION>
			<OPTION value=6<?php if($aUserInfo['threadview']==6){echo(' selected');} ?>>Show threads from the last 30 days.</OPTION>
			<OPTION value=7<?php if($aUserInfo['threadview']==7){echo(' selected');} ?>>Show threads from the last 45 days.</OPTION>
			<OPTION value=8<?php if($aUserInfo['threadview']==8){echo(' selected');} ?>>Show threads from the last 60 days.</OPTION>
			<OPTION value=9<?php if($aUserInfo['threadview']==9){echo(' selected');} ?>>Show threads from the last 75 days.</OPTION>
			<OPTION value=10<?php if($aUserInfo['threadview']==10){echo(' selected');} ?>>Show threads from the last 100 days.</OPTION>
			<OPTION value=11<?php if($aUserInfo['threadview']==11){echo(' selected');} ?>>Show threads from the last year.</OPTION>
			<OPTION value=12<?php if($aUserInfo['threadview']==12){echo(' selected');} ?>>Show all threads.</OPTION>
		</SELECT>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Default Posts Per Page</B>
		<DIV class=smaller>The number of posts that are shown on each page of a thread</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<SELECT name=perpage class=small>
			<OPTION value=0<?php if($aUserInfo['postsperpage']==0){echo(' selected');} ?>>Use forum default.</OPTION>
			<OPTION value=5<?php if($aUserInfo['postsperpage']==5){echo(' selected');} ?>>Show 5 posts per page.</OPTION>
			<OPTION value=10<?php if($aUserInfo['postsperpage']==10){echo(' selected');} ?>>Show 10 posts per page.</OPTION>
			<OPTION value=20<?php if($aUserInfo['postsperpage']==20){echo(' selected');} ?>>Show 20 posts per page.</OPTION>
			<OPTION value=30<?php if($aUserInfo['postsperpage']==30){echo(' selected');} ?>>Show 30 posts per page.</OPTION>
			<OPTION value=40<?php if($aUserInfo['postsperpage']==40){echo(' selected');} ?>>Show 40 posts per page.</OPTION>
		</SELECT>
	</TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Date &amp; Time Options</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Start Of The Week</B>
		<DIV class=smaller>Select the day on which weeks start in your culture so that the forum calendar will appear correct for you.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<SELECT name=weekstart class=small>
			<OPTION value=0<?php if($aUserInfo['weekstart']==0){echo(' selected');} ?>>Sunday</OPTION>
			<OPTION value=1<?php if($aUserInfo['weekstart']==1){echo(' selected');} ?>>Monday</OPTION>
			<OPTION value=2<?php if($aUserInfo['weekstart']==2){echo(' selected');} ?>>Tuesday</OPTION>
			<OPTION value=3<?php if($aUserInfo['weekstart']==3){echo(' selected');} ?>>Wednesday</OPTION>
			<OPTION value=4<?php if($aUserInfo['weekstart']==4){echo(' selected');} ?>>Thursday</OPTION>
			<OPTION value=5<?php if($aUserInfo['weekstart']==5){echo(' selected');} ?>>Friday</OPTION>
			<OPTION value=6<?php if($aUserInfo['weekstart']==6){echo(' selected');} ?>>Saturday</OPTION>
		</SELECT>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Time Offset</B>
		<DIV class=smaller>The time is now <?php echo(gmdate('g:ia')); ?> GMT. Select the appropriate offset so the displayed times will be correct for you.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<SELECT name=timeoffset class=small>
			<OPTION value="-43200"<?php if($aUserInfo['timeoffset'] == -43200){echo(' selected');} ?>>[GMT -12:00] International Date Line West</OPTION>
			<OPTION value="-39600"<?php if($aUserInfo['timeoffset'] == -39600){echo(' selected');} ?>>[GMT -11:00] Midway Islands, Samoa</OPTION>
			<OPTION value="-36000"<?php if($aUserInfo['timeoffset'] == -36000){echo(' selected');} ?>>[GMT -10:00] Hawaii-Aleutian Time</OPTION>
			<OPTION value="-32400"<?php if($aUserInfo['timeoffset'] == -32400){echo(' selected');} ?>>[GMT -09:00] Alaska Time</OPTION>
			<OPTION value="-28800"<?php if($aUserInfo['timeoffset'] == -28800){echo(' selected');} ?>>[GMT -08:00] Pacific Time (US &amp; Canada)</OPTION>
			<OPTION value="-25200"<?php if($aUserInfo['timeoffset'] == -25200){echo(' selected');} ?>>[GMT -07:00] Mountain Time (US &amp; Canada)</OPTION>
			<OPTION value="-21600"<?php if($aUserInfo['timeoffset'] == -21600){echo(' selected');} ?>>[GMT -06:00] Central Time (US &amp; Canada)</OPTION>
			<OPTION value="-18000"<?php if($aUserInfo['timeoffset'] == -18000){echo(' selected');} ?>>[GMT -05:00] Eastern Time (US &amp; Canada)</OPTION>
			<OPTION value="-14400"<?php if($aUserInfo['timeoffset'] == -14400){echo(' selected');} ?>>[GMT -04:00] Atlantic Time (Canada)</OPTION>
			<OPTION value="-12600"<?php if($aUserInfo['timeoffset'] == -12600){echo(' selected');} ?>>[GMT -03:30] Newfoundland Time</OPTION>
			<OPTION value="-10800"<?php if($aUserInfo['timeoffset'] == -10800){echo(' selected');} ?>>[GMT -03:00] Brasilia, Buenos Aires, Greenland</OPTION>
			<OPTION value="-7200"<?php if($aUserInfo['timeoffset'] == -7200){echo(' selected');} ?>>[GMT -02:00] Mid-Atlantic Time</OPTION>
			<OPTION value="-3600"<?php if($aUserInfo['timeoffset'] == -3600){echo(' selected');} ?>>[GMT -01:00] Azores, Cape Verde Island</OPTION>
			<OPTION value="0"<?php if($aUserInfo['timeoffset'] == 0){echo(' selected');} ?>>[GMT +00:00] Western Europe Time</OPTION>
			<OPTION value="3600"<?php if($aUserInfo['timeoffset'] == 3600){echo(' selected');} ?>>[GMT +01:00] Central Europe Time</OPTION>
			<OPTION value="7200"<?php if($aUserInfo['timeoffset'] == 7200){echo(' selected');} ?>>[GMT +02:00] Eastern Europe Time</OPTION>
			<OPTION value="10800"<?php if($aUserInfo['timeoffset'] == 10800){echo(' selected');} ?>>[GMT +03:00] Eastern Africa Time</OPTION>
			<OPTION value="12600"<?php if($aUserInfo['timeoffset'] == 12600){echo(' selected');} ?>>[GMT +03:30] Middle East Time</OPTION>
			<OPTION value="14400"<?php if($aUserInfo['timeoffset'] == 14400){echo(' selected');} ?>>[GMT +04:00] Near East Time</OPTION>
			<OPTION value="16200"<?php if($aUserInfo['timeoffset'] == 16200){echo(' selected');} ?>>[GMT +04:30] Kabul Time</OPTION>
			<OPTION value="18000"<?php if($aUserInfo['timeoffset'] == 18000){echo(' selected');} ?>>[GMT +05:00] Pakistan-Lahore Time</OPTION>
			<OPTION value="19800"<?php if($aUserInfo['timeoffset'] == 19800){echo(' selected');} ?>>[GMT +05:30] India Time</OPTION>
			<OPTION value="20700"<?php if($aUserInfo['timeoffset'] == 20700){echo(' selected');} ?>>[GMT +05:45] Kathmandu Time</OPTION>
			<OPTION value="21600"<?php if($aUserInfo['timeoffset'] == 21600){echo(' selected');} ?>>[GMT +06:00] Bangladesh Time</OPTION>
			<OPTION value="25200"<?php if($aUserInfo['timeoffset'] == 25200){echo(' selected');} ?>>[GMT +07:00] Christmas Island Time</OPTION>
			<OPTION value="28800"<?php if($aUserInfo['timeoffset'] == 28800){echo(' selected');} ?>>[GMT +08:00] China-Taiwan Time</OPTION>
			<OPTION value="32400"<?php if($aUserInfo['timeoffset'] == 32400){echo(' selected');} ?>>[GMT +09:00] Japan Time</OPTION>
			<OPTION value="34200"<?php if($aUserInfo['timeoffset'] == 34200){echo(' selected');} ?>>[GMT +09:30] Australia Central Time</OPTION>
			<OPTION value="36000"<?php if($aUserInfo['timeoffset'] == 36000){echo(' selected');} ?>>[GMT +10:00] Australia Eastern Time</OPTION>
			<OPTION value="39600"<?php if($aUserInfo['timeoffset'] == 39600){echo(' selected');} ?>>[GMT +11:00] Soloman Time</OPTION>
			<OPTION value="43200"<?php if($aUserInfo['timeoffset'] == 43200){echo(' selected');} ?>>[GMT +12:00] New Zealand Time</OPTION>
		</SELECT>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Are you currently observing Daylight Saving Time/Summer Time?</B>
		<DIV class=smaller>Adjust this setting if the forum times appear off, despite the Time Offset being correct.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<INPUT type=radio name=dst value=0<?php if(!$aUserInfo['dst']){echo(' checked');} ?>>No. &nbsp; <INPUT type=radio name=dst value=1<?php if($aUserInfo['dst']){echo(' checked');} ?>>Yes. Adjustment is <INPUT class=tinput style="font-size: 11px; text-align: right;" type=text name=dsth size=1 maxlength=2 value="<?php echo($aUserInfo['dsth']); ?>">:<INPUT class=tinput style="font-size: 11px;" type=text name=dstm size=2 maxlength=2 value="<?php printf('%02u', $aUserInfo['dstm']); ?>"> hours.
	</TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Other Options</B></TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Avatar</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=small><IMG src="avatar.php?userid=<?php echo($_SESSION['userid']); ?>" align=middle alt=""> <INPUT class=tinput type=submit name=editavatar value="Change Avatar"></TD>
</TR>

</TABLE>

<CENTER><BR><INPUT class=tinput type=submit name=submit value="Save Changes" accesskey="s"></CENTER>
</FORM>