<?

$formt = new Template("./");
ob_start();

if ($_REQUEST["task"]=='') {
	$_GET["task"]=$_REQUEST["task"]=$task;    
}
$auth=true;
//primarykey of the table
//primarykey of the table
$primarykey="uid";
//table name
$tablename="user";
//title of the administration page
$pagetitle="Aanmelden";
//name of this file, starting with a slash
$thisfile= $absolutepathfull."view/login";
if($view=='register'){
	$thisfile= $absolutepathfull."view/register";
}
//Multiselect aan. voor rechter colom select bij submit
//$_SETTINGS["set_multiselect"]=true;

// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("uid","hidden","uid",""),
	array("login","string30","Gebruikersnaam","required"),
	array("asd","hidden","dfg","",true,""),
	array("asd","hidden","dfg","",true,""),
	array("separator","separator","<br>Geef twee keer hetzelfde wachtwoord op, minimaal 6 karakters lang.","",true,""),// Used on the front-end
	array("user_password","password30","Wachtwoord",""),
	array("user_password2","password30","Wachtwoord (controle)","",true),

	array("separator","separator","<br />Bedrijfsgegevens","",true,""),// Used on the front-end
	//array("orgnaam","string40","Bedrijfsnaam","required","",""),
	array("display_name","string40","Bedrijfsnaam","required"),
	array("email","string40","E-mail","required"),
	array("separator","separator","Postadres","",true,""),// Used on the front-end
	array("post_a","string40","Staat &amp; huisnummer","required","",""),
	array("post_a2","string40","Postcode &amp; plaats","required","",""),
	//array("post_aan","radioonezero","Postadres zichtbaar","","",""),
/*
	array("separator","separator","<br />Bezoekadres","",true,""),// Used on the front-end
	array("bezoek_a","string40","Staat &amp; huisnummer","","",""),
	array("bezoek_a2","string40","Postcode &amp; plaats","","",""),
	//array("bezoek_aan","radioonezero","Bezoekadres zichtbaar","","",""),
	array("tel","string40","Telefoon nummer","","",""),
	array("fax","string40","Fax nummer","","",""),
	array("www","string40","Website<br /><font size=\"-2\"><b>Met http://</b></font>","","","http://"),
	//array("btw","string40","BTW-nummer","","",""),
	//array("kvk","string40","KvK-nummer","","",""),
	array("descrip","blob","Hoofdactiviteiten bedrijf","required","",""),
	//array("activi","blob","Hoofdactiviteiten","","",""),
	array("ond_vorm","dropdown","Ondernemingsvorm","","",""),
	//array("branches","branches","Branches","",true,""),

	array("separator","separator","<br />Hoofdcontactpersoon (graag volledig invullen)","",true,""),// Used on the front-end
	array("voorletters_1","string40","Voorletters","","",""),
	array("voornaam_1","string40","Voornaam","","",""),
	array("tussen_v_1","string40","Tussenvoegsel","","",""),
	array("achternaam_1","string40","Achternaam","","",""),
	array("geslacht_1","sexe","Sekse","","",""),
	array("func_1","string40","Functie","","",""),
	array("tel_1","string40","Telefoon nummer","","",""),
	array("mob_1","string40","Mobiel nummer","","",""),
	array("email_1","string40","E-mail","","",""),
	//array("separator","separator","<br />Tweede contact persoon (indien van toepassing)","",true,""),// Used on the front-end
	//array("voorletters_2","string40","Voorletters","","",""),
	//array("voornaam_2","string40","Voornaam","","",""),
	//array("tussen_v_2","string40","Tussenvoegsel","","",""),
	//array("achternaam_2","string40","Achternaam","","",""),
	//array("geslacht_2","sexe","Sexe","","",""),
	//array("func_2","string40","Functie","","",""),
	//array("tel_2","string40","Telefoon nr.","","",""),
	//array("mob_2","string40","Mobiel nr.","","",""),
	//array("email_2","string40","E-mail","","",""),
	array("type","hidden","type","","","user"),
*/
);

/*
// format:  dropdownfieldname, lookuptablename, dropdown value column, dropdown view column, argument
$dropdown = array("p_id"=>array("struct", "id", "systemtitle", ""), 
);
*/

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
$dropdown = array(
"p_id"=>array("struct", "id", "systemtitle", ""),
"ond_vorm"=>array("o_vorm", "o_id", "o_vorm", ""), 

);

general_date_process();
//actual login
if ($_REQUEST["task"] == "logout") {
	if($_SESSION["uid"]){
		logout($_SESSION["uid"]);
		$toptab=array("6");
		$seltoptab="6";
		login_screen("ended");
	}
	else{
		authenticate();
	}
}
elseif ($_REQUEST["task"]=='sendpw'){
	$today = date('Y-m-d H:i:s', time());
	$todaylock = date('Y-m-d H:i:s', (time()-61));
	$spamresult = mysql_prefix_query("SELECT * FROM mailspamstop WHERE ip='$REMOTE_ADDR' AND time>'$todaylock'") or die(mysql_error());
	mysql_prefix_query("INSERT INTO mailspamstop VALUES ('$REMOTE_ADDR', '$today')");
	if (mysql_num_rows($spamresult)<1) {
		if ($login){
			$mailr = mysql_prefix_query("SELECT firstname, middlename, lastname, email, user_password  FROM user WHERE login='".$login."'")or die (mysql_error());
			if ($marray=mysql_fetch_array($mailr)){
				$to = $marray[email];
				$subject= $sitename." wachtwoord aanvraag";
				$mailbody = "Uw ".$sitename." wachtwoord is aangevraagd op " . date('F jS, Y') . "\r\n\r\n";
				$mailbody .="- Een reply op deze e-mail zal niet worden beantwoord.\r\n\r\n";
				$mailbody .="Gebruikersnaam: ". $login."\r\n";
				$mailbody .="Wachtwoord: ". $marray["user_password"]."\r\n";
				$mailbody .="Powered by Jetstream - http://streamedge.com.\r\n";
				$mail_header = "From: \"noreply\" <noreply@".$_SERVER['HTTP_HOST'].">\n";
				$mail_status = mail($marray["email"], $subject, $mailbody, $mail_header);
			}
		}
		$annotation="<b>Uw wachtwoord is verzonden.</b>";
	}
	else{
		$annotation="<b><font color=\"#990000\">Uw wachtwoord is al verzonden.</font></b>";
	}
	login_form($login, $login_password, $annotation, $task);
}
elseif($view=='register'){
	loggedin_workflow();
}
else{
	loggedin_workflow();

	authenticate();
}

addbstack('', 'My account', 'webuser');
addbstack('', 'Home');
//$tabsarray=array(	array(tabname=>"breadcrum", func=>"backtrackcrum", argument=>$item, totab=>false));
if (isset($_SESSION["uid"])) {
	//Logged in or logging out
	if ($task == "logout") {
		$t->set_file("block", "main_tpl.html");
	}
	else{
		$t->set_file("block", "main_tpl_no_nav.html");
	}
}
elseif ($task=='register' || $_REQUEST["login"] || $_REQUEST["task"]) {
	$t->set_file("block", "main_tpl_no_nav.html");
}
else{
	$t->set_file("block", "main_tpl.html");
}

if (isset($_SESSION["uid"])) {
	$t->set_var("itemtitle", "Ingelogd");		    
	$t->set_var("pagetitle", $sitename." - Ingelogd");
	//$leftnav="<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" style=\"background-color:#F4FBFF; border: solid #a69764; border-width: 1px 0px 1px 0px; width:100%\"><tr><td style=\"padding: 20px 10px 10px 10px\">".openitem(26)."</td></tr></table>";

}
else{
	if($_REQUEST["view"]=='register' || $view=='register'	){
		$view='';
		$task='register';
		$t->set_var("itemtitle", "Aanmelden");		    
		$t->set_var("pagetitle", $sitename." - Aanmelden");
		$leftnav="<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" style=\"border-width: 0px 0px 1px 0px;\" class=\"info_box\"><tr><td style=\"padding: 20px 10px 10px 0px\">".openitem(24)."</td></tr></table>";
	}
	elseif($task == "logout"){
		$t->set_var("itemtitle", "Uitgelogd");		    
		$t->set_var("pagetitle", $sitename." - Uitgelogd");
			$leftnav="<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" style=\"border-width: 0px 0px 1px 0px;\" class=\"info_box\"><tr><td style=\"padding: 20px 10px 10px 0px\">".openitem(25)."</td></tr></table>";
	}
	else{
		$t->set_var("itemtitle", "Inloggen");		    
		$t->set_var("pagetitle", $sitename." - Inloggen");
		$leftnav="<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" style=\"border-width: 0px 0px 1px 0px;\" class=\"info_box\"><tr><td style=\"padding: 20px 10px 10px 0px\">".openitem(25)."</td></tr></table>";
	}

}


function on_before_form(){
	global $records;
	if ($_REQUEST["task"]=='editrecord' ||$_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
		// ret gebruikersnaam op show
		$records[2][1]='show';
		$records[2][3]='';
		$records[2][4]=true;

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

		$sql= "SELECT * from user where login='$_REQUEST[login]'";
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
			$error.= "Geef twee maal hetzefde wachtwoord op.";
		}
		if(($_REQUEST["user_password"]<>$_REQUEST["user_password2"]) || $_REQUEST["user_password"]=='' || $_REQUEST["user_password2"]=='' ){
			$records[5][4]=true;
		}
		if(!check_email($_REQUEST["email"]) || $_REQUEST["email"]==''){
			if ($error) {
		   $error.= "<br>"; 
			}
			$error.= "Geef een geldig e-mail op.";
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

	//while (list($key, $val) = each($_REQUEST["right"])) {
	//	if ($val<>''){
	//		mysql_prefix_query("INSERT INTO userrights (uid, container_id, type) VALUES (".$_POST[$primarykey].", $key, '$val')") or die (mysql_error()."1");
	//	}
	//}
	//echo $_REQUEST["task"];
	if ($_REQUEST["task"]=='updatecreate' || $_REQUEST["task"]=='updaterecreate') {
			$to = $_SETTINGS["aanmeld_email_adres"];
			$subject= "Nieuw lid aanvraag voor Zakelijk Zeeburg";
			$mailbody = "Een bedrijf heeft zich online aangemeld om lid te worden " . date('F jS, Y') . "\r\n\r\n";
			//$mailbody .="- Een reply op deze e-mail zal niet worden beantwoord.\r\n\r\n";
			//$mailbody .="Gebruikersnaam: ". $login."\r\n";
			//$mailbody .="Wachtwoord: ". $marray[user_password]."\r\n";
			//$mailbody .="Powered by Jetstream - http://streamedge.com.\r\n";
			$mail_header = "From: \"noreply\" <noreply@".$_SERVER['HTTP_HOST'].">\n";
			$mail_status = mail($_SETTINGS["aanmeld_email_adres"], $subject, $mailbody, $mail_header);



			$to = $_SETTINGS["aanmeld_email_adres"];
			$subject= "Zakelijk Zeeburg aanmelding";
			$mailbody = "Bedankt voor de aanmelding.\r\nZodra de betaling van de contributie binnen is, wordt het lidmaatschap definitief van kracht. En kun je inloggen met de opgegeven gebruikersnaam en wachtwoord combinatie.\r\n";
			//$mailbody .="- Een reply op deze e-mail zal niet worden beantwoord.\r\n\r\n";
			$mailbody .="Gebruikersnaam: ". $_REQUEST["login"]."\n";
			$mailbody .="Wachtwoord: ". $_REQUEST["user_password"]."\n\n";
			$mailbody .="Met vriendelijke groet, Zakelijk Zeeburg.\n";
			$mail_header = "From: ".$_SETTINGS["aanmeld_email_adres"]."\n";
			$mail_status = mail($_REQUEST["email"], $subject, $mailbody, $mail_header);

	}

	return $error;
} // end func

function listrecords($error='', $blurbtype='notify'){
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $listformat, $wf, $listgroup, $grouplistformat, $groupsql, $action, $absolutepath, $absolutepathfull;
	errorbox ($error, $blurbtype);
	if ($_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
		$bla="Account aangepast.";
		//$bla2="Please be aware, you are still logged in.";
		//$bla2.="<a href=\"".$thisfile."/task/editrecord\">I want to edit my account.</a><br>or<br><a href=\"".$thisfile."/task/logout\">I want to logout.</a>";
	}
	elseif($_REQUEST["task"]=='updatecreate'|| $_REQUEST["task"]=='updaterecreate'){
		$bla="De aanmelding is voltooid, U krijgt spoedig een bericht over de activering.";
		$bla=openitem(28);
		//$bla2="Please be aware, you aren't logged in.";
		//$bla2.="<br><a href=\"".$absolutepathfull."view/webuser\">Go to the login page.</a>";
	}
	else{
		$bla=openitem(26);
		//$bla2.="<a href=\"".$thisfile."/task/editrecord\">I want to edit my account.</a><br>or<br><a href=\"".$thisfile."/task/logout\">I want to logout.</a>";
		//$bla2=openitem(26);
	}
	?>
	<table cellspacing=0 cellpadding=0 width="100%" border=0>
			<tr> 
				<td valign="top"></td>
				<td class=formHead width="100%"><?echo $bla;?></td>
			</tr>
			<tr> 
				<td colspan=2><img height=10 src="images/clearpixel.gif" width=2></td>
			</tr>
			<tr> <td valign=top></td>
				<td valign=top width="100%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><?
									echo $bla2."<br>";
									?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?
}

jetstream_footer();
function jetstream_footer() {
	?>
	<!--
	<div class="small">Powered by <a href="http://www.streamedge.com">Jetstream <sup>&copy;</sup></a> <? echo $GLOBALS["back_end_version"]; ?>
	//-->
	<?
}


$containera = ob_get_contents(); 
ob_end_clean();

$t->set_var("extra_container", $leftnav);
$t->set_var("containera", $containera);