<?
addbstack('', 'News', 'news');
addbstack('', 'Home');
$t->set_file("block", "main_tpl.html");
$t->set_var("breadcrum", $breadcrumstack);
//output news for selected item

$date=date("Y-m-d");
if ($item<>'') {
   $sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_news.date) AS udate FROM plug_news, struct WHERE struct.container_id='14' ".$wfqadd." AND struct.content_id=plug_news.id AND struct.id='$item'"; 
}
if ($option=='past') {
	$sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_news.date) AS udate FROM plug_news, struct WHERE struct.container_id='14' ".$wfqadd." AND struct.content_id=plug_news.id AND plug_news.date<'$date' ORDER BY plug_news.date DESC LIMIT 10";
}
else{
	$sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_news.date) AS udate FROM plug_news, struct WHERE struct.container_id='14' ".$wfqadd." AND struct.content_id=plug_news.id ORDER BY date DESC LIMIT 10";
}


$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());

$newscount= mysql_num_rows($result1);
if ($newscount>'0') {
	$newst = new Template("./");
	$newst->set_file("block", "news_item_tpl.html");	    
	$newst->set_block("block", "news","newz");
	$newst->set_block("block", "month","monthz");
	$newst->set_block("news", "AUTHOR");
	$newst->set_var("absolutepathfull", $absolutepathfull);

	while ($resultarray = mysql_fetch_array($result1)){
		$t->set_var("pagekeywords", $resultarray["keywords"]);
		$t->set_var("pagedescription", $resultarray["description"]);
		$t->set_var("robots", $resultarray["robot"]);
		if ($resultarray["author"]<>''){
			$newst->set_var(array ("authorname"=> $resultarray["author"]));
		}
		else{
			$newst->set_var("AUTHOR", "");
		}
		$newst->set_var(array ("title"=> $resultarray["title"],"date"=> date("d-M-Y",$resultarray["udate"]),"brood"=> $resultarray["brood"]));
		if ($lastdate=='' && $item=='') {
			$newst->set_var("MONTH", date("F",$resultarray["udate"]));
			$newst->parse("newz", "month", true);
		}
		elseif(date("m",$lastdate)<>date("m",$resultarray["udate"])  && $item==''){
			$newst->set_var("MONTH", date("F",$resultarray["udate"]));
			$newst->parse("newz", "month", true);
		}
		$lastdate=$resultarray["udate"];
		$newst->parse("newz", "news", true);
	}
	$newst->parse("b", "block");
	$t->set_var("containera", $newst->get("b"));

}
else{
	$t->set_var("containera", "No news available.");
}

$navt = new Template("./");
$navt->set_file("block", "optionnav.html");	    
$navt->set_block("block", "sub","subz");
$navt->set_block("block", "subsel","subselz");
$navarray=array("all"=>array("url"=>$absolutepathfull."view/news/","lname"=>"Latest"),
								"past"=>array("url"=>$absolutepathfull."view/news/option/past","lname"=>"Past"),
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
		if ($key=="all" && $item=='') {
			$navt->parse("subz", "subsel", true);			    
		}
		else{
			$navt->parse("subz", "sub", true);
		}
	}
}

$t->set_var("itemtitle", "News");		    
$t->set_var("pagetitle", $sitename." - News");
$navt->set_var(array ("options"=> "Selection"));
$navt->parse("b", "block");
$t->set_var("leftnav", $navt->get("b"));
?>