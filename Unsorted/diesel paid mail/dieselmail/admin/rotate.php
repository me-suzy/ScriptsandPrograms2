<?php
include ('../includes/global.php');

$contents=file_reader("$admin_path/rotate.html");
print <<<HTM
     $contents
HTM;

//DB connectivity
$link=dbconnect();

if ($action=="add")
{
    $query="insert into banner set title='$title', html='$html', user='$user', clicks=$clicks";
    $qry1="insert into banner_imp set count=0 , click_date=now()";
    if((mysql_query($qry1)) &&(mysql_query($query)))  {
        print "Operation Completed";
    }
}
elseif ($action=="done")
{
    if ($edit)
    {
	$query2="update banner set html='$html', user='$user', clicks=$clicks where banner_id='$id'";
 	mysql_query($query2);
    }
    elseif ($delete)
    {
	$query3="delete from banner where banner_id='$id'";
	mysql_query($query3);
	$query4="delete from banner_imp where banner_id='$id'";
	mysql_query($query4);
    }
}
$contents=<<<HTM
       <hr>
       <br><font face="arial" size="3"><b><center>Existing Banners</center></b></font>
HTM;

print $contents;

$query1 = "select * from banner";
if($rs1 = mysql_query($query1))
{
    while ($data=mysql_fetch_array($rs1))
    {

	$output=<<<HTM
	<form action="rotate.php?action=done" method="POST">

	<table width="100%" align="center">

	<tr><td><font face="arial" size="2">Title:</font></td><td align="center"><b>$data[title]</b></td>

	<td rowspan="7"><input type="submit" name="edit" value="Edit Banner">

	<br><br><input type="submit" name="delete" value="Delete Banner">

	</td></tr>

	<tr><td><font face="arial" size="2">HTML:</font></td><td><textarea name="html">$data[html]</textarea></td></tr>

	<tr><td><font face="arial" size="2">E-mail Id:</font></td><td><b><input type="text" name="user" value="$data[user]"></b></td></tr>
	<tr><td><font face="arial" size="2"># of Clicks/Impressions:</font></td><td><input type="text" name="clicks" value="$data[clicks]"></td></tr>
	</table>

	<input type="hidden" name="id" value="$data[banner_id]">

	</form>
HTM;

print $output;
    }
}

$output=<<<HTM
	<br><br><pre> Click the link.<a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
HTM;
print $output;

dbclose($link);
?>