<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_78; ?></strong></font><font size="4" face="Arial, Helvetica, sans-serif"></font><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif">
  <?PHP
  	@ini_set('max_execution_time', '950*60');
	@set_time_limit (950*60);

		  $result = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result);
$murl = $row["murl"];
?>
  </font></b></font></b></font></b></font></b></font></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_84; ?></strong>:</font></p>
<p> <font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP
  @MYSQL_CONNECT("$host","$username","$password") OR DIE ("<b>Failed to connect to server.</b><br>Information entered is incorrect.<BR>Please check your username, password, and hostname that you entered.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 
@MYSQL_SELECT_DB ("$nama_db") OR DIE ("<b>Database Does not exist.</b><BR>Please check/verify your database name and re-enter it.<P><a href=\"javascript:window.history.go(-1);\">Back</a>"); 

  $nama_bak=date('D,d-m-Y');
  $nama_bak2=time();
  $nama=$nama_bak2."_".$nama_bak;
  $directory="backups";
  if($backup){
  if(isset($table)){
passthru("mysqldump -u\"$username\" -p\"$password\" --opt $nama_db $nama_tabel > $directory/$nama.sql");
}
else
{
  $sikat=passthru("mysqldump -u\"$username\" -p\"$password\" $nama_db > $directory/$nama.sql");
  passthru("gzip $directory/$nama.sql");
 if(!$sikat){echo"$lang_85<BR><br>$lang_86:<br>
<br>
 <a href=\"$murl/$directory/$nama.sql.gz\">$murl/$directory/$nama.sql.gz</a><p>$lang_87";}
 }
}
if($impor){
 if (is_uploaded_file($userfile)) {
    exec("mysql -u\"$username\" -p\"$password\" $nama_db < $userfile");
echo "";
} else {
    echo "ERROR.  ABORTING.  FILE OVERRIDE: filename '$userfile'.";
}
}
?>
  &nbsp;</font></p>
