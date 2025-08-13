<?php
$refok='no';
if(strlen($BHCIntranet) > 0)
	{
	$ipaddy = $BHCIntranet; $refok = 'yes';
	} else 
		{
		$ipaddy = getenv(REMOTE_ADDR);
		dbconnect($dbusername,$dbuserpasswd);
		$result = mysql_query( "select * from userinfo where ipaddress='$ipaddy'");
		$info=mysql_fetch_array($result);      
		if($info[ipaddress] and strpos($PHP_SELF,"index.php"))
			{ 
			      		
			$BHCIntranet = $info[ipaddress];                                           
			SetCookie("BHCIntranet",$BHCIntranet,time()+6000000); 
			$refok = 'yes';  echo $info[ipaddress];      					
			} else
				{
				dbconnect($dbusername,$dbuserpasswd);
				$remote_user=getenv(REMOTE_USER);
				$result = mysql_query( "select * from userinfo where login='$remote_user'");
				$info=mysql_fetch_array($result);
				if($info[ipaddress] and strpos($PHP_SELF,"index.php"))
					{		
					$BHCIntranet = $info[ipaddress];                                             
					SetCookie("BHCIntranet",$BHCIntranet,time()+6000000);
					$refok='yes';   //     echo "<br>Line26";
					} else {
						if($luser and $dbpasswd)
							{
							$result=mysql_query("select * from userinfo where login='$luser'");
							if($result and strpos($PHP_SELF,"index.php"))
								{
								$info=mysql_fetch_array($result);
								if($dbpasswd == $info[password])
									{
					                               	$BHCIntranet = $info[ipaddress];                                             
									SetCookie("BHCIntranet",$BHCIntranet,time()+6000000);
									$refok='yes'; 
									} else 
										{
										echo "  <html><body>
											<center>
											<form method='post' action='", $PHP_SELF, "'>	
											<table border='0' cellpadding='0' cellspacing='0' width='300'>
											<tr><td align='right'>Login Name: </td>
											<td><input type='text' name='luser' value=''></td></tr>
											<tr><td align='right'>Password: </td>
											<td><input type='password' name='dbpasswd' value=''></td></tr>
											<tr><td colspan='2' align='right'><input type='submit' value='Login' name='submit'></form></td></tr></table>
											</center></body></html>";
										}
								}
							} else 
								{
								echo "  <html><body>
									<center>
									<form method='post' action='", $PHP_SELF, "'>	
									<table border='0' cellpadding='0' cellspacing='0' width='300'>
									<tr><td align='right'>Login Name: </td>
									<td><input type='text' name='luser' value=''></td></tr>
									<tr><td align='right'>Password: </td>
									<td><input type='password' name='dbpasswd' value=''></td></tr>
									<tr><td colspan='2' align='right'><input type='submit' value='Login' name='submit'></form></td></tr></table>
									</center></body></html>";
								}
						}  
				}
		}
?>
