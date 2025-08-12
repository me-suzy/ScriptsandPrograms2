<?/*
version 0.1
Build by chris mccabe- under the gpl license
for updates and news or if you have feedback
http://scripts.maxersmix.com

main page- things to edit include the advertiser code on line 35
*/?>
<header>

<title>Example link dump scripts.maxersmix.com</title>


</header>
<font face="Geneva, Arial, Helvetica, sans-serif">
<h1 align="center"></h1>
<center>

<?
  include("dbinfo.php");
 $x = 0;
 $y = 30;

 do{

 $sql = "SELECT url, title, date FROM `links` ORDER BY date DESC LIMIT ".$x.", ".$y.";";
 $rs = mysql_query($sql);
 echo mysql_error();
 $nr = mysql_num_rows($rs);

 while($row = mysql_fetch_row($rs)){
     echo "<a href=\"".$row[0]."\">".$row[1]."</a> ".$row[2]."\n<br>\n";
 }

 if($nr !=0){echo "<p>Advertise code here</p>";
 }
 $x = $x + 30;
 $y = $y + 30;
 } while($nr!=0);
 
 
 mysql_close();
?>


<form name="form1" method="post" action="process.php">
  Title
  <input name="Link_title" type="text" id="Link_title2">
  Link
  <input name="Link" type="text" id="Link2">
  <input type="submit" name="Submit" value="Add">
</form>
</font>
<p>

</center>
