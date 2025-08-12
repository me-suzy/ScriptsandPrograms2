<?php

//require("settings.php");

//require($languageFile);


$_SETTINGS["rte_off"]=true;


while(list($key, $value)=each($_POST)){
	$$key=$value;
}
while(list($key, $value)=each($_GET)){
	$$key=$value;
}

$auth='yes';
$version = "1.16";
$mainTable = "postlistermain";
//name of this file, starting with a slash
$thisfile ="/".array_pop(explode("/", $_SERVER["SCRIPT_NAME"]));
$toptab=array("8", "3");
$seltoptab="8";
$current_time=time();
$languageFile = "language_files/english.php";

function listrecords($error='', $blurbtype='notify'){
	global $titel, $menu;
};
require("../../includes/includes.inc.php");
$result = mysql_prefix_query("SELECT id, uid FROM container WHERE cfile='/../postlister/generate.php'");
$container_id= @mysql_result($result,0,'id');

require($languageFile);
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . "/../postlister/index.php" => $GLOBALS["s2"]),		//Compose
		"2.2"		=>  array($jetstream_url . "/../postlister/subscribers.php" => "Manage subscribers"),		//Add/delete subscriber
		"2.3"		=>  array($jetstream_url . "/../postlister/import.php" => $GLOBALS[s105]),		//import
		//"2.3"		=>  array($jetstream_url . "/../postlister/generate.php" => "Generate text"),		//import
		"2.4"		=>  array($jetstream_url . "/../postlister/edit.php" => $GLOBALS["s4"]),		//list properties
		"2.5"		=>  array($jetstream_url . "/../postlister/lists.php" => $GLOBALS["s5"]),		//create/ delete list
);
$liste=$_REQUEST["liste"];

session_start();
ob_start();
if (isset($_SESSION["uid"])){
	jetstream_header($pagetitle);

	$tabs[]="2.1";
	$tabs[]="2.2";
	$tabs[]="2.4";
	$tabs[]="2.5";
	$tabs[]="2.3";
	jetstream_ShowSections($tabs, $jetstream_nav, $sel_tab);
}
elseif (new_visit()){
	jetstream_header($pagetitle);
	$tabs[]="2.1";
	$tabs[]="2.2";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
}
else{
	exit;
}




if (!isset($formfrontpage)){
	/*
	if ($uid && $mysession) {
		if ((check_session($uid,$mysession)) && (check_user_type($uid) == "Administrator")) {
			setcookie('postlister_uid', $uid, $current_time + 31536000, '/');
			setcookie('postlister_mysession', $mysession, $current_time + 31536000, '/');
			//backend_header($pagetitle);
			//loggedin($uid,$mysession);
		}
		else {
			access_denied();
			backend_footer();
			exit;
		}
	}
	else {
		if  ((new_visit($uid,$mysession)) && (check_user_type($uid) == "Administrator")) {
			setcookie('postlister_uid', $uid, $current_time + 31536000, '/');
			setcookie('postlister_mysession', $mysession, $current_time + 31536000, '/');
			//backend_header($pagetitle);
			//loggedin($uid,$mysession);
		}
		else {
			setcookie('postlister_uid', '', $current_time - 31536000, '/');
			setcookie('postlister_mysession', '', $current_time - 31536000, '/');
			access_denied();
			backend_footer();
			exit;
		}
	}
	*/
}

//mysql_connect($databaseHost, $databaseUsername, $databasePassword);
//mysql_select_db($databaseName);
$uniktId = uniqid("pl");
$ekstraHeadere = "X-Mailer: Jetbox ".$jetstream_version."\n";
$ekstraHeadere .= "Errors-To: ".$admin_email."\n";

function sidehoved($titel = "", $menu = 1) {
	global $site_title,$uid, $back_end_url, $front_end_url, $currensection, $toptab, $seltoptab, $uid;
	$toptab=array("8","3");
	$seltoptab="8";
	if (!$titel){
		$titel = "Postlister";
	}
	else{
		$titel = "Postlister | $titel";
	}
	$page_name= $titel;
	$aStyle ="link";
	$adskiller = "<br>";
	if ($menu != 0) {
	}
	echo "<div style=\"margin: 15px\">\n\n";
}

function subscribe() {
	sidehoved("", 0);
	if ($email) {
    # Is the email address valid?
    if (!ereg("^[-0-9A-Za-z._]+@[-0-9A-Za-z.]+\.[A-Za-z]{2,3}$", $email)) {
        fejl($s75);
    }
    if (!$action || ($action != "subscribe" && $action != "unsubscribe")) {
        fejl($s76);
    }
    # Checking to see if the email address already exists on the list:
    $kommando = mysql_query("select epostadresse from $list where epostadresse = '$email'");
    if (mysql_num_rows($kommando) > 0 && $action == "subscribe") {
        # "The email address already exists on the list":
        fejl($s84);
    }
		if (mysql_num_rows($kommando) == 0 && $action == "unsubscribe") {
			# "You can't unsubscribe that email because it doesn't exist
			# on the list":
			fejl($s89);
		}

    $kommando = mysql_query("select tilmeldingsbesked, afmeldingsbesked from $mainTable where liste = '".addslashes($list)."'");
    $resultat = mysql_fetch_array($kommando);

    $tilmeldingsbesked = stripslashes($resultat[tilmeldingsbesked]);
    $afmeldingsbesked = stripslashes($resultat[afmeldingsbesked]);

    if ($action == "subscribe") {
			$tilmeldingsbesked = str_replace("[SUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$list&abonner=1&epostadresse=". urlencode($email) ."&id=$uniktId", $tilmeldingsbesked);
			mysql_query("insert into $list values ( '$email', '$uniktId', '0')");
			mail($email, $s77, $tilmeldingsbesked, "From: $email\n$ekstraHeadere");
			echo "$s79\n";
    }
    else {
			$kommando = mysql_query("select id from $list where epostadresse = '$email'");
			$resultat = mysql_fetch_array($kommando);
			$idFraDatabasen = $resultat[id];
      $afmeldingsbesked = str_replace("[UNSUBSCRIBE_URL]", 
			"http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$list&abonner=0&epostadresse=".urlencode($email)."&id=$idFraDatabasen", 
			$afmeldingsbesked);
      mail($email, $s78, $afmeldingsbesked, "From: $email\n$ekstraHeadere");
      echo "$s80\n";
		}
		sidefod();
		exit;
	}
	$kommando = mysql_query("select liste from $mainTable");
	echo "<center>\n";

	echo "<table cellspacing=0 border=0 cellpadding=5>\n";
	echo "<form action=\"$PHP_SELF\" method=post>\n";

	echo "<tr><td colspan=2 style=\"background: maroon; color: white\">\n";
	echo "$s70\n";
	echo "</td></tr>\n";

	echo "<tr><td class=tilmelding>\n";
	echo "$s71\n";
	echo "</td>\n";

	echo "<td class=tilmelding>\n";
	echo "<input type=text name=email size=20>\n";
	echo "</td></tr>\n";

	echo "<tr><td class=tilmelding>\n";
	echo "$s72\n";
	echo "</td>\n";

	echo "<td class=tilmelding>\n";
	if (mysql_num_rows($kommando) == 0) {
			echo "$s14\n";
	}
	else {
			echo "<select name=list>\n";
			while ($resultat = mysql_fetch_array($kommando)) {
					$res = htmlspecialchars(stripslashes($resultat[liste]));
					echo "<option value=\"$res\">$res\n";
			}
			echo "</select>\n";
	}
	echo "</td></tr>\n";

	echo "<tr><td class=tilmelding>\n";
	echo "&nbsp;";
	echo "</td>\n";

	echo "<td class=tilmelding>\n";
	echo "<input type=radio name=action value=\"subscribe\" checked>\n";
	echo "$s73<br>\n";
	echo "<input type=radio name=action value=\"unsubscribe\">\n";
	echo "$s74\n";
	echo "</td></tr>\n";

	echo "<tr><td class=tilmelding>\n";
	echo "&nbsp;";
	echo "</td>\n";

	echo "<td class=tilmelding>\n";
	echo "<input type=submit value=\"$s13\"><p>\n";
	echo "<a href=\"http://www.nameless.f2s.com\" style=\"background: black; font-family:lucida,verdana,helvetica; font-size: 10pt; color: white; 
	text-decoration: none\">Postlister $version</a>\n";
	echo "</td></tr>\n";

	echo "</form>\n";
	echo "</table>\n";
	sidefod();
}


function sidefod() {
    echo "</td></tr></table></div>\n</body></html>";
}

function fejl($fejlbesked = "") {
	echo "<font size=2><b>$GLOBALS[s8]<font size=2><b>\n";
	echo "$fejlbesked\n";
	echo "<form><input type=button value=\"&lt;&lt;&lt; $GLOBALS[s9]\" onClick=history.back()></form>\n";
	echo "</div>\n";
	exit;
}

function vaelgListe($fil) {
	if (!$GLOBALS[liste]) {
		echo "<font size=2><b>$GLOBALS[s12]<font size=2><b>\n";
		echo "<form action=\"$fil\" method=get>\n";

		$kommando = mysql_query("select liste from $GLOBALS[mainTable]");
		$antalRaekker = mysql_num_rows($kommando);

		if ($antalRaekker == 0) {
			# "There are no lists":
			echo "$GLOBALS[s14]\n";
		}
		else {
			echo "<select name=liste>\n";
			while ($resultat = mysql_fetch_array($kommando)) {
				echo "<option value=\"$resultat[liste]\">$resultat[liste]\n";
			}
			echo "</select>\n";
			echo "<input type=submit value=\"$GLOBALS[s13]\">\n";
		}
		echo "</div>\n";
		exit;
	}
}
?>