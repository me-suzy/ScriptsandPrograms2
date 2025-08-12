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
$id=$_GET["id"];



?>
<html>
<head>
<title></title>
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

<script language="Javascript1.2"> // load htmlarea
_editor_url = "htmlarea/";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }


function update_textbox()
{
 
var d = new Date()
dan = d.getDate()
mesec = d.getMonth() + 1
godina = d.getFullYear()
sat = d.getHours()
minut = d.getMinutes()
seconds = d.getSeconds()
if (minut <10) {minut='0'+ minut}
document.editform.d.value = dan;
document.editform.m.value = mesec;
document.editform.y.value = godina;
document.editform.h.value = sat;
document.editform.mi.value = minut;
document.editform.sec.value = seconds;

} 

function up()
{

var txt = "&nbsp;<span class='maintext'>Updated!</span> &nbsp;<img src='images/app/all_good.jpg' width='16' height='16' align='absmiddle'><br>";
document.all.up.innerHTML=txt;
var txt2 ="";
setTimeout("document.all.up.innerHTML=''",3000);

}

function delete_go()
{
	this.location="articles_items_delete.php?id=<?php echo "$id";?>";
}

function image_go()
{
    window.open('articles_items_image_view.php?id=<?php echo "$id";?>','articleimage','toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes')
}

function delete_image()
{
	if(confirm("Are you sure you want to delete image?")) 
		{
	this.location="articles_items_image_delete.php?id=<?php echo "$id";?>";
		}
}

function cancel_go()
{
		this.location="articles_items_search.php";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.editform.article_title.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable">
  <tr> 
    <td class="titletext0">Articles: View/Edit Articles: <span class="titletext0blue">Edit Article</span></td>
  </tr>
</table>
<br>
<br>
<?php

$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row



//assign variables
$article_title=$row["title"]; 
$selected_marker=$row["marker"];
$selected_category=$row["category"];

$image=$row["image"];
$position=$row["position"];

if ($position=="left"){
$pose='
<select name="position" class="formfields" id="position">
          <option value="left">Left position</option>
          <option value="right">Right position</option>
        </select>
';
	}
else
	{ 
$pose='
<select name="position" class="formfields" id="position">
          <option value="right">Right position</option>
          <option value="left">Left position</option>
        </select>
';
	}
	//..
$alt=$row["alt"];
$source=$row["source"];
$location=$row["location"];
$keywords=$row["keywords"];
$expire=$row["expire"];
$visits=$row["visits"];
$priority=$row["priority"];
$rate=$row["rate"];
$added_by=$row["added_by"];
$edited_by=$row["edited_by"];
$date=$row["date"];

if ($edited_by=="") {$edited_by="not edited";}

//priority
if ($priority==1) {$priority=" checked";} else {$priority="";}

$text=$row["text"];
$text2=$row["text2"];

//comments
$comments_allow=$row["comments_allow"];
if ($comments_allow==1) {$comments_allow=" checked";} else {$comments_allow="";}

//registered
$comments_registered=$row["comments_registered"];
if ($comments_registered==1) {$comments_registered=" checked";} else {$comments_registered="";}

//comments_approve
$comments_approve=$row["comments_approve"];
if ($comments_approve==1) {$comments_approve=" checked";} else {$comments_approve="";}

//flag
$flag=$row["flag"];
if ($flag==1) {$flag=" checked";} else {$flag="";}

//date
			$old_d=date("j",$row["date"]);
			$old_m=date("n",$row["date"]);
			$old_y=date("Y",$row["date"]);
			$old_h=date("G",$row["date"]);
			$old_mi=date("i",$row["date"]);
			//fix seconds reading - remove leading zero
 			$old_sec=date("s",$row["date"]);
	 		$old_sec = intval(date("$old_sec"));

//load authors username
$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row
//assign variables
$login_ime_usera=$row["user"];

//load authors username
$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$login_ime_usera."'";
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row
//assign variables
$imeusera=$row["name"];


//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_edit1'";
$result=mysql_query($query);
$articles_editor_edit1=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_edit2'";
$result=mysql_query($query);
$articles_editor_edit2=mysql_result($result,0,"config_value");

?>

<form action="articles_items_insert.php" method="post" enctype="multipart/form-data" name="editform" onSubmit="return checkform(editform);">
  <input name="action" type="hidden" id="action" value="edit">
  <input name="id" type="hidden" id="id" value="<?php echo "$id";?>">
  <table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="80" class="maintext"><strong>Title:</strong></td>
      <td><input name="article_title" type="text" class="formfields" id="article_title" value="<?php echo "$article_title";?>" size="63" maxlength="255" alt="anything" emsg="Title">
         
        &nbsp;<img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"> </td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Marker:</strong></td>
      <td><select name="marker" class="formfields" id="marker" style="width:247" alt="anything" emsg="Marker (no privileges)">
          <?php 
		    //load user_privileges for current user
			$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row
			$user_privileges=$row["user_privileges"];
		  
		  
			//load all markers
			$query="SELECT * FROM ".$db_table_prefix."articles_marker ORDER BY marker";
			$result=mysql_query($query);
			$num=mysql_numrows($result); //how many rows
			

$odabrano="";
//loop
$i=0;
$marker_count=0;
while ($i < $num) {
			$marker=mysql_result($result,$i,"marker");

// check for selected marker		
if ($marker=="$selected_marker") {
$odabrano='selected';
}
else {
$odabrano='';
}

if (substr_count($user_privileges, "ARTICLES["."$marker"."]")<>"0") {
echo "<option value=\"".$marker."\" ".$odabrano.">".$marker."</option>";
$marker_count++;
}

++$i;
}

//if there is no markers
if ($marker_count==0) {echo '<option value=""></option>';}

		  ?>
        </select>
         &nbsp;<img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"> 
      </td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Status:</strong></td>
      <td><select name="status" class="formfields" id="status">
          <option value="active" >Active</option>
          <option value="suspended" >Suspended</option>
        </select></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Category:</strong></td>
      <td><select name="category" class="formfields" id="category" style="width:247">
          <?php 
		  //load all categories
			$query="SELECT * FROM ".$db_table_prefix."articles_category ORDER BY category";
			$result=mysql_query($query);
			$num=mysql_numrows($result); //how many row
			
//null category
echo "<option value=\"\" selected></option>";

//loop
$i=0;
while ($i < $num) {
			$id=mysql_result($result,$i,"idCat");
			$category=mysql_result($result,$i,"category");
			

//set default category
//if ($name=="home page") {$selektovan="selected";} else {$selektovan="";}
//$selektovan="";

//set selected
if ($selected_category=="$category") {$odabrano='selected';}
else {$odabrano='';}

//display all categories
echo "<option value=\"".$category."\" ".$odabrano.">".$category."</option>";

++$i;
}
//end loop-a

		  ?>
        </select></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Image:</strong></td>
      <td class="maintext"> <input name="file_up" type="file" class="formfields" id="file_up" size="30" maxlength="255"> 

        <?php echo "$pose";?>        
        &nbsp;<strong>Alt.:</strong> <input name="alt" type="text" class="formfields" id="alt" value="<?php echo "$alt";?>" size="15" maxlength="255">
		<?php 
		if ($image<>"") 
		{
		echo '
		<br>
		<input type="button" name="update" value="View Image ->" style="width: 90px; height: 20px;" class="formfields2" onClick="image_go()" align="absmiddle">
		<input type="button" name="update" value="Delete Image ->" style="width: 90px; height: 20px;" class="formfields2" onClick="delete_image()" align="absmiddle">
		<img src="images/app/i16.gif" width="16" height="16" align="absmiddle">&nbsp;<b>Info:</b> Image is set.
		';
		}
		
		?>

		</td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Source:</strong></td>
      <td><input name="source" type="text" class="formfields" id="source" value="<?php echo "$source";?>" size="30" maxlength="255"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Location:</strong></td>
      <td><input name="location" type="text" class="formfields" id="location" value="<?php echo "$location";?>" size="30" maxlength="255"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Keywords:</strong></td>
      <td><input name="keywords" type="text" class="formfields" id="keywords" value="<?php echo "$keywords";?>" size="30" maxlength="255"></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="80" class="maintext"><strong>Priority:</strong></td>
      <td> <input name="priority" type="checkbox" id="priority" value="1" <?php echo "$priority";?>></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="80" class="maintext"><strong>Flag:</strong></td>
      <td><input name="flag" type="checkbox" id="flag" value="1" <?php echo "$flag";?>></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Expire:</strong></td>
      <td class="maintext">in 
        <input name="expire" type="text" class="formfields" id="expire" value="<?php echo "$expire";?>" size="2" maxlength="255" alt="numeric" min="0" emsg="Expire">
        days.
		<?php 
		//check if article is expired
		$now_date=time();
		$exp_date=(60*60*24);
		if ($expire>0) {
			if ( ($now_date > ($date + ($exp_date * $expire))) ) {echo '<img src="images/app/i16.gif" width="16" height="16" align="absmiddle">&nbsp;<b>Info:</b> This article is expired.';}
		}
		?>
		</td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Date/Time:</strong></td>
      <td valign="middle"> <span class="maintext">Day:</span> <input name="d" type="text" class="formfields" id="d" value="<?php echo"$old_d"; ?>" size="2" maxlength="2" alt="numeric" min="1" max="31" emsg="Day"> 
        <span class="maintext">Month:</span> <input name="m" type="text" class="formfields" id="m" value="<?php echo"$old_m"; ?>" size="2" maxlength="2" alt="numeric" min="1" max="12" emsg="Month"> 
        <span class="maintext">Year:</span> <input name="y" type="text" class="formfields" id="y" value="<?php echo"$old_y"; ?>" size="2" maxlength="4" alt="numeric" min="2004" emsg="Year"> 
        <span class="maintext">&nbsp;/ &nbsp;Hour:</span> <input name="h" type="text" class="formfields" value="<?php echo"$old_h"; ?>" size="2" maxlength="2" alt="numeric" min="0" max="23" emsg="Hour"> 
        <span class="maintext">Minute:</span> <input name="mi" type="text" class="formfields" value="<?php echo"$old_mi"; ?>" size="2" maxlength="2" alt="numeric" min="0" max="59" emsg="Minute">
        <span class="maintext">Sec:</span> 
        <input name="sec" type="text" class="formfields" id="sec" value="<?php echo"$old_sec"; ?>" size="2" maxlength="2" alt="numeric" min="0" max="59" emsg="Second">
        &nbsp;<img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"> 
        <input type="button" name="update" value="<- Update" style="width: 70px; height: 20px;" class="formfields2" onClick="update_textbox();up()" align="absmiddle">&nbsp;&nbsp;<span class="maintext" id="up"></span> 
      </td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Opening Text:</strong></td>
      <td><textarea name="articles_text" style="width:500; height:330"><?php echo "$text";?></textarea>
         
        &nbsp; 
        <script language="JavaScript1.2" defer>
	<?php echo "$settings"; ?>
	editor_generate("articles_text",config);
	<?php if ($articles_editor_edit1=="0") { echo "editor_setmode('articles_text', 'textedit');";}?>	
	</script></td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td class="maintext">&nbsp;</td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Full Text:</strong></td>
      <td><textarea name="articles_text_2" style="width:500; height:330"><?php echo "$text2";?></textarea> 
        &nbsp; <script language="JavaScript1.2" defer>
	<?php echo "$settings"; ?>
	editor_generate("articles_text_2",config);
	<?php if ($articles_editor_edit2=="0") { echo "editor_setmode('articles_text_2', 'textedit');";}?>	
	</script> </td>
    </tr>
    <tr> 
      <td colspan="2" class="maintext"><strong>Comments:</strong><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Allow comments:</strong> 
        <input name="comments_allow" type="checkbox" id="comments_allow" value="1" <?php echo "$comments_allow";?> > 
        <strong> Only by registered users:</strong> <input name="comments_registered" type="checkbox" id="comments_registered" value="1" <?php echo "$comments_registered";?> > 
        <strong>Comments must be approved: 
        <input name="comments_approve" type="checkbox" id="comments_approve" value="1" <?php echo "$comments_approve";?>>
        </strong></td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Added by:</strong></td>
      <td><input name="added" type="text" class="formfields" id="added" size="30" maxlength="255" readonly value="<?php echo "$added_by";?>"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Edited by:</strong></td>
      <td><input name="edited" type="text" class="formfields" id="edited" size="30" maxlength="255" readonly value="<?php echo "$edited_by";?>"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Visits:</strong></td>
      <td><input name="visits" type="text" class="formfields" id="visits" size="30" maxlength="255" value="<?php echo "$visits";?>" alt="numeric" min="0" emsg="Visits"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Rate:</strong></td>
      <td><input name="rate" type="text" class="formfields" id="rate" size="30" maxlength="255" value="<?php echo "$rate";?>" alt="numeric" min="0" emsg="Rate"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td>


<input type="submit" name="submit" value="Save article -&gt;" style="width: 100px; height: 26px;" class="formfields2">
&nbsp;
<input name="delete_button" type="button" class="formfields2" id="delete_button" style="width: 75px; height: 26px;" value="Delete article" onClick="delete_go()">
&nbsp;
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Cancel" onClick="cancel_go()">
        <br>
        <span class="maintext">

        <br>
        <br>
        <br>
		<br>

        </span></td>
    </tr>
  </table>
</form>

<br>
<br>
<br>

</body>
</html>
