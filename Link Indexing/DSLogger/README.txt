DSLOGGER 1.0 README
---------------------

CONTENTS:
 1) Installation
 2) Explanation

1) INSTALLATION
 -Upload everything (Including the folder) somewhere onto your site.
 -CHMOD /logs/ to 0777.
 -CHMOD config.php to 0666.
 -Enjoy
  ADVANCED INSTALLATION:
   -Follow all of the directions above.
   -Rename the logs directory to whatever. (By whatever, I mean whatever you like.)
   -Make sure the new folder is CHMOD'ed to 0777.
   -Edit config.php's $logs feild to the new directory name of the logs folder.
   -Rename lview.php to something else.
   -Edit config.php's $sof field to your offset in hours from your server's time.
   -Enjoy

2) EXPLANATION
 To start counting, use a standard hyperlink to visit.php?URL replacing URL with 
 the address of the site. Example: visit.php?dvondrake.com. 
 To put the number of hits counted somewhere on your site, use this peice of code:
  <? include("logs/URL.html"); ?>
 Replacing URL with the same address you used for visit.php. Example:
  <? include("logs/dvondrake.com.html"); ?>
 USE THIS CODE ONLY WITH FILES ENDING WITH THE EXTENSION .PHP OR .PHTML!! It is
 _impossible_ to display the number of hits with any other type of webpage.
 To see a list of all the logs and view the 'master' log with recordings of all the
 pages seen, what time/date, and the IP Address of the person who clicked it, go to
 lview.php. Click on a 'single' log to view the number of hits to that address,
 or click on "Master Log" to view the _whole_ log with IPs and dates.
 Advanced installation above provides more security for your /logs/ folder so people
 can't figure out that you use DSLogger and then go into your logs. It also allows
 you to change the path of your lview.php so nobody can use the default path to
 view your log!
 If you are not seeing the correct time, edit config.php's $sof field to your offset
 in hours from the server's time. 
 Example: My server's time is 6:00, and mine is 4:00. So there is a two hour difference.
 You can put a peice of code in your PHP ot PHTML page to make it count the hit even
 if it doesn't have a link going to it as a 'visit'. Use this code:
<?php
  include("config.php");
  $string = stripslashes($_SERVER['PHP_SELF']) . stripslashes($_SERVER['QUERY_STRING']);
  $blf = "$logs/log.html";
  $time = time()- ($sof*3600);
  $td = date('g:i A  d-m-y', $time);
  if ($_SERVER['HTTP_REFERER'] != "") {
   $biglog = "[" . $td . "] - [" . $_SERVER['REMOTE_ADDR'] . "] : " . $string . " : [" . $_SERVER['HTTP_REFERER'] . "]<br>";
    } else {
   $biglog = "[" . $td . "] - [" . $_SERVER['REMOTE_ADDR'] . "] : " . $string . " : [Ref N/A]<br>";
  }
  $file = fopen($blf, 'a+');
  fwrite($file, $biglog);
  fclose($file); 
 ?>
 The same code is in reg.php as well.
 
 


 
