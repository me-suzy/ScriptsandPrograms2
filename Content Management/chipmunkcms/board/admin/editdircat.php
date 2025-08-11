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
    print "<table class='maintable'><tr class='headline'><td colspan='2'><center>Edit Category</center></td></tr>";
    print "<tr class='forumrow'><td>";
    $selcat="SELECT * from tut_cats order by catname ASC";
    $selcat2=mysql_query($selcat) or die("Could not select category");
    $ct=mysql_num_rows($selcat2);
    traverse(0,0,$selcat2,$ct);
    print "</td></tr></table></td></tr></table>";

  
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

function traverse($root, $depth, $sql, $ct) 
{ 
     $row=0; 
     while ($acat = mysql_fetch_array($sql)) 
     { 
    
          if ($acat['parentcat'] == $root) 
          { 
               if($acat['parentcat']==0)
               {
                 print "</td></tr>";
                 print "<tr class='forumrow'><td>";
               }
               while ($j<$depth) 
               {     
                     print "&nbsp;&nbsp;";
                    $j++; 
               } 
               if($depth>0)
               {
                 print "-";
               }
              
               print "<A href='editdircategory.php?ID=$acat[catID]'>$acat[catname]</a><br>";           
               mysql_data_seek($sql,0); 
              
               traverse($acat['catID'], $depth+1,$sql,$ct); 
              
          } 
          $row++;
          if($row<$ct)
          {
            mysql_data_seek($sql,$row);
          } 
          
     } 
      
}

?>

