<?php

/******************************************************************************
File Name    : index.php
Description  : shows the homepage, help and installed counter styles
Author       : mike@mfrank.net (Mike Frank)
Date Created : March 24, 2004
Last Change  : April 12, 2004
Licence      : Freeware (GPL)
******************************************************************************/

$p = $_GET["p"];
$pg = $_GET["pg"];
switch ($p){
        case "styles": showstyle(); exit;
        case "help": showhelp(); exit;
        default: showmain();
}
switch ($pg){
        case "styles": showstyle(); exit;
        case "help": showhelp(); exit;
        default: showmain();
}

function showmain(){
        $fp=fopen("previd.db","r");
        //Read the previous count
        $previd=fgets($fp,1024);
        //close the file.
        fclose($fp);

        include("config.php");
        include("incl/header.inc");
        include("incl/index.inc");
        include("incl/footer.inc");
        exit;
}

function showhelp(){
        include("config.php");
        include("incl/header.inc");
        include("incl/help.inc");
        include("incl/footer.inc");
        exit;
}

function showstyle(){
        include("config.php");
        include("incl/header.inc");
        include("incl/styles.inc");
        include("incl/footer.inc");
        exit;
}
?>