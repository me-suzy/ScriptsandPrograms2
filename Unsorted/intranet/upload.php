<?
include("config.php");
include("identity.php");
?>
<html>
<head>
<title>Upload File</title>
<?

$appheaderstring='File Upload';
include("header.php");

if($visitor=='yes')
	{
dbconnnect($dbusername,$dbuserpasswd);
		$result6=mysql_query("select * from visitor where id='$BHCid'");
		if(mysql_num_rows($result6) == 1) { $visit=mysql_fetch_array($result6); }
		if($hidden == '' and $visit[use_hidden_upload] == 'y') { $hidden = 'yes'; }
echo "<center><table width='400' cellpadding='0' border='0' cellspacing='0'>
<tr><td><img src='/intranet/visitor/header0120.gif' border='0' align='left'>
<font color='5555FF' size='5' face='Arial'>Welcome!</font>
<br>You can use this page to send a file to BHC staff.
<a href='/intranet/visitor/";
if($hidden=='yes') { echo "?hidden=yes"; }
echo "'>Click here</a> if you need to send one of us an instant message.</td></tr>
<tr><td bgcolor='5555FF'><font color='white'><b>BHC Intranet Visitor's Upload Page</b></font></td></tr><tr><td>";
	} else {
		echo "<center>&nbsp;<p><table width='400' cellpadding='0' border='0' cellspacing='0'><tr><td>";
		}

if($submit == 'Upload')
	{
	$old_picture_name = $picture_name;
	$picture_name = str_replace(" ","_",$picture_name);
	$picture_name = str_replace("(","_",$picture_name);
	$picture_name = str_replace(")","_",$picture_name);
	$picture_name = str_replace("'","!",$picture_name);
	$picture_name = str_replace('"','!',$picture_name);
	$picture_name = str_replace('&','and',$picture_name);
	if($hidden=='yes')
		{
		$command = "cp -f " . $picture . " " . $huploaddirectory . $picture_name;
        	exec($command);
		$message = $uploader . " uploaded " . $picture_name . " to " . $huploaddirectory . " on gabrielle.";
		} else
			{
			$command = "cp -f " . $picture . " " . $uploaddirectory . $picture_name;
        		exec($command);
			$message = $uploader . " uploaded " . $picture_name . " to " . $uploaddirectory . " on gabrielle.";
			}
	$logmsg = " sent a file to ";
	$logmsg = $uploader . $logmsg . $notify . " from " . getenv(REMOTE_ADDR) . ".";
	exec("echo $logmsg | smbclient -M Scott -U Gabrielle", $crap[], $nothin[$dude]);
	$currenttime = time();
	$timedata = getdate( $currenttime );
	if($timedata[hours] < 10) { $timedata[hours] = "0" . $timedata[hours]; }
	if($timedata[minutes] < 10) { $timedata[minutes] = "0" . $timedata[minutes]; }
	if($timedata[seconds] < 10) { $timedata[seconds] = "0" . $timedata[seconds]; }
	$logmsg = $timedata[month] . " " .$timedata[mday] . " " . $timedata[hours] . ":" . $timedata[minutes] . ":" . $timedata[seconds] . " FILE UPLOAD: " . $logmsg;
	$logmsg = $logmsg . " [" . $picture_name . ", " . $picture_type . ", " . $picture_size . "k]";
	exec("echo $logmsg  >> /usr/local/apache/logs/intranet.log");
	if($notify != "Nobody")
		{
dbconnect($dbusername,$dbuserpasswd);
		$result=mysql_query("select * from userinfo where login='$notify'");
		$list=mysql_fetch_array($result);
		$emailaddress=$list[emailaddress];
		$computername=$list[firstname];
		if($notifymethod == 'instant message')
			{
			$uploader = str_replace(" ","_",strtoupper($uploader));
			$message = $message . " The file will be deleted at 5pm on Friday.";
               	 	exec("echo $message | smbclient -M $computername -U $uploader");
			} else
				{
				$message = $message . "\nThe file will automatically be deleted at 5pm on Friday. Please consider retrieving it now:\nhttp://bhcinfo.tranquility.net/uploads/" . $picture_name;
				$subject = rawurlencode("[SYSTEM] File Upload Notification (Gabrielle)");
				$addtl = rawurlencode("From: nobody@bhcinfo.com");
				$url = "http://bhcinfo.com/admin/upload/email.php?emailaddress=" . $emailaddress . "&subject=" . $subject . "&message=" . rawurlencode($message) . "&addtl=" . $addtl;
				include($url);
				// mail($emailaddress,$subject,$message);   This doesn't work from Gabrielle because of the machine name.
				//						so the dirty work is off-loaded to Blue Cherry.
                        	}
		}
	if($picture_name)
		{ echo "Your file upload seems to have been successful. <br>File Name: ", $old_picture_name, "<br>File Size: ", $picture_size, "<br>File Type: ", $picture_type;
		if($hidden=='yes')
			{
			$output=exec("ls -laF $huploaddirectory | grep $picture_name");
			} else {
				$output=exec("ls -laF $uploaddirectory | grep $picture_name");
				}
		if($output) { echo "<br>File as Saved on Server:<br>", $output; } else { echo "<br><font color='red'>FILE NOT FOUND!</font>"; }
		if($notify != 'Nobody')
			{
                        if($notifymethod=='instant message')
				{
				echo "<br>If their computer was on at the time, ", $notify, " was notified that the file was uploaded.";
				} else {
                                        echo "<br>An e-mail notification was sent to ", $emailaddress, ".";
					}					
			}
		} else { echo "Upload Failed. <br>.", $picture_name, ".", $picture_size, ".", $picture_type; }
	echo "<form method='post' action='", $PHP_SELF, "'><input type='hidden' name='visitor' value='", $visitor, "'><input type='hidden' name='hidden' value='", $hidden, "'><input type='submit' value='Upload Another File'></form>";
	} else {
		echo "<font size='2'>There is a 15 megabyte file size limit. If you need to upload a larger file, please contact the system administrator. File
names with certain characters will be altered automatically.</font><br>";
		echo "<form enctype='multipart/form-data' method='post' action'", $PHP_SELF, "'>";
		echo "<input type='hidden' name='hidden' value='", $hidden, "'>";
		echo "<input type='hidden' name='visitor' value='", $visitor, "'>";
		echo "<input type='hidden' name='MAX_FILE_SIZE' value='12000000'>";
		echo "<font size='3' face='Arial'>File: </font><input type='file' name='picture' size='31'><p>";
		echo "<font size='3' face='Arial'>Your Name: </font><input type='text' name='uploader' size='24' value='";
		if($setting[login] != 'Visitor') { echo $setting[login]; } else { echo $visit[name]; }
		echo "'><p>";
		echo "<font size='3' face='Arial'>Notify </font><select name='notify'><option>Nobody";
dbconnect($dbusername,$dbuserpasswd);
		$result=mysql_query("select * from userinfo");
		while($list=mysql_fetch_array($result))
			{
		        echo "<option";
			if($list[login] == $visit[last_user_msg]) { echo " selected"; }
			echo ">", $list[login];
			}
		echo "</select><font size='3' face='Arial'> using </font><select name='notifymethod'><option>instant message<option>e-mail</select><p><input type='submit' value='Upload' name='submit'></form>";
		if($hidden=='yes')
			{
                        echo "<p><font color='red'><B>USING HIDDEN MODE</B></font><br>Any file you upload will only be available to the system administrator.";
			}
		}
       echo "</td></tr></table>";
if($BHCid) { echo "</center><a href='/visitor/'>Back</a>"; }
?>
</body></html>





