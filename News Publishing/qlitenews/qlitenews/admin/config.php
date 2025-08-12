<?php

  //Please edit all the variables below 
  //Edit everything inside the double quotes "*"

  #####################################
  #MYSQL DATABASE
  #####################################

  //Database Host
  $dbhost = "localhost";
  //Database Name
  $dbname = "DatabaseName";
  //Database User
  $dbuser = "Username";
  //Database User Password
  $dbpass = "UsernamePassword";

  #####################################
  #qliteNews MAIN OPTIONS
  #####################################
  
  //Number of news that will be display on the page
  $news_limit = 5;
  //Date format, THIS MUST BE PHP VALID OR YOU MAY GET AN ERROR!
  //For a list of date format go here: http://www.php.net/manual/en/function.date.php
  //If you are unsure just leave it the way it is!
  $news_date = "F j, Y";
  //Date and author display, put "1" if you want in ON or "0" if you want it OFF
  $news_info = 1;
  //Put "DESC" if you want the latest news to be displayed first or "ASC" if you want it to diplay last
  $news_format = "DESC";
  //Where the news will be display, do NOT include the "http://"
  $news_include = "www.r2xdesign.net";
  
  #####################################
  #qliteNews THEME
  #####################################

  //MAKE SURE YOU ARE USING RIGHT HEX COLOR FORMAT! (ex. #000000, #FFFFFF, #EEEEEE etc..)
  //If you want to play around with hex color here's a nice tool: http://www.r2xdesign.net/page-colorblender.html

  //Head title color
  $head_color = "#879BAF";
  //Main news color
  $body_color = "#000000";
  //Border between the news and info color
  $border_color = "#879BAF";
  //Date and author color
  $info_color = "#393939";

?>

   