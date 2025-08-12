<?php

//leave the following lines alone
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );

	//let TemplatePower do its thing, parsing etc.
	$tpl->prepare();

	//assign a value to {name}
	$tpl->assign( "footer", $settings[copyright] );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );

//Start modifying below:

/* You can do one of the following things to make an extra .php page that will look like the rest of your site:

1) Type out your text within the $output variable, like so:

	$output .= "<center><b>This is an about page.</b></center><br><br>";
	$output .= "You have to make sure that you backslash any quotation marks, such as <a href=\"url.com\">text</a>";
	
2) Include another page, like so:

	$output .= file_get_contents("aboutus.txt");


Whichever you choose, put it directly below in the blank space.*/







//Don't modify below this line	
	
	$tpl->assign( "output", $output );
	$tpl->printToScreen();

?>