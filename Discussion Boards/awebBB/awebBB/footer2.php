
</div></div>
<?
// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = "SELECT copyright, adenable, adcode, adlocation FROM prefs LIMIT 0,1"; 
$result = mysql_query($query); 
while($r=mysql_fetch_array($result)) 
{ 
$adenable = $r["adenable"]; 
$adcode = $r["adcode"]; 
$adlocation = $r["adlocation"]; 
if ($adenable == "yes" & $adlocation == "bottom") {
echo "<div class=\"ad-box\">" . $adcode . "</div>";
} else { } 
?>
  <div class="footer"><table cellpadding="0" cellspacing="0" border="0" width="700"><tr><td align="center" width="670">
<?
$copyright = $r["copyright"]; 
if ($copyright == "") {
} else {
echo "$copyright - ";
}
} 
mysql_close($db);
$disaup = "<iframe src=\"http://www.labs.aweb.com.au/aupdate.php?v=12\" width=\"13\" height=\"13\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\"></iframe>";

?>Forum developed by <a href="http://www.labs.aweb.com.au">aWeb Labs</a></td><td width="15"><a href="webmin/"><img src="images/lock.png" border="0" width="13" height="13" alt="Administer forum"></a></td><td width="15"><?=$disaup;?></td></tr></table>  </div></div></div>
	</body>
</html>

