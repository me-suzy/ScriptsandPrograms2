<?php
	$path = getcwd();
	chdir('..');
	
	include_once("checksession.php"); 
	include_once "./includes/classes.php";
	include_once "./includes/settings.php";
    chdir($path);
?>
<html>
	<head>
		<title>Create Problem Category</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
		<link href="../style.css" rel="stylesheet" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><td colspan="3">
			<?php
				if ($OBJ->get('navigation') == 'B') {
					$ppath = '../';
					include_once '../dataaccessheader.php';
				}
				else {
					$ppath = '../';	
					include_once "../textnavsystem.php";
				}
			?>
			</td></tr>
			<tr><td height="5"></td></tr>
			
			<tr><th colspan="3">
				Helpdesk Ticket Category Mangement
			</td></tr>
			<tr><td height="5"></td></tr>
			
			<form method="post" action="">
			<?php
				if (isset($_POST['add'], $_POST['catid'])) {
					
					if (!strlen($_POST['catName'])) {
						$error_msg = "Invalid Category Name";
						echo '<input type="hidden" name="catid" value="' . $_POST['cid'] . '" />';
					}
					else {
				    	$c = new Category($_POST['catid']);
				    	
				    	//this only updates the display
				    	$c->set('name', $_POST['catName'], 'mysql_real_escape_string');
				    	$c->commit();
					}
	    		}
	    		else if (isset($_POST['add'])) {
	    			if (!strlen($_POST['catName'])) {
	    				$error_msg = "Invalid Category Name";
	    			}
	    			else {
		    			$c = new Category();
				    	//ok so if we have a priority coming over we will use that as a link
				    	//otherwise default to 1
				    	$c->set('name', $_POST['catName'], 'mysql_real_escape_string');
				    	$pid = isset($_POST['pid']) ? $_POST['pid'] : 1;
				    	$c->set('priority', new Priority($pid));
				    	$c->commit();
	    			}
	    		}
	    		else if (isset($_POST['change'])) {
	    			if (!isset($_POST['cid'])) {
						$error_msg = "Please Select a Category to Edit";
	    			}
	    			else {
	    				echo '<input type="hidden" name="catid" value="' . $_POST['cid'] . '" />';
	    				$c = new Category($_POST['cid']);
	    				$name = $c->get('name', 'stripslashes');
	    			}
	    		}
	    		else if (isset($_POST['link'])) {
	    			if (!isset($_POST['cid']) || !isset($_POST['pid'])) {
	    				$error_msg = "Please Select a Category and a Priority to Link it To";	
	    			}
	    			else {
	    				$c = new Category($_POST['cid']);
	    				$c->set('priority', new Priority($_POST['pid']));
	    				$c->commit();
	    			}
	    		}
	    	?>
			<tr>
				<td colspan="3" style="font-weight:bold">
					Category Name:&nbsp;
					<input type="text" name="catName" size="30" maxlength="30" value="<?php echo isset($name) ? $name : ''; ?>" />&nbsp;
					<input type="submit" name="add" value="<?php echo isset($name) ? 'Update' : 'Add'; ?>" />
				</td>
			</tr>
			<tr><td heigh="5"></td></tr>
			
			<tr>
				<td align="right" style="padding-left:7px" valign="top">
					<select name="cid" size="7">
					<?php
						$q = "select id as cid from " . DB_PREFIX . "categories order by name";
						$s = mysql_query($q) or die(mysql_error());
						if (mysql_num_rows($s)) {
							while ($r = mysql_fetch_assoc($s))
							{
								$c = new Category($r['cid']);
								$p = $c->get('priority');
								echo '<option value="' . $c->get('id') . '">' . $c->get('name', 'stripslashes') . ' [' . $p->get('name', 'stripslashes') . ']</option>' . chr(10);
							}
						}
						else
							echo '<option value="">No Stored Categories</option>' . chr(10);
					?>
					</select>
				</td>
				<td width="150" valign="top">
					<input type="submit" name="change" value="Change" style="width:150px" /><br/>
					<!--<input type="submit" name="delete" value="Delete" style="width:150px" /><br/>-->
					<input type="submit" name="link" value="Link to Priority" style="width:150px" />
				</td>
				<td valign="top">
					<select name="pid" size="7">
					<?php
						$q = "select pid from " . DB_PREFIX . "priorities order by severity";
						$s = mysql_query($q) or die(mysql_error());
						while ($r = mysql_fetch_assoc($s))
						{
							$p = new Priority($r['pid']);
							echo '<option value="' . $p->get('pid', 'intval') . '">' . $p->get('name', 'stripslashes') . '</option>' . chr(10);
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td colspan="2" class="error" align="center">
			<?php echo isset($error_msg) ? $error_msg : ''; ?><br/>
			<a href="../actmgt.php">Return to Control Panel</a>
			</td></tr>
			</form>
	    </table>
		<map name="Map2">
		  <area shape="rect" coords="4,130,70,176" href="reportproblem.php">
		  <area shape="rect" coords="80,128,159,174" href="helpDeskAccessAllCalls.php">
		  <area shape="rect" coords="173,129,274,173" href="DataAccessSearch.php">
		  <area shape="rect" coords="292,126,375,177" href="ocm-first.php">
		  <area shape="rect" coords="384,128,447,174" href="search.php">
		  <area shape="rect" coords="454,128,544,169" href="DataAccess.php">
		</map>
		<map name="Map"><area shape="rect" coords="543,151,611,195" href="DataAccess.php">
		    <area shape="rect" coords="480,146,542,198" href="search.php">
		    <area shape="rect" coords="280,146,362,194" href="actmgt.php">
		    <area shape="rect" coords="189,146,277,196" href="ocm-first.htm">
		    <area shape="rect" coords="127,148,182,198" href="DataAccessSearch.php">
		    <area shape="rect" coords="76,147,122,196" href="helpDeskAccessAllCalls.php">
			<area shape="rect" coords="163,2,248,14" href="DataAccessDataAccess.php">
			<area shape="rect" coords="2,148,74,200" href="reportproblem.htm">
		</map>
	</body>
</html>
