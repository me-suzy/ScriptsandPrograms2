<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}
?>
<html>
<head>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css" />
<?php
if ($editor == "on") {
?>
<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "\htmlarea/";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script> 
<?php } ?>
</head>
<body>
<?php
include ("connect.php");
/*
$query1 = "SELECT DISTINCT page from links WHERE parent =\"index\"";
$result1 = mysql_query($query1) or die(mysql_error());
*/
if(! $B1){                            
?>
<span class = "heading">Insert a New Page</span>
<span class = "heading"><a href="options.php">Back to Options</a></span>
<span class="help"><a href="inserthelp.htm" target="_blank">HELP</a></span>
<form method="POST" action="insert.php">
  <div align="center">
    <table border="1" bordercolor="#333333">
      <tr>
        <td class="text-design1">Page Name</td>
        <td class="text-design1"><input name="name" type="text" class="text-box1" size="40" /></td>
      </tr>
      <tr>
        <td class="text-design1">Heading</td>
        <td class="text-design1"><input name="heading" type="text" class="text-box1" value="<?php echo $heading?>" size="40" /></td>
      </tr>
      <tr>
          <td class="text-design1" colspan="2">
		  <textarea name="text" cols="55" rows="14" class="text-box1"></textarea> 
          <?php if ($editor == "on") { ?>
		  <script language="JavaScript1.2" defer>
			editor_generate('text');
			</script>
		  <?php } ?>
		  </td>
      </tr>
      <tr>
        <td colspan="2">
		<p class="text" style="color:red;">All fields are required</p>
		<input name="B1" type="submit" class="text-box1" value="Submit" />
		</td>
     </tr>
    </table>
  </div>
</form>


  <?php }
  else {if(empty($name) || empty($heading) ||empty($text))
  {
  display($name,$heading,$text);
  }
  else {
  commands($name,$heading,$text);
   }
  
  ?>
  <?php 
  }
  ?>
<p class="headingcenter"><a href="options.php">Back to Options</a></p>
  <?php
  function commands($name,$heading,$text){
  include ("connect.php");
  $result = mysql_query ("INSERT into pages (name,heading,text) VALUES ('$name','$heading','$text')") or die(mysql_error());
//  $result = mysql_query ("SELECT serial from pages ORDER by serial DESC");
//  $row=mysql_fetch_row($result);
//  $result = mysql_query ("Insert into links (page,parent,pageid) VALUES ('$name','$parent','$row[0]') ") or die(mysql_error());
   print "<p class='headingcenter'>succesfully entered</p>";
   
  
  }
  ?>
<?php
function display($name,$heading,$text)
{
?>
<span class = "heading">critical field missing</span> <span class="help"><a href="inserthelp.htm" target="_blank">HELP</a></span>

<?php 
}
?>
</body>
</html>



