<?
addbstack('', 'Contact', 'contact');
addbstack('', 'Home');
$t->set_file("block", "main_tpl_no_nav.html");
$t->set_var("breadcrum", $breadcrumstack);
$sqlselect1 = "SELECT *, struct.id AS struct_id FROM plug_contact, struct WHERE struct.container_id='16' ".$wfqadd." AND struct.content_id=plug_contact.id AND plug_contact.p_id='".$primarray["struct_id"]."'";
$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
$newscount= mysql_num_rows($result1);
if ($newscount>'0') {
	$plugnewst = new Template("./");
	$plugnewst->set_file("block", "contact_item_tpl.html");	    
	$plugnewst->set_block("block", "contact","contactz");
	$plugnewst->set_block("contact", "FUNCTION");
	$plugnewst->set_block("contact", "URL");
	$plugnewst->set_block("contact", "INSTITUTE");
	while ($resultarray = mysql_fetch_array($result1)){
		$t->set_var("pagekeywords", $resultarray["keywords"]);
		$t->set_var("pagedescription", $resultarray["description"]);
		$t->set_var("robots", $resultarray["robot"]);
		$plugnewst->set_var(array ("name"=> $resultarray["name"],"address"=> $resultarray["address"],"url"=> $resultarray["url"],"institute"=> $resultarray["institute"],"phone"=> $resultarray["phone"],"email"=> $resultarray["email"],"function"=> $resultarray['function']));
		if ($resultarray['function']==''){
			$plugnewst->set_var("FUNCTION", "");
		}
		if ($resultarray["url"]==''){
			$plugnewst->set_var("URL", "");
		}
		else{
			$plugnewst->set_var("INSTITUTE", "");
		}
		$plugnewst->parse("contactz", "contact", true);
	}
	$plugnewst->parse("b", "block");
	$t->set_var("containera", $plugnewst->get("b"));
}
else {
	$t->set_var("containera", "No Contacts.");
}
$t->set_var("itemtitle", "Contact");		    
?>