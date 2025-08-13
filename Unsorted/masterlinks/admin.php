<?php
////////////////////////////////////////////////////////////////////////////
// db Masters' Links Directory 3.0, Copyright (c) 2003 db Masters Multimedia
// Content Manager comes with ABSOLUTELY NO WARRANTY
// Licensed under the AGPL
// See license.txt and readme.txt for details
////////////////////////////////////////////////////////////////////////////
	ob_start();
	include('config.php');
	include('header.php');
	$ax=$_GET["ax"];

/////////////////////
// Table Installation
/////////////////////

if($HTTP_COOKIE_VARS["admin_log"]=="in")
{
	echo "<p class=\"bodymd\">[ <a href=\"".$PHP_SELF."\">Link Admin Home</a> | <a href=\"".$PHP_SELF."?ax=addcat\">Add a Category</a> | <a href=\"".$PHP_SELF."?ax=add\">Add a Link</a> | <a href=\"".$PHP_SELF."?ax=logout\">Logout</a> | <a href=\"".$PHP_SELF."?ax=install\">Install Links</a> ]</p>";
	if($ax=="install")
	{
		if($type=="un")
		{
			$result=MySQLQuery("DROP TABLE 'links_cat'",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The links category database table was dropped</p>";}
			else
			{echo "<p class=\"bodymd\">Error dropping the links category database table</p>";}
			$result=MySQLQuery("DROP TABLE 'links'",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The links database table was dropped</p>";}
			else
			{echo "<p class=\"bodymd\">Error dropping the links database table</p>";}
		}
		else if($type=="full")
		{
			$result=MySQLQuery("CREATE TABLE links_cat(id int(10) auto_increment primary key not null,sub_cat int(10),name varchar(24),dsc varchar(255));",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The links category database table was created</p>";}
			else
			{echo "<p class=\"bodymd\">Error creating the links category database table</p>";}
			$result=MySQLQuery("CREATE TABLE links(id int(10) auto_increment primary key not null,cat_id int(10),name varchar(100),url varchar(100),dsc varchar(255),approved varchar(3),email varchar(50),password varchar(10),clicks int(10),date_added date,image varchar(100),rating int(255),rates int(255));",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The links database table was created</p>";}
			else
			{echo "<p class=\"bodymd\">Error creating the links database table</p>";}
		}
		else if($type=="upgrade2")
		{
			$result=MySQLQuery("ALTER TABLE links add(clicks int(10));",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The links database table was updated to version 2</p>";}
			else
			{echo "<p class=\"bodymd\">Error updating the links database table to version 2</p>";}
		}
		else if($type=="upgrade3")
		{
			$result=MySQLQuery("ALTER TABLE links_cat add(sub_cat int(10));",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The link categories database table was updated to version 3</p>";}
			else
			{echo "<p class=\"bodymd\">Error updating the link categories database table to version 3</p>";}
			$result=MySQLQuery("ALTER TABLE links add(date_added date,image varchar(100),rating int(255),rates int(255));",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The links database table was updated to version 3</p>";}
			else
			{echo "<p class=\"bodymd\">Error updating the links database table to version 3</p>";}
			$date_added=date($date_format);
			$result=MySQLQuery("update links set date_added='$date_added';",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The current links \"date added\" field was set to today</p>";}
			else
			{echo "<p class=\"bodymd\">Error updating the current links \"date added\" field to today</p>";}
			$result=MySQLQuery("update links_cat set sub_cat='0';",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{echo "<p class=\"bodymd\">The current links category \"sub-category field\" field was set to 0, as needed by version 3.</p>";}
			else
			{echo "<p class=\"bodymd\">Error updating the current links category \"sub-category field\" field</p>";}
		}
		else
		{
			echo "<p class=\"bodymdbold\">Select the type of install you require:</p>";
			echo "<p class=\"bodymd\"><a href=\"$PHP_SELF?ax=install&amp;type=un\">Full <b>UN</b>install of Version 3 (drops database tables - no way to recover)</a></p>";
			echo "<p class=\"bodymd\"><a href=\"$PHP_SELF?ax=install&amp;type=full\">Full install of Version 3</a></p>";
			echo "<p class=\"bodymd\"><a href=\"$PHP_SELF?ax=install&amp;type=upgrade2\">Upgrade from 1.x to 2.x</a></p>";
			echo "<p class=\"bodymd\"><a href=\"$PHP_SELF?ax=install&amp;type=upgrade3\">Upgrade from 2.x to 3.x</a></p>";
		}
	}
	else if($ax=="logout")
	{
		setcookie("admin_log");
		header("Location: ".$PHP_SELF);
	}

//////////////////////
// Category Management
//////////////////////

	else if($ax=="updatecat")
	{ 
		$id=$_GET["id"];
		$sub_cat=$_POST["sub_cat"];
		$name=$_POST["name"];
		$dsc=$_POST["dsc"];
		$result=MySQLQuery("update links_cat set sub_cat='$sub_cat',name='$name', dsc='$dsc' where id=$id",$QueryError_Email,$QueryError_Browser);
		if ($result)
		{
			echo "<p class=\"bodylgbold\">The Link Category Was Updated</p>";
			ob_end_clean();
			header("Location: ".$PHP_SELF);
		}
		else
		{echo "<p class=\"bodylgbold\">Error Updating Link Category</p>";}
	}
	else if($ax=="insertcat")
	{
		$sub_cat=$_POST["sub_cat"];
		$name=$_POST["name"];
		$dsc=$_POST["dsc"];
		$result=MySQLQuery("insert into links_cat (sub_cat,name,dsc) values ('$sub_cat','$name','$dsc')",$QueryError_Email,$QueryError_Browser);
		if ($result)
		{
			echo "<p class=\"bodylgbold\">The Link Category Was Added</p>";
			ob_end_clean();
			header("Location: ".$PHP_SELF);
		}
		else
		{echo "<p class=\"bodylgbold\">Error Adding Link Category</p>";}
	}
	else if($ax=="deletecat")
	{
		$id=$_GET["id"];
		$v=$_GET["v"];
		if($v=="yes")
		{
			$result=MySQLQuery("delete from links_cat where id=$id",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{
				echo "<p class=\"bodylgbold\">The Link Category Was Deleted</p>";
				ob_end_clean();
				header("Location: ".$PHP_SELF);
			}
			else
			{echo "<p class=\"bodylgbold\">Error Deleting Link Category</p>";}
		}
		else
		{
			echo "<p class=\"bodylgbold\">Are you Sure?</p>\n<p class=\"bodymd\">If you are sure you want to delete this category, <a href=\"$PHP_SELF?ax=deletecat&amp;id=$id&amp;v=yes\">click here</a>.</p>";
		}
	}
	else if($ax=="editcat" || $ax=="addcat")
	{
		if($ax=="editcat")
		{
			$id=$_GET["id"];
			$result=MySQLQuery("select * from links_cat where id=$id",$QueryError_Email,$QueryError_Browser);
			while($row=mysql_fetch_array($result))
			{
				$id=$row["id"];
				$sub_cat=$row["sub_cat"];
				$name=$row["name"];
				$dsc=$row["dsc"];
			}
			$form_title="Edit a Link Category";
			$form_action="?ax=updatecat&id=$id";
		}
		else if($ax=="addcat")
		{
			$name="";
			$dsc="";
			$form_title="Add a Link Category";
			$form_action="?ax=insertcat";
		}
?>
<p class="bodylgbold"><?php echo $form_title; ?></p>
<form action="<?php echo $form_action; ?>" method="post" id="form" onsubmit="return validate_cat();">
<table border="0" width="100%" cellspacing="2" cellpadding="0">
	<td class="bodymd"><span style="color:#FF0000;">*</span>Category Level:<br />
		<select name="sub_cat">
<?php
	if($ax=="editcat")
	{
		if($sub_cat != 0)
		{
			$result=MySQLQuery("select * from links_cat where id='$sub_cat'",$QueryError_Email,$QueryError_Browser);
			while($row=mysql_fetch_array($result))
			{
				$temp_id=$row["id"];
				$temp_name=$row["name"];
				echo "<option value=\"$temp_id\"> $temp_name</option>";
			}
		}
	}
	echo "<option value=\"0\"> Top-Level Category</option>";
	$result=MySQLQuery("select * from links_cat where sub_cat='0' order by name",$QueryError_Email,$QueryError_Browser);
	while($row=mysql_fetch_array($result))
	{
		$temp_id=$row["id"];
		$temp_name=$row["name"];
		echo "<option value=\"$temp_id\"> $temp_name</option>";
	}
?>
		</select>
	</td>
</tr>
<tr><td class="bodymd"><span style="color:#FF0000;">*</span>Category Name:<br /><input type="text" name="name" size="24" value="<?php echo $name; ?>"/></td></tr>
<tr><td class="bodymd"><span style="color:#FF0000;">*</span>Category Description:<br /><textarea name="dsc" rows="3" cols="40"><?php echo $dsc; ?></textarea></td></tr>
<tr><td class="bodymd"><span style="color:#FF0000;">*</span> - indicates required field</td></tr>
<tr><td><input type="submit" name="submit" value="<?php echo $form_title; ?>"/></td></tr>
</table>
</form>
<?php
	}

//////////////////
// Link Management
//////////////////

	else if($ax=="update")
	{ 
		$id=$_GET["id"];
		$cat_id=$_POST["cat_id"];
		$name=$_POST["name"];
		$dsc=$_POST["dsc"];
		$url=$_POST["url"];
		$approval=$_POST["approval"];
		$email=$_POST["email"];
		$password=$_POST["password"];
		$clicks=$_POST["clicks"];
		$date_added=$_POST["date_added"];
		$image=$_POST["image"];
		$rating=$_POST["rating"];
		$rates=$_POST["rates"];
		$result=MySQLQuery("update links set cat_id='$cat_id', name='$name', dsc='$dsc', url='$url', approved='$approval', email='$email', password='$password',date_added='$date_added',image='$image',rating='$rating',rates='$rates' where id=$id",$QueryError_Email,$QueryError_Browser);
		if ($result)
		{
			echo "<p class=\"bodylgbold\">The Link Was Updated</p>";
			ob_end_clean();
			header("Location: ".$PHP_SELF);
		}
		else
		{echo "<p class=\"bodylgbold\">Error Updating Link</p>";}
	}
	else if($ax=="insert")
	{
		$cat_id=$_POST["cat_id"];
		$name=$_POST["name"];
		$dsc=$_POST["dsc"];
		$url=$_POST["url"];
		$approval=$_POST["approval"];
		$email=$_POST["email"];
		$password=$_POST["password"];
		$clicks="0";
		$date_added=$_POST["date_added"];
		$image=$_POST["image"];
		$rating="0";
		$rates="0";
		$result=MySQLQuery("insert into links (cat_id,name,dsc,url,approved,email,password,date_added,image,rating,rates) values ('$cat_id','$name','$dsc','$url','$approval','$email','$password','$date_added','$image','$rating','$rates')",$QueryError_Email,$QueryError_Browser);
		if ($result)
		{
			echo "<p class=\"bodylgbold\">The Link Was Added</p>";
			ob_end_clean();
			header("Location: ".$PHP_SELF);
		}
		else
		{echo "<p class=\"bodylgbold\">Error Adding Link</p>";}
	}
	else if($ax=="delete")
	{
		$id=$_GET["id"];
		$v=$_GET["v"];
		if($v=="yes")
		{
			$result=MySQLQuery("delete from links where id=$id",$QueryError_Email,$QueryError_Browser);
			if ($result)
			{
				echo "<p class=\"bodylgbold\">The Link Was Deleted</p>";
				ob_end_clean();
				header("Location: ".$PHP_SELF);
			}
			else
			{echo "<p class=\"bodylgbold\">Error Deleting Link</p>";}
		}
		else
		{
			echo "<p class=\"bodylgbold\">Are you Sure?</p>\n<p class=\"bodymd\">If you are sure you want to delete this listing, <a href=\"$PHP_SELF?ax=delete&amp;id=$id&amp;v=yes\">click here</a>.</p>";
		}
	}
	else if($ax=="view")
	{
		$result = MySQLQuery("SELECT * FROM links WHERE id=$id", $QueryError_Email, $QueryError_Browser);
		while($row = mysql_fetch_array($result))
		{echo "<p class=\"bodymd\">".$row["name"]."<br />".$row["dsc"]."<br />".$row["email"]."</p>";}
	}
	else if($ax=="edit" || $ax=="add")
	{ 
		if($ax=="edit")
		{
			$id=$_GET["id"];
			$result=MySQLQuery("select * from links where id=$id",$QueryError_Email,$QueryError_Browser);
			while($row=mysql_fetch_array($result))
			{
				$id=$row["id"];
				$cat_id=$row["cat_id"];
				$name=$row["name"];
				$dsc=$row["dsc"];
				$url=$row["url"];
				$approved=$row["approved"];
				$email=$row["email"];
				$password=$row["password"];
				$clicks=$row["clicks"];
				$date_added=$row["date_added"];
				$image=$row["image"];
				$rating=$row["rating"];
				$rates=$row["rates"];
			}
			$form_title="Edit a Link";
			$form_action="$PHP_SELF?ax=update&amp;id=$id";
		}
		else if($ax=="add")
		{
			$name="";
			$dsc="";
			$url="";
			$approved="yes";
			$email="";
			$password="";
			$date_added="";
			$image="";
			$date_added=date($date_format);
			$date_added=date($date_format);
			$date_added=date($date_format);
			$form_title="Add a Link";
			$form_action="$PHP_SELF?ax=insert";
		}
?>
<p class="bodylgbold"><?php echo $form_title; ?></p>
<form action="<?php echo $form_action; ?>" method="post" id="form" onsubmit="return validate_link();">
<table border="0" width="100%" cellspacing="2" cellpadding="0">
<tr>
	<td class="bodymd"><span style="color:#FF0000;">*</span>Category:<br />
		<select name="cat_id">
<?php
	if($ax=="edit")
	{
		$result=MySQLQuery("select * from links_cat where id='$cat_id'",$QueryError_Email,$QueryError_Browser);
		while($row=mysql_fetch_array($result))
		{
			$temp_id=$row["id"];
			$temp_name=$row["name"];
			echo "<option value=\"$temp_id\"> $temp_name</option>";
		}
	}
	$result=MySQLQuery("select * from links_cat where id!='$cat_id' order by name",$QueryError_Email,$QueryError_Browser);
	while($row=mysql_fetch_array($result))
	{
		$temp_id=$row["id"];
		$temp_name=$row["name"];
		echo "<option value=\"$temp_id\"> $temp_name</option>";
	}
?>
		</select>
	</td>
</tr>
<tr><td class="bodymd">Website Name<span style="color:#FF0000;">*</span>:<br /><input type="text" name="name" size="60" value="<?php echo $name; ?>" maxlength="100"/></td></tr>
<tr><td class="bodymd">Website Description<span style="color:#FF0000;">*</span>:<br /><textarea name="dsc" rows="3" cols="40"><?php echo $dsc; ?></textarea></td></tr>
<tr><td class="bodymd">URL<span style="color:#FF0000;">*</span>:<br /><input type="text" name="url" size="60" value="<?php echo $url; ?>" maxlength="100"/></td></tr>
<tr><td class="bodymd">Date Added<span style="color:#FF0000;">*</span>:<br /><input type="text" name="date_added" size="10" value="<?php echo $date_added; ?>"/></td></tr>
<tr><td class="bodymd">Image URL:<br /><input type="text" name="image" size="60" value="<?php echo $image; ?>" maxlength="100"/></td></tr>
<tr>
	<td class="bodymd">Approved:<br />
	<select name="approval">
	<option value="<?php echo $approved; ?>"><?php echo $approved; ?></option>
<?php if($approved!="yes"){ ?>
	<option value="yes">yes</option>
<?php } ?>
<?php if($approved!="no"){ ?>
	<option value="no">no</option>
<?php } ?>
	</select>
	</td>
</tr>
<tr><td class="bodymd">Email:<br /><input type="text" name="email" size="60" value="<?php echo $email; ?>" maxlength="50"/></td></tr>
<tr><td class="bodymd">Password:<br /><input type="password" name="password" size="60" value="<?php echo $password; ?>" maxlength="10"/></td></tr>
<?php if($ax=="edit"){ ?>
<tr><td class="bodymd">Clicks:<br /><input type="text" name="clicks" size="60" value="<?php echo $clicks; ?>" /></td></tr>
<tr><td class="bodymd">Rating:<br /><input type="text" name="rating" size="60" value="<?php echo $rating; ?>" /></td></tr>
<tr><td class="bodymd">Raters:<br /><input type="text" name="rates" size="60" value="<?php echo $rates; ?>" /></td></tr>
<tr><td class="bodymd"><span style="color:#FF0000;">*</span> - indicates required field</td></tr>
<?php } ?>
<tr><td><input type="submit" name="submit" value="<?php echo $form_title; ?>"/></td></tr>
</table>
</form>
<?php
	}

/////////////////////////
// List Links By Category
/////////////////////////

	else if ($ax=="viewlinks")
	{
		echo "<p class=\"bodylgbold\">Link Management</p>";
		$cat_id=$_GET["cat_id"];
		$topic_result = MySQLQuery("SELECT id,sub_cat,name FROM links_cat WHERE id=$cat_id", $QueryError_Email,$QueryError_Browser);
		while($topic_row = mysql_fetch_array($topic_result))
		{
			$topic_id=$topic_row["id"];
			$topic_sub=$topic_row["sub_cat"];
			$topic_name=$topic_row["name"];
			if($topic_sub != 0)
			{
				$topic_result2 = MySQLQuery("SELECT id,sub_cat,name FROM links_cat WHERE id=$topic_sub", $QueryError_Email,$QueryError_Browser);
				while($topic_row2 = mysql_fetch_array($topic_result2))
				{
					$topic_name2=$topic_row2["name"];
				}
				$topic_text=$topic_name2." &gt; ".$topic_name;
			}
			else
			{
				$topic_text=$topic_name;
			}
		}
		$result = MySQLQuery("SELECT * FROM links WHERE cat_id=$cat_id AND approved='yes' ORDER BY id DESC", $QueryError_Email, $QueryError_Browser);
		echo "<table cellpadding=\"2\" cellspacing=\"0\" style=\"width:100%;border-style:none;\">\n
				<tr class=\"rowheader\">\n
				<td class=\"cellheader\" colspan=\"4\">Links listed in \"$topic_text\"</td>\n
				</tr>\n";
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_array($result))
			{
				$count=$count+1;
				if($count%2){$bgclass="primary";}else{$bgclass="secondary";}
				echo "<tr class=\"row$bgclass\">
						<td class=\"cell$bgclass\" style=\"white-space:nowrap;\"><a href=\"".$PHP_SELF."?ax=view&amp;id=".$row["id"]."\">View</a></td>
						<td class=\"cell$bgclass\" style=\"white-space:nowrap;\"><a href=\"".$PHP_SELF."?ax=edit&amp;id=".$row["id"]."\">Edit</a></td>
						<td class=\"cell$bgclass\" style=\"white-space:nowrap;\"><a href=\"".$PHP_SELF."?ax=delete&amp;id=".$row["id"]."\">Delete</a></td>
						<td class=\"cell$bgclass\" style=\"width:100%;\">".$row["name"]."</td>
						</tr>";
			}
		}
		else
		{
			echo "<tr class=\"rowprimary\">
					<td class=\"cellprimary\" colspan=\"4\">No links assigned to this category.</td>
					</tr>";
		}			
		echo "<tr class=\"rowheader\">\n
				<td class=\"cellheader\" colspan=\"4\"></td>\n
				</tr>\n</table>\n";
	}

//////////////////
// Front Page View
//////////////////

	else
	{
		echo "<p class=\"bodylgbold\">Category Management</p>
				<p class=\"bodymd\">To work with individual links, click 'View Links' next to the name of the category that the link is located in. In order to make the \"Delete\" links active you will have to re-categorize any links in that category. If your delete button is not active, it has some child records of some sort.</p>";
		$result = MySQLQuery("SELECT id,sub_cat,name FROM links_cat WHERE sub_cat='0' ORDER BY name DESC", $QueryError_Email, $QueryError_Browser);
		echo "<table cellpadding=\"2\" cellspacing=\"0\" style=\"width:100%;border-style:none;\">\n
				<tr class=\"rowheader\">\n
				<td class=\"cellheader\" colspan=\"5\">Link Catagories</td>\n
				</tr>\n";
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_array($result))
			{
				$id=$row["id"];
				$sub_cat=$row["sub_cat"];
				$name=$row["name"];
				$delete="yes";
				$delete_check = MySQLQuery("SELECT * FROM links_cat WHERE sub_cat=$id", $QueryError_Email, $QueryError_Browser);
				if(mysql_num_rows($delete_check)){$delete="no";}
				$delete_check = MySQLQuery("SELECT * FROM links WHERE cat_id=$id AND approved='yes'", $QueryError_Email, $QueryError_Browser);
				if(mysql_num_rows($delete_check)){$delete="no";}
				if($delete=="no"){$delete="";}else{$delete="<a href=\"$PHP_SELF?ax=deletecat&amp;id=$id\" class=\"adminbtn\">Delete</a>";}
				echo "<tr class=\"rowsecondary\">\n
						<td class=\"cellsecondary\" align=\"left\" valign=\"top\" style=\"white-space:nowrap;\"><a href=\"$PHP_SELF?ax=viewlinks&amp;cat_id=$id\" class=\"adminbtn\">View Links</a></td>\n
						<td class=\"cellsecondary\" align=\"left\" valign=\"top\" style=\"white-space:nowrap;\"><a href=\"$PHP_SELF?ax=editcat&amp;id=$id\" class=\"adminbtn\">Edit</a></td>\n
						<td class=\"cellsecondary\" align=\"left\" valign=\"top\" style=\"white-space:nowrap;\">$delete</td>\n
						<td class=\"cellsecondary\" align=\"left\" valign=\"top\" style=\"width:100%;\">$name</td>\n
						</tr>\n";
				$sub_result = MySQLQuery("SELECT * FROM links_cat WHERE sub_cat='".$row["id"]."' ORDER BY id DESC", $QueryError_Email, $QueryError_Browser);
				if(mysql_num_rows($sub_result))
				{
					while($sub_row = mysql_fetch_array($sub_result))
					{
						$sub_id=$sub_row["id"];
						$sub_sub_cat=$sub_row["sub_cat"];
						$sub_name=$sub_row["name"];
						$delete_check = MySQLQuery("SELECT * FROM links WHERE cat_id='$sub_id' AND approved='yes' ORDER BY name DESC", $QueryError_Email, $QueryError_Browser);
						if(mysql_num_rows($delete_check)){$delete="no";}
						if($delete=="no"){$delete="";}else{$delete="<a href=\"$PHP_SELF?ax=deletecat&amp;id=$sub_id\" class=\"adminbtn\">Delete</a>";}
						echo "<tr class=\"rowprimary\">\n
								<td class=\"cellprimary\" align=\"left\" valign=\"top\" style=\"white-space:nowrap;\"><a href=\"$PHP_SELF?ax=viewlinks&amp;cat_id=$sub_id\" class=\"adminbtn\">View Links</a></td>\n
								<td class=\"cellprimary\" align=\"left\" valign=\"top\" style=\"white-space:nowrap;\"><a href=\"$PHP_SELF?ax=editcat&amp;id=$sub_id\" class=\"adminbtn\">Edit</a></td>\n
								<td class=\"cellprimary\" align=\"left\" valign=\"top\" style=\"white-space:nowrap;\">$delete</td>\n
								<td class=\"cellprimary\" align=\"left\" valign=\"top\" style=\"width:100%;\">&nbsp;&nbsp;&nbsp;$sub_name</td>\n
								</tr>\n";
					}
				}
			}
		}
		else
		{
			echo "<tr class=\"rowprimary\">
					<td class=\"cellprimary\" style=\"white-space:nowrap;\" colspan=\"4\">No Categories currently listed.</td>
					</tr>";
		}			
		echo "<tr class=\"rowheader\">\n
				<td class=\"cellheader\" colspan=\"5\"></td>\n
				</tr>\n</table>\n<br />\n";
		if($admin_approved=="no")
		{
			$result = MySQLQuery("SELECT * FROM links WHERE approved<>'yes' ORDER BY id DESC",$QueryError_Email,$QueryError_Browser);
			echo "<table cellpadding=\"2\" cellspacing=\"0\" style=\"width:100%;border-style:none;\">\n
					<tr class=\"rowheader\">\n
					<td class=\"cellheader\" colspan=\"5\">Links Waiting For Approval</td>\n
					</tr>\n";
			if(mysql_num_rows($result))
			{
				while($row = mysql_fetch_array($result))
				{
					$count=$count+1;
					if($count%2){$bgclass="primary";}else{$bgclass="secondary";}
					echo "<tr class=\"row$bgclass\">
							<td class=\"cell$bgclass\" style=\"white-space:nowrap;\"><a href=\"".$PHP_SELF."?ax=view&amp;id=".$row["id"]."\" class=\"adminbtn\">View</a></td>
							<td class=\"cell$bgclass\" style=\"white-space:nowrap;\"><a href=\"".$PHP_SELF."?ax=edit&amp;id=".$row["id"]."\" class=\"adminbtn\">Edit</a></td>
							<td class=\"cell$bgclass\" style=\"white-space:nowrap;\"><a href=\"".$PHP_SELF."?ax=delete&amp;id=".$row["id"]."\" class=\"adminbtn\">Delete</a></td>
							<td class=\"cell$bgclass\" style=\"width:100%;\">".$row["name"]."</td>
							</tr>";
				}
			}
			else
			{
				echo "<tr class=\"rowprimary\">
						<td class=\"cellprimary\" style=\"white-space:nowrap;\" colspan=\"4\">No records are needing approval.</td>
						</tr>";
			}
			echo "<tr class=\"rowheader\">\n
					<td class=\"cellheader\" colspan=\"5\"></td>\n
					</tr>\n</table>\n";
		}
	}
}
else if($_GET["login"]=="true")
{
	if($pw==$admin_password)
	{
		setcookie("admin_log","in");
		header("Location: ".$PHP_SELF);
	}
	else
	{
		echo "<p class=\"bodylgbold\">Error</p>
				<p class=\"bodymd\">Your have entered the wrong password.</p>";
	}
}
else
{
	echo "<p class=\"bodylgbold\">Login to Administration</p>";
	echo "<form id=\"form\" method=\"post\" action=\"$PHP_SELF?login=true\" onsubmit=\"return validate_admin();\">
			<p class=\"bodymd\"><input type=\"password\" name=\"pw\"/></p>
			<p class=\"bodymd\"><input type=\"submit\" name=\"submit\" value=\"Login\"/></p>
			</form>";
?>
<script type="text/javascript">
<!--
	document.getElementById('form').pw.focus();
//-->
</script>
<?php
}
	include('footer.php');
	ob_end_flush();
?>
