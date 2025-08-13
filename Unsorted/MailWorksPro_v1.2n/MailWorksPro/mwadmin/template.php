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
	  DeleteTemplates();
	  break;
	case "modify":
	  ModifyTemplate();
	  break;
	case "doModify":
	  ProcessModify();
	  break;
	default:
	  ShowNewsletterList();
  }
  
  function ShowNewForm()
  {
	if(@$_SERVER["SERVER_NAME"] == "")
		$from = "newsletter@yourdomain.com";
	else
		$from = "newsletter@" . $_SERVER["SERVER_NAME"];
  ?>  
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Create Newsletter Template</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
              Please complete the form below to create a new newsletter template. Once you create a newsletter template
              you can use it the create and send a newsletter.
			</span>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">		  
		  </td></tr>
		  <tr><td>
		    <form action="template.php" method="post">
			  <input type="hidden" name="what" value="doNew">
			  <table width="95%" align="center" border="0">
			    <tr>
				  <td valign="top">
				    <span class="BodyText">
					  <br>
				      Template Name:<br>
					  <input type="text" name="name" size="40">
					  <br><br>
					  Template Description:<br>
					  <textarea name="desc" rows="5" cols="30"></textarea>
					  <br><br>
					  Template Topic:<br>
					  <select name="topicId" size="3" style="width: 196pt">
					    <?php GetTopicList(); ?>
					  </select>
					  <br><input type="text" name="newTopicId" size="40" value="[Enter New Topic Here]" onClick="if(this.value =='[Enter New Topic Here]') { this.value = ''; }">
					  <br><br>
					  What Is The Email Address That This Newsletter Will Be Sent From?<br>
					  <input type="text" name="fromEmail" size="40" value="<?php echo $from; ?>">
					  <br><br>
					  What Is The Reply-To Email Address For This Newsletter?<br>
					  <input type="text" name="replyToEmail" size="40" value="[None]" onClick="if(this.value == '[None]') { this.value = ''; }">
					  <br><br>
					  How Often Will This Newsletter Be Sent? Every...<br>
					  <select name="freq1">
					    <?php GetFrequencyList(1); ?>
					  </select>
					  <select name="freq2">
					    <?php GetFrequencyList(2); ?>
					  </select>
					  <br><br>
					  Will This Newsletter Contain Plain-Text Or HTML?<br>
					  <input type="radio" name="format" value="text"> Plain-Text
					  <input type="radio" name="format" value="html" CHECKED> HTML
					  <br><br>
					  <input type="button" value="« Cancel" onClick="ConfirmCancel('template.php')">
					  <input type="submit" name="submit" value="Add Template »">
					</span>
				  </td>
				</tr>
			  </table>
			</form>
		  </td></tr>
		</table>
	<?php
	}
	
	function ShowNewsletterList()
	{
		// Show a list of templates currently in the database
		doDbConnect();
		?>
			<script language="JavaScript">

				function CheckConfirmDelete()			
				{
					if(confirm('WARNING: You are about to permanently delete the selected templates. All newsletters that use this template will also be deleted. Click OK to continue.'))
						return true;
					else
						return false;
				}
			
			</script>
			
			<form onSubmit="return CheckConfirmDelete()" action="template.php" method="post">
			<input type="hidden" name="what" value="delete">

			<table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Newsletter Templates</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  A list of existing newsletter templates is shown below. To add a new template, click the "Add Template" link.
				  To remove one/more templates, click the checkbox for that template and then click the "Delete Selected" button.
  				</span>
			    </td></tr>
			    <tr><td background="images/yellowbg1.gif">
			    </td></tr>
			    <tr><td>
  			    </td></tr>
			  </table>
			  
			  <table width="98%" align="center" border="0" cellspacing="2" cellpadding="2">
				<tr>
					<td width="100%" colspan="4" height="40" class="Info">
						<a href="template.php?what=new"> Add Template »</a>
					</td>
				</tr>
				<tr>
					<td bgcolor="#183863" width="10%" height="20" class="MenuHeading">
						&nbsp;
					</td>
					<td bgcolor="#183863" width="40%" height="20" class="MenuHeading">
						Template Name
					</td>
					<td bgcolor="#183863" width="30%" height="20" class="MenuHeading">
						Topic
					</td>
					<td bgcolor="#183863" width="20%" height="20" class="MenuHeading">
						Format
					</td>
				</tr>
				<?php
				
					$result = mysql_query("select * from templates inner join topics on templates.nTopicId = topics.pk_tId order by templates.nName asc");
					
					if(mysql_num_rows($result) == 0)
					{
					?>
						<tr>
							<td colspan="4" width="100%" height="25" class="Info">
								There are no newsletter templates in the database.
							</td>
						</tr>
					<?php
					}
					
					while($row = mysql_fetch_array($result))
					{
					?>
						<tr>
							<td width="10%" height="20" class="TableCell">
								<input type="checkbox" name="nId[]" value="<?php echo $row["pk_nId"]; ?>">
							</td>
							<td width="40%" height="20" class="TableCell">
								<a href="template.php?what=modify&tId=<?php echo $row["pk_nId"]; ?>"><?php echo $row["nName"]; ?></a>
							</td>
							<td width="30%" height="20" class="TableCell">
								<?php echo $row["tName"]; ?>
							</td>
							<td width="20%" height="20" class="TableCell">
								<?php echo $row["nFormat"]; ?>
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
	
	function ProcessNew()
	{
	  // Grab the details for the newsletter from the form, create the new
	  // topic if necessary and workout whether to show a textbox or EWP control
	  
	  $name = @$_POST["name"];
	  $desc = @$_POST["desc"];
	  $topicId = @$_POST["topicId"];
	  $newTopic = @$_POST["newTopicId"];
	  $fromEmail = @$_POST["fromEmail"];
	  $replyToEmail = @$_POST["replyToEmail"];
	  $freq1 = @$_POST["freq1"];
	  $freq2 = @$_POST["freq2"];
	  $format = @$_POST["format"];

	  // Make sure all of the required form variables are complete
	  $err = "";
	  
	  if($name == "")
	    $err .= "<li>You forgot to enter a name for this template</li>";
		
	  if($desc == "")
	    $err .= "<li>You forgot to enter a description for this template</li>";
	
	  if($topicId == "" && (trim($newTopic) == "" || $newTopic == "[Enter New Topic Here]"))
	    $err .= "<li>You forgot to select a topic for this template</li>";
	    
	  if(!is_numeric(strpos($fromEmail, "@")) || !is_numeric(strpos($fromEmail, ".")))
		$err .= "<li>Please enter a valid 'From' email address</li>";

	  if($replyToEmail != "" && $replyToEmail != "[None]")
		if(!is_numeric(strpos($replyToEmail, "@")) || !is_numeric(strpos($replyToEmail, ".")))
			$err .= "<li>Please enter a valid 'ReplyTo' email address</li>";

	  if($replyToEmail == "[None]")
		$replyToEmail = "";

	  doDbConnect();

  	  // Do we need to add a new topic to the topics table?
	  if($topicId == "" || $newTopic != "" || $newTopic != "[Enter New Topic Here]")
	  {
	    $topicExists = mysql_result(mysql_query("select count(*) from topics where tName='$newTopic'"), 0, 0) > 0 ? true : false;
						  
	    if($topicExists == false && $newTopic != "" && $newTopic != "[Enter New Topic Here]")
	    {
	      $result = mysql_query("insert into topics(tName) values('$newTopic')");
	      $topicId = mysql_insert_id();
	    }
	  }
	  
	  if($err != "")
	  {
		?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Create Newsletter Template</span>
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
	  
	  $query = "insert into templates values(0, '$name', '$desc', $topicId, '$fromEmail', '$replyToEmail', $freq1, $freq2, '$format')";
	  $result = @mysql_query($query);
	  $status = "";
	  
	  if($result)
	  {
	    $status = "<br>Your newsletter template has been successfully saved to the database.<br><br>";
		$status .= "<a href='sendnewsletter.php'>Send Newsletter</a> | <a href='template.php'>Continue >></a>";
	  }
	  else
	  {
		$status = "<br>Some errors occured while trying to create this newsletter.<br><br>";
	    $status .= "<a href='javascript:history.go(-1)'><< Go Back</a><br>&nbsp;";
	  }
	  ?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Create Newsletter Template</span>
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
	  
	  function DeleteTemplates()
	  {
		// This function will remove the selected templates and all newsletters that use this
		// template from the database
		
		doDbConnect();
		
		$nId = @$_POST["nId"];
		$query1 = "";
		$query2 = "";

		?>

		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Delete Newsletter Template(s)</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">

		<?php
		
		if(is_array($nId) == true)
		{
			// Templates have been chosen, run 2 delete queries
			$query1 = "delete from templates where pk_nId = " . implode(" OR pk_nId = ", $nId);
			$query2 = "delete from newsletters where nTemplateId = " . implode(" OR nTemplateId = ", $nId);
			
			if(@mysql_query($query1))
				$result1 = true;
			else
				$result1 = false;
			
			if(@mysql_query($query2))
				$result2 = true;
			else
				$result2 = false;

			if($result1 && $result2)
			{
				// Query executed OK
				$status = "<br>You have successfully delete one/more templates from the database.<br><br>";
				$status .= "<a href='sendnewsletter.php'>Send Newsletter</a> | <a href='template.php'>Continue >></a>";
			}
			else
			{
				// Delete querie(s) failed
				$status = "<br>An error occured while trying to delete the selected template(s).<br><br>";
				$status .= "<a href='javascript:document.location.reload()'>Try Again</a>";
			}
		}
		else
		{
			// No templates have been chosen
			$status = "<br>You didn't select one/more templates to delete.<br><br>";
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
	
	function ModifyTemplate()
	{
		// Change the details of a template
		doDbConnect();
		
		$tId = @$_GET["tId"];
		$result = mysql_query("select * from templates where pk_nId = $tId");
		
		if($row = mysql_fetch_array($result))
		{
		?>
			<table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Modify Newsletter Template</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
			      Please complete the form below to modify the selected newsletter template. Click on the "Update Template" button
			      to update the details of this template.
				</span>
			  </td></tr>
			  <tr><td background="images/yellowbg1.gif">		  
			  </td></tr>
			  <tr><td>
			    <form action="template.php" method="post">
				  <input type="hidden" name="what" value="doModify">
				  <input type="hidden" name="tId" value="<?php echo $tId; ?>">
				  <table width="95%" align="center" border="0">
				    <tr>
					  <td valign="top">
					    <span class="BodyText">
						  <br>
					      Template Name:<br>
						  <input type="text" name="name" size="40" value="<?php echo str_replace("\"", "'", $row["nName"]); ?>">
						  <br><br>
						  Template Description:<br>
						  <textarea name="desc" rows="5" cols="30"><?php echo $row["nDesc"]; ?></textarea>
						  <br><br>
						  <select name="topicId" size="3" style="width: 196pt">
						    <?php GetTopicList($row["nTopicId"]); ?>
						  </select>
						  <br><input type="text" name="newTopicId" size="40" value="[Enter New Topic Here]" onClick="if(this.value =='[Enter New Topic Here]') { this.value = ''; }">
						  <br><br>
						  What Is The Email Address That This Newsletter Will Be Sent From?<br>
						  <input type="text" name="fromEmail" size="40" value="<?php echo $row["nFromEmail"]; ?>">
						  <br><br>
						  What Is The Reply-To Email Address For This Newsletter?<br>
						<input type="text" name="replyToEmail" size="40" value="<?php echo $row["nReplyToEmail"]; ?>">
						  <br><br>
						  How Often Will This Newsletter Be Sent? Every...<br>
						  <select name="freq1">
						    <?php GetFrequencyList(1, $row["nFrequency1"]); ?>
						  </select>
						  <select name="freq2">
						    <?php GetFrequencyList(2, $row["nFrequency2"]); ?>
						  </select>
						  <br><br>
						  Will This Newsletter Contain Plain-Text Or HTML?<br>
						  <input type="radio" name="format" value="text" <?php if($row["nFormat"] == "text") { echo " CHECKED "; } ?>> Plain-Text
						  <input type="radio" name="format" value="html" <?php if($row["nFormat"] == "html") { echo " CHECKED "; } ?>> HTML
						  <br><br>
						  <input type="button" value="« Cancel" onClick="ConfirmCancel('template.php')">
						  <input type="submit" name="submit" value="Update Template »">
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
			    <span class="MainHeading">Invalid Template Selected</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  <br>The template that you have selected is either invalid or has been deleted from the database.
				  <br><br>
				  <a href="template.php">Continue >></a>
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
	  
	  $name = @$_POST["name"];
	  $desc = @$_POST["desc"];
	  $topicId = @$_POST["topicId"];
	  $newTopic = @$_POST["newTopicId"];
	  $fromEmail = @$_POST["fromEmail"];
	  $replyToEmail = @$_POST["replyToEmail"];
	  $freq1 = @$_POST["freq1"];
	  $freq2 = @$_POST["freq2"];
	  $format = @$_POST["format"];
	  $tId = @$_POST["tId"];
	  
	  // Make sure all of the required form variables are complete
	  $err = "";
	  
	  if($name == "")
	    $err .= "<li>You forgot to enter a name for this template</li>";
		
	  if($desc == "")
	    $err .= "<li>You forgot to enter a description for this template</li>";
	
	  if($topicId == "" && (trim($newTopic) == "" || $newTopic == "[Enter New Topic Here]"))
	    $err .= "<li>You forgot to select a topic for this template</li>";

	  if(!is_numeric(strpos($fromEmail, "@")) || !is_numeric(strpos($fromEmail, ".")))
		$err .= "<li>Please enter a valid 'From' email address</li>";

	  if($replyToEmail != "" && $replyToEmail != "[None]")
		if(!is_numeric(strpos($replyToEmail, "@")) || !is_numeric(strpos($replyToEmail, ".")))
			$err .= "<li>Please enter a valid 'ReplyTo' email address</li>";

	  if($replyToEmail == "[None]")
		$replyToEmail = "";
	  
	  doDbConnect();

  	  // Do we need to add a new topic to the topics table?
	  if($topicId == "" || $newTopic != "" || $newTopic != "[Enter New Topic Here]")
	  {
	    $topicExists = mysql_result(mysql_query("select count(*) from topics where tName='$newTopic'"), 0, 0) > 0 ? true : false;
						  
	    if($topicExists == false && $newTopic != "" && $newTopic != "[Enter New Topic Here]")
	    {
	      $result = mysql_query("insert into topics(tName) values('$newTopic')");
	      $topicId = mysql_insert_id();
	    }
	  }

	  if($err != "")
	  {
		?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Modify Newsletter Template</span>
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
	  
	  $query = "update templates set nName='$name', nDesc='$desc', nTopicId=$topicId, nFromEmail='$fromEmail', nReplyToEmail='$replyToEmail', nFrequency1=$freq1, nFrequency2=$freq2, nFormat='$format' where pk_nId=$tId";
	  $result = @mysql_query($query);
	  $status = "";
	  
	  if($result)
	  {
	    $status = "<br>Your newsletter template has been successfully modified.<br><br>";
		$status .= "<a href='sendnewsletter.php'>Send Newsletter</a> | <a href='template.php'>Continue >></a>";
	  }
	  else
	  {
		$status = "<br>Some errors occured while trying to modify this template.<br><br>";
	    $status .= "<a href='javascript:history.go(-1)'><< Go Back</a><br>&nbsp;";
	  }
	  ?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Modify Newsletter Template</span>
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
