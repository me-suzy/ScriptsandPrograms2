<?
//ob_start();
include("conn.php");
require_once("cms.php");


if($select =="modify") {

$iview = "show";
}

if($select =="view") {

$iview = "view";
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
<body>
<center>
<table width="90%" border="0" >
 <tr>
 <td width="100%" align = "left">

<table width="100%" border="0"  cellSpacing= "1" class=catTbl mm_noconvert="TRUE">
      <tr bgcolor="#FFFFFF">
        <td width="25%" align = "left" bgcolor="#ECFCFF"  class = "leftform">Page
          -
          <?=$name?>
        </td>
        <td width="1%" align = "right">&nbsp;</td>
        <td width="74%" align = "right" bgcolor="#EAFDEB"><a href="modifynews.php?id=<?=$id?>&action=<?=$iview?>&select=<?=$select?>&name=<?=$name?>">Main</a>
          |<a href="modifynews.php?id=<?=$id?>&action=add&select=<?=$select?>&name=<?=$name?>">add new story</a></td>
      </tr>
    </table>
  <br>




<?
news($id, $action, $name, $nid, $heading, $title, $content, $rmove, $rmove2, $img1, $audio, $select);
 ?> 

 
<script language="JavaScript1.2" defer>
editor_generate('heading');
</script>


<script language="JavaScript1.2" defer>
editor_generate('content');
</script> 

</body>
</html>



