<?php
	//Created on May 29, 2005
	//REvised by Jason Farrell
	//Version 4
	//Copyright 2005 Help Desk Reloaded.  Do not resell or redistribute
	
	
	//do some outsider directory inclusions
	session_start();
	$p = getcwd();

	chdir('../');
	include_once "./config.php";
	include_once "./includes/constants.php";
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS) or die("Technical Problems Preventing Data Connection - Terminating");
	mysql_select_db(DB_DBNAME) or die("Invalid : " . mysql_error());
	
	include_once "./includes/settings.php";
	include_once "./includes/classes.php";
	
	//log into the database - this is for looking at the settings
	chdir($p);
	
	include_once "./includes/functions.php";
	if (isset($_SESSION['enduser'])) {
		$u = unserialize($_SESSION['enduser']);
		$_SESSION['userLevel'] = $u->get('securityLevel', 'intval');
		unset($u);	
	}
	
	//default search is standard search
	if (!isset($_GET['type'])) $_GET['type'] = 'std';
?>
<html>
	<head>
		<title>Helpdesk Knowledge Base Search</title>
		<style type="text/css">
			.outerTable {
				border: 0px solid red;
				padding: 3px;
				width: 650px;
				background-color: white;
			}
			
			th.head, th.head_select {
				border: 2px solid blue;
				background-color: white;
			}
			
			th.head_select {
				border-style: inset;
				background-color: #DDDDDD;
			}
			
			th.head {
				border-style: outset;
			}
			
			td.content {
				padding: 2px;
				padding-lefft: 5px;
			}
			
			/*  Hover Styles */
			th.head_select a.link:hover, th.head a.link:hover {
				color: red;
			}

			/* Link Style */
			th.head_select a.link, th.head a.link {
				text-decoration: none;
			}
			
			.innerbox {
				padding-left: 20px;
			}
		</style>
		<link rel="stylesheet" href="../style.css" type="text/css" />
	</head>
	
	<body>
		<?php
			if (isset($_SESSION['enduser']))
				if ($OBJ->get('navigation') == 'B') {
					$ppath = "../";
					include_once "../dataaccessheader.php";
				}
				else
					include_once "../textnavsystem.php";
			else
				include_once "./includes/otherheader.php"; 

		?>
		<table cellpadding="0" cellspacing="5" class="outerTable" border="0">
			<tr>
				<th class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'cat') ? 'head_select' : 'head'; ?>"><a href="?type=cat" class="link">Browse Problems</a></th>
				<th class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'key') ? 'head_select' : 'head'; ?>"><a href="?type=key" class="link">Keyword Search</a></th>
				<th class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'std') ? 'head_select' : 'head'; ?>"><a href="?type=std" class="link">Standard Search</a></th>
			</tr>
			
			<tr><td colspan="3" class="content" valign="top">
			<?php
				switch ($_GET['type'])
				{
					case 'cat':
						include_once './includes/cat_form.php';
						break;
					case 'key':
						include_once './includes/key_form.php';
						break;
					case 'std':
						include_once './includes/std_form.php';
						break;
					default:
						echo 'Invalid Search Option Selected';
				}
			?>
			</td></tr>
			<tr><td colspan="3" valign="top">
				<h4 style="display:inline">Top 10 Problem Categories</h4>
				<ol>
				<?php
					$q = "select category, sum(pageView) as sum from " . DB_PREFIX . "data group by category order by sum desc LIMIT 10";
					$s = mysql_query($q) or die(mysql_error());
					while ($r = mysql_fetch_assoc($s))
					{
						$stat = new Category($r['category']);
						echo "<li>" . $stat->get('name', 'stripslashes') . "</li>\n";
					}
				?>
				</ol>
			</td></tr>
		</table>
	</body>
</html>