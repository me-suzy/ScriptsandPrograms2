<?
/*
CREATE TABLE ppuploads (
  id bigint(21) unsigned NOT NULL auto_increment,
  content blob NOT NULL,
  filename varchar(255) NOT NULL default '',
  width smallint(6) NOT NULL default '0',
  height smallint(6) NOT NULL default '0',
  mime varchar(100) NOT NULL default '',
  name varchar(100) NOT NULL default '',
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
*/

$auth=true;
$pagetitle="Downloads";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
$BASE_URL = $install_dir.'/webfiles';
//this is where the images will be stored relative to the server root
//this directory MUST be readable AND writable by the web server.
$BASE_ROOT = "/webfiles"; 
$fileupload=true;
$storemethod='hd'; //[hd|db] harddisk or database
$filefield="banner"; // alleen nodig bij db store method
$filenamefield="filename";
$primarykey="id";
$tablename="plug_outputs";
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
      array("id",				"hidden","id",""),
			array("p_id",			"hidden","Parent",""),
      array("title",		"sting","Title","required"),
      array("author",		"sting","Author(s)","required"),
      array("date",			"date","Date","required"),
      array("summary",	"richblob","Summary","required"),
      array("filename",	"file","File",""),
     );	

// format:  dropdownfieldname, lookuptablename, lookuptableprimarykey, lookuptabledisplayfield
$dropdown = array("p_id"=>array("struct", "id", "systemtitle", ""),

);


$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		
		"2.2"		=>  array($jetstream_url . $thisfile."?task=createrecord" => "New download"),
		"2.3"		=>  array($jetstream_url . $thisfile => "Edit download"),
		"2.4"		=>  array($jetstream_url . $thisfile . "?showarchive=true" => "Archive"),		//2.1 general overview
);

// format: fieldname,fieldtype,display text
$listformat=array(	
				array("title", "editlink","Title"),
				array("filename", "showfilelink","filename"),
				//array("p_id", "reflink","Parent","struct", "id", "systemtitle", "")

);


//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//date:time when content should go offline, empty means online for the next ten years
	'systemtitle'=>$GLOBALS["title"]//article title for general overview
);

//language
$generallanguage = array(
	"beingedited"=>"This download is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This download can not be undeleted (its not deleted).",
);

//actual login
authenticate($uid);

function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};
jetstream_footer();