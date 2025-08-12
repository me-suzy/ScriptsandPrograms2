<?
$auth=true;
//primarykey of the table
$primarykey="uid";
//table name
$tablename="user";
//title of the administration page
$pagetitle="Personal preferences";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
//$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
//$container_id= @mysql_result($result,0,'id');
//$mailuid= @mysql_result($result,0,'uid');
//workflow support true||flase
$wf=false;
//group list by some row or other table row
$listgroup=false;
//ability to show list of items [true|false]
$overviewitemoption=false;
//ability to add an item [true|false]
$addnewitemoption=false;
//ability to delte an item [true|false]
$deleteitemoption=false;
// format: fieldname, fieldtype, display text, mandatory [true|false], ignore [true|false]
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("uid","hidden","uid",""),
	array("login","show","User name","",true),
	array("display_name","string30","Full name","required"),
	array("email","string30","Email","required"),
	array("separator","separator","<br>Enter your new password below twice to change it.","",true,""),// Used on the front-end
	array("user_password","password30","Password","", true),
	array("user_password2","password30","Password (retype)","",true),
);
// format: fieldname,fieldtype,display text
$listformat=array(	
	array("login", "editlink","Name"),
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
//		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New contact"),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Settings"),//2.3 Change item
//		"2.4"		=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),		//2.1 general overview
);


//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//date:time when content should go offline, empty means online for the next ten years
	'systemtitle'=>$_REQUEST["name"]." - ".$_REQUEST["institute"]//article title for general overview
);

//language
$generallanguage = array(
	"beingedited"=>"This contact item is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This contact item can not be undeleted (its not deleted).",
);

function on_before_process(){
	global $records, $tablename, $primarykey, $uid, $status, $userright, $display_name, $u_mail, $pagetitle, $jetstream_url, $wf;
	global $container_id, $generalconfig, $mailuid, $front_end_url;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT, $fileuploaduserrestricted;
	global $userowneditemsonly, $templateenabled, $parent_field;
	if ($_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
		if ($_REQUEST["user_password"]==$_REQUEST["user_password2"]) {
			if ($_REQUEST["user_password"]<>'') {
				$records[5][4]=false;
				$records[4][2]="<br><b>Your password is changed.</b>";			    
			}
		}
		else{
		  $error="The new passwords do not match.";
		}
	}
	return $error;
} // end func

//actual login
authenticate($uid);
function listrecords($error='', $blurbtype='notify'){
	global $primarykey;
	$_REQUEST[$primarykey]=$_SESSION["uid"];
	general_form('edit', '', '');
	//general_listrecords($error, $blurbtype);
};
jetstream_footer();