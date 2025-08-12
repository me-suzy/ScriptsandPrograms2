<?
//Voor het aanmaken van een nieuwe template gebruik opentemplate.php
//in de source van opentemplate.php staat verdere uitleg.


$auth=true;
//primarykey of the table
$primarykey="id";
//table name
$tablename="opencontent";
//title of the administration page
$pagetitle="Items";
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
$deleteitemoption=false;
//depending on templates [true|false]
$templateenabled=true;
// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("id","hidden","id",""),
	//array("p_id","hidden","Parent",""),
	array("page_title","string40","Item title","required"),
	//array("nav_title","hidden","Nn",""),
	array("content","novalue","Content",""),
	array("t_id","valuefromurl","Template","required"),
	//array("position","hidden","Position",""),
	//array("linkdoc","hidden","Link page",""),
	//array("v_id","hidden","Page to link",""),
);

// format: fieldname,fieldtype,display text
$listformat=array(	
	array("page_title", "editlink","Item title"),
	array("id", "","Item ID")
);

// format: fieldname,fieldtype,display text
$grouplistformat=array(	
	array("", "",""),
);
// format: table,where clause for group query, where clause for records
$groupsql=array("", "", "");


// format:  dropdownfieldname => lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument
$dropdown = array(
	"v_id"=>array("opentree", "id", "nav_title", ""), 
	"t_id"=>array("opentempl", "id", "t_name", ""), 
	"p_id"=>array("opentree", "id", "nav_title", ""), 
);
 
 
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createstep1" => "New item"),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Edit item"),//2.3 Change item
);

//general configuration for structure info
$generalconfig = array(
	'keywords'=>'',//meta keywords
	'description'=>'',//meta description
	'robot'=>'index,follow',//robot tags, empty means "index,folow"
	'ondate'=>date("Y-m-d  H:i:s",(time())),//date:time when content should go online, empty is inmediatly online, when aproved
	'offdate'=>date("Y-m-d  H:i:s",(time()+315360000)),//date:time when content should go offline, empty means online for the next ten years
	'comment'=>'',//date:time when content should go offline, empty means online for the next ten years
	'systemtitle'=>$_REQUEST["nav_title"]//article title for general overview
);

//language
$generallanguage = array(
	"beingedited"=>"This page is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This page can not be undeleted (its not deleted).",
);

// First segment is header of informarmation section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
						'Content blocks'=>'Are used to make a \'special\' section of a webpage dynamic. Normaly this section does not fit any container.',
						'Example'=>'The website has a contact form, above the form you can add a dynamic section with \'Content blocks\'.',
						'Code'=>'$block=openitem(\'Item ID\'); The Item ID\'s are shown above.',
						'Remove content blocks'=>'Content blocks can\'t be removed, as this will result in a broken link between the CMS and webpage.',


);
// Help under form
$huf=array(	'Help'=>'General configuration for content blocks',
						'Item title'=>'Name of the content block, normaly only show in Jetbox CMS.',
						'Other fields'=>'Depending on the choosen templates one or more input fields can be filled out.',
);

//actual login
authenticate($uid);
function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};

jetstream_footer();