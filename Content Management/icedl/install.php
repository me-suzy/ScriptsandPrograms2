<?php 
include('config.php');
echo "Starting to install the 'downloads' table in the database '$database'.";
 ?><title>Ice-Downloader</title>
<br>
<br>
<?php 
$sql = mysql_query("CREATE TABLE `downloads` (
  `id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `picture` varchar(200) NOT NULL default '',
  `description` longtext NOT NULL,
  `download` longtext NOT NULL,
  `username` varchar(200) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;");
if($sql){ 
echo "Installation Succes! You can now use Ice-Downloader, you will be redirected to the login page in 5 seconds.<br><br>
<b>Your Username:</b> $username2<br>
<b>Your Password:</b> $password2<br><br>

Thanks for choosing Ice-Downloader as Download Manager. <br><br>

<b><font color=#FF0000>NOTE: You must remove the install.php file in order to login!</b></font>"; 
echo "<META HTTP-EQUIV=Refresh CONTENT=\"5; URL=index.php\">";
}
if(!$sql){
echo "Failed";
}


?>