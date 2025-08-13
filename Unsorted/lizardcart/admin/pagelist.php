<?php
include ("atho.inc.php");
include ("config.inc.php");


$query = "SELECT * FROM pages"; 

$products_per_page="$perpage";
$numresults=mysql_query($query);
$numrows=mysql_num_rows($numresults);

if (empty($offset) || ($offset < 0)) {
	$offset=0;
}


$dbResult = mysql_query("select * from pages limit $offset,$products_per_page");

$title="Lizard Cart Product Administration";
?>

<? include ("header.php");?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#3366CC">
  <tr bgcolor=ffffff>
    <td colspan=2></td></tr>
    <td width="50?">
	<font face="Verdana, Arial, Helvetica, sans-serif" size="2">
  <tr> 
    <td width="50?"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Pages (<?echo "$numrows"?>)</b></font></td>
    <td>
      <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="White">Click on an item for Details</font></div>
    </td>
  </tr>
  <tr>
    <td colspan=3 align=center>
	<a href="<? echo "pageedit.php?action=0"?>"><font size=1 face="Verdana, Arial, Helvetica, sans-serif" color='white'>[ Add A Page ]</a></td>
  </tr>
</table>
<? 
while ($row=mysql_fetch_object($dbResult)) {
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#3366CC" >
  <tr> 
    <td>&nbsp;<a href="pageedit.php?id=<? echo "$row->id" ?>"><font face="Verdana, Arial, Helvetica, sans-serif"><b><font color="#000000">Page Title<? echo "$row->page_title" ?></font></b></font></a><br><u>Page Content:</u><br><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<? echo "$row->page_content" ?></font>
	  <br>
      <br>
      </a><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="pageedit.php?id=<? echo "$row->id"?>"><font color="#336699">more...</font></a></font> 
    </td>
    <td width="50"> 
      <font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="1">
      <a href='<? echo "pageedit.php?action=0&id=$row->id" ?>'>Edit</a>
      <a href='<? echo "pageedit.php?action=2&id=$row->id" ?>'>Delete</a>
      </font></b></font>
</td>
  </tr>
</table>
<br>
<? 
} // while
print "<table width=100% ><tr><td colspan=2 align=center>\n";
print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">";
$prevoffset=$offset-$products_per_page;
if ($prevoffset >= 0) {
	print "<a href=\"$PHP_SELF?offset=$prevoffset\">PREV</a> &nbsp; \n";
} else {
	print "PREV &nbsp; \n";
}

// calculate number of pages needing links
$pages=intval($numrows/$products_per_page);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$products_per_page) {
    // has remainder so add one page
    $pages++;
}

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$products_per_page*($i-1);
    if($newoffset==$offset) {
	    print "<b>$i</b> &nbsp; \n";
    } else {
	    print "<a href=\"$PHP_SELF?offset=$newoffset\">$i</a> &nbsp; \n";
    }
}

// check to see if last page
if (!(($offset/$products_per_page)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$products_per_page;
    if ($newoffset < ($numrows+1)) {
	    print "<a href=\"$PHP_SELF?offset=$newoffset\">NEXT</a><p>\n";
    } else {
	    print "NEXT &nbsp;<p>\n";
    } 
	
} else {
	    print "NEXT &nbsp;<p>\n";
}
?>
</td></tr></table>
<? include ("footer.php");?>
