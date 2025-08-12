<?
$auth=true;
//primarykey of the table
$primarykey="uid";
//table name
$tablename="webuser";
//title of the administration page
$pagetitle="Internal mail";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='/../postlister/generate.php'");
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
//ability to delte an item [true|false]
$deleteitemoption=false;
// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("uid","hidden","uid",""),
	array("login","string30","Username","required"),
	array("email","string30","Email","required"),
	array("firstname","string30","Firstname","required"),
	array("middlename","string30","MI",""),
	array("lastname","string30","Surname","required"),
	array("newsmail","radioonerezo","News mail",""),
	array("eventmail","radioonerezo","Event mail",""),
	array("internalmail","radioonerezo","Internal mail",""),
);

// format: fieldname,fieldtype,display text
$listformat=array(	
	array("login", "editlink","Username"),
	array("email", "","Email"),
	array("firstname", "","Firstname"),
	array("middlename", "","MI"),
	array("lastname", "","Surname"),
	array("internalmail", "radioyesno","Intenal mail"),
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(	
	array("", "",""),
);
// format: table,where clause for group query, where clause for records
$groupsql=array("", "", "");


// format:  dropdownfieldname, lookuptablename, dropdown value column, dropdown view column, argument
$dropdown = array("p_id"=>array("struct", "id", "systemtitle", ""), 
);
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . "/../postlister/generate.php?admin=yes" => "Generate text"),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Change contact"),//2.3 Change item
);

//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//date:time when content should go offline, empty means online for the next ten years
	'systemtitle'=>$GLOBALS["name"]." - ".$GLOBALS["institute"]//article title for general overview
);

//language
$generallanguage = array(
	"beingedited"=>"This contact item is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This contact item can not be undeleted (its not deleted).",
);

//actual login
authenticate($uid);

function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};
jetstream_footer();