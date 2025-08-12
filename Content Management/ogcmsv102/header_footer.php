<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                                      |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
//look for a session, and start one if none is present
session_start();

//include required files------------
require_once 'language.php';
require_once 'connect.php';
require_once 'login_functions.php';
include 'site_functions.php';
//------------------------------------


    function GetSysVar() {
             //function to get the system variables set in the system
             //page from the db, into an array for use in other functions

             //query------------------------------------------------------
             $query = "SELECT * FROM og_system WHERE id = '1'";
             $query_result = mysql_query($query) or die (mysql_error());
             //-----------------------------------------------------------
             $SysVar = mysql_fetch_array($query_result);
             return $SysVar;
             }


    function nav_zones() {
             //get language variables
             global $lang;
             //get all zone names from db
             //query----------------------------------------------------------------------------
             $query = "SELECT * FROM og_zones";
             $query_result = mysql_query($query) or die ("Terrible DB fault, please evacuate");
             //---------------------------------------------------------------------------------

             //print out zone names, with link to post_list.php, passing zone number
             //print-out--------------------------------------------------------------------
             print "<a href=\"post_list.php\">{$lang[nav_home]}</a><br><br>";
             while ($row = mysql_fetch_array($query_result)) {
                   //print " | ";
                   print "<a href=\"post_list.php?zone={$row['id']}\">{$row['zone']}</a>
                   <br><br>";
             }
             //------------------------------------------------------------------------------
    }


    function nav_download() {
             //get language variables
             global $lang;
             //print a link to post_list.php requesting list of awailable downloads. if any downloads
             //are awailable
             //query to check if there are any downloads awailable--------------------
             $query_dl = "select count(*) from og_post WHERE LENGTH(file_name) > 3";
             $dl_result = mysql_query($query_dl);
             $total_dl = mysql_result($dl_result, 0, 0);
             //-----------------------------------------------------------------------

             //if there are any downloads awailable, print the link
             //print-out---------------------------------------------------------
             if ($total_dl > 0) {
                print "<a href=\"post_list.php?dl=1\">{$lang[nav_downloads]}</a><br><br>";
                }
             //------------------------------------------------------------------
    }


    function nav_statics() {
        //check the database and check if any static pages are
        //awailable, it there are any, print a link to
        //show_static.php passing static id

        //query-----------------------------------------------------
        $query_static = "SELECT * FROM og_static";
        $query_static_result = mysql_query($query_static);
        //----------------------------------------------------------

        //if there are any statics, proceed with printout
        if (mysql_num_rows($query_static_result) >= 1) {
           //print-out-------------------------------------------------
           while ($s_row = mysql_fetch_array($query_static_result)) {
                 //print " | ";
                 print "<a href=\"view_static.php?static={$s_row['id']}\">{$s_row['name']}</a>
                       <br><br>";
                 }
           }
    }
             

    function nav_admin () {
             //get language variables
             global $lang;
             //displays admin-options
             print "<hr />";
             print "<a href=\"admin_add_post.php\">{$lang[nav_add_post]}</a><br><br>";
             print "<a href=\"admin_zones.php\">{$lang[nav_zones]}</a><br><br>";
             print "<a href=\"admin_static.php\">{$lang[nav_statics]}</a><br><br>";
             print "<a href=\"admin_system.php\">{$lang[nav_system]}</a><br><br>";
             print "<a href=\"admin_logout.php\">{$lang[nav_logout]}</a>";
    }


//-------------------------------------------------------------------
//M A I N  S I T E  B U I L D I N G  F U N C T I O N S ! ! ! - - - -||
//-------------------------------------------------------------------


//T H E  S H O W  H E A D E R  F U N C T I O N  T A K E S  C A R E  O F
//D I S P L A Y I N G  H E A D E R  A N D  N A V I G A T I O N  B A R!!


//----------------------------------------------------------------------

//------------------------------------------
//|  H E A D E R                            |
//|                                         |
//|_________________________________________|
//|N A V |
//|  -   |
//|B A R |
//|      |
//|      |
//|      |
//|      |

//-------------------------------------------------------------------------
    function show_header () {
             //diplays the header, with html header, link to stylesheet, and navbar

             //create an array with system variables
             $hd = GetSysVar();

             //escape to html mode to print out top of html page
             ?>
             <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
             <html>
             <head>
             <title><?php print $hd['title']?></title>

             <?php
             //if admin is enabled check if user is logged in:
             //start login check------------------------------
              if ($_SESSION['admin']) {
                 if (login_check()) {
                    $_SESSION['authorized'] = true;
                 }
                 if (!$_SESSION['authorized']) {
                    login_form();
                    die ();
                    }
                 }
              //----------------------------------------------

              //escape to html to print the rest of the html header
              ?>
             <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
             <meta name="generator" content="HAPedit 3.1">

             <!-- link to stylesheet-->
             <link rel="StyleSheet" href="style.css" type="text/css">
             <!--end of stylesheet link-->

             </head>
             <!-- end of html header----------------------------  -->

             <body>

             <!--container div block (sets the width of the page-->
             <div id="container">

                  <!--top heading-->
                  <div id="top">
                  <?php
                  //check what header image to use:
                  //(different if you are logged in)
                  if ($_SESSION['authorized']) {
                     $hdi = $hd['admin_top_image'];
                     }
                  else {
                       $hdi = $hd['top_image'];
                  }

                  //print header image-----------------------------------------
                  print "<img src=\"images/$hdi\"/\" alt=\"header image\" />";
                  //----------------------------------------------------------
                  ?>
                  </div>

                  <!--left coloum(navbar)-->
                  <div id="left">
                  <?php
                  //----------------------------------
                  //display admin options if you're logged in
                  //if you are not logged in, jsut display normal
                  //nav bar
                  //----------------------------------------------
                  print "<div id=\"navigation\">";
                  if ($_SESSION['authorized']) {
                             nav_zones();
                             nav_download();
                             nav_statics();
                             nav_admin();
                          }
                          else {
                             nav_zones();
                             nav_download();
                             nav_statics();
                          }
                  print "</div>";
                  //----------------------------------------------
                  ?>
                  </div>

                  <!--centre (main) coloumn-->
                  <div id="centre">
                  <?php
                  //end of show header function!!
    }

//T H E  S H O W  F O O T E R  F U N C T I O N  T A K E S  C A R E  O F
//D I S P L A Y I N G  T H E  R I G H T  C O L O U M N  A N D  T H E
//F O O T E R  S T R I P

//                                 ________
//                                |        |
//                                |S I D E |
//                                |  -     |
//                                | B A R  |
//                                |        |
//                                |        |
//                                |        |
//                                |________|
//------------------------------------------
//|F O O T E R  S T R I P                   |
//------------------------------------------


    function show_footer () {
             //create an array with system variables
             $hd = GetSysVar();
             //get language variables
             global $lang;
             //displays the right contents bar, and the footer, it allso
             //closes the main content coloumn and the body and html tags


             ?>
             </div>

             <!--right (auxilliary) coloumn-->
             <?php
             if ($hd['rh_enable'] == 'on') {
                print "<div id=\"right\">";

                      //show contents in right coloumn
                      //-------------------------
                      //top downloads
                      if ($hd['top_five_dl'] == 'on') {
                         top_dl();
                         print "<br><br>";
                      }
                      if ($hd['last_five_comm'] == 'on') {
                         //last comments
                         last_comment();
                      }
                      //-------------------------

                print "</div>";
                }

             print "<!--bottom footer-->
             <div id=\"bottom\">
             {$hd['footer']}, <a href=\"mailto:{$hd['mail']}\">webmaster ({$hd['mail']})</a> ";
             if ($_SESSION['authorized']) {
                             print "[<a href=\"admin_logout.php\">{$lang[nav_logout]}</a>]";
                          }
                          else {
                             print "[<a href=\"admin.php\">{$lang[login]}</a>]";
                          }
             ?>
             </div>

             <!--closing of the container and tags-->
             </div>

             </body>

             </html>
             <?php
    }


?>