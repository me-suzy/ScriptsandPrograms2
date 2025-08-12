<?PHP 
$cgress = "UP";
?>
<html>
<head>
<title>Link Stats</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="4"><?PHP print $lang_412; ?>:</font></b></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"> 
  <?PHP 
$result = mysql_query ("SELECT * FROM Links
                         WHERE nl LIKE '$id'
						 AND link != 'open'
                       	ORDER BY link
						");
$numrows=mysql_num_rows($result);

if ($numrows == 0){
print "$lang_413";
die();
}
if ($row = mysql_fetch_array($result));
{
do {
$llid = $row["id"];
?>
  </font></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D5E2F0">
  <tr> 
    <td> <div align="center"></div>
      <table width="100%" border="0" cellspacing="1" cellpadding="6">
        <tr valign="top"> 
          <td width="50%" bgcolor="#FFFFFF"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?nl=<?PHP print $nl; ?>&page=lt/view2&id=<?PHP print $id; ?>&lid=<?PHP print $llid; ?>&link=<?PHP print $row["link"]; ?>"> 
              <?PHP 
  print "$lang_414 ";
  print $row["link"]; 
  $link = $row["link"]; ?>
              </a><br>
              <?PHP print $lang_580; ?> 
              <?PHP
  						  $result65 = mysql_query ("SELECT * FROM Messages
                         WHERE id LIKE '$id'
						 limit 1
                       ");
				$row65 = mysql_fetch_array($result65);

				$resulttrack = mysql_query ("SELECT * FROM Links
                         WHERE nl LIKE '$id'
						 AND link LIKE '$link'
						");
				$rowtrack = mysql_fetch_array($resulttrack);
				$trackid = $rowtrack["id"];
				$resulttrack2 = mysql_query ("SELECT id FROM 12all_LinksD
                         WHERE lid LIKE '$trackid'
						");
				$tracknum=mysql_num_rows($resulttrack2);
				if ($tracknum == ""){
				$tracknum = 0; 
				}
				print $tracknum;
				?>
              <?PHP print $lang_578; ?> <?PHP print $lang_149; ?>. ( 
              <?PHP 
				$ntotal = $row65["amt"];
				@$nvs = round(($tracknum / $ntotal),4);
				@$nvs = round(($nvs * 100),4);
				print $nvs; 
				?>
              % )<b><br>
              </b></font><font size="4" face="Arial, Helvetica, sans-serif"><b><font size="1"> 
              <?PHP if($nvs >= 5){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 10){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 15){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 20){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 25){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 30){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 35){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 40){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 45){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 50){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 55){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 60){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 65){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 70){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 75){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 80){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 85){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 90){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 95){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              <?PHP if($nvs >= 100){ ?>
              <img src="media/box1.gif" width="8" height="6"> 
              <?PHP } else { ?>
              <img src="media/box2.gif" width="8" height="6"> 
              <?PHP } ?>
              </font></b></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<p><font face="Arial, Helvetica, sans-serif" size="2"> 
  <?PHP
		
} while($row = mysql_fetch_array($result));
}


	?>
  </font></p>
</body>
</html>
