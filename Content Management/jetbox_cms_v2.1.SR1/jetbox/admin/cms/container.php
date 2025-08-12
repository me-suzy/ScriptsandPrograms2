<?
$auth=true;
//primarykey of the table
	$primarykey="id";
//table name
	$tablename="container";
//title of the administration page
$pagetitle="Container administration";
//name of this file, starting with a slash
$thisfile =		"/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
$mailuid= @mysql_result($result,0,'uid');
//workflow support true||flase
$wf=false;
//group list by some row or other table row
//$listgroup=true;
//ability to show list of items [true|false]
$overviewitemoption=true;
//ability to add an item [true|false]
$addnewitemoption=true;
//ability to delte an item [true|false]
$deleteitemoption=true;
// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records= array(array("id","hidden","id",""),// unique id
								array("cfile",	"display","File name<br> <a href=\"containercode.php\" target=\"_new\">Generate Container</a>",""),//container file name
								array("cname",	"display","Name in navigation","required"),//Container name in navigation
								array("corder",	"display","Position","required"),//Relative position in navigation
								array("uid",		"dropdown","Request for approval email recipient","required"),//User who receives email for approval
								array("level",	"containerradio3","Authorization level",""),// Requird user level
								array("inuseradmin","radioonerezo","Userrights managed",""),// Editable in user administration
								array("generalview","radioonerezo","Visible in editorial overview",""),// Viewed @ general overview after login
								array("separator","separator","<br>Values below are only used on the front-end","","false",""),// Used on the front-end
								array("keywords","string30","Keywords","","",""),// Used on the front-end
								array("description","string30","Description","","",""),// Used on the front-end
								array("robot","string20","Robot","","","index, follow"),// Used on the front-end

			 );

// format: fieldname,fieldtype,display text
$listformat=array(	
				array("cname", "editlink","Name"),
				array("level", "containerlevel","Level"),
				array("corder", "string","Position"),
				array("id", "string","Container ID")

				);


// format:  dropdownfieldname, lookuptablename, lookuptableprimarykey, lookuptabledisplayfield, argument
$dropdown = array("uid"=>array("user LEFT JOIN userrights ON user.uid=userrights.uid","uid","display_name", "WHERE  ((user.type='administrator') OR (userrights.container_id='".$_REQUEST["id"]."' AND userrights.type IN ('administrator', 'editor'))) GROUP BY user.uid"),
			);
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New container"),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Edit container"),//2.3 Change item
);

//language
$generallanguage = array(
	"beingedited"=>"This container is locked.",
	"norights"=>"You don't have the right permissions to preform this action.",
	"undeleteunable"=>"This container can not be undeleted (its not deleted).",
);

// First segment is header of information section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
						'Container'=>'Containers are placeholders for administration sections in Jetbox CMS. With a container one single type of information can be administrated, or can be a complete separate section of third party developed software, like the mailing list.<br>Use the \'Generate container\' function, under new container, to create a new administration section that facilitates all internal feutures of Jetbox CMS. For every container the userright can be set separately.',
);
// Help under form
$huf=array(	'Help'=>'General configuration for container',
						'Filename'=>'First <i>generate</i> the containercode and save this to a file in the /admin/cms/ directory. Filename should start with \'/\'',
						'Name in navigation'=>'Name presented in the left menu.',
						'Position'=>'Absolute position in left menu',
						'Request for approval email receipient'=>'Every workflow enabled container has one administrator. If a user submits an item for publishing, this administrator will recieve an email. The email address is configured in the user preferences menu.',
						'Authorization level'=>'The minimum level a user should have.',
						'Userrights managed'=>'Enable or disable userright management for this container. If \'no\' is selected the container is not chown in the useradministration section.',
						'Visible in editorial overview'=>'Show saved and submitted items in the editorial overview.',
);


//actual login
authenticate($uid);
//
function listrecords($error='', $blurbtype='notify'){
	general_listrecords($error, $blurbtype);
};

jetstream_footer();