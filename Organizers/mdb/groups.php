<?php
include "data.inc.php";

$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
mysql_select_db($DB_name);


if(isset($_POST["list2"]) || isset($_POST["list1"])) {
	$Job = mysql_query("SELECT * FROM $Table_contacts") or die(mysql_error());

	while ($J = mysql_fetch_object($Job)) {
		$Sub = $J->group_num;
		$removed = ereg_replace(":".$ID,"", $Sub);
		$Rem = mysql_query("UPDATE $Table_contacts SET group_num=\"$removed\" WHERE C_ID=\"$J->C_ID\"") or die(mysql_error());
	}

		while(list($key,$val)=each($_POST["list2"])) {
			// update new
			$Get = mysql_query("SELECT group_num FROM $Table_contacts WHERE C_ID=\"$val\"") or die(mysql_error());
			$M = mysql_fetch_object($Get);

			$R = explode(":", $M->group_num);
				if (!in_array($ID, $R)) {
					$add = ":" . $ID;
					$added = $M->group_num . $add;
					$Update = mysql_query("UPDATE $Table_contacts SET group_num=\"$added\" WHERE C_ID=\"$val\"") or die(mysql_error());
				}
	}
	
	header("location: $HTTP_REFERER");
}

$Query = mysql_query("SELECT * FROM $Table_contacts") or die(mysql_error());

include "header.inc.php";

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"contacts.php?Sec=contacts\">Contacts</a> > Organize Group<br><br>");
?>

<div align="center">
<font size="3">
<b>( <?= $gname; ?> ) Group</b>
<font size="2">
<p>
<form action="groups.php?Sec=contacts&ID=<?= $ID; ?>&gname=<?= $gname; ?>" method="post" name="combo_box">
<table cellpadding="4" cellspacing="0" border="0">
<tr>
	<td><div align=center><strong>Not in group</strong></div><br>
       <select multiple size="10" name="list1[]" style="width:150" class="select">
		<option>~~~~~~~~~~~~~~</option>
<?php

	while ($Item = mysql_fetch_object($Query)) {
		$array = explode(":", $Item->group_num);

			if (!in_array($ID, $array)) {
					echo ("<option value=\"$Item->C_ID\">$Item->first_name $Item->last_name</option>");
			}

			if (in_array($ID, $array)) {
					$second .= "<option value=\"$Item->C_ID\">$Item->first_name $Item->last_name</option>\n";
			}

	}

?>
		</select>
	</td>
	<td align="center" valign="middle">
	<input type="button" onClick="move(this.form.elements['list2[]'],this.form.elements['list1[]'])" value="<<">
	<input type="button" onClick="move(this.form.elements['list1[]'],this.form.elements['list2[]'])" value=">>">
	</td>
	<td><div align=center><strong>Curently in group</strong></div><br>
	<select multiple size="10" name="list2[]" style="width:150" class="select">
	<option>~~~~~~~~~~~~~~</option>
<?php
	
	echo $second;

?>

	</select>
	</td>
</tr>
	</table>
<p>
<input type="submit" name="submit_button" value="Submit" onClick="selectAll(document.combo_box.elements['list2[]']);">
</form>
<p>
</div>

<?php

include "footer.inc.php";
?>