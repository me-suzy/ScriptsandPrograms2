<SCRIPT language="JavaScript" type="text/javascript">
<!--
function vbcode(code)
{
	inserttext = prompt("Enter text to be formatted:\n[" + code + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		document.theform.message.value = document.theform.message.value + "[" + code + "]" + inserttext + "[/" + code + "]";
	}
	document.theform.message.focus();
}

function vbcode2(code, option)
{
	inserttext = prompt("Enter the text to be formatted:\n[" + code + "=" + option + "]blah[/" + code + "]", "");
	if((inserttext != null) && (inserttext != ""))
	{
		document.theform.message.value = document.theform.message.value + "[" + code + "=" + option + "]" + inserttext + "[/" + code + "]";
	}

	document.theform.tsize.selectedIndex = 0;
	document.theform.tfont.selectedIndex = 0;
	document.theform.tcolor.selectedIndex = 0;
	document.theform.message.focus();
}

function smilie(smilie)
{
	document.theform.message.value = document.theform.message.value + smilie;
	document.theform.message.focus();
}
//-->
</SCRIPT>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="100%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="usercp.php">User Control Panel</A> &gt; <A href="private.php">Private Messages</A> &gt; New Private Message</B></TD>
</TR>
</TABLE><BR>

<?php
	// User CP menu.
	PrintCPMenu();

	// What to do?
	if(($_REQUEST['submit'] == 'Preview Message') || is_array($aError))
	{
		// Store the posted values. We'll need them now and later.
		$strRecipient = htmlspecialchars($_REQUEST['recipient']);
		$strSubject = htmlspecialchars($_REQUEST['subject']);
		$iPostIcon = (int)$_REQUEST['icon'];
		$strMessage = $_REQUEST['message'];
		$bParseURLs = (bool)$_REQUEST['parseurls'];
		$bParseEMails = (bool)$_REQUEST['parseemails'];
		$bDisableSmilies = (bool)$_REQUEST['dsmilies'];
		$bSaveCopy = (bool)$_REQUEST['savecopy'];
		$bTrack = (bool)$_REQUEST['track'];

		// Did we preview or submit?
		if(is_array($aError))
		{
			// We submitted and got an error, so display that.
			DisplayErrors($aError);
		}
		else
		{
			// Make a copy of the message, so we can parse it for the
			// preview, yet still have the original.
			$strParsedMessage = $strMessage;

			// Put [email] tags around suspected e-mail addresses if they want us to.
			if($bParseEMails)
			{
				$strParsedMessage = ParseEMails($strParsedMessage);
			}

			// Parse any vB code in the message.
			$strParsedMessage = ParseMessage($strParsedMessage, $bDisableSmilies);
?>
<BR><TABLE width="100%" cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" align=center>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" align=left class=smaller style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>Message Preview</B></TD></TR>
	<TR><TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php echo($strParsedMessage); ?></TD></TR>
</TABLE><BR>
<?php
		}
	}
	else
	{
		echo('<br>');
	}
?>

<FORM style="margin: 0px;" name=theform action="private.php?action=<?php echo($_REQUEST['action']); ?>" method=post>
<INPUT type=hidden name=id value=<?php echo((int)$_REQUEST['id']); ?>>
<TABLE cellpadding=4 cellspacing=1 border=0 bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" width="100%" align=center>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['heading']['bgcolor']); ?>" colspan=2 class=medium style="color: <?php echo($CFG['style']['table']['heading']['txtcolor']); ?>;"><B>New Private Message</B></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Logged In As</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium><?php if($_SESSION['loggedin']){echo(htmlspecialchars($_SESSION['username']).' <FONT class=smaller>[<A href="logout.php">Logout</A>]</FONT>');}else{echo('<I>Not logged in.</I> <FONT class=smaller>[<A href="login.php">Login</A>]</FONT>');} ?></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Recipient</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=smaller><INPUT class=tinput type=text name=recipient size=25 maxlength=32 value="<?php echo($strRecipient); ?>">&nbsp;&nbsp;<A href="memberlist.php" target="_blank">View Member List</A></TD>
</TR>

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=medium nowrap><B>Subject</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" class=smaller><INPUT class=tinput type=text name=subject size=40 maxlength=64 value="<?php echo($strSubject); ?>"></TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap>
		<B>Message Icon</B>
		<DIV class=smaller><INPUT type=radio name=icon value=0<?php if(!$iPostIcon) echo(' checked'); ?>>No icon</DIV>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
		<DIV class=smaller>
<?php
	// Display the post icons' radio buttons.
	DisplayPostIcons($aPostIcons, $iPostIcon);
?>		</DIV>
	</TD>
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
		<B>Message</B><BR><BR><BR>

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
		<TEXTAREA class=tinput name=message cols=70 rows=20><?php echo(htmlspecialchars($strMessage)); ?></TEXTAREA>
		<DIV class=smaller>[<A href="javascript:alert('The maximum permitted length is 5000 characters.\n\nYour message is '+document.theform.message.value.length+' characters long.');">Check message length.</A>]</DIV>
	</TD>
</TR>

<TR>
	<TD valign=top bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" class=medium nowrap><B>Options</B></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>">
	<TABLE cellpadding=0 cellspacing=0 border=0>
		<TR>
			<TD valign=top><INPUT type=checkbox name=parseurls disabled<?php if($bParseURLs){echo(' checked');} ?>></TD>
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
			<TD width="100%" class=smaller><B>Disable smilies in this message?</B> This will disable the automatic parsing of smilie codes, i.e. :cool:, into smilie images.</TD>
		</TR>
		<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
		<TR>
			<TD valign=top><INPUT type=checkbox name=savecopy<?php if($bSaveCopy){echo(' checked');} ?>>
			<TD width="100%" class=smaller><B>Save a copy?</B> This will save a copy of the message to your <A href="private.php?action=view&amp;item=folder&amp;id=1">Sent Items</A> folder.</TD>
		</TR>
		<TR><TD colspan=2><IMG src="images/space.png" width=1 height=3 alt=""></TD></TR>
		<TR>
			<TD valign=top><INPUT type=checkbox name=track<?php if($bTrack){echo(' checked');} ?>>
			<TD width="100%" class=smaller><B>Track the message?</B> This will allow you to know if and when the recipient reads your message.</TD>
		</TR>
	</TABLE>
	</TD>
</TR>

</TABLE><BR>

<CENTER><INPUT class=tinput type=submit name=submit value="Send Message" accesskey="s"> <INPUT class=tinput type=submit name=submit value="Preview Message" accesskey="p"></CENTER>
</FORM><BR>

<SCRIPT language="JavaScript" type="text/javascript">
<!--
	document.theform.status.value='';
//-->
</SCRIPT>