<?php
include_once "data.inc.php";

if (isset($Delete)) {
	$CONNECT = mysql_connect($DB_host,$DB_user,$DB_pass) or die(mysql_error());
	mysql_select_db($DB_name);

	$Query = mysql_query("DELETE FROM $Table_notes WHERE N_ID=$Delete") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: notes.php?Sec=notes");
}

include "header.inc.php";

$Get = mysql_query("SELECT * FROM $Table_notes ORDER BY N_ID DESC") or die(mysql_error());
$Counted = mysql_num_rows($Get);

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Notes<br><br>");

?>

<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td>
	  <table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><img src="images/mynotes.gif"><br><br>

<?

if ($Counted == 0) {
	echo ("<div align=center><strong>No Notes found in database.</strong></div>");
}

while ($R = mysql_fetch_object($Get)) {

	if ($R->color == 0) {
		$Color = "#FFFFCC";
	}

	if ($R->color == 1) {
		$Color = "#FFCCCC";
	}

	if ($R->color == 2) {
		$Color = "#A0F0FF";
	}

	if ($R->color == 3) {
		$Color = "#A0FFC0";
	}

?>
	<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000" align="center">
	  <tr>
		<td><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="<?= $Color; ?>">
			<tr> 
			  <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
				  <tr valign="top"> 
					<td width="20" class="BottomBorder"><img src="images/note.gif" width="16" height="16" alt="Note ID: <?= $R->N_ID; ?>"></td>
					<td class="BottomBorder"><strong><?= $R->date; ?></strong><br><em><?= $R->note; ?></em><br><br></td>
					<td width="20" class="BottomBorder"><div align="center"><a href="print.php?Sec=notes&ID=<?= $R->N_ID; ?>"><img src="images/print.gif" width="16" height="16" alt="Print Note" border="0"></div></td>
					<td width="20" class="BottomBorder"><div align="center"><a href="add_note.php?Sec=notes&Mod=<?= $R->N_ID; ?>"><img src="images/edit.gif" width="16" height="16" alt="Edit Note" border="0"></div></td>
					<td width="20" class="BottomBorder"><div align="center"><a href="notes.php?Delete=<?= $R->N_ID; ?>"><img src="images/delete.gif" width="16" height="16" alt="Delete Note" border="0"></div></td>
				  </tr>
				</table></td>
			</tr>
		  </table></td>
	  </tr>
	</table><br>
	



<?php
}

?>
		</td>
        </tr>
      </table></td>
	  </tr>
	</table>

<?php

include "footer.inc.php";
?>