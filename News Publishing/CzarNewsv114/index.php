<?
$pagetitle = "News Admin";
include("cn_auth.php");
include("cn_head.php");

if(isset($_REQUEST['op'])) {

	if($_REQUEST['op'] == "add") {
		if($_POST['go'] == "true") {
			### Add a new news into DB
			$subject = cn_htmltrans($_POST['subject'],'text');
			$content = cn_htmltrans($_POST['content'],'text');
			$content2 = cn_htmltrans($_POST['content2'],'text');
			if(!isset($_POST[cat])) { $_POST[cat] = 0; }
			if(empty($_POST[subject]) || empty($_POST[content])) { print E("You have not filled in a subject and/or content for your news post"); }
			if(!in_array($_POST[cat], $ucats) && $useri[categories] != "all") { print E("You are not allowed to add news posts out of your category permisions"); }
			$q[add] = mysql_query ("INSERT INTO $t_news (id, author, cat, subject, content, content2, sumstory, date, source, sourceurl) VALUES ('', '$useri[id]', '$_POST[cat]', '$subject', '$content', '$content2', '$_POST[sumstory]', '$now', '$_POST[source]', '$_POST[sourceurl]')", $link) or E("Could not insert news:<br />" . mysql_error());
			
			echo S("New news has been added");
			exit;
		}
		### Set variables for adding
		$button_txt = "Add News";
	
	} elseif($_REQUEST['op'] == "edit") {
	
		$q[edit] = mysql_query("SELECT * FROM $t_news WHERE id = '$id'", $link) or E("Couldn't retieve news info:<br />" . mysql_error());
		$ev = mysql_fetch_array($q[edit], MYSQL_ASSOC);
		if(!in_array($ev[cat], $ucats) && $useri[categories] != "all") { print E("You are not allowed to edit news posts out of your category permisions"); }
		if($useri[news] != "on" && $useri[id] != $ev[author]) { print E("You are only allowed to edit your own news items"); }
		
		if($_POST['go'] == "true") {
			// Check if subject and content is filled-in 
			if(empty($_POST[subject]) || empty($_POST[content])) { print E("You have not filled in a subject and/or content for your news post"); }
			### Save changes into DB
			$subject = cn_htmltrans($_POST['subject'],'text');
			$content = cn_htmltrans($_POST['content'],'text');
			$content2 = cn_htmltrans($_POST['content2'],'text');
			$q[update] = mysql_query ("UPDATE $t_news SET cat='$_POST[cat]', subject='$subject', content='$content', content2='$content2', sumstory='$_POST[sumstory]', source='$_POST[source]', sourceurl='$_POST[sourceurl]' WHERE id = '$_POST[id]'", $link) or E("Could not update news:<br />" . mysql_error());
			echo S("News has been edited");
			exit;
		}
		### Set variables for editing
		$button_txt = "Save News";
		
	} elseif($_REQUEST['op'] == "del") {
	
		### Fetch info about record
		$q[del] = mysql_query("SELECT * FROM $t_news WHERE id = '$_REQUEST[id]'", $link) or E("Couldn't select news:<br />" . mysql_error());
		$dv = mysql_fetch_array($q[del], MYSQL_ASSOC);
		if(!in_array($dv[cat], $ucats) && $useri[categories] != "all") { print E("You are not allowed to delete news posts out of your category permisions"); }
		if($useri[news] != "on" && $useri[id] != $dv[author]) { print E("You are only allowed to delete your own news items"); }
		if($_POST['go'] == "true") {
			### Delete news record
			$q[del] = mysql_query("DELETE FROM $t_news WHERE id = '$_POST[id]'", $link) or E("Couldn't delete news:<br />" . mysql_error());
			$q[del2] = mysql_query("DELETE FROM $t_coms WHERE news_id = '$_POST[id]'", $link) or E("Couldn't delete comments:<br />" . mysql_error());
			echo S("News item has been deleted");
			exit;
		}
		?>
		<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
		Are you sure you want to delete "<b><?=$dv[subject]?></b>"?<br /><br />
		<input type="hidden" name="op" value="<? print $_REQUEST['op']; ?>">
		<input type="hidden" name="id" value="<? print $_REQUEST['id']; ?>">
		<input type="hidden" name="go" value="true">
		<input type="submit" name="submit" value="Yes" class="input">&nbsp;&nbsp;<input type="button" onClick="javascript:location.href='<? print $_SERVER['PHP_SELF']; ?>'" value="No" class="input">
		<?
		exit;
	
	### Do an action to multiple items
	} elseif($_REQUEST['op'] == "multi") {
	
		if(count($_POST['item']) <= "0") { print E("You must check at least one item"); }
		
		if($_POST['mode'] == "move") {
			if($_POST['go'] == "true") {
				foreach($_POST['item'] as $mid => $t) {
					$q[upd] = mysql_query("SELECT * FROM $t_news WHERE id = '$mid'", $link) or E("Couldn't select news:<br />" . mysql_error());
					$ev = mysql_fetch_array($q[upd], MYSQL_ASSOC);
					// Check the category against user permissions
					if(!in_array($ev[cat], $ucats) && $useri[categories] != "all") {
						$movs .= "$t <b>[NOT MOVED - INSUFFICIENT CATEGORY PERMISSIONS]</b><br />\n";
					} elseif($useri[news] != "on" && $useri[id] != $ev[author]) {
						$movs .= "$t <b>[NOT MOVED - INSUFFICIENT USER PERMISSIONS]</b><br />\n";
					} else {
						$q[del2] = mysql_query("UPDATE $t_news SET cat = '$_POST[cat]' WHERE id = '$mid'", $link) or E("Couldn't move news:<br />" . mysql_error());
						$movs .= "$t<br />\n";
					}
				}
				### Delete news record
				echo S("The following news items have been moved:<br /><br />" . $movs);
				exit;
			}
			?>
			<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
			Which category do you want to move these items to?<br /><br />
			
			<table  width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tr><td bgcolor="<? print $MenuBg2; ?>">
			Category:
			</td><td bgcolor="<? print $MenuBg1; ?>">
			<? print cn_catBox("cat","$ev[cat]"); ?>
			</td></tr>
			<tr><td colspan="2">
			<?
			foreach($_POST['item'] as $iid => $t) {
				print "<b>$t</b><br /><input type=\"hidden\" name=\"item[$iid]\" value=\"$t\">\n";
			}
			?>
			</td></tr>
			</table>
			<br />
			<input type="hidden" name="op" value="<? print $_POST['op']; ?>">
			<input type="hidden" name="mode" value="<? print $_POST['mode']; ?>">
			<input type="hidden" name="go" value="true">
			<input type="submit" name="submit" value="Move Items" class="input">&nbsp;&nbsp;<input type="button" onClick="javascript:location.href='<? print $_SERVER['PHP_SELF']; ?>'" value="Cancel" class="input">
			<?
			exit;
		
		} elseif($_POST['mode'] == "del") {
			if($_POST['go'] == "true") {
				foreach($_POST['item'] as $did => $t) {
					$q[del] = mysql_query("SELECT * FROM $t_news WHERE id = '$did'", $link) or E("Couldn't select news:<br />" . mysql_error());
					$dv = mysql_fetch_array($q[del], MYSQL_ASSOC);
					// Check the category against user permissions
					if(!in_array($dv[cat], $ucats) && $useri[categories] != "all") {
						$dels .= "$t <b>[NOT DELETED - INSUFFICIENT CATEGORY PERMISSIONS]</b><br />\n";
					} elseif($useri[news] != "on" && $useri[id] != $dv[author]) {
						$dels .= "$t <b>[NOT DELETED - INSUFFICIENT USER PERMISSIONS]</b><br />\n";
					} else {
						$q[del] = mysql_query("DELETE FROM $t_news WHERE id = '$did'", $link) or E("Couldn't delete news:<br />" . mysql_error());
						$q[del2] = mysql_query("DELETE FROM $t_coms WHERE news_id = '$did'", $link) or E("Couldn't delete comments:<br />" . mysql_error());
						$dels .= "$t<br />\n";
					}
				}
				### Delete news record
				echo S("The following news items have been deleted:<br /><br />" . $dels);
				exit;
			}
			?>
			<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
			Are you sure you want to delete these items?<br /><br />
			<?
			foreach($_POST['item'] as $id => $t) {
				print "<b>$t</b><br /><input type=\"hidden\" name=\"item[$id]\" value=\"$t\">\n";
			}
			?>
			<br />
			<input type="hidden" name="op" value="<? print $_POST['op']; ?>">
			<input type="hidden" name="mode" value="<? print $_POST['mode']; ?>">
			<input type="hidden" name="go" value="true">
			<input type="submit" name="submit" value="Yes" class="input">&nbsp;&nbsp;<input type="button" onClick="javascript:location.href='<? print $_SERVER['PHP_SELF']; ?>'" value="No" class="input">
			<?
			exit;
		}
	}
	?>
	
	<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
	<table  width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
	<tr><td bgcolor="<? print $MenuBg2; ?>">
	Author:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<b><? if(empty($ev[author])) { print $useri[user]; } else { print cn_getinfo($ev[author]); } ?></b>
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">
	Category:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<? print cn_catBox("cat","$ev[cat]"); ?>
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">
	Subject:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="text" name="subject" size="40" class="input" value="<? echo cn_htmltrans($ev[subject],'html'); ?>" />
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">
	Content:<br />
	<a href="javascript:popBox('cn_images.php?display=gallery&field=content');" class="small">Insert Images</a>
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<textarea cols="50" rows="6" name="content" class="input"><? echo cn_htmltrans($ev[content],'html'); ?></textarea>
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">
	Entire Story:<br />
	<a href="javascript:popBox('cn_images.php?display=gallery&field=content2');" class="small">Insert Images</a>
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="checkbox" name="sumstory" id="sumstory" onClick="openBox(1);"<? if($ev[sumstory] == "on") { print " CHECKED"; } ?>> <label for="sumstory">Use summary/entire story feature</label><br />
	<div id="1" style="display: <? if($ev[sumstory] == "on") { print "block"; } else { print "none"; } ?>">
	<textarea cols="50" rows="6" name="content2" class="input"><? echo cn_htmltrans($ev[content2],'html'); ?></textarea>
	</div>
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">&nbsp;
	
	</td><td bgcolor="<? print $MenuBg1; ?>">
	If you got your news from somewhere else, you can cite the source below.  Otherwise, leave both fields blank.
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
	Source Name:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="text" name="source" size="30" class="input" value="<? echo cn_htmltrans($ev[source],'html')?>" />
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">
	Source URL:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="text" name="sourceurl" size="40" class="input" value="<?=$ev[sourceurl]?>" />
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">&nbsp;
	
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="hidden" name="op" value="<?=$op?>" />
	<input type="hidden" name="id" value="<?=$id?>" />
	<input type="hidden" name="m" value="<?=$m?>" />
	<input type="hidden" name="go" value="true" />
	<input type="submit" name="submit" value="<?=$button_txt?>" class="input" />&nbsp;&nbsp;
	<input type="button" name="cancel" value="Cancel" class="input" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>'" />
	</td></tr>
	</table><br />
	</form>
	
	<?
} else {
	// Set limits for multiple pages
	if(!isset($pg)) { $pg = 1; }
	// Number of news items to display per page
	$pgset = "25";
	$lims = ($pg-1)*$pgset;
	
	if($useri[news] != "on") {
		$t_news .= " WHERE author = '$useri[id]'";
	}
	
	$q[info] = mysql_query("SELECT * FROM $t_news ORDER BY date DESC LIMIT $lims, $pgset", $link) or E("Couldn't select news:<br />" . mysql_error());
	// Count news posts that current user has access to
	$newsnum = 0;
	$q[ncount] = mysql_query("SELECT id,cat FROM $t_news", $link) or E("Couldn't count news:<br />" . mysql_error());
	while ($num = mysql_fetch_array($q[ncount], MYSQL_ASSOC)) {
		if(in_array($num[cat], $ucats) || $useri[categories] == "all") { $newsnum++; }
	}
	print "<b>$newsnum</b> News Article(s) Found<br /><br /><a href=\"?op=add\">[ Add News ]</a> or click on a news article below to edit";
	if($newsnum == "0") {
		print "<p>No news found in database, or none have been found that you have posted</p>";
	}
	?>
	<table border="0" cellpadding="1" cellspacing="1" width="100%" align="center">
	<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
	<?
	$i=$lims+1;
	while ($r = mysql_fetch_array($q[info], MYSQL_ASSOC)) {
		if(in_array($r[cat], $ucats) || $useri[categories] == "all") {
		$q[cinfo] = mysql_query("SELECT name FROM $t_cats WHERE id='$r[cat]'", $link);
		$inf = mysql_fetch_array($q[cinfo], MYSQL_ASSOC);
		?>
		<tr>
		<td bgcolor="#EEEEEE"><input type="checkbox" name="item[<?=$r[id]?>]" value="<?=cn_cutstr($r[subject])?>" /></td>
		<td bgcolor="#EEEEEE" width="60%">&nbsp;<a href="?op=edit&id=<?=$r[id]?>"><b><? echo cn_cutstr($r[subject]); ?></b></a></td>
		<td bgcolor="#EEEEEE">&nbsp;<?=$inf[name]?>&nbsp;</td>
		<td bgcolor="#EEEEEE" width="20%" align="right" nowrap>&nbsp;<? echo date("M d, Y",$r[date]); ?>&nbsp;</td>
		<td><a href="?op=del&id=<?=$r[id]?>">[Delete]</a></td>
		</tr>
		<?
		$i++;
		}
	}
	?>
	</table>
	<? if($newsnum != "0") { ?>
	<table border="0" cellpadding="0" cellspacing="2">
	<tr><td>
	With Selected:&nbsp;&nbsp;
	</td><td>
	<input type="hidden" name="op" value="multi" />
	<input type="hidden" name="m" value="<?=$m?>" />
	<select name="mode">
	<option value="move">Move</option>
	<option value="del">Delete</option>
	</select>
	</td><td>
	<input type="submit" name="submit" value="Submit" class="input" />
	</td></tr>
	</table>
	<? } ?>
	<div align="center">
	<?
	### Page numbering code
	if ($newsnum > $pgset) {
		if ($pg != "1") {
			$pgn = $pg-1;
			print "<a href=\"?pg=$pgn\">";
			print "&lt;&lt; Prev";
			print "</a>&nbsp;&nbsp;";
		}
		
		$totalpages = ceil($newsnum / $pgset);
		for ($loop = 1; ;$loop++) {
			if ($loop > $totalpages) {
				break;
			}
			if ($loop == $pg) {
				print "<b>$loop</b>";
				print "&nbsp;&nbsp;";
			} else {
				print "<b><a href=\"$_SERVER[PHP_SELF]?pg=$loop\">";
				print $loop;
				print "</a></b>";
				print "&nbsp;&nbsp;";
			}
		}
	}
	
	if ($pg < $totalpages) {
		$pgn = $pg+1;
		print "<a href=\"?pg=$pgn\">";
		print "Next &gt;&gt;";
		print "</a>";
	}
	?>
	</div>
	</form>
	<?
}
include("cn_foot.php");
?>