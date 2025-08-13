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
  $what = @$_POST["what"];
  
  if($what == "")
  $what = @$_GET["what"];
  
  switch($what)
  {
    case "send":
      SendNewsletter();
	  break;
	case "done":
	  ShowStatus();
	  break;
	default:
	  GetSendingOptions();
  }
  
  function GetSendingOptions()
  {
	// This will allow the user to choose which newsletter to send
	doDbConnect();
	
	// Do we need to retrieve the details for a newsletter from the database?
	$nId = @$_GET["nId"];
	
	if($nId != -1 && $nId != "")
	{
		$result = mysql_query("select * from newsletters inner join templates on newsletters.nTemplateId = templates.pk_nId where newsletters.pk_nId = $nId");
		
		if($row = mysql_fetch_row($result))
		{
			$fromEmail = $row[10];
			$replyToEmail = $row[11];
			$subject = $row[2];
			$templateId = $row[4];
			
			// Workout the number of recipients for this newsletter
			$numRecips = mysql_result(mysql_query("select count(subscriptions.pk_sId) from subscriptions inner join subscribedUsers on subscribedUsers.pk_suId = subscriptions.sSubscriberId where subscribedUsers.suStatus = 'subscribed' and subscriptions.sNewsletterId = $templateId"), 0, 0);
		}
	}
	
	?>
	
		<script language="JavaScript">
		
			function GetNewsletterStats()
			{
				var nId = document.frmSend.nId.options[document.frmSend.nId.selectedIndex].value;
				document.location.href = 'sendnewsletter.php?nId='+nId;
			}
		
		</script>
		
		  <table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Send Newsletter</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  <?php if($nId == -1 || $nId == "") { ?>
					Sending a newsletter to your subscriber list is easy -- start by choosing which newsletter you would like to send
					from the list shown below.
				  <?php } else { ?>
				    The details for the selected newsletter are shown below. To send the newsletter to the subscriber list for this email
				    now, click on the "Send Newsletter" button.
				  <?php } ?>
  				</span>
			    </td></tr>
			    <tr><td background="images/yellowbg1.gif">
			    </td></tr>
			    <tr><td>
  			    </td></tr>
  			    <tr><td>
					<form name="frmSend" action="sendnewsletter.php" method="post">
					  <input type="hidden" name="what" value="send">
					  <table width="95%" align="center" border="0">
					    <tr>
						  <td valign="top">
						    <span class="BodyText">
							  <br>
						      Newsletter To Send:<br>
						      <select onChange="GetNewsletterStats()" name="nId" style="width: 196pt">
						      <?php
						      
								if($nId == "")
									echo "<option value='-1'>-- Select Newsletter --</option>";
						      
								// Gather a list of newsletters from the database
								$result = mysql_query("select pk_nId, nName from newsletters order by nName asc");
								
								while($row = mysql_fetch_row($result))
								{
									echo "<option ";
									
									if($nId == $row[0])
										echo " SELECTED ";
									
									echo " value='{$row[0]}'>{$row[1]}</option>";
								}
						      
						      ?>
						      </select>
							  <br><br>
							  <?php
							  
								if($nId == -1 || $nId == "")
								{
									echo "<i>[Please select a newsletter to see other details]</li>";
								}
								else
								{
									// Display the details of this mailing
									?>
										Newsletter Subject Line: [<a href="template.php?what=modify&tId=<?php echo $templateId; ?>">Edit</a>]<br>
										<input type="text" DISABLED size="40" value="<?php echo $subject; ?>">
										<br><br>
										Newsletter "From" Email Address: [<a href="template.php?what=modify&tId=<?php echo $templateId; ?>">Edit</a>]<br>
										<input type="text" DISABLED size="40" value="<?php echo $fromEmail; ?>">
										<br><br>
										Newsletter "Reply-To" Email Address: [<a href="template.php?what=modify&tId=<?php echo $templateId; ?>">Edit</a>]<br>
										<input type="text" DISABLED size="40" value="<?php echo $replyToEmail; ?>">
										<br><br>
										Number Of Subscribers:<br>
										<input type="text" DISABLED size="19" value="<?php echo number_format($numRecips); ?>">
										<br><br>
										<input type="button" value="« Cancel" onClick="ConfirmCancel('sendnewsletter.php')">
										<input type="submit" name="submit" value="Send Newsletter »">
									<?php
								}
							  ?>
							  
						  </td>
						</tr>
					  </table>
  			    </td></tr>
		  </table>
	<?php
  }
  
  function SendNewsletter()
  {
	// Get the details of this newsletter from the database and use JavaScript
	// and a hidden iFrame to send it
	
	doDbConnect();
	$nId = @$_POST["nId"];

	$result = mysql_query("select * from newsletters inner join templates on newsletters.nTemplateId = templates.pk_nId where newsletters.pk_nId = $nId");
		
	if($row = mysql_fetch_row($result))
	{
		$fromEmail = $row[10];
		$replyToEmail = $row[11];
		$subject = $row[2];
		$templateId = $row[4];
		$format = $row[14];
		
		// Workout the number of recipients for this newsletter
		$numRecips = mysql_result(mysql_query("select count(pk_sId) from subscriptions where sNewsletterId = $templateId"), 0, 0);
		
		if($numRecips == 0)
		{
			// There are no recipients for this newsletter
			?>
				<table width="98%" align="center" border="0">
				    <tr><td height="30">
				      <span class="MainHeading">Send Newsletter</span>
				    </td></tr>
				    <tr><td background="images/yellowbg.gif">
				      <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				  	<span class="Info">
				  	  <br>This newsletter has 0 subscribers and therefore it cannot be sent.
				  	  <br><br>
				  	  <a href="sendnewsletter.php">Continue >></a><br>&nbsp;
  				  	</span>
				      </td></tr>
				      <tr><td background="images/yellowbg1.gif">
				      </td></tr>
				      <tr><td>
  				      </td></tr>
				</table>
			<?php
		}
		else
		{
			// Everything is OK, start sending the emails
			?>
				<form name="frmSend">
				<input type="hidden" name="subject" value="<?php echo $subject; ?>">
				<input type="hidden" name="fromEmail" value="<?php echo $fromEmail; ?>">
				<input type="hidden" name="replyToEmail" value="<?php echo $replyToEmail; ?>">
				<input type="hidden" name="nId" value="<?php echo $nId; ?>">
				<input type="hidden" name="templateId" value="<?php echo $templateId; ?>">
				<input type="hidden" name="format" value="<?php echo $format; ?>">
				<input type="hidden" name="currentPos" value="0">
				<input type="hidden" name="numSent" value="0">
				<input type="hidden" name="numFailed" value="0">
				<input type="hidden" name="startTime" value="<?php echo time(); ?>">
				<input type="hidden" name="numSubs" value="<?php echo $numRecips; ?>">
				
				<table width="98%" align="center" border="0">
				    <tr><td height="30">
				      <span class="MainHeading">Send Newsletter</span>
				    </td></tr>
				    <tr><td background="images/yellowbg.gif">
				      <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				  	<span class="Info">
				  	  The selected newsletter is being sent...
  				  	</span>
				      </td></tr>
				      <tr><td background="images/yellowbg1.gif">
				      </td></tr>
				      <tr><td>
  				      </td></tr>
  				      <tr><td>
  						<table width="95%" border="0" cellspacing="0" cellpadding="0">
  							<tr>
  								<td>
									<iframe id="iNum" frameborder="no" scrolling="no" width="550" height="40"></iframe><br>
									<iframe id="iStatus" frameborder="1" scrolling="auto" width="550" height="350"></iframe><br>
									<img src="images/blank.gif" width="1" height="5"><br>
									<input type="button" value="« Cancel" onClick="ConfirmCancel('sendnewsletter.php')">
									<input type="submit" DISABLED name="submit" value="Working...">
									<iframe id="iSend" frameborder="no" scrolling="no" width="550" height="30"></iframe><br>
  								</td>
  							</tr>
  						</table>
  				      </td></tr>
				</table>
				</form>

				<script language="JavaScript">
				
					function SendIt()
					{
						currentPos = document.frmSend.currentPos.value;
						iSend.location.href = 'sendit.php?currentPos='+currentPos;
					}
					
					SendIt();
				
				</script>
				
			<?php
		}
	}
	else
	{
	?>
		<table width="98%" align="center" border="0">
		    <tr><td height="30">
		      <span class="MainHeading">Send Newsletter</span>
		    </td></tr>
		    <tr><td background="images/yellowbg.gif">
		      <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
		  	<span class="Info">
		  	  <br>An error occured while trying to send this newsletter to the subscriber list.
		  	  <br><br>
		  	  <a href="javascript:document.location.reload()">Try Again</a><br>&nbsp;
  		  	</span>
		      </td></tr>
		      <tr><td background="images/yellowbg1.gif">
		      </td></tr>
		      <tr><td>
  		      </td></tr>
	    </table>
	<?php
	}
  }
  
  function ShowStatus()
  {
	// Show the status of the mailout
	$numSent = @$_GET["success"];
	$numFail = @$_GET["fail"];
	$startTime = @$_GET["start"];
	$nId = @$_GET["nId"];
	
	doDbConnect();
	@mysql_query("update newsletters set nStatus = 'sent' where pk_nId = $nId") or die(mysql_error());
	?>
		<table width="98%" align="center" border="0">
		    <tr><td height="30">
		      <span class="MainHeading">Send Newsletter</span>
		    </td></tr>
		    <tr><td background="images/yellowbg.gif">
		      <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
		  	<span class="Info">
		  	  <br>The selected newsletter has been sent. The statistics for the mail out are shown in the bulleted list below.
		  	  Click "Continue" to return to the send newsletter page.
		  	  <ul>
		  		<li>
		  		<?php
		  		
		  			if($numSent == 1)
		  				echo "$numSent email was sent successfully";
		  			else
		  				echo "$numSent emails were sent successfully";
		  		?>
		  		</li>
		  		<li>
		  		<?php
		  		
		  			if($numFail == 1)
		  				echo "$numFail email failed";
		  			else
		  				echo "$numFail emails failed";
		  		?>
		  		</li>
		  		<li>
		  		<?php
		  		
		  			$currTime = time();
		  			$numMins = DateDiff("n", $startTime, $currTime);
		  			$numSecs = DateDiff("s", $startTime, $currTime);
		  			
		  			if($numMins > 0)
		  				echo "It took  $numMins minute(s) to send the newsletter";
		  			else
		  				echo "It took  $numSecs second(s) to send the newsletter";
		  		?>
		  		</li>
		  	  </ul>
		  	  <p style="margin-left:5">
		  	  <a href="newsletter.php">Continue >></a><br>&nbsp;
  		  	</span>
		      </td></tr>
		      <tr><td background="images/yellowbg1.gif">
		      </td></tr>
		      <tr><td>
  		      </td></tr>
	    </table>
	<?php
  }

?>  
		
<?php include("templates/bottom.php"); ?>
