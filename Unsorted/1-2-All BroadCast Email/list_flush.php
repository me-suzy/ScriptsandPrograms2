<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_175; ?> </strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"> 
<?PHP
	flush();
	mysql_close($db_link);
	ini_set('max_execution_time', '950*60');
	set_time_limit (950*60);
				require("engine.inc.php");
	$msgCounter = "1";
	
//SELECT postid FROM adresses GROUP BY epost HAVING count(epost) > 1 ; 

					$res = mysql_query("SELECT id FROM ListMembers 
					WHERE nl = '$nl'
					GROUP BY email HAVING count(email) > 1
					");

$i = 0;
if ($c1 = mysql_num_rows($res)){
while($row = mysql_fetch_array($res)) {
	$rid = $row["id"];
		mysql_query ("DELETE FROM ListMembers
                                WHERE id = '$rid'
								AND nl = '$nl'
								");
		$i++;
				if ($msgCounter == "75"){
				mysql_close($db_link);
				require("engine.inc.php");
				$msgCounter = "1";
				sleep(1);
	}
		$msgCounter++;
	flush();
	}
	}
echo "$lang_177: $i";
if ($i != ""){
print "<br>$lang_178";
}
?>
  </font></p>
