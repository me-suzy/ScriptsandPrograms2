<?php
/**************************************************************
 *                                                            *
 *                     Dellary                                *
 *            An easy to use Gallery Script                   *
 *                                                            *
 *              (c) 2001 by Marcus Proest                     *
 *                                                            *
 *   The script may be used and changed for private usage     *
 *         but this Header may not be removed                 *
 *                                                            *
 *                                                            *
 *                Component: config.inc                       *
 *                                                            *
 **************************************************************/
//Basic Configuration
$config[NeedPass] = "No";               //Upload Password protected? (Yes/No)
$config[Password] = "";                 //Password
$config[files] = "3";                   //Number of FikeUploadBoxes in insert.php
$config[dir] = "images";                //!!!!!This Folder has to be created as subfolder!!!!!!!!!!!!!!!
$config[title] = "Dellary";             //The title of the Page

//Color Configuration
$config[bgcolor] = "#ffffff";           //Backgroundcolor
$config[textcolor] = "#000000";         //Textcolor
$config[alinkcolor] = "#0000ff";        //Active Link color
$config[vlinkcolor] = "#9900ff";        //visited Link color
$config[linkcolor] = "#0000ff";         //Linkcolor
$config[tablebgcolor] = "#0000ff";      //Upload Table Background color
$config[tablefontcolor] = "#ffffff";    //Upload Table Font Color

//Language Configuration
$config[lang] = "english";              //Language. (german/english). Fell free to add translations in other languages.
                                        //Files are located in "./lang/"
                                        //Send them to dellary@proest.net (your name will be featured)

php?>