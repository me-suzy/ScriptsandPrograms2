<?
$pagetitle = "Category Admin";
include("cn_auth.php");
include("cn_head.php");

### If user is not a "Category Admin" or an "Ultimate Admin", do not grant access
if($useri[cats] != "on" && $useri[admin] != "on") {
	print E("You are not allowed to edit categories");
}

if(isset($_REQUEST['op'])) {
	if($_REQUEST['op'] == "add") {
		if($_POST['go'] == "true") {
			### Add a new category into DB
			$name = addslashes($_POST[name]);
			$q[infoc] = mysql_query("SELECT id FROM $t_cats", $link) or E( "Could not retrieve category info:<br>" . mysql_error());
			$q[add] = mysql_query ("INSERT INTO $t_cats (id, name, date) VALUES ('', '$name', '$now')", $link) or E("Could not insert category:<br>" . mysql_error());
			$num = mysql_num_rows($q[infoc]);
			if($num == "0") {
				$q[info] = mysql_query("SELECT * FROM $t_cats WHERE name='$name'", $link) or E( "Could not retrieve category info:<br>" . mysql_error());
				$rc = mysql_fetch_array($q[info]);
				$q[move] = mysql_query ("UPDATE $t_news SET cat='$rc[id]'", $link) or E("Could not move news to new category:<br>" . mysql_error());
				echo S("New category has been added, and all news posts have been moved into it");
			} else {
				echo S("New category has been added");
			}
			exit;
		}
	### Set variables for adding
	$button_txt = "Add Category";
	} elseif($_REQUEST['op'] == "edit") {
		if($_POST['go'] == "true") {
			### Save changes into DB
			$name= addslashes($_POST[name]);
			$q[update] = mysql_query("UPDATE $t_cats SET name='$name' WHERE id = '$id'", $link) or E("Could not update category:<br>" . mysql_error());
			echo S("Category has been edited");
			exit;
		}
	### Set variables for editing
	$button_txt = "Save Category";
	$q[edit] = mysql_query("SELECT * FROM $t_cats WHERE id = '$id'", $link) or E("Couldn't retieve category info:<br>" . mysql_error());
	$ev = mysql_fetch_array($q[edit]);
	} elseif($_REQUEST['op'] == "del") {
	
		if($_POST['go'] == "true") {
			### Delete category record
			$q[del2] = mysql_query("DELETE FROM $t_cats WHERE id = '$id'", $link) or E("Couldn't delete category:<br>" . mysql_error());
			echo S("Category has been deleted");
			exit;
		}
	
		/*
		$q[infoc] = mysql_query("SELECT id FROM $t_news WHERE cat = '$id'", $link) or E( "Could not retrieve news info:<br>" . mysql_error());
		$num = mysql_num_rows($q[infoc]);
		
		if($num != "0") {
		echo S("Category has been deleted, and all news posts have been moved to the selected category");
		} else {
		echo S("New category has been added");
		}
		*/
		
		$q[del] = mysql_query("SELECT name FROM $t_cats WHERE id = '$id'", $link) or E("Couldn't select category:<br>" . mysql_error());
		$dv = mysql_fetch_array($q[del], MYSQL_ASSOC);
		?>
		<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
		Are you sure you want to delete "<b><?=$dv[name]?></b>"?<br><br>
		<input type="hidden" name="op" value="<?=$op?>">
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="go" value="true">
		<input type="submit" name="submit" value="Yes" class="input">&nbsp;&nbsp;<input type="button" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>'" value="No" class="input">
		<?
		exit;
	}
	$q[info] = mysql_query("SELECT * FROM $t_cats ORDER BY date ASC", $link) or E( "Couldn't select category:<br>" . mysql_error());
	$num = mysql_num_rows($q[info]);
	?>
	
	<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
	<table  width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
	<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
	Name:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="text" name="name" size="25" class="input" value="<?=stripslashes($ev[name])?>">
	</td></tr>
	<? if($num == "0") { ?>
		<tr><td colspan="2">
		<blockquote>
		Note:  If you have already posted news items without a category, creating this first category will cause all previously posted news items to be moved under this category.
		</blockquote>
		</td></tr>
	<? } ?>
	<tr><td bgcolor="<? print $MenuBg2; ?>">&nbsp;
	
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="hidden" name="op" value="<?=$op?>">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="num" value="<?=$num?>">
	<input type="hidden" name="go" value="true">
	<input type="submit" name="submit" value="<?=$button_txt?>" class="input">&nbsp;&nbsp;
	<input type="button" name="cancel" value="Cancel" class="input" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>'">
	</td></tr>
	</table><br>
	
	<?
} else {
	
	$q[info] = mysql_query("SELECT * FROM $t_cats ORDER BY date ASC", $link) or E( "Couldn't select category:<br>" . mysql_error());
	$num = mysql_num_rows($q[info]);
	print "<b>$num</b> Found<br><br><a href=\"?op=add\">[ Add Category ]</a> or click on a category below to edit";
	if($num == "0") {
		print "<br><br>You currently have no categories for your news";
	}
	?>
	<table border="0" cellpadding="1" cellspacing="1" width="100%" align="center">
	<?
	$i=1;
	while ($r = mysql_fetch_array($q[info], MYSQL_ASSOC)) {
		?>
		<tr><td><?=$i?>)</td><td bgcolor="#EEEEEE" width="70%">&nbsp;<a href="?op=edit&id=<?=$r[id]?>"><b><? echo cn_cutstr($r[name]); ?></b></a> <? echo "(ID: $r[id])"; ?></td><td><a href="?op=del&id=<?=$r[id]?>">[Delete]</a></td></tr>
		<?
		$i++;
	}
	?>
	</table><br>
	
	<?
}
include("cn_foot.php");
?>