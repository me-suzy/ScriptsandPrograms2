MyGosuBracket v1.0

/**
* readme.txt
* ------------------------------------------------------------
* Package:   MyGosuBracket
* Version:   1.0
* License:   GPL
* Author:    cagrET (Cezary Tomczak)
* Email:     cagret@yahoo.com
* Homepage:  http://cagret.prv.pl
* ------------------------------------------------------------
*/

Last updated: 2003-06-04

1. About
2. Requirements
3. Install
4. Config
5. Layout

# START

1. About

   Plz put a link to the projects homepage at the bottom of ur page:
   Powered by <a href="http://cagret.prv.pl">MyGosuBracket</a> v1.0

2. Requirements

   PHP4 + MySQL

   php.ini:
   'short_open_tag'   => On
   'magic_quotes_gpc' => On

3. Install

   3.1 Files
       
       chmod 700 kernel/

   3.2 Database

       a) edit file: kernel/config.php - database connection
       b) execute sql file: "kernel/brackets.sql"
          OR
          open web browser at http://example.com/install.php
       c) delete "install.php" file

4. Config

   kernel/config.php

5. Layout

   All html code is in "templates/" directory.

# EOF