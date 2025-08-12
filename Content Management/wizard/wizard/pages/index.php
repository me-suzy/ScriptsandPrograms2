<?php

//load all the configuration and database settings

include_once '../page_header.php';

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

<link href="../templates/css/basestyles.css" rel="stylesheet" type="text/css">

<?php

if ($config[topmenu] >= 1)

	{ echo "<link href=\"../templates/css/topmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }

else { echo "<link href=\"../templates/css/leftmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }

?>

<link rel="shortcut icon" type="image/ico" href="favicon.ico" />

<title><?php echo $config['name']; ?></title>



<!-- javascript for DHTML (Suckerfish) menu -->

<?php include("../inc/js/suckerfish.js"); ?>





</head>

<body id="bd">



<!-- Register, Login etc -->

<?php include("../searchandlogin.php"); ?>





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

		 	include("../inc/functions/topMenu.php"); 

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

 							include("../inc/functions/leftMenu.php"); 

							echo "</td></tr></table></td></tr>";

     					}

					?>

			<tr>

				<td valign="top" id="sidebarbottom" >

				<p>The links below provide additional information on the Wizard system.</p>



                <p> <strong>Links:</strong></p>



<!-- Link set 1 Script Events are for illustrative purposes only -->

<ul>

<li><a href="blog.php">Blog</a></li>

</ul>



<p><strong> Version 3 sites: </strong></p>

<ul>

  <li><a href="http://www.mobilevideoshorts.com">Mobile Video Shorts</a></li>

</ul>

<p><strong>      Version 2 sites: 

  </strong>

    <!-- Link set 2 Script Events are for illustrative purposes only -->

</p>

<ul>

<li><a href="http://www.sunpluscanada.com" >Sun Plus</a></li>

<li><a href="http://www.ragepictures.com/home/index.php?id=1" >Rage Pictures </a></li>

<li><a href="http://www.guppyinfo.com/">Guppy Info </a></li>

<li><a href="http://www.guyshaddock.com/home/index.php?id=1" >Guy Shaddock Design Services </a></li>

</ul>

				</td>

			</tr>

		</table>

  	</td>



<!-- Main Content -->

<td valign="top" id="maincontent">

<h1>Wizard Site Framework: Version 3 </h1>

<p><img src="../images/window_000.jpg" alt="Window" width="130" height="215" hspace="8" vspace="8" align="left" />The Wizard framework has been used in production sites for three years, serving countless thousands of page views.</p>

<p>It is modular , coming out of the factory with a basic structure, allowing the webmaster to spend more time on design and less on the plumbing.</p>

<p>Included is a user authentication system, scripts for registering, changing passwords, contact forms, confirmation emails, listing users, editing their profiles, privacy statements (etc.etc.), fully integrated into the site skeleton and under the rule of common CSS stylesheets.</p>

<p>Very cool is the menu and sitemap systems which are generated from the database automatically when a page is added or altered during authoring. The menu systems use the famed Suckerfish CSS-driven Javascript code harnessed to a MySQL database with PHP scripting. You can, for example, have either horizontal or vertical menus, and they can be set at whatever depth you think is reasonable. You can hide pages from the menu or sitemap or restrict their view to only site members with the appropriate credentials.</p>

<p>Other features include such utilities as visitors statistics tied to the user management system so that you can see what specific pages on your site your registered members are viewing.<img src="../images/adminpanel.jpg" alt="Admin Panel" width="300" height="164" hspace="5" vspace="5" border="0" align="right" /></p>

<p>This is the third generation of the Wizard framework. While the structure of the site continues to be managed through an admin panel, and stored in a MySQL database, what is rather unique about the latest iteration is that the page content, indeed the entire page, is stored on the server as an HTML - PHP file.</p>

<p>While previous versions stored HTML content in the database, the Version 3.0 makes the page completely accessible to such online programs as Macromedia Contribute&#8482;&nbsp; or other authoring tools. Unlike previous versions, or content management systems (CMS) that store the HTML content in a database table, you have the ability to include whatever server-side or client-side code in site pages, removing a major disadvantage of most content management systems. </p>

<p>This makes Wizard Site Framework totally customizable and flexible. And because it is lightweight and very efficient it is easily scalable to large sites.</p>

<p style="margin-bottom: 0;">&nbsp;</p>

<!-- end Main Content -->

</td>

</tr>

<tr>



<!-- Footer -->

<td id="footer" colspan="2">

<?php include("../footer.php"); ?>

</td>

</tr>

</table>

<!-- end Main Layout Table -->	

</body>

</html>