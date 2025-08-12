<?
$auth=true;
//primarykey of the table
$primarykey="id";
//table name
$tablename="plug_news";
//title of the administration page
$pagetitle="News";
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
// format: fieldname,fieldtype,display text

// if you wish to hide a field set the fieldtype to 'hidden'

$records=array(
	array("id","hidden","id",""),
	array("p_id","hidden","Parent",""),
	array("title","string","Title","required","",""),
	array("brood","richblob","Content","required"),
	array("date","date","Date","required"),
	array("author","string","Author","required"),
	array("intern","checkbox","Intern",""),
	array("extern","checkbox","Extern",""),
);

// format: fieldname,fieldtype,display text
$listformat=array(	
	array("title", "editlink","Title"),
	//array("p_id", "reflink","Parent","struct", "id", "systemtitle", "")
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(	array("", "",""),);
// format: table,where clause for groug query, where clause for records

$groupsql=array("","");

// format:  dropdownfieldname, lookuptablename, dropdown value column, dropdown view column, argument
$dropdown = array("p_id"=>array("struct", "id", "systemtitle", ""), 
);
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New news item"),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Change news item"),//2.3 Change item
		"2.4"		=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),		//2.1 general overview
);

//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//date:time when content should go offline, empty means online for the next ten years
	'systemtitle'=>$_REQUEST["title"]//article title for general overview
);

//language
$generallanguage = array(
	"beingedited"=>"This news item is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This news item can not be undeleted (its not deleted).",
);

//actual login
authenticate($uid);

function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};
jetstream_footer();