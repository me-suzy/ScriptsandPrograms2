<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (
	'0' => 'ADMIN',
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php';


//receive posted data
echo $full_name=$_POST["full_name"];
$username=$_POST["username"];
if (isset($_POST["pagelimit"])) {$pagelimit=$_POST["pagelimit"];} else {$pagelimit=$_GET["pagelimit"];}
$page=$_GET["page"];


	if (($username<>"") OR ($full_name<>"")) {$where_start="WHERE ";}
	if ($full_name<>"") {$trazenje=' full_name LIKE "%'.$full_name.'%" ';}
		if ($full_name<>"") {$dodavanje=" AND ";}
	if ($username<>"") {$k_ime=$dodavanje.' username LIKE "%'.$username.'%" ';}


//assemble all
$ima_naslova=$where_start.$trazenje.$k_ime;



//***********************************************************************
if ($_SESSION["search_query_users"]=="") {
$MySQLQuery="SELECT * FROM ".$db_table_prefix."users ".$ima_naslova." ORDER BY full_name , username DESC";
           session_start(); //start session
		   $_SESSION["search_query_users"]=$MySQLQuery;
}
else
{
$MySQLQuery=$_SESSION["search_query_users"];
}

$table_width=650; //table width
$thispage="users_list.php"; //this page

$query=$MySQLQuery; //$MySQLQuery comes from multipaging page
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows


// count number of matches
   $totalrows = mysql_num_rows($result);


?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/page_bg.jpg");
background-repeat: 
repeat-y;
background-attachment: 
fixed
}
</style>

<script language="JavaScript" type="text/JavaScript">
function checkToggle (theForm, theCheckbox, action) {
    // Set target elements
    var theElements = document.forms[theForm].elements[theCheckbox];
   // Loop through the elements
    for (var count = 0; count < theElements.length; count++) {

         theElements[count].checked = action;
    }
	//if only one item
	theElements.checked = action;
    return false;
} 

function decheckiraj()
{
var txt = "<input type=\"button\" name=\"checkall\" value=\"Uncheck All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"checkiraj();checkToggle('delform','todelete[]',false); return false;\">";
document.all.ovde.innerHTML=txt;
}

function checkiraj()
{
var txt = "<input type=\"button\" name=\"checkall\" value=\"Check All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"decheckiraj();checkToggle('delform','todelete[]',true); return false;\">";
document.all.ovde.innerHTML=txt;
}

function back_go()
{
	this.location="users_search.php";
}

function delete_go(delform) {
	if (! delform["todelete[]"].length && delform["todelete[]"].checked)
		return true
	else {
		for (var i = 0; i < delform["todelete[]"].length; i++) {
			if (delform["todelete[]"][i].checked)
				return true
		}
	}
	alert("None selected!")
	return false    
}

</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" <?php if ($totalrows>1) {echo 'onload="checkiraj()"';}?> class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Users: View/Edit Users: <span class="titletext0blue">Users listing</span></td>
  </tr>
</table>
<p> <br>
  <?php 


if ($totalrows>0) { 


//multipaging
include "multipage.php"; 



echo "<form name='delform' action='users_delete.php' method='post' onSubmit=\"return delete_go(this)\">"; 


//table head
echo '<table width="340" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr bgcolor="#3C89D1" class="maintext2beli"> 
    <td height="20" width="40" align="left" valign="middle">&nbsp;&nbsp;#</td>
    <td height="20" width="200" align="center" valign="middle">Full name</td>
    <td height="20" width="100" align="center" valign="middle">Username</td>
    <td height="20" width="20" align="right" valign="middle">A&nbsp;&nbsp;</td>
  </tr>
  <tr align="left" valign="middle"> 
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
  </tr>';


//loop
$i=0;
$bojaCount=1;
$brojac_rezultata=$starting_no;
while ($i < $num) {

$id=mysql_result($result,$i,"idUsers");
$full_name=mysql_result($result,$i,"full_name");
$username=mysql_result($result,$i,"username");
$user_privileges=mysql_result($result,$i,"user_privileges");

//lord
$lord=mysql_result($result,$i,"lord");
if ($lord==1) {
	$lord_status=" (master admin)";
	$master_text=" a master";
	$master_lock=" disabled";
	$cbox_name="master_admin";	
	$name_tt=' title="This user is a master administrator" ';
	} 
else {
	$lord_status="";
	$master_text="";
	$master_lock="";
	$cbox_name="todelete[]";
	$name_tt="";
}

//technicolor table
if ($bojaCount > 1) {
$boja="#FFFFFF";
$bojaCount=0;
}
else
	{
		$boja="#F0F0F0";
	}
		
//is current user administrator
if (substr_count($user_privileges, "ADMIN")<>"0") { 
$ada='<a class="2" href="users_edit.php?id='.$id.'"><img src="images/app/a.gif" alt="This user is'.$master_text.' administrator" width="20" height="20" border="0"></a>'; 
} 
else { $ada="";}

//table fields
echo '
<tr align="left" valign="middle" bgcolor="'.$boja.'"> 
    <td height="20">&nbsp;'.$brojac_rezultata.'</td>
    <td height="20"><b><span class="maintext"><input type=checkbox name="'.$cbox_name.'" value="'.$id.'" '.$master_lock.'><a class="newslist" href="users_edit.php?id='.$id.'" '.$name_tt.'>'.$full_name.'</a></span></b></td>
    <td height="20" align="center" valign="middle">'.$username.'</td>
    <td height="20" align="center" valign="middle">'.$ada.'</td>
</tr>';



++$i;
++$bojaCount;
++$brojac_rezultata;
}
//end loop

//close table
echo '
<tr align="left" valign="middle" bgcolor="#F0F0F0"> 
    <td height="2"></td>
    <td height="2"></td>
    <td height="2"></td>
    <td height="2"></td>
</tr>

</table>
';


// check\uncheck buttons
if ($totalrows>1) { //only if master admin is not only one listed
echo "
<table width=\"650\" height=\"30\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >
  <tr>
    <td valign=\"bottom\">
	<span class=\"maintext\" id=\"ovde\"></span>
	</td>
  </tr>
</table>
";
}



echo '<br><br><br>';
if ($totalrows>1) {echo '<input type="submit" name="submit" value="Delete user(s) ->" style="width: 100px; height: 26px;" class="formfields2">
&nbsp;&nbsp;';}
echo '<input name="back_button" type="button" class="formfields2" id="back_button" style="width: 75px; height: 26px;" value="Back" onClick="back_go()">

</form>';

}

else  //no results
{
echo '
<span class="maintext"><strong>Status:</strong> None found matching that criteria!</span> &nbsp;<img src="images/app/none.jpg" width="16" height="16" align="absbottom"><br>
<br>
<br>
<meta http-equiv="Refresh" content="3; url=users_search.php">' ;
}

?>
  <br>
  <br>
  <br>

</body>
</html>
