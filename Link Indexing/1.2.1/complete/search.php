<?php
require("includes/global.php");

// check for search
if ($_GET["q"] <> ""){
	// run a search type thing
	$st = dbSecure($_GET["q"]);
	
	// search for topics
	$limit = intval($config["topicresults"]);
	$sql  = "SELECT *, MATCH (title, keywords) ";
	$sql .= "AGAINST ('" . $st . "' IN BOOLEAN MODE) AS score ";
	$sql .= "FROM " . $dbprefix . "topics ";
	$sql .= "WHERE MATCH (title, keywords) AGAINST ";
	$sql .= "('" . $st . "' IN BOOLEAN MODE) ORDER BY score DESC ";
	$sql .= "LIMIT 0, " . $limit;
	$top  = $db->execute($sql);
	
	// search for websites
	$sql  = "SELECT *, MATCH (website, description, url) ";
	$sql .= "AGAINST ('" . $st . "' IN BOOLEAN MODE) AS score ";
	$sql .= "FROM " . $dbprefix . "links ";
	$sql .= "WHERE MATCH (website, description, url) AGAINST ";
	$sql .= "('" . $st . "' IN BOOLEAN MODE) ORDER BY score DESC";
	
	// run the command
	$rec = $db->execute($sql);
}

// setup the variables
$bread = '<a href="' . $config["topicpage"] . '">' . $phrase["top"] . '</a> ' . $config["breadcrumb"] . ' <a href="search.php">' . $phrase["search"] . '</a>';
$pagetitle = $config["sitename"] . " Search";

// page header
include("includes/page_header.php");
?>

<form action="search.php" method="get" id="f" name="f">
<label for ="q"></label>
<input type="text" id="q" name="q" size="30" maxlength="255" value="<?=un(htmlspecialchars($st))?>" />
<input type="submit" value="<?=$phrase["search"]?>!" />
</form>

<script language="JavaScript" type="text/javascript">
window.onload = document.forms.f.q.focus();
</script>

<?php if ($st <> ""){ ?>
<?php if ($top->rows > 0){ // topics results check ?>
<strong><?=$phrase["topic"]?> <?=$phrase["results"]?></strong>
<ul>
	<?php do { ?>
	<li><?=fetchtopic($top->fields["topicid"])?></li>
	<?php } while ($top->loop()); ?>
</ul>
<?php } // end topics results check ?>

<?php if ($rec->rows < 1){ ?>
<strong><?=$phrase["search"]?> <?=$phrase["results"]?></strong><br />
No results were found for this search term, please try another
<?php } else { ?>

<strong><?=$phrase["search"]?> <?=$phrase["results"]?></strong><br />
<?=$phrase["resultspages"]?>: <?php
$perpage = 10;
$pnum = ceil($rec->rows / $perpage);
$pqu = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$x = 1;
while ($x <= $pnum){
	if ($x == $pqu){
		echo(" <span>" . $x . "</span>");
	} elseif ($x > ($pqu - 5) && $x < ($pqu + 5)) {
		echo(' <a href="./?q=' . urlencode($_GET["q"]) . '&amp;page=' . $x . '">' . $x . '</a>');
	} elseif ($x == ($pqu - 5)){
		echo(' <a href="./?q=' . urlencode($_GET["q"]) . '&amp;page=1">&laquo;</a>');
	} elseif ($x == ($pqu + 5)){
		echo(' <a href="./?q=' . urlencode($_GET["q"]) . '&amp;page=' . $pnum . '">&raquo;</a>');
	}
	
	// and loop thing
	$x = ($x + 1);
}

// count results forward
$ic = ($perpage * ($pqu - 1));
while ($ic > 0){
	$res->fields = $res->loop();
	$ic = ($ic - 1);
}

$rcount = 0;
do {
$rcount = ($rcount + 1);
?>
<br /><br />
<strong><a href="<?=$rec->fields["url"]?>"><?=$rec->fields["website"]?></a></strong><br />
<?=$rec->fields["description"]?><br />
<span class="smalltext"><?=fetchtopic($rec->fields["topicid"])?></span>
<?php } while (($rec->fields = $rec->loop()) && ($rcount < $perpage)); ?>

<?php } } ?>

<?php
include("includes/page_footer.php");
?>