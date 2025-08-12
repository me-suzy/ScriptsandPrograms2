<?php

/*
################################################################
#SV's Simple Counter v1.4
#(c)SecondVersion www.secondversion.com
################################################################
#This script is provided free and as-is without warranty.
#To be able to use this script all links to my website
#must remain intact. And all copyright headers left intact.
#You may redistribute my script as long as all files, copyright
#and links are intact.
################################################################
*/

/*
No need to change.
However, if you renamed the counter file, rename it here.
*/
$count_file = "counter.txt";

if(!file_exists($count_file))
{
  die("Error: Count file not be found.");
}
elseif(!is_writable($count_file))
{
  die("Error: Count file not writeable. Please CHMOD to 0666 or 0777");
}
else
{
  //Check the file for current count..
  $fp = fopen($count_file, "r");
  $new_count = fread($fp, filesize($count_file));
  $new_count++;
  fclose($fp);

  //Print the count..
  echo $new_count." people have visited my site.";
  echo "<br><font size='1'>Powered by: <a href='http://www.secondversion.com'>SVsSimpleCounter</a></font>";

  /*
  1 for yes, 0 for no.
  If you do not want the hits reset, set to 0, otherwise, leave at 1.
  */
  $reset = 1;
  //The below resets the count file between 12 and 12:01 AM
  if($reset == 1)
  {
    $time = getdate();

    if($time['hours'] == 00 && $time['minutes'] == 00)
    {
      $empty = fopen($count_file, "w");
      fwrite($empty, "0");
      fclose($empty);
    }
  }
  //Check to see if our cookie is present, if so the count won't be logged.
  //If not, the count will be logged.
  if(empty($_COOKIE['site_visitor']))
  {
    //Write the count to our count file..
    $count = fopen($count_file, "w");
    fputs($count, $new_count);
    fclose($count);
    //Set a cookie that will expire in 24 hours.
    setcookie('site_visitor', 1, time()+86400);
  }
}
?>