<?
ob_start();
include("conn.php");
require_once("cms.php");


 if ($type == "home") {
header("Location: edithome.php?id=$id&type=$type");

}






$sql2 = "SELECT * FROM menu  WHERE id ='$id'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$name = stripslashes($result["name"]);
$cat = stripslashes($result["cat"]);
$placement = stripslashes($result["placement"]);
$news = stripslashes($result["news"]);
$poll = stripslashes($result["poll"]);
$enabled = stripslashes($result["enabled"]);
$position = stripslashes($result["position"]);
$snewpge = stripslashes($result["show_n_page"]);
}



$sql3 = "SELECT * FROM category";
$query3 = mysql_query($sql3) or die("Cannot query the database.<br>" . mysql_error());


$sql5 = "SELECT * FROM menu WHERE deleted='0' and type = 'news' ORDER BY catposition ASC, position ASC ";
$query5 = mysql_query($sql5) or die("Cannot query the database.<br>" . mysql_error());



// check for enabled show link on menu

if ($enabled == "yes") {

$eflag = "checked";
}

if ($enabled == "no") {

$eflag2 = "checked";
}


//check yes or no news summary

 if ($news == "yes") {

$nflag = "checked";
}

if ($news == "no") {

$nflag2 = "checked";
}


//news summary

if( ($type == "gallery")||($type == "news") ||($type == "contact")  ) {

$men = "<td width='75%' align = 'left' bgcolor='#E7FCFE'>No</td> <input type='hidden' name='news' value = 'no'>";

} else {

$f1 = "<td width='75%' align = 'left' bgcolor='#E7FCFE'> Yes:<input type='radio' name='news' value ='yes' $nflag >&nbsp; No:<input type='radio' name='news' value ='no' $nflag2> 

(if yes which news page)
 
 <select name='showsum'>";
 

     while($result = mysql_fetch_array($query5)) {
           $mname = stripslashes($result["name"]);
            $pid = stripslashes($result["id"]);
			
			if($mname == $snewpge) {
			$f3 = $f3."<option selected >$mname</option>";
			
			} else { 
			
        $f3 = $f3."<option>$mname</option>";
		  
		
      }
	  
	  }
           $f4 = "<option>all_news_pages</option> 
	             </select>

</td>";

$ft = $f1.$f3.$f4;




$men = $ft;






}




?>

<HTML>
<HEAD>
<TITLE>Content Management </TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="style.css" type="text/css">

<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script>



</head>
<body onunload="opener.location=('mainf.php')">
<center>
<table width="90%" border="0" >
 <tr>
 <td width="100%" align = "left">
<form name="input" action="editpost.php" method="post" enctype="multipart/form-data">

<table width="100%" border="0"  cellSpacing= "1" class=catTbl mm_noconvert="TRUE">
 <tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Menu Name</td>
 <td width="75%" align = "left"><input type="text" name="name" style="background-color:#FFFFD7" size= "45" value = "<?=$name?>"></td>
  </tr>
 
 <tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Page Type</td>
 <td width="75%" align = "left" bgcolor="#F9F0CC"><?=$type?></td>
  </tr>

 <!--
 <tr bgcolor="#FFFFFF">
 <td width="25%" align = "left" class = "leftform">Menu Placement</td>
 <td width="75%" align = "left" bgcolor="#EAFDEB"><b>[ <?=$placement?> ]</b>&nbsp; &nbsp; Change Placement:
<select name="placement">
<option value="Primary">Primary</option>
<option value="Secondary">Secondary</option>
<option value="Tertiary">Tertiary</option>
</select> </td>
  </tr>
  -->

<tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Show on Menu</td>
 <td width="75%" align = "left" >Yes:<input type="radio" name="enabled" value ="yes" <?=$eflag?> >&nbsp; No:<input type="radio" name="enabled" value ="no" <?=$eflag2?> > </td>
  </tr>

<tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Show News Summary</td>
 <!--<td width="75%" align = "left" bgcolor="#E7FCFE"> Yes:<input type="radio" name="news" value ="yes" <?=$nflag?> >&nbsp; No:<input type="radio" name="news" value ="no" <?=$nflag2?> > </td>-->
 <?=$men?>
  </tr>
<!--
<tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Show Poll No.</td>
 <td width="75%" align = "left"><input type="text" name="poll" value = "<?=$poll?>"> (show poll number, 0 for <b>No</b> polls) </td>
  </tr>
-->
  <tr bgcolor="#FFFFFF">
 <td width="25%" align = "left"  class = "leftform">Menu Category</td>
 <td width="75%" align = "left"> <select name='cat'>
 <?
global $cat;
 while($result = mysql_fetch_array($query3)) {
           $catname = stripslashes($result["name"]);

           if($catname == $cat) {
    ?>
    <option selected><?=$catname?></option>

 <?
           }else {
  ?>
   <option><?=$catname?></option>
   <?
 }
           }
   ?>
   </select>
 </td>
  </tr>

 <?
 menupos($id);
 ?> 

 <tr bgcolor="#FFFFFF">
        <td width="25%" align = "left"  class = "leftform">Page</td>
        <td width="75%" align = "left">index.php?id=<?=$id?>&type=<?=$type?></td>
  </tr> 
</table>
<br>
<?


etype($type,$id, $position)


?>

<script language="JavaScript1.2" defer>
editor_generate('content');
</script>

<script language="JavaScript1.2" defer>
editor_generate('heading');
</script>



<?

editnote($type,$id)

?>

</center>
</body>
</html>



