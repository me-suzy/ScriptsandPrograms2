<?

require("ssn_verify.php");

if ($_POST) extract($_POST);

if ($ssn) {
  $check = isValidSSN($ssn);
	if ($check) $response = $ssn." appears to be a valid Social Security Number.";
	else $response = "<FONT COLOR=#CC0000>".$ssn." is an INVALID Social Security Number.</FONT>";
}

?>

<HTML>
<HEAD>
<TITLE>SSN Verify Example Script</TITLE>
</HEAD>
<BODY>
<CENTER>
<FONT FACE="Verdana" SIZE=2 COLOR=#000000>
<BR><B><?=$response?></B><BR><BR><BR>
<FORM NAME="ssn_verify" ACTION="<?=$PHP_SELF?>" METHOD="POST">
Enter SSN# to Check:&nbsp;&nbsp;
<INPUT TYPE=TEXT NAME="ssn" VALUE="<?=$ssn?>" SIZE=11 MAXLENGTH=11>
&nbsp;&nbsp;
<INPUT TYPE=SUBMIT VALUE="Check SSN">
</BODY>
</HTML>
