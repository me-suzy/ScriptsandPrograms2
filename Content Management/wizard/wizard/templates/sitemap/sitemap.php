<?php
//load all the configuration and database settings
include_once '../../page_header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="robots" content="<?php echo $config['robots']; ?>" />
<meta name="author" content="<?php echo $config['siteAdmin']; ?>" />
<meta name="description" content="<?php echo $config['description']; ?>" />
<meta name="keywords" content="<?php echo $config['keywords']; ?>" />
<link href="../../templates/css/basestyles.css" rel="stylesheet" type="text/css">
<link href="../../templates/css/sitemap.css" rel="stylesheet" type="text/css">
<?php
if ($config[topmenu] >= 1)
	{ echo "<link href=\"../../templates/css/topmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }
else { echo "<link href=\"../../templates/css/leftmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }
?>
<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
<title><?php echo $config['name']; ?> Sitemap</title>

<!-- javascript for DHTML (Suckerfish) menu -->
<?php include("../../inc/js/suckerfish.js"); ?>


</head>
</head>
<body id="bd">
<!-- Register, Login etc -->
<?php include("../../searchandlogin.php"); ?>


<!-- Main Layout Table -->
<table align="center" cellpadding="20" cellspacing="0" id="mainTable">

	
<!-- Header -->
	<tr>
		<td colspan="2" id="header">&nbsp;</td>
	</tr>

<!-- Menu Bar -->
<?php
	if ($config[topmenu] >= 1)
	{
	echo "<tr>";
		echo "<td  id=\"topMenu\" colspan=\"2\">";
		 	include("../../inc/functions/topMenu.php"); 
		echo "</td>";
	echo "</tr>";
	}
?>


<!-- end of Menubar -->

<!-- Sidebar -->
<tr>
	<td valign="top" >
		<table id="sidebar">
			<!-- sidebar vertical menu -->
    				<?php
						if (!$config[topmenu] >= 1)
						{	echo "<tr><td><table id=\"sidebarmenu\"><tr><td valign=\"top\">";
 							include("../../inc/functions/leftMenu.php"); 
							echo "</td></tr></table></td></tr>";
     					}
					?>
			<tr>
				<td valign="top" id="sidebarbottom" >
			Sitemap
				</td>
			</tr>
		</table>
  	</td>

<!-- Main Content -->
<td valign="top" id="maincontent" >

<h1>Sitemap</h1>
<?php
echo "<div id=\"sitemapcontainer\">";
echo "<ul class=\"sitemap\">";
$count = 0;

// page and menu data 
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "menuData WHERE id=3 ");
	$i = $db->next_record();
	$siteMap = unserialize($i[serialized]);

//iterate through the menu data file
   foreach ($siteMap as $v1) {
          
	  		  
	  echo "<li><a ";
	  if (($siteMap[$count+1][0] > $siteMap[$count][0]) && ($siteMap[$count][0] != 0)) {echo "class=\"parent\"";}   //applies sublevel styling
	  echo "href=\"".CMS_WWW."/pages/". $siteMap[$count][4] ."?id=" . $siteMap[$count][1]  ."\">".$siteMap[$count][2]."</a>"; 
 
	  //now determine closing tags
	  if ($siteMap[$count+1][0] > $siteMap[$count][0])
	     { echo "<ul>"; }
	  
	  elseif($siteMap[$count+1][0] == $siteMap[$count][0])  // next item is at same level so close tag
	  	{echo "</li>";}
	  elseif (!isset($siteMap[$count+1][0]) || $siteMap[$count+1][0] < $siteMap[$count][0]) //next is end of array or one level up
	     { 
		     echo "</li>";	 
			 
		    $leveldepth = $siteMap[$count][0] - $siteMap[$count+1][0];
			$leveldepth = abs($leveldepth) ;
			while ($leveldepth > 0 )
						{
				    		echo "</ul></li>";
							$leveldepth--;
						}
		 }   //it is up one level  
		 
		 else { echo "There is an error in the pages table hierarchy."; };

 $count++; 
} // foreach
	   
  
echo "</ul>";
echo "</div>";
?>







<!-- End Main Content -->
</td>
</tr>
<tr>

<!-- Footer -->
<td id="footer" colspan="2">
<?php include("../../footer.php"); ?>
</td>
</tr>
</table>
<!-- end Main Layout Table -->	
</body>
</html>