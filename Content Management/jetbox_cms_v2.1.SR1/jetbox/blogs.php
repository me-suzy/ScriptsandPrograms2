<?
// CONFIGURATION
// Container ID
// After you have created the administration container in Jetbox check the container ID in the overview list

$container_id=35;
$date=date("Y-m-d");

if(!is_numeric($container_id)){
	echo "Set the \$container_id to the appropriate value";
	exit;
}
addbstack('', 'Blog', $view);
addbstack('', 'Home');
$t->set_file("block", "main_tpl.html");
$t->set_var("breadcrum", $breadcrumstack);
$t->set_var("itemtitle", "Blog");
$t->set_var("pagetitle", $sitename." - Blog");

//output news for selected item

$thisfile= $absolutepathfull."view/blog/item/".$item."";
$primarykey="c_id";
//table name
// NOTICE: if applicable, in order to work correctly remove the table prefix!
$tablename="blog_comments";
//ability to add an item [true|false]
$addnewitemoption=true;
//ability to delete an item [true|false]
$deleteitemoption=false;
//ability to edit an item [true|false]
$edititemoption=false;
$floodstop=true; //enable anti flood
$floodstop_time=61; //seconds

$records=array(
	array("c_id","hidden","C_id","","",""),
	array("blog_id","hidden","C_id","","",""),
	array("name","string","Name","required","",""),
	array("email","string","Email","","",""),
	//array("web_link","string","Url","","",""),
	array("comment","blob_max_length","Comment","required","",""),
	array("date","hidden","Date","",true,""),
);
function listrecords($error='', $blurbtype='notify'){general_form('create');};


//$c=openitem(29).errorbox($flood_error,"",'return');

function on_after_process(){
	$today = date('Y-m-d H:i:s', time());
	mysql_prefix_query("INSERT INTO mailspamstop VALUES ('".$_SERVER['REMOTE_ADDR']."', '$today')");
};

function on_before_process(){
	global $floodstop_time, $flood_error;
	$todaylock = date('Y-m-d H:i:s', (time()-$floodstop_time));
	$spamresult = mysql_prefix_query("SELECT * FROM mailspamstop WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND time>'$todaylock'") or die(mysql_error());
	if (mysql_num_rows($spamresult)>0) {
		return "No more than one message a minute please.";
	}
}	



if ($item<>'' && is_numeric($item)) {
   $sqlselect1 = "SELECT *, struct.id AS struct_id FROM blog, struct WHERE struct.container_id=".$container_id." ".$wfqadd." AND struct.content_id=blog.b_id AND struct.id=".$item; 
}
elseif($option=='last10'){
	$sqlselect1 = "SELECT *, struct.id AS struct_id FROM blog, struct WHERE struct.container_id=".$container_id." ".$wfqadd." AND struct.content_id=blog.b_id ORDER BY blog.b_id DESC LIMIT 10";
}
else{
	$sqlselect1 = "SELECT *, struct.id AS struct_id FROM blog, struct WHERE struct.container_id=".$container_id." ".$wfqadd." AND struct.content_id=blog.b_id ORDER BY blog.b_id DESC";
}

$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
$blogscount= mysql_num_rows($result1);
if ($blogscount>'0') {
	$view_tpl = new Template("./");
	$view_tpl->set_file("block", "blogs_item_tpl.html");	    
	$view_tpl->set_block("block", "blogs","blogsz");
	$view_tpl->set_var(array("absolutepathfull"=>$absolutepathfull ));

	while ($resultarray = mysql_fetch_array($result1)){
		$records[1][5]=$resultarray["b_id"];
		ob_start();
		loggedin_workflow();
		$containera = ob_get_contents(); 
		ob_end_clean();

		$splitdate = split('-',$resultarray["date"],3);
		$resultarray["date"]= substr($splitdate[2],"0", "2") ."/". $splitdate[1] ."/". $splitdate[0]."";
		
		if($resultarray["keywords"]<>''){
			$t->set_var("pagekeywords", $resultarray["keywords"]);
		}
		if($resultarray["description"]<>''){
			$t->set_var("pagedescription", $resultarray["description"]);
		}
		if($resultarray["robot"]<>''){
			$t->set_var("robots", $resultarray["robot"]);
		}


		$view_tpl->set_var("id", $resultarray["id"]);
		$view_tpl->set_var("name", $resultarray["name"]);
		$view_tpl->set_var("title", $resultarray["title"]);
		$view_tpl->set_var("brood", $resultarray["brood"]);
		$view_tpl->set_var("date", $resultarray["date"]);
		if($item==''){
			$view_tpl->set_var("comments", "<a href=\"view/blog/item/". $resultarray["struct_id"]."\">Comments</a>");
		}

		$id=$resultarray["b_id"];
		$view_tpl->parse("blogsz", "blogs", true);
	}
	$view_tpl->parse("b", "block");
	$t->set_var("containera", $view_tpl->get("b"));
	if ($item<>'' && is_numeric($item)) {
		//$t->set_var("containera", "add comments", true);
		$sqlselect1 = "SELECT * FROM blog_comments WHERE blog_id=".$id." ORDER BY blog_comments.c_id ASC";

		$result1 = mysql_prefix_query($sqlselect1) or die (mysql_error());
		$blog_commentcount= mysql_num_rows($result1);
		if ($blog_commentcount>'0') {
			$view_tpl2 = new Template("./");
			$view_tpl2->set_file("block", "blog_comment_item_tpl.html");	    
			$view_tpl2->set_block("block", "blog_comment","blog_commentz");
			$view_tpl2->set_var(array("absolutepathfull"=>$absolutepathfull ));
			$view_tpl2->set_var("num", mysql_num_rows($result1));

			while ($resultarray2 = mysql_fetch_array($result1)){
				//$view_tpl2->set_var("c_id", $resultarray2["c_id"]);
				//$view_tpl2->set_var("blog_id", $resultarray2["blog_id"]);
				$resultarray2["name"]=removeEvilTags(removeEvilTags(removeEvilTags($resultarray2["name"])));
				$resultarray2["email"]=removeEvilTags(removeEvilTags(removeEvilTags($resultarray2["email"])));
				$resultarray2["comment"]=removeEvilTags(removeEvilTags(removeEvilTags($resultarray2["comment"])));


				$view_tpl2->set_var("name", $resultarray2["name"]);
				if($resultarray2["email"]<>''){
					$view_tpl2->set_var("email","<a href=\"mailto:".$resultarray2["email"]."\">Mail</a>&nbsp;");
				}
				//if($resultarray2["url"]<>''){
				//	$view_tpl2->set_var("url", "<a href=\"".$resultarray2["url"]."\">Website</a>");
				//}
				$view_tpl2->set_var("comment", $resultarray2["comment"]);
				$splitdate = split('-',$resultarray2["date"],3);
				$resultarray2["date"]= substr($splitdate[2],"0", "2") ."/". $splitdate[1] ."/". $splitdate[0]."";

				$view_tpl2->set_var("date", $resultarray2["date"]);
				$view_tpl2->parse("blog_commentz", "blog_comment", true);
			}
			$view_tpl2->parse("b", "block");
			$t->set_var("containera", $view_tpl2->get("b"), true);
		}
		$t->set_var("containera", '<div style="margin:0px 10px 20px 0px;padding:4px 10px 10px 0px;">'.$containera.'</div>', true);
	}
}
else {
	$t->set_var("containera", "No Blogs found.");
}



$navt = new Template("./");
$navt->set_file("block", "optionnav.html");	    
$navt->set_block("block", "sub","subz");
$navt->set_block("block", "subsel","subselz");
$navarray=array("all"=>array("url"=>$absolutepathfull."view/".$view."/","lname"=>"All"),
								"past"=>array("url"=>$absolutepathfull."view/".$view."/option/last10","lname"=>"Last 10"),
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
$navt->set_var(array ("options"=> "Selection"));
$navt->parse("b", "block");
$t->set_var("leftnav", $navt->get("b")."<div style=\"border: solid #c40;border-width:1px;background-color:#f90;width:35px;padding-left:4px\"><a href=\"view/rss\" style=\"color:#fff\">RSS</a></div>");
?>