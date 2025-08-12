<?php
require("includes/global.php");

// get limit or disable stuff
$limit = intval($config["recentlyadded"]);
if ($limit == 0){ redirect($config["topicpage"]); }

// get recordset
$sql = "SELECT * FROM " . $dbprefix . "links ORDER BY postdate DESC LIMIT 0, " . $limit;
$nuw = $db->execute($sql);

// setup the variables
$bread = '<a href="' . $config["topicpage"] . '">' . $phrase["top"] . '</a> ' . $config["breadcrumb"] . ' <a href="recent.php">' . $phrase["recentlyadded"] . '</a>';
$pagetitle = $config["sitename"] . " - " . $phrase["recentlyadded"];

// page header
include("includes/page_header.php");
?>
<?php
$dat = date("j F Y", 0);
$row = 0;
if ($nuw->rows > 0){ do {
$ndat = date("j F Y", $nuw->fields["postdate"]);
if ($ndat <> $dat){
	if ($row > 0){ echo("</ul>\n"); }
	echo($ndat . "\n<ul>\n");
	$dat = $ndat;
}
?>
	<li><a href="<?=$nuw->fields["url"]?>"><?=$nuw->fields["website"]?></a> - <?=$nuw->fields["description"]?></li>
<?php
$row++;
} while ($nuw->loop());
?>
</ul>
<?php } // end show links check thing ?>
<?php
include("includes/page_footer.php");
?>