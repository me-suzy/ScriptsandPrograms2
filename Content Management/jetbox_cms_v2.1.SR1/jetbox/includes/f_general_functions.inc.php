<?
// Create breadcrum trail
function addbstack($item, $name, $view=''){
	global $breadcrumstack, $absolutepathfull, $show_current_page_in_bctr;
	if ($breadcrumstack=='') {
		if($show_current_page_in_bctr){
			$breadcrumstack= " ".$name." ".$breadcrumstack;
		}
		else{
			$breadcrumstack= ' ';
		}
	}
	else{
		if ($view<>'' && $item<>'' ) {
			$breadcrumstack= " <a href=\"".$absolutepathfull."view/".$view."/item/".$item."\">".$name."</a> &gt; ".$breadcrumstack;
		}
		elseif ($view<>''){
			$breadcrumstack= " <a href=\"".$absolutepathfull."view/".$view."\">".$name."</a> &gt; ".$breadcrumstack;
		}
		elseif($item<>'') {
			$breadcrumstack= " <a href=\"".$absolutepathfull."item/".$item."\">".$name."</a> &gt; ".$breadcrumstack;
		}
		else{
			$breadcrumstack= " <a href=\"".$absolutepathfull."\">".$name."</a> &gt; ".$breadcrumstack;
		}
	}
}
// end func



// Content block item
function openitem($item){
	global $wfqadd,  $t, $primarray, $absolutepathfull, $absolutepath, $treestack, $sitename;
	if ($item){
		$treeq = "SELECT  opencontent.*, opencontent.id as opencontent_id FROM opencontent WHERE opencontent.id='".$item."'";
		$treer = mysql_prefix_query($treeq) or die(mysql_error());
		if ($treearray = mysql_fetch_array($treer)){
			$opent = new Template("./");
			$opent->set_var("absolutepathfull", $absolutepathfull);
			// Decode page content
			$decodedcontent = unserialize($treearray["content"]);
			$sqlselecttemplate = "SELECT *  FROM opentempl WHERE opentempl.id=".$treearray["t_id"];
			$resulttemplate = mysql_prefix_query ($sqlselecttemplate) or die (mysql_error());
			if ($resultarraytemplate = mysql_fetch_array($resulttemplate)){

				$opent->set_file("block", $resultarraytemplate["t_file"]);
				// Decode template data
				$decodedtemplateconfig = unserialize(stripslashes($resultarraytemplate["t_data"]));
				// field 0
				// namefield 2
				while (list($val, $var)= each($decodedtemplateconfig)){
					$opent->set_var(array ($var[0]=> stripslashes($decodedcontent[$var[0]]),$var[0]."name"=> $var[2]));
					// Extra subst parsing (phplib template function) for variable set in contents
					$opent->set_var($var[0], $opent->subst($var[0]));
					}
				$opent->parse("b", "block");
			}
			$content= $opent->get("b");
		}
	}
  return $content;
}

function top_nav(){
	global $wfqadd, $absolutepathfull, $view, $option, $item;
	$topnavt = new Template("./");
	$topnavt->set_file("block", "top_nav.html");	    
	$topnavt->set_block("block", "link", "linkz");
	$topnavt->set_var("absolutepathfull", $absolutepathfull);

	$sql2="SELECT * FROM navigation WHERE top_nav=1";
	$r2 = mysql_prefix_query($sql2) or die(mysql_error()." q: ".$sql2."<br /> Line: ".__LINE__." <br/>File: ".__FILE__);
	while($ra2 = mysql_fetch_array($r2)){
		if($ra2["nav_name"]=="*opentree*"){
			$treeq = "SELECT struct.*, opentree.*, struct.id AS structid FROM struct, opentree WHERE opentree.p_id='' ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id AND opentree.top_nav=1 ORDER BY position";
			$treer = mysql_prefix_query($treeq) or die(mysql_error());
			while ($treearray = mysql_fetch_array($treer)){
				$topnavt->set_var(array ("url"=> "item/".$treearray["structid"],"link_name"=> $treearray["nav_title"]));
				$topnavt->parse("linkz", "link", true);
			}
		}
		else{
			$link='';
			$link_add='';
			if($ra2["view_name"]<>''){
				$link.="view/".$ra2["view_name"];
				$link_add='/';
			}
			if($ra2["option_name"]<>''){
				$link.=$link_add."option/".$ra2["option_name"];
				$link_add='/';
			}
			if($ra2["item"]<>'' && $ra2["item"]<>0){
				$link.=$link_add."item/".$ra2["item"];
				$link_add='/';
			}
			$topnavt->set_var(array ("url"=> $link,"link_name"=> $ra2["nav_name"]));
			$topnavt->parse("linkz", "link", true);
		}
	}
	$topnavt->parse("b", "block", true);
	return 	$topnavt->get("b");
}

// opentree top navigation
function bot_nav(){
	global $wfqadd, $absolutepathfull, $view, $option, $item;
	$botnavt = new Template("./");
	$botnavt->set_file("block", "bot_nav.html");	    
	$botnavt->set_block("block", "link", "linkz");
	$botnavt->set_var("absolutepathfull", $absolutepathfull);

	$sql2="SELECT * FROM navigation WHERE bot_nav=1";
	$r2 = mysql_prefix_query($sql2) or die(mysql_error()." q: ".$sql2."<br /> Line: ".__LINE__." <br/>File: ".__FILE__);
	while($ra2 = mysql_fetch_array($r2)){
		if($ra2["nav_name"]=="*opentree*"){
			$treeq = "SELECT struct.*, opentree.*, struct.id AS structid FROM struct, opentree WHERE opentree.p_id='' ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id AND opentree.top_nav=1 ORDER BY position";
			$treer = mysql_prefix_query($treeq) or die(mysql_error());
			while ($treearray = mysql_fetch_array($treer)){
				$botnavt->set_var(array ("url"=> "item/".$treearray["structid"],"link_name"=> $treearray["nav_title"]));
				$botnavt->parse("linkz", "link", true);
			}
		}
		else{
			$link='';
			$link_add='';
			if($ra2["view_name"]<>''){
				$link.="view/".$ra2["view_name"];
				$link_add='/';
			}
			if($ra2["option_name"]<>''){
				$link.=$link_add."option/".$ra2["option_name"];
				$link_add='/';
			}
			if($ra2["item"]<>'' && $ra2["item"]<>0){
				$link.=$link_add."item/".$ra2["item"];
				$link_add='/';
			}
			$botnavt->set_var(array ("url"=> $link,"link_name"=> $ra2["nav_name"]));
			$botnavt->parse("linkz", "link", true);
		}
	}
	$botnavt->parse("b", "block", true);
	return 	$botnavt->get("b");
}


function check_email($email){
	$regexp = "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|museum|name))$";
	//$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$"; 
	if( eregi($regexp, $email) ) { 
		return true; 
	} 
	else { 
		return false; 
	}
}

/**
 * Allow these tags
 */
$allowedTags = '<h1><b><i><a><ul><li><pre><hr><blockquote>';

/**
 * Disallow these attributes/prefix within a tag
 */
$stripAttrib = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|'.
               'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|style|onload|onchange';

/**
 * @return string
 * @param string
 * @desc Strip forbidden tags and delegate tag-source check to removeEvilAttributes()
 */
function removeEvilTags($source){
   global $allowedTags;
   $source = strip_tags($source, $allowedTags);
   return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
}

/**
 * @return string
 * @param string
 * @desc Strip forbidden attributes from a tag
 */
function removeEvilAttributes($tagSource){
   global $stripAttrib;
   return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
}
?>