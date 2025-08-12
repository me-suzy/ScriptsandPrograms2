<?php
/*
Version .1.1
June 8, 2005

This script is written by Lee Robertson. Feel free to use it/modify it as you see fit.
Use of this script as it our own risk. I make no guarantees that it will work on your machine/server.
If something goes wrong, it is your responsibility not mine. You are not permitted to use this script for displaying
quotes that would be offensize. Also if you use this script to make money in any way shape or form please contact me.  

All that being said, this script is just a random quote script that I wrote because I need one.
The script should work on most servers that have PHP installed. It was developed on a Windows machine with Apache and PHP.
A production version runs on a Linux machine with Apache and PHP.

If you like the script please either drop me a note at lee@lgr.ca or
even better a dollar or two (or more) via Paypal is always nice.

If you would like the professional version of the script with MySQL Database support,
web admin to add edit and delete quotes, please feel free to contact me. The cost is $60.00USD and
includes installation on your server

If you have other PHP work that you would like done, please drop me a note at lee@lgr.ca.

INSTALL
You can provide some configuration varibles to change how the script works.
Edit the quotes.txt file like so:
Quote|Quote Author|Link|Link Text

With one quote information on each line. If you leave onie section blank it will not display that line.

The link should be a full link including http:// but if you use a relative link to a file
on your server it should work fine as well.

You will need to add a <?php include_once(path to lgrquote.php file); ?> in the file(s) that you
want to add the quote to.

Please leave this header alone.
*/


//Configuration

$quotetitle="LGRQuote";
$quotefile="quotes.txt";

//directory might need to be changed when the script is included
//if the script is in the same folder as the calling file the dir and url
//would not need the ."path/"
//if you are calling the script from another folder you need to define
//the rest of the path to the folder.

$dir=$_SERVER['DOCUMENT_ROOT']."/rest of path/";

//No need to change anything below here.
if (file_exists($dir."/".$quotefile)) {
  $quote=file($dir."/".$quotefile);
  $rand_key=array_rand($quote, 1);
	list($qtext, $qauthor, $qlink, $qlinktext) = explode("|", trim($quote[$rand_key]));

  echo '<div>';
  echo '<h1 style="font: bold 12pt Arial, Helvetica, sans-serif; margin: 0; padding: 0;">'.$quotetitle.'</h1>';
  if (isset($qtext) && $qtext!="") { echo '<p style="font: normal 10pt Arial, Helvetica, sans-serif; margin: 5px 0 0 20px; padding: 0;">'.$qtext.'</p>'; }
  if (isset($qauthor) && $qauthor!="") { echo '<p style="font: normal 10pt Arial, Helvetica, sans-serif; margin: 5px 0 0 20px; padding: 0;">'.$qauthor.'</p>'; }
  if (isset($qlink) && $qlink!="" && isset($qlinktext) && $qlinktext!="") { echo '<p style="font: normal 10pt Arial, Helvetica, sans-serif; margin: 5px 0 0 20px; padding: 0;"><a href="'.$qlink.'">'.$qlinktext.'</a></p>'; }
  echo '</div>';
}

?>