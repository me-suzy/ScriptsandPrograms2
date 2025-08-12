<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");
}else{




/* Step0 - read the Alias from the DB, to remove the re-direction from the .htaccess file */

$result=mysql_query("SELECT Alias FROM pages WHERE PageId='$pageid'");
$row=mysql_fetch_row($result);
$alias=$row[0];

$subresult=mysql_query("SELECT PageId, Alias FROM pages WHERE RefId='$pageid' order by PageId");
$sub_num_rows=mysql_num_rows($subresult);


/* Step1 - remove the section(s) from the DB */
mysql_query("DELETE FROM pages WHERE PageId='$pageid'");

/* ... and the subsections */
mysql_query("DELETE FROM pages WHERE RefId='$pageid'");



/* Step2 - remove the directory */

$imagedir="../sections/$pageid/images";

$dh=opendir($imagedir);
while ($file=readdir($dh))
	{if ($file!="." && $file!="..") unlink("$imagedir/$file");
	}
closedir($dh);
rmdir($imagedir);

$filedir="../sections/$pageid/files";
$dh=opendir($filedir);
while ($file=readdir($dh))
	{if ($file!="." && $file!="..") unlink("$filedir/$file");
	}
closedir($dh);
rmdir($filedir);


$rootdir="../sections/$pageid";
$dh=opendir($rootdir);
while ($file=readdir($dh))
	{if ($file!="." && $file!="..") unlink("$rootdir/$file");
	}
closedir($dh);
rmdir($rootdir);


/* Step3 - remove the redirection from the .htaccess file */


if ($alias!="")
	{$file = "../.htaccess";
	$file_cont=file_get_contents($file);
	$file_cont_new=str_replace("\n Redirect 301 /$alias $thisurl/index.php?pageid=$pageid","",$file_cont);
	$handle=fopen($file,"w");
	fwrite($handle,$file_cont_new);
	fclose($handle);
	}
if ($sub_num_rows!=0)
	{
	while ($subrow=mysql_fetch_row($subresult))
		{$subpageid=$subrow[0];
		$subalias=$subrow[1];
		if ($subalias!="")
			{$file = "../.htaccess";
			$file_cont=file_get_contents($file);
			$file_cont_new=str_replace("\n Redirect 301 /$subalias http://mycms.arkiva.pl/index.php?pageid=$subpageid","",$file_cont);
			$handle=fopen($file,"w");
			fwrite($handle,$file_cont_new);
			fclose($handle);
			}
		}
	}




/* Communicate the success */


if (!isset($f)) $f="structure";
if (!isset($sf)) $sf="del";

commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>Control panel - delete a section.</h2></center><br /><br />
	<b>The section (and its subsections) have been removed from the service.</b><br />
<br /><br />
<br />
	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();



}
?>
