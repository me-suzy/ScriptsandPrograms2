<?php
session_start();
include "connect.php";
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
    print "<table class='maintable'><tr class='headline'><td><center>Delete a category</center></td></tr>";
    print "<tr class='forumrow'><td>";
    if(isset($_POST['submit']))
    {
       $delcat=$_POST['delcat'];
       $changecats="update tut_cats set parentcat='0' where parentcat='$delcat'";
       mysql_query($changecats) or die("Could not update sub categories");
       $delentries="DELETE from tut_entries where catparent='$delcat'";
       mysql_query($delentries) or die("Could not delete entries");
       $delcat="DELETE from tut_cats where catID='$delcat'";
       mysql_query($delcat) or die("Could not delete category");
       print "Category Deleted";
    }
    else
    {    
      print "Delete a category:<br>";
      print "<form action='deletedircat.php' method='post'>";
      print "<select name='delcat'>";
      $getcats="SELECT * from tut_cats order by catname ASC";
      $getcats2=mysql_query($getcats) or die("Could not select category");
      traverse(0,0,$getcats2);
      print "</select><br>";
      print "<input type='submit' name='submit' value='submit'></form><br><br>";     
      print "*Note that deleting a category will delete all entries in the category and will move sub-categories up 1 level.<br><br>";

    }
  
}
else
{
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Not logged in</center></td></tr>";
  print "<tr class='mainrow'><td>You are not logged in as admin</td></tr>";
  print "<table>";

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
