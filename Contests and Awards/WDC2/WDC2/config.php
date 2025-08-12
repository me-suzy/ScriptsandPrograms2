<?php

//edit this to what you want your game to be called

$gametitle = "Weekly Drawing Contest";

//This is the url to your game, do not include index.php in the url, and no trailing slash

$gameurl = "http://contest.tcgames.net/";


//the Url to the main site

$siteurl = "http://www.tcgames.net/";


//your admin email address

$adminemail="contest@tcgames.net";


//Email the admin when a new user joins

$newemailadmin = "0";


// Secret words, if you allow source to be viewable (default on) you will open a security hole in that people can see certain secret words (such as mysql passwords) Seperate with |

$words = "password1|password2|secretword1";

$secret = explode("|",$words);


//change this to a 1 if you want MD5 encryption on users passwords
//NOTE : IF YOU CHANGE THIS SETTING WHILE YOU HAVE MEMBERS THEY WILL NOT BE ABLE TO LOG IN, YOU WILL HAVE TO RESET THIER PASSWORDS MANUALLY
//if you are upgrading versions make sure this setting is the same as previous versions (pre 2.0.4 are always 0)

$md5pass = "0";


//you can ignore this, doesnt effect much yet

$GAME_SELF="$PHP_SELF";




?>