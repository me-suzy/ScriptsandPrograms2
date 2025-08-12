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
	//$basedir = "c:/htdocs/wo/webfiles";
// end some old configs

$auth=true;
//example, this is the actual file system path
//of the web server document root. e.g.
// Filesystem == /home/web/www.yourdomain.com 
//$_SERVER['DOCUMENT_ROOT'];

//the path where the browser sees the document root (i.e. http://www.yourdomain.com/)
$BASE_URL = 'wo/';

//this is where the images will be stored relative to the server root
//this directory MUST be readable AND writable by the web server.
$BASE_ROOT = 'wo/webfiles'; 

$primarykey="id";
$tablename="plug_files";
$pagetitle="Outputs";
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

$fileupload=true;
$storemethod='hd'; //[hd|db] harddisk or database
//$filefield="banner";
// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
      array("id",				"hidden","id",""),
			array("p_id",			"valuefromurlshowlinked","Parent",""),
      array("title",		"sting","Title","required"),
      array("author",		"sting","Publisist","required"),
      array("date",			"date","Date","required"),
      array("summary",	"richblob","Summary","required"),
      array("filename",	"file","File",""),
     );	

// format:  dropdownfieldname, lookuptablename, lookuptableprimarykey, lookuptabledisplayfield
$dropdown = array("p_id"=>array("struct", "id", "systemtitle", ""),

);


$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		
		"2.2"		=>  array($jetstream_url . $thisfile."?task=createrecord" => "New output"),
		"2.3"		=>  array($jetstream_url . $thisfile => "Edit output"),
);

// format: fieldname,fieldtype,display text
$listformat=array(	
				array("title", "editlink","Title"),
				array("filename", "showfilelink","filename"),
				array("p_id", "refeditlink","Parent","struct", "id", "systemtitle", "")

);


//actual login
authenticate($uid);
//
function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};


//echo 	$userfile;
//echo "<br>";
//echo $userfile_name;
//echo "<br>";
//echo $basedir;
//echo "<br>";
//phpinfo();
/*
function process($task) {
	global $records,$tablename,$primarykey, $userfile, $userfile_name, $basedir;
	$error ="";
	if (!empty($userfile) && $userfile != "none") {
		$size = GetImageSize($userfile);
		$GLOBALS["width"] = $size[0];
		$GLOBALS["height"] = $size[1];
		$GLOBALS["format"]=  $GLOBALS[userfile_type];
		$newbasedir= "$basedir$userfile_name";
		move_uploaded_file($userfile,$newbasedir);				
		$fd = fopen ($newbasedir, "r");
		$GLOBALS[banner] = fread($fd, filesize($newbasedir));
		fclose ($fd);
		unlink($newbasedir);
	}
	while (list($var, $val) = each($records)) {
		$field=$val[0];
		$nicefield=$val[2];
		$req=$val[3];
		if ($req && !$GLOBALS[$field]) {
			$errors .="\n<li>".$nicefield."</li>";
		}
	}
	if ($errors) {
		print "\nVul de volgende (verplichte) velden in:\n<ul>" . $errors . "</ul>";
		$GLOBALS[$primarykey] = $primarykey;
		form("re$task",$GLOBALS["sessid"],$GLOBALS["sesscode"]);
		return false;
	}
	if ($task == 'create') {
		$create = "INSERT INTO $tablename ( $primarykey ) VALUES ('')";
		$r = mysql_prefix_query($create);
		$error = mysql_error();
		if ($error) {
		print "\n<B><P>Fout bij maken van nieuw record: $error</P></B>";
		exit;
		}
		$GLOBALS[$primarykey] = mysql_insert_id();
	}
	$update = "UPDATE $tablename ";
	$index=0;
	reset($records);
	while (list($var, $val) = each($records)) {
		$field=$val[0];
		if (!($primarykey == $field) && !(($field == banner)  && (($userfile=='') || ($userfile=='none')))) {
			if ($previouscellvalue) {
				$update = $update . "," . $field  . "='" .  addslashes($GLOBALS[$field]) . "'";
			}
			else {
				$update = $update . "SET " . $field  . "='" .  addslashes($GLOBALS[$field]) . "'";
			}
			$previouscellvalue="true";
		}
		$index++;
	}
	$update = $update . " WHERE $primarykey = '" . $GLOBALS[$primarykey] ."'";
	$r = mysql_prefix_query($update);
	$error = mysql_error();
	if ($error) {
		print "\n<P><B>Fout bij invoeren record: $error</B></P>\n";
	}
	else {
		//print "\n<P><B>$task record gelukt!</B></P>\n";
	}
listrecords();
}

*/
jetstream_footer();
?>