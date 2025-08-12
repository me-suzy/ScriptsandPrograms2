<?
$auth=true;
//primarykey of the table
$primarykey="cat_id";
//table name
$tablename="links_cat";
//title of the administration page
$pagetitle="Links category";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
$mailuid= @mysql_result($result,0,'uid');
//workflow support true||flase
$wf=false;
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
$default_sort_colomn="cat";
//sort order
//ASC: Ascending
//DESC: Descending
$defaul_sort_order="ASC";

//Column for item order on the front-end
$order_column="pos";
//sort order
//ASC: Ascending
//DESC: Descending
$order_column_order="ASC";

// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("cat_id","hidden","cat_id",""),
	array("cat","string30","Category","required"),
	array("pos","string10","Position","required"),
);
// format: fieldname,fieldtype,display text
$listformat=array(	
	array("cat", "editlink","Category"),
	//array("pos", "","Position"),
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(	
	array("", "",""),
);
// format: table,where clause for group query, where clause for records
$groupsql=array("", "", "");

//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New category"), //2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Edit category"),//2.3 Change item
);

//language
$generallanguage = array(
	"beingedited"=>"This catagorie is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This catagorie can not be undeleted (its not deleted).",
);

//actual login
authenticate($uid);
function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};

jetstream_footer();