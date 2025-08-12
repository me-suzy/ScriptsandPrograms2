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
	'1' => 'ARTICLES_MASTER',
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php';


//receive posted data
$article_title=$_POST["article_title"];
$marker=$_POST["marker"];
$category=$_POST["category"];
$con_text=$_POST["con_text"];
$expired=$_POST["expired"];
$past=$_POST["past"];
$from_day=$_POST["from_day"];
$from_month=$_POST["from_month"];
$from_year=$_POST["from_year"];
$to_day=$_POST["to_day"];
$to_month=$_POST["to_month"];
$to_year=$_POST["to_year"];
if (isset($_POST["pagelimit"])) {$pagelimit=$_POST["pagelimit"];} else {$pagelimit=$_GET["pagelimit"];}
$to_query=$_POST["to_query"];
$page=$_GET["page"];





	if ($marker<>"") {$kategorija=$dodavanje."marker='".$marker."'";} else {$kategorija=$dodavanje."(".$to_query.")";}
	if ($category<>"") {$trazenje2=" AND category='".$category."'";}
	if ($article_title<>"") {$trazenje=' AND title LIKE "%'.$article_title.'%" ';}
	if ($con_text<>"") {$containing=$dodavanje2." AND text LIKE \"%".$con_text."%\"";}



//process FROM-TO date
if (($past=="") AND ($from_day<>"") AND ($from_month<>"") AND ($from_year<>"") AND ($to_day<>"") AND ($to_month<>"") AND ($to_year<>"")) {

$from_date=mktime(0,0,0,$from_month,$from_day,$from_year);
$to_date=mktime(0,0,0,$to_month,$to_day,$to_year);
$date_search=" AND (date BETWEEN ".$from_date." AND ".$to_date.") ";
}

//process PAST date
		if ($past<>"") {
// get the current timestamp
$timestamp =  time();
$date_time_array =  getdate($timestamp);

$hours =  $date_time_array["hours"];
$minutes =  $date_time_array["minutes"];
$seconds =  $date_time_array["seconds"];
$month =  $date_time_array["mon"];
$day =  $date_time_array["mday"];
$year =  $date_time_array["year"];

// recreate the unix timestamp
$timestamp=mktime($hours, $minutes,$seconds ,$month, $day - $past,$year);
$date_search=" AND date > '".$timestamp."'";
}

//process EXPIRE date
if ($expired==1) {
$now_date=time();
$exp_date=(60*60*24);
$date_search=" AND ($now_date > (date + ($exp_date * expire))) AND expire <> 0 ";
}


//assemble all
$ima_naslova="WHERE ".$kategorija.$trazenje2.$trazenje.$containing.$date_search;

//***********************************************************************
if (!isset($_SESSION["search_query_articles"])) {
	 $MySQLQuery="SELECT * FROM ".$db_table_prefix."articles_items ".$ima_naslova." ORDER BY priority DESC, date DESC, idArtc DESC"; 
           session_start(); //start session
		   $_SESSION["search_query_articles"]=$MySQLQuery;
}
else
{
$MySQLQuery=$_SESSION["search_query_articles"];
}

$table_width=750; //table width
$thispage="articles_items_list.php"; //this page

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
document.all.source_button.disabled=true;
document.all.target_button.disabled=true;
}

function checkiraj()
{
var txt = "<input type=\"button\" name=\"checkall\" value=\"Check All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"decheckiraj();checkToggle('delform','todelete[]',true); return false;\">";
document.all.ovde.innerHTML=txt;
document.all.source_button.disabled=true;
document.all.target_button.disabled=true;
}

function back_go()
{
	this.location="articles_items_search.php";
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

function move_state() {
var brojac=0;
	if (! delform["todelete[]"].length && delform["todelete[]"].checked)
		brojac++;
	else {
		for (var i = 0; i < delform["todelete[]"].length; i++) {
			if (delform["todelete[]"][i].checked)
		brojac++;
		}
	}

if (brojac == 1){ //if one selected - GOOD
	if (document.cookie.indexOf("move_source") != -1) { //if cookie exists
		document.all.source_button.disabled=true;
		document.all.target_button.disabled=false;
		document.all.cancel_button.disabled=false;
		}
		else { //if cookie does NOT exists
		document.all.source_button.disabled=false;
		document.all.target_button.disabled=true;
		document.all.cancel_button.disabled=true;
		}

}
else { //if selected more that one - BAD
		if (document.cookie.indexOf("move_source") != -1) { //if cookie exists
		document.all.source_button.disabled=true;
		document.all.target_button.disabled=true;
		document.all.cancel_button.disabled=false;
		}
		else { //if cookie does NOT exists
		document.all.source_button.disabled=true;
		document.all.target_button.disabled=true;
		document.all.cancel_button.disabled=true;
		}

	} //brojac selected end
} //function end


function SetCookie(cookieName,cookieValue,nMinutes) {
 var today = new Date();
 var expire = new Date();
 if (nMinutes==null || nMinutes==0) nMinutes=1;
 expire.setTime(today.getTime() + 60000*nMinutes);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
}

function ReadCookie(cookieName) {
 var theCookie=""+document.cookie;
 var ind=theCookie.indexOf(cookieName);
 if (ind==-1 || cookieName=="") return ""; 
 var ind1=theCookie.indexOf(';',ind);
 if (ind1==-1) ind1=theCookie.length; 
 return unescape(theCookie.substring(ind+cookieName.length+1,ind1));
}

function DeleteCookie(cookieName,cookieValue,nMinutes) {
 var today = new Date();
 var expire = new Date();
 if (nMinutes==null || nMinutes==0) nMinutes=1;
 expire.setTime(today.getTime() - 60000*nMinutes);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
}

function source_go() {

document.all.source_button.disabled=true;
document.all.target_button.disabled=true;
document.all.cancel_button.disabled=false;

document.all.moveinfo.innerHTML="[source selected] select target>&nbsp;&nbsp;";
//alert ("[Moving article]\n\nSource article selected. Now select target article...");

var moving_source=null;
	if (! delform["todelete[]"].length && delform["todelete[]"].checked)
		moving_source=delform["todelete[]"].value;
					
		for (var i = 0; i < delform["todelete[]"].length; i++) {
			if (delform["todelete[]"][i].checked)
		moving_source=delform["todelete[]"][i].value;
		}

SetCookie("move_source",moving_source,60);
checkiraj();
checkToggle('delform','todelete[]',false); return false; //uncheck all
}


function target_go() {

var moving_target=null;

	if (! delform["todelete[]"].length && delform["todelete[]"].checked)
		moving_target=delform["todelete[]"].value;
		
		for (var i = 0; i < delform["todelete[]"].length; i++) {
			if (delform["todelete[]"][i].checked)
		moving_target=delform["todelete[]"][i].value;
		}
SetCookie("move_target",moving_target,60);
//alert (moving_target);
	this.location="articles_items_move_check.php";
}



function set_move_buttons() {

if (document.cookie.indexOf("move_source") != -1) {
//alert ("cookie exist");
document.all.source_button.disabled=true;
document.all.target_button.disabled=false;
document.all.cancel_button.disabled=false;
document.all.moveinfo.innerHTML="[source selected] select target>&nbsp;&nbsp;";
	}
else {
//alert ("cookie does NOT exist");
document.all.source_button.disabled=true;
document.all.target_button.disabled=true;
document.all.cancel_button.disabled=true;
	}	

}

function cancel_go() {
//clear buttons
document.all.source_button.disabled=true;
document.all.target_button.disabled=true;
document.all.cancel_button.disabled=true;
document.all.moveinfo.innerHTML="";
//clear cookies
DeleteCookie("move_source","cookie expired",100);
DeleteCookie("move_target","cookie expired",100);
//clear all selected
checkiraj();
checkToggle('delform','todelete[]',false); return false; //uncheck all
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

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext" <?php if ($totalrows<>0) {echo 'onload="checkiraj();move_state();set_move_buttons()"';}?>>
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Articles:<span class="titletext0blue"> Articles listing</span></td>
  </tr>
</table>
 <br>
 <br>
  <?php 



if ($totalrows>0) {


//multipaging results
include "multipage.php"; 


echo "<form name='delform' action='' method='post' >";


//head tabele
echo '<table width="750" border="0" cellpadding="0" cellspacing="0" class="listing" >
  <tr bgcolor="#3C89D1" class="maintext2beli"> 
    <td height="20" width="40" align="left" valign="middle">&nbsp;&nbsp;#</td>
    <td height="20" width="10" align="center" valign="middle"></td>
    <td height="20" width="350" align="center" valign="middle">Title</td>
    <td height="20" width="120" align="left" valign="middle">Marker</td>
    <td height="20" width="130" align="left" valign="middle">Date / Time</td>
    <td height="20" width="60" align="center" valign="middle">Status</td>	
    <td height="20" width="20" align="right" valign="middle">P&nbsp;&nbsp;</td>	
  </tr>
  <tr align="left" valign="middle"> 
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

$id=mysql_result($result,$i,"idArtc");
$title=mysql_result($result,$i,"title");
$marker=mysql_result($result,$i,"marker");
$priority=mysql_result($result,$i,"priority");
$flag=mysql_result($result,$i,"flag");
$status=mysql_result($result,$i,"status");
$expire=mysql_result($result,$i,"expire");
if ($status=="suspended" or $status=="expired") {$status="<span class=\"mg\">$status</span>";}

$date=mysql_result($result,$i,"date");
$jedandate=date('j. F Y / H:i',"$date");
//technicolor table
if ($bojaCount > 1) {
$boja="#FFFFFF";
$bojaCount=0;
}
else
	{
		$boja="#F0F0F0";
	}
		
//priority
if ($priority=="1")
{$slovo='<a href="articles_items_edit.php?id='.$id.'" class="2"><img src="images/app/i.gif" alt="This article has priority" width="20" height="20" border="0"></a>';}
else {$slovo='';}

//expiration
$now_date=time();
$exp_date=(60*60*24);

if (($now_date > ($date + ($exp_date * $expire))) AND ($expire<>0)) 
{$title_class='class="expiration" title="article expired"';}
else {$title_class='class="newslist"';}

//table fields
echo '
<tr align="left" valign="middle" bgcolor="'.$boja.'"> 
    <td height="20" >&nbsp;'.$brojac_rezultata.'</td>
    <td height="20" ><span class="maintext"><input type=checkbox name="todelete[]" value="'.$id.'" onClick="move_state()"></td>
    <td height="20" ><b>&nbsp;<a '.$title_class.' href="articles_items_edit.php?id='.$id.'">'.$title.'</a></span></b></td>
    <td height="20" align="left" >'.$marker.'</td>
    <td height="20" >'.$jedandate.'</td>
    <td height="20" align="center">'.$status.'</td>	
    <td height="20" align="left">'.$slovo.'</td>
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
echo '
<table width="750" height="30" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr>
    <td valign="bottom"><span class="maintext" id="ovde"></span></td>
    <td valign="bottom" align="right">
<span id="moveinfo"></span>
<input name="source_button" type="button" class="formfields2" id="source_button" style="width: 45px; height: 20px;" value="Source" title="Select source article to move" onClick="source_go()">
<input name="target_button" type="button" class="formfields2" id="target_button" style="width: 45px; height: 20px;" value="Target" title="Select target article where moving" onClick="target_go()" >
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 45px; height: 20px;" value="Cancel" title="Cancel article moving" onClick="cancel_go()" >	
	</td>
  </tr>
</table>
';

//multipaging results
//echo "<br>";
//include "multipage.php"; 

echo '
<br>
<br>
<br>
<input type="button" name="delete_button" value="Delete article(s) ->" style="width: 100px; height: 26px;" class="formfields2" OnClick="document.delform.action=\'articles_items_delete.php\';submit_go(this);">
&nbsp;&nbsp;
<input type="button" name="reassign_button" value="Reassign marker ->" style="width: 110px; height: 26px;" class="formfields2" OnClick="document.delform.action=\'articles_items_reassign.php\';submit_go(this);">
&nbsp;&nbsp;
<input type="button" name="back_button" class="formfields2" id="back_button" style="width: 75px; height: 26px;" value="Back" onClick="back_go()">

</form>';

}

else  //no results
{
echo '
<span class="maintext"><strong>Status:</strong> None found matching that criteria!</span> &nbsp;<img src="images/app/none.jpg" width="16" height="16" align="absbottom"><br>
<br>
<br>
<meta http-equiv="Refresh" content="3; url=articles_items_search.php">' ;
}


?>

  <br>
  <br>
  <br>
  
</body>
</html>
