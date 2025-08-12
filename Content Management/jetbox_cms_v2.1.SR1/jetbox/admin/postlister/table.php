<?php
require("functions.php");

sidehoved("", 0);



if (!$send) {
	echo "$s6<p>\n";
	echo "<form action=$PHP_SELF method=post>\n";
	echo "<input type=submit name=send value=\"$s7\">\n";
	echo "</form>\n";
}



else {
	# The name of the main table must include at least one character (naturally),
	# and numbers are allowed. However, table names consisting *only*
	# of numbers are not allowed.
	if (!ereg("^[A-Za-z]+[0-9]*$", $mainTable)) {
		# "Error: The name of the main table is invalid":
		fejl($s10);
	}

	mysql_query("create table $mainTable (
		liste char ( 20 ),
		tilmeldingsbesked text,
		afmeldingsbesked text,
		standardafsender char ( 100 ),
		signatur text,
		afsender char ( 100 ),
		emne char ( 100 ),
		ebrev text
	)");

	echo "$s11\n";
}



sidefod();
?>