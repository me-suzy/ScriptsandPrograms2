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
$username=$_POST["username"];
$full_name=$_POST["full_name"];
$old=$_POST["old"];
if (isset($_POST["pagelimit"])) {$pagelimit=$_POST["pagelimit"];} else {$pagelimit=$_GET["pagelimit"];}
$page=$_GET["page"];



	if (($username<>"") OR ($full_name<>"") OR ($old<>"")) {$where_start="WHERE ";}
	if ($username<>"") {$trazenje=' username LIKE "%'.$username.'%" ';}
	if ($username<>"") {$dodavanje=" AND ";}
	if ($full_name<>"") {$ime=$dodavanje.' full_name LIKE "%'.$full_name.'%" ';}
	if (($username<>"") OR ($full_name<>"")) {$dodavanje2=" AND ";}
	if ($old<>"") {
		$now_date=time();
		$passed_months=(60*60*24*30)*$old;
		$old_date=$now_date-$passed_months;
		$old_status=$dodavanje2.'last_login < '.$old_date;
		}


//assemble all
$ima_naslova=$where_start.$trazenje.$ime.$old_status;



//***********************************************************************
if ($_SESSION["search_query_visitors"]=="") {
$MySQLQuery="SELECT * FROM ".$db_table_prefix."visitors ".$ima_naslova." ORDER BY idVis DESC"; // proveri sve uslove i daj MySQL QUERY bez limita
           session_start(); //startuje se sesija
   		   $_SESSION["search_query_visitors"]=$MySQLQuery;
}
else
{
$MySQLQuery=$_SESSION["search_query_visitors"];
}

$table_width=700; //table width
$thispage="visitors_list.php"; //this page

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
	this.location="visitors_search.php";
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

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext" <?php if ($totalrows>0) {echo 'onload="checkiraj()"';}?>>
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Visitors: View/Edit Visitors:<span class="titletext0blue"> 
      Visitors listing</span></td>
  </tr>
</table>
<br>
<br>
<?php 





if ($totalrows>0) { 

//multipaging
include "multipage.php";


echo "<form name='delform' action='visitors_delete.php' method='post' onSubmit=\"return delete_go(this)\">";



//table head
echo '<table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr bgcolor="#3C89D1" class="maintext2beli"> 

    <td height="20" width="40" align="left" valign="middle">&nbsp;&nbsp;#</td>
    <td height="20" width="220" align="left" valign="middle">Full name</td>
    <td height="20" width="160" align="left" valign="middle">Username</td>
    <td height="20" width="200" align="left" valign="middle">E-mail</td>
    <td height="20" width="180" align="left" valign="middle">Last login</td>

  </tr>
  <tr align="left" valign="middle"> 

    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>

  </tr>';



//loop
$i=$start;
$brojac_rezultata=$starting_no;

$bojaCount=1;
for ($i=0;$i<$num;$i++) {

$id=mysql_result($result,$i,"idVis");
$username=mysql_result($result,$i,"username");
$full_name=mysql_result($result,$i,"full_name");
$email=mysql_result($result,$i,"email");
$last_login=mysql_result($result,$i,"last_login");
$last_login=date("j. F Y / G:i",$last_login);


//technicolor table
if ($bojaCount > 1) {
$boja="#FFFFFF";
$bojaCount=0;
}
else
	{
		$boja="#F0F0F0";
	}
		



//table fields
echo '
<tr align="left" valign="middle" bgcolor="'.$boja.'"> 

    <td height="20">&nbsp;'.$brojac_rezultata.'</td>
    <td height="20"><b><span class="maintext"><input type=checkbox name="todelete[]" value="'.$id.'">&nbsp;'.$full_name.'</span></b></td>
    <td height="20" align="left" valign="middle">'.$username.'</td>
    <td height="20"><a href="mailto:'.$email.'"><b>'.$email.'</b><a></td>
    <td height="20">'.$last_login.'</td>

</tr>';



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
    <td height="2"></td>
    <td height="2"></td>
</tr>

</table>
';


// check\uncheck dugmad
echo "
<table width=\"700\" height=\"30\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >
  <tr>
    <td valign=\"bottom\">
	<span class=\"maintext\" id=\"ovde\"></span>
	</td>
  </tr>
</table>
";


echo '
<br><br><br>
<input type="submit" name="submit" value="Delete visitor(s) ->" style="width: 100px; height: 26px;" class="formfields2" >
&nbsp;
<input name="back_button" type="button" class="formfields2" id="back_button" style="width: 75px; height: 26px;" value="Back" onClick="back_go()">

</form>
';

}
else  //no results
{
echo '
<span class="maintext"><strong>Status:</strong> None found matching that criteria!</span> &nbsp;<img src="images/app/none.jpg" width="16" height="16" align="absbottom"><br>
<meta http-equiv="Refresh" content="3; url=visitors_search.php">' ;
}



?>

<br>
<br>
<br>

</body>
</html>
