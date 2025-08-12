<?
//primarykey of the table
$primarykey="nav_id";
//table name
// NOTICE: if applicable, in order to work correctly remove the table prefix!
$tablename="navigation";
//title of the administration page
$pagetitle="Navigation";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
$mailuid= @mysql_result($result,0,'uid');
//workflow support [true|false]
$wf=false;
//group list by some row or other table row [true|false]
$listgroup=false;
//ability to show list of items [true|false]
$overviewitemoption=true;
//ability to add an item [true|false]
$addnewitemoption=true;
//ability to delete an item [true|false]
$deleteitemoption=true;

//Default column for sorting the overview results
//This column does not have to be a part of the displayed column of the overview
$default_sort_colomn="top_nav_order";
//sort order
//ASC: Ascending
//DESC: Descending
$defaul_sort_order="ASC";

//Column for item order on the front-end
$order_column="top_nav_order";
//sort order
//ASC: Ascending
//DESC: Descending
$order_column_order="ASC";

$result2 = mysql_prefix_query("SELECT max(top_nav_order) as a FROM ".$tablename) or die(mysql_error());
$ra2=@mysql_fetch_array($result2);

// format: fieldname, fieldtype, display text, required field [reguired|""], noprocess [true|false,''], default value
$records=array(
	array("nav_id","hidden","","","",""),
	array("nav_name","string20","Navigation Name","required","",""),
	array("view_name","string20","View","","",""),
	array("option_name","string20","Option","","",""),
	array("item","string10","Item","","",""),
	array("asdasd","separator","","",true,""),
	array("file_name","string40","File name","","",""),
	array("top_nav_order","hidden","Order","","",$ra2["a"]),

	array("top_nav","radioonezero","In top nav","","",""),
	array("bot_nav","radioonezero","In bot nav","","",""),
	array("robot","string40","Meta robot","","","index, follow"),
	array("search","string40","Meta search","","",""),
	array("descrip","string40","Meta description","","",""),
);

// format: fieldname,fieldtype,display text
$listformat=array(
	array("nav_name","editlink","Navigation Name","","",""),
	array("view_name","string","View","","",""),
	array("option_name","string","Option","","",""),
	array("item","string","Item","","",""),

	//array("top_nav_order","string","Order","","",""),
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(
	array("","",""),
);

// format: table,where clause for group query, where clause for records
$groupsql=array("","","");


// format:  dropdownfieldname => lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument
$dropdown = array(
);

//array configuration
$jetstream_nav = array (
	"2.1"	=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
	"2.2"	=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New Nav"), //2.2 New item
	"2.3"	=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Edit Nav"),//2.3 Change item
	"2.4"	=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),	//2.4 Archive
);

//language
$generallanguage = array(
	"beingedited"=>"This Nav item is locked.",
	"norights"=>"You don't have the right permissions to perform this action.",
	"undeleteunable"=>"This Nav item can not be undeleted (its not deleted).",
);

//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//Comment for item
	'systemtitle'=>$_REQUEST["nav_id"]//article title for general overview
);

// First segment is header of information section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
	'Administrate the top and bottom navigation for the website with this container'=>'',
	'Order of appearance.'=>'The configure the order of appearance in te navigation, select the "order" colum in this list and change the order with the arrows.',

	'Look and feel'=>'To change the look and feel of the navigation edit the top_nav and bot_nav functions in /includes/f_general_functions.inc.php',
);
// Help under form
//$huf=array(	'Help'=>'General information for creating a new item',
//	'Item'=>'Information.',
//);


function on_before_process(){

}

function on_before_form(){

}

function on_before_list(){

}

//actual login
authenticate($uid);
function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};

jetstream_footer();