<?
//Phplib template system
$t = new Template("./");
$t->set_var("absolutepath", $absolutepath);
$t->set_var("absolutepathfull", $absolutepathfull);
$t->set_var("pagetitle", $sitename);
$t->set_file("block", "main_tpl_no_nav.html");   

if($view=='manual_installation'){
	$error_message="<h3>Step I</h3>";
	$error_message.="Change the permissions to be writeable by all (767 or -rwxrw-rwx within your FTP Client) on:</b><br><br>
	&nbsp;- Copy & paste this line in your telnet/ssh client:<br>
	<code style=\"color: #008\">chmod 767 includes/general_settings.inc.php search_texts/ webfiles/ webimages/ temp/ files/ files/wfbtrash/</code><br><br>
	<b>General Jetbox CMS files and folders</b><br>
	/includes/general_settings.inc.php<br>
	<br>
	<b>Search engine</b><br>
	/search_texts/<br>
	<br>
	<b>Files and image</b><br>
	/webfiles/<br>
	/webimages/<br>
	/temp/<br>
	<br>
	<b>Document management</b><br>
	/files/<br>
	/files/wfbtrash/
	";
	$error_message.="<br><br><h3>Step II</h3>";
	$error_message.="Fill out all the required information in <br>/includes/general_settings.inc.php<br>";
	$error_message.="<br><i>Specify the database information:</i>";
	$error_message.="<br>Leave the <b>table prefix empty</b> as this is only for future compatibility.";
	$error_message.="<br>Check the whole file for more configuration options. Especially for a serious installation.";

	$error_message.="<br><br><h3>Step III</h3>";
	$error_message.="Create a new database<br>";
	$error_message.="Insert the mysql dump file /docs/jetbox.sql into the database";

	$error_message.="<br><br><h3>Step IV</h3>";
	$error_message.="<b>Security:</b><br>Remove the /installation/ folder<br>Change the permissions to be writeable only the 'webserver user' (644 or -rw-r--r-- 
within your FTP/ssh Client) on:<br>
/includes/general_settings.inc.php<br><br>";
	$error_message.="<b>Jetbox CMS should now be available</b><br>
							The administration is available via <a href=\"".$absolutepath."admin/index.php\" target=\"_new\">/admin/</a><br>
							Username: admin<br>
							Password: admin1<br>
							<i>Dont't forget to change this password</i>";
}
elseif ($install_jetbox==true && $error_message<>'') {
	$error_message="<h2>Install Jetbox</h2>";
	$error_message.="<br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"fcfcfc\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\" style=\"border: #A31C00	 solid; border-width: 0 0 0 10px;padding:0px 10px 10px 10px\">
					<h4><b>Configuration check:</b></h4>
						Jetbox detects the installation folder automatically, on serveral system configuration this will result in broken images & broken links. This might also happen if you run Jetbox on a subdomain. If you have broken images on this page, please check /includes/general_settings.inc.php for more information.<br></td>
			</tr>
		</tbody>
	
		<tbody>
			<tr>
				<td width=\"100%\" style=\"border: #A31C00	 solid; border-width: 0 0 0 10px;padding:0px 10px 10px 10px\">
					<h4><b>Compatibility check:</b></h4>
						Click on the link below to show php info on this system. This link should work on all systems with php installed as apache module with apache 1.3.x.<br><a href=\"".$absolutepathfull."view/phpinfo\" target=\"_new\">".$absolutepath."view/phpinfo</a><br><font color=\"#A31C00\">On incompatible systems this link will result in a 404 error.</font>
					</td></tr><tr><td style=\"border: #A31C00	 solid; border-width: 0 0 0 10px;padding:0px 10px 10px 10px\"><div style=\"padding:00px 10px 10px 10px;border: #dddddd solid; border-width: 0px 0px 0px 4px;\"><h4>Solutions:</h4>For Apache HTTP server 2.x:<br>If the check results in a 404 error, please check the <a href=\"http://httpd.apache.org/docs-2.0/mod/core.html\" target=\"_new\">Apache documentation on AcceptPathInfo On</a> to solve this issue.<br>
					<br>For other servers:<br>
					Set <code>\$use_standard_url_method</code> to <code>true<code> without quotes in /includes/general_settings.inc.php</div></td>
			</tr>
		</tbody>
	</table>";
	$error_message.="<br>";

	$error_message.="<br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"f0f0f0\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\" style=\"border: #A31C00	 solid; border-width: 0 0 0 10px;padding:10px 10px 10px 10px\">
					<a href=\"instl/\"><b>Start installation wizard</b></a><br></td>
			</tr>
		</tbody>
	</table>";
	$error_messagee="<br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"fcfcfc\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\">
					<p><b>Manual installation:</b><br>
						<a href=\"".$absolutepathfull."view/manual_installation\">Manual installation instruction</a><br></td>
			</tr>
		</tbody>
	</table>";
	$error_message.="<br>";

}
elseif($install_jetbox==true){
	$error_message="<h2>Welcome to Jetbox</h2>";
	$error_message.="<br><table width=\"100%\" border=0 cellpadding=8 cellspacing=0 bgcolor=\"fcfcfc\" style=\"border: #dddddd solid; border-width: 1px 1px 1px 1px;\">
		<tbody>
			<tr>
				<td width=\"100%\">
					<h4><b>Congratulations: Your system is configured correctly</b></h4>
						Please set <code>\$install_jetbox</code> to <code>false</code> (without quotes) in includes/general_settings.inc.php in order to disable the installation wizard and secure your system settings.<br>
						
						 <br><b><a href=\"#\" onclick=\"window.parent.focus;window.close();\">Close this page</a></b>		
		</td>
			</tr>
		</tbody>
	</table>";
	$error_message.="<br>";
}
else{
	$error_message.="<br><b>Database settings</b>";
	$error_message.="<br>Database_username: <b>".($username<>'' ? $username: '<i>Not set</i>')."</b>";
	$error_message.="<br>Database_password: <b><i>".($password<>'' ? 'Password set': 'Not set')."</i></b>";
	$error_message.="<br>Database_hostname: <b>".($hostname<>'' ? $hostname: '<i>Not set</i>')."</b>";
	$error_message.="<br>Database_database: <b>".($database<>'' ? $database: '<i>Not set</i>')."</b>";
	$error_message.="<br><br><b>Debug infomation</b>";
	$error_message.="<br>This file: <b>".__FILE__."</b>";
	$error_message.="<br>Server root path: <b>".$_SERVER["DOCUMENT_ROOT"]."</b>";
	$error_message.="<br>Installation path: <b>".$install_dir."</b><br><font color=\"cc0000\">The installation path should start with a <b>/</b></font>";
	$error_message.="<br>Includes path: <b>".$includes_path."</b>";
}
if ($_REQUEST["view"]=='phpinfo' || $_URL["view"]=='phpinfo') {
	// start the output buffer, this means nothing will be displayed until the buffer is closed
	ob_start();
	// call the phpinfo function to display the php info
	phpinfo();
	// get contents of output buffer, which is everything that would have been printed from phpinfo();
	$val_phpinfo .= ob_get_contents();
	// flush the output buffer and delete the contents
	ob_end_clean();
	$error_message.="<p><b>Hide php info: </b> <a href=\"".$absolutepathfull."\">".$absolutepathfull."</a><p>";
	// get a substring of the php info to get rid of the html, head, title, etc.
	$error_message= substr( $val_phpinfo, 1142, -27 );
}
$t->set_var("containera","<div style=\"margin:6px 10px\">".$error_message."</div>");
$t->set_var("baseurl", "<base href=\"".$front_end_url."\">");
//General Header
//$gh = new Template("./");
//$gh->set_file("block", "general_header_tpl.html");
//$gh->set_var("absolutepath", $absolutepath);
$t->set_var("absolutepathfull", $absolutepathfull);
//$gh->parse("finaloutput", "block");
//$t->set_var("header", $gh->get("finaloutput"));
$t->parse("finaloutput", "block");
$t->p("finaloutput");
die();
?>