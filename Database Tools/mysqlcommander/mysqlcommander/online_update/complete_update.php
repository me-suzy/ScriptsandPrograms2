<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Complete Update from Server</title>
	<style>
		body, td {
			font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
			color : Black;
			font-size : 10pt;
		}
		
		.textdown {
			font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
			color : Black;
			font-size : 8pt;
		}
		
		.textrefresh {
			font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
			color : Black;
			font-size : 8pt;
		}
		a  { text-decoration: none; }
		a.nodetext:hover {color: #CA523E;}
		a.textdown:hover {color: #ff0000;}
		a.textrefresh:hover {color: #008000;}
	</style>
</head>

<body>
<font class='text8'>
Following files were updated:<br><br>
<?php
include("./class.ClientUpdate.php");
$b_show_readme = false;
$obj_cl_update = new ClientUpdate();
//$arrFiles = $obj_cl_update->getAllFilesForUpdate("../");


$arrFiles = $obj_cl_update->getCycleUpdate("../");


for ($i=0; $i<count($arrFiles); $i++) {
	echo $arrFiles[$i]['filepath']."<br>";
}

?>
</font>

<br><br>
</body>
</html>