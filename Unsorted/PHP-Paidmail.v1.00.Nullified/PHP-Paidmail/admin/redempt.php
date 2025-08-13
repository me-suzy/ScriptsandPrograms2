<?
include('../includes/global.php');

$contents=file_reader("redempt.html");
print <<<HTM
   $contents
HTM;
//DB connectivity
$link=dbconnect();

if ($action=="add")
{
    $qry="select * from redempt where track_id='$id'";
    $res=mysql_query($qry);
    $num=mysql_num_rows($res);
    if($num==0)
    {
	$count=substr_count($id,'"');
	if($count> 1)
	$id=str_replace('"',$id,"\"");
	$query="insert into redempt values ('$id','$for',$amount,'$descrip')";
	if (mysql_query($query))
	    print "Operation Completed";
    }
}
if ($action=="done")
{
    $count=substr_count($id,'"');
    if($count > 1)
        $id=str_replace('"',$id,"\"");
    if ($edit)
    {
        $count=substr_count($id,'"');
        if($count > 1)
             $id=str_replace('"',$id,"\"");
        $query2="update redempt set item='$for',amt=$amount,r_desc='$descrip' where track_id='$id'";
        mysql_query($query2);
    }
    elseif ($delete)
    {
        $query3="delete from redempt where track_id='$id'";
        mysql_query($query3);
    }
}

$contents=<<<HTM
       <br><font face="arial" size="3"><b><center>Existing Redemptions</center></b></font>
HTM;
print $contents;
$query1="select * from redempt";
if ($res1=mysql_query($query1)) {
    while ($data=mysql_fetch_array($res1))
    {
 	$output=<<<HTM

		<form action="redempt.php?action=done" method="POST">

		<table width="100%" align="center">

		<tr><td><font face="arial" size="2">Tracking ID:</font></td><td><b>$data[track_id]</b></td>
		
		<td rowspan="4"><input type="submit" name="edit" value="Edit Redemption">

		<br><br><input type="submit" name="delete" value="Delete Redemption">

		</td></tr>

		<tr><td><font face="arial" size="2">Item:</font></td><td><input type="text" name="for" value="$data[item]"><br>

		<font face="arial" size="2">(Redeem XXXX for A <u>[ITEM NAME]</font></u>)</font></td></tr>

		<tr><td><font face="arial" size="2">Amount:</font></td><td><input type="text" name="amount" value="$data[amt]"><br>

		<font face="arial" size="2">(How much the users have to have earned to be able

		to redeem for this item In Cents)</font></td></tr>

		<tr><td><font face="arial" size="2">Description:</font></td><td><textarea name="descrip">$data[r_desc]</textarea><br>

		<font face="arial" size="2">(A Description of the Item)</font></td></tr>

		</table>

		<input type="hidden" name="id" value='$data[track_id]'>

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
