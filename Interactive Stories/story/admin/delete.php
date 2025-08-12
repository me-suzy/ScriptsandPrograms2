<?
include "connect.php";
session_start();
if (isset($_SESSION['username']))
{
  $ID=$_GET['ID'];
  $delquery="SELECT * from s_entries where ID='$ID'";
  $delquery2=mysql_query($delquery) or die("There is no ID");
  $delquery3=mysql_fetch_array($delquery2);
  $update="UPDATE s_titles set numposts=numposts-1 where ID='$delquery3[parent]'";
  mysql_query($update) or die("Could not update");
  $del="DELETE from s_entries where ID='$ID'";
  mysql_query($del) or die("Failed to delete, muhahaha");
  print "Entry Deleted successfully, redirecting to admin panel <META HTTP-EQUIV = 'Refresh' Content = '2; URL =index.php'>";


 }
else
{
  print "Not logged in as admin";
}
?>

 
