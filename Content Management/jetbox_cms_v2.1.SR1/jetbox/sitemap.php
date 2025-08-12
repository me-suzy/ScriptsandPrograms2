<?
addbstack('', 'Sitemap', 'sitemap');
addbstack('', 'Home');
$t->set_file("block", "main_tpl_no_nav.html");
$t->set_var("breadcrum", $breadcrumstack);

$sitemapt = new Template("./");
$sitemapt->set_file("block", "sitemap_items_tpl.html");	    
$sitemapt->set_block("block", "blok0", "blok0z");
$sitemapt->set_block("block", "seblok", "seblokz");
$sitemapt->set_block("block", "s1blok", "s1blokz");
$sitemapt->set_block("block", "s2blok", "s2blokz");
$sitemapt->set_block("block", "s3blok", "s3blokz");
$sitemapt->set_var("absolutepathfull", $absolutepathfull);

$treeq = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.p_id='' ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id";
$treer = mysql_prefix_query($treeq) or die(mysql_error());
while ($treearray = mysql_fetch_array($treer)){
	$sitemapt->set_var("item0", $treearray["nav_title"]);
	$sitemapt->set_var("item", "Overview");
	$sitemapt->set_var("url", $absolutepathfull."item/".$treearray["structid"]);
	$sitemapt->parse("s1blokz", "seblok");
	$treeq2 = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.p_id=".$treearray["opentree_id"]." ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id";
	$treer2 = mysql_prefix_query($treeq2) or die(mysql_error());
	while ($treearray2 = mysql_fetch_array($treer2)){
		$sitemapt->set_var("item", $treearray2["nav_title"]);
		$sitemapt->set_var("url", $absolutepathfull."item/".$treearray2["structid"]);
		$sitemapt->parse("s1blokz", "s1blok", true);
		$treeq3 = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.p_id=".$treearray2["opentree_id"]." ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id";
		$treer3 = mysql_prefix_query($treeq3) or die(mysql_error());
		while ($treearray3 = mysql_fetch_array($treer3)){
			$sitemapt->set_var("item", $treearray3["nav_title"]);
			$sitemapt->set_var("url", $absolutepathfull."item/".$treearray3["structid"]);
			$sitemapt->parse("s1blokz", "s2blok", true);
			$treeq4 = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.p_id=".$treearray3["opentree_id"]." ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id";
			$treer4 = mysql_prefix_query($treeq4) or die(mysql_error());
			while ($treearray4 = mysql_fetch_array($treer4)){
				$sitemapt->set_var("item", $treearray4["nav_title"]);
				$sitemapt->set_var("url", $absolutepathfull."item/".$treearray4["structid"]);
				$sitemapt->parse("s1blokz", "s3blok", true);
			}
		}
	}
	$sitemapt->parse("blok0z", "blok0");
	$sitemapt->parse("b", "block", true);
}
$t->set_var("containera", $sitemapt->get("b"));
$t->set_var("itemtitle", "Sitemap");		    
?>