<?php
ob_start();
//include the header
require("top.php");
echo "<br>";
//show all the members...
$result = mysql_query("SELECT * FROM loginphp") 
or die(mysql_error());
echo "<center>";
echo "<table><tr><td><b>Username</b></td><td><b>Email</b></td></tr>";
if($_GET['action'] == '')
{
header("Location:?action=nextpage&id=1");
}
if($_GET['action'] == 'nextpage')
{
// Print out the contents of the entry
$num=mysql_numrows($result);
$strBGColor = '#EAF1FF';
$i=0;
while ($i < $num)
{
$number = $number + 1;
$number2 = 25;
$id = $_GET['id'] + 1;
$id3 = $_GET['id'] - 1;
$id2 = $_GET['id']*$number2;
if($number < (($id3*$number2)+1) || $number > $_GET['id']*$number2)
{
$row = mysql_fetch_array( $result );
$i++;
$number4 = $number;
}
else
{
if($strBGColor == '#EAF1FF')
{
$strBGColor = '#8ab4ff';
}
else
{
$strBGColor = '#EAF1FF';
}
$row = mysql_fetch_array( $result );
echo "<tr bgcolor='$strBGColor'><td>";
echo "<img src=images/member.gif border=0>" . $row['Uname'] . "</td><td>";
echo "<a href=mailto:" .$row['Email'] . "><img src=images/email.gif border=0>" . $row['Email'] . "</td></tr>";
$i++;
$username = $row['Uname'];
}
}
}
echo "</table>";
$next = $_GET['id'] + 1;
$back = $_GET['id'] - 1;
if($_GET['id'] == '1')
{
echo "<a href=?action=nextpage&id=$next>next</a>";
}
if($_GET['id'] > '1')
{
if($_GET['id'] < ($number4/$number2))
{
echo "<a href=?action=nextpage&id=$back>back</a> | <a href=?action=nextpage&id=$next>next</a>";
}
else
{
echo "<a href=?action=nextpage&id=$back>back</a>";
}
}
if($username == '')
{
echo "<a href=?action=nextpage&id=$back>back</a>";
}
?>