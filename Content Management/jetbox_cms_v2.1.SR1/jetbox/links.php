<?
addbstack('', 'Links', 'links');
addbstack('', 'Home');
$t->set_file("block", "main_tpl_no_nav.html");
$t->set_var("breadcrum", $breadcrumstack);
//output news for selected item

$sqlselect1 = "SELECT *, struct.id AS struct_id FROM plug_links, struct, links_cat WHERE struct.container_id='15' ".$wfqadd." AND struct.content_id=plug_links.id AND plug_links.p_id='' AND plug_links.cat_id=links_cat.cat_id ORDER BY links_cat.pos ASC";
$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
$linkscount= mysql_num_rows($result1);
if ($linkscount>'0') {
	$pluglinkst = new Template("./");
	$pluglinkst->set_file("block", "links_item_tpl.html");	    
	$pluglinkst->set_block("block", "links","linkz");
	$pluglinkst->set_block("block", "CAT","catz");

	while ($resultarray = mysql_fetch_array($result1)){
		$pluglinkst->set_var(array ("name"=> $resultarray["name"],"descrip"=> $resultarray["descrip"],"url"=> $resultarray["url"]));
		if($resultarray["cat_id"]<>$lastcat){
			$pluglinkst->set_var("cat", $resultarray["cat"]);
			$pluglinkst->parse("linkz", "CAT", true);
		}
		$lastcat=$resultarray["cat_id"];
		$pluglinkst->parse("linkz", "links", true);
	}
	$pluglinkst->parse("b", "block");

	$t->set_var("containera", $pluglinkst->get("b"));
}
else {
	$t->set_var("containera", "No links.");
}

$t->set_var("itemtitle", "Links");		    
?>