<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; Edit Profile</B></TD>
</TR>
</TABLE><BR>

<?php
	// User CP menu.
	PrintCPMenu();
?>

<BR>

<?php
	if($aError)
	{
		DisplayErrors($aError);
	}
?>

<FORM style="margin: 0px;" name=theform action="usercp.php?section=profile" method=post>
<INPUT type=hidden name=posting value=1>

<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" colspan=2 align=center class=medium style="color: <?php echo($CFG['style']['table']['section']['txtcolor']); ?>;"><B>Edit Profile</B></TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;">
	<B>Required Information</B>
	<DIV class=smaller>All fields are required.</DIV>
</TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>E-Mail Address</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=emaila size=25 maxlength=128 value="<?php echo(htmlspecialchars($aUserInfo['emaila'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>E-Mail Address Again</B>
		<DIV class=smaller>Enter your e-mail address again for confirmation.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=emailb size=25 maxlength=128 value="<?php echo(htmlspecialchars($aUserInfo['emailb'])); ?>"></TD>
</TR>

<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 align=left class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;">
	<B>Optional Information</B>
	<DIV class=smaller>Everything here will be public.</DIV>
</TD></TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Web Site</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=website size=25 maxlength=128 value="<?php echo(htmlspecialchars($aUserInfo['website'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>AIM Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=aim size=25 maxlength=16 value="<?php echo(htmlspecialchars($aUserInfo['aim'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>ICQ Number</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=icq size=25 maxlength=24 value="<?php echo(htmlspecialchars($aUserInfo['icq'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>MSN Messenger Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=msn size=25 maxlength=128 value="<?php echo(htmlspecialchars($aUserInfo['msn'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Yahoo! Messenger Handle</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=yahoo size=25 maxlength=50 value="<?php echo(htmlspecialchars($aUserInfo['yahoo'])); ?>"></TD>
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
					<OPTION value=0<?php if($aUserInfo['birthmonth']==0){echo(' selected');} ?>></OPTION>
					<OPTION value=1<?php if($aUserInfo['birthmonth']==1){echo(' selected');} ?>>January</OPTION>
					<OPTION value=2<?php if($aUserInfo['birthmonth']==2){echo(' selected');} ?>>February</OPTION>
					<OPTION value=3<?php if($aUserInfo['birthmonth']==3){echo(' selected');} ?>>March</OPTION>
					<OPTION value=4<?php if($aUserInfo['birthmonth']==4){echo(' selected');} ?>>April</OPTION>
					<OPTION value=5<?php if($aUserInfo['birthmonth']==5){echo(' selected');} ?>>May</OPTION>
					<OPTION value=6<?php if($aUserInfo['birthmonth']==6){echo(' selected');} ?>>June</OPTION>
					<OPTION value=7<?php if($aUserInfo['birthmonth']==7){echo(' selected');} ?>>July</OPTION>
					<OPTION value=8<?php if($aUserInfo['birthmonth']==8){echo(' selected');} ?>>August</OPTION>
					<OPTION value=9<?php if($aUserInfo['birthmonth']==9){echo(' selected');} ?>>September</OPTION>
					<OPTION value=10<?php if($aUserInfo['birthmonth']==10){echo(' selected');} ?>>October</OPTION>
					<OPTION value=11<?php if($aUserInfo['birthmonth']==11){echo(' selected');} ?>>November</OPTION>
					<OPTION value=12<?php if($aUserInfo['birthmonth']==12){echo(' selected');} ?>>December</OPTION>
				</SELECT>
			</TD>
			<TD>
				<SELECT name=birthdate>
					<OPTION value=0<?php if($aUserInfo['birthdate']==0){echo(' selected');} ?>></OPTION>
					<OPTION value=1<?php if($aUserInfo['birthdate']==1){echo(' selected');} ?>>1</OPTION>
					<OPTION value=2<?php if($aUserInfo['birthdate']==2){echo(' selected');} ?>>2</OPTION>
					<OPTION value=3<?php if($aUserInfo['birthdate']==3){echo(' selected');} ?>>3</OPTION>
					<OPTION value=4<?php if($aUserInfo['birthdate']==4){echo(' selected');} ?>>4</OPTION>
					<OPTION value=5<?php if($aUserInfo['birthdate']==5){echo(' selected');} ?>>5</OPTION>
					<OPTION value=6<?php if($aUserInfo['birthdate']==6){echo(' selected');} ?>>6</OPTION>
					<OPTION value=7<?php if($aUserInfo['birthdate']==7){echo(' selected');} ?>>7</OPTION>
					<OPTION value=8<?php if($aUserInfo['birthdate']==8){echo(' selected');} ?>>8</OPTION>
					<OPTION value=9<?php if($aUserInfo['birthdate']==9){echo(' selected');} ?>>9</OPTION>
					<OPTION value=10<?php if($aUserInfo['birthdate']==10){echo(' selected');} ?>>10</OPTION>
					<OPTION value=11<?php if($aUserInfo['birthdate']==11){echo(' selected');} ?>>11</OPTION>
					<OPTION value=12<?php if($aUserInfo['birthdate']==12){echo(' selected');} ?>>12</OPTION>
					<OPTION value=13<?php if($aUserInfo['birthdate']==13){echo(' selected');} ?>>13</OPTION>
					<OPTION value=14<?php if($aUserInfo['birthdate']==14){echo(' selected');} ?>>14</OPTION>
					<OPTION value=15<?php if($aUserInfo['birthdate']==15){echo(' selected');} ?>>15</OPTION>
					<OPTION value=16<?php if($aUserInfo['birthdate']==16){echo(' selected');} ?>>16</OPTION>
					<OPTION value=17<?php if($aUserInfo['birthdate']==17){echo(' selected');} ?>>17</OPTION>
					<OPTION value=18<?php if($aUserInfo['birthdate']==18){echo(' selected');} ?>>18</OPTION>
					<OPTION value=19<?php if($aUserInfo['birthdate']==19){echo(' selected');} ?>>19</OPTION>
					<OPTION value=20<?php if($aUserInfo['birthdate']==20){echo(' selected');} ?>>20</OPTION>
					<OPTION value=21<?php if($aUserInfo['birthdate']==21){echo(' selected');} ?>>21</OPTION>
					<OPTION value=22<?php if($aUserInfo['birthdate']==22){echo(' selected');} ?>>22</OPTION>
					<OPTION value=23<?php if($aUserInfo['birthdate']==23){echo(' selected');} ?>>23</OPTION>
					<OPTION value=24<?php if($aUserInfo['birthdate']==24){echo(' selected');} ?>>24</OPTION>
					<OPTION value=25<?php if($aUserInfo['birthdate']==25){echo(' selected');} ?>>25</OPTION>
					<OPTION value=26<?php if($aUserInfo['birthdate']==26){echo(' selected');} ?>>26</OPTION>
					<OPTION value=27<?php if($aUserInfo['birthdate']==27){echo(' selected');} ?>>27</OPTION>
					<OPTION value=28<?php if($aUserInfo['birthdate']==28){echo(' selected');} ?>>28</OPTION>
					<OPTION value=29<?php if($aUserInfo['birthdate']==29){echo(' selected');} ?>>29</OPTION>
					<OPTION value=30<?php if($aUserInfo['birthdate']==30){echo(' selected');} ?>>30</OPTION>
					<OPTION value=31<?php if($aUserInfo['birthdate']==31){echo(' selected');} ?>>31</OPTION>
				</SELECT>
			</TD>
			<TD><INPUT class=tinput type=text name=birthyear size=4 maxlength=4 value="<?php if($aUserInfo['birthyear']){echo($aUserInfo['birthyear']);} ?>"></TD>
		</TR>
		</TABLE>
	</TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Biography</B>
		<DIV class=smaller>A few details about yourself</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=bio size=25 maxlength=255 value="<?php echo(htmlspecialchars($aUserInfo['bio'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium>
		<B>Location</B>
		<DIV class=smaller>Where are you currently residing?</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=location size=25 maxlength=48 value="<?php echo(htmlspecialchars($aUserInfo['location'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium><B>Interests</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>"><INPUT class=tinput type=text name=interests size=25 maxlength=255 value="<?php echo(htmlspecialchars($aUserInfo['interests'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><B>Occupation</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>"><INPUT class=tinput type=text name=occupation size=25 maxlength=255 value="<?php echo(htmlspecialchars($aUserInfo['occupation'])); ?>"></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium>
		<B>Signature</B>
		<DIV class=smaller>This will appear at the bottom of each of your posts.<BR><BR><A href="info.php?action=vbcode">vB Code</A> is <B>on</B>.<BR>[img] tags are <B>on</B>.<BR><A href="info.php?action=smilies">Smilies</A> are <B>on</B>.</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>">
		<TEXTAREA class=medium name=signature rows=5 cols=35><?php echo(htmlspecialchars($aUserInfo['signature'])); ?></TEXTAREA>
		<DIV class=smaller>[<A href="javascript:alert('The maximum permitted length is 255 characters.\n\nYour signature is '+document.theform.signature.value.length+' characters long.');">Check signature length.</A>]</DIV>
	</TD>
</TR>

</TABLE>

<CENTER><BR><INPUT class=tinput type=submit name=submit value="Save Changes" accesskey="s"></CENTER>
</FORM>