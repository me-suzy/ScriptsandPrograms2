<link rel='stylesheet' href='style.css' type='text/css'>
<center>
<?php
include "title.php";
include "connect.php";
?>
</center><br><br>
<?php
print "<center>";
$getnewentries="SELECT * from tut_cats, tut_entries where tut_cats.catID=tut_entries.catparent and tut_entries.validated='1' order by timeadded DESC LIMIT 15";
$getnewentries2=mysql_query($getnewentries) or die("muhahahaha");
while($getnewentries3=mysql_fetch_array($getnewentries2))
{
   print "<table class='maintable'><tr class='headline'><td><A href='index.php?catID=$catID&tutid=$getnewentries3[tutid]'><font color='white'>$getnewentries3[title]</font></a>&nbsp;</td></tr>";
     print "<tr class='mainrow'><td>$getnewentries3[shortdes]<br><br>";
     print "<b>Rating:&nbsp;</b>";
     if($getnewentries2[totalvotes]<1)
     {
       print "Not yet Rated";
     }
     else
     {
       for(;$getnewentries3[avgvote]>=1;$getnewentries3[avgvote]--)
       {
         print "<img src='acorn.gif' border='0' align='middle'>";
       }
       if($getnewentries3[avgvote]>=0.5)
       {
         print "<img src='halfacorn.gif' border='0' align='middle'>";  
       }
       print "&nbsp;($getnewentries3[totalvotes] votes)";
     }
     print "&nbsp;&nbsp;&nbsp;<b>Date added:</b>&nbsp;$getnewentries3[dateadded]";
     print "</td></tr></table><br><br>";
 
}
?>
</center>



