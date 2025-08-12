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
$marker=$_POST["marker"];
if (isset($_POST["pagelimit"])) {$pagelimit=$_POST["pagelimit"];} else {$pagelimit=$_GET["pagelimit"];}
$page=$_GET["page"];




if ($marker<>"") {$search='WHERE marker LIKE "%'.$marker.'%"';} 


//***********************************************************************
$MySQLQuery="SELECT * FROM ".$db_table_prefix."articles_marker ".$search." ORDER BY marker";

$table_width=400; //table width
$thispage="articles_marker_list.php"; //this page

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
	this.location="articles_marker_search.php";
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

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" <?php if ($totalrows>1) {echo 'onload="checkiraj()"';} ?> class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Markers:<span class="titletext0blue"> 
      Markers listing</span></td>
  </tr>
</table>
<p> <br>
  <?php 



if ($totalrows>0) {


//multipaging
include "multipage.php"; 


echo "<form name='delform' action='articles_marker_delete.php' method='post' onSubmit=\"return delete_go(this)\">"; 


//table head
echo '<table width="400" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr bgcolor="#3C89D1" class="maintext2beli"> 

    <td height="20" width="40" align="left" valign="middle">&nbsp;&nbsp;#</td>
    <td height="20" width="360" align="left" valign="middle">Marker</td>

  </tr>
  <tr align="left" valign="middle"> 

    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>

  </tr>';


//loop results

$i=$start;
$brojac_rezultata=$starting_no;

$bojaCount=1;
for ($i=0;$i<$num;$i++) {

$id=mysql_result($result,$i,"idMark");
$marker=mysql_result($result,$i,"marker");

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
    <td height="20"><input type=checkbox name="todelete[]" value="'.$id.'">&nbsp;<b><a class="newslist" href="articles_marker_edit.php?id='.$id.'">'.$marker.'</a></b></td>

</tr>';


++$bojaCount;
++$brojac_rezultata;
}
//end loop

// close table
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

// check\uncheck buttons
echo "
<table width=\"400\" height=\"30\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >
  <tr>
    <td valign=\"bottom\">
	<span class=\"maintext\" id=\"ovde\"></span>
	</td>
  </tr>
</table>
";


echo '
<br>
<br><br><input type="submit" name="submit" value="Delete marker(s) ->" style="width: 110px; height: 26px;" class="formfields2" >
&nbsp;
<input name="back_button" type="button" class="formfields2" id="back_button" style="width: 75px; height: 26px;" value="Back" onClick="back_go()">
</form>
';

}
else  //no results
{
echo '
<span class="maintext"><strong>Status:</strong> None found matching that criteria!</span> &nbsp;<img src="images/app/none.jpg" width="16" height="16" align="absbottom"><br>
<br>
<br>
<meta http-equiv="Refresh" content="3; url=articles_marker_search.php">
';

}



?>
<br>
<br>
<br>

</body>
</html>
