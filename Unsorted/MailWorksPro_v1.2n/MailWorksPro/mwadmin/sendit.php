<?php include_once("conf.php"); ?>
<?php include_once("includes/functions.php"); ?>
<html>
<head>
<script language="JavaScript">

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
// This script grabs the parameter values for the email from the
// parent frame and calls itself again with these parameters to send
// the newsletter. It returns a value to the parent frame indicating
// success or failure
	
	$send = @$_GET["send"] == "" ? false : true;
	
	if($send == false)
	{
		// Grab the values from the parent frame and pass them back
	?>
		
		var subject = escape(parent.document[0].all.subject.value);
		var fromEmail = escape(parent.document[0].all.fromEmail.value);
		var replyToEmail = escape(parent.document[0].all.replyToEmail.value);
		var nId = escape(parent.document[0].all.nId.value);
		var templateId = escape(parent.document[0].all.templateId.value);
		var currentPos = escape(parent.document[0].all.currentPos.value);
		var format = escape(parent.document[0].all.format.value);
		
		document.location.href = 'sendit.php?send=true&subject='+subject+'&fromEmail='+fromEmail+'&replyToEmail='+replyToEmail+'&nId='+nId+'&templateId='+templateId+'&currentPos='+currentPos+'&format='+format;

	<?php
	}
	else
	{
		// Send the email and pass a value back to the parent frame
		$subject = @$_GET["subject"];
		$fromEmail = @$_GET["fromEmail"];
		$replyToEmail = @$_GET["replyToEmail"];
		$nId = @$_GET["nId"];
		$templateId = @$_GET["templateId"];
		$format = @$_GET["format"];
		
		// Grab the actual body of the newsletter from the database
		doDbConnect();
		
		$result = mysql_query("select nContent from newsletters where pk_nId = $nId");
		
		if($row = mysql_fetch_row($result))
		{
			// Get the content of the newsletter
			$content = $row[0];
			
			// Send the newsletter
			$headers = "From: $fromEmail\r\n";
			
			if($replyToEmail != "")
				$headers .= "Reply-To: $replyToEmail\n";
				
			if($format == "html")
				$headers .= "Content-type: text/html\n";
				
			// Get the next email address to send it to
			$nResult = mysql_query("select * from subscribedUsers inner join subscriptions on subscribedUsers.pk_suId = subscriptions.sSubscriberId where subscriptions.sNewsletterId = $templateId limit $currentPos,1");
			
			if($nRow = mysql_fetch_row($nResult))
			{
				// Replace the personalized tags with their values
				$completeName = $nRow[1] . " " . $nRow[2];
				$firstName = $nRow[1];
				$lastName = $nRow[2];
				$email = $nRow[3];
				
				$content = str_replace("%%complete_name%%", $completeName, $content);
				$content = str_replace("%%first_name%%", $firstName, $content);
				$content = str_replace("%%last_name%%", $lastName, $content);
				$content = str_replace("%%email%%", $email, $content);
				
				// Send the email to the user
				if(@mail($email, $subject, $content, $headers))
				{
				?>
				
					var subject = escape(parent.document[0].all.subject.value);
					var fromEmail = escape(parent.document[0].all.fromEmail.value);
					var replyToEmail = escape(parent.document[0].all.replyToEmail.value);
					var nId = escape(parent.document[0].all.nId.value);
					var templateId = escape(parent.document[0].all.templateId.value);
					var currentPos = escape(parent.document[0].all.currentPos.value);
					var format = escape(parent.document[0].all.format.value);
										
					++parent.document.all.currentPos.value;
					++parent.document.frmSend.numSent.value;
					
					parent.iStatus.document.write('<img src=images/tick.gif> <font face=Verdana size=2 color=black>Sent to <?php echo $nRow[3]; ?></font><br>');
					document.location.href = 'sendit.php?send=true&subject='+subject+'&fromEmail='+fromEmail+'&replyToEmail='+replyToEmail+'&nId='+nId+'&templateId='+templateId+'&currentPos='+currentPos+'&format='+format;
					
					// Do we need to restart the list?
					if(currentPos % 50 == 0)
						parent.iStatus.document.body.innerHTML = '';

				<?php
				}
				else
				{
					// Sending to this subscriber failed
					?>
						var subject = escape(parent.document[0].all.subject.value);
						var fromEmail = escape(parent.document[0].all.fromEmail.value);
						var replyToEmail = escape(parent.document[0].all.replyToEmail.value);
						var nId = escape(parent.document[0].all.nId.value);
						var templateId = escape(parent.document[0].all.templateId.value);
						var currentPos = escape(parent.document[0].all.currentPos.value);
						var format = escape(parent.document[0].all.format.value);

						++parent.document.all.currentPos.value;
						++parent.document.frmSend.numFailed.value;
						
						parent.iStatus.document.write('<img src=images/cross.gif> <font face=Verdana size=2 color=red>Failed to <?php echo $nRow[3]; ?></font><br>');
						document.location.href = 'sendit.php?send=true&subject='+subject+'&fromEmail='+fromEmail+'&replyToEmail='+replyToEmail+'&nId='+nId+'&templateId='+templateId+'&currentPos='+currentPos+'&format='+format;
					<?php
				}
				
				// Scroll to the bottom of the frame
				?>
					parent.iStatus.scrollTo(0, 100000);
					parent.iNum.document.body.innerHTML = '';
					parent.iNum.document.write('<body leftmargin=0><img src=images/arrow.gif> <font face=verdana size=2 color=black><b>Sending copy ' + currentPos + ' of '+parent.document[0].all.numSubs.value+'</b></font></body>');
				<?php
			}
			else
			{
				// All newsletters have been sent - there are no more subscribers on the list
				?>
					var numSent = parseInt(escape(parent.document[0].all.numSent.value));
					var numFailed = escape(parent.document[0].all.numFailed.value);
					var startTime = escape(parent.document[0].all.startTime.value);
					var nId = escape(parent.document[0].all.nId.value);
					
					parent.location.href = 'sendnewsletter.php?what=done&nId='+nId+'&success='+(--numSent)+'&fail='+numFailed+'&start='+startTime;
				
				<?php
			}
		}
		else
		{
			// Couldn't get the newsletter content
			
		}
	}
?>
</script>
</head>
</html>