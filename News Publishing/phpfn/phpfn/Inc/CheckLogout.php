<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

DisplayGroupHeading('End PHPFreeNews Session?');
?>
<TABLE class="Admin">
	<TR>
		<TD width="80">
			<DIV align="center"><IMG src="Inc/Images/Question.gif" alt="Question"></DIV>
		</TD>
		<TD width="320">
			<DIV class="plaintext">Are you sure you want to end your session and log out?</DIV>
			<BR />
			<BR />
			<DIV align="center">
				<A href="<?=$AdminScript?>?action=Logout&amp;mode=Destroy">Yes</A> | <A href="<?=$AdminScript?>">No</A>
			</DIV>
		</TD>
	</TR>
</TABLE>
