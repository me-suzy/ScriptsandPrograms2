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
    case "import":
      ShowNewForm();
	  break;
	case "doImport":
	  ProcessImports();
	  break;
	case "delete":
	  DeleteSubscribers();
	  break;
	 case "export":
		ShowExportForm();
		break;
	 case "doExport":
		ProcessExport();
		break;
	 case "deleteAll":
		DeleteAll();
		break;
	default:
	  ShowSubscriberList();
  }
  
  function ShowNewForm()
  {
  ?>  
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Import Subscribers</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
              <br>
              Please complete the form below to add one/more subscribers to your mailing list. Everyone entered in the list below
              will be able to visit your newsletter subscription page to manage their preferences, change their password, etc.
              <br><br>
              <b>Note:</b> If you have more than 1,000 subscribers to import then you *must* upload them as a text file and not
              paste them into the "New Email Addresses" box.
			<br><br>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">		  
		  </td></tr>
		  <tr><td>
		    <form onSubmit="document.frmSubscriber.submit.disabled = true; document.frmSubscriber.submit.value = 'Working...'" enctype="multipart/form-data" name="frmSubscriber" action="subscriber.php" method="post">
			  <input type="hidden" name="what" value="doImport">
			  <table width="95%" align="center" border="0">
			    <tr>
				  <td valign="top">
				    <span class="BodyText">
					  <br>
					  New Email Addresses:<br>
					  <textarea rows="10" cols="60" name="emails"></textarea>
					  <br><br>
					  <b><i>OR</i></b> Upload Text File Containing Subscribers:<br>
					  <input type="file" name="eFile" style="width:260">
					  <br><br>
				      List Delimiter:<br>
					  <select name="delim" size="5" style="width:260">
						<option value="_">None</option>
						<option value="nl">New Line</option>
						<option value=",">Comma</option>
						<option value=";">Semi-Colon</option>
						<option value="|">Pipe</option>
					  </select>
					  <br><br>
					  Subscribe These Users To The Following Newsletters:<br>
					  <?php
					  
						// Grab a list of topics and their newsletters from the database and list them
						doDbConnect();
						$result = mysql_query("select * from topics order by tName asc");
						
						while($row = mysql_fetch_row($result))
						{
							$nResult = mysql_query("select pk_nId, nName from templates where nTopicId = " . $row[0]);
							
							if(mysql_num_rows($nResult) > 0)
							{
								echo "<br><b>&nbsp;&nbsp;&nbsp;<img src='images/arrow.gif'> " . $row[1] . "</b><br><br>";
								
								while($nRow = mysql_fetch_row($nResult))
								{
								?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" CHECKED name="templateId[]" value="<?php echo $nRow[0]; ?>">
									<?php echo $nRow[1]; ?><br>
								<?php
								}
							}
						}
					  ?>
					  
					  <br><br>
					  <input type="button" value="« Cancel" onClick="ConfirmCancel('subscriber.php')">
					  <input id="submit" type="submit" name="submit" value="Import Subscribers »">
					</span>
				  </td>
				</tr>
			  </table>
			</form>
		  </td></tr>
		</table>
	<?php
  }
  
  function ProcessImports()
  {
	// Import the email addresses into the subsribedUsers and subscriptions tables
	
	set_time_limit(0); // No script execution time limit
	doDbConnect();
	
	$emails = @$_POST["emails"];
	$delim = @$_POST["delim"];
	$arrTemplateIds = @$_POST["templateId"];
	$numValid = 0;
	$numInvalid = 0;
	$numDup = 0;
	$eFileName = @$_FILES["eFile"]["tmp_name"];
	$hasEmails = false;
	
	// Should we read-in the file?
	if($eFileName != "")
	{
		if($fp = @fopen($eFileName, "rb")) // "b" is for binary on Windows servers
		{
			while(!@feof($fp))
				$emails .= @fgets($fp, 1024);
		
			@fclose($fp);
			$hasEmails = true;
		}
		else
		{
			// Couldn't open email file -- die
			?>
				<table width="98%" align="center" border="0">
				<tr><td height="30">
				  <span class="MainHeading">Import Subscribers</span>
				</td></tr>
				<tr><td background="images/yellowbg.gif">
				  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  An error occured while trying to load the uploaded file. Check your php.ini file to make sure that
				  you can upload files via your web browser.
				  <p style="margin-left:5pt">
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
	else
	{
		if($emails != "")
			$hasEmails = true;
	}
	
	// Split the list of subscribers based on the delimiter
	if($delim == "nl")
		$delim = "\r\n";

	if($delim != "_")
		$arrEmails = @explode($delim, $emails);
	else
		$arrEmails = array($emails);
	
	if($hasEmails == true && $delim != "")
	{
		for($i = 0; $i < sizeof($arrEmails); $i++)
		{
			// Is this a valid email address?
			if(is_numeric(strpos($arrEmails[$i], "@")) && is_numeric(strpos($arrEmails[$i], ".")))
			{
				// Does this user already exist in the database?
				$userExists = (mysql_result(mysql_query("select count(pk_suId) from subscribedUsers where suEmail = '{$arrEmails[$i]}'"), 0, 0) > 0 ? true : false);
				
				// Add the user to the subscribedUsers
				if($userExists == false)
				{
					if(mysql_query("insert into subscribedUsers(suFName, suLName, suEmail, suPassword, suStatus) values('', '', '{$arrEmails[$i]}', password('" . GenerateRandomPassword() . "'), 'subscribed')"))
					{
						// Add the user to his selected newsletter(s)
						$listErr = false;
						$userId = mysql_insert_id();
						
						for($j = 0; $j < sizeof($arrTemplateIds); $j++)
						{
							if(!mysql_query("insert into subscriptions values(0, " . $arrTemplateIds[$j] . ", " . $userId . ")"))
								$listErr = true;
						}
						
						if($listErr == false)
							++$numValid;
						else
							++$numInvalid;
					}
					else
					{
						++$numInvalid;
					}
				}
				else
				{
					// Duplicate
					++$numDup;
				}
			}
			else
			{
				if($arrEmails[$i] != "")
					++$numInvalid;
			}
		}
		?>
			<table width="98%" align="center" border="0">
			<tr><td height="30">
			  <span class="MainHeading">Import Subscribers</span>
			</td></tr>
			<tr><td background="images/yellowbg.gif">
			  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
			  The subscription process has been completed. Here are the stats:
			  <ul>
				<li>
				<?php

					if($numValid == 1)
						echo "1 user was subscribed successfully";
					else
						echo "$numValid users were subscribed successfully";

				?>
				</li>
				<li>
				<?php

					if($numInvalid == 1)
						echo "1 user was invalid (bad email addresses)";
					else
						echo "$numInvalid users were invalid (bad email addresses)";

				?>
				</li>
				<?php

					if($numDup == 1)
						echo "<li>1 user already existed in the database and wasn't added</li>";
					else if($numDup > 1)
						echo "<li>$numDup users already existed in the database and weren't added</li>";

				?>
			  </ul>
			  <ul><?php echo $err; ?></ul>
			  <p style="margin-left:5pt">
			  <a href="sendnewsletter.php">Send Newsletter</a> | <a href="subscriber.php">Continue >></a><br>&nbsp;
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
		if($hasEmails == false)
			$err .= "<li>You forgot to type-in or upload a file containing email addresses</li>";
			
		if($delim == "")
			$err .= "<li>You forgot to select the delimeter for your subscriber list</li>";
	?>
		<table width="98%" align="center" border="0">
		<tr><td height="30">
		  <span class="MainHeading">Import Subscribers</span>
		</td></tr>
		<tr><td background="images/yellowbg.gif">
		  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
		<span class="Info">
		<br>
			The form that you've just submitted is incomplete. Please review the errors below and then go back and
			correct them:
			<ul><?php echo $err; ?></ul>
		  <p style="margin-left:5pt">
		  <a href="javascript:history.go(-1)"><< Go Back</a><br>&nbsp;
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
  
  function ShowSubscriberList()
  {
	// Show a list of templates currently in the database
	doDbConnect();
	
	// Workout recordset paging
	$page = @$_GET["page"];
	$start = @$_GET["start"];
	$find = @$_GET["find"];
	$recsPerPage = 50;

	if(!is_numeric($page))
		$page = 1;
		
	$start = ($page * $recsPerPage) - $recsPerPage;
		
	// Do we use a normal query or do we have to perform a full-text search?
	if($find == "")
	{
		$countQuery = "select count(pk_suId) from subscribedUsers";
		$subQuery = "select * from subscribedUsers order by suEmail asc limit $start, 50";
	}
	else
	{
		$countQuery = "select count(pk_suId) from subscribedUsers where match(suFName, suLName, suEmail) against('$find')";
		$subQuery = "select * from subscribedUsers where match(suFName, suLName, suEmail) against('$find') order by suEmail asc limit $start, 50";
	}
	
	$numRows = mysql_result(mysql_query($countQuery), 0, 0);
	$result = mysql_query($subQuery);

	if($numRows > 50)
	{
		$nav = "Pages: ";

		if($page > 1)
			$nav = "[<a href='subscriber.php?find=$find&'>««</a>] ";

		if($page > 1)
		  $nav .= "<a href='subscriber.php?find=$find&page=" . ($page-1) . "'><u>« Prev</u></a> | ";

		$min = 1;
		
		if($page - RECS_TO_SHOW > $min)
			$min = $page - RECS_TO_SHOW;
			
		$max = ceil($numRows / $recsPerPage);
		
		if($page + RECS_TO_SHOW < $max)
			$max = $page + RECS_TO_SHOW;
			
		for($i = $min; $i <= $max; $i++)
		  if($i == $page)
		    $nav .= "<b>$i</b> | ";
		  else
		    $nav .= "<a href='subscriber.php?find=$find&page=$i'>$i</a> | ";

		if(($start+$recsPerPage) < $numRows && $numRows > 0)
		  $nav .= "<a href='subscriber.php?find=$find&page=" . ($page+1) . "'><u>Next »</u></a>";

		if(substr(strrev($nav), 0, 2) == " |")
		  $nav = substr($nav, 0, strlen($nav)-2);

		if($page < ceil($numRows / $recsPerPage))
			$nav .= " [<a href='subscriber.php?find=$find&page=" . ceil($numRows / $recsPerPage) . "'>»»</a>]";
						  
		$nav .= "<br><br><i>$numRows record(s) found</i><br>&nbsp;";
	}
	else
	{
		$nav = "";
	}
	
	?>
		<script language="JavaScript">
		
			var chkState = 0;
		
			function CheckConfirmDelete()			
			{
				if(confirm('WARNING: You are about to permanently delete the selected subscribers. Click OK to continue.'))
					return true;
				else
					return false;
			}
			
			function ToggleDel()
			{
				// Loop through the form and check any checkboxes
				var frm = document.frmSubscriber.elements;
				
				if(chkState == 0)
				{
					// Tick all boxes
					for(i = 0; i < frm.length; i++)
						if(frm.elements[i].type == 'checkbox')
							frm.elements[i].checked = true;
							
					chkState = 1;
				}
				else
				{
					// Tick all boxes
					for(i = 0; i < frm.length; i++)
						if(frm.elements[i].type == 'checkbox')
							frm.elements[i].checked = false;
							
					chkState = 0;
				}
			}
			
		</script>
			
		<form name="frmSubscriber" action="subscriber.php?find=<?php echo $find; ?>" method="post">
		<input type="hidden" name="what" value="delete">

		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Newsletters</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
			  A list of existing subscribers is shown below. To import subscribers, click the "Import Subscribers" link.
			  To remove one/more subscribers, click the checkbox for that subscriber and then click the "Delete Selected" button.
  			</span>
		    </td></tr>
		    <tr><td background="images/yellowbg1.gif">
		    </td></tr>
		    <tr><td>
  		    </td></tr>
		  </table>
		  <table width="98%" align="center" border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td width="100%" colspan="4" height="70" class="Info">
					<iframe frameborder="no" scrolling="no" width="550" height="30" src="searchemails.php?find=<?php echo $find; ?>"></iframe><br>
					<a href="subscriber.php?what=import"> Import Subscribers »</a>
				</td>
			</tr>
			<?php if($numRows > 50) { ?>
				<tr>
					<td width="100%" colspan="4" height="40" class="Info">
						<?php echo $nav; ?>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td bgcolor="#183863" width="10%" height="20" class="MenuHeading">
					<input type="checkbox" name="masterDelete" onClick="ToggleDel()">
				</td>
				<td bgcolor="#183863" width="35%" height="20" class="MenuHeading">
					Subscriber Email
				</td>
				<td bgcolor="#183863" width="35%" height="20" class="MenuHeading">
					Subscriber Name
				</td>
				<td bgcolor="#183863" width="20%" height="20" class="MenuHeading">
					Status
				</td>
			</tr>
			<?php
				
				if(mysql_num_rows($result) == 0)
				{
				?>
					<tr>
						<td colspan="4" width="100%" height="25" class="Info">
						<?php
							
							if($find == "")
								echo "There are no subscribers in the database.";
							else
								echo "No results were found for search term '$find'.";
						?>
						</td>
					</tr>
				<?php
				}
					
				while($row = mysql_fetch_row($result))
				{
				?>
					<tr>
						<td width="10%" height="20" class="TableCell">
							<input type="checkbox" name="sId[]" value="<?php echo $row[0]; ?>">
						</td>
						<td width="35%" height="20" class="TableCell">
							<a href="mailto:<?php echo $row[3]; ?>"><?php echo substr($row[3], 0, 30); ?></a>
						</td>
						<td width="35%" height="20" class="TableCell">
							<?php echo $row[1] . " " . $row[2]; ?>
						</td>
						<td width="20%" height="20" class="TableCell">
							<?php echo $row[5]; ?>
						</td>
					</tr>
				<?php
				}
			?>
				<tr>
					<td width="100%" colspan="4">
						<input onClick="return CheckConfirmDelete()"  type="submit" value="Delete Selected »">
					</td>
				</tr>
		  </table>
		</form>
	<?php
  }
  
  function DeleteSubscribers()
  {
		// This function will remove the selected newsletters from the database
		
		doDbConnect();
		
		$sId = @$_POST["sId"];
		$query = "";
		$result = "";

		?>

		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Delete Subscriber(s)</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">

		<?php
		
		if(is_array($sId) == true)
		{
			$query1 = "delete from subscribedUsers where pk_suId = " . implode(" OR pk_suId = ", $sId);
			$query2 = "delete from subscriptions where sSubscriberId = " . implode(" OR sSubscriberId = ", $sId);
			
			if(@mysql_query($query1))
				$result1 = true;
			else
				$result1 = false;
			
			if(@mysql_query($query2))
				$result2 = true;
			else
				$result2 = false;
			
			if($result1 == true && $result2 == true)
			{
				// Query executed OK
				$status = "<br>You have successfully deleted one/more subscribers from the database.<br><br>";
				$status .= "<a href='subscriber.php'>Continue >></a>";
			}
			else
			{
				// Delete querie(s) failed
				$status = "<br>An error occured while trying to delete the selected subscriber(s).<br><br>";
				$status .= "<a href='javascript:document.location.reload()'>Try Again</a>";
			}
		}
		else
		{
			// No subscribers have been chosen
			$status = "<br>You didn't select one/more subscribers to delete.<br><br>";
			$status .= "<a href='javascript:history.go(-1)'>Try Again</a>";
		}
		
		echo $status;
	  ?>
  		  </span>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">
		  </td></tr>
		  <tr><td>
  		  </td></tr>
		</table>
	<?php
  }
  
  function ShowExportForm()
  {
	// Show the form to give users the option to export the list of email addresses
	doDbConnect();
	
	$numSubscribers = mysql_result(mysql_query("select count(pk_suId) from subscribedUsers"), 0, 0);
	
	?>
		<script language="JavaScript">
		
			function ShowSample(sampleId)
			{
				var theDiv = document.all.sample;
				var incFName = document.all.incFName.checked;
				var incLName = document.all.incLName.checked;
				var incEmail = document.all.incEmail.checked;
				var incStatus = document.all.incStatus.checked;
				var incDate = document.all.incDate.checked;
				var sampleBox = "<font color='red'><b><br>";
				
				switch(sampleId)
				{
					case 0:
					{
						if(incFName)
							sampleBox = sampleBox + "John|";
							
						if(incLName)
							sampleBox = sampleBox + "Doe|";
							
						if(incEmail)
							sampleBox = sampleBox + "jdoe@somesite.com|";
							
						if(incStatus)
							sampleBox = sampleBox + "subscribed|";
							
						if(incDate)
							sampleBox = sampleBox + "20020831141649";
						
						// Do we need to remove a trailing pipe?
						if(sampleBox.substring(sampleBox.length-1, sampleBox.length) == "|")
							sampleBox = sampleBox.substring(0, sampleBox.length-1);
						
						theDiv.innerHTML = sampleBox + '</b></font>';
						break;
					}
					case 1:
					{
						if(incFName)
							sampleBox = sampleBox + "John,";
							
						if(incLName)
							sampleBox = sampleBox + "Doe,";
							
						if(incEmail)
							sampleBox = sampleBox + "jdoe@somesite.com,";
							
						if(incStatus)
							sampleBox = sampleBox + "subscribed,";
							
						if(incDate)
							sampleBox = sampleBox + "20020831141649";
						
						// Do we need to remove a trailing comma?
						if(sampleBox.substring(sampleBox.length-1, sampleBox.length) == ",")
							sampleBox = sampleBox.substring(0, sampleBox.length-1);
						
						theDiv.innerHTML = sampleBox + '</b></font>';
						break;
					}
					case 2:
					{
						sampleBox = sampleBox + "&lt;subscriber&gt;<br>";
						
						if(incFName)
							sampleBox = sampleBox + "&nbsp;&nbsp;&lt;suFName&gt;John&lt;/suFName&gt;<br>";
							
						if(incLName)
							sampleBox = sampleBox + "&nbsp;&nbsp;&lt;suLName&gt;Doe&lt;/suLName&gt;<br>";
							
						if(incEmail)
							sampleBox = sampleBox + "&nbsp;&nbsp;&lt;suEmail&gt;jdoe@somesite.com&lt;/suEmail&gt;<br>";
							
						if(incStatus)
							sampleBox = sampleBox + "&nbsp;&nbsp;&lt;suStatus&gt;subscribed&lt;/suStatus&gt;<br>";
							
						if(incDate)
							sampleBox = sampleBox + "&nbsp;&nbsp;&lt;suDateSubscribed&gt;20020831141649&lt;/suDateSubscribed&gt;<br>";
						
						sampleBox = sampleBox + "&lt;/subscriber&gt;<br>";
						theDiv.innerHTML = sampleBox + '</b></font>';
						break;
					}
				}
			}
		
		</script>
		
		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Export Subscribers</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
			<?php if($numSubscribers > 0) { ?>
			  <br>You can export your newsletter subscriber list to plain text, CSV or XML format. Complete the form
			  below and click on the "Export Subscribers" button to start the export process.
			  <br><br>
			  <b>Note:</b> Depending on how many subscribers you have, it could take anywhere from 5 seconds to 5 minutes to download your subscriber list.
			  <br><br>
  			</span>
		    </td></tr>
		    <tr><td background="images/yellowbg1.gif">
		    </td></tr>
		    <tr><td>
  		    </td></tr>
  		    <tr><td>
				<form name="frmExport" action="subscriber.php" method="post">
				  <input type="hidden" name="what" value="doExport">
				  <table width="95%" align="center" border="0">
				    <tr>
					  <td valign="top">
					    <span class="BodyText">
						  <br>
						  Export Format:<br>
						  <select onChange="ShowSample(this.selectedIndex)" name="format" size="3" style="width:260">
							<option value="1">Plain Text (Delimeted By New Line)</option>
							<option value="2">CSV For Excel</option>
							<option value="3">XML</option>
						  </select>
						  <br><br>
						  Preview Of Output:<br>
						  <div id="sample">
							<i>[Please select a format first]</i>
						  </div>
						  <br>
						  Which Fields Should Be Exported?<br><br>
						  <input onClick="if(format.selectedIndex > -1) { ShowSample(format.selectedIndex) }" type="checkbox" name="incFName" CHECKED> First Name<br>
						  <input onClick="if(format.selectedIndex > -1) { ShowSample(format.selectedIndex) }" type="checkbox" name="incLName" CHECKED> Last Name<br>
						  <input onClick="if(format.selectedIndex > -1) { ShowSample(format.selectedIndex) }" type="checkbox" name="incEmail" CHECKED> Email Address<br>
						  <input onClick="if(format.selectedIndex > -1) { ShowSample(format.selectedIndex) }" type="checkbox" name="incStatus" CHECKED> Subscriber Status<br>
						  <input onClick="if(format.selectedIndex > -1) { ShowSample(format.selectedIndex) }" type="checkbox" name="incDate" CHECKED> Date Joined<br>
						  <br>
						  Only Export Subscribers Who Match This Search Term:<br>
						  <input type="text" name="find" value="[None - Export All Subscribers]" onClick="if(this.value == '[None - Export All Subscribers]') { this.value = ''; }" size="40">
						  <br><br>
						  How Many Subscribers Should Be Exported?:
						  <br><br>
						  Export <input type="text" name="number" size="4" value="<?php echo $numSubscribers; ?>"> subscribers starting from record # <input type="text" name="start" size="4" value="0">
						  <br><br>
						  <input type="button" value="« Cancel" onClick="ConfirmCancel('subscriber.php')">
						  <input id="submit" type="submit" name="submit" value="Export Subscribers »">
						</span>
					  </td>
					</tr>
				  </table>
				</form>
			</td></tr>
			<?php } else { ?>
			  <br>You currently have 0 subscribers in your list. Please build up a list first.
			  <br><br>
			  <a href="subscriber.php">Continue >></a>
			  <br><br>
  			</span>
		    </td></tr>
		    <tr><td background="images/yellowbg1.gif">
		    </td></tr>
			<?php } ?>
		  </table>
	<?php
  }
  
  function ProcessExport()
  {
	// This function will export the subscriber list in the selected format (text, CSV, XML)
	$format = @$_POST["format"];
	$incFName = @$_POST["incFName"] != "" ? true : false;
	$incLName = @$_POST["incLName"] != "" ? true : false;
	$incEmail = @$_POST["incEmail"] != "" ? true : false;
	$incStatus = @$_POST["incStatus"] != "" ? true : false;
	$incDate = @$_POST["incDate"] != "" ? true : false;
	$find = @$_POST["find"];
	$doFullText = ($find == "" || $find == "[None - Export All Subscribers]") ? false : true;
	$exportNum = @$_POST["number"];
	$exportFrom = @$_POST["start"];
	
	// Is there at least one field selected to be exported?
	$noFieldsSelected = (($incFName || $incLName || $incEmail || $inStatus || $incDate) ? false : true);
	
	// Build the error list
	if($format == "")
		$err .= "<li>You forgot to choose the export format</li>";
		
	if($noFieldsSelected == true)
		$err .= "<li>You must select at least one field to export</li>";
		
	if(!is_numeric($exportNum) || @($exportNum <= 0))
		$err .= "<li>You must enter a valid number of records to export</li>";
		
	if(!is_numeric($exportFrom) || @($exportFrom < 0))
		$err .= "<li>You must enter a valid record numer to start exporting from</li>";
		
	if($err != "")
	{
		// Invalid forms. Show the error list
		?>
		  <table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Export Subscribers</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  The form that you've just submitted is incomplete. Please review the errors below and then go back and
				  correct them:
				  <ul><?php echo $err; ?></ul>
				  <p style="margin-left:5pt">
				  <a href="javascript:history.go(-1)"><< Go Back</a><br>&nbsp;
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
		// No errors, start the export process by building the query
		$query = "select ";
			
		if($incFName == true)
			$query .= "suFName, ";
		if($incLName == true)
			$query .= "suLName, ";
		if($incEmail == true)
			$query .= "suEmail, ";
		if($incStatus == true)
			$query .= "suStatus, ";
		if($incDate == true)
			$query .= "suDateSubscribed, ";
				
		// Kill the trailing comma if it exists
		$query = ereg_replace(", $", " ", $query);
		
		$query .= "from subscribedUsers ";

		if($doFullText == true)
			$query .= "where match(suFName, suLName, suEmail) against('$find') ";
			
		if($incFName == true && $incLName == true)
			$query .= "order by suFName asc, suLName asc ";
		else if($incFName == true && $incLName == false)
			$query .= "order by suFName asc ";
		else if($incEmail == true)
			$query .= "order by suEmail asc ";
		else if($incStatus == true)
			$query .= "order by suStatus asc ";
		else if($incDate == true)
			$query .= "order by suDateSubscribed asc ";
			
		$query .= "limit $exportFrom, $exportNum";
			
		// Now that we have the query we have to export the results in the selected format.
		// We wil use 3 select cases to perform the different export methods
		
		doDbConnect();
		$result = mysql_query($query);
		
		// If there are no rows returned the show an error
		
		if(mysql_num_rows($result) == 0)
		{
		?>
		  <table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Export Subscribers</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  <br>
				  The selected export options generated a list containing 0 subscribers. Please try again.
				  <br><br>
				  <a href="javascript:history.go(-1)"><< Go Back</a><br>&nbsp;
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
			ob_end_clean();
			
			switch($format)
			{
				case 1:
				{
					header("Content-Type: text/plain");
					header("Content-Disposition: attachment; filename=subscribers.txt");
					
					while($row = mysql_fetch_row($result))
					{
						for($i = 0; $i < sizeof($row); $i++)
						{
							echo $row[$i];
							
							if($i < sizeof($row)-1)
								echo "|";
							else
								echo "\r\n";
						}
					}
					
					break;
				}
				case 2:
				{
					header("Content-Type:application/vnd.ms-excel");
					header("Content-Disposition: attachment; filename=subscribers.csv");
					
					while($row = mysql_fetch_row($result))
					{
						for($i = 0; $i < sizeof($row); $i++)
						{
							echo $row[$i];
								
							if($i < sizeof($row)-1)
								echo ",";
							else
								echo "\r\n";
						}
					}

					break;
				}
				case 3:
				{
					header("Content-Type:application/xml");
					header("Content-Disposition: attachment; filename=subscribers.xml");
					
					echo "<subscribers>\r\n";
					
					while($row = mysql_fetch_array($result))
					{
						echo "  <subscriber>\r\n";
						
						foreach($row as $k => $v)
							if(!is_numeric($k))
								echo "    <$k>$v</$k>\r\n";
						
						echo "  </subscriber>\r\n";
					}
					
					echo "</subscribers>";

					break;
				}
			}
			
			die();
		}
	}
  }
  
  function DeleteAll()
  {
	// Delete every entry in the subscribedUsers and subscriptions tables
	doDbConnect();
	$result1 = false;
	$result2 = false;
	
	if(@mysql_query("delete from subscriptions"))
		$result1 = true;
	else
		$result1 = false;
	
	if(@mysql_query("delete from subscribedUsers"))
		$result2 = true;
	else
		$result2 = false;
		
	if($result1 == true && $result2 == true)
	{
	?>
		<table width="98%" align="center" border="0">
		    <tr><td height="30">
		      <span class="MainHeading">Delete Subscriber List</span>
		    </td></tr>
		    <tr><td background="images/yellowbg.gif">
		      <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
		  	<span class="Info">
		  	  <br>Your entire subscriber list and all of their subscription details have been deleted.
		  	  <br><br>
		  	  <a href="subscriber.php">Continue >></a><br>&nbsp;
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
	?>
		<table width="98%" align="center" border="0">
		    <tr><td height="30">
		      <span class="MainHeading">Delete Subscriber List</span>
		    </td></tr>
		    <tr><td background="images/yellowbg.gif">
		      <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
		  	<span class="Info">
		  	  <br>An internal error occured while trying to delete your entire subscriber list.
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
  
	?>
		
<?php include("templates/bottom.php"); ?>
