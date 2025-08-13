<?
#####################################################################
#  Pager 0,12
#  Released under the terms of the GNU General Public License.
#  Please refer to the README file for more information.
#####################################################################

#####################################################################
# PLEASE EDIT THE FOLLOWING VARIABLES:
#####################################################################

# The path to the language file:
$language = "language_files/english.php";

#####################################################################
# THAT'S IT! NO MORE EDITING NECESSARY.
#####################################################################



require($language);

function besked($besked) {
	global $l_tilbage;

	echo "<html><head><title>Pager</title>\n";
	echo "<style type=text/css><!--\n";
	echo "body {background:black; font-family:helvetica; font-size:10pt; color:white}\n";
	echo "--></style>\n";
	echo "</head>\n\n\n\n";

	echo "<body>\n";
	echo "<center>\n";
	echo "$besked<p>\n";
	echo "<form><input type=button onClick=history.go(-1) value=\"&lt;&lt;&lt; $l_tilbage\"></form>\n";
	echo "</center>\n";
	echo "</body></html>\n";
}

$formular = "<TABLE BGCOLOR=\"$tabelbaggrund\" BORDER=$ramme CELLSPACING=$celleafstand CELLPADDING=$cellevaeg>
<FORM ACTION=$PHP_SELF METHOD=post>
<TR><TD><FONT SIZE=$skriftstr FACE=\"$skrifttype\" COLOR=\"$skriftfarve\">$l_send</TD>
<TD><INPUT TYPE=text NAME=icqnummer SIZE=25></TD>
<TR><TD><FONT SIZE=$skriftstr FACE=\"$skrifttype\" COLOR=\"$skriftfarve\">$l_emne</TD>
<TD><INPUT TYPE=text name=emne SIZE=25></TD>
<TR><TD><FONT SIZE=$skriftstr FACE=$skrifttype COLOR=\"$skriftfarve\">$l_besked</TD>
<TD><TEXTAREA NAME=besked COLS=25 ROWS=8 WRAP=virtual></TEXTAREA></TD>
<TR><TD><FONT SIZE=$skriftstr FACE=\"$skrifttype\" COLOR=\"$skriftfarve\">$l_ditnavn</TD>
<TD><INPUT TYPE=text NAME=navn SIZE=25></TD>
<TR><TD><FONT SIZE=$skriftstr FACE=\"$skrifttype\" COLOR=\"$skriftfarve\">$l_dinepost</TD>
<TD><INPUT TYPE=text NAME=epost SIZE=25></TD>
<TR><TD>&nbsp;</TD>
<TD><FONT SIZE=$skriftstr FACE=\"$skrifttype\" COLOR=\"$skriftfarve\"><INPUT TYPE=submit NAME=submit VALUE=\"$l_sendbeskeden\">
</FORM>
</TABLE>";

$bund = "<br><br>
<hr>
<center>
<a href=README.html>$l_hjaelp</a>
</center>
<body></html>";

if ($submit) {
	if (!$emne) $emne = "[No subject]";
	if (!$navn) $navn = "[No name]";

	# If no ICQ number was specified...
	if (!$icqnummer) {
		besked($l_icqikkeudfyldt);
	}
	# If the user did not write a message...
	elseif (!$besked) {
		besked($l_ingenbesked);
	}
	# The message body cannot contain more than 450 characters.
	elseif (strlen($besked) > 450) {
		besked($l_over450tegn);
	}
	# If the user did not specify his/her email address...
	elseif (!$epost) {
		besked($l_epostikkeudfyldt);
	}
	# Sending the message:
	else {
		mail("$icqnummer@pager.icq.com", $emne, $besked, "From: $navn <$epost>");
		besked($l_beskedenafsendt);
	}
}

else {
	if (!$lavkoden) {
		echo "<html><head><title>Pager</title>\n";
		echo "<style type=text/css><!--\n";
		echo "body {background:orange; font-family:helvetica; font-size:10pt}\n";
		echo "table {background:navy}\n";
		echo "td {font-size:10pt; font-family:helvetica; color:white}\n";
		echo "--></style>\n";
		echo "</head>\n\n\n\n";

		echo "<body>\n";
		echo "$l_redigerformularen<p>\n";
		echo "<table border=1 cellspacing=1 cellpadding=3>\n";
		echo "<form action=$PHP_SELF method=post>\n";
		echo "<tr><td>$l_tabelbaggrund</td><td><input type=text name=tabelbaggrund size=20 value=navy></td>\n";
		echo "<tr><td>$l_ramme</td><td><input type=text name=ramme size=2 value=1><input type=button value=\"&lt;\" onClick=\"this.form.ramme.value = parseInt(this.form.ramme.value)-1\"><input type=button value=\"&gt;\" onClick=\"this.form.ramme.value = parseInt(this.form.ramme.value)+1\"></td>\n";
		echo "<tr><td>$l_celleafstand</td><td><input type=text name=celleafstand size=2 value=1><input type=button value=\"&lt;\" onClick=\"this.form.celleafstand.value = parseInt(this.form.celleafstand.value)-1\"><input type=button value=\"&gt;\" onClick=\"this.form.celleafstand.value = parseInt(this.form.celleafstand.value)+1\"></td>\n";
		echo "<tr><td>$l_cellevaeg</td><td><input type=text name=cellevaeg size=2 value=3><input type=button value=\"&lt;\" onClick=\"this.form.cellevaeg.value = parseInt(this.form.cellevaeg.value)-1\"><input type=button value=\"&gt;\" onClick=\"this.form.cellevaeg.value = parseInt(this.form.cellevaeg.value)+1\"></td>\n";
		echo "<tr><td>$l_skrifttype</td><td><input type=text name=skrifttype size=20 value=helvetica></td>\n";
		echo "<tr><td>$l_skriftstr</td><td><input type=text name=skriftstr size=2 value=-1><input type=button value=\"&lt;\" onClick=\"this.form.skriftstr.value = parseInt(this.form.skriftstr.value)-1\"><input type=button value=\"&gt;\" onClick=\"this.form.skriftstr.value = parseInt(this.form.skriftstr.value)+1\"></td>\n";
		echo "<tr><td>$l_skriftfarve</td><td><input type=text name=skriftfarve size=20 value=white></td>\n";
		echo "<tr><td>&nbsp;</td><td><input type=submit name=lavkoden value=\"$l_lavkoden\">\n";
		echo "</form>\n";
		echo "</table>\n";
		echo $bund;
	}

	else {
		echo "<html><head><title>Pager</title>\n";
		echo "<style type=text/css><!--\n";
		echo "body {background:orange; font-family:helvetica; font-size:10pt}\n";
		echo "--></style>\n";
		echo "</head>\n\n\n\n";

		echo "<body>\n";
		echo "$l_gennemse<p>\n";
		echo "$formular<br><br><br><br>\n";
		echo "$l_kopier<p>\n";
		echo "<form>\n";
		echo "<textarea cols=50 rows=10>";
		echo htmlentities($formular);
		echo "</textarea>\n";
		echo "</form>\n";
		echo $bund;
	}
}
?>
