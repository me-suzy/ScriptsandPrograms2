<?php
session_start();
include "connect.php";
?>

<link rel='stylesheet' href='style.css' type='text/css'>


<?php
print "<center>";
include "title.php";
print "<br>";
print "</center>";
if(isset($_POST['searchterm'])||isset($_POST['submit'])) // looking at individual category
{
   $searchterm=$_POST['searchterm'];
   print "<center><table class='maintable'>";
   print "<form action='search.php' method='post'>";
   print "<tr class='headline'><td>";
   print "<center>Search:<input type='text' name='searchterm' size='30'>&nbsp;<input type='submit' name='submit' value='submit'></center></td></tr></form>";
   print "<tr class='mainrow'><td><center><A href='new.php'><b>Search Results</b></a>-<A href='addtutorial.php'><b>Add a tutorial</b></a>-<A href='modify.php'><b>Modify a listing</b></a>-<A href='getrated.php'><b>Getting Rated</b></a>-<A href='mailto:webmaster@chipmunk-scripts.com'><b>Contact webmaster</b></a></center></td></tr>";
   print "</table></center><br>";
   print "<center><table class='maintable'><tr>";
   print "<td valign='top' width=20%>";
   print "<table class='maintable'><tr class='headline'><td> Main Categories</td></tr>";
   print "<tr class='mainrow'><td>";
   $getcatss="SELECT * from tut_cats order by catname DESC";
   $getcatss2=mysql_query($getcatss) or die("Could not get categories");
   while($getcatss3=mysql_fetch_array($getcatss2))
   {
      print "<img src='regular.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcatss3[catID]'><b>$getcatss3[catname]</b></a><br>";
   }
   print "</td></tr></table></td>";
   print "<td valign='top' width=60%>";
   if(!isset($_GET['start']))
   {
     $start=0;
   }
   else
   {
     $start=$_GET['start'];
   }
   $getentries="SELECT * from tut_entries where (title like '%$searchterm%' or description like '%$searchterm%')  and validated='1' order by rankscore DESC limit $start, 15";
   $getentries2=mysql_query($getentries) or die("Could not get entries2");
   while($getentries3=mysql_fetch_array($getentries2))
   {
     print "<table class='maintable'><tr class='headline'><td><A href='index.php?catID=$getentries3[catparent]&tutid=$getentries3[tutid]'><font color='white'>$getentries3[title]</font></a>&nbsp;</td></tr>";
     print "<tr class='mainrow'><td>$getentries3[shortdes]<br><br>";
     print "<b>Rating:&nbsp;</b>";
     if($getentries2[totalvotes]<1)
     {
       print "Not yet Rated";
     }
     else
     {
       for(;$getentries3[avgvote]>=1;$getentries3[avgvote]--)
       {
         print "<img src='acorn.gif' border='0' align='middle'>";
       }
       if($getentries3[avgvote]>=0.5)
       {
         print "<img src='halfacorn.gif' border='0' align='middle'>";  
       }
       print "&nbsp;($getentries3[totalvotes] votes)";
     }
     print "&nbsp;&nbsp;&nbsp;<b>Date added:</b>&nbsp;$getentries3[dateadded]";
     print "<br><A href='rate.php?ID=$getentries3[tutid]'>Rate It</a>";
     print "</td></tr></table><br><br>";
   }
   print "<table clas='maintable'><tr class='headline'><td>";
   print "<p align='right'><b>Page:</b> ";  
         $order="SELECT * from tut_entries where (title like '%$searchterm%' or description like '%$searchterm%')  and validated='1' order by rankscore DESC limit $start, 15";
         $order2=mysql_query($order) or die("2");
         $num=mysql_num_rows($order2);
         $d=0;
         $f=0;
         $g=1;
         $prev=$start-15;
         $next=$start+15;
         if($start>=15)
         {
           print "<A href='index.php?start=$prev'><font color='white'><<</font></a>&nbsp;";
         }
         while($order3=mysql_fetch_array($order2))
         {
          if($f>=$start-15&&$f<=$start+7*15)
          {
           if($f%15==0)
           { 
             print "<A href='index.php?start=$d'><b><font color='white'>$g</font></b></a> ";
             $g++;
           }  
         }
          $d=$d+1;
          $f++;
         }
        if($start<=$num-15)
        {
          print "<A href='index.php?start=$next'><font color='white'>>></font>";
        }
   print "</td></tr></table>";
   print "</td>";
   print "<td width=20% valign='top'>";
   print "<table class='maintables'><tr class='headline'><td>Sites to Visit</td></tr>";
   print "<tr class='mainrow'><td>";
   include "side.php";
   print "</td></tr>";
   print "</table></td></tr></table>";
   

   

}


?>
<?php
$tutorialuser=$_SESSION['thename'];
$getuser="SELECT * from tut_users where basename='$tutorialuser'";
$getuser2=mysql_query($getuser) or die(mysql_error());
$getuser3=mysql_fetch_array($getuser2);
if($getuser3['permissions']==3)
{
  print "<A href='admin/index.php'>Admin Panel</a>";
}
?>


