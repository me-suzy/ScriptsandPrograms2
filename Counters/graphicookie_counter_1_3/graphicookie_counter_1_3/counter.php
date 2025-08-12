<?php
##############################################################
# GraphiCookie Counter 1.3                                  #
# Script by: Matthieu Biscay                                #
# Web: http://www.skyminds.net/	                            #
# Contact: http://www.skyminds.net/contact/                 #
# Copyright 2001-2004 - SkyMinds.Net. All rights reserved.  #
# This script is linkware. Contact us for commercial use.   #
##############################################################


// ----------------------------------- EDIT HERE ---------------------------------------- //

$gcc_aspect       = "img"; 		// directory where the images can be found.
$gcc_file         = "counter.txt";	// name of the file where the number of visitors is kept
$gcc_cookie_name  = "my_cookie_name";	// name of the cookie (eg: your site's name)
$gcc_cookie_value = "my_cookie_value";	// value of the cookie (eg: something about your site)
$gcc_cookie_life  = "900";		// cookie lifespan. Default is 900s (15min)

// -------------------------------------------------------------------------------------- //

// ------------------------------ Creation of the counter file -------------------------- //
if(!file_exists("$gcc_file"))
{
	$gcc_fp=fopen("$gcc_file","a");
	fputs($gcc_fp,"0");
	fclose($gcc_fp);
}
// ------------------------------------------------------------------------------------- //

// ----------------- Visitor already came : the cookie is here ------------------------- //
$gcc_alt = 'alt=""';
if(isset($_COOKIE["$gcc_cookie_name"]) && $_COOKIE["$gcc_cookie_name"] == "$gcc_cookie_value")
{
  $gcc_fp=fopen($gcc_file,"r+");
  $gcc_hits=fgets($gcc_fp,10);
  $gcc_hits = "<a href='http://www.skyminds.net/source/' target='_blank'>".$gcc_hits;
  $gcc_hits = str_replace("0","<img src='$gcc_aspect/0.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("1","<img src='$gcc_aspect/1.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("2","<img src='$gcc_aspect/2.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("3","<img src='$gcc_aspect/3.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("4","<img src='$gcc_aspect/4.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("5","<img src='$gcc_aspect/5.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("6","<img src='$gcc_aspect/6.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("7","<img src='$gcc_aspect/7.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("8","<img src='$gcc_aspect/8.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("9","<img src='$gcc_aspect/9.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = $gcc_hits."</a>";
}
// -------------------------------------------------------------------------------------- //

// ----------------- A new visitor is coming: creation of a cookie ---------------------- //
else
{
  setcookie($gcc_cookie_name, $gcc_cookie_value, time()+$gcc_cookie_life, "");
  $gcc_fp=fopen($gcc_file,"r+");
  $gcc_hits=fgets($gcc_fp,10);
  $gcc_hits++;
  fseek($gcc_fp,0);
  fputs($gcc_fp,$gcc_hits);
  fclose($gcc_fp);
  $gcc_hits = "<a href='http://www.skyminds.net/source/' target='_blank'>".$gcc_hits;
  $gcc_hits = str_replace("0","<img src='$gcc_aspect/0.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("1","<img src='$gcc_aspect/1.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("2","<img src='$gcc_aspect/2.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("3","<img src='$gcc_aspect/3.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("4","<img src='$gcc_aspect/4.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("5","<img src='$gcc_aspect/5.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("6","<img src='$gcc_aspect/6.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("7","<img src='$gcc_aspect/7.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("8","<img src='$gcc_aspect/8.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = str_replace("9","<img src='$gcc_aspect/9.jpg' $gcc_alt>","$gcc_hits");
  $gcc_hits = $gcc_hits."</a>";
}
// -------------------------------------------------------------------------------------- //

// --------------------- Clean HTML: set the image attributes --------------------------- //
//
// Adapt height and width to your images.

$gcc_hits = str_replace('alt=""','alt="" border="0" height="12" width="10"',$gcc_hits);
// -------------------------------------------------------------------------------------- //
?>