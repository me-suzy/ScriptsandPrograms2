<?
$auth=true;
//primarykey of the table
$primarykey="uid";
//table name
$tablename="user";
//title of the administration page
$pagetitle="User accounts";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
require("../../includes/includes.inc.php");
//get container id
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='".$thisfile."'");
$container_id= @mysql_result($result,0,'id');
//$mailuid= @mysql_result($result,0,'uid');
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
//Multiselect aan. voor rechter colom select bij submit
$_SETTINGS["set_multiselect"]=true;

//Default column for sorting the overview results
//This column does not have to be a part of the displayed column of the overview
$default_sort_colomn="display_name";
//sort order
//ASC: Ascending
//DESC: Descending
$defaul_sort_order="asc";


// format: fieldname, fieldtype, display text, mandatory [true|false], ignore [true|false]
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("uid","hidden","uid",""),
	array("login","string30","Username","required"),
	array("display_name","string30","Full name","required"),
	array("email","string30","Email","required"),
	array("a","separator","<br />Enter the password twice (to change it)","", true),
	array("user_password","password30","Password","required"),
	array("user_password2","password30","Password (check)","required",true),
	array("b","separator","<br />If an account is not active the user can't log in.","", true),
	array("active","radioonezero","Active account",""),
	array("Project toegang","user_rights","project_access","",true),
	array("type","no_element","",""),
);


// format: fieldname,fieldtype,display text
$listformat=array(	
	array("display_name", "editlink","Name"),
	array("login","string","User name"),
	array("type", "array_show","User type"),
	array("active","radioyesno","Account active",""),
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
		"2.2"		=>  array($jetstream_url . $thisfile . "?task=createrecord" => "New user"),	//2.2 New item
		"2.3"		=>  array($jetstream_url . $thisfile . "?task=editrecord&". $primarykey. "=".$$primarykey => "Accouny properties"),//2.3 Change item
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

function on_before_form(){
	global $records;
	if ($_REQUEST["task"]=='editrecord' ||$_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
		// ret gebruikersnaam op show
		$records[1][1]='show';
		$records[1][3]='';
		$records[1][4]=true;

		$records[5][3]='';
		$records[6][3]='';
	}
}

function on_before_process(){
	global $records, $tablename, $primarykey, $uid, $status, $userright, $display_name, $u_mail, $pagetitle, $jetstream_url, $wf;
	global $container_id, $generalconfig, $mailuid, $front_end_url;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT, $fileuploaduserrestricted;
	global $userowneditemsonly, $templateenabled, $parent_field;
	if ($_REQUEST["task"]=='editrecord' ||$_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
		// ret gebruikersnaam op show
		$records[2][1]='show';
		$records[2][3]='';
		$records[2][4]=true;
	}

	if ($_REQUEST["task"]=='updatecreate' || $_REQUEST["task"]=='updaterecreate') {
		// negeer nieuwe gebruikersnaam
		//$records[2][4]=true;

		$sql= "SELECT * from user where login='".$_REQUEST["login"]."'";
		$results= mysql_prefix_query($sql) or die (mysql_error());
		if(mysql_fetch_array($results)){
			$error= "Deze gebruikersnaam is al in gebruik, geef een andere naam op.";
		}
		if ($_REQUEST["user_password"]<>'' && $_REQUEST["user_password"]<>$_REQUEST["user_password2"]) {
			if ($error) {
			   $error.= "<br>"; 
			}
			$error.= "De opgegeven wachtwoorden zijn niet gelijk.";
		}
		elseif ($_REQUEST["user_password"]=='' || $_REQUEST["user_password2"]=='') {
			if ($error) {
			   $error.= "<br>"; 
			}
			$error.= "Geef een wachtwoord op.";
		}
		if(($_REQUEST["user_password"]<>$_REQUEST["user_password2"]) || $_REQUEST["user_password"]=='' || $_REQUEST["user_password2"]=='' ){
			$records[5][4]=true;
		}
	}

	if ($_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
		if(($_REQUEST["user_password"]<>$_REQUEST["user_password2"]) || $_REQUEST["user_password"]=='' || $_REQUEST["user_password2"]=='' ){
			$records[5][4]=true;
			$records[5][3]='';
			$records[6][3]='';
		}
		if ($_REQUEST["user_password"]<>$_REQUEST["user_password2"]) {
		  $error="De opgegeven wachtwoorden zijn niet gelijk.";
		}
	}
	return $error;
} // end func


function on_after_process(){
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $listformat;
	global $_SETTINGS;
	
	// check of de projecten die worden doorgestuurd wel kunnen
	// check of ze wel bestaan, check of de opgegeven gebruiker geen projectmanager is
	// indien okay, de projecten invoeren in p_rights
	// Alleen de projecten die in de array voorkomen en die okay zijn worden opgeslagen
	// Dit zijn dan alle projecten waar de gebruiker toegang to heeft
	//$_POST[$primarykey];
	//$_POST["list2"];
	//for(reset($_POST["list2"]); list($key, $val)=each($_POST["list2"]); ) {
	//	echo $key.": ".$val." ";
	//}


	/*
	$allusers=mysql_prefix_query("SELECT p_id FROM projects WHERE p_leader<>'".$_POST[$primarykey]."' ORDER BY p_name ASC");
	while ($allusersa=mysql_fetch_array($allusers)) {
		$alr_array[]=$allusersa["p_id"];
	}
	for(reset($_POST["list2"]); list($key, $val)=each($_POST["list2"]); ) {
		if(in_array($val, $alr_array)){
			$selected_okay[]=$val;
		}
	}
	mysql_prefix_query("DELETE FROM p_rights WHERE uid=".$_POST[$primarykey]) or die(mysql_error());
	for(reset($selected_okay); list($key, $val)=each($selected_okay); ) {
		mysql_prefix_query("INSERT INTO p_rights VALUES (".$_POST[$primarykey].", ".$val.", 'user')") or die(mysql_error());
	}
	*/
	mysql_prefix_query("DELETE FROM userrights WHERE uid=".$_POST[$primarykey]) or die (mysql_error());
	while (list($key, $val) = each($_REQUEST["right"])) {
	//key== module id
	//val== rights
		if ($val<>''){
			mysql_prefix_query("INSERT INTO userrights (uid, container_id, type) VALUES (".$_POST[$primarykey].", $key, '$val')") or die (mysql_error()."1");
		}
	}


	return $error;
} // end func



//actual login

// authenticate();
//echo $sql="SELECT * FROM user WHERE uid='".$_SESSION["uid"]."'";
//	$res = mysql_prefix_query($sql);
//	if (mysql_num_rows($res)>0){
//		echo $userright=mysql_result($res,0,'type');
//	}

authenticate($uid);



//show_format_javascript();
function listrecords($error='', $blurbtype='notify'){
	global $primarykey, $jetstream_nav, $tablename;
	//$_REQUEST[$primarykey]=$_SESSION["uid"];
	//general_form('edit', '', '');
	if($_REQUEST["export"]==true){
		
		jetstream_ShowSections(array("2.6"), $jetstream_nav, "2.6");
		$result = mysql_prefix_query("SELECT * FROM ".$tablename);
		echo "<pre><br>";
		echo '"uid"|"login"|"display_name"|"email"|"display_name"|"post_a"|"post_a2"|"bezoek_a"|"bezoek_a2"|"tel"|"fax"|"www"|"btw"|"kvk"|"descrip"|"activi"|"ond_vorm"|"voorletters_1"|"voornaam_1"|"tussen_v_1"|"achternaam_1"|"geslacht_1"|"func_1"|"tel_1"|"mob_1"|"email_1"|"voorletters_2"|"voornaam_2"|"tussen_v_2"|"achternaam_2"|"geslacht_2"|"func_2"|"tel_2"|"mob_2"|"email_2"|"active"|"betaald"<br>';
		while($ra=mysql_fetch_array($result)){
			
			echo "\"".$ra["uid"]."\"|";
			echo "\"".$ra["login"]."\"|";
			echo "\"".$ra["display_name"]."\"|";
			echo "\"".$ra["email"]."\"|";

			echo "\"".$ra["display_name"]."\"|";
			echo "\"".$ra["post_a"]."\"|";
			echo "\"".$ra["post_a2"]."\"|";


			echo "\"".$ra["bezoek_a"]."\"|";
			echo "\"".$ra["bezoek_a2"]."\"|";

			echo "\"".$ra["tel"]."\"|";
			echo "\"".$ra["fax"]."\"|";
			echo "\"".$ra["www"]."\"|";
			echo "\"".$ra["btw"]."\"|";
			echo "\"".$ra["kvk"]."\"|";
			echo "\"".$ra["descrip"]."\"|";
			echo "\"".$ra["activi"]."\"|";
			echo "\"".$ra["ond_vorm"]."\"|";


			echo "\"".$ra["voorletters_1"]."\"|";
			echo "\"".$ra["voornaam_1"]."\"|";
			echo "\"".$ra["tussen_v_1"]."\"|";
			echo "\"".$ra["achternaam_1"]."\"|";
			echo "\"".$ra["geslacht_1"]."\"|";
			echo "\"".$ra["func_1"]."\"|";
			echo "\"".$ra["tel_1"]."\"|";
			echo "\"".$ra["mob_1"]."\"|";
			echo "\"".$ra["email_1"]."\"|";

			echo "\"".$ra["voorletters_2"]."\"|";
			echo "\"".$ra["voornaam_2"]."\"|";
			echo "\"".$ra["tussen_v_2"]."\"|";
			echo "\"".$ra["achternaam_2"]."\"|";
			echo "\"".$ra["geslacht_2"]."\"|";
			echo "\"".$ra["func_2"]."\"|";
			echo "\"".$ra["tel_2"]."\"|";
			echo "\"".$ra["mob_2"]."\"|";
			echo "\"".$ra["email_2"]."\"|";
			
			echo "\"".$ra["active"]."\"|";
			echo "\"".$ra["betaald"]."\"|";

			echo "</br>";
		}
		echo"</pre>";
	}
	else{
		general_listrecords($error, $blurbtype);
	}
}

mysql_close();
jetstream_footer();