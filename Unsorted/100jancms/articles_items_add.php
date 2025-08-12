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

//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_add1'";
$result=mysql_query($query);
$articles_editor_add1=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_add2'";
$result=mysql_query($query);
$articles_editor_add2=mysql_result($result,0,"config_value");

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
document.addform.d.value = dan;
document.addform.m.value = mesec;
document.addform.y.value = godina;
document.addform.h.value = sat;
document.addform.mi.value = minut;
document.addform.sec.value = seconds;

} 

function up()
{

var txt = "&nbsp;<span class='maintext'>Updated!</span> &nbsp;<img src='images/app/all_good.jpg' width='16' height='16' align='absmiddle'><br>";
document.all.up.innerHTML=txt;
var txt2 ="";
setTimeout("document.all.up.innerHTML=''",3000);

}

</script>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.addform.article_title.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: <span class="titletext0blue">Add new Article</span></td>
  </tr>
</table>
<br>
<br>

<form action="articles_items_insert.php" method=post enctype="multipart/form-data" name="addform" onSubmit="return checkform(addform);">
  <input name="action" type="hidden" id="action" value="new">
  <input type=hidden name="pritisnuto" value="true">
  <table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="80" class="maintext"><strong>Title:</strong></td>
      <td ><input name="article_title" type="text" class="formfields" id="article_title" size="63" maxlength="255" alt="anything" emsg="Title">
         
        &nbsp;<img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"> </td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Marker:</strong></td>
      <td><select name="marker" class="formfields" id="marker" style="width:247" alt="anything" emsg="Marker (no privileges)">
          <?php 
		    //load user_privileges for current user
			$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //u kom sam redu
			$user_privileges=$row["user_privileges"];


			//load all markers
			$query="SELECT * FROM ".$db_table_prefix."articles_marker ORDER BY marker";
			$result=mysql_query($query);
			$num=mysql_numrows($result); //koliko ima redova
			
//loop
$i=0;
$marker_count=0;
while ($i < $num) {
			$id=mysql_result($result,$i,"idMark");
			$marker=mysql_result($result,$i,"marker");
			

//set default marker
//if ($marker=="home page") {$selektovan="selected";} else {$selektovan="";}

//display allowed markers

if (substr_count($user_privileges, "ARTICLES[$marker]")<>"0") {
echo "<option value=\"".$marker."\" ".$selektovan.">".$marker."</option>";
$marker_count++;
}

++$i;

} //while

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
		  //load categories
			$query="SELECT * FROM ".$db_table_prefix."articles_category ORDER BY category";
			$result=mysql_query($query);
			$num=mysql_numrows($result); //how many rows
			
//null category
echo "<option value=\"\" selected></option>";

//loop
$i=0;
while ($i < $num) {
			$id=mysql_result($result,$i,"idCat");
			$category=mysql_result($result,$i,"category");
			


//echo all categories
echo "<option value=\"".$category."\" ".$selektovan.">".$category."</option>";

++$i;
}
//end loop

		  ?>
        </select></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Image:</strong></td>
      <td> 
        <input name="file_up" type="file" class="formfields" id="file_up" size="30" maxlength="255"> 
        <select name="position" class="formfields" id="position">
          <option value="left" >Left position</option>
          <option value="right" >Right position</option>
        </select>
        &nbsp;<span class="maintext"><strong>Alt.:</strong> 
        <input name="alt" type="text" class="formfields" id="alt" size="15" maxlength="255">
        </span></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Source:</strong></td>
      <td><input name="source" type="text" class="formfields" id="source" size="30" maxlength="255"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Location:</strong></td>
      <td><input name="location" type="text" class="formfields" id="location" size="30" maxlength="255" ></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Keywords:</strong></td>
      <td><input name="keywords" type="text" class="formfields" id="keywords" size="30" maxlength="255"></td>
    </tr>
    <tr align="left"> 
      <td width="80" valign="middle" class="maintext"><strong>Priority:</strong></td>
      <td valign="middle"> 
        <input name="priority" type="checkbox" id="priority" value="1"> 
      </td>
    </tr>
    <tr align="left"> 
      <td width="80" valign="middle" class="maintext"><strong>Flag:</strong></td>
      <td valign="middle">
<input name="flag" type="checkbox" id="flag" value="1"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Expire:</strong></td>
      <td class="maintext">in 
        <input name="expire" type="text" class="formfields" id="expire" value="0" size="2" maxlength="255" alt="numeric" min="0" emsg="Expire">
        days.&nbsp;&nbsp;<img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"></td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Date/Time:</strong></td>
      <td> 
        <?php 
		$today = getdate();
		?>
        <span class="maintext">Day:</span> <input name="d" type="text" class="formfields" id="d" value="<?php echo $today['mday'];;?>" size="2" maxlength="2" alt="numeric" min="1" max="31" emsg="Day"> 
        <span class="maintext">Month:</span> <input name="m" type="text" class="formfields" id="m" value="<?php echo $today['mon'];;?>" size="2" maxlength="2" alt="numeric" min="1" max="12" emsg="Month"> 
        <span class="maintext">Year:</span> <input name="y" type="text" class="formfields" id="y" value="<?php echo $today['year'];;?>" size="2" maxlength="4" alt="numeric" min="2004" emsg="Year"> 
        <span class="maintext">&nbsp;/ &nbsp;Hour:</span> <input name="h" type="text" class="formfields" value="<?php echo $today['hours'];;?>" size="2" maxlength="2" alt="numeric" min="0" max="23" emsg="Hour"> 
        <span class="maintext">Minute:</span> <input name="mi" type="text" class="formfields" value="<?php echo $today['minutes'];;?>" size="2" maxlength="2" alt="numeric" min="0" max="59" emsg="Minute">
        <span class="maintext">Sec:</span> 
        <input name="sec" type="text" class="formfields" id="sec" value="<?php echo $today['seconds'];;?>" size="2" maxlength="2" alt="numeric" min="0" max="59" emsg="Second">
        &nbsp;<img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"> 
        <input type="button" name="update" value="<- Update" style="width: 70px; height: 20px;" class="formfields2" onClick="update_textbox();up()" align="absmiddle">&nbsp;<span class="maintext" id="up"></span> 
      </td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Opening text:</strong></td>
      <td><textarea name="articles_text" style="width:500; height:330"></textarea>
         
        &nbsp; 
        <script language="JavaScript1.2" defer>
	<?php echo "$settings"; ?>
	editor_generate("articles_text",config);
	<?php if ($articles_editor_add1=="0") { echo "editor_setmode('articles_text', 'textedit');";}?>
	</script> </td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td class="maintext">&nbsp;</td>
    </tr>
    <tr> 
      <td width="80" class="maintext"><strong>Full  text:</strong></td>
      <td><textarea name="articles_text_2" style="width:500; height:330"></textarea> 
        &nbsp; <script language="JavaScript1.2" defer>
	<?php echo "$settings"; ?>
	editor_generate("articles_text_2",config);
	<?php if ($articles_editor_add2=="0") { echo "editor_setmode('articles_text_2', 'textedit');";}?>
	</script> </td>
    </tr>
    <tr> 
      <td width="80" class="maintext">&nbsp;</td>
      <td class="maintext">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2" valign="middle" class="maintext"><strong>Comments:</strong><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Allow comments: </strong>
        <input name="comments_allow" type="checkbox" id="comments_allow" value="1" checked  > 
        <strong> Only by registered users:</strong> <input name="comments_registered" type="checkbox" id="comments_registered" value="1" checked  > 
        <strong>Comments must be approved: 
        <input name="comments_approve" type="checkbox" id="comments_approve" value="1" checked>
        </strong></td>
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
	  <input type="submit" name="submit" value="Insert article ->" style="width: 100px; height: 26px;" class="formfields2">
        <span class="maintext"> <br>
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
