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
    case "new":
      ShowNewForm();
	  break;
	case "doNew":
	  ProcessNew();
	  break;
	case "delete":
	  DeleteTopics();
	  break;
	case "modify":
	  ModifyTopic();
	  break;
	case "doModify":
	  ProcessModify();
	  break;
	default:
	  ShowTopicList();
  }
  
  function ShowNewForm()
  {
	// Show the form to get the name of a new topic
	?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Create Topic</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
              Please complete the form below to create a new topic. Once created, you can select for new/current newsletters
              to be listed under this topic on your newsletter signup page.			
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
				      Topic Name:<br>
					  <input type="text" name="topic" size="40">
					  <br><br>
					  <input type="button" value="« Cancel" onClick="ConfirmCancel('topic.php')">
					  <input type="submit" name="submit" value="Add Topic »">
					</span>
				  </td>
				</tr>
			  </table>
			</form>
		  </td></tr>
		</table>
	<?php
  
  }
  
  function ProcessNew()
  {
		// Make sure all fields are completed
		$topic = @$_POST["topic"];
		$err = "";
		
		// Has the user entered all of the required fields?
		if($topic == "")
			$err .= "<li>You forgot to enter a topic</li>";
			
		if($err != "")
		{
			?>
		  <table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Create Topic</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  <br>The form that you've just submitted is incomplete. Please review the errors below and then go back and
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
			die();
		}
		else
		{
			// All of the fields were complete, save this newsletter to the database
			$query = "insert into topics values(0, '$topic')";
			
			doDbConnect();

			if(@mysql_query($query))
			{
				// Query executed OK
				?>
					<table width="98%" align="center" border="0">
					  <tr><td height="30">
					    <span class="MainHeading">Create Topic</span>
					  </td></tr>
					  <tr><td background="images/yellowbg.gif">
					    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
						<span class="Info">
						  <br>
						  You have successfully created and saved a topic. You can now add new/current newsletters under
						  this topic.
						  <br><br>
						  <a href="topic.php">Continue</a>
						  <br>&nbsp;
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
				// Query failed
				?>
				
				
				<?php
			}
		}
  }
  
  function ShowTopicList()
  {
	// Show a list of templates currently in the database
	doDbConnect();
	?>
		<script language="JavaScript">

			function CheckConfirmDelete()			
			{
				if(confirm('WARNING: You are about to permanently delete the selected topics. All templates and newsletters that are under this topic will also be deleted. Click OK to continue.'))
					return true;
				else
					return false;
			}
			
		</script>
			
		<form onSubmit="return CheckConfirmDelete()" action="topic.php" method="post">
		<input type="hidden" name="what" value="delete">

		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Topics</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
			  A list of existing topics is shown below. To add a new topic, click the "Add Topic" link.
			  To remove one/more topics, click the checkbox for that topic and then click the "Delete Selected" button.
  			</span>
		    </td></tr>
		    <tr><td background="images/yellowbg1.gif">
		    </td></tr>
		    <tr><td>
  		    </td></tr>
		  </table>
			  
		  <table width="98%" align="center" border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td width="100%" colspan="3" height="40" class="Info">
					<a href="topic.php?what=new"> Add Topic »</a>
				</td>
			</tr>
			<tr>
				<td bgcolor="#183863" width="10%" height="20" class="MenuHeading">
					&nbsp;
				</td>
				<td bgcolor="#183863" width="40%" height="20" class="MenuHeading">
					Topic Name
				</td>
				<td bgcolor="#183863" width="50%" height="20" class="MenuHeading">
					Number Of Dependants
				</td>
			</tr>
			<?php
				
				$result = mysql_query("select * from topics order by tName asc");
					
				if(mysql_num_rows($result) == 0)
				{
				?>
					<tr>
						<td colspan="4" width="100%" height="25" class="Info">
							There are no topics in the database.
						</td>
					</tr>
				<?php
				}
					
				while($row = mysql_fetch_row($result))
				{
				?>
					<tr>
						<td width="10%" height="20" class="TableCell">
							<input type="checkbox" name="tId[]" value="<?php echo $row[0]; ?>">
						</td>
						<td width="40%" height="20" class="TableCell">
							<a href="topic.php?what=modify&tId=<?php echo $row[0]; ?>"><?php echo $row[1]; ?></a>
						</td>
						<td width="50%" height="20" class="TableCell">
						<?php
						
							// Workout how many templates and newsletters are dependant on this topic
							$numTemplates = 0;
							$numNewsletters = 0;

							$tResult = mysql_query("select pk_nId from templates where nTopicId = " . $row[0]);
							$numTemplates = mysql_num_rows($tResult);
							
							while($tRow = mysql_fetch_row($tResult))
							{
								$cResult = mysql_query("select count(*) from newsletters where nTemplateId = " . $tRow[0]);
								$numNewsletters += mysql_result($cResult, 0, 0);
							}
							
							echo "$numTemplates template(s) and $numNewsletters newsletter(s)";
						?>
						</td>
					</tr>
				<?php
				}
			?>
				<tr>
					<td width="100%" colspan="4">
						<input type="submit" value="Delete Selected »">
					</td>
				</tr>
		  </table>
		</form>
	<?php
  }
  
  function DeleteTopics()
  {
		// This function will remove the selected topics from the database
		
		doDbConnect();
		
		$tId = @$_POST["tId"];
		$query = "";
		$result1 = "";
		$result2 = "";
		$result3 = "";

		?>

		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Delete Topic(s)</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">

		<?php
		
		if(is_array($tId) == true)
		{
			// Topics have been chosen, run 2 delete queries
			$query1 = "delete from topics where pk_tId = " . implode(" OR pk_tId = ", $tId);
			
			if(@mysql_query($query1))
				$result1 = true;
			else
				$result1 = false;
				
			// Topics have been removed, now delete the templates
			$result = mysql_query("select pk_nId from templates where nTopic = " . implode(" OR nTopicId = ", $tId));
			
			while($row = mysql_fetch_row($result))
			{
				// Delete newsletters that reference this template
				@mysql_query("delete from newsletters where nTemplateId = " . $row[0]);
			}
			
			// Finally, delete the templates
			$query2 = "delete from templates where nTopicId = "  . implode(" OR nTopicId = ", $tId);
			
			if(@mysql_query($query2))
				$result2 = true;
			else
				$result2 = false;

			if($result1 == true && $result2 == true)
			{
				// Query executed OK
				$status = "<br>You have successfully deleted one/more topics from the database, as well as any templates / newsletters that relied on these topics.<br><br>";
				$status .= "<a href='topic.php'>Continue >></a>";
			}
			else
			{
				// Delete querie(s) failed
				$status = "<br>An error occured while trying to delete the selected topic(s).<br><br>";
				$status .= "<a href='javascript:document.location.reload()'>Try Again</a>";
			}
		}
		else
		{
			// No newsletters have been chosen
			$status = "<br>You didn't select one/more topics to delete.<br><br>";
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
  
  function ModifyTopic()
  {
		// Change the details of a template
		doDbConnect();
		
		$tId = @$_GET["tId"];
		$result = mysql_query("select * from topics where pk_tId = $tId");
		
		if($row = mysql_fetch_array($result))
		{
		?>
			<table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Modify Topic</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
			      Please complete the form below to modify the selected topic. Click on the "Update Topic" button
			      to update this topic.
				</span>
			  </td></tr>
			  <tr><td background="images/yellowbg1.gif">		  
			  </td></tr>
			  <tr><td>
			    <form action="topic.php" method="post">
				  <input type="hidden" name="what" value="doModify">
				  <input type="hidden" name="tId" value="<?php echo $tId; ?>">
				  <table width="95%" align="center" border="0">
				    <tr>
					  <td valign="top">
					    <span class="BodyText">
						  <br>
					      Topic Name:<br>
						  <input type="text" name="tName" size="40" value="<?php echo str_replace("\"", "'", $row["tName"]); ?>">
						  <br><br>
						  <input type="button" value="« Cancel" onClick="ConfirmCancel('topic.php')">
						  <input type="submit" name="submit" value="Update Topic »">
						</span>
					  </td>
					</tr>
				  </table>
				</form>
			  </td></tr>
			</table>
		<?php
		}
		else
		{
		?>
			<table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Invalid Topic Selected</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  <br>The topic that you have selected is either invalid or has been deleted from the database.
				  <br><br>
				  <a href="topic.php">Continue >></a>
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
  
  function ProcessModify()
  {
	  // Grab the details for the newsletter from the form, create the new
	  // topic if necessary and workout whether to show a textbox or EWP control
	  
	  $tId = @$_POST["tId"];
	  $tName = @$_POST["tName"];
	  
	  // Make sure all of the required form variables are complete
	  $err = "";
	  
	  if($tName == "")
	    $err .= "<li>You forgot to enter the topic</li>";
		
	  doDbConnect();

	  if($err != "")
	  {
		?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Modify Topic</span>
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
		die();
	  }
	  
	  $query = "update topics set tName='$tName' where pk_tId=$tId";
	  $result = @mysql_query($query);
	  $status = "";
	  
	  if($result)
	  {
	    $status = "<br>Your topic has been successfully modified.<br><br>";
		$status .= "<a href='topic.php'>Continue >></a>";
	  }
	  else
	  {
		$status = "<br>Some errors occured while trying to modify this template.<br><br>";
	    $status .= "<a href='javascript:history.go(-1)'><< Go Back</a><br>&nbsp;";
	  }
	  ?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Modify Topic</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
			  <?php echo $status; ?>
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
