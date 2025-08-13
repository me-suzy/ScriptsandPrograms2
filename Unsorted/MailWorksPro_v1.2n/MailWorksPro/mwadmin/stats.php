<?php include("templates/top.php"); ?>
<?php include_once("includes/functions.php"); ?>

<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : MailWorks Professional                           //
//   Release Version      : 1.2                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
// Connect to the database and get a tally of subscribers, etc
	doDbConnect();
	
	$numSubs = mysql_result(mysql_query("select count(pk_suId) from subscribedUsers"), 0, 0);
	$totalNumSubs = number_format($numSubs, 0);

?>

	<table width="98%" align="center" border="0">
	  <tr><td height="30">
	    <span class="MainHeading">MailWorksPro Statistics</span>
	  </td></tr>
	  <tr><td background="images/yellowbg.gif">
	    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
		<span class="Info">
          The statistics shown below include how many subscribers are on your list, which
          newsletters they are subscribed to, how many subscribers joined today, in the last week and in the last month, etc.
		</span>
	  </td></tr>
	  <tr><td background="images/yellowbg1.gif">		  
	  </td></tr>
	  <tr><td>
	    <form name="frmTopic" action="topic.php" method="post">
		  <input type="hidden" name="what" value="doNew">
		  <table width="95%" align="center" border="0">
		    <tr>
			  <td valign="top">
			    <span class="BodyText">
				  <br>
			      <b>Total Number Of Subscribers:</b> <?php echo $totalNumSubs; ?>
				  <br><br>
				  <b>Subscriber Count By Newsletter:</b>
				  <br>
				  <?php
				  
						$result = mysql_query("select * from topics order by tName asc");
						
						while($row = mysql_fetch_row($result))
						{
							$nResult = mysql_query("select pk_nId, nName from templates where nTopicId = " . $row[0]);
							
							if(mysql_num_rows($nResult) > 0)
							{
								echo "<br>&nbsp;&nbsp;&nbsp;<b><i><font color='#183863'><img src='images/arrow.gif'> " . $row[1] . "</font></i></b><br><br>";
								
								while($nRow = mysql_fetch_row($nResult))
								{
									$numSubs = mysql_result(mysql_query("select count(pk_sId) from subscriptions where sNewsletterId = " . $nRow[0]), 0, 0);
								?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php echo $nRow[1] . ": " . number_format($numSubs, 0); ?><br>
								<?php
								}
							}
						}
					?>
					<br><b>Subscriber Count By Date Joined:</b>
					<br><br>
					<?php
					
						$year = date("Y");
						$month = date("m");
						$day = date("d");
						
						$yestdate = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
						$yestyear = date("Y", $yestdate);
						$yestmonth = date("m", $yestdate);
						$yestday = date("d", $yestdate);
						
						$weekdate = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
						
						$newToday = number_format(mysql_result(mysql_query("select count(suDateSubscribed) from subscribedUsers where left(suDateSubscribed, 4) = '$year' and mid(suDateSubscribed, 5, 2) = '$month' and mid(suDateSubscribed, 7, 2) = '$day'"), 0, 0));
						$newYest = number_format(mysql_result(mysql_query("select count(suDateSubscribed) from subscribedUsers where left(suDateSubscribed, 4) = '$yestyear' and mid(suDateSubscribed, 5, 2) = '$yestmonth' and mid(suDateSubscribed, 7, 2) = '$yestday'"), 0, 0));
						$newWeek = number_format(mysql_result(mysql_query("select count(suDateSubscribed) from subscribedUsers where suDateSubscribed > $weekdate"), 0, 0));
						$newMonth = number_format(mysql_result(mysql_query("select count(suDateSubscribed) from subscribedUsers where left(suDateSubscribed, 4) = '$year' and mid(suDateSubscribed, 5, 2) = '$month'"), 0, 0));
						$newYear = number_format(mysql_result(mysql_query("select count(suDateSubscribed) from subscribedUsers where left(suDateSubscribed, 4) = '$year'"), 0, 0));
					
						// Get subscriber counts by date joined
						echo "&nbsp;&nbsp;&nbsp;New Subscribers Today: $newToday <br>";
						echo "&nbsp;&nbsp;&nbsp;New Subscribers Yesterday: $newYest <br>";
						echo "&nbsp;&nbsp;&nbsp;New Subscribers This Week: $newWeek <br>";
						echo "&nbsp;&nbsp;&nbsp;New Subscribers This Month: $newMonth <br>";
						echo "&nbsp;&nbsp;&nbsp;New Subscribers This Year: $newYear <br>";
					?>
					<br><b>Subscriber Count By Email Domain:</b>
					<br><br>
					<?php
					
						// Get the number of users for each domain
						$com = mysql_result(mysql_query("select count(suEmail) from subscribedUsers where right(suEmail, 4) = '.com'"), 0, 0);
						$net = mysql_result(mysql_query("select count(suEmail) from subscribedUsers where right(suEmail, 4) = '.net'"), 0, 0);
						$org = mysql_result(mysql_query("select count(suEmail) from subscribedUsers where right(suEmail, 4) = '.org'"), 0, 0);
						$couk = mysql_result(mysql_query("select count(suEmail) from subscribedUsers where right(suEmail, 6) = '.co.uk'"), 0, 0);
						$comau = mysql_result(mysql_query("select count(suEmail) from subscribedUsers where right(suEmail, 7) = '.com.au'"), 0, 0);

						$numCOM = number_format($com);
						$numNET= number_format($net);
						$numORG = number_format($org);
						$numCOUK = number_format($couk);
						$numCOMAU = number_format($comau);
						$numMisc = number_format($numSubs - ($com + $net + $org + $couk + $comau));
						
						if($numMisc < 0)
							$numMisc = 0;
						
						// Show the subscriber counts via email domain
						echo "&nbsp;&nbsp;&nbsp;Subscribers From .COM Host (ex: john@somehost.com): $numCOM <br>";
						echo "&nbsp;&nbsp;&nbsp;Subscribers From .NET Host (ex: john@somehost.net): $numNET <br>";
						echo "&nbsp;&nbsp;&nbsp;Subscribers From .ORG Host (ex: john@somehost.org): $numORG <br>";
						echo "&nbsp;&nbsp;&nbsp;Subscribers From .CO.UK Host (ex: john@somehost.co.uk): $numCOUK <br>";
						echo "&nbsp;&nbsp;&nbsp;Subscribers From .COM.AU Host (ex: john@somehost.com.au): $numCOMAU <br>";
						echo "&nbsp;&nbsp;&nbsp;Subscribers From Other Hosts: $numMisc <br>";
					?>
				</span>
			  </td>
			</tr>
		  </table>
		</form>
	  </td></tr>
	</table>
  
<?php include("templates/bottom.php"); ?>
