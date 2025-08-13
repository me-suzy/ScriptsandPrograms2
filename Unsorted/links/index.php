<?php
////////////////////////////////////////////////////////////////////////////
// db Masters' Links Directory 3.1.2, Copyright (c) 2003 db Masters Multimedia
// Links Directory comes with ABSOLUTELY NO WARRANTY
// Licensed under the AGPL
// See license.txt and readme.txt for details
////////////////////////////////////////////////////////////////////////////
	ob_start();
	include('config.php');
	include('header.php');
	$ax=$_GET["ax"];
	$list_type=$_GET["l"];

////////////////
// List Function
////////////////

	if ($ax=="list")
	{
		if($list_type=="clicks")
		{
			echo "<p class=\"bodylgbold\">$popular Most Popular Links</p>";
			echo "<p class=\"bodysm\"> &gt; <a href=\"$PHP_SELF\">Home</a> &gt; Popular Links</p>";
			$sql_rqst="SELECT * FROM links WHERE approved='yes' ORDER BY clicks DESC LIMIT 0,".$popular;
		}
		else if($list_type=="date_added")
		{
			echo "<p class=\"bodylgbold\">$new Newest Links</p>";
			echo "<p class=\"bodysm\"> &gt; <a href=\"$PHP_SELF\">Home</a> &gt; Newest Links</p>";
			$sql_rqst="SELECT * FROM links WHERE approved='yes' ORDER BY date_added,id ASC LIMIT 0,".$new;
		}
		else
		{
			$topic_result = MySQLQuery("SELECT * FROM links_cat WHERE id=$cat_id", $QueryError_Email,$QueryError_Browser);
			while($topic_row = mysql_fetch_array($topic_result))
			{
				$id=$topic_row["id"];
				$formcat_id=$topic_row["id"];
				$sub_cat=$topic_row["sub_cat"];
				$name=$topic_row["name"];
				$dsc=$topic_row["dsc"];
			}
			if($sub_cat != 0)
			{
				$topic2_result = MySQLQuery("SELECT id,sub_cat,name FROM links_cat WHERE id=$sub_cat", $QueryError_Email,$QueryError_Browser);
				while($topic2_row = mysql_fetch_array($topic2_result))
				{
					$id2=$topic2_row["id"];
					$sub_cat2=$topic2_row["sub_cat"];
					$name2=$topic2_row["name"];
				}
				$trail="<a href=\"$PHP_SELF?ax=list&amp;sub=$id2&amp;cat_id=$id2\">$name2</a> &gt; $name";
			}
			else
			{
				$trail=$name;
			}
			echo "<p class=\"bodylgbold\">$name</p>";
			echo "<p class=\"bodysm\"> &gt; <a href=\"$PHP_SELF\">Home</a> &gt; $trail</p>";
			echo "<p class=\"bodymd\">$dsc</p>";
			if(!isset($start)){$start = 0;}
			$sql_rqst="SELECT * FROM links WHERE cat_id=".$_GET["cat_id"]." AND approved='yes' ORDER BY ".$list_by." ".$list_order." LIMIT ".$start.",".$category;
			$page_result = MySQLQuery("SELECT * FROM links WHERE cat_id=$cat_id",$QueryError_Email,$QueryError_Browser);
			$previous_page=$start - $category;
			$next_page=$start + $category;
			$numrows=mysql_num_rows($page_result);
			if(($start > 0) || ($numrows > ($start + $category))){echo "<p class=\"bodysm\">";}
			if($start > 0){echo "[ <a href=\"$PHP_SELF?ax=list&amp;l=list_by&amp;cat_id=$cat_id&amp;start=$previous_page\">&lt; &lt; Previous</a> ]\n";}
			if($numrows > ($start + $category)){echo "[ <a href=\"$PHP_SELF?ax=list&amp;l=list_by&amp;cat_id=$cat_id&amp;start=$next_page\">Next &gt; &gt;</a> ]\n";}
			if(($start > 0) || ($numrows > ($start + $category))){echo "</p>";}
		}
		$result = MySQLQuery($sql_rqst,$QueryError_Email,$QueryError_Browser);
		while($row = mysql_fetch_array($result))
		{
			$id=$row["id"];
			$name=$row["name"];
			$clicks=$row["clicks"];
			$approved=$row["approved"];
			$url=$row["url"];
			$dsc=$row["dsc"];
			$date_added=$row["date_added"];
				$year = substr($date_added, 0, 4); 
				$month = substr($date_added, 5, 2); 
				$day = substr($date_added, 8, 2);
			$date_added=date($display_format,mktime("00","00","00",$month,$day,$year));
			$image=$row["image"];
			$rating=$row["rating"];
			$rates=$row["rates"];
			if($image_enabled=="yes" && $image)
			{
				$image="<img src=\"$image\" align=\"right\">";
			}
			else
			{
				$image="";
			}
			if($rates=="" || $rates=="0")
			{
				$rate_stat="<i>Not Yet Rated</i>";
			}
			else
			{
				$rating=$rating / $rates;
				$rating=round($rating,2);
				$rate_stat=$rating." out of 10 (".$rates." Ratings)";
			}
			if($clicks==""){$clicks="0";}
?>
<script type="text/javascript">
<!--
function validate_rate<?php echo $id; ?>()
{
	if(document.getElementById('form<?php echo $id; ?>').rating.selectedIndex == 0){alert("Please select your rating for <?php echo $name ?> from 1-10.");return false;}		
}
//-->
</script>
<p class="bodymd"><?php echo $image; ?><a href="<?php echo $PHP_SELF ?>?ax=out&amp;id=<?php echo $id ?>"><?php echo $name ?></a>
<br /><span class="bodysm">[ Added: <?php echo $date_added ?> | Total Clicks: <?php echo $clicks ?> | Rating: <?php echo $rate_stat ?> | <a href="#" onclick="blocking('<?php echo $id ?>');return false;">Rate This Website</a> | <a href="<?php echo $PHP_SELF ?>?ax=deadlink&amp;id=<?php echo $id ?>">Notify of Dead Link</a> ]</span>
<br /><?php echo $dsc ?>
<br /><div id="<?php echo $id ?>"><form action="<?php echo $PHP_SELF ?>?ax=addrate&amp;id=<?php echo $id ?>&amp;cat_id=<?php echo $formcat_id; ?>&amp;l=<?php echo $list_type; ?>" method="post" id="form<?php echo $id; ?>" onsubmit="return validate_rate<?php echo $id; ?>();"><span class="bodysm">Rate This Website</span> <select name="rating" class="bodysm"><option>- choose --</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select> <input type="submit" name="submit" value="Rate <?php echo $name ?>" class="bodysm" /></div></form>
</p>
<script type="text/javascript">
<!--
	document.getElementById('<?php echo $id ?>').style.display='none';
//-->
</script>
<?php
		}
		if($_GET["l"]!="date_added" && $_GET["l"]!="clicks")
		{
			if(($start > 0) || ($numrows > ($start + $category))){echo "<p class=\"bodysm\">";}
			if($start > 0){echo "[ <a href=\"$PHP_SELF?ax=list&amp;l=list_by&amp;cat_id=$cat_id&amp;start=$previous_page\">&lt; &lt; Previous</a> ]\n";}
			if($numrows > ($start + $category)){echo "[ <a href=\"$PHP_SELF?ax=list&amp;l=list_by&amp;cat_id=$cat_id&amp;start=$next_page\">Next &gt; &gt;</a> ]\n";}
			if(($start > 0) || ($numrows > ($start + $category))){echo "</p>";}
		}
	}
	else if($ax=="out")
	{
		$result = MySQLQuery("SELECT id,url,clicks FROM links WHERE id=".$_GET["id"],$QueryError_Email,$QueryError_Browser);
		while($row = mysql_fetch_array($result))
		{
			$id=$row["id"];
			$url=$row["url"];
			$clicks=$row["clicks"];
			$clicks=$clicks+1;
			$up_clicks = MySQLQuery("UPDATE links SET clicks='$clicks' WHERE id='$id'",$QueryError_Email,$QueryError_Browser);
			ob_end_clean();
			header("Location: ".$url);
		}
	}
	else if($ax=="deadlink")
	{
		$result = MySQLQuery("SELECT id,name,url FROM links WHERE id=".$_GET["id"],$QueryError_Email,$QueryError_Browser);
		while($row = mysql_fetch_array($result))
		{
			$id=$row["id"];
			$url=$row["url"];
			$name=$row["name"];
			$clicks=$clicks+1;
			$message="$name\n$url\nis being reported as a dead link in the links directory at $site_title";
			mail("$admin_mail","Deal Link Report from $site_title","$message","From: $admin_mail");
			echo "<p class=\"bodylgbold\">Thank You</p>";
			echo "<p class=\"bodysm\"> &gt; <a href=\"$PHP_SELF\">Home</a> &gt; Report Dead Link</p>";
			echo "<p class=\"bodymd\">You have reported $name linked to $url as being a deal link in our directory.</p>";
			echo "<p class=\"bodymd\">This message has been sent to the web site administrator, thank you for your help.</p>";
		}
	}
	else if($ax=="addrate")
	{
		$id=$_GET["id"];
		$cat_id=$_GET["cat_id"];
		$new_rating=$_POST["rating"];
		$result = MySQLQuery("SELECT id,rating,rates FROM links WHERE id=$id;",$QueryError_Email,$QueryError_Browser);
		while($row = mysql_fetch_array($result))
		{
			$rating=$row["rating"];
			$rating=$rating + $new_rating;
			$rates=$row["rates"];
			$rates=$rates + 1;
			$up_clicks = MySQLQuery("UPDATE links SET rating='$rating',rates='$rates' WHERE id='$id'",$QueryError_Email,$QueryError_Browser);
			echo "<p class=\"bodylgbold\">Thanks for your input</p>\n";
			echo "<p class=\"bodysm\"> &gt; <a href=\"$PHP_SELF\">Home</a> &gt; Rating</p>";
			echo "<p class=\"bodymd\">Your rating has been added, thank you.</p>\n";
		}
		if($list_type=="")
		{
			$sub_result = MySQLQuery("SELECT id,sub_cat FROM links_cat WHERE id=".$cat_id."",$QueryError_Email,$QueryError_Browser);
			while($sub_row = mysql_fetch_array($sub_result))
			{
				$list_type=$_GET["l"];
				if($list_type=="")
				{
					$id=$sub_row["id"];
					$subcat=$sub_row["sub_cat"];
					if($subcat==0){$subcat=$id;}
					echo "<p class=\"bodymd\">To return to the page you were at, <a href=\"".$_SERVER["PHP_SELF"]."?ax=list&amp;sub=". $subcat."&amp;cat_id=".$id."\">click here</a>.</p>\n";
				}
			}
		}
		else
		{
			echo "<p class=\"bodymd\">To return to the page you were at, <a href=\"".$_SERVER["PHP_SELF"]."?ax=list&amp;l=".$_GET["l"]."\">click here</a>.</p>\n";
		}
	}

//////////////////
// Search Function
//////////////////

	else if ($ax=="search")
	{
		echo "<p class=\"bodylgbold\">Search Results</p>";
		echo "<p class=\"bodysm\"> &gt; <a href=\"$PHP_SELF\">Home</a> &gt; Search Results</p>";
		if(!isset($start))
		{
			$start = 0;
			$search_for=$_POST["search_for"];
		}
		else
		{
			$search_for=$_GET["search_for"];
		}
		$result = MySQLQuery("SELECT * FROM links WHERE (name LIKE '%".$search_for."%' OR url LIKE '%".$search_for."%' OR dsc LIKE '%".$search_for."%') AND approved='yes' ORDER BY name ASC LIMIT  $start,$search",$QueryError_Email,$QueryError_Browser);
		$page_result = MySQLQuery("SELECT * FROM links WHERE (name LIKE '%".$search_for."%' OR url LIKE '%".$search_for."%' OR dsc LIKE '%".$search_for."%') AND approved='yes' ORDER BY id DESC",$QueryError_Email,$QueryError_Browser);
		$previous_page=$start - $search;
		$next_page=$start + $search;
		$numrows=mysql_num_rows($page_result);
		echo "<p class=\"bodymd\">Total of $numrows Records Returned</p>";
		if(($start > 0) || ($numrows > ($start + $search))){echo "<p class=\"bodysm\">";}
		if($start > 0){echo "[ <a href=\"$PHP_SELF?ax=search&amp;search_for=$search_for&amp;start=$previous_page\">&lt; &lt; Previous</a> ]\n";}
		if($numrows > ($start + $search)){echo "[ <a href=\"$PHP_SELF?ax=search&amp;search_for=$search_for&amp;start=$next_page\">Next &gt; &gt;</a> ]\n";}
		if(($start > 0) || ($numrows > ($start + $search))){echo "</p>";}
		while($row = mysql_fetch_array($result))
		{
			$id=$row["id"];
			$name=$row["name"];
			$clicks=$row["clicks"];
			$approved=$row["approved"];
			$url=$row["url"];
			$dsc=$row["dsc"];
			if ($approved=="yes")
			{
				if($clicks==""){$clicks="0";}
				echo "<p class=\"bodymd\"><a href=\"$PHP_SELF?ax=out&amp;id=$id\">$name</a>
						<br /><span class=\"bodysm\">[ <a href=\"$PHP_SELF?ax=out&amp;id=$id\">$url</a> | Total Clicks: $clicks | <a href=\"$PHP_SELF?ax=deadlink&amp;id=$id\">Notify of Dead Link</a> ]</span>
						<br />$dsc</p>";
			}
		}
		if(($start > 0) || ($numrows > ($start + $search))){echo "<p class=\"bodysm\">";}
		if($start > 0){echo "[ <a href=\"$PHP_SELF?ax=search&amp;search_for=$search_for&amp;start=$previous_page\">&lt; &lt; Previous</a> ]\n";}
		if($numrows > ($start + $search)){echo "[ <a href=\"$PHP_SELF?ax=search&amp;search_for=$search_for&amp;start=$next_page\">Next &gt; &gt;</a> ]\n";}
		if(($start > 0) || ($numrows > ($start + $search))){echo "</p>";}
	}

///////////////////////////
// Add and Insert Functions
///////////////////////////

	else if($ax=="add")
	{
		$date_added=date($date_format);
?>
<p class="bodylgbold">Add a Link</p>
<p class="bodysm"> &gt; <a href="<?php echo $PHP_SELF; ?>">Home</a> &gt; Add a Link</p>
<p class="bodymd">All Fields are required for link submission</p>
<form action="<?php echo $PHP_SELF; ?>?ax=insert" method="post" id="form" onsubmit="return validate_add();">
<table border="0" width="100%" cellspacing="2" cellpadding="0">
<tr>
	<td class="bodymd"><span style="color:#FF0000;">*</span>Category:<br />
		<select name="cat_id">
<option value="">Select a Category</option>
<?php
		$result=MySQLQuery("select * from links_cat where id !=0 order by name",$QueryError_Email,$QueryError_Browser);
		while($row=mysql_fetch_array($result))
		{
			$cat_id=$row["id"];
			$cat_name=$row["name"];
			echo "<option value=\"$cat_id\"> $cat_name</option>";
		}
?>
		</select>
	</td>
</tr>
<tr><td class="bodymd">Website Name<span style="color:#FF0000;">*</span>:<br /><input type="text" name="name" size="60"/></td></tr>
<tr><td class="bodymd">Website Description<span style="color:#FF0000;">*</span>:<br /><textarea name="dsc" rows="3" cols="40"></textarea></td></tr>
<tr><td class="bodymd">URL<span style="color:#FF0000;">*</span>:<br /><input type="text" name="url" size="60"/></td></tr>
<?php if($image_enabled=="yes"){ ?>
<tr><td class="bodymd">Image URL:<br /><input type="text" name="image" size="60" value="<?php echo $image; ?>" maxlength="100"/></td></tr>
<?php } ?>
<tr><td class="bodymd"> Email:<br /><input type="text" name="email" size="50"/></td></tr>
<tr><td class="bodymd"> Password:<br /><input type="password" name="password" size="10"/></td></tr>
<tr><td class="bodymd"><span style="color:#FF0000;">*</span> - indicates required field</td></tr>
<tr><td><input type="hidden" name="date_added" value="<?php echo $date_added; ?>"/><input type="submit" name="submit" value="Add Link"/></td></tr>
</table>
</form>
<?php
	}
	else if($ax=="insert")
	{
		$cat_id=$_POST["cat_id"];
		$name=$_POST["name"];
		$dsc=$_POST["dsc"];
		$url=$_POST["url"];
		$email=$_POST["email"];
		$password=$_POST["password"];
		$date_added=$_POST["date_added"];
		$image=$_POST["image"];
		if($admin_approved=="no")
		{
			$approved="no";
		}
		else
		{
			$approved="yes";
		}
		$result=MySQLQuery("insert into links (cat_id,name,dsc,url,approved,email,password,date_added,image,rating,rates) values ('$cat_id','$name','$dsc','$url','$approved','$email','$password','$date_added','$image','0','0')",$QueryError_Email,$QueryError_Browser);
		if ($result)
		{
			mail("$admin_mail", "Link submitted to ".$site_title."", "The link ".$name." is waiting for approval at ".$site_title."", "From: ".$admin_mail."");
			if ($email && $password)
			{
				mail($email, "Link submitted to ".$site_title."","Your link is waiting for approval at ".$site_title.".\nTo update you listing in the future, login with \n\nURL:".$url."\nEmail:".$email."\nPassword:".$password."\n\nThanks for your submission", "From: ".$GLOBALS["NoReply_Mail"]);
			}
			echo "<p class=\"bodylgbold\">The Link Was Added</p>";
			echo "<p class=\"bodysm\"><a href=\"".$PHP_SELF."\">Home</a> &gt; <a href=\"".$PHP_SELF."?ax=add\">Add a Link</a> &gt; Link Added</p>";
			if($admin_approved=="no")
			{
				echo "<p class=\"bodymd\">Your link request has been sent to the site administrator for approval.</p>";
				echo "<p class=\"bodymd\">An email has also been sent to you regarding your login information should you choose to edit your listing in the future.</p>";
			}
			else
			{
				echo "<p class=\"bodymd\">Your link has been added to the directory.</p>";
				echo "<p class=\"bodymd\">An email has also been sent to you regarding your login information should you choose to edit your listing in the future.</p>";
			}
		}
		else
		{
			echo "<p class=\"bodysm\"><a href=\"".$PHP_SELF."\">Home</a> &gt; <a href=\"".$PHP_SELF."?ax=add\">Add a Link</a> &gt; Error Adding Link</p>
					<p class=\"bodylgbold\">Error Adding Link</p>";
		}
	}

//////////////////////////////
// Login and Editing Functions
//////////////////////////////

	else if($ax=="login")
	{
?>
<p class="bodylgbold">Edit Your Listing</p>
<p class="bodysm"> &gt; <a href="<?php echo $PHP_SELF; ?>">Home</a> &gt; Edit Login</p>
<form action="<?php echo $PHP_SELF; ?>?ax=editlogin" method="post" id="form" onsubmit="return validate_login();">
<table border="0" width="100%" cellspacing="2" cellpadding="0">
<tr><td class="bodymd">URL<span style="color:#FF0000;">*</span>:<br /><input type="text" name="url" size="60"/></td></tr>
<tr><td class="bodymd">Email<span style="color:#FF0000;">*</span>:<br /><input type="text" name="email" size="60"/></td></tr>
<tr><td class="bodymd">Password<span style="color:#FF0000;">*</span>:<br /><input type="password" name="password" size="60"/></td></tr>
<tr><td class="bodymd"><span style="color:#FF0000;">*</span> - indicates required field</td></tr>
<tr><td><input type="submit" name="submit" value="Login"/></td></tr>
<tr><td class="bodymd"><a href="<?php echo $PHP_SELF; ?>?ax=remind">Forget your login info?</a></td></tr>
</table>
</form>
<?php
	}
	else if($ax=="editlogin")
	{
		$result=MySQLQuery("select * from links where url='".$_POST["url"]."' and email='".$_POST["email"]."' and password='".$_POST["password"]."'",$QueryError_Email,$QueryError_Browser);
		if(mysql_num_rows($result))
		{
			while($row=mysql_fetch_array($result))
			{
				$id=$row["id"];
				$cat_id=$row["cat_id"];
				$name=$row["name"];
				$dsc=$row["dsc"];
				$url=$row["url"];
				$image=$row["image"];
				$email=$row["email"];
				$password=$row["password"];
?>
<p class="bodylgbold">Edit a Link</p>
<p class="bodysm"> &gt; <a href="<?php echo $PHP_SELF; ?>">Home</a> &gt; <a href="<?php echo $PHP_SELF."?ax=login"; ?>">Edit Login</a> &gt; Edit <?php echo $name; ?></p>
<form action="<?php echo $PHP_SELF; ?>?ax=update&id=<?php echo $id; ?>" method="post" name="form" onsubmit="return validate_edit();">
<table border="0" width="100%" cellspacing="2" cellpadding="0">
<tr>
	<td class="bodymd"><span style="color:#FF0000;">*</span>Category:<br />
		<select name="cat_id">
<?php
				$result=MySQLQuery("select * from links_cat where id='$cat_id'",$QueryError_Email,$QueryError_Browser);
				while($row=mysql_fetch_array($result))
				{
					$temp1_id=$row["id"];
					$temp1_name=$row["name"];
					echo "<option value='$temp1_id'> $temp1_name</option>";
				}
				$result=MySQLQuery("select * from links_cat where id!='$cat_id' order by name",$QueryError_Email,$QueryError_Browser);
				while($row=mysql_fetch_array($result))
				{
					$temp2_id=$row["id"];
					$temp2_name=$row["name"];
					echo "<option value='$temp2_id'> $temp2_name</option>";
				}
?>
		</select>
	</td>
</tr>
<tr><td class="bodymd">Website Name<span style="color:#FF0000;">*</span>:<br /><input type="text" name="name" size="60" value="<?php echo $name ?>"/></td></tr>
<tr><td class="bodymd">Website Description<span style="color:#FF0000;">*</span>:<br /><textarea name="dsc" rows="3" cols="40"><?php echo $dsc ?></textarea></td></tr>
<tr><td class="bodymd">URL<span style="color:#FF0000;">*</span>:<br /><input type="text" name="url" size="60" value="<?php echo $url ?>"/></td></tr>
<?php if($image_enabled=="yes"){ ?>
<tr><td class="bodymd">Image URL:<br /><input type="text" name="image" size="60" value="<?php echo $image; ?>" maxlength="100"/></td></tr>
<?php } ?>
<tr><td class="bodymd"> Email:<br /><input type="text" name="email" size="50" value="<?php echo $email ?>"/></td></tr>
<tr><td class="bodymd"> Password:<br /><input type="password" name="password" size="10" value="<?php echo $password ?>"/></td></tr>
<tr><td class="bodymd"><span style="color:#FF0000;">*</span> - indicates required field</td></tr>
<tr><td><input type="submit" name="submit" value="Edit Link"/></td></tr>
</table>
</form>
<?php
			}
		}
		else
		{
			echo "<p class=\"bodylgbold\">No Records Found</p>";
			echo "<p class=\"bodymd\">No web site listing can be found with that URL, email address or password.</p>";
			echo "<p class=\"bodymd\"><a href=\"$PHP_SELF?ax=remind\">Click here</a> to get login info for all websites listed to your email address sent to you.</p>";
		}
	}
	else if($ax=="update")
	{ 
		$cat_id=$_POST["cat_id"];
		$name=$_POST["name"];
		$dsc=$_POST["dsc"];
		$url=$_POST["url"];
		$email=$_POST["email"];
		$password=$_POST["password"];
		$image=$_POST["image"];
		$result=MySQLQuery("update links set cat_id='$cat_id', name='$name', dsc='$dsc', url='$url', email='$email', password='$password', image='$image' where id=$id",$QueryError_Email,$QueryError_Browser);
		if ($result)
		{
			mail($admin_mail, "Link was modified at ".$site_title."", "The link ".$name." has been modified at ".$site_title."", "From: ".$admin_mail);
			if ($email && $password)
			{
				mail($email, "Link modified at ".$site_title."", "Your link has been modified.\n\nThanks for keeping it up to date.", "From: ".$noreply_mail);
			}	
			echo "<p class=bodylgbold>The Link Was Updated</p>";
			echo "<p class=\"bodysm\"><a href=\"".$PHP_SELF."\">Home</a> &gt; <a href=\"$PHP_SELF?ax=login\">Edit Login</a> &gt; Edit &gt; $name Updated</p>";
			echo "<p class=\"bodymd\">Your link update has been changed and the site administrator has been notified of the change.</p>";
			echo "<p class=\"bodymd\">Your listing will remain in the directory during this time.</p>";
		}
		else
		{
			echo "<p class=bodylgbold>Error Updating Link</p>";
		}
	}

//////////////////////////////
// Password Reminder Functions
//////////////////////////////

	else if($ax=="remind")
	{
?>
<p class="bodylgbold">Get Your Login Information</p>
<p class="bodymd">This will email you the login information for all URL's that have been submitted by the requested email address</p>
<form action="<?php echo $PHP_SELF; ?>?ax=remindmail" method="post" id="form" onsubmit="return validate_remind();">
<table border="0" width="100%" cellspacing="2" cellpadding="0">
<tr><td class="bodymd">Email:<br /><input type="text" name="email" size="40"/></td></tr>
<tr><td><input type="submit" name="submit" value="Email My Login Info"/></td></tr>
</table>
</form>
<?php
	}
	else if($ax=="remindmail")
	{
		$msg="";
		$result=MySQLQuery("select * from links where email='$email'",$QueryError_Email,$QueryError_Browser);
		if(mysql_num_rows($result))
		{
			while($row=mysql_fetch_array($result))
			{
				$name=$row["name"];
				$url=$row["url"];
				$email=$row["email"];
				$password=$row["password"];
				$msg="$msg Login Information for $name is as follows:\n URL: $url\n Email: $email\n Password: $password\n\n";
			}
			$msg="$msg Thank you for using $Site_Name";
			mail($email, "Login information from ".$GLOBALS["Site_Name"], $msg, "From: ".$GLOBALS["NoReply_Mail"]);
			Echo "<p class=bodylgbold>Email Sent</p><p class=bodymd>Your Login Information has been sent to $email.</p>";
		}
		else
		{
			echo "<p class=\"bodylgbold\">No Records Found</p><p class=\"bodymd\">Sorry, no records were found registered to $email.</p>";
		}			
	}

//////////////////
// Main Front Page
//////////////////

	else
	{
		$result = MySQLQuery("SELECT id,sub_cat,name FROM links_cat WHERE sub_cat='0' ORDER BY name ASC", $QueryError_Email,$QueryError_Browser);
		$count=0;
		if(mysql_num_rows($result))
		{
			echo "<table cellpadding=\"0\" cellspacing=\"0\" style=\"border-style:none;width:100%;\">\n";
			while($row=mysql_fetch_array($result))
			{
				$count=$count+1;
				$id=$row["id"];
				$sub_cat=$row["sub_cat"];
				$name=$row["name"];
				$dsc=$row["dsc"];
				$link_count = MySQLQuery("SELECT * FROM links WHERE cat_id='".$id."' AND approved='yes' ORDER BY name ASC", $QueryError_Email,$QueryError_Browser);
				$link_count=mysql_num_rows($link_count);
				if($link_count==1){$link_text="link";}else{$link_text="links";}
				if($count <= $front_cols && $count==1){echo "<tr>\n<td align=\"left\" valign=\"top\" style=\"width:$front_perc;\">\n";}
				else if($count < $front_cols && $count > 1){echo "<td class=\"bodymd\">&nbsp;&nbsp;</td>\n<td align=\"left\" valign=\"top\" style=\"width:$front_perc;\">\n";}
				else if($count==$front_cols){echo "<td class=\"bodymd\">&nbsp;&nbsp;</td>\n<td align=\"left\" valign=\"top\" style=\"width:$front_perc;\">\n";}
				echo "<span class=\"bodymdbold\"><a href=\"$PHP_SELF?ax=list&amp;sub=$id&amp;cat_id=$id\">$name ($link_count $link_text)</a></span><br />\n";
				$result2 = MySQLQuery("SELECT id,sub_cat,name FROM links_cat WHERE sub_cat='$id' ORDER BY name ASC", $QueryError_Email,$QueryError_Browser);
				if(mysql_num_rows($result2))
				{
					echo "<ul class=\"bodysm\">";
					while($row2=mysql_fetch_array($result2))
					{
						$id2=$row2["id"];
						$sub_cat2=$row2["sub_cat"];
						$name2=$row2["name"];
						$link_count = MySQLQuery("SELECT * FROM links WHERE cat_id='".$id2."' AND approved='yes' ORDER BY name ASC", $QueryError_Email,$QueryError_Browser);
						$link_count=mysql_num_rows($link_count);
						if($link_count==1){$link_text="link";}else{$link_text="links";}
						echo "<li><a href=\"$PHP_SELF?ax=list&amp;sub=$sub_cat2&amp;cat_id=$id2\">$name2 ($link_count $link_text)</a></li>\n";
					}
					echo "</ul>";
				}
				if($count <= $front_cols && $count==1){echo "<br /><br />\n</td>\n";}
				else if($count < $front_cols && $count > 1){echo "<br /><br />\n</td>\n";}
				else if($count==$front_cols){echo "<br /><br />\n</td>\n";}
				if($count==$front_cols){$count=0;}
			}
			if($count < $front_cols)
			{
				$spacer=($front_cols - $count) * 2;
				echo "\n<td class=\"bodymd\" colspan=\"$spacer\">&nbsp;&nbsp;</td>\n";
			}
			echo "</tr>\n</table>\n";
		}
		else
		{
			echo "<p class=\"bodymd\">No categories found</p>";
		}
	}
	include('footer.php');
	ob_end_flush();
?>
