<?php
session_start();
include "../connect.php";
?>
<link rel='stylesheet' href='../style.css' type='text/css'>
<?php
$user=$_SESSION['user'];
$selectuser="SELECT * from b_users where username='$user'";
$selectuser2=mysql_query($selectuser);
$selectuser3=mysql_fetch_array($selectuser2);
if($selectuser3[status]>="3")
{
    print "<center><table border='0' width='95%' cellspacing='20'>";
    print "<tr>";
    print "<td valign='top' width='25%'>";
    print "<table class='maintable'><tr class='headline'><td><center>Admin Options</center></td></tr>";
    print "<tr class='forumrow'><td>";
    include "adminleft.php";
    print "</td></tr></table></td>";
    print "<td valign='top' width='75%'>";
    print "<table class='maintable'><tr class='headline'><td><center>Main Admin</center></td></tr>";
    print "<tr class='forumrow'><td>";
    if(isset($_POST['submit']))
    {
       $catname=$_POST['catname'];
       $parentcat=$_POST['parentcat'];
       $addcat="INSERT into tut_cats(catname, parentcat) values ('$catname','$parentcat')";
       mysql_query($addcat) or die("Could not query");
       print "Category added.";
       print "</td></tr></table></td></tr></table>";

    }
    else
    {  
       
       print "<table border='0'>";
       print "<form action='adddircat.php' method='post'>";
       print "<tr class='mainrow'><td>Category Name:</td><td><input type='text' name='catname' size='20'></td></tr>";
       print "<tr class='mainrow'><td>Parent Category:</td></td><td>";
       print "<select name='parentcat'>";
       print "<option value='0'>None</option>";
       $selcat="SELECT * from tut_cats order by catname ASC";
       $selcat2=mysql_query($selcat) or die("Could not select category");
       traverse(0,0,$selcat2);
       print "</select><br>";
       print "</td></tr>";
       print "<tr class='mainrow'><td></td><td><input type='submit' name='submit' value='submit'></td></tr>";
       print "</table></form></td></tr></table></td></tr></table>";
       
    }
  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Not logged in</center></td></tr>";
  print "<tr class='mainrow'><td>You are not logged in as admin</td></tr>";
  print "</table>";

}

?>


<?php
function traverse($root, $depth, $sql) 
{ 
     $row=0; 
     while ($acat = mysql_fetch_array($sql)) 
     { 
          if ($acat['parentcat'] == $root) 
          { 
               print "<option value='" . $acat['catID'] . "'>"; 
               $j=0; 
               while ($j<$depth) 
               {     
                     print "&nbsp;&nbsp;";
                    $j++; 
               } 
               if($depth>0)
               {
                 print "-";
               }
               print $acat['catname'] . "</option>"; 
               mysql_data_seek($sql,0); 
               traverse($acat['catID'], $depth+1,$sql); 
                
          } 
          $row++; 
          mysql_data_seek($sql,$row); 
           
     } 
} 
?>