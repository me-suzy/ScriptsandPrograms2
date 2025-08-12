<?
addbstack('', 'My account', 'webuser');
addbstack('', 'Home');

if (isset($_SESSION["uid"]) && $_SESSION["type"]=="frontend") {
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

$t->set_var("breadcrum", $breadcrumstack);
$t->set_var("itemtitle", "Your account");		    
$t->set_var("pagetitle", $sitename." - Sign in");

$formt = new Template("./");
ob_start();
if ($_REQUEST["task"]=='') {
	$_GET["task"]=$_REQUEST["task"]=$task;    
}
$auth=true;
//primarykey of the table
$primarykey="uid";
//table name
$tablename="webuser";
//title of the administration page
$pagetitle="Webusers";
//name of this file, starting with a slash
$thisfile= $absolutepathfull."view/webuser";
// format: fieldname,fieldtype,display text
// if you wish to hide a field set the fieldtype to 'hidden'
$records=array(
	array("uid","hidden","uid",""),
	array("login","uname","Username","required"),
	array("user_password","password","Password","required"),
	array("email","string","E-mail","required"),
	array("firstname","string","Firstname","required"),
	array("middlename","string","MI",""),
	array("lastname","string","Surname","required"),
	array("companyname","string","Company","required"),
	array("companyfunction","string","Position",""),
	array("address","string","Address","required"),
	array("address2","string","",""),
	array("address3","string","",""),
	array("city","string","City",""),
	array("state","string","State",""),
	array("zip","string","Zip code",""),
	array("country","string","Country","required"),
	array("workphone","string","Workphone no.","required"),
	array("homephone","string","Homephone no.",""),
	array("newsmail","hidden","",""),
	array("eventmail","hidden","",""),
);

/*
// format:  dropdownfieldname, lookuptablename, dropdown value column, dropdown view column, argument
$dropdown = array("p_id"=>array("struct", "id", "systemtitle", ""), 
);
*/

general_date_process();
//actual login
if ($_REQUEST["task"] == "logout") {
	logout($_SESSION["uid"]);
	$toptab=array("6");
	$seltoptab="6";
	login_screen("ended");
}
elseif ($_REQUEST["task"]=='sendpw'){
	$today = date('Y-m-d H:i:s', time());
	$todaylock = date('Y-m-d H:i:s', (time()-61));
	$spamresult = mysql_prefix_query("SELECT * FROM mailspamstop WHERE ip='$REMOTE_ADDR' AND time>'$todaylock'") or die(mysql_error());
	mysql_prefix_query("INSERT INTO mailspamstop VALUES ('$REMOTE_ADDR', '$today')");
	if (mysql_num_rows($spamresult)<1) {
		if ($login){
			$mailr = mysql_prefix_query("SELECT firstname, middlename, lastname, email, user_password  FROM webuser WHERE login='".$login."'")or die (mysql_error());
			if ($marray=mysql_fetch_array($mailr)){
				$to = $marray["email"];
				$subject= $sitename." password request";
				$mailbody = "Your ".$sitename." password was requested  on " . date('F jS, Y') . "\r\n\r\n";
				$mailbody .="- A reply to this email message will not awnsered.\r\n\r\n";
				$mailbody .="login: ". $login."\r\n";
				$mailbody .="password: ". $marray["user_password"]."\r\n";
				$mailbody .="Powered by Jetstream - http://streamedge.com.\r\n";
				$mail_header = "From: \"noreply\" <noreply@".$_SERVER['HTTP_HOST'].">\n";
				$mail_status = mail($marray["email"], $subject, $mailbody, $mail_header);
			}
		}
		$annotation="<b>Your password is sent.</b>";
	}
	else{
		$annotation="<b>One e-mail should enough, don't you think?</b>";
	}
	login_form($login, $login_password, $annotation, $task);
}
elseif($_REQUEST["task"]){
	loggedin_workflow();
}
else{
	authenticate();
}

function listrecords($error='', $blurbtype='notify'){
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $listformat, $wf, $listgroup, $grouplistformat, $groupsql, $action, $absolutepath, $absolutepathfull;
	errorbox ($error, $blurbtype);
	if ($_REQUEST["task"]=='updateedit' || $_REQUEST["task"]=='updatereedit') {
		$bla="Your account is updated.";
		//$bla2="Please be aware, you are still logged in.";
		$bla2.="<a href=\"".$thisfile."/task/editrecord\">I want to edit my account.</a><br>or<br>
		<a href=\"".$thisfile."/task/logout\">I want to logout.</a>";
	}
	elseif($_REQUEST["task"]=='updatecreate'|| $_REQUEST["task"]=='updaterecreate'){
		$bla="Your account is created.";
		$bla2="Please be aware, you aren't logged in.";
		$bla2.="<br><a href=\"".$absolutepathfull."view/webuser\">Go to the login page.</a>";

	}
	?>
	<table cellspacing=2 cellpadding=0 width="100%" border=0>
			<tr> 
				<td colspan=2 bgcolor="#ACAE94"><img height=1 src="<?echo $absolutepath?>images/clearpixel.gif" 	width=2></td>
			</tr>
			<tr> 
				<td><img height=22 alt="" hspace=3 src="<?echo $absolutepath?>images/lb_pijl.gif" width=22 vspace=3></td>
				<td class=formHead width="100%"><b><?echo $bla;?></b></td>
			</tr>
			<tr> 
				<td colspan=2><img height=10 src="<?echo $absolutepath?>images/clearpixel.gif" width=2></td>
			</tr>
			<tr> <td valign=top></td>
				<td valign=top width="100%">
					<table width="100%" border="0" cellspacing="2" cellpadding="0">
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
	<br>
	<div class="small">Powered by <a href="http://www.streamedge.com">Jetstream <sup>&copy;</sup></a> <? echo $GLOBALS["back_end_version"]; ?>
	</div>
	<?
}
$leftnav="<table cellpadding=0 cellspacing=0 style=\"padding: 0px 0px 0px 14px; border: #A61F1F dotted; border-width: 0px 0px 0px 2px;\"><tr><td><b>Don't have an account?</b><br><br><a href=\"".$absolutepathfull."view/webuser/task/register\">Register now.</a><br><br>It's a easy 2 step process, you may cancel at any time.<br>If you like, we have a weekly news and events mailinglist, to keep you up-to-date.</td></tr></table>";

$containera = ob_get_contents(); 
ob_end_clean();
$t->set_var("leftnav", $leftnav);
$t->set_var("containera", $containera);