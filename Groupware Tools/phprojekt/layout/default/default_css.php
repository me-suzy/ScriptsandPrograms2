<?php

$printmode = (isset($_GET['print'])) ? true : false;

$cfg_highlight_link_color = '#DC6417';
$cfg_column_main_color    = '#085EB4';

header("Content-type: text/css");

?>
/**
 * PHProjekt CSS-File
 *
 * $Id: default_css.php,v 1.48.2.2 2005/09/06 13:00:07 fgraf Exp $
 *
 */
li img{
    border:none;
    vertical-align:middle;
}
body, td {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    margin: 0px;
    padding: 0px;
}
body {
    <?php
    if (!isset($_GET['void_background_image']) && !$printmode) {
        echo "background-image:  url(img/background.png);\n";
        echo "background-repeat: repeat-y;\n";
    }
    ?>
    height: 100%;
}
/* logo style */
img.logo { }
/* list numeration */
dfn {
    display:none;
}
#logo {}
#body {
    width:95%;
    height:250px;
}

/***********************************
       special menu entries
***********************************/
/* menu entry 'logged in as ...' */
/* menu entry 'start work / stop work ...' */
/* menu entry stop watch */
.menuLoggedAs, .menuStartStopWork,  .menuStopWatch {
    padding-bottom:8px;
    margin-left:5px;
    display:block;
}

/* search box in menu */
.menuSearchbox {}
/* help link in menu */
.menuHelp {}
/* copyright link in menu */
.menuCopyright {}
/* summary link in menu */
.menuSummary {}
/* calendar link in menu */
.menuCalendar {}

/***********************************
           menu icons
***********************************/
.icon {
    display:table-cell;
    font-size:110%;
    width: 50px;
    height: 30px;
    overflow:auto;
    list-style-type:none;
    cursor:pointer;
    padding-left: 20px;
    width:145px;
    border-bottom:thin solid #ffffff;
    padding-bottom:5px;
    padding-top:5px;
}

/* Define these styles if you want to see icons instead of text for the menu */
.iconSummary, .iconCalendar, .iconContacts, .iconChat, .iconForum, .iconFilemanager, .iconProjects,
.iconTimecard, .iconNotes, .iconHelpdesk, .iconMail, .iconTodo, .iconLinks, .iconBookmarks, .iconVotum {
    display:none;
}

h2, h3, h4, h5, h6 {
    font-weight: bold;
    font-size: 100%;
}

a {
    text-decoration: none;
    color: <?php echo $cfg_column_main_color; ?>;
    font-weight: bold;
}
a:link {
    text-decoration: none;
}
a:visited {
    text-decoration: none;
    color: <?php echo $cfg_column_main_color; ?>;
    font-weight: bold;
}
a:hover {
    text-decoration: underline;
}
a:active {
    text-decoration: underline;
}
/* link white, bold */
a.white {
    text-decoration: none;
    color:#ffffff;
    font-weight: bold;
}
a.white:visited {
    text-decoration: none;
    color:#ffffff;
    font-weight: bold;
}
a.white:hover {
    text-decoration:underline;
}
a.count_related {
    border:0;
    border-style:none;
    color:rgb(159,9,9);
    font-size:11px;
    font-weight:normal;
}
a.count_related:hover {
    color:rgb(159,9,9);
    font-size:11px;
    font-weight:normal;
}
a.count_related:visited {
    color:rgb(159,9,9);
    font-size:11px;
    font-weight:normal;
}
a.filter_active {
    border:0;
    border-style:none;
    color:#ffffff;
    background-color:#dc6417;
    font-size:11px;
    font-weight:bold;
}
a.filter_active:hover {
    color:#ffffff;
    background-color:#dc6417;
    font-size:11px;
    font-weight:bold;
}
a.filter_active:visited {
    color:#ffffff;
    background-color:#dc6417;
    font-size:11px;
    font-weight:bold;
}
a.filter_manage {
    border:0;
    border-style:none;
    color: <?php echo $cfg_column_main_color; ?>;
    font-size:11px;
    font-weight:normal;
}
a.filter_manage:hover {
    color: <?php echo $cfg_column_main_color; ?>;
    font-size:11px;
    font-weight:normal;
}
a.filter_manage:visited {
    color: <?php echo $cfg_column_main_color; ?>;
    font-size:11px;
    font-weight:normal;
}

.home {
    height:100%;
    color: #000000;
    text-decoration:underline;
}
.path {
    text-decoration: none;
    color: #000000;
    font-weight: bold;
    margin-right: 20px;
    margin-left: 10px;
}

/* top header line in content container */
.he1 {
    width:100%;
    z-index:1;
    height:0.4em;
    font-size:0.3em;
    padding:0;
    margin:0;
    height: 0.4em;
    background: <?php echo $cfg_column_main_color; ?>;
}
.header {
    width: 78%;
    margin-top: 10px;
    padding-left:2%;
    height:20px;
    margin-left:10px;
    background: <?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    text-align:left;
    background-color: <?php echo $cfg_column_main_color; ?>;
    margin-bottom:0px;
}
.chat_he1 {
    position:fixed;
    top:0;
    width:100%;
    z-index:2;
    font-size:2px;
    padding:0px;
    margin:0 0;
    height: 5px;
    background: <?php echo $cfg_column_main_color; ?>;
}

.he2 {
    width:100%;
    z-index:1;
    margin:0 0;
    height: 10px;
    background: <?php echo $cfg_column_main_color; ?>;
}
/* module header div containing main functions , e.g. buttons */
.div1 {
    width:100%;
    z-index:1;
    margin:0;
    padding: 0.5em 0em;
    font-weight:bold;
    text-align:left;
    background-color: rgb(211,217,223);
    /*background-color: yellow;*/
    border-bottom-width:1px;
    border-bottom-style: solid;
    border-bottom-color: #000000;
    height: 25px;
    vertical-align:middle;
}

.chat_div1 {
    position:fixed;
    top:5px;
    width:100%;
    z-index:2;
    margin:0 0;
    padding: 5px 0px 5px 0px;
    font-weight:bolder;
    text-align:left;
    background: rgb(239,239,239);
    border-bottom-width: thin;
    border-bottom-style: solid;
    border-bottom-color: #000000;
    height: 25px;
    vertical-align: middle;
}
.clock {
    width:100%;
    margin:0 0;
    padding: 5px 10px 5px 0px;
    font-size:11px;
    font-weight:bold;
    text-align:left;
    background: rgb(239,239,239);
    height: 100px;
    vertical-align: middle;
}
.bottom {
    width:100%;
    bottom:0;
    position:absolute;
    margin-bottom:0px;
    padding: 5px 0px 5px 0px;
    font-weight:bolder;
    text-align:left;
    background: rgb(239,239,239);
    border-bottom-width: thin;
    border-bottom-style: solid;
    border-bottom-color: #000000;
    border-top-width: thin;
    border-top-style: solid;
    border-top-color: #000000;
    height: 25px;
    vertical-align: middle;
}
select.cont {
    width:70%;
}
.div11 {
    width:100%;
    margin:0 0;
    padding: 5px 0px 5px 0px;
    font-weight:bolder;
    text-align:left;
    background: rgb(239,239,239);
    border-bottom-width: thin;
    border-bottom-style: solid;
    border-bottom-color: #000000;
    vertical-align: middle;
}
.div12 {
    width:100%;
    margin:0 0;
    padding: 5px 0px 5px 0px;
    font-weight:bolder;
    text-align:left;
    background: rgb(239,239,239);
    height: 25px;
    vertical-align: middle;
}
.div2 {
    width:100%;
    margin:0 0;
    padding: 0 0;
    font-weight:bolder;
    text-align:left;
    background: rgb(211,217,223);
    border-bottom-width: thin;
    border-bottom-style: dashed;
    border-bottom-color: #ffffff;
    height: 30px;
    vertical-align: middle;
}
.div3 {
    width:100%;
    margin:0 0;
    padding: 0 0;
    font-weight:bolder;
    text-align:left;
    background: rgb(211,217,223);
    border-bottom-width: thin;
    border-bottom-style: solid;
    border-bottom-color: #000000;
    height: 20px;
}
.div4 {
    clear:both;
    width:100%;
    margin:-2px 0;
    padding: 2px 0 2px 0 ;
    background: rgb(239,239,239);
    height: 20px;
}
.div5 {
    width:100%;
    margin:0 0;
    padding: 2px 0;
    background: rgb(211,217,223);
    height: 20px;
}
.div6 {
    width:100%;
    margin: 0;
    padding: 5px 0px 5px 0px;
    font-weight:bolder;
    text-align:left;
    background: rgb(211,217,223);
    border-bottom-width: thin;
    border-bottom-style: solid;
    border-bottom-color: #000000;
    height: 25px;
    vertical-align: middle;
}
div.oben {
    position:absolute;
    top:41px;
    width:inherit;
    bottom:85px;
    overflow:auto;
}

div.unten {
    position:fixed;
    height:65px;
    padding:10px 0 10px 10px;
    bottom:0px;
    margin:0;
    width:inherit;
    background:#D3D9DF;
    border:1px solid #000000;
}
div.links {
    float:left;
    width:85%;
    bottom:40px;
}
div.white {
    height:2px;
    width:100%;
    background:#ffffff;
}
div.rechts {
    position:fixed;
    z-index:0;
    right:0;
    bottom:0;
    top:41px;
    height:auto;
    width:15%;
    background: rgb(239,239,239);
}
div.left {
    clear:both;
    float:left;
    height:65px;
    width:50%;
    border-bottom:4px solid #ffffff;
    border-collapse:collapse;
    border-right:4px solid #ffffff;
    margin:0;
    margin-left:-4px;
}
div.right {
    width:50%;
    height:65px;
    border-bottom:4px solid #ffffff;
    margin:0;
}
a.mail_pa {
    text-decoration:underline;
    color: #ffffff;
    cursor: pointer;
    font-weight:normal;
}
div.mail_left {
    clear:both;
    float:left;
    height:auto;
    width:50%;
    border-collapse:collapse;
    border-right:4px solid #ffffff;
    margin:0;
    margin-left:-4px;
}
div.mail_right {
    width:50%;
    float:left;
    height:auto;
    margin:0;
}
div.mail_both {
    width:100%;
    height:auto;
}
div.mail_both1 {
    padding-top:5px;
    padding-bottom:5px;
    width:100%;
    height:auto;
}

div.da_mail {
    margin-top: 10px;
    padding:0;
    background-color: rgb(239,239,239);
    margin-left: 10px;
    width:100%;
}

hr.mail {
    border: 0;
    height:2px;
    color: #ffffff;
    background-color:#ffffff;
    width:100%;
    margin-left:0;
}
.form_div {
    float:left;
    margin-top: 5px;
}
div.both {
    width:100%;
    height:auto;
    border-bottom:4px solid #ffffff;
}
input.mail_form {
    float:left;
    text-align: left;
    margin-left: 5px;
    margin-right: 5px;
    width:75%;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
.mail_rechts {
    position:absolute;
    right:10px;
    text-align: left;
    margin-left:1px;
    width:40%;
}
.mail_links {
    position:absolute;
    left:0;
    padding:0;
    width:58%;
}
.mail_rechts1 {
    display:block;
    text-align: left;
    margin-left:1px;
    width:100%;
}
.mail_links1 {
    height:auto;
    float:left;
    width:65%;
    border-collapse:collapse;
    border-right:4px solid #ffffff;
}
input.mail_formf {
    float:left;
    text-align: left;
    margin-left: 5px;
    margin-right: 5px;
    margin-top: 5px;
    border:1px solid #000000;
}
textarea.mail_form {
    text-align: left;
    margin-left: 5px;
    margin-right: 5px;
    margin-top: 10px;
    margin-bottom: 10px;
    width:75%;
    background-color:#ffffff;
    border:1px solid #000000;
}
select.mail_form {
    float:left;
    width:75%;
    text-align: left;
    margin-left: 5px;
    margin-right: 5px;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
select.mail_mul {
    float:left;
    width:97%;
    height:400px;
    text-align: left;
    margin-left: 5px;
    margin-right: 5px;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
label.mail_form {
    float:left;
    text-align:left;
    width:20%;
    margin-left:5px;
    margin-top:5px;
}
label.mail_forms {
    float:left;
    text-align: left;
    width:15%;
    margin-left:5px;
    margin-top:5px;
}
label.mail_forml {
    float:left;
    text-align: left;
    width:90%;
    margin-left:5px;
    margin-top:5px;
}
label.mail_formll {
    text-align: left;
    width:90%;
    margin-left:5px;
    margin-top:5px;
}
.mail_form_check {
    margin-left:21%;
    text-align: left;
    margin-top:5px;
    margin-right:5px;
}
input.formk {
    text-align: left;
    margin-right: 5px;
    max-width:60%;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
div.formk {
    float:left;
    text-align: left;
    margin-right: 5px;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
form.formk {
    float:left;
    text-align: left;
    margin-right: 5px;
    max-width:60%;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
select.formk {
    max-width:60%;
    float:left;
    text-align: left;
    margin-right: 5px;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
label.formk {
    float:left;
    text-align: left;
    width:13%;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}
span.formk {
    float:left;
    text-align: left;
    width:13%;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}
label.file {
    float:left;
    width:50%;
    text-align: left;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}

br.clear {
    clear:both;
}

.col4 {
    margin-top:-15px;
    margin-right:20px;
    float: right;
    font-weight:normal;
    font-size:11px;
    height: 100%;
    color:#000000
}
.col5 {
    margin-right:2em;
    margin-left:2em;
    font-weight:normal;
    font-size:11px;
    color:#000000
}
.filter {
    float:left;
    margin-left: 10px;
    padding-right: 10px;
    padding-left: 10px;
    padding-top: 5px;
    height:25px;
    font-size:11px;
    border-right-color:#000000;
    border-right-style:dashed;
    border-right-width:thin;
    font-weight:normal;
    color: #000000;
}

.fdis {
    clear:both;
    float:left;
    margin-left: 10px;
    padding-right: 10px;
    padding-left: 10px;
    padding-top: 1px;
    height:19px;
    font-size:11px;
    border-right-color:#ffffff;
    border-right-style:dashed;
    border-right-width:thin;
    font-weight:normal;
    color: #000000;
}
.sort {
    float:left;
    margin-left: 10px;
    padding-right: 10px;
    padding-left: 10px;
    padding-top: 1px;
    height:19px;
    font-size:11px;
    font-weight:normal;
    color: #000000;
}
.co1bw {
    margin-left: 10px;
    padding-right: 10px;
    padding-left: 10px;
    border-right-color:#ffffff;
    border-right-style:dashed;
    border-right-width:thin;
    height: 100%;
    color: #000000;
}
.col3b {
    margin-left: 10px;
    padding-right: 10px;
    height: 100%;
    color: #000000;
}
/* first col in module header div, e.g. module name */
.co1 {
    margin-left: 1em;
    color:  <?php echo $cfg_column_main_color; ?>;
    /*background-color:orange;*/
}
/* FIXIT -> delete me, I am senseless */
.co1b {
    /*background-color:pink;*/
/*

    margin-left:1em;
    padding-left:1em;
    border-right-style:dashed;
    border-right-width:1px;
    height: 100%;
    color: #000000;
    */
}
.co1w {
    margin-left: 10px;
    padding-right: 10px;
    border-right-color:#ffffff;
    border-right-style:dashed;
    border-right-width:thin;
    width:40px;
    font-weight:bold;
    height: 100%;
    color:  <?php echo $cfg_column_main_color; ?>;
}
.co {
    margin-left: 10px;
    padding-right: 10px;
    height: 100%;
    color:  <?php echo $cfg_column_main_color; ?>;
}
.co2 {
    font-size:11px;
    margin-left: 10px;
    padding-right: 10px;
    height: 100%;
    color:  <?php echo $cfg_column_main_color; ?>;
}
.col3 {
    margin-left: 10px;
    padding-right: 10px;
    height: 100%;
    color:  <?php echo $cfg_column_main_color; ?>;
}

thead th, tfoot td {
    margin:0 0;
    padding:5px 5px;
    font-weight:bold;
    font-size:90%;
    background: <?php echo $cfg_column_main_color; ?>;
    color: #ffffff;
}

table.contextmenu {
    margin:0;
    padding:0;
    border-width:1px;
    border-color:#ffffff;
    background-color:#D4D0C8;
}
table.opt {
    width:85%;
    border: 1px solid rgb(255,255,255);
    padding: 0;
    background-color: rgb(239,239,239);
}

table.chat td {
    width:inherit;
    border: 0px;
    padding: 0px;
    background:#ffffff ;
    text-align:left;
}

.chats {
    width:60px;
    background: rgb(239,239,239);
    border: 0px;
    padding: 0px;
}

opts {
    width:100%;
    padding: 5px;
}
.book1 {
    background: rgb(250,228,184);
    background-color:rgb(250,228,184);
    border:0;
    padding:0;
    margin:0;
    color: #000000;
}
.book2 {
    margin:0;
    padding:0;
    background: rgb(210,233,255);
    color: #000000;
}
tr.book1 td {
    margin:0;
    padding:0;
    background: rgb(250,228,184);
    color: #000000;
}
tr.book2 td {
    margin:0;
    padding:0;
    background: rgb(210,233,255);
    color: #000000;
}
.column-1 {
    width:60%;
    text-align:left;
    padding-left:10px;
}
.column2 {
    text-align:left;
    padding-left:10px;
}
.button {
    font-size:1em;
    width:9.5em;
    background-color: <?php echo $cfg_column_main_color; ?>;
    color: #ffffff;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    border-width: 4px;
    height: 25px;
    cursor: pointer;
    font-weight:bold;
}

.buttonklein {
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;

    font-size: 60%;
    font-weight:lighter;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 18px;
    cursor: pointer;
    margin-right: 2px;
}
.chat1 {
    position:absolute;
    bottom:30px;
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;

    font-size: 70%;
    font-weight:lighter;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 23px;
    cursor: pointer;
    margin-left: 5px;
    margin-bottom:15px;
}
.chat_einzeilig {
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;

    font-size: 70%;
    font-weight:lighter;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 23px;
    cursor: pointer;
    margin-left: 5px;
    margin-bottom:15px;
}
/* button start working time */
.buttonstart {
    background-color: #74B548;
    border-top-color:#B0D698;
    border-left-color:#B0D698;
    border-right-color:#5C9139;
    border-bottom-color:#5C9139;
    text-decoration: none;
}
/* button stop working time */
.buttonstop {
    background-color: rgb(194,5,10);
    border-top-color:rgb(242,201,202);
    border-left-color:rgb(242,201,202);
    border-right-color:rgb(149,4,8);
    border-bottom-color:rgb(149,4,8);
    text-decoration: none;
}
.button2,
input.searchbutton {
    margin-top:5px;
    margin-bottom:5px;
    margin-left:0px;
    margin-right:5px;
    padding-left:20px;
    padding-right:20px;
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;

    font-size: 90%;
    font-weight:bolder;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 20px;
    cursor: pointer;
    width: auto;
}
.button2small{
    margin-bottom:3px;
    margin-top:0px;
    margin-left:5px;
    margin-right:0px;
    padding-left:2px;
    padding-right:2px;
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 1px;
    color: #ffffff;
    font-size: 70%;
    font-weight:bolder;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 13px;
    cursor: pointer;
    width: auto;
}
.button2b {
    margin-left:0px;
    margin-right:5px;
    padding-left:20px;
    padding-right:20px;
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;

    font-size: 90%;
    font-weight:bolder;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 20px;
    cursor: pointer;
    width: auto;
}
/* search button in menu */
input.searchbutton {
    padding-left:5px;
    padding-right:5px;
    margin-left:0px;
}
.button2sel {
    padding-left:20px;
    padding-right:20px;
    background-color: #dc6417;
    border-width: 2px;
    color: #ffffff;

    font-size: 90%;
    font-weight:bolder;
    border-style:solid;
    border-top-color:#edb18a;
    border-left-color:#edb18a;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 20px;
    cursor: pointer;
    width: auto;
}
.button3 {
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;

    font-size: 90%;
    font-weight:bold;
    border-style:solid;
    padding: 0px;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 20px;
    cursor: pointer;
    width: auto;
}
.button4 {
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 0px;
    border-right-width:2px;
    border-right-color:#000000;
    border-right-style:solid;
    color: #ffffff;
    font-size: 1em;
    font-weight: 600;
    height: 23px;
    cursor: pointer;
    margin-left:10px;
    margin-top:7px;
    margin-right: 2px;
    margin-bottom:0px;
}
.button5 {
    background-color: #FFFFFF;
    border-width: 0px;
    border-right-width:1px;
    border-right-color:#000000;
    border-right-style:solid;
    color: #607080;

    font-size: 1em;
    font-weight: 600;
    height: 23px;
    cursor: pointer;
    margin-left:10px;
    margin-top:7px;
    margin-right: 2px;
    margin-bottom:0px;
}
tr.ruled {
    background: rgb(189,223,255);
}
tr.unev {
    background: rgb(194, 194, 194);
}
tbody tr {
    background: rgb(213,213,213);
}
tbody td {
    text-align: left;
}
label.elfpx {
    margin-right: 5px;
    font-size:11px;
    font-weight: normal;
}
label.center {
    text-align: right;
    width: 13%;
    margin: 5px;
    float:left;
}
label.nixcenter {
    text-align: left;
    margin-left: 15%;
    padding-left:5px;
    margin-bottom:11px;
    /*color:red;*/
    font-size:11px;
    font-weight:bold;
    margin-top:-8px;
}
label.center2  {
    text-align: right;
    width: 110px;
    margin-right: 10px;
    float:left;
}

.a1 {
    float:left;
    text-align: left;
    margin-right: 50px;
    margin-left:10px;
}
.lf {
    float:left;
    width:auto;
    height:auto;
    margin-right: 5px;
    margin-left: 5px;
}

/*************************************
          summary stuff
*************************************/
div.summary_box{
    width:100%;
    background-color:#EFEFEF;
    padding-bottom:3px;
}
/*************************************
        calendar stuff
*************************************/
div.calendar_ctrl {
    <?php echo ($printmode) ? 'display: none;' : ''; ?>
    margin: 0px;
    top: 0px;
    left: 0px;
}
div.calendar_datepicker {
    margin: 5px;
    top: 0px;
    left: 0px;
    width: 300px;
    float: left;
}
div.calendar_createevent {
    margin: 5px;
    top: 0px;
    width: 350px;
    float: left;
}
div.calendar_view {
    margin: 0;
    left: 0px;
    clear: both;
}
div.calendar_tabs_area, div.export_tabs_area, div.tabs_area {
    padding:0px;
    margin:0px;
    height:24px;
    font-size:0px;
    overflow:hidden;
}
div.tabs_area_left,
div.tabs_area_modname {
    float:left;
}
div.tabs_area_right {
    float:right;
    text-align:right;
}
div.tabs_area_modname {
    width:120px;
}
a.calendar_top_area_tabs_active,
a.calendar_top_area_tabs_active:hover,
a.calendar_top_area_tabs_active:visited,
a.export_top_area_tabs_active,
a.tab_active,
a.tab_active:hover,
a.tab_active:visited {
    font-size:12px;
    vertical-align:bottom;
    background-color: #dc6417;
    line-height:34px;
    color:#FFFFFF;
    font-weight:bold;
    padding:0em;
    padding-left:1em;
    padding-right:1em;
    margin-right:15px;
    border-right-width:1px;
    border-right-color:#333333;
    border-right-style:solid;
}
a.calendar_top_area_tabs_inactive,
a.calendar_top_area_tabs_inactive:hover,
a.calendar_top_area_tabs_inactive:visited,
a.tab_inactive,
a.tab_inactive:hover,
a.tab_inactive:visited {
    font-size:12px;
    vertical-align:bottom;
    background-color: rgb(239,239,239);
    line-height:34px;
    color:#000000;
    font-weight:normal;
    padding:0em;
    padding-left:1em;
    padding-right:1em;
    margin-right:15px;
    border-right-width:1px;
    border-right-color:#333333;
    border-right-style:solid;
}
span.calendar_tabs_area, span.export_tabs_area, span.tabs_area, span.tabs_area_modname {
    font-size:12px;
    vertical-align:bottom;
    line-height:34px;
    color:#FFFFFF;
    margin:0px;
    margin-left:10px;
    margin-right:10px;
}
span.tabs_area {
    vertical-align:middle;
    line-height:25px;
    font-size:12px;
    color:#FFFFFF;
    margin:0px;
    margin-left:10px;
    margin-right:10px;
}
span.tabs_area_modname, span.modname {
    padding-left:1em;
    color:#dc6417;
    font-weight:bold;
}
span.modname{
    padding-left:4px;
}
div.calendar_nav_area,
div.nav_area {
    width:100%;
    background-color: rgb(239,239,239);
    padding:0px;
    margin:0px;
    height:34px;
    font-size:0px;
    overflow:visible;
}
span.calendar_nav_area,
span.nav_area {
    font-size:12px;
    vertical-align:middle;
    line-height:34px;
    margin:0px;
    margin-left:1.8em;
    color:#000000;
}
div.calendar_date_selection_area {
    width:100%;
    background-color: rgb(239,239,239);
    height:34px;
    padding:0px;
    margin:0px;
    overflow:hidden;
    font-size:0px;
}
span.calendar_date_selection_area {
    font-size: 12px;
    height: 100%;
    vertical-align: middle;
    line-height: 34px;
    margin: 0px;
    margin-left: 10px;
    color: #000000;
}

.calendar_table {
    border: 0px solid rgb(255,255,255);
    background: #929292;
    margin-left: 0em;
}
.calendar_table td {
    background-color: #EFEFEF;
}

.calendar_pick1 {
    font-weight: bold;
    text-align: right;
}
.calendar_pick2 {
    font-weight: bold;
    text-align: right;
    font-style: italic;
}
.calendar_pick3 {
    text-align: right;
    color: gray;
}
.calendar_pick4 {
    font-weight: bold;
    text-align: right;
    color: red;
    background-color: #FFFFFF;
}

td.calendar_year {
    padding: 1px;
    text-align: left;
    vertical-align: top;
}
td.calendar_year_header {
    text-align: center;
    background-color: #FDFDFD;
}
td.calendar_year_day {
    color: black;
    background-color: #EFEFEF;
}
td.calendar_year_event {
    background-color: #FDFDFD;
}
td.calendar_year_empty {
    background-color: #FDFDFD;
}
table.calendar_year_month {
    margin: 2px;
    border: 1px solid transparent;
    background-color: #C0C0C0;
}
table.calendar_year_current_month {
    margin: 2px;
    border: 1px solid <?php echo $cfg_column_main_color; ?>;
    background-color: <?php echo $cfg_column_main_color; ?>;
}
table.calendar_year_selected_month {
    margin: 2px;
    border: 1px solid #EAF8F7;
}

.calendar_month_nolink {
    color: black;
}
td.calendar_month {
    padding: 1px;
    text-align: left;
    vertical-align: top;
    color: gray;
    border: 1px solid transparent;
    height: 7.5em;
}
td.calendar_month_weekday {
    font-style: italic;
}

.calendar_week,
a.calendar_week,
a.calendar_week:hover,
a.calendar_week:visited {
    padding: 1px;
    vertical-align: top;
    text-align: left;
    color: white;
}
td.calendar_week_title {
    padding: 1px;
    border: 1px solid transparent;
    vertical-align: top;
}

td.calendar_day {
    padding: 1px;
    text-align: left;
    vertical-align: top;
}

td.calendar_day_current {
    background-color: #FDFDFD;
}
td.calendar_day_today {
    border: 1px solid <?php echo $cfg_column_main_color; ?>;
    background-color: #DAE8F7;
}
td.calendar_day_sameday {
    background-color: #EAF8F7;
}
td.calendar_day_weekend {
    background-color: #FFF9EF;
}
td.calendar_day_prevnext {
    background-color: #EFEFEF;
}
.calendar_day_event,
a.calendar_day_event,
a.calendar_day_event:hover,
a.calendar_day_event:visited {
    padding: 1px;
    color: white;
}

td.calendar_event_open, tr.calendar_event_open {
    background-color: #6699FF;
}
td.calendar_event_accept, tr.calendar_event_accept {
    background-color: #55BB66;
}
td.calendar_event_reject, tr.calendar_event_reject {
    background-color: #FF5544;
}
td.calendar_event_canceled, tr.calendar_event_canceled {
    background-color: #888888;
}

.calendar_holiday_anywhere {
    color: red;
    font-weight: normal;
}
.calendar_holiday_somewhere {
    color: green;
    font-weight: normal;
}
.calendar_holiday_nonfree {
    color: gray;
    font-weight: normal;
}

div.calendar_box {
    margin: 0px;
    margin-left: 0.5em;
    padding: 0px;
    background-color: rgb(239,239,239);
    height: 38em;
}
div.calendar_box_header {
    background-color: <?php echo $cfg_column_main_color; ?>;
    padding-left: 1em;
    padding-right: 1em;
    color: #FFFFFF;
    font-weight: bold;
    vertical-align: middle;
    text-align: center;
    height: 1.0em;
    padding-top: 0.2em;
    padding-bottom: 0.3em;
    line-height:1.0em;
}
div.calendar_box_header_left {
    float:left;
    width:50%;
    height:100%;
    text-align:left;
}
div.calendar_box_header_right {
    float:left;
    width:50%;
    height:100%;
    text-align:right;
}
fieldset.calendar {
    margin:0;
    background-color: rgb(239,239,239);
    /*height:400px;*/
    height:37em;
    border:0px solid #000000;
}
div.calendar_form_left {
    float:left;
    width:53%;
    background-color: rgb(239,239,239);
    padding-left:0.4em;
}
div.calendar_form_right {
    float:right;
    width:46%;
    background-color:red;
    background-color: rgb(239,239,239);
}
input.calendar {
    font-size:1.1em;
    float:left;
    text-align: left;
    margin-left: 10px;
    margin-right: 5px;
    margin-top: 5px;
    background-color:#FFFFFF;
    border:1px solid #000000;
}
label.calendar {
    float:left;
    text-align: left;
    width:6em;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}
label.calendarsmall {
    width:1em;
    margin-left:19px;
/*
    float:left;
    text-align: left;


    margin-right:5px;
    margin-top:5px;
*/
}
select.calendar {
    float:left;
    max-width:60%;
    text-align: left;
    margin-left: 10px;
    margin-right: 5px;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
textarea.calendar {
    text-align: left;
    padding:0px;
    margin-left: 10px;
    margin-right: 5px;
    margin-top: 10px;
    margin-bottom: 10px;
    width:320px;
    background-color:#ffffff;
    border:1px solid #000000;
}
span.calendar_options{
    line-height:3em;
    margin-left:7.7em;
}

/*** end calendar stuff ***/

/*** begin todo stuff ***/
tr.todo_deadline_exceeded {
    background-color: #FF5544;
}
/*** end todo stuff ***/


/*************************************
        search stuff
*************************************/
span.search_options,
input.search_options {
    float:left;
    margin-left:5px;
    margin-bottom:8px;
    width:11em;
    /*float: right;
    margin-left: 5px;
    margin-bottom: 10px;*/
}
/*** end search stuff ***/


input.center {
    float:right;
    text-align: left;
    margin-left: 5px;
    width: 70%;
    margin-right: 100px;
    margin-bottom: 10px;
    background-color:#ffffff;
    border:1px solid #000000;
}
span.center {
    float:right;
    text-align: left;
    margin-left: 5px;
    width: 70%;
    margin-right: 100px;
    margin-bottom: 10px;
}
select.center {
    text-align: left;
    width: 70%;
    margin-left: 8px;
    margin-bottom: 10px;
    background-color:#ffffff;
    border:1px solid #000000;
}
select{
    /*background-color:red;*/
    font-size:1em;
}
select.options {
    text-align: left;
    margin-bottom: 10px;
    background-color:#ffffff;
    border:1px solid #000000;
}

div.options {
    float: right;
    margin-left: 5px;
    margin-bottom: 10px;
}
input.options {
    text-align: left;
    margin-bottom: 10px;
    background-color:#ffffff;
    border:1px solid #000000;
}
legend.options {
    width:inherit;
    text-align: center;
    margin-left: 5px;
    margin-top: 10px;
    font-weight:bold;
}

label.options {
    float:left;
    width:350px;
    text-align: left;
    margin-bottom: 10px;
}
input.elfpx {
    margin-left: 5px;
    margin-right: 5px;
    font-size:11px;
    font-weight: normal;
    border:1px solid #000000;
}
textarea.center {
    float:right;
    text-align: left;
    margin-left: 5px;
    margin-right: 100px;
    width: 70%;
    margin-bottom: 10px;
    background-color:#ffffff;
    border:1px solid #000000;
}
.clear {
    clear:both;
}
#right {
    float:right;
    margin-right: 100px;
}
.right {
    margin-top: -15px;
    float:right;
    margin-right: 20px;
}
#right1 {
    float:right;
    margin-right: 20px;
    margin-top:5px;
}
.right2 {
    float:right;
    margin-right: 10px;
    margin-left: 20px;
    margin-top: -20px;
}
#left {
    float:left;
    margin-right:10px;
    height:auto;
    width:100px;
}
#lef {
    text-align:left;
    margin-right:10px;
    height:auto;
    position:
    relative;
    width:70%
}
legend.ac {
    float:left;
    margin-right:10px;
    height:auto;
    width:90px;
}
#center {
    float:left;
    margin-right:10px;
    height:auto;
    width:auto;
}

input.left {
    font-size:1em;
    float: left;
    text-align: left;
    /*width: 7em;*/
    margin: 0.2em;
}

input.right, select.right {
    margin-left: 7.3em;
}
.inv {
    visibility:hidden;
    background: rgb(239,239,239);
}
.mail_header1 {
    width:98%;
    margin-top: 10px;
    padding-left:2%;
    height:20px;
    margin-left:10px;
    background: <?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    text-align:left;
    background-color: <?php echo $cfg_column_main_color; ?>;
    margin-bottom:0px;
}
.mail_header {
    width:100%;
    margin-top: 10px;
    padding-left:2%;
    height:20px;
    margin-left:10px;
    background: <?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    text-align:left;
    background-color: <?php echo $cfg_column_main_color; ?>;
    margin-bottom:0px;
}
#ankerunten {
    font-size:70%;
    color:#000000;
    font-weight:bold;
    text-decoration:underline;
}
.container {
    width:100%;
    background-color:transparent;
}
.contA {
    margin-left:10px;
    height:20px;
    background-color: <?php echo $cfg_column_main_color; ?>;
}
.contB {
    margin-left:15px;
    height:20px;
    color:#FFFFFF;
    font-weight:normal;
}
.contC {
    overflow:visible;
    margin-left:10px;
    background: rgb(239,239,239);
}
.contD {
    margin-left:15px;
    color:#000000;
    font-weight:normal;
}


.cen {
    text-align:center;
}
.mail_formbody1 {
    padding:0;
    background-color: rgb(239,239,239);
    margin-left: 10px;
    width:100%;
    margin-top:0px;
}

.normal1 {
    padding-bottom:15px;
    background-color: #FFFFFF;
    margin-left: 10px;
    width:30%;
    height:inherit;
    float:right;
    margin-top:0px;
    vertical-align:baseline;
    text-align: right;
}
.normal {
    padding-bottom:10px;
    background-color: #FFFFFF;
    width:70%;
    margin-left: 10px;
    text-align: left;
}
#mittig {
    clear:both;
    top: 10px;
    height:20px;
    margin-top: -10px;
}
a.und {
    text-decoration:underline;
    color: #000000;
    cursor: pointer;
    font-weight:normal;
}
a.pa,
a.pa:visited {
    text-decoration:underline;
    color: #000000;
    cursor: pointer;
    font-weight:normal;
}
a.un {
    text-decoration:none;
    color: #000000;
    cursor: pointer;
    font-weight:bold;
}
div.error, span.error {
    color:#FF0000;
    font-weight:bold;
}
div.notice, span.notice {
    color:#33CC33;
    font-weight:bold;
}
div.adminleft {
    position:absolute;
    top:0;
    z-index:0;
    left:180px;
    width:450px;
    height:100%;
}
div.adminright {
    position:absolute;
    top:0;
    z-index:0;
    left:630px;
    width:650px;
    height:100%;
    background-color:#EEEEEE;
}

ul {
    margin:0;
    padding:0;
    list-style-type:none;
    padding-left:5px;
}

li.bld {
    font-weight:bold;
    font-size:110%;
}
/* content container */
div.outer_content {
    position:absolute;
    left:0px;
    top:0px;
    width:100%;
    height:100%;
}
div.content {
    margin-left: <?php echo ($printmode) ? '0' : '130'; ?>px;
    height: 100%;

}
div.inner_content{
    margin:0px;
    margin-left:0.5em;
    margin-right:0.5em;
    padding:0px;
    background-color:transparent;
    /*height:50px;*/
    height:100%;
}

div.gantt{
float:left;
width:100px;
margin-left:10px;
margin-top:2px;
margin-right:10px;
}
/* container for navigation entries (menu) */
div.navi {
    <?php echo ($printmode) ? 'display: none;' : ''; ?>
    float:left;
    position:absolute;
    overflow:hidden;
    height:auto;
    top:0px;
    left:0;
    z-index:1;
    border:none;
    padding:0;
    margin:0;
    background-color:#EFEFEF;
    /*width:179px;*/
    width:130px;
    background-image:url(../../layout/default/img/background.png);
    background-repeat:repeat-y;
}
/* menu list entries selected */
li.navs {
    margin-right:1px;
    margin-left:1px;
    padding-left:8px;
    list-style-type:none;
    border-bottom:1px solid #ffffff;
    padding-top:3px;
    padding-bottom:3px;
}
/* selected nav entry */
.selected, a.selected, a.selected:visited{
    background-color: #EFEFEF;
    color: #dc6417;
    font-weight: bold;
    font-size: 1em;
    vertical-align: bottom;
}
/* not selected nav entry */
.notselected, a.notselected ,a.notselected:visited {
    background-color: #EFEFEF;
    color: #000000;
    font-weight: normal;
    font-size: 1em;
    vertical-align: bottom;
}

.p2 {
    font-size:11px;
    text-align:center;
    width:250px;
    word-spacing: 2px;
}

/*************************************
            FORM FIELDS
*************************************/
/* textfields in filter menues */
input[type=text].filter {
    border-width:0px;
}
input.form, input[type=file].form{
    font-size:1em;
    float:left;
    text-align: left;
    margin-left: 10px;
    margin-right: 5px;
    width:60%;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
input[type=file].form {
    float:none;
    width:auto;
}
input.smallinput{
    width:auto;
}
label.form {
    float:left;
    text-align: left;
    width:21%;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}
span.form {
    float:left;
    text-align: left;
    width:21%;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}
label.formsmall {
    float:left;
    text-align: left;
    width:10%;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}
/* group select field */
select.groupselect {
    font-size:1em;
    width:7.5em;
}
select.form {
    float:left;
    max-width:60%;
    text-align: left;
    margin-left: 10px;
    margin-right: 5px;
    margin-top: 5px;
    background-color:#ffffff;
    border:1px solid #000000;
}
textarea.form {
    text-align: left;
    margin-left: 10px;
    margin-right: 5px;
    margin-top: 10px;
    margin-bottom: 10px;
    width:60%;
    background-color:#ffffff;
    border:1px solid #000000;
}
select.projectCat {
    text-align: left;
    margin-left: 8px;
    margin-bottom: 10px;
    background-color:#ffffff;
    border:1px solid #000000;
}
div.buttons,
div.buttons_bottom {
    background: rgb(239,239,239);
    margin-left:1em;
    padding: 5px 0px 5px 0px;
    font-weight:bold;
    text-align:left;
    border-bottom-width: 1px;
    border-bottom-style: solid;
    border-bottom-color: #000000;
    border-top-width: 1px;
    border-top-style: solid;
    border-top-color: #000000;
    height: 25px;
}

/*************************************
            NAVIGATION
*************************************/
.globalnav {
    background-color: transparent;
    padding: 0px 0px 0px 0px;
    white-space: nowrap;
    list-style: none;
    margin: 0px;
    line-height: normal;
}
.globalnav a:hover {
    background-color: #FF00FF;
    border-color: #0000FF;
    border-bottom-color: #00FF00;
    color: #FF0000;
}
/*************************************
                 LINKS
*************************************/
a.formBoxHeader,
a.formBoxHeader:visited {
    text-decoration:underline;
    color: #FFFFFF;
    cursor: pointer;
    font-weight:normal;
}
/*************************************
                 TEXTS
*************************************/

div.formBodyRow {
    width:100%;
    height:auto;
    border-bottom:1px solid #ffffff;
    border-top:2px solid #ffffff;
    border-collapse:collapse;
    clear:both;
}
div.formBodyLeft {

    clear:both;
    float:left;
    height:65px;
    width:50%;
    border-collapse:collapse;
    border-right:2px solid #ffffff;
    margin:0px;
    margin-left:-4px;
}
div.formBodyRight {
    float:left;
    width:50%;;
    border-collapse:collapse;
}
/* fieldset for login form */
fieldset.login {
    width: 35em;
    height: 8em;
    padding:0em;
    margin: auto;
    background: #FFFFFF;
    border: 1px solid;
}
/* fieldset for search in menu navigation */
fieldset.navsearch {
    margin:0;
    padding:0;
    background-color:red;
    margin-left:-5px;
/*
    margin:0;
    margin-left:-5px;
    padding:0;
    text-align:left;
    border: 0;
*/
}

/* label login form */
label.login {
    clear:both;
    float: left;
    text-align: right;
    width: 6.3em;
    margin: 0.4em;
}
input.login {
    font-family: Arial, Helvetica, sans-serif;
    float: left;
    clear: both;
    margin-left: 7.2em;
    height:2em;
/*
    float: left;
    width: auto;
    clear: both;
    margin-left: 7.3em;
    margin-top: 0.5em;
    margin-bottom:.0.5em;
*/
}
.relObjHead {
    background-color: rgb(239,239,239);
    height: 2.2em;
    padding-top:0.3em;
}
.relObjHeadStartCol {
    display:inline;
    color:  <?php echo $cfg_column_main_color; ?>;
    font-weight:bold;
    padding-left:1em;
    padding-right:1em;
    border-right-color:#ffffff;
    border-right-style:dashed;
    border-right-width:thin;
}
.relObjHeadNextCol {
    color: #000000;
    width:10em;
    padding-left:1em;
    padding-right:1em;
    float:left;
    /*
    border-right-color:#ffffff;
    border-right-style:dashed;
    border-right-width:thin;*/
}
.relObjHeadFirstCol {
    color: #000000;
    padding-left:1em;
    padding-right:1em;
    float:left;
    /*
    border-right-color:#ffffff;
    border-right-style:dashed;
    border-right-width:thin;*/
}
.relObjHeadLastCol {
    margin-left: 10px;
    padding-right: 10px;
    height: 100%;
    color:  <?php echo $cfg_column_main_color; ?>;
}

.relObjFilter {
    clear:both;
    float:left;
    margin-left: 10px;
    padding-right: 10px;
    padding-left: 10px;
    padding-top: 1px;
    height:19px;
    font-size:11px;
    border-right-color:#ffffff;
    border-right-style:dashed;
    border-right-width:thin;
    font-weight:normal;
    color: #000000;
}
.relObjLeftAlign {
    float:left;
    width:70%;
}
.relObjRightAlign {
    float:right;
}
.inline {
    display:inline;
}
.center{
    text-align:center;
}

/*************************************
         COMMON STYLES
*************************************/
/* top header line in content container */
.topline, .tabs_bottom_line {
    width:100%;
    background-color: <?php echo $cfg_column_main_color; ?>;
    padding:0px;
    margin:0px;
    height:8px;
    font-size:0px;
}
.tabs_bottom_line {
    background-color: #dc6417;
}
/* horizontal line in content area */
div.hline {
    width:100%;
    font-size:0px;
    line-height:0px;
    border-bottom-width:1px;
    border-bottom-style: solid;
    border-bottom-color: #000000;
}
/* module title left to navigation buttons  */
.mod_title {
    margin-left:1em;
    font-weight:bold;
    color: <?php echo $cfg_column_main_color; ?>;
}
/* vertical line */
.strich {
    border-right-color:#000000;
    border-right-style:dashed;
    border-right-width:1px;
    margin-left:5px;
    margin-right:5px;
}
/* navigation buttons */
/*input[type=submit].navbutton,*/
.navbutton,
a.navbutton,
a.navbutton:hover,
a.navbutton:visited {
    position: relative;
    top: 2px;
    left: 3px;
    padding-top: 1px;
    padding-left: 10px;
    padding-right :10px;
    border-width: 2px;
    color: #ffffff;
    font-size: 1em;
    font-weight:bold;
    border-style:solid;
    cursor: pointer;
    width: auto;
    text-decoration: none;
}

/*input[type=submit].navbutton_active,*/
a.navbutton_active,
a.navbutton_active:hover,
a.navbutton_active:visited {
    background-color: #dc6417;
    border-top-color:#edb18a;
    border-left-color:#edb18a;
    border-right-color:#000000;
    border-bottom-color:#000000;
    text-decoration: none;
}
/* submit button */
input.submit, input.submit_disabled {
    font-size: 1em;
    height:1.6em;
    margin:0;
    margin-bottom: 1px ! important;
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    border-style:solid;
    color:#FFFFFF;
    font-weight:bold;
    cursor: pointer;
    padding: 0px 10px 0px 10px;
}
input.submit_disabled{
    background-color: rgb(150,150,150);
    border-top-color: rgb(200,200,200);
    border-left-color: rgb(200,200,200);
    color: rgb(100,100,100);
}

/*input[type=submit].navbutton_inactive,*/
a.navbutton_inactive,
a.navbutton_inactive:hover,
a.navbutton_inactive:visited {
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    text-decoration: none;
}
.visible {
    visibility:visible;
}
.hidden {
    visibility:hidden;
}

/*************************************
         Timecard STYLES
*************************************/

div.tc_left {
    width:45%;
    height:100%;
    /*background-color:#EEEEEE;*/
}
div.tc_right {
    float:right;
    width:55%;
    height:auto;
    /*background-color:#EEEEEE;*/
}
.tc_header {
    width: 96%;
    margin-top: 10px;
    padding-left:2%;
    height:20px;
    margin-left:2%;
    background: <?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    text-align:left;
    background-color: <?php echo $cfg_column_main_color; ?>;
    margin-bottom:0;
}

/*************************************
         ADMIN STYLES
*************************************/

div.admin_left {
    width:420px;
    height:100%;
    /*background-color:#EEEEEE;*/
}
div.admin_right {
    position:absolute;
    top:75px;
    z-index:0;
    left:555px;
    right:0;
    height:auto;
    /*background-color:#EEEEEE;*/
}
/**
div.admin_right {
    margin-left:3%;
    margin-top: 10px;
    height:100%;
   background-color:#EEEEEE;
}*/

.admin_header {
    width: 96%;
    margin-top: 10px;
    padding-left:2%;
    height:20px;
    margin-left:2%;
    background: <?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    text-align:left;
    background-color: <?php echo $cfg_column_main_color; ?>;
    margin-bottom:0;
}
.admin_header_right {
    position:absolute;
    top:47px;
    z-index:0;
    left:555px;
    right:0;
    height:20px;
    background: <?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    text-align:left;
    background-color: <?php echo $cfg_column_main_color; ?>;
    margin-bottom:0;
}
label.admin_label {
    float:left;
    text-align: left;
    width:26%;
    margin-left:5px;
    margin-right:5px;
    margin-top:5px;
}
.admin_fields {
    width:96%;
    margin-left:2%;
    padding-left:2%;
    padding-top:2%;
    padding-bottom:2%;
    color:#000000;
    background-color:#EEEEEE;
}
.admin_button {
    margin-top:5px;
    margin-bottom:5px;
    margin-left:0px;
    margin-right:5px;
    padding-left:5px;
    padding-right:5px;
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;

    font-size: 90%;
    font-weight:bolder;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
    height: 20px;
    cursor: pointer;
    width: auto;
}

/* navigation container -> global definitions */
div.nav {
    background-color:#EFEFEF;
    clear:both;
    height:32px;
}
/* navigation container below tabs */
span.navleft,
div.navleft {
    width:100%;
    background-color:#EFEFEF;
    clear:both;
    float:left;
    height:38px;
}
div.navright {
    display:none;
    width:20%;
    background-color:#EFEFEF;
    clear:both;
    float:right;
    text-align:right;
    height:38px;
}
/* navigation container without tabs */
span.navleft_notabs,
div.navleft_notabs {
    width:80%;
    float:left;
}
div.navright_notabs {
    width:20%;
    float:right;
    text-align:right;
}
/* nav area without tabs */
div.nav_area_notabs {
    width:100%;
    background-color: rgb(239,239,239);
    padding:0px;
    margin:0px;
    height:32px;
    font-size:0px;
    overflow:hidden;
}
span.nav_area_notabs {
    font-size:12px;
    vertical-align:middle;
    line-height:32px;
    margin:0px;
    margin-left:10px;
    color:#000000;
}
/* date in nav container */
span.navdate {
    font-weight:bold;
    line-height:35px;
    margin-right:10px;
}
.clearboth {
    clear:both;
}
/* errors */
div.error, span.error {
    color:#FF0000;
    font-weight:bold;
}
/* notices */
div.notice, span.notice {
    color:#33CC33;
    font-weight:bold;
}
/* warnings */
div.warning, span.warning {
    color:#FF7700;
    font-weight:bold;
}
/* box header */
div.box_header {
/*    margin-left:1.2em;*/
    background-color: <?php echo $cfg_column_main_color; ?>;
    height:1.5em;
    padding-top:0.5em;
    padding-bottom:0.4em;
    padding-left:1em;
    padding-right:1em;
    color:#FFFFFF;
    font-weight:bold;
    vertical-align:middle;
    text-align:left;
}
div.box_header_left {
    text-align:left;
    float:left;
    width:50%;
    height:100%;
    text-align:left;
}
div.box_header_right {
    float:left;
    width:50%;
    height:100%;
    text-align:right;
}
/* form area */
div.formbody {
    padding:0em;
    background-color: rgb(239,239,239);
}
div.formbody_mailops {
    padding:0em;
    width: 78%;
    padding-left:2%;
    margin-left:10px;
    background-color: rgb(239,239,239);
}
/* fieldset */
fieldset {
    /*background: rgb(239,239,239);*/
    background-color:transparent;
    margin-left:0px;
    padding-left:0px;
    border:none;
}
fieldset.settings {
    border:1px solid #000000;
    width:60%;
    padding-top:20px;
    margin-top:20px;
    padding-left:20px;
}
label.settings {
    float:left;
    text-align: left;
    width:20em;
    margin-left:0px;
    margin-right:5px;
    margin-top:0px;
}
label.formbody {
    margin:0;
    width:13em;
    float:left;
    margin-right:5em;
    /*padding-left:0em;*/
}
label.small{
    float:left;
    width:10em;
}
div.settings_options,
input.settings_options {
    float:left;
    margin-left:5px;
    margin-bottom:8px;
    width:11em;
/*
    float: right;
    margin-left: 5px;
    margin-bottom: 10px;
*/
}
select.settings_options {
    margin-left:5px;
}


input {
    vertical-align: middle;
    float: none;
    font-size: 1.1em;
    border: 1px solid #000000;
    background-color: #FFFFFF;
}

/* selector */
/* Tabelle um die beiden Selectboxen unten */
table.selector_select_multiple {
    background-color: rgb(239,239,239);
    border:0;
}
td.selector_select_multiple {
    background-color: rgb(239,239,239);
    vertical-align: middle;
}
/* select-boxes */
select.selector_select_multiple {
    width: 100%;
}

/* verschiebe-buttons zwischen den beiden select-boxen */
input.selector_mover {
    margin-left:5px;
    margin-right:5px;
    background-color: <?php echo $cfg_column_main_color; ?>;
    border-width: 2px;
    color: #ffffff;
    font-size: 90%;
    font-weight:bolder;
    border-style:solid;
    border-top-color:#76a6d6;
    border-left-color:#76a6d6;
    border-right-color:#000000;
    border-bottom-color:#000000;
}

span.selector_title {
    color: <?php echo $cfg_highlight_link_color; ?>;
    font-weight: bold;
}

/* Selector-Tabelle Kopf der Filter und Quickadds einschließt */
table.selector_head {
    border:0px;
    border-collapse:collapse;
    border-spacing:0px;
}
td.selector_head {
    padding:5px;
    background-color: rgb(239,239,239);
}

/* Submit-Button-Zelle im Selektor-Kopf */
td.selector_head_submit_cell {
    background-color: rgb(239,239,239);
    vertical-align:bottom;
    padding:5px;
    padding-bottom:15px;
}
/* Filter-Eigenschaften (Quickadd/Filter): Nested Tables */
table.selector_filter {
    border-spacing:0px;
    border:0px;
    padding-bottom:10px;
    margin:0px;
    background-color: rgb(239,239,239);
}
td.selector_filter {
    padding:2px;
    text-align:left;
    vertical-align:bottom;
    background-color: rgb(239,239,239);
}
select.selector_filter {}

.align-left {
 text-align: left;
}

/* filter and status bars */
div.filter_bar,
span.filter_bar,
div.filter_execute_bar,
span.filter_execute_bar,
div.filter_edit_bar,
span.filter_edit_bar,
div.status_bar,
span.status_bar,
div.path_bar,
span.path_bar {
    <?php echo ($printmode) ? 'display: none;' : ''; ?>
    background-color: rgb(211,217,223);
    width: 100%;
    height: 2.5em;
    vertical-align: middle;
    line-height: 2.5em;
}
span.filter_bar,
span.filter_execute_bar,
span.filter_edit_bar,
span.status_bar,
span.path_bar{
    padding-left:2em;
}
div.filter_execute_bar,
div.filter_edit_bar,
div.status_bar{
    border-bottom:1px solid #000000;
}
div.status_bar,
span.status_bar {
    font-weight:bold;
    height:auto;
}
div.path_bar,
span.path_bar {
    background-color: rgb(239,239,239);
}
/* page navigation bar */
div.page_navigation_bar {
    background-color: #BFD6ED;
    padding:0;
    margin:0;
    padding-left:1em;
    height:2.5em;

    overflow:hidden;
}
span.page_navigation_bar {
    vertical-align:middle;
    line-height:2.5em;
    font-size:12px;
    color:#000000;
    font-weight:bold;
    margin:0px;
    margin-left:10px;
    margin-right:10px;
}
div.page_navigation_bar_left {
    float:left;
}
div.page_navigation_bar_right {
    float:right;
    text-align:right;
}
/* pseudo table row */
div.tr {
    padding:2px;
    margin:0px;
}
/* pseudo table cell */
div.td {
    padding:0px;
    height:18px;
    overflow:hidden;
    margin-left:2px;
    margin-right:2px;
    padding-left:5%;
    position:relative;
    background-color:#D5D5D5;
    float:left;
    border:none;
    margin:1px;
}
/* pseudo table cell header */
div.tdhead {
    background-color:<?php echo $cfg_column_main_color; ?>;
    color:#FFF;
    font-weight:bold;
    height:1.8em;
    padding-top:0.4em;
}
.filter_fields {
    color: white;
    width:80%;
    background: transparent;
    background: <?php echo $cfg_column_main_color; ?>;
    border-color:white;
    margin-left:0%;
    padding:0%;
    font-size:12px;
}
.navsearchbox {
    margin-left: 0px;
    margin-right: 5px;
    border:1px solid #000000;
    font-size:1em;
    width:7.5em;
}
/***********************
 boxHeaders boxContents
***********************/
/* box header full box */
div.boxHeader {
    background-color: <?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    height:1.5em;
    width:98%;
    padding-left:1%;
    padding-right:1%;
}
/* box header half box left */
div.boxHeaderLeft,
div.boxHeaderSmallLeft {
    background-color:<?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    height:1.5em;
    float:left;
    width:49%;
    padding-left:1%;
}
/* box header half box right */
div.boxHeaderRight,
div.boxHeaderSmallRight {
    background-color:<?php echo $cfg_column_main_color; ?>;
    color:#FFFFFF;
    font-weight:bold;
    height:1.5em;
    float:right;
    width:49%;
    text-align:right;
    padding-right:1%;
}
/* content below boxHeader */
div.boxContent {
    background-color: rgb(239,239,239);
    float:left;
    width:98%;
    padding-left:1%;
    padding-right:1%;
}
/* content below boxHeaderLeft */
div.boxContentLeft,
div.boxContentSmallLeft {
    background-color: rgb(239,239,239);
    float:left;
    width:49%;
    padding-left:1%;
}
/* content below boxHeaderRight */
div.boxContentRight,
div.boxContentSmallRight {
    background-color: rgb(239,239,239);
    float:right;
    width:49%;
    padding-left:1%;
}

div.boxHeaderSmallLeft,
div.boxContentSmallLeft {
    width:48%;
}
div.boxHeaderSmallRight,
div.boxContentSmallRight {
    width:48%;
    text-align:left;
    padding-left:1%;
    padding-right:0;
}
div.chatContent{
    overflow:auto;
    background-color:#FFFFFF;
    width:80%;
    height:80%;
}
div.chatInput{
    border-top: 1px solid rgb(220, 100, 23);
    padding-top:0.5em;
    width:80%;
}
div.chatUsers{
    margin-top:1em;
    padding-top:1em;
    border: 1px solid rgb(220, 100, 23);
    background-color:#E2E2E2;
    float:right;
    width:18%;
    height:70%;
}


li.nav {
    list-style-type:none;
    margin-right:1px;
    margin-left:1px;
    padding-left:8px;
    padding-bottom:3px;
    padding-top:3px;
}
a.navLink, a.navLink:visited {
    color:#000000;
    font-weight:normal;
}
span.navLink, span.navLinkSelected {
    padding-top:0.5em;
    display:inline;
    line-height:1.8em;
}
span.navLinkSelected {
    color: <?php echo $cfg_highlight_link_color; ?>;
    font-weight:bold;
}
div.navImage {
    height:1em;
    float:left;
    margin-left:0.2em;
    margin-right:0.5em;

}
.navbr {
    clear:both;
}
.navSearchButton {
    width:15px;
    height:15px;
    background-image:url(img/search_button.gif);
    cursor:pointer;
}