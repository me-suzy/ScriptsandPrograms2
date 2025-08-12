<?

$link[0] = "index.php";
$text[0] = "$txt_mview";
$link[1] = "search.php";
$text[1] = "$txt_msearch";
$link[2] = "members.php";
$text[2] = "$txt_mmembers";

if(isset($logincookie[user])) {

$link[3] = "editprofile.php";
$text[3] = "$txt_mprofile";
$link[4] = "pm.php?s=i";
$text[4] = "$txt_mpms";

$pmchk = mysql_query("SELECT * FROM ${table_prefix}private WHERE userto='$logincookie[user]' AND msgread='0'");
$pmno = mysql_num_rows($pmchk);
if ($pmno != "0" && isset($pmno)) $text[4] .= " ($pmno new)";
}
else {
$link[3] = "register.php";
$text[3] = "$txt_mregister";
$link[4] = "login.php";
$text[4] = "$txt_mlogin";
}

for($i = 0; $i < count($text); $i++) {

$dispmenu = str_replace("#link#", $link[$i], $menutemplate);
$dispmenu = str_replace("#text#", $text[$i], $dispmenu);
echo $dispmenu;
}
?>