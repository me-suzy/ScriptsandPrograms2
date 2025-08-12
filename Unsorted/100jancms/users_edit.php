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
$id=$_GET["id"];

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
var txt = "<input type=\"button\" name=\"checkall\" value=\"Uncheck All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"checkiraj();checkToggle('editform','articles[]',false); return false;\">";
document.all.ovde.innerHTML=txt;
}

function checkiraj()
{
var txt = "<input type=\"button\" name=\"checkall\" value=\"Check All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"decheckiraj();checkToggle('editform','articles[]',true); return false;\">";
document.all.ovde.innerHTML=txt;
}
// ****************
function decheckiraj3()
{
var txt = "<input type=\"button\" name=\"checkall3\" value=\"Uncheck All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"checkiraj3();checkToggle('editform','comments[]',false); return false;\">";
document.all.ovde3.innerHTML=txt;
}

function checkiraj3()
{
var txt = "<input type=\"button\" name=\"checkall3\" value=\"Check All\" style=\"width: 80px; height: 20px;\" class=\"formfields2\" onClick=\"decheckiraj3();checkToggle('editform','comments[]',true); return false;\">";
document.all.ovde3.innerHTML=txt;
}

function cancel_go()
{
		this.location="users_search.php";
}

function delete_go()
{
	this.location="users_delete.php?id=<?php echo "$id";?>";
}
</script>



</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.editform.full_name.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Users: View/Edit Users: <span class="titletext0blue">Edit User</span></td>
  </tr>
</table>
<br>
<br>
<?php

$query="SELECT * FROM ".$db_table_prefix."users WHERE idUsers=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row


//assign variables
$id=$row["idUsers"];
$full_name=$row["full_name"];  
$username=$row["username"];
$email=$row["email"];
$comment=$row["comment"];


			$old_d=date("j",$row["last_login"]);
			$old_m=date("F",$row["last_login"]);
			$old_y=date("Y",$row["last_login"]);
			$old_h=date("H",$row["last_login"]);
			$old_mi=date("i",$row["last_login"]);

$LL="$old_d. $old_m $old_y.  /  $old_h:$old_mi";


//user_privileges
$query="SELECT * FROM ".$db_table_prefix."users WHERE idUsers=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich ror
$user_privileges=$row["user_privileges"];
$lord=$row["lord"];


if (substr_count($user_privileges, "ADMIN")<>"0") 
{$da_li="checked";} else {$da_li="";}
//lord status
if ($lord==1) {$lord_status=" disabled ";$master_lock=" disabled";} else {$lord_status="";$master_lock="";}

?>
<form action="users_insert.php" method=post enctype="multipart/form-data" name="editform" onSubmit="return checkform(editform);">
  <input name="action" type="hidden" id="action" value="edit">
    <input name="id" type="hidden" id="id" value="<?php echo "$id";?>">
  <table width="314" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="80" class="maintext"><strong>Full Name:</strong></td>
      <td><input name="full_name" type="text" class="formfields" id="full_name" value="<?php echo "$full_name";?>" size="30" maxlength="255" alt="anything" emsg="Full name"> 
        <img src="images/app/asterix.jpg" width="9" height="8"> </td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Username:</strong></td>
      <td><input name="username" type="text" class="formfields" id="username2" value="<?php echo "$username";?>" size="30" maxlength="255" alt="anything" emsg="Username"> 
        <img src="images/app/asterix.jpg" width="9" height="8"> </td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Password:</strong></td>
      <td><input name="password" type="password" class="formfields" id="password" size="30" maxlength="255" alt="length" min="6" optional="true" emsg="Password" title="password not shown for security"> 
      </td>
    </tr>
    <tr> 
      <td class="maintext"><strong>E-mail:</strong></td>
      <td><input name="email" type="text" class="formfields" id="email" size="30" maxlength="255" value="<?php echo "$email";?>" alt="email" optional="true" emsg="E-mail">
      </td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Comment:</strong></td>
      <td> <textarea name="comment" class="formfields" id="textarea2" style="width:180; height:100"><?php echo "$comment";?></textarea> 
      </td>
    </tr>
    <tr> 
      <td class="maintext"><strong>Last login:</strong></td>
      <td class="maintext"><input name="LL" type="text" class="formfields" id="LL" size="30" maxlength="255" value="<?php echo "$LL";?>" readonly></td>
    </tr>
  </table>
  <br>
  <span class="maintext"><strong><span class="maintext">Privileges: <img src="images/app/asterix.jpg" width="9" height="8"> 
  </span><br>
        <br>
        </strong></span>
        
  <table width="300" border="0" cellpadding="0" cellspacing="0" class="maintext">
          
    <tr align="center" valign="middle" bgcolor="#3C89D1" class="maintext2beli"> 
            
      <td height="20" align="left" bgcolor="#3C89D1">&nbsp;Administrator</td>
    </tr>
          
    <tr align="left" valign="middle"> 
            
      <td height="20">&nbsp;</td>
    </tr>
          
    <tr align="left" valign="middle" bgcolor="#F0F0F0"> 
            
      <td height="20"><input name="admin" align="absmiddle" type="checkbox" id="admin" value="1" <?php echo $da_li; echo $lord_status;?> >
              Full access </td>
    </tr>
        
  </table>
  <?php 
  if ($lord==1) {
  //display message
  echo '<img src="images/app/i16.gif" width="16" height="16" align="absbottom">&nbsp;<b>Info:</b>&nbsp;This user is a master administrator.<br>';
  //bypass disabled admin checkbox
  echo '<input name="admin" type="hidden" id="admin" value="1">';
  } 
  ?>
 
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

//check if checked

if (substr_count($user_privileges, "ARTICLES["."$name1"."]")<>"0") 
{$da_li="checked";} else {$da_li="";}
		
//table fields
echo '
<tr align="left" valign="middle" bgcolor="'.$boja.'"> 
    <td height="20"><input type=checkbox name="articles[]" value="'.$name1.'" '.$da_li.'>'.$name1.'</td>
</tr>';



++$bojaCount;
} //for

// close table
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

if (substr_count($user_privileges, "COMMENTS["."$name1"."]")<>"0") 
{$da_li="checked";} else {$da_li="";}		

//table fields
echo '
<tr align="left" valign="middle" bgcolor="'.$boja.'"> 
    <td height="20"><input type=checkbox name="comments[]" value="'.$name1.'" '.$da_li.'><b>ARTICLES</b> > '.$name1.'</td>
</tr>';


++$bojaCount;
} //for


//close tables
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
<input type="submit" name="submit" value="Save -&gt;" style="width: 90px; height: 26px;" class="formfields2">
&nbsp;
<input name="delete" type="button" class="formfields2" id="delete" style="width: 75px; height: 26px;" value="Delete user" onClick="delete_go()" <?php echo $master_lock;?>>
&nbsp;
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Cancel" onClick="cancel_go()">
        <br>
        <br>
</form>

  <br>
  <br>
  <br>
  
</body>
</html>
