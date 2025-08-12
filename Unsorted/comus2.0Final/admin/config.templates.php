<?
include($DOCUMENT_ROOT . "/includes/config.inc.php");
include($DOCUMENT_ROOT . "/includes/header.php");
if($deflang)
{
	include("../includes/language/lang-".$deflang.".php");
}
else
{
    include("../includes/language/lang-english.php");
}

$file_p  = $DOCUMENT_ROOT . "/templates/email_partner.txt";
$file_ps = $DOCUMENT_ROOT . "/templates/email_partner_subject.txt";
$file_c  = $DOCUMENT_ROOT . "/templates/email_confirm.txt";
$file_cs = $DOCUMENT_ROOT . "/templates/email_confirm_subject.txt";
$file_a  = $DOCUMENT_ROOT . "/templates/email_post_accepted.txt";
$file_as = $DOCUMENT_ROOT . "/templates/email_post_accepted_subject.txt";
$file_r  = $DOCUMENT_ROOT . "/templates/email_post_rejected.txt";
$file_rs = $DOCUMENT_ROOT . "/templates/email_post_rejected_subject.txt";
$file_ht = $DOCUMENT_ROOT . "/templates/html_galtmpl.txt";

if(isset($modify))
{
	/* Html Template */
    $f_ht = fopen($file_ht, "w");
	$htmlt = stripslashes($htmlt);
    fputs($f_ht,$htmlt);
    fclose($f_ht);
    /* Partner */
    $f_partner = fopen($file_p, "w");
	$partner = stripslashes($partner);
    fputs($f_partner,$partner);
    fclose($f_partner);
    /* Partner Subject */
    $f_partners = fopen($file_ps, "w");
    fputs($f_partners,$partners);
    fclose($f_partners);
	/* Confirm */
    $f_confirm = fopen($file_c, "w");
	$confirm = stripslashes($confirm);
    fputs($f_confirm,$confirm);
    fclose($f_confirm);
	/* Confirm Subject */
    $f_confirms = fopen($file_cs, "w");
    fputs($f_confirms,$confirms);
    fclose($f_confirms);

	/* Post Accepted */
    $f_accepted = fopen($file_a, "w");
	$accepted = stripslashes($accepted);
    fputs($f_accepted,$accepted);
    fclose($f_accepted);
	/* Post Accepted Subject */
    $f_accepteds = fopen($file_as, "w");
    fputs($f_accepteds,$accepteds);
    fclose($f_accepteds);
	/* Post Rejected */
    $f_rejected = fopen($file_r, "w");
	$rejected = stripslashes($rejected);
    fputs($f_rejected,$rejected);
    fclose($f_rejected);
	/* Post rejected Subject */
    $f_rejecteds = fopen($file_rs, "w");
    fputs($f_rejecteds,$rejecteds);
    fclose($f_rejecteds);
}
?>
<TABLE BORDER="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<? printabout(2); ?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="2">
	<A HREF="admin/index.php"><? echo GTGP_SET_RETURN; ?></A>
	</TD>
</TR>
<form method="post" action="admin/config.templates.php">
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_TMPL; ?></B>
	</TD>
</TR>



<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_TMPL_HTML; ?></B>
	<BR>
<?
if(file_exists($file_ht))
{
$f_htmlt = fopen($file_ht, "r");
$f_dlugh = filesize($file_ht);
$h_htmlt = fread($f_htmlt,$f_dlugh);
fclose($f_htmlt);
}
else
{
 $c_htmlt = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_ht . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $c_htmlt </font>"; 
?>
	<TEXTAREA NAME="htmlt" COLS="85" ROWS="10" WRAP="OFF"><? echo $h_htmlt; ?></TEXTAREA>
	</TD>
</TR>





<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_TMPL_PMAIL; ?></B>
	<BR>
<?
if(file_exists($file_p))
{
$f_partner = fopen($file_p, "r");
$f_dlug = filesize($file_p);
$f_html = fread($f_partner,$f_dlug);
fclose($f_partner);
}
else
{
 $c_partner = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_p . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $c_partner </font>"; 
?>
<?
if(file_exists($file_ps))
{
$f_partners = fopen($file_ps, "r");
$f_dlugs = filesize($file_ps);
$f_htmls = fread($f_partners,$f_dlugs);
fclose($f_partners);
}
else
{
 $c_partners = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_ps . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $c_partners </font>"; 
?>
	<INPUT TYPE="TEXT" NAME="partners" value="<? echo $f_htmls; ?>" SIZE="40">
	<TEXTAREA NAME="partner" COLS="85" ROWS="10" WRAP="OFF"><? echo $f_html; ?></TEXTAREA>
	</TD>
</TR>
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_TMPL_CMAIL; ?></B>
	<BR>
<?
if(file_exists($file_c))
{
$f_confirm = fopen($file_c, "r");
$c_dlug = filesize($file_c);
$c_html = fread($f_confirm,$c_dlug);
fclose($f_confirm);
}
else
{
 $c_confirm = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_c . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $c_confirm </font>"; 
?>
<?
if(file_exists($file_cs))
{
$f_confirms = fopen($file_cs, "r");
$c_dlugs = filesize($file_cs);
$c_htmls = fread($f_confirms,$c_dlugs);
fclose($f_confirms);
}
else
{
 $c_confirms = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_cs . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $c_confirms </font>"; 
?>
	<INPUT TYPE="TEXT" NAME="confirms" value="<? echo $c_htmls; ?>" SIZE="40">
	<TEXTAREA NAME="confirm" COLS="85" ROWS="10" WRAP="OFF"><? echo $c_html; ?></TEXTAREA>
	</TD>
</TR>
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_TMPL_AMAIL; ?></B>
	<BR>
<?
if(file_exists($file_a))
{
$f_accepted = fopen($file_a, "r");
$a_dlug = filesize($file_a);
$a_html = fread($f_accepted,$a_dlug);
fclose($f_accepted);
}
else
{
 $a_accepted = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_a . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $a_accepted </font>"; 
?>
<?
if(file_exists($file_as))
{
$f_accepteds = fopen($file_as, "r");
$a_dlugs = filesize($file_as);
$a_htmls = fread($f_accepteds,$a_dlugs);
fclose($f_accepteds);
}
else
{
 $a_accepteds = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_as . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $a_accepteds </font>"; 
?>
	<INPUT TYPE="TEXT" NAME="accepteds" value="<? echo $a_htmls; ?>" SIZE="40">
	<TEXTAREA NAME="accepted" COLS="85" ROWS="10" WRAP="OFF"><? echo $a_html; ?></TEXTAREA>
	</TD>
</TR>







<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_TMPL_RMAIL; ?></B>
	<BR>
<?
if(file_exists($file_r))
{
$f_rejected = fopen($file_r, "r");
$r_dlug = filesize($file_r);
$r_html = fread($f_rejected,$r_dlug);
fclose($f_rejected);
}
else
{
 $r_rejected = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_r . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $r_rejected </font>"; 
?>
<?
if(file_exists($file_rs))
{
$f_rejecteds = fopen($file_rs, "r");
$r_dlugs = filesize($file_rs);
$r_htmls = fread($f_rejecteds,$r_dlugs);
fclose($f_rejecteds);
}
else
{
 $r_rejecteds = GTGP_ADMIN_TMPL_FILE . "<B> " . $file_rs . "</B> " .GTGP_ADMIN_TMPL_NEX;
}
 echo "<font color=red> $r_rejecteds </font>"; 
?>
	<INPUT TYPE="TEXT" NAME="rejecteds" value="<? echo $r_htmls; ?>" SIZE="40">
	<TEXTAREA NAME="rejected" COLS="85" ROWS="10" WRAP="OFF"><? echo $r_html; ?></TEXTAREA>
	</TD>
</TR>










<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<input type=submit name=modify value="<? echo GTGP_ADMIN_TMPL_SUBMIT; ?>">
	</TD>
</TR>	
</FORM>
<TR>
	<TD ALIGN="CENTER" COLSPAN="2">
	<font color=red><? echo $message3; ?>&nbsp;</font>
	</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</body>
</html>