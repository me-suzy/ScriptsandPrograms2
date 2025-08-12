<?
addbstack('', 'Downloads', 'outputs');
addbstack('0', 'Home', '');
$t->set_file("block", "main_tpl.html");
$t->set_var("breadcrum", $breadcrumstack);
//output news for selected item

$newst = new Template("./");
$newst->set_file("block", "outputs_item_tpl.html");	    
$newst->set_block("block", "outputs","outputz");
$newst->set_block("block", "outputsall","outputallz");
$newst->set_block("block", "outputslong","outputlongz");

$newst->set_block("block", "month","monthz");
$newst->set_block("outputs", "OUTPUTLINK", "OUTPUTLINKZ");

$newst->set_block("news", "AUTHOR");
$newst->set_var("absolutepathfull", $absolutepathfull);


//
function output($resultarray){
	global $t, $newst, $absolutepathfull, $absolutepath;
	$newst->set_var("date", date("d-M-Y",$resultarray["udate"]));
	$newst->set_var("summary", $resultarray["summary"]);
	$newst->set_var("url", $absolutepathfull."view/outputs/item/".$resultarray["struct_id"]);
	$newst->set_var("filename", "/webfiles/".$resultarray["filename"]);
	$newst->set_var(array ("author"=> $resultarray["author"]));
	$newst->set_var(array ("title"=> $resultarray["title"],"date"=> date("d-M-Y",$resultarray["udate"]),"brood"=> $resultarray["brood"]));

	if ($resultarray["filename"]==''){
		$newst->set_var("OUTPUTLINKZ", "");
	}
	else{
		$newst->parse("OUTPUTLINKZ", "OUTPUTLINK");
	}
	if ($item<>'') {
		$newst->parse("outputz", "outputslong", true);
	}
	else{
		$newst->parse("outputz", "outputs", true);
	}
} // end func


if ($option=='all') {
	$sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_outputs.date) AS udate FROM plug_outputs, struct WHERE struct.container_id='5' ".$wfqadd." AND struct.content_id=plug_outputs.id ORDER BY plug_outputs.date DESC";
}
elseif ($item<>'') {
	 $sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_outputs.date) AS udate FROM plug_outputs, struct WHERE struct.container_id='5' ".$wfqadd." AND struct.content_id=plug_outputs.id AND struct.id='$item'"; 
}
else{
	$sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_outputs.date) AS udate FROM plug_outputs, struct WHERE struct.container_id='5' ".$wfqadd." AND struct.content_id=plug_outputs.id ORDER BY plug_outputs.date DESC LIMIT 5";
}

$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
$newscount= mysql_num_rows($result1);
if ($newscount>'0') {
	while ($resultarray = mysql_fetch_array($result1)){
		$t->set_var("pagekeywords", $resultarray["keywords"]);
		$t->set_var("pagedescription", $resultarray["description"]);
		$t->set_var("robots", $resultarray["robot"]);
		$newst->set_var("date", date("d-M-Y",$resultarray["udate"]));
		$newst->set_var("summary", $resultarray["summary"]);
		$newst->set_var("url", $absolutepathfull."view/outputs/item/".$resultarray["struct_id"]);
		$newst->set_var("filename", "webfiles/".$resultarray["filename"]);
		$newst->set_var(array ("author"=> $resultarray["author"]));
		$newst->set_var(array ("title"=> $resultarray["title"],"date"=> date("d-M-Y",$resultarray["udate"]),"brood"=> $resultarray["brood"]));
		if ($option<>'all') {
			if ($lastdate=='' && $item=='') {
				$newst->set_var("MONTH", date("F Y",$resultarray["udate"]));
				$newst->parse("outputz", "month", true);
			}
			elseif(date("m",$lastdate)<>date("m",$resultarray["udate"])  && $item==''){
				$newst->set_var("MONTH", date("F Y",$resultarray["udate"]));
				$newst->parse("outputz", "month", true);
			}
			$lastdate=$resultarray["udate"];
		}
		elseif($headerprinted<>true){
			$newst->set_var("MONTH", 'All');
			$newst->parse("outputz", "month", true);
			$headerprinted=true;
		}
		if ($resultarray["filename"]==''){
			$newst->set_var("OUTPUTLINKZ", "");
		}
		else{
			$newst->parse("OUTPUTLINKZ", "OUTPUTLINK");
		}
		if ($item<>'') {
			$newst->parse("outputz", "outputslong", true);
		}
		else{
			$newst->parse("outputz", "outputs", true);
		}
	}
	$newst->parse("b", "block");
	$t->set_var("containera", $newst->get("b"));

}
else{
	$t->set_var("containera", "No downloads available.");
}   

$navt = new Template("./");
$navt->set_file("block", "optionnav.html");	    
$navt->set_block("block", "sub","subz");
$navt->set_block("block", "subsel","subselz");
$navarray=array(""=>array("url"=>$absolutepathfull."view/outputs/","lname"=>"Most recent"),
								"all"=>array("url"=>$absolutepathfull."view/outputs/option/all","lname"=>"All"),
								);
if ($option) {
		while (list($key, $val)= each($navarray)) {
		$navt->set_var(array ("lname"=> $val["lname"],"url"=>$val["url"]));
		if ($key==$option) {
			$navt->parse("subz", "subsel", true);			    
		}
		else{
			$navt->parse("subz", "sub", true);
		}
	}
}
else{
	while (list($key, $val)= each($navarray)) {
		$navt->set_var(array ("lname"=> $val["lname"],"url"=>$val["url"]));
		if ($key=="" && $item=='') {
			$navt->parse("subz", "subsel", true);			    
		}
		else{
			$navt->parse("subz", "sub", true);
		}
	}
}
$t->set_var("itemtitle", "Downloads");		    
$t->set_var("pagetitle", $sitename." - Downloads");
$navt->set_var(array ("options"=> "Selection"));
$navt->parse("b", "block");
$t->set_var("leftnav", $navt->get("b"));
?>