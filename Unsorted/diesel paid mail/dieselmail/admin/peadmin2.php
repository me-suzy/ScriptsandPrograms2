<?
include ('../includes/global.php');

//connecting DB
$link=dbconnect();
$query="select ad_id,email_id from advt_email where type='paid' ";
?>
	<html><body>
	<font face="arial" size="3"><b><center>Paid-Email List</center></b></font>
	<font face="arial" size="2">
	<font color="red"><b></b></font><br>
	<a href="<?=$admin_url?>/pe.php?s=new" target="pe1">Make a New Link</a><br>
	For the link to insert into your e-mails, please click "Edit" next to the paid-email of your choice, below.
	<ol type=1></font>
<?   
if($re=mysql_query($query))
{
   while($res=mysql_fetch_array($re))
   {
?>        <li><?=$res[0]?> [<a href="<?=$admin_url?>/pe.php?t=delete&adid=<?=$res[0]?>&refresh=yes" target="pe1">Delete</a> | <a
href="<?=$admin_url?>/pe.php?t=edit&adid=<?=$res[0]?>" target="_top">Edit</a>]
<?
   }
}

dbclose($link);
?>
          </ol>
          </body></html>
<?
print  <<<HTM
<br><br><pre>Click the link. <a href="$admin_url/admin.php" target="_top">Go to Admin Index Page</a></pre>
HTM;
?>