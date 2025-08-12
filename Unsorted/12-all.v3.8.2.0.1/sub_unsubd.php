<?PHP 
if ($p == ""){
$p = 1;
}
if ($cort == ""){
$cort = email;
}
?>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_398; ?></strong></font></p>
<form name="form1" method="post" action="">
  <p> 
    <textarea name="textfield" cols="65" rows="12"><?PHP 
$result = mysql_query ("SELECT * FROM ListMembersU
						 WHERE em != ''
						 AND nl LIKE '$nl'
                       	ORDER BY id DESC
");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
print $row["em"];
print "\n";
}
} else {print "Empty.
          ";} ?></textarea>
  </p>
  </form>
<div align="center"> 
  <p>&nbsp;</p>
</div>
