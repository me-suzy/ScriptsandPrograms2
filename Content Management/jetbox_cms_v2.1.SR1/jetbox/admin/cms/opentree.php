<?
//primarykey of the table
$primarykey="id";
//parent field of the table
$parent_field="p_id";
//table name
$tablename="opentree";
//title of the administration page
$pagetitle="Pages";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
$mailuid= @mysql_result($result,0,'uid');
//workflow support true||flase
$wf=true;
//group list by some row or other table row
$listgroup=false;
//ability to show list of items [true|false]
$overviewitemoption=true;
//ability to add an item [true|false]
$addnewitemoption=true;
//ability to delte an item [true|false]
$deleteitemoption=true;
//depending on templates [true|false]
$templateenabled=true;
//Items parent changeable [true|false]
$changeparentenabled=true;
//Show archive tab[true|false]
$archiveenabled=false;

// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("id","hidden","id",""),
	array("p_id","valuefromurlshowlinked","Parent",""),
	array("page_title","string","Title",""),
	array("nav_title","string","Navigation name","required"),
	array("content","novalue","Content",""),
	array("t_id","valuefromurl","Template","required"),
	array("position","string10","Position",""),
	array("linkdoc","hidden","Link page",""),
	array("v_id","hidden","Page to link",""),
	array("left_nav","radioonerezo","Left navigation",""),
);

// format: fieldname,fieldtype,display text
$listformat=array(
	array("nav_title", "editlink","Short title")
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(
	array("", "",""),
);
// format: table,where clause for group query, where clause for records
$groupsql=array("", "", "");


// format:  dropdownfieldname => lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument
$dropdown = array(
	//"p_id"=>array("freetree", "id", "nav_title", " WHERE freetree.id<>'".$_REQUEST[id]."'"),
	"v_id"=>array("opentree", "id", "nav_title", ""),
	"t_id"=>array("opentempl", "id", "t_name", ""),
	"p_id"=>array("opentree", "id", "nav_title", ""),

);
if ($_REQUEST["id"] || $_REQUEST["p_id"] ) {
	$newpage="New sub page";
	$newpage_link="?task=createstep1&p_id=".$_REQUEST["id"];
}
else{
	$newpage="New main page";
	$newpage_link="?task=createstep1";
}
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . $newpage_link => $newpage),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Edit page"),//2.3 Change item
		"2.4"		=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),		//2.1 general overview
		"2.5"		=>  array($jetstream_url . $thisfile . "?task=moverecord&". $primarykey. "=".$_REQUEST[$primarykey] => 'Move page'),	//2.2 New item

);

//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//date:time when content should go offline, empty means online for the next ten years
	'systemtitle'=>$_REQUEST["nav_title"]//article title for general overview
);

//language
$generallanguage = array(
	"beingedited"=>"This page is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This page can not be undeleted (its not deleted).",
);

// First segment is header of information section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
						'Pages'=>'Are used to create a tree structure like website. Every page can have one or more sub pages.',
						'Icons'=>'Items with colored icons are shown online, grayish items are not shown on the website.',
						'Not visible on website'=>'Publish or aprove a page to make it visible on the website. Select \'archive\' to hide a page or a whole tree of pages. Archived pages can be made visible again by \'re-publishing\' them.',
						'Create pages in main navigation'=>'Select the \'New main page\' tab form this list overview to create a new page. A \'Main page\' is visible in the main navigation.',
						'Create sub pages'=>'Select the page from the tree listing above where you want to create a new sub page under. The edit form of this page is opened. Next, select the \'New sub page\' tab.',
);

// Help under form
$huf=array(	'Help'=>'General configuration for content blocks',
						'Item title'=>'Name of the content block, normaly only show in Jetbox CMS.',
						'Other fields'=>'Depending on the choosen templates one or more input fields can be filled out.',
);

//actual login
local_authenticate();
jetstream_footer();

function on_before_form(){
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $wf;
	global $container_id, $generalconfig, $fileupload, $fileuploaduserrestricted, $noruler;
	global $overviewitemoption, $addnewitemoption, $deleteitemoption, $generallanguage, $templateenabled, $archiveenabled;
	global $userowneditemsonly;
	global $help_under_form;
	global $changeparentenabled;
	global $huf;
	global $_SETTINGS;
	global $treestack;
	global $action;

	//echo $_REQUEST["task"];
	//echo $_REQUEST["p_id"];
	if ($_REQUEST["task"]=="updatecreate" || $_REQUEST["task"]=="updaterecreate" || $_REQUEST["task"]=="createrecord" ) {
		$show_new=true;
		$parent=$_REQUEST["p_id"];
	}
	else{
		$parent=$_REQUEST[$primarykey];
	}

	//check if item has childs
	$q="SELECT *, struct.id AS struct_id, ".$tablename.".id AS tree_id FROM user, struct, $tablename WHERE struct.container_id='".$container_id."' AND struct.content_id=".$tablename.".".$primarykey." AND struct.u_id=user.uid AND $tablename.p_id='".$parent."' ORDER BY $tablename.position ASC";

	$qr = mysql_prefix_query($q) or die (mysql_error());
	if (mysql_num_rows($qr)>0) {
		$deleteitemoption=false;
	}
	$treestack= backtrackopentree($parent);
	$level=0;
	$_SETTINGS["current_process"]["before_form"].="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>";
	$maxstack=count($treestack);
	if($maxstack>0){
		foreach($treestack as $tstack){
			$tstack[1]=htmlspecialchars(stripslashes($tstack[1]));
			$level++;
			if($level==$maxstack){
				if ($show_new) {
					if ($level==1) {
						$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"dtreeimg/base.gif\"></td>";
					}
					else{
						$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"images/caret-rs.gif\"></td><td><img src=\"dtreeimg/page.gif\"></td>";
					}

					$_SETTINGS["current_process"]["before_form"].="<td><a href=\"".$jetstream_url.$thisfile."?task=editrecord&".$primarykey."=".$tstack[0]."\">".$tstack[1]."</a></td>";
					$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"images/caret-rs.gif\"></td><td><img src=\"dtreeimg/page.gif\"></td><td><b>New page</b></td>";
				}
				else{
					if ($level==1) {
						$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"dtreeimg/base.gif\"></td><td><b>".$tstack[1]."</b></td>";
					}
					else{
						$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"images/caret-rs.gif\"></td><td><img src=\"dtreeimg/page.gif\"></td><td><b>".$tstack[1]."</b></td>";
					}
				}
			}
			elseif ($level==1) {
				$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"dtreeimg/base.gif\"></td><td><a href=\"".$jetstream_url.$thisfile."?task=editrecord&".$primarykey."=".$tstack[0]."\">".$tstack[1]."</a></td>";
			}
			else{
				$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"images/caret-rs.gif\"></td><td><img src=\"dtreeimg/page.gif\"></td><td><a href=\"".$jetstream_url.$thisfile."?task=editrecord&".$primarykey."=".$tstack[0]."\">".$tstack[1]."</a></td>";
			}
		}
	}
	else{
		if ($show_new) {
			$_SETTINGS["current_process"]["before_form"].="<td>&nbsp;<img src=\"dtreeimg/base.gif\"></td><td><b>New main page</b></td>";
		}
	}
	$_SETTINGS["current_process"]["before_form"].="</tr></table>";

} // end func

function on_before_list(){
	global $jetstream_nav, $jetstream_url, $thisfile;
	$newpage="New main page";
	$newpage_link="?task=createstep1";
	$jetstream_nav['2.2']=array($jetstream_url . $thisfile . $newpage_link => $newpage);
}

//
function listrecords($error='', $blurbtype='notify'){
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $listformat, $wf, $listgroup, $grouplistformat, $groupsql;
	global $hul;
	if (function_exists("on_before_list")) {
		$error=on_before_list();
	}
	$span=2;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	$tabs[]="2.1";
	$tabs[]="2.2";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
	errorbox ($error, $blurbtype);

	?>
	<DIV class="popupmenu" ID="overDiv" nowrap STYLE="position:absolute; visibility:hidden; z-index:2;"></DIV>
	<script type="text/javascript" src="dtree.js"></script>
	<SCRIPT LANGUAGE="JavaScript" SRC="overlib.js"></SCRIPT>
	<div class="dtree">
	<script language="JavaScript1.2">
	<!--
	<?
	tree("","aa");
	?>
	// -->
	</script>
	</div>

	<?
	echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo help_section($hul, $ruler, $ruler2, $span);
	echo "\n</table>\n";
}

function tree($p, $treeob){
	global $tablename, $primarykey, $jetstream_url, $thisfile, $totalcounter, $container_id, $visible;
	$curlevel=$totalcounter;
	$rootlevel=false;
	$q="SELECT *, struct.id AS struct_id, ".$tablename.".id AS tree_id FROM user, struct, $tablename WHERE struct.container_id='".$container_id."' AND struct.content_id=".$tablename.".".$primarykey." AND struct.u_id=user.uid AND $tablename.p_id='$p' ORDER BY $tablename.position ASC";

	//$q = "SELECT * FROM $tablename WHERE p_id='$p' ORDER BY position ASC";
	$qr = mysql_prefix_query($q) or die (mysql_error());
	$c = mysql_num_rows($qr);
	// get all on this level
	while($qarray = mysql_fetch_array($qr)){
		if ($qarray["p_id"]=='0'){
echo $treeob;?> = new dTree('<?echo $treeob;?>');
	<?
			$totalcounter=-1;
			$drawstring.="		".$treeob.".draw()\n";
			$curlevel=$totalcounter;
			$rootlevel=true;
			$visible=array();
		}
		if ($qarray["status"]<>'published') {
			array_push($visible, false);
			$vis=false;
		}
		elseif ($rootlevel) {
			array_push($visible, true);
			$vis=true;
		}
		elseif($visible[(count($visible)-1)]==false){
			array_push($visible, false);
			$vis=false;
		}
		elseif($qarray["left_nav"]=='0'){
			array_push($visible, false);
			$navoff=", no nav";
			$vis=true;
		}
		else{
			array_push($visible, true);
			$vis=true;
		}
echo $treeob;?>.add(<?echo ($totalcounter+1);?>,<?echo $curlevel;?>,'<?echo $qarray["nav_title"]. " <font color=\"#000000\">[".$qarray["status"].$navoff."]</font>";?>','href="<? echo $jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$qarray["tree_id"];?>" title="<?echo $qarray["status"];?> Edit page"','','','','<?$rootlevel<>true ? (($qarray["status"]<>'published' ||$vis==false) ?  print('page_bw.gif') : print('page.gif')):(($qarray["status"]<>'published' || $vis==false)? print('base_bw.gif'):print('base.gif'));?>');
		<?
		$totalcounter++;
		tree($qarray["id"],$treeob);
		if ($rootlevel==true){
			$treeob++;
		}
		$vis=array_pop($visible);
		$navoff="";
	}

	if ($rootlevel==true){
		/*
		$treeob++;
		echo $treeob;?> = new dTree('<?echo $treeob;?>');
		<? echo $treeob;?>.add(<?echo ($totalcounter+1);?>,<?echo $curlevel;?>,'New page in main navigation','href="?task=createstep1&p_id=<?echo $jetstream_url . $thisfile . "?task=createstep1&p_id=".$p;?>"','','','','base.gif');
		<?		$drawstring.="		".$treeob.".draw()\n";
		*/
		echo $drawstring;
	}
}

//
function move_tree($error='', $blurbtype='notify'){
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $listformat, $wf, $listgroup, $grouplistformat, $groupsql;
	global $hul;
	$span=2;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	$tabs[]="2.1";
	$tabs[]="2.5";

	jetstream_ShowSections($tabs, $jetstream_nav, "2.5");
	errorbox ($error, $blurbtype);
	errorbox ("Click on page to move the selected page under.", "notify");
	?>
	<DIV class="popupmenu" ID="overDiv" nowrap STYLE="position:absolute; visibility:hidden; z-index:2;"></DIV>
	<script type="text/javascript" src="dtree.js"></script>
	<SCRIPT LANGUAGE="JavaScript" SRC="overlib.js"></SCRIPT>
	<div class="dtree">
	<div style="margin:2px 2px 2px 0"><a href="<?echo $jetstream_url . $thisfile . "?task=updatemoverecord&p_id=0&". $primarykey. "=".$_REQUEST[$primarykey];?>"><img src="images/workflow_new.gif" style="margin:2px 5px 2px 0">new main page</a></div>
	<script language="JavaScript1.2">
	<!--
	<?
	m_tree("","aa");
	?>
	// -->
	</script>
	</div>

	<?
	echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo help_section($hul, $ruler, $ruler2, $span);
	echo "\n</table>\n";
}

function m_tree($p, $treeob){
	global $tablename, $primarykey, $jetstream_url, $thisfile, $totalcounter, $container_id, $visible;
	$curlevel=$totalcounter;
	$rootlevel=false;
	$q="SELECT *, struct.id AS struct_id, ".$tablename.".id AS tree_id FROM user, struct, $tablename WHERE struct.container_id='".$container_id."' AND struct.content_id=".$tablename.".".$primarykey." AND struct.u_id=user.uid AND $tablename.p_id='$p' AND $tablename.p_id<>'".$_REQUEST[$primarykey]."' ORDER BY $tablename.position ASC";

	//$q = "SELECT * FROM $tablename WHERE p_id='$p' ORDER BY position ASC";
	$qr = mysql_prefix_query($q) or die (mysql_error());
	$c = mysql_num_rows($qr);
	// get all on this level
	while($qarray = mysql_fetch_array($qr)){
		if ($qarray["p_id"]=='0'){
	echo $treeob;?> = new dTree('<?echo $treeob;?>');
	<?
			$totalcounter=-1;
			$drawstring.="		".$treeob.".draw()\n";
			$curlevel=$totalcounter;
			$rootlevel=true;
			$visible=array();
		}
		if ($qarray["status"]<>'published') {
			array_push($visible, false);
			$vis=false;
		}
		elseif ($rootlevel) {
			array_push($visible, true);
			$vis=true;
		}
		elseif($visible[(count($visible)-1)]==false){
			array_push($visible, false);
			$vis=false;
		}
		elseif($qarray["left_nav"]=='0'){
			array_push($visible, false);
			$vis=true;
		}
		else{
			array_push($visible, true);
			$vis=true;
		}
		if ($qarray[$primarykey]<>$_REQUEST[$primarykey]) {
	    echo $treeob;?>.add(<?echo ($totalcounter+1);?>,<?echo $curlevel;?>,'<?echo $qarray["nav_title"];?>','href="<? echo $jetstream_url . $thisfile . "?task=updatemoverecord&p_id=".$qarray["tree_id"]."&". $primarykey. "=".$_REQUEST[$primarykey];?>" title="Move under this page"','','','','<?$rootlevel<>true ? (($qarray["status"]<>'published' ||$vis==false) ?  print('page_bw.gif') : print('page.gif')):(($qarray["status"]<>'published' || $vis==false)? print('base_bw.gif'):print('base.gif'));?>');
			<?
		}
		else{
			echo $treeob;?>.add(<?echo ($totalcounter+1);?>,<?echo $curlevel;?>,'<?echo $qarray["nav_title"];?><font color="#000000"> [Current location]</font>','','','','','<?$rootlevel<>true ? (($qarray["status"]<>'published' ||$vis==false) ?  print('page_bw.gif') : print('page.gif')):(($qarray["status"]<>'published' || $vis==false)? print('base_bw.gif'):print('base.gif'));?>');
		<?
		}
		$totalcounter++;
		m_tree($qarray["id"],$treeob);
		if ($rootlevel==true){
			$treeob++;
		}
		$vis=array_pop($visible);
	}

	if ($rootlevel==true){
		/*
		$treeob++;
		echo $treeob;?> = new dTree('<?echo $treeob;?>');
		<? echo $treeob;?>.add(<?echo ($totalcounter+1);?>,<?echo $curlevel;?>,'New page in main navigation','href="?task=createstep1&p_id=<?echo $jetstream_url . $thisfile . "?task=createstep1&p_id=".$p;?>"','','','','base.gif');
		<?		$drawstring.="		".$treeob.".draw()\n";
		*/
		echo $drawstring;
	}

}

function backtrackopentree($item){
	global $absolutepathfull, $tablename, $primarykey, $container_id;
	$treeq="SELECT *, struct.id AS struct_id, ".$tablename.".id AS tree_id FROM user, struct, $tablename WHERE struct.container_id='".$container_id."' AND struct.content_id=".$tablename.".".$primarykey." AND struct.u_id=user.uid AND $tablename.id='$item' ORDER BY $tablename.position ASC";
	$treer = mysql_prefix_query($treeq) or die(mysql_error());
	if ($treearray = mysql_fetch_array($treer)){
		if($treearray["p_id"]<>''){
			$treestack= backtrackopentree($treearray["p_id"]);
		}
		$treestack[]=array($treearray["tree_id"], $treearray["nav_title"]);
	}
	return $treestack;
}

function local_authenticate($uid='') {
	global $pagetitle, $wf;
	ob_start();
	session_start();
	if (isset($_SESSION["uid"])){
		jetstream_header($pagetitle);
		loggedin_workflow();
	}
	elseif (new_visit()){
		jetstream_header($pagetitle);
		loggedin_workflow();
	}
}