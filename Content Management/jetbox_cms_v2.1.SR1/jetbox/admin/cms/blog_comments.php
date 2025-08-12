<?
//primarykey of the table
$primarykey="c_id";
//table name
// NOTICE: if applicable, in order to work correctly remove the table prefix!
$tablename="blog_comments";
//title of the administration page
$pagetitle="Blog comment";
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
$default_sort_colomn="c_id";
//sort order
//ASC: Ascending
//DESC: Descending
$defaul_sort_order="ASC";

// format: fieldname, fieldtype, display text, required field [reguired|""], noprocess [true|false,''], default value
$records=array(
	array("c_id","hidden","C_id","","",""),
	array("blog_id","dropdown","Blog","","",""),
	array("name","string","Name","","",""),
	array("email","string","Email","","",""),
	//array("url","string","Url","","",""),
	array("comment","richblob","Comment","","",""),
	array("date","show","Date","",true,""),
);

// format: fieldname,fieldtype,display text
$listformat=array(
	array("blog_id","reflink","Blog","blog", "b_id", "title"),
	array("name","editlink","Name","","",""),
	array("comment","show","Comment","","",""),
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(
	array("","",""),
);

// format: table,where clause for group query, where clause for records
$groupsql=array("","","");


// format:  dropdownfieldname => lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument
$dropdown = array(
	"blog_id"=>array("blog", "b_id", "title", ""), 
);

//array configuration
$jetstream_nav = array (
	"2.1"	=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
	"2.2"	=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New comment"), //2.2 New item
	"2.3"	=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Edit comment"),//2.3 Change item
	"2.4"	=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),	//2.4 Archive
);

//language
$generallanguage = array(
	"beingedited"=>"This comment item is locked.",
	"norights"=>"You don't have the right permissions to perform this action.",
	"undeleteunable"=>"This comment item can not be undeleted (its not deleted).",
);

//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//Comment for item
	'systemtitle'=>$_REQUEST["c_id"]//article title for general overview
);

// First segment is header of information section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
	'Item'=>'Information.',
);
// Help under form
$huf=array(	'Help'=>'General information for creating a new item',
	'Item'=>'Information.',
);


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