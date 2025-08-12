<?php
if(!isset($_GET['install'])) {
echo('<p>Installer for NoteIt! v1.0.</p><p>Please edit the db settings in note.inc.php before running this script.</p><p>Also, please change the values of $LOGIN and $PASSWORD in the index.php file.<p>Ready to go? <a href="?install=yes">Yes!</a></p>');
}
else {
include "note.inc.php";
$note = new Note;
mysql_query("CREATE TABLE `notes` (
  `noteID` int(11) NOT NULL auto_increment,
  `noteTitle` text NOT NULL,
  `noteText` text NOT NULL,
  PRIMARY KEY  (`noteID`)
)") or die("Error:".mysql_error());
echo("<p>Done! Please proceed to the index and start taking notes!</p>");
}
?>
