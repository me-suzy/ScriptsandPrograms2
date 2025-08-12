<?
$auth=true;
//primarykey of the table
$primarykey="id";
//table name
$tablename="plug_links";
//title of the administration page
$pagetitle="Links";
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

//Default column for sorting the overview results
//This column does not have to be a part of the displayed column of the overview
$default_sort_colomn="cat_id";
//sort order
//ASC: Ascending
//DESC: Descending
$defaul_sort_order="ASC";

// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("id","hidden","id",""),
	array("p_id","hidden","Parent",""),
	array("cat_id","dropdown","Catagorie",""),
	array("name","string","Site name","required"),
	array("url","string","Url<br><font size=\"-2\"><b>Include http://</b></font>","required","","http://"),
	array("descrip","richblob","Description","required"),
);
// format: fieldname,fieldtype,display text
$listformat=array(	
	array("name", "editlink","Site name"),
	array("cat_id", "reflink","Catagorie","links_cat", "cat_id", "cat", "")
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(	
	array("", "",""),
);
// format: table,where clause for group query, where clause for records
$groupsql=array("", "", "");


// format:  dropdownfieldname, lookuptablename, dropdown value column, dropdown view column, argument
$dropdown = array(
							"p_id"=>array("struct", "id", "systemtitle", ""),
							"cat_id"=>array("links_cat", "cat_id", "cat", ""),
);
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New website link"),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Change website link"),//2.3 Change item
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
	'systemtitle'=>$_REQUEST["name"]//article title for general overview
);

//language
$generallanguage = array(
	"beingedited"=>"This website link is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This website link can not be undeleted (its not deleted).",
);

//actual login
authenticate($uid);

function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};
jetstream_footer();