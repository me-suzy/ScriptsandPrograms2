<?php
////////////////////////////////////////////////////////////////////////////
// db Masters' Links Directory 3.0, Copyright (c) 2003 db Masters Multimedia
// Content Manager comes with ABSOLUTELY NO WARRANTY
// Licensed under the AGPL
// See license.txt and readme.txt for details
////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<script src="site.js" type="text/javascript"></script>
<link rel="stylesheet" href="style_<?php echo $style_name; ?>.css" type="text/css" title="style sheet"/>
</head>
<body>
<table cellpadding="5" cellspacing="1" class="template">
<tr>
	<td colspan="2" class="header"><p class="bodyheader"><?php echo $site_title; ?></p></td>
</tr>
<tr>
	<td class="nav" valign="top">
<p class="bodymd"><a href="index.php">Home</a><br />
<?php
		MySQLConnect($ConnError_Email,$ConnError_Browser);
		error_reporting(0);
		$result = MySQLQuery("SELECT * FROM links_cat WHERE sub_cat='0' ORDER BY name ASC", $QueryError_Email,$QueryError_Browser);
		echo "<span class=\"bodysm\">";
		while($row=mysql_fetch_array($result))
		{
			$id=$row["id"];
			$name=$row["name"];
			echo "&nbsp;&nbsp;<a href=\"index.php?ax=list&amp;sub=$id&amp;cat_id=$id\">$name</a><br />";
			if($_GET["sub"]==$id)
			{
				$sub_result = MySQLQuery("SELECT * FROM links_cat WHERE sub_cat=".$_GET["sub"]." ORDER BY name ASC", $QueryError_Email,$QueryError_Browser);
				while($row=mysql_fetch_array($sub_result))
				{
					$sub_id=$row["id"];
					$sub_sub_cat=$row["sub_cat"];
					$sub_name=$row["name"];
					echo "&nbsp;&nbsp;&nbsp;&gt;<a href=\"index.php?ax=list&amp;sub=$sub_sub_cat&amp;cat_id=$sub_id\">$sub_name</a><br />";
				}
			}
		}
		echo "</span>";
?>
</p>
<p class="bodymd"><a href="index.php?ax=list&amp;l=date_added"><?php echo $new; ?> Newest Links</a></p>
<p class="bodymd"><a href="index.php?ax=list&amp;l=clicks"><?php echo $popular; ?> Most Popular Links</a></p>
<p class="bodymd"><a href="index.php?ax=add">Add Your Link</a></p>
<p class="bodymd"><a href="index.php?ax=login">Edit Your Link</a></p>
<form id="search_form" action="index.php?ax=search" method="post">
<table border="0" cellspacing="2" cellpadding="0">
<tr>
	<td class="bodymd"><input type="text" name="search_for" size="10"/></td>
</tr>
<tr>
	<td><input type="submit" name="submit" value="Search"/></td>
</tr>
</table>
</form>
	</td>
	<td class="main" align="left" valign="top">
