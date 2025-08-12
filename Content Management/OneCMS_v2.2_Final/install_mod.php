<?php
include ("config.php");

$mod = $_GET['load'];
$filexp = explode(".", $mod);
$filetype = $filexp[1];
$file = $filexp[0];
$file2 = "mods/$mod";

if (!is_numeric($mod)) { // makes sure that the user isnt entering a #
if ($filetype == "php") {
if ($_GET['act'] == "") {
echo "Are you sure you would like to install the <b>".$file."</b> module?<br><a href='install_mod.php?load=".$mod."&act=go'>Yes</a>";
}
if ($_GET['act'] == "go") {
include ($file2);
        $file = fopen($file2,'r');
        $contents ='';
        while (!@feof($file)) {
          $contents .= fread($file,1024); //
        }
        @fclose($file);

        $ex = explode("------", $contents);
	    while (list(, $i) = each ($ex)) {
		mysql_query($i);
}

if (table($table_name) == TRUE) {
echo "SQL Installed Successfully<br>";
$install1 = "Yes";
} else {
echo "SQL <b>NOT</b> Installed Successfully - Please check ".$file." and try again<br>";
exit;
}

$sql = mysql_query("INSERT INTO onecms_mods VALUES ('null', '".$name."', '".$url."', 'Yes', '".$version."', '".$readme."', '".$url2."', '".$status."')");

if ($sql == TRUE) {
echo "Module SQL Installed Successfully<br>";
$install2 = "Yes";
} else {
echo "Module SQL <b>NOT</b> Installed Successfully - Please try again<br>";
exit;
}

if (($install1 == "Yes") && ($install2 == "Yes")) {
echo "Module <u>".$name."</u> Installed Successfully! You can now proceed to the <a href='".$url."'><b>Admin Panel</b></a>";
}
}

}
}
?>