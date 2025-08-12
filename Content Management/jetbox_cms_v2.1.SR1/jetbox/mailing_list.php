<?


// CONFIGURATION
// By default this mailing list subscribes to news_list
// To change this edit $tablename below to the correct mailinglist name


$date=date("Y-m-d");
addbstack('', 'Mailing list', $view);
addbstack('', 'Home');
$t->set_file("block", "main_tpl_no_nav.html");
$t->set_var("breadcrum", $breadcrumstack);
$t->set_var("itemtitle", "Mailing list");
$t->set_var("pagetitle", $sitename." - Mailing list");

//output news for selected item

$thisfile= $absolutepathfull."view/mailing_list";
//table name
// NOTICE: if applicable, in order to work correctly remove the table prefix!
$tablename="default";
//ability to add an item [true|false]
$addnewitemoption=true;
//ability to delete an item [true|false]
$deleteitemoption=false;
//ability to edit an item [true|false]
$edititemoption=false;
$floodstop=false; //enable anti flood
$floodstop_time=61; //seconds
$uniktId = uniqid("pl");
$ekstraHeadere = "X-Mailer: Jetbox ".$jetstream_version."\n";
$ekstraHeadere .= "Errors-To: ".$admin_email."\n";


if($option=="confirm"){
	$records=array(
		array("godkendt","hidden","godkendt","",1,""),
		array("epostadresse","string","Email","required","",""),
	);
}
else{
	$records=array(
		//array("c_id","hidden","C_id","","",""),
		array("epostadresse","string","Email","required","",""),
	);
}
function listrecords($error='', $blurbtype='notify'){general_form('create');};

function on_after_process(){
	$today = date('Y-m-d H:i:s', time());
	mysql_prefix_query("INSERT INTO mailspamstop VALUES ('".$_SERVER['REMOTE_ADDR']."', '$today')");
};

function on_before_process(){
	global $floodstop_time, $flood_error;
	$todaylock = date('Y-m-d H:i:s', (time()-$floodstop_time));
	$spamresult = mysql_prefix_query("SELECT * FROM mailspamstop WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND time>'$todaylock'") or die(mysql_error());
	if (mysql_num_rows($spamresult)>0) {
		return "No more than one subscription a minute please.";
	}
}	


ob_start();
$result = mysql_query("SELECT tilmeldingsbesked, afmeldingsbesked FROM postlistermain WHERE liste = '".addslashes($tablename)."'");
$result_array = mysql_fetch_array($result) or die(mysql_error()."Please check if you have the appropriate mailing list configured.";

$tilmeldingsbesked = stripslashes($result_array["tilmeldingsbesked"]);
$afmeldingsbesked = stripslashes($result_array["afmeldingsbesked"]);

if($_REQUEST["task"]=="updatecreate" || $_REQUEST["task"]=="updaterecreate"){
	//Check mail adres
	//
	if ($_REQUEST["epostadresse"]==''){
		loggedin_workflow();
	}
	elseif (!ereg("^[-0-9A-Za-z._]+@[-0-9A-Za-z.]+\.[A-Za-z]{2,3}$", $_REQUEST["epostadresse"])) {
		$error="Please provide a valid email address";
		errorbox ($error, 'error');
		general_form('recreate');
	}
	else{
		$error=on_before_process();
		
		if($error<>''){
			errorbox ($error, 'error');
			general_form('create');
		}
		else{
			echo "Thank you for you subscription. You will recieve an e-mail shortly to confirm your subscription.";
		}
		$tilmeldingsbesked = str_replace("[SUBSCRIBE_URL]", $front_end_url.$thisfile."/option/confirm_subscribe?liste=".$tablename."&epostadresse=".urlencode($_REQUEST["epostadresse"])."&id=".$uniktId, $tilmeldingsbesked);
		mysql_query("INSERT INTO ".$tablename." VALUES ('".$_REQUEST["epostadresse"]."','$uniktId','0','".date("Y-m-d")."')") or die(mysql_error());
		//echo $tilmeldingsbesked;
		mail($_REQUEST["epostadresse"], $s77, $tilmeldingsbesked, "From: ".$_REQUEST["epostadresse"]."\n$ekstraHeadere");
		on_after_process();
	}
}
elseif($option=="confirm_subscribe" || $option=="confirm_unsubscribe"){
	// select e-mail form db
	// if okay, 
	// update record to confirm
	$epostadresse = addslashes($_REQUEST["epostadresse"]);
	$result = mysql_query("SELECT id FROM ".$tablename." WHERE epostadresse = '$epostadresse'");
	$result_array = mysql_fetch_array($result);
	$idFraDatabasen = $result_array["id"];
	if($_REQUEST["id"] == $idFraDatabasen){
		if($option=="confirm_subscribe"){
			mysql_query("UPDATE ".$tablename." SET godkendt = '1' WHERE epostadresse = '$epostadresse'");
		}
		else{
			mysql_query("DELETE FROM ".$tablename." WHERE epostadresse = '$epostadresse'");
		}
		echo "Thank you for you confirmation.";
	}
	else{
		echo "We're unable to preform your requested action, the information you provided is invalid or incomplete.";
	}
}
elseif($option=="unsubscribe"){
	//echo "You are unsubscribed form our mailing list.";
	// delete from db
	$result = mysql_query("SELECT id FROM ".$tablename." WHERE epostadresse = '".urldecode($_REQUEST["epostadresse"])."'") or die(mysql_error());
	$result_array = mysql_fetch_array($result);
	$idFraDatabasen = $result_array["id"];
	$afmeldingsbesked = str_replace("[UNSUBSCRIBE_URL]", $front_end_url.$thisfile."/option/confirm_unsubscribe?liste=".$tablename."&epostadresse=".urlencode($_REQUEST["epostadresse"])."&id=".$idFraDatabasen, $afmeldingsbesked);
	//echo $afmeldingsbesked;
	mail($email, $s78, $afmeldingsbesked, "From: ".$_REQUEST["epostadresse"]."\n$ekstraHeadere");
}
else{
	general_form('create');
}

$containera = ob_get_contents(); 
ob_end_clean();


$t->set_var("containera", '<h2>Subscribe</h2><div style="margin:0px 10px 20px 0px;padding:4px 10px 10px 0px;">'.$containera.'</div>', true);



?>