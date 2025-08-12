<? 
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
if ($p == ""){
$p = 1;
}
if ($cort == ""){
$cort = email;
}
?>
<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Export Data</strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">You currently have 
  <?php 


$findcount = mysql_query ("SELECT * FROM cnpCalendar
                         WHERE nl LIKE '$nl'
                       ");

$countdata = mysql_num_rows($findcount);	
print $countdata;
?>
  record(s) in your calendar.</font></p>
<form name="form1" method="post" action="">
  <p> 
    <textarea name="textfield" cols="65" rows="12"><?php 
$result = mysql_query ("SELECT * FROM cnpCalendar
						 WHERE nl LIKE '$nl'
						 ORDER BY id
");
if ($row = mysql_fetch_array($result)) {

do {
print $row["date"];

if ($row["time"] != ""){
print ";";
print $row["time"];
}
if ($row["header"] != ""){
print ";";
print $row["header"];
}
if ($row["info"] != ""){
print ";";
print $row["info"];
}
print "\n";
} while($row = mysql_fetch_array($result));
} else {print "Empty.
          ";} ?></textarea>
  </p>
  <ul>
    <li><font size="2" face="Arial, Helvetica, sans-serif">Every individual entry 
      is on a different line / new line.</font></li>
    <li><font size="2" face="Arial, Helvetica, sans-serif">Fields are separate 
      by semicolons.</font></li>
  </ul>
  </form>
<div align="center"> 
  <p>&nbsp;</p>
</div>
