<html>
<head>
	<title>ClanAdmin Tools 1.3.4 pr3 Help</title>
	
<script type="text/javascript" src="js/openwindow.js"></script>
</head>
<link rel="stylesheet" href="../css/style.css" type="text/css" />
<body>
<?php
if(!empty($_GET['id']))
{
	 switch($_GET['id'])
	 {
	  	case '1':
	  	echo"Enter your server hostname. Often localhost or 127.0.0.1";
	  	break;
	  	
	  	case '2':
	  	echo"Enter your database name. Ex. mydomain_db";
	  	break;
	  	
	  	case '3':
	  	echo"Enter your database username. Ex. my_user";
	  	break;
	  	
	  	case '4':
	  	echo"Enter your database password. Ex. Xf88ds";
	  	break;
	  	
	  	case '5':
	  	echo"Enter your table prefix. Ex. cat_, admin_. This grants you to run multiple
	  	versions on one database.";
	  	break;
	  	
	  	case '6':
	  	echo"Enter your prefered admin username. Ex. superhotadmin.";
	  	break;
	  	
	  	case '7':
	  	echo"Enter your prefered admin password. Ex. guUkl. Your pass will be
	  	converted into a md5 sum.";
	  	break;
	  	
	  	default:
	  	echo"No ID selected";
	  	break;
	}
}
?>

<hr style="height: 1px; padding: 0px;">
<span class="copytext">Copyright &copy; 2003-2005 ClanAdmin Tools. All rights reserved.</span>
</body>
</html>