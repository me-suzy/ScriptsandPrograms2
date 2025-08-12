<?

ob_start();
include("conn.php");
require_once("cms.php");


$sql2 = "SELECT * FROM menu  WHERE id ='$id'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$name = stripslashes($result["name"]);
$cat = stripslashes($result["cat"]);
$placement = stripslashes($result["placement"]);
$news = stripslashes($result["news"]);
$poll = stripslashes($result["poll"]);
$enabled = stripslashes($result["enabled"]);
}

?>


<HTML>
<HEAD>
<TITLE>Content Management </TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
<center>

  <table width="90%" border="0" cellSpacing=1 class=catTbl mm_noconvert="TRUE">
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" align = "left"  class = "leftform">Menu Name</td>
      <td width="81%" align = "left"><b> 
        <?=$name?>
        </b></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" align = "left"  class = "leftform">Page Type</td>
      <td width="81%" align = "left" bgcolor="#F9F0CC"> 
        <?=$type?>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" align = "left" class = "leftform">Menu Placement</td>
      <td width="81%" align = "left" bgcolor="#EAFDEB"><b>[ 
        <?=$placement?>
        ] </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" align = "left"  class = "leftform">Show on Menu</td>
      <td width="81%" align = "left"> 
        <?=$enabled?>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" align = "left"  class = "leftform">News Summary</td>
      <td width="81%" align = "left"> 
        <?=$news?>
        (news summary)</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" align = "left"  class = "leftform">Show Poll No.</td>
      <td width="81%" align = "left"> 
        <?=$poll?>
        (Show poll number )</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td align = "left"  class = "leftform">Page</td>
      <td width="81%" align = "left">index.php?id=<?=$id?>&type=<?=$type?></td>
    </tr></td>
  </table>
<br>



<?

stype($type,$id);

?>



</center>

<br>
</body>
</html>
