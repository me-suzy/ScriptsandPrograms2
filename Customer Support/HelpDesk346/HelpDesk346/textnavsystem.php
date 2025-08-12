<?php
	//Revised on May 29, 2005
	//revised by jason farrell
	//revision number 1
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table border="1">
  <tr valign="top"> 
    <td><a href="<?php echo isset($ppath) ? $ppath : ''; ?>reportproblem.php">Open New Help Desk Call</a></td>
    <td><a href="<?php echo isset($ppath) ? $ppath : ''; ?>DataAccess.php">View All Help Desk Calls</a></td>
    <td><a href="<?php echo isset($ppath) ? $ppath : ''; ?>DataAccess.php?filter=user">View Only My Help Desk Calls</a></td>
    <td><a href="<?php echo isset($ppath) ? $ppath : ''; ?>kb/">Help Desk Search</a></td>
    <td><a href="<?php echo isset($ppath) ? $ppath : ''; ?>DataAccess.php">Helpdesk Main</a></td>
    <?php
	$buffer = '<a href="DataAccess.php?filter=active">Show Active Helpdesk Calls</a>';
	if (isset($_GET['filter']) && $_GET['filter'] == 'active')
		$buffer = "<b>$buffer</b>";
	echo "<td>$buffer</td>";
	?>
  </tr>
</table>
</body>
</html>
