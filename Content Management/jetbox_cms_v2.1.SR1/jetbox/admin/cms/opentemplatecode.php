<?
//primarykey of the table
$primarykey="id";
//table name
$tablename="container";
//title of the administration page
$pagetitle="Generate template code";
//name of this file, starting with a slash
$thisfile =		"/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
$mailuid= @mysql_result($result,0,'uid');
//Show left menu and top tabs.
//$nomenu=true;
// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
/*
$records= array(array("id","hidden","id",""),// unique id
								array("cfile",	"display","File name",""),// file name
								array("cname",	"string","Name in navigation","required"),//Container name in navigation
								array("corder",	"string","Position","required"),//Relative position in navigation
								array("uid",		"dropdown","Request for approval email receiver","required"),//User who receives email for approval
								array("level",	"radio3","Visible for",""),// Requird user level
								array("inuseradmin","radio","Userrights managed",""),// Editable in user administration
								array("generalview","radio","General status overview",""),// Viewed @ general overview after login
			 );
// format:  dropdownfieldname=> lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument
$dropdown = array("uid"=>array("user, userrights","user.uid","user.display_name", ""),
			);
	*/

//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Start"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New opentemplate data"),	//2.2 New item
		"2.4"		=>  array($jetstream_url ."/index.php?task=edit&container_id=".$container_id => "Generate opentemplate data"),//2.4 preferences
);

//language
$generallanguage = array(
	"beingedited"=>"This page is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This container can not be undeleted (its not deleted).",
);

$drop2array= array (
	"-Select-"=>"string",
	//"Primary key"=>"hidden",
	"Input (79)"=>"string",
	"Input 10"=>"string10",
	"Input 20"=>"string20",
	"Input 30"=>"string30",
	"Input 40"=>"string40",

	"Textarea"=>"blob",
	"Rich text"=>"richblob",

	"Date"=>"date",
	"Checkbox yes/no"=>"checkbox",
	"Radio yes/no (standard)"=>"radio",
	//"Dropdown menu *"=>"dropdown",
	//"File"=>"file",
	"Image (popup select)"=>"image",

	"Hidden"=>"hidden",
	"Only show"=>"display",
	//"No value"=>"novalue",
	//"Radio no/yes [no/yes]"=>"radioyesno",
	"Radio no/yes [false/true]"=>"radiotruefalse",
	//"Value from url"=>"valuefromurl",
	//"Value from url referred *"=>"valuefromurlshowlinked",
	"User"=>"uid",
);

/*
$drop3array= array (
	"-Select-"=>"editlink",
	"Edit link"=>"editlink",
	"Referred edit link **"=>"refeditlink",
	"Show value"=>"string",
	"Referred show **"=>"reflink",
	"Show image"=>"imagelink",
	"Show file link"=>"showfilelink",
	"Yes/no"=>"radioyesno",
	"Date dd-mm-yyyy"=>"date",
	"Date dd-mm-yy"=>"date2",
	
);
*/
general_date_process();
ob_start();
session_start();
if (isset($_SESSION["uid"])){
	jetstream_header($pagetitle);
	loggedin();
}
elseif (new_visit()){
	jetstream_header($pagetitle);
	loggedin();
}

function loggedin($uid=''){
	switch($_REQUEST["task"]) {

		case 'createrecord'				: form('create'); break;
		case 'editrecord'					: form('edit'); break;

		case 'updateedit'					: process('edit'); break;
		case 'updatereedit'				: process('edit'); break;

		case 'updatecreate'				: process('create'); break;
		case 'updaterecreate'			: process('create'); break;
		
		case 'deleterecord'				: deleterecord(); break;

		default : listrecords();
	}

}
//


function listrecords($error='', $blurbtype='notify') {
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $database;
	$tabs[]="2.1";
	$span=2;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
	// Help under form
	$hul=array(	'Help'=>'Generating the template code',
							'Getting started example'=>'Create a new html file and store it in the /opentemplate folder.<br/> Edit the file with a text editor and enter "{header}{contents}". Type 2 in the field above and go to the next step.',
							'Number of fields'=>'The number of fields is corresponding with the number of {items} found in the template.',
	);
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
	errorbox ($error, $blurbtype);
	//Step one select database table";
	echo "\n<FORM ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\">";
	echo "\n<table border=0 width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\">";
	echo "\n<td class='tab-g' colspan=2>Generate template code</td>";
	echo "\n</tr>";
	echo $ruler;

	echo "\n<tr bgcolor=\"#ffffff\">";
	echo "\n<td	width=10% nowrap>Number of fields</td><td><INPUT class=\"form_input\" TYPE=\"TEXT\" NAME=\"fieldcount\" SIZE=\"10\" MAXLENGTH=\"255\"</td>\n";
	echo "\n</tr>";
	echo "\n<INPUT TYPE=\"Hidden\" NAME=\"task\" VALUE=\"editrecord\">";
	echo "\n<tr><td><br></td></tr>";
	echo "\n<tr>\n<td colspan=2><INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Step One of Two\">";
	echo "\n</td></tr>";
	echo "</TABLE>\n</FORM>";
	echo "<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"5\" id=\"table_overview_list\">";
	echo help_section($hul, $ruler, $ruler2, $span);
	echo "</table>";
}

function form($action, $error='') {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $uid;
	global $container_id, $generalconfig, $containerdropdown, $drop2array, $drop3array, $database;
	//Step two show general form";
	$span=8;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
	$tabs[]="2.1";
	$userright=get_userrights($_SESSION["uid"]);
	if ($userright=="administrator"){
		$tabs[]="2.4";
		$selectedtab="2.4";
	}
	jetstream_ShowSections($tabs, $jetstream_nav, $selectedtab);
	errorbox ($error, $blurbtype);
	echo "\n<FORM ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\">";
	// 2. Gets table keys and retains them
	$fieldcount= $_REQUEST["fieldcount"];
	for ($i=0; $i<$fieldcount; $i++) {
		$ffields.= "\n<tr><td nowrap=\"nowrap\"><INPUT class=\"form_input\" TYPE=\"TEXT\" NAME=\"fieldname_" .$i."\" SIZE=\"30\" value=\"fieldname_".$i."\" MAXLENGTH=\"255\"></td>";
		$ffields.= "<td><INPUT class=\"form_input\" TYPE=\"TEXT\" NAME=\"nicefieldname_" . $i. "\" VALUE=\"".$cellvalue."\" SIZE=\"30\" MAXLENGTH=\"255\"></td>";
		$ffields.= "<td>";
		$ffields.=  "\n<select name=\"dropdinput_".$i."\">";
		foreach ($drop2array as $key => $value) {
			//echo "Key: $key; Value: $value<br>\n";
			$ffields.=  "\n<option class=\"form_select\" value=\"". $value . "\">" . $key;
		}					
		$ffields.=  "\n</select></td>";
		$ffields.= "<td><input class=\"form_input\" type=\"TEXT\" name=\"defaultvalue_" . $i. "\" value=\"\" size=\"30\" MAXLENGTH=\"255\"></td>";
		$ffields.=  "\n<TD VALIGN=\"TOP\"><INPUT TYPE=\"CHECKBOX\" NAME=\"required_$i\" VALUE=\"1\"></TD>";
		$ffields.=  "\n</tr>";
		$ffields.= 	$ruler;
	} // end while
	echo "\n<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">\n";
	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=8  background='images/stab-bg.gif'><font color=\"\"><b>Open template configuration</b></font></td></tr>";
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\">";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Field</td>";
	//echo "\n<td class='tab-g' nowrap=\"nowrap\">Type</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Nice field name</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Formfield type</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Default value</td>";
	echo "\n<td class='tab-g' nowrap=\"nowrap\">Required</td>";
//	echo "\n<td class='tab-g' nowrap=\"nowrap\">Listed</td>";
//	echo "\n<td class='tab-g' nowrap=\"nowrap\">List type</td>";
	echo "\n</tr>";
	echo $ruler;
	echo $ffields;	
	echo "\n<tr><td><br></td></tr>";
	echo "\n<tr>\n<td colspan=2><INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Step Two of Two\" name=\"statusactionsave\">";
	echo "\n</td></tr>";
	echo "\n<tr><td><br></td></tr>";
	echo "\n<INPUT TYPE=\"Hidden\" NAME=\"table\" VALUE=\"".$_REQUEST["table"]."\">";
	echo "\n<INPUT TYPE=\"Hidden\" NAME=\"fieldcount\" VALUE=\"".$_REQUEST["fieldcount"]."\">";
	echo "\n<INPUT TYPE=\"Hidden\" NAME=\"task\" VALUE=\"update".$action."\">";
	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=8 background='images/stab-bg.gif'><font color=\"\"><b>Help</b></font></td></tr>";
	echo $ruler;
	$counter=1;
	echo "\n<tr bgcolor=\"#F6F6F6\"><td class='tab-g' colspan=8><table cellpadding=0 cellspacing=0><tr><td><div id=open1".$counter." style=\"DISPLAY: block\"><a title=Expand  style=\"CURSOR: hand\" onClick=\"javascript:showLayer('rest1".$counter."'); hideLayer('rest2".$counter."'); hideLayer('open1".$counter."'); showLayer('close1".$counter."')\"><img src=\"dtreeimg/nolines_plus.gif\"></a></div><div id=close1".$counter." style=\"DISPLAY: none\"><a title=Collapse style=\"CURSOR: hand\" onClick=\"javascript:hideLayer('rest1".$counter."'); showLayer('rest2".$counter."'); showLayer('open1".$counter."'); hideLayer('close1".$counter."')\"><img src=\"dtreeimg/nolines_minus.gif\"></a></div></td><td class='tab-g'>Form configuration for container</td></tr></table></td>\n</tr>";
	echo $ruler;
	echo "<tr><td colspan=8 style=\"padding: 0\">";
	echo "<div id=rest1".$counter." style=\"DISPLAY: none\">";
	echo "\n<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">\n";
	echo "\n<tr><td class='tab-g' colspan=8>Field</td>\n</tr>";
	echo "\n<tr><td>Copy the names of the {replaceme} items in the fields above</td></tr>";
	echo $ruler;
	echo "\n<tr><td class='tab-g' colspan=8>Nice field name</td>\n</tr>";
	echo "\n<tr><td>Descriptive name of the field. This is the title of the field the user will see in the form.</td></tr>";
	echo $ruler;
	echo "\n<tr><td class='tab-g' colspan=8>Form field type</td>\n</tr>";
	echo "\n<tr><td><i>Input (79):</i><br>Is a standard text field, this one aligns with the rich text field. 10, 20, 30 and 40 have different field lenghts.</td></tr>";
	echo "\n<tr><td><i>Rich text:</i><br>Standard texarea with link to rich text editor, has ability to insert images.</td></tr>";
	echo "\n<tr><td><i>Only show:</i><br>The value is display as text, the user won't be able to change this value. A hidden field contains the value.</td></tr>";
	echo "\n<tr><td><i>User:</i><br>When a new item is made the hidden field gets the value from the user identifier. Not seen by the user.</td></tr>";
	echo $ruler;
	echo "\n<tr><td class='tab-g' colspan=8>Default value</td>\n</tr>";
	echo "\n<tr><td>This value is automatically entered when the user creates a new item.</td></tr>";
	echo $ruler;
	echo "\n<tr><td class='tab-g' colspan=8>Required</td>\n</tr>";
	echo "\n<tr><td>The field must contain a value. To indicate this a small gray bar is displayed in front of the form field.</td></tr>";
	echo $ruler;
	echo "</table>";
	echo "</div>";
	echo "<div id=rest2".$counter." style=\"DISPLAY: block\"></div>";
	echo "</td></tr>";
	echo "</TABLE>\n</FORM>";


}

function process($action) {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $uid;
	global $container_id, $generalconfig, $containerdropdown, $drop2array;
	$span=8;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
	$tabs[]="2.1";
	$userright=get_userrights($_SESSION["uid"]);
	if ($userright=="administrator"){
		$tabs[]="2.4";
		$selectedtab="2.4";
	}
	jetstream_ShowSections($tabs, $jetstream_nav, $selectedtab);
	errorbox ($error, $blurbtype);
	echo "\n<FORM ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\">";
	//$_REQUEST[step]
	$fieldcount= $_REQUEST["fieldcount"];

	$error ="";
	// 2. Get fields
	$i=0;
	$aa=array();
	for ($i; $i<$fieldcount; $i++) {
		$aa[$i]=array($_REQUEST['fieldname_'.$i], $_REQUEST["dropdinput_".$i], $_REQUEST["nicefieldname_".$i],"","",$_REQUEST["defaultvalue_".$i]);
		$_REQUEST["required_".$i]? $aa[$i][3]= "required": $aa[$i][3]= "";
	}
	$a= serialize($aa);
	if (strlen($dorpdows)==0) {
			echo "\n<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">\n";

		echo $ruler2;
		echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=8  background='images/stab-bg.gif'><font color=\"\"><b>Opentemplate code generation completed.</b></font></td></tr>";
		echo $ruler;
		echo "\n<tr bgcolor=\"#F6F6F6\">";
		echo "\n<td class='tab-g' nowrap=\"nowrap\" colspan=8>Copy the codes in the Data field.</td>";
		echo "\n</tr>";
		echo $ruler;
		echo "\n<tr><td>".$a."</td></tr>";    
		echo "\n<tr><td><br></td></tr>";    

	}
	echo "</TABLE>\n</FORM>";
}
jetstream_footer();