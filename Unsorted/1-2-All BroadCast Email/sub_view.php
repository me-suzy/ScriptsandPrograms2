<?PHP 
if ($p == ""){
$p = 1;
}
if ($cort == ""){
$cort = email;
}
?>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_399; ?> </strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_400; ?> 
  <?PHP 


$findcount = mysql_query ("SELECT * FROM ListMembers
                         WHERE active LIKE '0'
						 AND nl LIKE '$nl'
						 AND email != ''
                       ");

$countdata = mysql_num_rows($findcount);	
print $countdata;
?>
  <?PHP print $lang_401; ?></font></p>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#BFD2E8">
  <tr> 
    <td> <table width="100%" border="0" cellspacing="1" cellpadding="4" bgcolor="#F0F0F0">
        <tr bgcolor="#D5E2F0"> 
          <td width="50"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_22; ?></font></b></div></td>
          <td> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?> &nbsp;&nbsp;<a href="main.php?page=sub_view&nl=<?PHP print $nl; ?>&cort=email"><img src="media/up.gif" width="13" height="7" border="0"></a> 
              &nbsp;<a href="main.php?page=sub_view&nl=<?PHP print $nl; ?>&cort=email%20DESC"><img src="media/down.gif" width="13" height="7" border="0"></a></font></b></div></td>
          <td width="175" bgcolor="#D5E2F0"><div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?> 
              &nbsp;&nbsp;<a href="main.php?page=sub_view&nl=<?PHP print $nl; ?>&cort=name"><img src="media/up.gif" width="13" height="7" border="0"></a> 
              &nbsp;<a href="main.php?page=sub_view&nl=<?PHP print $nl; ?>&cort=name%20DESC"><img src="media/down.gif" width="13" height="7" border="0"></a></font></b></div></td>
          <td width="75" bgcolor="#D5E2F0"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_29; ?>&nbsp;&nbsp;<a href="main.php?page=sub_view&nl=<?PHP print $nl; ?>&cort=sdate"><img src="media/up.gif" width="13" height="7" border="0"></a> 
              &nbsp;<a href="main.php?page=sub_view&nl=<?PHP print $nl; ?>&cort=sdate%20DESC"><img src="media/down.gif" width="13" height="7" border="0"></a></font></b></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<?PHP

$limit=30; // rows to return
$numresults=mysql_query ("SELECT * FROM ListMembers
                         WHERE active LIKE '0'
						 AND nl LIKE '$nl'
						 AND email != ''
						 ORDER BY $cort
                       ");
$numrows=mysql_num_rows($numresults);

// next determine if offset has been passed to script, if not use 0
if (empty($offset)) {
    $offset=0;
}

// get results
$result=mysql_query("select * from ListMembers where email != '' AND active LIKE '0' AND nl LIKE '$nl' ".
    "order by $cort limit $offset,$limit");

// now you can display the results returned
while ($row=mysql_fetch_array($result)) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D5E2F0">
  <tr> 
    <td bgcolor=""> <table width="100%" border="0" cellspacing="1" cellpadding="4" bordercolor="#FFFFFF" align="center">
        <tr bgcolor="#FFFFFF"> 
          <td width="50" bordercolor="#ECECFF" bgcolor="#FFFFFF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP if ($row_admin["m_dusers"] == 1){ ?><a href="main.php?page=sub_del&id=<?PHP print $row["id"]; ?>&emaildel=<?PHP print $row["email"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/del.gif" width="11" height="7" border="0"></a>&nbsp;&nbsp;<?PHP } ?><?PHP if ($row_admin["m_users"] == 1){ ?><a href="main.php?page=sub_modify&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/edit.gif" width="11" height="7" border="0"></a> 
              &nbsp;<?PHP } ?><a href="main.php?page=sub_details&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/info.gif" width="11" height="7" border="0"></a> 
              </font></div></td>
          <td bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              <?PHP print $row["email"]; ?> </font></div></td>
          <td bordercolor="#CCCCCC" width="175"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              <?PHP 
			  $namclown = $row["name"];
			  if ($namclown == ""){
			  print "-";
			  }
			  else {
			   print $namclown; 
			   }
			  ?>
              </font></div></td>
          <td bordercolor="#CCCCCC" width="75"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $row["sdate"]; ?></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<?PHP
}
?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1">
      <?PHP
// next we need to do the links to other result
if ($offset!=0) { // bypass PREV link if offset is 0
    $prevoffset=$offset-30;
    //print "<a href=\"$PHP_SELF?page=$page&nl=$nl&offset=$prevoffset&p=$i&cort=$cort\">$lang_397</a> &nbsp; \n";
	
	print "<form name=\"form1\" method=\"post\" action=\"main.php\">";
	print "<input type=\"submit\" name=\"$lang_397\" value=\"$lang_397\">";
	print "<input name=\"offset\" type=\"hidden\" id=\"offset\" value=\"$prevoffset\">";
	print "<input name=\"nl\" type=\"hidden\" id=\"nl\" value=\"$nl\">";
	print "<input name=\"cort\" type=\"hidden\" id=\"cort\" value=\"$cort\">";
	print "<input name=\"page\" type=\"hidden\" id=\"page\" value=\"sub_view\">";
	print "</form>";

}
?>
    </td>
    <td width="200"> 
      <?PHP
	  $pages=intval($numrows/$limit);

		// $pages now contains int of pages needed unless there is a remainder from division
		if ($numrows%$limit) {
			// has remainder so add one page
			$pages++;
		}
// check to see if last page
$clowns=intval($offset/$limit);
$clowns++;
if ($clowns != $pages AND $numrows != "0") {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    //print "<a href=\"$PHP_SELF?page=$page&nl=$nl&offset=$newoffset&p=$i&cort=$cort\">$lang_396</a>\n";
	print "<form name=\"form1\" method=\"post\" action=\"main.php\">";
	print "<input type=\"submit\" name=\"$lang_396\" value=\"$lang_396\">";
	print "<input name=\"offset\" type=\"hidden\" id=\"offset\" value=\"$newoffset\">";
	print "<input name=\"nl\" type=\"hidden\" id=\"nl\" value=\"$nl\">";
	print "<input name=\"cort\" type=\"hidden\" id=\"cort\" value=\"$cort\">";
	print "<input name=\"page\" type=\"hidden\" id=\"page\" value=\"sub_view\">";
	print "</form>";
	
}

?>
    </td>
    <td><div align="right"> 
        <?PHP
// calculate number of pages needing links
$pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$limit) {
    // has remainder so add one page
    $pages++;
}
$thenum = $offset / 30;
$thenum++;
print "<form name=\"form1\" method=\"post\" action=\"main.php\">";
print "<select name=\"offset\">";
for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
	if ($i == $thenum){
	$vnum = "selected";
	}
	else{
	$vnum = "";
	}
	print "<option value=\"$newoffset\" $vnum>Page $i</option>";
    //print "<a href=\"$PHP_SELF?page=$page&nl=$nl&offset=$newoffset&p=$i&cort=$cort\">$i</a> &nbsp; \n";
}
print "</select>";
print "<input type=\"submit\" name=\"$lang_21\" value=\"$lang_21\">";
print "<input name=\"nl\" type=\"hidden\" id=\"nl\" value=\"$nl\">";
print "<input name=\"cort\" type=\"hidden\" id=\"cort\" value=\"$cort\">";
print "<input name=\"page\" type=\"hidden\" id=\"page\" value=\"sub_view\">";
print "</form>";
?>
      </div></td>
  </tr>
</table>
<br>
<br>
<div align="center"> 
  <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
    <tr> 
      <td> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_143; ?></font><br>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
          <tr> 
            <td width="20%"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/del.gif" width="11" height="7" border="0"> 
                = <?PHP print $lang_402; ?></font></div></td>
            <td width="20%"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/edit.gif" width="11" height="7" border="0"> 
                = <?PHP print $lang_403; ?></font></div></td>
            <td width="20%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/info.gif" width="11" height="7" border="0"> 
                = <?PHP print $lang_145; ?></font></div></td>
            <td width="20%"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/up.gif" width="13" height="7"> 
                <?PHP print $lang_404; ?></font></div></td>
            <td width="20%"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img src="media/down.gif" width="13" height="7"> 
                <?PHP print $lang_405; ?></font></div></td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
