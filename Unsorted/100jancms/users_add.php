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

<script type="text/javascript" src="checkform.js"></script>

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
var txt = "<input type=\"button\" name=\"checkall\" value=\"Uncheck All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"checkiraj();checkToggle('addform','articles[]',false); return false;\">";
document.all.ovde.innerHTML=txt;
}

function checkiraj()
{
var txt = "<input type=\"button\" name=\"checkall\" value=\"Check All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"decheckiraj();checkToggle('addform','articles[]',true); return false;\">";
document.all.ovde.innerHTML=txt;
}

function decheckiraj3()
{
var txt = "<input type=\"button\" name=\"checkall3\" value=\"Uncheck All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"checkiraj3();checkToggle('addform','comments[]',false); return false;\">";
document.all.ovde3.innerHTML=txt;
}

function checkiraj3()
{
var txt = "<input type=\"button\" name=\"checkall3\" value=\"Check All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"decheckiraj3();checkToggle('addform','comments[]',true); return false;\">";
document.all.ovde3.innerHTML=txt;
}

</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.addform.full_name.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Users: <span class="titletext0blue"> Add new User </span></td>
  </tr>
</table>
<br>
<br>
<form action="users_insert.php" method="post" enctype="multipart/form-data" name="addform" onSubmit="return checkform(addform);">
  <input name="action" type="hidden" id="action" value="new">
  <table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
    <tr> 
      <td width="314"><table width="314" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="80" class="maintext"><strong>Full Name:</strong></td>
          <td><input name="full_name" type="text" class="formfields" id="full_name" size="30" maxlength="255" alt="anything" emsg="Full name"> 
            <img src="images/app/asterix.jpg" width="9" height="8"> </td>
        </tr>
        <tr> 
          <td width="80" class="maintext"><strong>Username:</strong></td>
          <td><input name="username" type="text" class="formfields" id="username" size="30" maxlength="255" alt="anything" emsg="Username"> 
            <img src="images/app/asterix.jpg" width="9" height="8"> </td>
        </tr>
        <tr> 
          <td width="80" class="maintext"><strong>Password:</strong></td>
          <td><input name="password" type="password" class="formfields" id="password" size="30" maxlength="255" alt="length" min="6" emsg="Password"> 
            <img src="images/app/asterix.jpg" width="9" height="8"> </td>
        </tr>
        <tr> 
          <td class="maintext"><strong>E-mail:</strong></td>
          <td><input name="email" type="text" class="formfields" id="email" size="30" maxlength="255" alt="email" optional="true" emsg="E-mail">
          </td>
        </tr>
        <tr> 
          <td width="80" class="maintext"><strong>Comment:</strong></td>
          <td> <textarea name="comment" class="formfields" id="textarea" style="width:180; height:100"></textarea> 
          </td>
        </tr>
      </table>
        <br>
      <strong><span class="maintext">Privileges: <img src="images/app/asterix.jpg" width="9" height="8"> 
      <br>
          </span></strong><br>

        <table width="300" border="0" cellpadding="0" cellspacing="0" class="maintext">
          <tr align="center" valign="middle" bgcolor="#3C89D1" class="maintext2beli"> 
            <td height="20" align="left" bgcolor="#3C89D1">&nbsp;Administrator</td>
          </tr>
          <tr align="left" valign="middle"> 
            <td height="20">&nbsp;</td>
          </tr>
          <tr align="left" valign="middle" bgcolor="#F0F0F0"> 
            <td height="20"><input name="admin" type="checkbox" id="admin" value="admin">
              Full access</td>
          </tr>
        </table>
        <br>
<br>

          
        <?php 
//ARTICLES ************************************

echo '<table width="300" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr bgcolor="#3C89D1" class="maintext2beli"> 
    <td height="20" width="40" align="left" valign="middle">&nbsp;Articles</td>
  </tr>
  <tr align="left" valign="middle"> 
    <td height="20">&nbsp;</td>
  </tr>';

$query="SELECT marker FROM ".$db_table_prefix."articles_marker ORDER BY marker";
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

$bojaCount=1;
for ($i=0;$i<$num;$i++) {

$name1=mysql_result($result,$i,"marker");
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
    <td height="20"><input type=checkbox name="articles[]" value="'.$name1.'" >'.$name1.'</td>
</tr>';




++$bojaCount;
} //for

//close table
echo '
<tr align="left" valign="middle" bgcolor="#F0F0F0"> 
    <td height="2"></td>
</tr>
</table>
';


// check\uncheck dugmad
if ($num>0) {
echo "

<table width=\"300\" height=\"30\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >
  <tr>
    <td valign=\"bottom\">
	<span class=\"maintext\" id=\"ovde\"></span>
	</td>
  </tr>
</table>

<script language=\"JavaScript\" type=\"text/JavaScript\">
checkiraj();
</script>
";
}

?>
        <br>
        <br>
        <?php 
//COMMENTS ************************************

echo '<table width="300" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr bgcolor="#3C89D1" class="maintext2beli"> 
    <td height="20" width="40" align="left" valign="middle">&nbsp;Comments</td>
  </tr>
  <tr align="left" valign="middle"> 
    <td height="20">&nbsp;</td>
  </tr>';

$query="SELECT marker FROM ".$db_table_prefix."articles_marker ORDER BY marker";
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

$bojaCount=1;
for ($i=0;$i<$num;$i++) {

$name1=mysql_result($result,$i,"marker");
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
    <td height="20"><input type=checkbox name="comments[]" value="'.$name1.'" ><b>ARTICLES</b> > '.$name1.'</td>
</tr>';



++$bojaCount;
} //for



//close table
echo '
<tr align="left" valign="middle" bgcolor="#F0F0F0"> 
    <td height="2"></td>
</tr>
</table>
';


// check\uncheck dugmad
if ($num>0) {
echo "
<table width=\"300\" height=\"30\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >
  <tr>
    <td valign=\"bottom\">
	<span class=\"maintext\" id=\"ovde3\"></span>
	</td>
  </tr>
</table>

<script language=\"JavaScript\" type=\"text/JavaScript\">
checkiraj3();
</script>
";
}

?>
        <br>
        <br>
		<br>
		<br>
<input type="submit" name="submit" value="Add user -&gt;" style="width: 90px; height: 26px;" class="formfields2">                 
</form>
<br>
          <br>


<br>
<br>
<br>

</body>
</html>
