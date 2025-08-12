<HEAD>
<TITLE>WebRing</TITLE>
<LINK REL=STYLESHEET HREF=style.css>
</HEAD>
<BODY>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=450>
	<TR>
		<TD WIDTH=450>
<DIV CLASS=headline>Join The Ring</DIV>
<DIV CLASS=Normal>

<?

require("config.php");

if (!isset($mode))
{
	$mode = 'index';
}

switch($mode)
{

	case 'index':
	
?>

If you would like to join the webring, simply fill out the fomr below.  Please note that it may take up to 48 hours for your site to be approved, depending on how busy we are.
<HR>

<FORM ACTION=<? echo $PHP_SELF; ?>?mode=submit METHOD=POST>

<TABLE BORDER=0 CELLSPACINT=0 CELLPADDING=10>
	<TR>
		<TD WIDTH=80 VALIGN=TOP><B>Your Name</B></TD>
		<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=name SIZE=20></TD>
	</TR>
	<TR>
		<TD WIDTH=80 VALIGN=TOP><B>Your E-Mail</B></TD>
		<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=email SIZE=20></TD>
	</TR>
	<TR>
		<TD WIDTH=80 VALIGN=TOP><B>Site Name</B></TD>
		<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=site_name SIZE=30></TD>
	</TR>
	<TR>
		<TD WIDTH=80 VALIGN=TOP><B>Site URL</B></TD>
		<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=site_url SIZE=30 VALUE='http://'></TD>
	</TR>
	<TR>
		<TD WIDTH=80 VALIGN=TOP><B>Description:</B></TD>
		<TD VALIGN=TOP><TEXTAREA NAME=description ROWS=5 COLS=25></TEXTAREA></TD>
	</TR>
	<TR>
		<TD COLSPAN=2 ALIGN=RIGHT><INPUT TYPE=Submit NAME=Submit VALUE=Submit></TD>
	</TR>
</TABLE>

<?
	break;

	case 'submit':

		require("functions.php");

	$submit = submit_site($name, $email, $site_name, $site_url, $description);

	if ($submit == 'true')
	{
		echo "Your submission has been added. Thank you.";
	}
	
	else
	{
		echo "There was an error in processing your application. Please be sure you filled out all of the fields.";
	}

	echo "<HR><A HREF=$PHP_SELF?mode=index>Go Back</A>";	
}

?>
</DIV>
		</TD>
	</TR>
</TABLE>