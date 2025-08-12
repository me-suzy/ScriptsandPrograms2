<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function fill_icons($tableprefix, $db)
{
$arr = array(
"VALUES (1, 'archive.gif');",
"VALUES (2, 'bell.gif');",
"VALUES (3, 'blue_dude.gif');",
"VALUES (4, 'bulb.gif');",
"VALUES (5, 'bulb2.gif');",
"VALUES (6, 'calendar.gif');",
"VALUES (7, 'cd.gif');",
"VALUES (8, 'debug.gif');",
"VALUES (9, 'delete.gif');",
"VALUES (10, 'disk.gif');",
"VALUES (11, 'flying_envelope.gif');",
"VALUES (12, 'folder.gif');",
"VALUES (13, 'hand.gif');",
"VALUES (14, 'info1.gif');",
"VALUES (15, 'info2.gif');",
"VALUES (16, 'info3.gif');",
"VALUES (17, 'info4.gif');",
"VALUES (18, 'info5.gif');",
"VALUES (19, 'info6.gif');",
"VALUES (20, 'lightning.gif');",
"VALUES (21, 'news1.gif');",
"VALUES (22, 'news2.gif');",
"VALUES (23, 'news3.gif');",
"VALUES (24, 'note.gif');",
"VALUES (25, 'person.gif');",
"VALUES (26, 'pim1.gif');",
"VALUES (27, 'pim2.gif');",
"VALUES (28, 'qmark.gif');"
);

for($i = 0; $i< count($arr); $i++)
{
	$sql = "INSERT INTO ".$tableprefix."_icons ";
	$sql .=$arr[$i];
	if(!$result = mysql_query($sql, $db))
		die("Unable to insert data into ".$tableprefix."_icons ($i)<br>".mysql_error());
}
}
?>