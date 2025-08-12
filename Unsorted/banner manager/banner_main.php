<?php
	session_start();
	if($user_name == '' && $id == '')
	{
		header('Location: relogin.php');
		exit;
	}

	
	include_once('./include/connection.php');
	$expired = 0;
	$started = 0;
	if(!isset($list))
	{	
		if(isset($listall))
		{
			$SQL = "SELECT id, name, clicks, views, start_date,end_date,  (now() > end_date) as expired, (now() > start_date) as started FROM banner_campaign WHERE group_id = $group";
		}
		else
		{
			$SQL = "SELECT id, name, clicks, views, start_date,end_date, 0 as expired, 1 as started FROM banner_campaign WHERE (now() BETWEEN start_date AND end_date) AND group_id = $group";
		}
		if(!$result = mysql_query($SQL,$con))
		{
			header('Location: down.php');
		}
	}
	else
	{
		$active_date = $from_year.'-'.$from_month.'-'.$from_day;
		$SQL = "SELECT id, name, clicks, views, start_date,end_date,(now() > end_date) as expired, (now() > start_date) as started FROM banner_campaign WHERE (start_date <= '$active_date' AND end_date >= '$active_date') AND group_id = $group";
		if(!$result = mysql_query($SQL,$con))
		{
			header('Location: down.htm');
		}
	}	
	while($row = mysql_fetch_array($result))
	{
		$start_date = explode(" ",$row['start_date']);
		$end_date = explode(" ",$row['end_date']);
		$SQL = "SELECT clicks,views FROM banner_stat WHERE campaign_id = ".$row['id'];
		$result2 = mysql_query($SQL,$con);
		$count = mysql_affected_rows();
		$clicks = 0;
		$views = 0;
		$expired = $row['expired'];
		$started = $row['started'];
		while($banner = mysql_fetch_array($result2))
		{
			$clicks = $clicks + $banner[0];
			$views = $views + $banner[1];
		}
		$clickthrough = $clicks != 0  ? round(($clicks / $views)*100,2) : "N/A";
		$clickratio = $clicks.'/'.$row['clicks'];
		$viewratio = $views.'/'.$row['views'];

		if((($views >= $row['views']) && ($row['views'] != 0)) || (($clicks >= $row['clicks']) && ($row['clicks'] != 0)) || $expired)
			$bgcolor = "bgcolor='pink'";
		else
		{
			if($started)
				$bgcolor = "bgcolor='#99FFCC'";
			else
				$bgcolor = '';
		}

		if($end_date[0] == '2999-01-01')
			$myedate = "Not Set";
		else
			$myedate = $end_date[0];
		$html .= "<tr $bgcolor><td><a href='add_banner.php?cid=".$row['id']."'>".$row['name']."</a></td><td align='center'>$count</td><td align='center'>".$clickthrough."</td><td align='right'>".$clickratio."</td><td align='right'>".$viewratio."</td><td align='right'>".$start_date[0]."</td><td align='right'>".$myedate."</td>";					
		
	}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Banner Manager</title>
<meta name="Keywords" content="">
<meta name="Description" content="">
<style type="text/css">
		.copyright {font: 8pt arial}
		.tips {font: italic 8pt arial}
		.copyrightsite {font: bold 8pt verdana}
		.header {font: bold 10pt verdana}
		.label {font: 9pt arial}
		.error {font: italic 8pt arial; color: red}
		body {font: 8pt arial}
		td {font: 8pt arial}
</style>
</head>
<body bgcolor="white">
<center>
<br>
<font class="header">Banner Manager Admin</font><br><hr width="90%">
<table width="80%" cellpadding="1" cellspacing="0" border="0">
<tr><td><?php echo Date("d F Y")?></td>
	<td align="right" class="copyrightsite"><a href="index.php?action=logout">Logout</a> <?php echo $user_name?></td>
</tr>
</table>
<br>
<table width="90%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">
	<tr>
		<td>
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#ffae00">
		<tr><td align="center">
		<table width="90%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#ffae00">
		<tr align="center">
			<td class="copyrightsite">List Campaigns<br></td>
		</tr>
		<tr align="center">
			<td>
			<form action="banner_main.php" method="post">
			Campaign On : 
			<select name="from_day" class="copyright">
			<?php
				for($i=0;$i<32;$i++)
				{
					if(Date("d") == $i)
						echo "<option value='$i' selected>$i";
					else
						echo "<option value='$i'>$i";
				}
			echo "</select><select name='from_month' class=\"copyright\">";
				$month_array = array("January","February","March","April","May","June","July","August","September","October","November","December");
				for($i=0;$i<12;$i++)
				{
					if(Date("m") == $i)
						echo "<option value='".($i)."' selected>".$month_array[$i-1];
						
					else
						echo "<option value='".($i)."'>".$month_array[$i-1];
				}
			echo "</select><select name='from_year' class=\"copyright\">";
				for($i=2001;$i<2010;$i++)
				{
					if(Date("Y") == $i)
						echo "<option value='$i' selected>$i";
					else
						echo "<option value='$i'>$i";
				}
			echo "</select>&nbsp;<input type='submit' value='List' name='list' class=\"copyright\">";
			?>
			&nbsp;<input type="submit" name="listall" value="List All" class="copyright"> 
			
			</form>
			</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
	</tr>
</table>
<br>
<table width="90%" cellpadding="1" cellspacing="0" border="0">
	<tr>
		<td>
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0">
		<tr><td align="center">
		<table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0">
		<tr align="center">
			<td class="copyrightsite"><?php if(!isset($list)){ if(isset($listall)){ print("All Campaign(s)"); } else {print('Current Campaign(s)');} }else{ print("Campaign(s) on $from_day ".$month_array[$from_month-1]." $from_year");} ?><br></td>
		</tr>
		<tr><td>
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
		<tr align="center" bgcolor="#eeeee0"><td class="copyrightsite">Name</b></td><td class="copyrightsite">Total Banner</b></td><td class="copyrightsite">% Click Through</b></td><td class="copyrightsite">Clicks</b></td><td class="copyrightsite">Impressions</b></td><td class="copyrightsite">Start Date</b></td><td class="copyrightsite">End Date</b></td></tr>
			<?php 
				if($html != '')
					echo $html;
				else
					echo "<tr><td colspan='7' align='center'>-- No Campaign Available --</td></tr>";
			?>
		</table>
		</td></tr>
		<tr><td>&nbsp;</td></tr>
		</table>
		</td></tr>
		</table>
		</td>
	</tr>
</table>
<form action="campaign.php" action="get">
<input type="submit" value="Create New Campaign" class="copyright">
</form>

<table width='95%' cellpadding='0' cellspacing='0' border='0'>
<tr><td>
<br><br>
NOTES:<br>

** Name - Name of the campaign<br>
** Total Banner - Total banner in the campaign<br>
** % Click Through - Percentage of total clicks over total impression<br>
** Clicks - Total clicks over targeted total clicks<br>
** Impressions - Total impression over total target impression<br>
** Start Date - The starting date of the campaign<br>
** End Date - The ending date of the canpaign<br>

</td>
<td>
<table width='100' cellpadding='0' cellspacing='2'>
<tr><td colspan='2'><b>Legend:</b></td></tr>
<tr><td bgcolor='pink' width='15'>&nbsp;</td><td>Expired</td></tr>
<tr><td bgcolor='#99FFCC' width='15'>&nbsp;</td><td>Running</td></tr>
<tr><td bgcolor="#eeeeee" width='15'>&nbsp;</td><td>Not Active</td></tr>
</table>
</td>

</tr></table>
</center>
<br><br>
<?php include("./include/footer.php"); ?>

</body>
</html>
