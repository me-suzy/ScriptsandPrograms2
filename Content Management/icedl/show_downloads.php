<?php 
			$path =  __FILE__;
$path = preg_replace( "'\\\show_downloads\.php'", "", $path);
$path = preg_replace( "'/show_downloads\.php'", "", $path);
		include("$path/config.php");
$sQuery = "SELECT*FROM downloads ORDER BY id DESC";
			$sResult = MySQL_Query($sQuery);
			$Rows = MySQL_Num_Rows($sResult);
			
			$x = "1";
			while ($Output = MySQL_Fetch_Array($sResult)) {
			$Output = array_reverse($Output);
				($x%2) ? $bg = "#FFFFFF": $bg = "#C4C8D4";
				
					
echo "<table width=\"$table_size\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td><table width=\"20%\" border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\">
      <tr>
        <td width=\"20%\" height=\"21\"><center><a href=\"".$Output['picture']."\"><img src=\"".$Output['picture']."\" height=\"60\" width=\"60\" border=\"0\"></a><br> Click To Enlarge</center><br>
		
		Submitted by:   ".$Output['username']."
        </td>
      </tr>
    </table>
      <table width=\"50%\" height=\"25\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
          <td width=\"50%\" height=\"21\" bgcolor=\"\"><h2>".$Output['name']."</h2>
		  ".$Output['description']."<br>
		  <a href=\"".$Output['download']."\">Download</a><br><br><br><br>
		  
		  </td>
        </tr>
      </table></td>
  </tr>
</table>
 <HR align=\"left\" width=\"100%\" SIZE=\"1\" style=\"COLOR: #181b16\">";
				$x++;
			}
			echo "</table>";
			echo "<br><br><center><br><br>Powered by <a href=\"www.ice-host.net\" target=\"_blank\">Ice-Downloader</a></center>";
?>