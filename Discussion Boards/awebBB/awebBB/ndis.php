<?php
session_start();
if ($_GET['a'] == "refresh") {
echo "<meta http-equiv=\"refresh\" content=\"0;url=ndis.php?c=" . $_GET['c'] . "&tid=" . $_GET['tid'] . "&t=" . $_GET['t'] . "\">"; 
} else { }
session_start();
if ($_SESSION['Username'] == "" AND $_SESSION['Logged_In'] != "True" OR $_SESSION['Logged_In'] == "True-Admin") {
$IncPageLog = "reply_log.php";
} else {
$IncPageLog = "reply_now.php";
}
include "header.php";
?>
<style type="text/css">
<!--
.menutitle{
cursor:pointer;
margin-bottom: 0px;
color: black;
padding:0px;
font-weight:bold;
border: 0px double silver;
}
//-->
</style>
<script language="javascript" type="text/javascript">
<!--
/***********************************************
* Special thanks to these guys for drop-down menu script which I could modify 
* Switch Menu script- by Martial B of http://getElementById.com/
* Modified by Dynamic Drive for format & NS4/IE4 compatibility
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/
//-->
</script>
<div class="side-headline"><b><?=$_GET['t'];?>, Category: <?=$_GET['c'];?></b></div>
<div align="center"><br>

<?
if ($_GET['id'] != "") {
$GetById = " WHERE id = '$_GET[id]'";
} else {
$GetById = " WHERE categories = '$_GET[c]'";
}
include "switcharray.php";
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
//Get the data
$query = "SELECT id, tname, poster, fpost, sig, avatar, time, date FROM forum WHERE categories = '$_GET[c]' AND tid = '$_GET[tid]' ORDER BY date"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 

$id=$r["id"]; 
$tname=$r["tname"];
$poster=$r["poster"]; 
$fpost=str_replace($smiliearray, $imagearray, $r["fpost"]); 
$sig=$r["sig"]; 
$avatar=$r["avatar"]; 
$time=$r["time"]; 
$date=$r["date"]; 
?>
<script language="javascript" type="text/javascript">
if (document.getElementById){ 
document.write('<style type="text/css">\n')
document.write('.submenu<?=$id;?>{display: none;}\n')
document.write('</style>\n')
}
function SwitchPlanet(obj){
	if(document.getElementById){
	var el = document.getElementById(obj);
	var ar = document.getElementById("masterdiv<?=$id;?>").getElementsByTagName("span"); //DynamicDrive.com change
		if(el.style.display != "block"){ //DynamicDrive.com change
			for (var i=0; i<ar.length; i++){
				if (ar[i].className=="submenu<?=$id;?>") //DynamicDrive.com change
				ar[i].style.display = "none";
			}
			el.style.display = "block";
		}else{
			el.style.display = "none";
		}
	}
}
</script><style type="text/css">
.submenu<?=$id;?>{
margin-bottom: 0.5em;
}
</style>
<div class="blue-box"><div class="breaker"><a id="<?=id;?>"></a><b><?=$tname;?></b> by <a href="dpost.php?p=<?=$poster;?>"><?=$poster;?></a></div><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td height="80" width="80" rowspan="2"><img src="<?=$avatar;?>" border="0" align="left" width="80" height="80"></td><td valign="top"><div class="breaker"><?=$fpost;?></div></td></tr><tr><td valign="bottom"><div align="right"><i><?=$sig;?></i><br><?=$time;?> - <?=$date;?></div></td></tr></table><div class="breaker"></div><div id="masterdiv<?=$id;?>"><div class="menutitle" onclick="SwitchPlanet('sub<?=$id;?>')">&nbsp~ <a href="#<?=$id;?>">Reply</a></div><span class="submenu<?=$id;?>" id="sub<?=$id;?>">
<?
include "$IncPageLog";
?>
</span></div></div>
<?
}
if ($_GET['a'] == "post") {
$time1=date("H:i:s");
include "config.php"; // As you can see we connected to the database with config
$db = mysql_connect($db_host, $db_user, $db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "INSERT INTO forum(tid, categories, tname, poster, fpost, sig, avatar, time, date) 
VALUES('$_POST[tid]','$_POST[categ]','$_POST[tname]','$_SESSION[Username]','$_POST[fpost]','$_POST[sig]','$_POST[avatar]','$time1', now())"; 
mysql_query($query); 
echo "Submitted";
echo "<meta http-equiv=\"refresh\" content=\"0;url=ndis.php?c=" . $_GET['c'] . "&tid=" . $_GET['tid'] . "&t=" . $_GET['t'] . "\">"; 
mysql_close($db); 
} else { }


echo "<br></div>";


include "footer.php";

?>