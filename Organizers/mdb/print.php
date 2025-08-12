<?php
include "data.inc.php";

$Connect = mysql_connect($DB_host,$DB_user,$DB_pass) or die(mysql_error());
mysql_select_db($DB_name);

?>

<html>
<head>
<title>My DataBook Printing</title>
<style type="text/css">
<!--
p {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	text-decoration: none;
}
a {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.Title {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
	margin: 1px;
	padding: 1px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #000000;
}
td {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	text-decoration: none;
}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

</head>

<body link="#6666FF" vlink="#6666FF" alink="#6666FF">
<img src="images/header.gif" width="250" height="50"> 
<br>
<hr size="1"><a href="<?= $HTTP_REFERER; ?>">Go back</a>&nbsp;&nbsp;&nbsp;<a href="javascript:printWindow()">Print This Page</a>

<?php

if (isset($Sec)) {
	switch($Sec) {
		case notes:
			$Query = mysql_query("SELECT * FROM $Table_notes WHERE N_ID=\"$ID\"") or die(mysql_error());
			$A = mysql_fetch_array($Query);
			
				?>

				<p>Type: <em>Note</em><br>
				  Date Printed: <em><?= date("F jS, Y"); ?></em><br>
				  Date Wrote: <em><?= $A[date]; ?></em></p>
				<p><?= $A[note]; ?></p>

				<?php

			break;

		case contacts:
			$Get = mysql_query("SELECT * FROM $Table_contacts WHERE C_ID=\"$ID\"") or die(mysql_error());
			$array = mysql_fetch_object($Get);

			?>

			<br><br>
			<table width="655" border="0" align="left" cellpadding="1" cellspacing="0" bgcolor="#000000">
			  <tr>
				<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFFF">
					<tr> 
					  <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
						  <tr> 
							<td><font size="3"><strong>&nbsp;&nbsp;<?= $array->first_name; ?> <?= $array->last_name; ?></strong></font></td>
							<td width="250">&nbsp;</td>
						  </tr>
						</table>
						<br>
						<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0">
						  <tr> 
							<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
								<tr> 
								  <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
									  <tr> 
										<td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Personal Information </div></td>
									  </tr>
									  <tr> 
										<td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="1" cellpadding="1">
											<tr> 
											  <td width="125">First Name:</td>
											  <td width="175"><em><?= $array->first_name; ?></em></td>
											  <td width="125">Last Name:</td>
											  <td><em><?= $array->last_name; ?></em></td>
											</tr>
											<tr> 
											  <td>Birthday:</td>
											  <td><em><?= $array->birthday; ?></em></td>
											  <td>Title:</td>
											  <td><em><?= $array->title; ?></em></td>
											</tr>
											<tr> 
											  <td>Company Name:</td>
											  <td colspan="2"><em><?= $array->company; ?></em></td>
											  <td>&nbsp;</td>
											</tr>
										  </table></td>
									  </tr>
									</table></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr> 
							<td>&nbsp;</td>
						  </tr>
						  <tr> 
							<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
								<tr> 
								  <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
									  <tr> 
										<td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Contact Information </div></td>
									  </tr>
									  <tr> 
										<td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="1" cellpadding="1">
											<tr> 
											  <td width="125">Email Address:</td>
											  <td width="175"><em><?= $array->email; ?></em></td>
											  <td width="125">Home Phone:</td>
											  <td><em><?= $array->home_phone; ?></em></td>
											</tr>
											<tr> 
											  <td>ICQ:</td>
											  <td><em><?= $array->icq; ?></em></td>
											  <td>Work Phone:</td>
											  <td><em><?= $array->work_phone; ?></em></td>
											</tr>
											<tr> 
											  <td>MSN:</td>
											  <td><em><?= $array->msn; ?></em></td>
											  <td>Other Phone:</td>
											  <td><em><?= $array->other_phone; ?></em></td>
											</tr>
											<tr> 
											  <td>Yahoo:</td>
											  <td><em><?= $array->yahoo; ?></em></td>
											  <td>Cell Phone:</td>
											  <td><em><?= $array->cell_phone; ?></em></td>
											</tr>
											<tr> 
											  <td>AIM:</td>
											  <td><em><?= $array->aim; ?></em></td>
											  <td>Pager:</td>
											  <td><em><?= $array->pager; ?></em></td>
											</tr>
											<tr> 
											  <td>WebSite:</td>
											  <td colspan="3"><em><?= $array->website; ?></em></td>
											</tr>
										  </table></td>
									  </tr>
									</table></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr> 
							<td>&nbsp;</td>
						  </tr>
						  <tr> 
							<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
								<tr> 
								  <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
									  <tr> 
										<td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Physical Address </div></td>
									  </tr>
									  <tr> 
										<td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="0" cellpadding="1">
											<tr> 
											  <td width="125">Street:</td>
											  <td colspan="3"><em><?= $array->street; ?></em></td>
											</tr>
											<tr> 
											  <td width="125">City:</td>
											  <td width="175"><em><?= $array->city; ?></em></td>
											  <td width="125">State:</td>
											  <td><em><?= $array->state; ?></em></td>
											</tr>
											<tr> 
											  <td>Country:</td>
											  <td><em><?= $array->country; ?></em></td>
											  <td>Zip Code:</td>
											  <td><em><?= $array->zip; ?></em></td>
											</tr>
										  </table></td>
									  </tr>
									</table></td>
								</tr>
							  </table></td>
						  </tr>
						  <tr> 
							<td>&nbsp;</td>
						  </tr>
						  <tr>
							<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
								<tr> 
								  <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
									  <tr> 
										<td bgcolor="#6699FF" class="Title" style="padding='3px'"><div align="center">Notes</div></td>
									  </tr>
									  <tr> 
										<td bgcolor="#e3e3e3"><table width="100%" border="0" cellspacing="8" cellpadding="8">
											<tr>
											  <td bgcolor="#F0F0F0"><em><?= nl2br($array->notes); ?></em></td>
											</tr>
										  </table></td>
									  </tr>
									</table></td>
								</tr>
							  </table></td>
						  </tr>
						</table>
						<p>&nbsp;</p></td>
					</tr>
				  </table></td>
			  </tr>
			</table>
			<?php
			break;
	}
}



if (!isset($Sec)) {
	echo "oops!";
}

?>

</body>
</html>

<?php

mysql_close($Connect);
?>	