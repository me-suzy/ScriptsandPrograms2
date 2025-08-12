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
  '1' => 'COMMENTS_MASTER',  
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 


//receive posted data
$c_state=$_POST["c_state"];
$c_text=$_POST["c_text"];
$marker=$_POST["marker"];
if (isset($_POST["pagelimit"])) {$pagelimit=$_POST["pagelimit"];} else {$pagelimit=$_GET["pagelimit"];}
$to_query=$_POST["to_query"];
$page=$_GET["page"];




	if ($marker<>"") {$kategorija=$dodavanje."marker='".$marker."'";} else {$kategorija=$dodavanje."(".$to_query.")";}
	if ($c_text<>"") {$trazenje=" AND text LIKE \"%".$c_text."%\" ";}
	//c_state
	if ($c_state=="for approval") {$c_approval=" AND approval='0'";} 
	if ($c_state=="approved") {$c_approval=" AND approval='1'";} 	



//assebmle all
$ima_naslova="WHERE ".$kategorija.$trazenje.$c_approval;




//***********************************************************************
if ($_SESSION["search_query_comments"]=="") {
$MySQLQuery="SELECT * FROM ".$db_table_prefix."comments ".$ima_naslova." ORDER BY date DESC"; 
           session_start(); //start session
		   $_SESSION["search_query_comments"]=$MySQLQuery;

}
else
{
$MySQLQuery=$_SESSION["search_query_comments"];
}

$table_width=760; //table width
$thispage="comments_list.php"; //this page

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
	this.location="comments_search.php";
}



function submit_go(delform) {
var to_do=0;
	if (! document.delform["todelete[]"].length && document.delform["todelete[]"].checked) 
		var to_do=1;

	else {
		for (var i = 0; i < document.delform["todelete[]"].length; i++) {
			if (document.delform["todelete[]"][i].checked)
			var to_do=1;
		}
	}
//submit or not
if (to_do==1) 
	document.delform.submit();
	else {
	alert("None selected!");
	}
}

</script>



</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0"  class="maintext" <?php if ($totalrows>0) {echo 'onload="checkiraj()"';}?> >
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Comments: View/Edit Comments:<span class="titletext0blue"> 
      Comments listing</span></td>
  </tr>
</table>
 <br>
 <br>
  <?php 

if ($totalrows>0) { 


//multipaging
include "multipage.php"; 



echo "<form name='delform' action='' method='post' >";

//head table
echo '<table width="760" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr bgcolor="#3C89D1" class="maintext2beli"> 

    <td height="20" width="40" align="left" valign="middle">&nbsp;&nbsp;#</td>
    <td height="20" width="10" align="center" valign="middle"></td>
    <td height="20" width="290" align="center" valign="middle">Comment text</td>
    <td height="20" width="100" align="center" valign="middle">Added by</td>
    <td height="20" width="150" align="left" valign="middle">Marker</td>
    <td height="20" width="130" align="left" valign="middle">Date / Time</td>
    <td height="20" width="20" align="left" valign="middle">Edit</td>	
	<td height="20" width="20" align="right" >A&nbsp;&nbsp;</td>	

  </tr>

  <tr align="left" valign="middle"> 

    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
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

$id=mysql_result($result,$i,"idComm");
$text=mysql_result($result,$i,"text");
$added_by=mysql_result($result,$i,"added_by");
$marker=mysql_result($result,$i,"marker");
$dajdate=mysql_result($result,$i,"date");
$jedandate=date('j. F Y / G:i',"$dajdate");
$section=mysql_result($result,$i,"section");
$approval=mysql_result($result,$i,"approval");
//approval
if ($approval==1) {
$approval_status='<img src="images/app/active.gif" alt="This comment is approved" width="20" height="20" border="0">';
}
else {
$approval_status='<a href="comments_approve.php?id='.$id.'" class="2"><img src="images/app/archive.gif" alt="This comment is NOT approved.
[Click to approve this comment]" width="20" height="20" border="0"></a>';
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
		


//table fields
echo '
<tr align="left" valign="middle" bgcolor="'.$boja.'">

    <td height="20" valign="top">&nbsp;'.$brojac_rezultata.'</td>
    <td height="20" align="left" valign="top"><input type=checkbox name="todelete[]" value="'.$id.'"></td>
    <td height="20" valign="top"><span class="maintext">&nbsp;'.$text.'</span></td>
    <td height="20" align="center" valign="top">'.$added_by.'</td>
    <td height="20" align="left" valign="top">'.$section." > ".$marker.'</td>
    <td height="20" valign="top">'.$jedandate.'</td>
    <td height="20" align="left" valign="top"><a href="comments_edit.php?id='.$id.'" class="2" ><img src="images/app/m_notepad.gif" alt="Edit this comment" width="20" height="20" border="0"></a>&nbsp;</td>	
    <td height="20" valign="top">'.$approval_status.'</td>
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
    <td height="2"></td>
    <td height="2"></td>
</tr>

</table>
';

// check\uncheck buttons
echo "
<table width=\"760\" height=\"30\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >
  <tr>
    <td valign=\"bottom\">
	<span class=\"maintext\" id=\"ovde\"></span>
	</td>
  </tr>
</table>
";


if ($c_state=="for approval") {

$approve_button='
<input type="button" name="approve_button" value="Approve comment(s) ->" class="formfields2" id="approve_button" style="width: 120px; height: 26px;" OnClick="document.delform.action=\'comments_approve.php\';submit_go(this);">
&nbsp;';
}
else {$approve_button="";}


echo '<br><br><br>
'.$approve_button.'
<input type="button" name="delete_button" value="Delete comment(s) ->" class="formfields2" id="delete_button" style="width: 120px; height: 26px;" OnClick="document.delform.action=\'comments_delete.php\';submit_go(this);">
&nbsp;
<input name="back_button" type="button" class="formfields2" id="back_button" style="width: 75px; height: 26px;" value="Back" onClick="back_go()">
';

}
else  //no results
{
echo '
<span class="maintext"><strong>Status:</strong> None found matching that criteria!</span> &nbsp;<img src="images/app/none.jpg" width="16" height="16" align="absbottom"><br>
<br>
<br>
<meta http-equiv="Refresh" content="3; url=comments_search.php">' ;
}



?>

<br>
<br>
<br>
<br>
<br>


</body>
</html>
