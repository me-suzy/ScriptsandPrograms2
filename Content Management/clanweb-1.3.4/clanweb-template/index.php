<html>
<head>
<title>Con Your enemy - ClanWeb template</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body { background-color: #CEBDA8; background-image: url(gfx/bg.gif); color: black; }

#menubox { position: absolute; left: 7px; top: 300px; width: 74px; height: 80px; }
#gamesbox { position: absolute; left: 85px; top: 300px; width: 225px; height: 80px; }
#ircbox { position: absolute; left: 617px; top: 381px; width: 83px; height: 18px; }
#designby { position: absolute; left: 3px; top: 382px; width: 120px; height: 15px; }
#logo { position: absolute; left: 294px; top: 2px; width: 400px; height: 100px; }
#mainbox { position: absolute; left: 296px; top: 110px; width: 400px; height: 250px; background-color: #E6DACC; border: 1px solid #A39583; padding: 2px; overflow: scroll; }
#cornerpic { position: absolute; left: 7px; top: 2px; width: 300px; height: 300px; background-color: #CEBDA8; }
#box { position: absolute; left: 130px; top: 98px; width: 700px; height: 400px; background-color: #CEBDA8; border: 1px solid #A39583; padding: 2px; font-size: 10px; font-family: verdana; }

a.menu:link { border-left: 3px solid #E0D4C5; text-decoration: none; color: black;}
a.menu:visited { border-left: 3px solid #E0D4C5; text-decoration: none; color: black;}
a.menu:active { border-left: 3px solid #E0D4C5; text-decoration: none; color: black;}
a.menu:hover { border-left: 3px solid black; text-decoration: none; color: #DB8316;}

a.irc:link { text-decoration: underline; color: black;}
a.irc:visited { text-decoration: underline; color: black;}
a.irc:active { text-decoration: underline; color: black;}
a.irc:hover { text-decoration: none; color: black;}

a:link { text-decoration: underline; color: black;}
a:visited { text-decoration: underline; color: black;}
a:active { text-decoration: underline; color: black;}
a:hover { text-decoration: none; color: black;}

img { border: 0px; }
-->
</style>
<!--
// This is a demo template for ClanWeb.
// The template is designed by Error 404 and is protected under the copyright laws.
// You are welcome to use this template free of charge. 
// Any attempt to sell this template are strictly forbidden.
// WWW: http://www.clanadmintools.com & http://www.error404.se
-->
</head>

<body>
<div id="box"> 
  <div id="cornerpic"> 
    <img src="gfx/pic_1.jpg" width="300" height="300"> 
  </div>
  <div id="menubox""> 
    <a href="?p=" class="menu">Main</a><br/>
    <a href="?p=members" class="menu">Members</a><br/>
	<a href="?p=clanwars" class="menu">Clan Wars</a><br/>
	<a href="?p=sponsors" class="menu">Sponsors</a>
  </div>
  <div id="gamesbox">
<?php
		require('cfg.php');
			$result=mysql_query("select * from ".$db_prefix."game order by id DESC LIMIT 5");
			while ($read=mysql_fetch_array($result)) 
			{
				$id = $read["id"];
				$dates=$read["dates"];
				$team1=$read["team1"];
				$team2=$read["team2"];
				$point1=$read["point1"];
				$point2=$read["point2"];
				
            	echo "<a href=\"?p=match&amp;id=$id\">$team1 vs. $team2</a> ";
            	if($point1 > $point2)
            	{ 
            	  echo "<span style=\"color:green\">$point1-$point2</span>"; 
            	}
            	elseif($point1 < $point2)
            	{ 
            	  echo "<span style=\"color:red\">$point1-$point2</span>"; 
            	}
            	elseif($point1 == $point2)
            	{ 
            	  echo "<span style=\"color:#FFA902\">$point1-$point2</span>"; 
            	} 
            	echo"<br/>";
			}
?> 
  </div>
  <div id="ircbox"><strong>IRC:</strong> 
    <a class="irc" href="#">#ircchannel</a> </div>
  <div id="designby"> 
    <a href="http://www.error-404.se/" title="Design by error 404"><img src="gfx/design.jpg" alt="Design by error 404" /></a> </div>
  <div id="logo"><img src="gfx/logo.jpg" width="400" height="100"></div> 
  <div id="mainbox">
  <?php
    	if(isset($_GET['p']) && basename($_GET['p']) != '') 
		{
		 		if($_GET['p'] == 'index')
		 		{
		 		 	$fp = fopen('log.txt',"a+");
            		fwrite($fp, "|".$_SERVER['REMOTE_ADDR']." went at ".date('l dS of F Y H:i:s')." to page {$_SERVER['PHP_SELF']} with invalid GET-data.".$_SERVER['REQUEST_URI']."| \n");
            		fclose($fp);
                	header("location: ".$_SERVER['PHP_SELF']."?p=main");
                	die;
		 		}
       			if (file_exists(''. basename($_GET['p']) .'.php'))
		                        include(''. basename($_GET['p']) .'.php');
		else 
		{
		                if (file_exists('404.php'))
                    			include('404.php');
		                else
			                include('main.php');
		}
		}
    		else
        		include('main.php');
		?> 
  </div>
</div>
</body>
</html>
