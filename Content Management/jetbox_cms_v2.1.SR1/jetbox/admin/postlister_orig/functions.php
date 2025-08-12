<?php


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
		"2.2"		=>  array($jetstream_url . "/internalmail.php" => "Manage subscribers"),		//Add/delete subscriber
		//"2.3"		=>  array($jetstream_url . "/../postlister/import.php" => $GLOBALS[s105]),		//import
		"2.3"		=>  array($jetstream_url . "/../postlister/generate.php" => "Generate text"),		//import
		"2.4"		=>  array($jetstream_url . "/../postlister/edit.php" => $GLOBALS["s4"]),		//list properties
		"2.5"		=>  array($jetstream_url . "/../postlister/lists.php" => $GLOBALS["s5"]),		//create/ delete list
);

session_start();
ob_start();
if (isset($_SESSION["uid"])){
	jetstream_header($pagetitle);
	$tabs[]="2.3";
	$tabs[]="2.1";
	$tabs[]="2.4";
	//$tabs[]="2.5";
	$tabs[]="2.2";
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


$uniktId = uniqid("pl");
$ekstraHeadere = "X-Mailer: Postlister $version\n";
$ekstraHeadere .= "Errors-To: ".$admin_email."\n";

function sidehoved($titel = "", $menu = 1) {
	global $site_title, $uid, $jetsream_url, $front_end_url, $currensection, $toptab, $seltoptab, $uid;
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
		/*
		?>
		<table width='160' cellpadding='0' cellspacing='0' border='0'>
		<tr> 
			<td valign="top" align="left" colspan=2>&nbsp;</td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr>
		<tr> 
			<td colspan='2' class='nav'><b>Options</b></td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr>
		<tr> 
			<td width='20' valign='top'><img src='<? echo $jetstream_url;?>/images/caret-rs.gif' width='11' height='7'>&nbsp;</td>
			<td width='140'><a href='<? echo $jetstream_url . "/../postlister/index.php" ?>'><? echo $GLOBALS["s2"]; ?></a></td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr>
		<tr> 
			<td width='20' valign='top'><img src='<? echo $jetstream_url;?>/images/caret-rs.gif' width='11' height='7'>&nbsp;</td>
			<td width='140'><a href='<? echo $jetstream_url . "/../postlister/subscribers.php" ?>'><? echo $GLOBALS["s3"];?></a></td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr>
		<tr> 
			<td width='20' valign='top'><img src='<? echo $jetstream_url;?>/images/caret-rs.gif' width='11' height='7'>&nbsp;</td>
			<td width='140'><a href='<? echo $jetstream_url . "/../postlister/import.php" ?>'><? echo $GLOBALS[s105];?></a></td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr>
		<tr> 
			<td width='20' valign='top'><img src='<? echo $jetstream_url;?>/images/caret-rs.gif' width='11' height='7'>&nbsp;</td>
			<td width='140'><a href='<? echo $jetstream_url . "/../postlister/export.php" ?>'><? echo$GLOBALS[s112];?></a></td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr>
		<tr> 
			<td width='20' valign='top'><img src='<? echo $jetstream_url;?>/images/caret-rs.gif' width='11' height='7'>&nbsp;</td>
			<td width='140'><a href='<? echo $jetstream_url . "/../postlister/edit.php" ?>'><? echo$GLOBALS["s4"];?></a></td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr>
		<tr> 
			<td width='20' valign='top'><img src='<? echo $jetstream_url;?>/images/caret-rs.gif' width='11' height='7'>&nbsp;</td>
			<td width='140'><a href='<? echo $jetstream_url . "/../postlister/lists.php"?>'><? echo$GLOBALS["s5"];?></a></td>
		</tr>
		<tr> 
			<td colspan='2'><img src='<? echo $jetstream_url;?>/images/break.gif' height='1' width='160' vspace='4'></td>
		</tr></table>
		<?
			*/
	}
	echo "<br/>\n\n";
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
    $kommando = mysql_prefix_query("select epostadresse from $list where epostadresse = '$email'");
    if (mysql_num_rows($kommando) > 0 && $action == "subscribe") {
        # "The email address already exists on the list":
        fejl($s84);
    }
		if (mysql_num_rows($kommando) == 0 && $action == "unsubscribe") {
			# "You can't unsubscribe that email because it doesn't exist
			# on the list":
			fejl($s89);
		}

    $kommando = mysql_prefix_query("select tilmeldingsbesked, afmeldingsbesked from $mainTable where liste = '".addslashes($list)."'");
    $resultat = mysql_fetch_array($kommando);

    $tilmeldingsbesked = stripslashes($resultat[tilmeldingsbesked]);
    $afmeldingsbesked = stripslashes($resultat[afmeldingsbesked]);

    if ($action == "subscribe") {
			$tilmeldingsbesked = str_replace("[SUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$list&abonner=1&epostadresse=". urlencode($email) ."&id=$uniktId", $tilmeldingsbesked);
			mysql_prefix_query("insert into $list values ( '$email', '$uniktId', '0')");
			mail($email, $s77, $tilmeldingsbesked, "From: $email\n$ekstraHeadere");
			echo "$s79\n";
    }
    else {
			$kommando = mysql_prefix_query("select id from $list where epostadresse = '$email'");
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
	$kommando = mysql_prefix_query("select liste from $mainTable");
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
   // echo "</td></tr></table></div>\n</body></html>";
}

function fejl($fejlbesked = "") {
	echo "<font size=2><b>$GLOBALS[s8]<font size=2><b>\n";
	echo "$fejlbesked\n";
	echo "<form><input type=button value=\"&lt;&lt;&lt; $GLOBALS[s9]\" onClick=history.back()></form>\n";
	echo "</div>\n";
	exit;
}

function date_diff($date1, $date2) {
 $s = strtotime($date2)-strtotime($date1);
 $d = intval($s/86400); 
 $s -= $d*86400;
 $h = intval($s/3600);
 $s -= $h*3600;
 $m = intval($s/60); 
 $s -= $m*60;
 return array("d"=>$d,"h"=>$h,"m"=>$m,"s"=>$s);
}

function vaelgListe($fil) {
	global $jetstream_url, $mainTable;
	if (!$_REQUEST["liste"]) {
		$span=2;
		//$dr= mysql_prefix_query("SELECT UNIXTIMESTAMP(date) as udate FROM $mainTable");
		$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
		$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
		echo "\n<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"4\">";
		echo $ruler;
		echo "\n<tr bgcolor=\"#F6F6F6\">";
		echo "\n<td class='tab-g'>$GLOBALS[s12]</td><td>Last sent (days ago)</td></tr>";	
		echo $ruler2;
		echo "<tr><td><a href=\"$fil?liste=news\">Standard news</a></td>";
		$dr= mysql_prefix_query("SELECT UNIX_TIMESTAMP(date) as udate FROM $mainTable WHERE liste='news'");
		if ($narray=mysql_fetch_array($dr)) {
			echo "<td>".date("d-m-Y",$narray["udate"]);
			$a= date_diff(date("Y-m-d",$narray["udate"]), date("Y-m-d"));
			echo " (".$a["d"].")</td>";
		}
		else{
			echo "</tr>";
		}
		echo $ruler;
		echo "<tr><td><a href=\"$fil?liste=events\">Events mailing</a></td>";
		$dr= mysql_prefix_query("SELECT UNIX_TIMESTAMP(date) as udate FROM $mainTable WHERE liste='events'");
		if ($narray=mysql_fetch_array($dr)) {
			echo "<td>".date("d-m-Y",$narray["udate"]);
			$a= date_diff(date("d-m-Y",$narray["udate"]), date("d-m-Y"));
			echo " (".$a["d"].")</td>";
		}
		else{
			echo "</tr>";
		}
		echo $ruler;


		/*
		echo "<form action=\"$fil\" method=get>\n";
		$kommando = mysql_prefix_query("select liste from $_REQUEST[mainTable]");
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
		*/
		echo "</td></tr></table>";
		jetstream_footer();
		exit;
	}
}

function generate_mail_txt($itemarray, $date, $liste) {
	global $front_end_url, $absolutepathfull, $postlisterheaders;
	while (list($var, $val) = each($itemarray)) {
		if($items<>''){
			$items.=", ".$val;
		}
		else{
			$items.=$val;
		}
	}
 	if ($liste=="news" || $liste=="internalnews"){
		$ebateartikelresult = mysql_prefix_query("SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_news.date) AS udate, DATE_FORMAT(plug_news.date, \"%d-%m-%Y\") as datef FROM plug_news, struct WHERE struct.container_id='14' AND struct.content_id=plug_news.id AND plug_news.id IN (".$items.")") or die (mysql_error());
		mysql_num_rows($ebateartikelresult);
		$topcontent="---------------------------------<br>".$postlisterheaders['news']."<br>\n".date("d-M-Y")."<br><br>";
		$topcontent.= "---------------------------------<br>IN THIS MAILING:<br>---------------------------------<br>";
		while ($mailtxtarray = mysql_fetch_array($ebateartikelresult)){
			$inhoud.="\n* " . stripslashes($mailtxtarray["title"]);
			$inhoud.= "<br>";
			$botcontent.= "\n* ".$mailtxtarray["datef"] ." - ". stripslashes($mailtxtarray["title"]) . "<br>";
			if ($mailtxtarray["author"]<>''){
				$botcontent.= "\n  ".$mailtxtarray["author"]."<br><br>";
			}
			else{
				$botcontent.= "\n<br>";
			}
			$bodytxt = trim($mailtxtarray["brood"]);
			//Remove newlines
			$bodytxt = implode("",explode("\n",$bodytxt));
			//Chacge html <br> into newlines
			$bodytxt = implode("\n",explode("<br />",$bodytxt));
			$bodytxt = strip_tags($bodytxt);
			//Get first 200 characters of maintext
			$bodytxta = substr($bodytxt, 0, 200); //first part of text
			//Get some more text
			$bodytxtb = substr($bodytxt, 200, 50); //lastpart of text
			//If character is white space, than its a whole word 
			$spatie='';
			if ($bodytxtb[0]==' '){
				$spatie=' ';
			}
			//split this on white spaces
			$bodytxtarray = explode(" ",$bodytxtb);
			//Combine the characters to make a whole word at the and of the sentence.
			$bodytxta.=$spatie.$bodytxtarray[0];
			//echo $bodytxta;
			$botcontent.= "\n" . $bodytxta."...\n\n";
			$botcontent.= "<br>More information: ".$front_end_url.$absolutepathfull."view/news/item/".$mailtxtarray["struct_id"];
			$botcontent.= "<br><br>---------------------------------<br>";
		}
  }
	else{
		$ebateartikelresult = mysql_prefix_query("SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_event.date) AS udate, DATE_FORMAT(plug_event.date, \"%d-%m-%Y\") as datef FROM plug_event, struct WHERE struct.container_id='17' AND struct.content_id=plug_event.id AND plug_event.id IN (".$items.")") or die (mysql_error());
		mysql_num_rows($ebateartikelresult);
		$topcontent="---------------------------------<br>".$postlisterheaders['events']."<br>\n".date("d-M-Y")."<br><br>";
		$topcontent.= "---------------------------------<br>IN THIS MAILING:<br>---------------------------------<br>";
		while ($mailtxtarray = mysql_fetch_array($ebateartikelresult)){
			$inhoud.="\n* " . stripslashes($mailtxtarray["name"])." - ".stripslashes($mailtxtarray["location"]);
			$inhoud.= "<br>";
			$botcontent.= "\n* ".$mailtxtarray["datef"] ." - ". stripslashes($mailtxtarray["name"])." <br>Location: ".stripslashes($mailtxtarray["location"]) . "<br>";
			if ($mailtxtarray["contact"]<>''){
				$botcontent.= "\nContact:  ".$mailtxtarray["contact"]."<br><br>";
			}
			else{
				$botcontent.= "\n<br>";
			}
			$bodytxt = trim($mailtxtarray["subject"]);
			//Remove newlines
			$bodytxt = implode("",explode("\n",$bodytxt));
			//Chacge html <br> into newlines
			$bodytxt = implode("\n",explode("<br />",$bodytxt));
			$bodytxt = strip_tags($bodytxt);
			//Get first 200 characters of maintext
			$bodytxta = substr($bodytxt, 0, 200); //first part of text
			//Get some more text
			$bodytxtb = substr($bodytxt, 200, 50); //lastpart of text
			//If character is white space, than its a whole word 
			$spatie='';
			if ($bodytxtb[0]==' '){
				$spatie=' ';
			}
			//split this on white spaces
			$bodytxtarray = explode(" ",$bodytxtb);
			//Combine the characters to make a whole word at the and of the sentence.
			$bodytxta.=$spatie.$bodytxtarray[0];
			//echo $bodytxta;
			$botcontent.= "\n" . $bodytxta."...\n\n";
			$botcontent.= "<br>More information: ".$front_end_url.$absolutepathfull."view/events/item/".$mailtxtarray["struct_id"];
			$botcontent.= "<br><br>---------------------------------<br>";
		}

	}
	echo "".$topcontent. $inhoud. "<br>---------------------------------<br>".$botcontent."";
}

//
function listitems($liste, $date){
	global $jetstream_url;
	echo "<form action=\"?\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"liste\" value=\"".$liste."\">";
	echo "<table border=\"0\">";
	echo "<tr><td><b>".$liste."</b></td></tr>";
	// select all descriptions
	if ($liste=='news') {
    $sql="SELECT *, DATE_FORMAT(date, \"%d/%m/%Y\") as datef FROM plug_news WHERE intern<>1 AND date>'".$date."'";
	}
	elseif ($liste=='internalnews') {
    $sql="SELECT *, DATE_FORMAT(date, \"%d/%m/%Y\") as datef FROM plug_news WHERE intern=1 AND date>'".$date."'";
	}
	elseif ($liste=='events') {
    $date=date("Y-m-d");
		$sql="SELECT *, DATE_FORMAT(date, \"%d/%m/%Y\") as datef FROM plug_event WHERE date>'".$date."'";
	}
	$result = mysql_prefix_query($sql) or die (mysql_error());
	//echo mysql_num_rows($result);
	$polls_available = 0;
	// cycle through the descriptions until everyone has been fetched
	while($item_object = mysql_fetch_array($result)){
		$polls_available = 1;
		if ($liste<>'events') {
   		echo "<tr><td><input type=\"checkbox\" name=\"itemarray[]\" value=\"".$item_object["id"]."\">".$item_object["title"]."</td></tr>";
		}
		else{
   		echo "<tr><td><input type=\"checkbox\" name=\"itemarray[]\" value=\"".$item_object["id"]."\">".$item_object["name"]." - ".$item_object["datef"]."</td></tr>";
		}
	}
	// close table and form
	echo "</table>";
	if($polls_available){
		echo "<br><input type=\"submit\" value=\"Select\">";
	}
	else{
		echo "No items found.";
	}
	echo "</form>";
} // end func
?>