<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function fill_emoticons($tableprefix, $db)
{
$arr = array(
"VALUES (1,':D','icon_biggrin.gif','Very Happy');",
"VALUES (2,':-D','icon_biggrin.gif','Very Happy');",
"VALUES (3,':grin:','icon_biggrin.gif','Very Happy');",
"VALUES (4,':)','icon_smile.gif','Smile');",
"VALUES (5,':-)','icon_smile.gif','Smile');",
"VALUES (6,':smile:','icon_smile.gif','Smile');",
"VALUES (7,':(','icon_frown.gif','Sad');",
"VALUES (8,':-(','icon_frown.gif','Sad');",
"VALUES (9,':sad:','icon_frown.gif','Sad');",
"VALUES (10,':o','icon_eek.gif','Surprised');",
"VALUES (11,':-o','icon_eek.gif','Surprised');",
"VALUES (12,':eek:','bluebigeek.gif','Very Suprised');",
"VALUES (13,':-?','icon_confused.gif','Confused');",
"VALUES (14,':???:','icon_confused.gif','Confused');",
"VALUES (15,'8)','icon_cool.gif','Cool');",
"VALUES (16,'8-)','icon_cool.gif','Cool');",
"VALUES (17,':cool:','icon_cool.gif','Cool');",
"VALUES (18,':lol:','icon_lol.gif','Laughing');",
"VALUES (19,':x','icon_mad.gif','Mad');",
"VALUES (20,':-x','icon_mad.gif','Mad');",
"VALUES (21,':mad:','icon_mad.gif','Mad');",
"VALUES (22,':P','icon_razz.gif','Razz');",
"VALUES (23,':-P','icon_razz.gif','Razz');",
"VALUES (24,':razz:','icon_razz.gif','Razz');",
"VALUES (25,':oops:','blueembarrassed.gif','Embaressed');",
"VALUES (26,':cry:','icon_cry.gif','Crying (very sad)');",
"VALUES (27,':upset:','blueupset.gif','Very Upset');",
"VALUES (28,':roll:','icon_rolleyes.gif','Rolling Eyes');",
"VALUES (29,':wink:','icon_wink.gif','Wink');",
"VALUES (30,';)','icon_wink.gif','Wink');",
"VALUES (31,';-)','icon_wink.gif','Wink');",
"VALUES (32,':sleep:','bluesleep.gif','Very Bored');",
"VALUES (33,':sigh:','bluesigh.gif','Sigh');",
"VALUES (34,':no:','blueno.gif','No');",
"VALUES (35,':yes:','blueyes.gif','Yes');",
"VALUES (36,':dead:','bluedead.gif','Dead');",
);

for($i = 0; $i< count($arr); $i++)
{
	$sql = "INSERT INTO ".$tableprefix."_emoticons ";
	$sql .=$arr[$i];
	if(!$result = mysql_query($sql, $db))
		die("Unable to insert data into ".$tableprefix."_emoticons ($i)<br>".mysql_error());
}
}
?>