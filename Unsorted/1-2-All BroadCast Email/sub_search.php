<?PHP 
if ($p == ""){
$p = 1;
}
if ($cort == ""){
$cort = email;
}
?>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_391; ?> </strong></font></p>
<p> 
  <?PHP
if ($action == ""){
?>
</p>
<form name="form1" method="post" action="main.php">
  <table width="75%" border="0" cellspacing="0" cellpadding="8">
    <tr> 
      <td width="150" height="30" bgcolor="#F0F0F0"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_392; ?> </font></td>
      <td height="30" bgcolor="#F0F0F0"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="terms">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30" bgcolor="#F0F5FF"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_393; ?> 
        </font></td>
      <td height="30" bgcolor="#F0F5FF"> <font size="2" face="Arial, Helvetica, sans-serif"> 
	  <?PHP
	  	$result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
		$row = mysql_fetch_array($result);

	  ?>
        <select name="type">
          <option value="email" selected><?PHP print $lang_394; ?></option>
          <option value="name"><?PHP print $lang_395; ?></option>
          <option value="field1"><?PHP	print $row["field1"];	?></option>
          <option value="field2"><?PHP	print $row["field2"];	?></option>
          <option value="field3"><?PHP	print $row["field3"];	?></option>
          <option value="field4"><?PHP	print $row["field4"];	?></option>
          <option value="field5"><?PHP	print $row["field5"];	?></option>
          <option value="field6"><?PHP	print $row["field6"];	?></option>
          <option value="field7"><?PHP	print $row["field7"];	?></option>
          <option value="field8"><?PHP	print $row["field8"];	?></option>
          <option value="field9"><?PHP	print $row["field9"];	?></option>
          <option value="field10"><?PHP	print $row["field10"];	?></option>
        </select>
        </font></td>
    </tr>
  </table>
  <br>
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>" onClick="MM_validateForm('name','','R');return document.MM_returnValue">
        <input type="hidden" name="page" value="sub_search">
        <input type="hidden" name="action" value="search">
        <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
        </font></td>
    </tr>
  </table>
</form>
<?PHP }
else {
?>
<?PHP

$limit=15; // rows to return
$numresults=mysql_query ("SELECT * FROM ListMembers
                         WHERE active LIKE '0'
						 AND nl LIKE '$nl'
						 AND email != ''
						 AND $type LIKE '%$terms%'
						 ORDER BY $cort
                       ");
$numrows=mysql_num_rows($numresults);

// next determine if offset has been passed to script, if not use 0
if (empty($offset)) {
    $offset=0;
}

// get results
$result=mysql_query("select id,email,nl,active,name ".
    "from ListMembers where email != '' AND active LIKE '0' AND nl LIKE '$nl' AND $type LIKE '%$terms%' ".
    "order by $cort limit $offset,$limit");

// now you can display the results returned
while ($row=mysql_fetch_array($result)) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr> 
    <td bgcolor="#CCCCCC"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
        <tr bgcolor="#FFFFFF"> 
          <td width="75" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_del&id=<?PHP print $row["id"]; ?>&emaildel=<?PHP print $row["email"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/del.gif" width="11" height="7" border="0"></a>&nbsp;&nbsp;<a href="main.php?page=sub_modify&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/edit.gif" width="11" height="7" border="0"></a> 
              &nbsp;<a href="main.php?page=sub_details&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>"><img src="media/info.gif" width="11" height="7" border="0"></a></font></div></td>
          <td bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              <?PHP print $row["email"]; ?> </font></div></td>
          <td bordercolor="#CCCCCC" width="200"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
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
        </tr>
      </table></td>
  </tr>
</table>
<?PHP
}

// next we need to do the links to other results

if ($offset!=0) { // bypass PREV link if offset is 0
    $prevoffset=$offset-15;
    print "<a href=\"$PHP_SELF?page=$page&nl=$nl&offset=$prevoffset&p=$i&cort=$cort&action=$action&terms=$terms&type=$type\">$lang_397</a> &nbsp; \n";
}

// calculate number of pages needing links
$pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$limit) {
    // has remainder so add one page
    $pages++;
}

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"$PHP_SELF?page=$page&nl=$nl&offset=$newoffset&p=$i&cort=$cort&action=$action&terms=$terms&type=$type\">$i</a> &nbsp; \n";
}

// check to see if last page
$clowns=intval($offset/$limit);
$clowns++;
if ($clowns != $pages) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"$PHP_SELF?page=$page&nl=$nl&offset=$newoffset&p=$i&cort=$cort&action=$action&terms=$terms&type=$type\">$lang_396</a>\n";
}

?>
<?PHP
}
?>
