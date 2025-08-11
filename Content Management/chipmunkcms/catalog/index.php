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
if(isset($_GET['tutid'])&&isset($_GET['catID'])) // case for looking at individual entry
{
   $getfullpath="SELECT * from tut_entries, tut_cats where tut_cats.catID=tut_entries.catparent and tut_entries.tutid='$tutid'";
   $getfullpath2=mysql_query($getfullpath) or die(mysql_error());
   $getfullpath3=mysql_fetch_array($getfullpath2);
   print "<title>PHP Tutorials--$getfullpath3[title]</title>";
   $tutid=$_GET['tutid'];
   $catID=$_GET['catID'];
   print "<center><table class='maintable'>";
   print "<form action='search.php' method='post'>";
   print "<tr class='headline'><td>";
   print "<center>Search:<input type='text' name='searchterm' size='30'>&nbsp;<input type='submit' name='submit' value='submit'></center></td></tr></form>";
   print "<tr class='mainrow'><td><center><A href='new.php'><b>Newest listings</b></a>-<A href='addtutorial.php'><b>Add a tutorial</b></a>-<A href='modify.php'><b>Modify a listing</b></a>-<A href='getrated.php'><b>Getting Rated</b></a>-<A href='mailto:webmaster@chipmunk-scripts.com'><b>Contact webmaster</b></a></center></td></tr>";
   print "</table></center><br>";
   print "<center><table class='maintable'><tr>";
   print "<td valign='top' width=20%>";
   print "<table class='maintable'><tr class='headline'><td> Main Categories</td></tr>";
   print "<tr class='mainrow'><td>";
   $getcatss="SELECT * from tut_cats where parentcat='0' order by catname ASC";
   $getcatss2=mysql_query($getcatss) or die("Could not get categories");
   while($getcatss3=mysql_fetch_array($getcatss2))
   {
      print "<img src='regular.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcatss3[catID]'><b>$getcatss3[catname]</b></a><br>";
   }
   print "</td></tr></table></td>";
   print "<td valign='top' width=60%>";
   print "<table class='maintable'><tr class='headline'><td>";
   print "<A href='index.php'><font color='white'>Home</font></a>>><A href='index.php?catID=$getfullpath3[catID]'><font color='white'>$getfullpath3[catname]</font></a>>>$getfullpath3[title]";
   print "</td></tr></table>";
   print "<table class='maintable'><tr class='headline'><td>$getfullpath3[title]</td></tr>";
   print "<tr class='mainrow'><td><A href='$getfullpath3[url]'>Read tutorial now</a><br><br>";
   print "<b>Description:</b>&nbsp;$getfullpath3[description]<br><br>";
   print "<center><table class='maintable'><tr class='headline'><td colspan='2'>Resource Specifications</td></tr>";
   print "<tr class='mainrow'><td>Added:</td><td>&nbsp;&nbsp;$getfullpath3[dateadded]</td></tr>";
   print "<tr class='mainrow'><td>Author:</td><td>&nbsp;&nbsp;$getfullpath3[author]</td></tr></table>";
   print "</td></tr></table>";
   print "</td>";
   print "<td valign='top'>";
   print "<table class='maintables'><tr class='headline'><td width='120'>Sites to Visit</td></tr>";
   print "<tr class='mainrow'><td width='120'>";
   include "side.php";
   print "</td></tr>";
   print "</table></td></tr></table>";
   
  

}
else if(isset($_GET['catID'])) // looking at individual category
{
   $getpath="SELECT * from tut_cats where catID='$catID'";
   $getpath2=mysql_query($getpath) or die("Could not get path");
   $getpath3=mysql_fetch_array($getpath2);
   print "<title>PHP Scripts$getpath3[catname]</title>";
   $catID=$_GET['catID'];
   print "<center><table class='maintable'>";
   print "<form action='search.php' method='post'>";
   print "<tr class='headline'><td>";
   print "<center>Search:<input type='text' name='searchterm' size='30'>&nbsp;<input type='submit' name='submit' value='submit'></center></td></tr></form>";
   print "<tr class='mainrow'><td><center><A href='new.php'><b>Newest listings</b></a>-<A href='addtutorial.php'><b>Add a tutorial</b></a>-<A href='modify.php'><b>Modify a listing</b></a>-<A href='getrated.php'><b>Getting Rated</b></a>-<A href='mailto:webmaster@chipmunk-scripts.com'><b>Contact webmaster</b></a></center></td></tr>";
   print "</table></center><br>";
   print "<center><table class='maintable'><tr>";
   print "<td valign='top' width=20%>";
   print "<table class='maintable'><tr class='headline'><td> Main Categories</td></tr>";
   print "<tr class='mainrow'><td>";
   $getcatss="SELECT * from tut_cats where parentcat='0' order by catname ASC";
   $getcatss2=mysql_query($getcatss) or die("Could not get categories");
   while($getcatss3=mysql_fetch_array($getcatss2))
   {
      print "<img src='regular.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcatss3[catID]'><b>$getcatss3[catname]</b></a><br>";
   }
   print "</td></tr></table></td>";
   print "<td valign='top' width=60%>";
   print "<table class='maintable'><tr class='headline'><td>";
   $getpath="SELECT * from tut_cats where catID='$catID'";
   $getpath2=mysql_query($getpath) or die("Could not get path");
   $getpath3=mysql_fetch_array($getpath2);
   print "<A href='index.php'><b><font color='white'>Home</font></b></a>>><A href='index.php?catID=$getpath3[catID]'><b><font color='white'>$getpath3[catname]</font></b></a>";
   print "</td></tr></table>";
   $timenow=date("U");
   $getsubcats="SELECT * from tut_cats where parentcat='$catID' order by catname ASC";
   $getsubcats2=mysql_query($getsubcats) or die("Could not get subcategories");
   $getsubcats3=mysql_num_rows($getsubcats2);
   if($getsubcats3>0)
   {
   
      $lday=$timenow-24*3600;
      $lweek=$timenow-24*3600*7;
      print "<center><table class='maintable'><tr class='headline'><td>Sub-Categgories</td></tr>";
      print "<tr class='mainrow'><td>";
      while($getsubcats4=mysql_fetch_array($getsubcats2))
      {
         if($getsubcats4[lastadded]>$lday)
         {
           print "<img src='reallynew.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getsubcats4[catID]'>$getsubcats4[catname]</a>";
         }
         else if($getsubcats4[lastadded]>$lweek)
         {
           print "<img src='oneweek.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getsubcats4[catID]'>$getsubcats4[catname]</a>";
         }
         else
         {
           print "<img src='regular.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getsubcats4[catID]'>$getsubcats4[catname]</a>";
         }
         
          print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        
         
       }
       print "</td></tr></table><br><br>";
     }
   $newtime=$timenow-24*3600*7;
   $getnewentries="SELECT * from tut_entries where validated='1' and catparent='$catID' and timeadded>'$newtime' order by timeadded DESC";
   $getnewentries2=mysql_query($getnewentries) or die(mysql_error());
   while($getnewentries3=mysql_fetch_array($getnewentries2))
   {
     print "<table class='maintable'><tr class='headline'><td><A href='index.php?catID=$catID&tutid=$getnewentries3[tutid]'><font color='white'>$getnewentries3[title]</font></a>&nbsp;<Img src='new.gif' border='0' align='middle'></td></tr>";
     print "<tr class='mainrow'><td>$getnewentries3[shortdes]<br><br>";
     print "<b>Rating:&nbsp;</b>";
     if($getnewentries3[totalvotes]==0)
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
     print "<br><A href='rate.php?ID=$getnewentries3[tutid]'>Rate Tutorial</a>";
     print "</td></tr></table><br><br>";
   }
   if(!isset($_GET['start']))
   {
     $start=0;
   }
   else
   {
     $start=$_GET['start'];
   }
   $getentries="SELECT * from tut_entries where catparent='$catID' and timeadded<'$newtime' and validated='1' order by rankscore DESC limit $start, 15";
   $getentries2=mysql_query($getentries) or die("Could not get entries2");
   while($getentries3=mysql_fetch_array($getentries2))
   {
     print "<table class='maintable'><tr class='headline'><td><A href='index.php?catID=$catID&tutid=$getentries3[tutid]'><font color='white'>$getentries3[title]</font></a>&nbsp;</td></tr>";
     print "<tr class='mainrow'><td>$getentries3[shortdes]<br><br>";
     print "<b>Rating:&nbsp;</b>";
     if($getentries3[totalvotes]==0)
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
         $order="SELECT * from tut_entries where catparent='$catID' and timeadded<'$newtime' order by rankscore DESC";
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
   print "<table class='maintables'><tr class='headline'><td width='120'>Sites to Visit</td></tr>";
   print "<tr class='mainrow'><td width='120'>";
   include "side.php";
   print "</td></tr>";
   print "</table></td></tr></table>";
   

   

}
else //the root case to display all categories
{
   print "<title>PHP tutorials, counter, guestbooks, etc</title>";
   print "<center><table class='maintable'>";
   print "<form action='search.php' method='post'>";
   print "<tr class='headline'><td>";
   print "<center>Search:<input type='text' name='searchterm' size='30'>&nbsp;<input type='submit' name='submit' value='submit'></center></td></tr></form>";
   print "<tr class='mainrow'><td><center><A href='new.php'><b>Newest listings</b></a>-<A href='addtutorial.php'><b>Add a tutorial</b></a>-<A href='modify.php'><b>Modify a listing</b></a>-<A href='getrated.php'><b>Getting Rated</b></a>-<A href='mailto:webmaster@chipmunk-scripts.com'><b>Contact webmaster</b></a></center></td></tr>";
   print "</table></center><br>";
   print "<center><table class='maintable'><tr><td width='75%' valign='top'>";
   print "<table class='maintable'>";
   print "<tr class='headline'><td><center><b>PHP Tutorial categories</b></center></td></tr>";
   print "<tr class='mainrow'><td>";
   $count=0;
   $day=date("U");
   $weekold=$day-3600*24*7;
   $dayold=$day-3600*24;
   $getcats="SELECT * from tut_cats where parentcat='0' order by catname ASC";
   $getcats2=mysql_query($getcats) or die(mysql_error());
   print "<table width=90%>";
   while($getcats3=mysql_fetch_array($getcats2))
   {
      if ($count%3==0)
      {
        print "<tr class='mainrow'><td valign='top'>";
        if($getcats3[lastadded]>$dayold)
        {
           print "<img src='reallynew.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        else if($getcats3[lastadded]>$weekold)
        {
           print "<img src='oneweek.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        else
        {
           print "<img src='regular.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        print "</center></td>";
      }
      else if($count%3==1)
      {
        print "<td valign='top'>";
        if($getcats3[lastadded]>$dayold)
        {
           print "<img src='reallynew.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        else if($getcats3[lastadded]>$weekold)
        {
           print "<img src='oneweek.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        else
        {
           print "<img src='regular.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        print "</center></td>";
      }
      else if($count%3==2)
      {
        print "<td valign='top'>";
        if($getcats3[lastadded]>$dayold)
        {
           print "<img src='reallynew.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        else if($getcats3[lastadded]>$weekold)
        {
           print "<img src='oneweek.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        else
        {
           print "<img src='regular.gif' border='0' align='middle'>&nbsp;<A href='index.php?catID=$getcats3[catID]'><b>$getcats3[catname]</b></a>($getcats3[numtutorials])";
        }
        print "</center></td></tr>";
      }
      $count++;
   }
   print "</table>";
   print "</td></tr></table>";
   print "<table class='maintable'><tr class='headline'><td>Legend</td></tr>";
   print "<tr class='mainrow'><td><img src='reallynew.gif' border='0' align='middle'>&nbsp;Last day Addition<br>";
   print "<img src='oneweek.gif' align='middle' border='0'>&nbsp;Last Week addition<br>";
   print "<img src='regular.gif' align='middle' border='0'>&nbsp;Over 1 week<br>";
   print "</td></tr></table>";
   print "</td>";
   print "<td valign='top' width='20%'>";
   print "<table class='maintables'>";
   print "<tr class='headline'><td width='120'><center><b>Sites to visit</b></center></td></tr>";
   print "<tr class='mainrow'><td width='120'>";
   include "side.php";
   print "</td></tr></table>";
   print "</td></tr></table>";




}

?>


