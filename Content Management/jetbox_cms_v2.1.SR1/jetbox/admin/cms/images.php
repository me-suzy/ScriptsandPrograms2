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

Form type should be
enctype=\"multipart/form-data\"
*/
// some old configs
	# Change this to your desired root directory
	//$basedir = "/opt/guide/www.perspectief.nu/HTML/objects/images/frontpage_makeup/usr_upload";
	# Webdir for images
	//$upload_image_dir = "../../objects/images/frontpage_makeup/usr_upload";
// end some old configs



$auth=true;
$pagetitle="Images";
require("../../includes/includes.inc.php");
$primarykey="id";
$tablename="images";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
$fileupload=true;
$storemethod='hd'; //[hd|db] harddisk or database
//fieldname for binarie data in case of db storage
$filefield="banner";
//field of filename
$filenamefield="name";
//example, this is the actual file system path
//of the web server document root. e.g.
//the path where the browser sees the document root (i.e. http://www.yourdomain.com/)
$BASE_URL = $install_dir.'/webimages';
//this is where the images will be stored relative to the server root
//this directory MUST be readable AND writable by the web server.
$BASE_ROOT = '/webimages';

//ability to show list of items [true|false]
$overviewitemoption=true;
//ability to add an item [true|false]
$addnewitemoption=true;
//ability to delte an item [true|false]
$deleteitemoption=true;

//only owned items (except for system administrators)
//EXTRA OPTION FOR NON WORKFLOW CONTAINERS ONLY!
$userowneditemsonly=true;
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
$mailuid= @mysql_result($result,0,'uid');

// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
      array("id","hidden","id",""),
      array("name","file","Image","required"),
      array("width","hidden","Width",""),
      array("height","hidden","Height",""),
      array("format","hidden","Format",""),
      array("name","hidden","Name",""),
      array("description","string","Name","required"),
      array("uid","uid","uid",""),
     );	


// format:  dropdownfieldname, lookuptablename, lookuptableprimarykey, lookuptabledisplayfield
$DROPDOWN = array(array("","","","") );

$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . "/images.php" => "Overview"),		
		"2.2"		=>  array($jetstream_url . "/images.php?task=createrecord" => "New image"),
		"2.3"		=>  array($jetstream_url . "/images.php" => "Edit image"),
);

// format: fieldname,fieldtype,display text

$listformat=array(	
	array("description", "editlink","Name"),
	array("description", "imagelink","Preview"),
);

//language
$generallanguage = array(
	"beingedited"=>"This image item is locked.",
	"norights"=>"You don't have the right permissions to perform this action.",
	"undeleteunable"=>"This contact item can not be undeleted (its not deleted).",
);

//actual login
authenticate($uid);
//

function on_before_form(){
	global $records;

	if ($_REQUEST["task"]=='createrecord' || $_REQUEST["task"]=='updatecreate' || $_REQUEST["task"]=='updaterecreate') {
		$records[1][3]="required";
	}
	else{
		$records[1][3]="";
	}
} // end func


function on_before_process(){
	global $records, $tablename, $primarykey, $uid, $status, $userright, $display_name, $u_mail, $pagetitle, $jetstream_url, $wf;
	global $container_id, $generalconfig, $mailuid, $front_end_url;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT, $fileuploaduserrestricted;
	global $userowneditemsonly, $templateenabled, $parent_field;
	if ($_REQUEST["task"]=='updatecreate' || $_REQUEST["task"]=='updaterecreate') {
		$records[1][3]="required";
	}
	else{
		$records[1][3]="";
	}
	//$_FILES['userfile']['tmp_name']
	if ($_FILES['userfile']['name']){ 
		$extension= array_pop(explode(".",strtolower($_FILES['userfile']['name'])));
		if($extension<>'gif' && $extension<>'png' && $extension<>'jpg' && $extension<>'jpeg'){
			$error="Only images are alowed<br /> Only files files with the following extension are acepted: gif, png, jpg (jpeg)";
		}
	}
	return $error;
} // end func

function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};


?>
<script language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
-->
</script>
<?

jetstream_footer();
?>