<?
//opentree
opentree($item);
addbstack('', 'Home');
$t->set_var("breadcrum", $breadcrumstack);



//opentree content
function opentree($item){
	global $wfqadd,  $t, $primarray, $absolutepathfull, $absolutepath, $treestack, $sitename, $eval_result;
	$t->set_file("block", "main_tpl.html");
	if ($item){
		backtrackopentree($item);
		$t->set_var("breadcrum", $treestack);
		$treeq = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.id=struct.content_id ".$wfqadd." AND struct.container_id=11 AND struct.id=".$item;
		$treer = mysql_prefix_query($treeq) or die(mysql_error());
		if ($treearray = mysql_fetch_array($treer)){
			if ($treearray['left_nav']=='0') {
				$t->set_file("block", "main_tpl_no_nav.html");
			}
			$opent = new Template("./");
			// decode page content
			$decodedcontent = unserialize($treearray["content"]);

			$sqlselecttemplate = "SELECT *  FROM opentempl WHERE opentempl.id='".$treearray["t_id"]."'";
			$resulttemplate = mysql_prefix_query ($sqlselecttemplate) or die (mysql_error());
			if ($resultarraytemplate = mysql_fetch_array($resulttemplate)){
				$opent->set_file("block", $resultarraytemplate["t_file"]);
				// decode template data
				$decodedtemplateconfig = unserialize(stripslashes($resultarraytemplate["t_data"]));
				// field 0
				// namefield 2
				while (list($val, $var)= each($decodedtemplateconfig)){
					$opent->set_var(array ($var[0]=> stripslashes($decodedcontent[$var[0]]),$var[0]."name"=> $var[2]));
				}
				$opent->set_var(array ("pagetitle"=> $treearray["page_title"]));
				$opent->parse("b", "block");
			}
			// Navigation
			$treecq = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.p_id=".$treearray["opentree_id"]." ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id  ORDER BY position ASC";
			$treecr = mysql_prefix_query($treecq) or die(mysql_error());
			$treeccount = mysql_num_rows($treecr);
			if ($treeccount>0){
				while ($treecarray = mysql_fetch_array($treecr)){
					$nav.="<a href=\"".$absolutepathfull."item/".$treecarray["structid"]."\">".$treecarray["nav_title"]."</a><br>";
				}
			}
		}
		$t->set_var("itemtitle", $treearray["page_title"]);
		$t->set_var("pagetitle", $sitename." - ".$treearray["page_title"]);
		$t->set_var("pagekeywords", $treearray["keywords"]);
		$t->set_var("pagedescription", $treearray["description"]);
		$t->set_var("robots", $treearray["robot"]);
		$t->set_var("leftnav", $nav);
		
		$content=$opent->get("b");
		$t->set_var("containera",$content);
	}
}
function html2specialchars($str){
   $trans_table = array_flip(get_html_translation_table(HTML_ENTITIES));
   return strtr($str, $trans_table);
}
function function_date($a){
	global $eval_result;
	//echo "Asdasdas";
	$eval_result=date("d-M-Y");
}

//Used to track back the full hierarchy of the currently displayed tree page
//current item and back
function backtrackopentree($item){
	global $wfqadd,  $treestack, $absolutepathfull, $breadcrumstack;
	$treeq = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.id=struct.content_id ".$wfqadd." AND struct.container_id=11 AND struct.id=".$item;
	$treer = mysql_prefix_query($treeq) or die(mysql_error());
	if ($treearray = mysql_fetch_array($treer)){
		addbstack($treearray["structid"], $treearray["nav_title"]);
		$treecq = "SELECT struct.*, opentree.*, struct.id AS structid, opentree.id as opentree_id FROM struct, opentree WHERE opentree.id=".$treearray["p_id"]." ".$wfqadd." AND struct.container_id=11 AND struct.content_id=opentree.id";
		$treecr = mysql_prefix_query($treecq) or die(mysql_error());
		$treeccount = mysql_num_rows($treecr);
		if ($treecarray = mysql_fetch_array($treecr)){
			if($treecarray["p_id"]<>''){
				backtrackopentree($treecarray["structid"]);
			}
		}
	}
}
?>