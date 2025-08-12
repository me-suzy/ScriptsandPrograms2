<?php
$pagetitle="Add/ delete subscribers";
$sel_tab="2.2";
require("functions.php");
sidehoved();
vaelgListe($PHP_SELF);
$liste = addslashes($_REQUEST['liste']);
if ($epostadresseTilfoej) {
	# Checking to see if we have a valid email address:
	if (!ereg("^[-0-9A-Za-z._]+@[-0-9A-Za-z.]+\.[A-Za-z]{2,3}$", $epostadresseTilfoej)) {
		fejl($s38);
	}

	$epostadresseTilfoej = addslashes($epostadresseTilfoej);

	# Checking to see if the email address already exists on the list:
	$kommando = mysql_prefix_query("select epostadresse from $liste where epostadresse = '$epostadresseTilfoej'");
	if (mysql_num_rows($kommando) > 0) {
		# "The email address already exists on the list":
		fejl($s40);
	}

	# Inserting the email address into the database:
	mysql_prefix_query("insert into $liste values (
		'$epostadresseTilfoej',
		'$uniktId',
		'1'
	)");

	echo "$s39<p>\n";
	echo "<form action=\"$PHP_SELF?liste=$liste\" method=post><input type=submit value=\"$s9\"></form>\n";

	sidefod();

	exit;
}



if ($sletDenne) {
	$sletDenne = addslashes($sletDenne);
	mysql_prefix_query("delete from $liste where epostadresse = '$sletDenne'");

	echo "$s50<p>\n";
	echo "<form action=\"$PHP_SELF?liste=$liste\" method=post><input type=submit value=\"$s9\"></form>\n";

	sidefod();
	exit;
}

echo "<font size=2><b>$s35</b></font>\n";
echo "<form action=\"$PHP_SELF?liste=$liste\" method=post>\n";
echo "<input type=radio name=soegevalg value=1 checked>\n";
echo "$s41\n";
echo "<select name=visHvad1 onFocus=\"this.form.soegevalg[0].checked = true\">\n";
echo "<option value=alle>$s42\n";
echo "<option value=godkendte>$s43\n";
echo "<option value=ikke-godkendte>$s44\n";
echo "</select>\n";
echo "<br>\n\n";
echo "<input type=radio name=soegevalg value=2>\n";
echo "$s41\n";
echo "<select name=visHvad2 onFocus=\"this.form.soegevalg[1].checked = true\">\n";
echo "<option value=alle>$s42\n";
echo "<option value=godkendte>$s43\n";
echo "<option value=ikke-godkendte>$s44\n";
echo "</select>\n";
echo "$s45\n";
echo "<select name=alfabetet onFocus=\"this.form.soegevalg[1].checked = true\">\n";
echo "<option>a\n";
echo "<option>b\n";
echo "<option>c\n";
echo "<option>d\n";
echo "<option>e\n";
echo "<option>f\n";
echo "<option>g\n";
echo "<option>h\n";
echo "<option>i\n";
echo "<option>j\n";
echo "<option>k\n";
echo "<option>l\n";
echo "<option>m\n";
echo "<option>n\n";
echo "<option>o\n";
echo "<option>p\n";
echo "<option>q\n";
echo "<option>r\n";
echo "<option>s\n";
echo "<option>t\n";
echo "<option>u\n";
echo "<option>v\n";
echo "<option>w\n";
echo "<option>x\n";
echo "<option>y\n";
echo "<option>z\n";
echo "<option>0\n";
echo "<option>1\n";
echo "<option>2\n";
echo "<option>3\n";
echo "<option>4\n";
echo "<option>5\n";
echo "<option>6\n";
echo "<option>7\n";
echo "<option>8\n";
echo "<option>9\n";
echo "</select>\n";
echo "<br>\n\n";

echo "<input type=radio name=soegevalg value=3>\n";
echo "$s41\n";
echo "<select name=visHvad3 onFocus=\"this.form.soegevalg[2].checked = true\">\n";
echo "<option value=alle>$s42\n";
echo "<option value=godkendte>$s43\n";
echo "<option value=ikke-godkendte>$s44\n";
echo "</select>\n";
echo "$s46\n";
echo "<input type=text name=soegeord size=15 value=\"".htmlspecialchars(stripslashes($soegeord))."\" onFocus=\"this.form.soegevalg[2].checked = true\">\n";
echo "<br>\n\n";
echo "<input type=submit name=soeg value=\"$s13\">\n";
echo "</form>\n\n";



if ($soeg) {
	$alfabetet = addslashes($alfabetet);
	$soegeord = addslashes($soegeord);
	if ($soegevalg == 1) {
		if ($visHvad1 == "alle") {
			$kommando = mysql_prefix_query("select * from $liste") or die (mysql_error());;
		}
		if ($visHvad1 == "godkendte") {
			$kommando = mysql_prefix_query("select * from $liste where godkendt = '1'");
		}
		if ($visHvad1 == "ikke-godkendte") {
			$kommando = mysql_prefix_query("select * from $liste where godkendt = '0'");
		}
	}

	if ($soegevalg == 2) {
		if ($visHvad2 == "alle") {
			$kommando = mysql_prefix_query("select * from $liste where epostadresse like '$alfabetet%'");
		}
		if ($visHvad2 == "godkendte") {
			$kommando = mysql_prefix_query("select * from $liste where epostadresse like '$alfabetet%' and godkendt = '1'");
		}
		if ($visHvad2 == "ikke-godkendte") {
			$kommando = mysql_prefix_query("select * from $liste where epostadresse like '$alfabetet%' and godkendt = '0'");
		}
	}

	if ($soegevalg == 3) {
		if ($visHvad3 == "alle") {
			$kommando = mysql_prefix_query("select * from $liste where epostadresse like '%$soegeord%'");
		}
		if ($visHvad3 == "godkendte") {
			$kommando = mysql_prefix_query("select * from $liste where epostadresse like '%$soegeord%' and godkendt = '1'");
		}
		if ($visHvad3 == "ikke-godkendte") {
			$kommando = mysql_prefix_query("select * from $liste where epostadresse like '%$soegeord%' and godkendt = '0'");
		}
	}



	if (mysql_num_rows($kommando) == 0) {
		# "No search results":
		echo "$s47\n";
	}



	else {
		echo "<form action=\"$PHP_SELF?liste=$liste\" method=post>\n";
		echo "<select name=sletDenne size=5>\n";

		while ($resultat = mysql_fetch_array($kommando)) {
			$sletEpostadresse = htmlspecialchars(stripslashes($resultat[epostadresse]));
			$datum = $resultat[date];		
			if ($resultat[godkendt] == 1) $sletGodkendt = $s48;
			else $sletGodkendt = $s49;


			echo "<option value=\"$sletEpostadresse\">$sletEpostadresse [$sletGodkendt] $datum\n";
		}

		echo "</select><br>\n";
		echo "<input type=submit value=\"$s21\">\n";
		echo "</form>\n\n";
	}
}



echo "<hr>\n";

echo "<font size=2><b>$s34</b></font>\n";

echo "<form action=\"$PHP_SELF?liste=$liste\" method=post>\n";
echo "$s37<br>\n";
echo "<input type=text name=epostadresseTilfoej size=40 maxlength=50>\n";
echo "<input type=submit value=\"$s36\">\n";
echo "</form><br><br>\n";



sidefod();
?>
