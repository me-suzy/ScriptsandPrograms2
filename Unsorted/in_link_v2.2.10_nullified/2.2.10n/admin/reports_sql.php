<?php
//Read in config file
$thisfile = "reports_sql";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<link rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon5-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav4 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#reports"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">

  <tr> 
    <td class="tabletitle" bgcolor="#666666">
      <?php echo $la_title_reports ?>
      </td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
       <form name="form1" method="post" action="reports_sql.php<?php 
			if($sid && $session_get)
				echo "?sid=$sid";
				?>">   <tr bgcolor="#999999" valign="middle"> 
            <td colspan="3" class="textTitle">Results of your query: <?php echo($customquery); ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">


				  
		  <?php
		  $customquery = stripSlashes($customquery);
		  $rs = &$conn->Execute($customquery);
			
			if (!$rs || $rs->EOF):
				echo "<span class=\"error\"> SQL Error " . $conn->ErrorNo() . ": " . $conn->ErrorMsg() . "</span>";
			elseif ($rs->RecordCount() == 0 ):
				echo "Your query has been executed against the database";
			else:
			?>

			<table border=1 bordercolor="000000"  cellspacing="2" cellpadding="2" >
				<thead>
					<tr>
					<?php
						for ($i = 0; $i < $rs->FieldCount(); $i++)
						{
							$fld = $rs->FetchField($i);
							$fieldname = $fld->name;
							echo "<th bgcolor=\"#DEDEDE\" class=small>$fieldname</th>";							
						}
					?>

					</tr></thead>
					<tbody>
						<?php
							for ($i = 0; $i < $rs->RecordCount(); $i++)
							{
								echo "<tr class=small>";
								for ($j = 0; $j < $rs->FieldCount(); $j++)
								{
									echo "<td class=small>";
									
									if ($rs->fields[$j]==""){
										echo "&nbsp;";}
									else
										echo $rs->fields[$j];

									echo "</td>";
								}
								echo "</tr>";
								$rs->MoveNext();
							}
						?>

						</tbody></table>



			<?php
			endif
			?>

            </td>
          </tr>




     
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"> 
              <input type="submit" name="Submit342" onClick="history.back();" value="<?php echo $la_button_ok ?>" class="button">
			  <input type="submit" onClick="history.back();" name="Button" value="<?php echo $la_button_cancel; ?>" class="button">
            </td>
            <td class="text">&nbsp;</td>
          </tr>
        </form>
      </table>
      <br>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
