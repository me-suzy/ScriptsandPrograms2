<?
include('config.php');
// Admin check
  if (!isset($_SERVER['PHP_AUTH_USER'])) {
   header('WWW-Authenticate: Basic realm="gnSOTW v1.0"');
   header('HTTP/1.0 401 Unauthorized');
   echo 'You are not authorized to be here.';
   exit;
  } else {
  $check_user = $_SERVER['PHP_AUTH_USER'];
  $check_pass = $_SERVER['PHP_AUTH_PW'];
	if($adminUser == $check_user && $adminPass == $check_pass){
			





					$sql = "SELECT * FROM sotw_week ORDER BY wid DESC LIMIT 1";
					$q = mysql_query($sql);
					while($row = mysql_fetch_array($q)){
					$wid = $row['wid'];
					}
					
					if($admin == 'judge' && $wid && $sig){
					$sql = "UPDATE sotw_submits SET winner='Y' WHERE sig='$sig'";
					mysql_query($sql) or die(mysql_error());
					echo "SOTW Posted, new week created automatically.<br>";
					mysql_query("INSERT INTO sotw_week VALUES ('null')") or die(mysql_error());
					echo "New week table created.<br>";
					$wid = $wid+1;
					mkdir($sotwPath.$wid, 0777);
					echo "New week folder created.<br.";
					}
					?>
					
					<center>Click on the sig to post it as the winner:<br><br>
					<?
					$i = 1;
					if ($handle = opendir($sotwPath.$wid.'')) {
					   while (false !== ($file = readdir($handle))) { 
						   if ($file != "." && $file != "..") { 
							echo "<font color='white'>".$i++.".</font> <a href='?admin=judge&week=".$wid."&sig=".$file."'><img src='".$wid."/".$file."' border=0 /> </a><br>"; 
						   } 
					   }
					   closedir($handle); 
					}
					
					}
					}
					
					
					echo $copyright;
?></center>