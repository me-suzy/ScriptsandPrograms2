<?php
include "header.inc.php";

if (isset($Sub)) {
	if ($note == "" || $note == " ") {
		echo ("<div align=\"center\"><p>&nbsp</p><font size=\"3\"><strong>oops!</strong></font><br>You forgot to enter a note!<p><a href=\"$HTTP_REFERER\">Go back</a></p>");
		include "footer.inc.php";
		exit;
	}

	$Date = date("F jS, Y");
	$note = htmlspecialchars($note);
	$note = stripslashes($note);
	$note = ereg_replace("(\r\n|\n|\r)", "<br>", $note);
	
	if ($Sub == 1) {
		$Query = mysql_query("INSERT INTO $Table_notes SET date=\"$Date\", color=\"$radiobutton\", note=\"$note\"") or die(mysql_error());

		echo ("<div align=\"center\"><p>&nbsp</p><font size=\"3\"><strong>Note added!</strong></font><br>Your note should now be visable on the note listing.<p><a href=\"notes.php?Sec=notes\">Note listing</a></p>");
		include "footer.inc.php";
		exit;
	}

	if ($Sub == 2) {
		$Query = mysql_query("UPDATE $Table_notes SET color=\"$radiobutton\", note=\"$note\" WHERE N_ID=\"$ID\"") or die(mysql_error());

		echo ("<div align=\"center\"><p>&nbsp</p><font size=\"3\"><strong>Note Updated!</strong></font><br>Your note should now be visable on the note listing.<p><a href=\"notes.php?Sec=notes\">Note listing</a></p>");
		include "footer.inc.php";
		exit;
	}
}

if (isset($Mod)) {
	$Get = mysql_query("SELECT * FROM $Table_notes WHERE N_ID=\"$Mod\"") or die(mysql_error());
	$Array = mysql_fetch_array($Get);

	if ($Array[color] == 1) {
		$selectA = "checked";
	}

	if ($Array[color] == 2) {
		$selectB = "checked";
	}

	if ($Array[color] == 3) {
		$selectC = "checked";
	}

	if (!isset($selectA) || !isset($selectB) || !isset($selectC)) {
		$select = "checked";
	}

	$Sub = "2";
	$note = $Array[note];
	$note = stripslashes($note);
	$note = ereg_replace("<br>", "\r\n", $note);

}

if (!isset($Sub)) {
	$Sub = "1";
}

if (!isset($selectA) || !isset($selectB) || !isset($selectC)) {
	$select = "checked";
}

?>
&nbsp;&nbsp;&nbsp;<a href="index.php">Home</a> > <a href="notes.php?Sec=notes">Notes</a> > Add Note
<br><br>
<table width="450" border="0" align="center" cellpadding="1" cellspacing="0">
<tr>
<td>
<form name="form1" method="post" action="<?= $PHP_SELF; ?>?Sec=notes&Sub=<?= $Sub; ?>">
<input type="hidden" name="ID" value="<?= $Mod; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr> 
    <td><div align="center"><strong><font size="3">Add new note</font></strong></div></td>
  </tr>
  <tr> 
    <td><div align="center"></div></td>
  </tr>
  <tr> 
    <td><div align="center">
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
	  <tr valign="top"> 
	    <td> 
	      <div align="right"> 
		<textarea name="note" cols="20" rows="10" id="note"><?= $note; ?></textarea>
	      </div></td>
	    <td width="135"> <p> Note Color:<br>
		<input name="radiobutton" type="radio" value="0" class="CheckBox" <?= $select; ?>> Yellow <br>
		<input type="radio" name="radiobutton" value="1" class="CheckBox" <?= $selectA; ?>>	Red<br>
		<input type="radio" name="radiobutton" value="2" class="CheckBox" <?= $selectB; ?>>	Blue<br>
		<input type="radio" name="radiobutton" value="3" class="CheckBox" <?= $selectC; ?>>	Green </p>
	      </td>
	  </tr>
	</table>
      </div></td>
  </tr>
  <tr> 
    <td><div align="center">
	<input type="submit" name="Submit" value="  Add Note  ">
      </div></td>
  </tr>
</table>
</form></td>
</tr>
</table>


<?php

include "footer.inc.php";
?>