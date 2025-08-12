<?php
/**
*   @script: newsletter example page
*	  @version: 1.0
*	  @author: Konstantin Atanasov
*	  @file: example_page.php
*
*
NO WARRANTY
 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, 
 THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES 
 PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
 THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. 
 THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, 
 REPAIR OR CORRECTION.
*/

    include_once "newsletter.php";
    include_once "config.php";
     
    
    global $con;
    
  /*
    // myusql connection parameters
    $db_host = "localhost";
    $db_name = " ";
    $db_user_name = " ";
    $db_user_pass = " ";
    
    
    // open mysql db connection
    $con = mysql_connect($db_host,$db_user_name,$db_user_pass);
  
    $db = mysql_select_db($db_name,$con);
  */  
    
    $news = new newsletter($con);
    // set newsletter page
    $news->url = "http://www.sourceworkshop.com/examples/newsletter1.0/example_page.php"; // url installed
    
    $news->parseCommands(); // parse commands from newssletter   
                            // subscribe or unsubscribe command
                            // 
 
?>

<HTML>
<HEAD>
    <META http-equiv=Content-Type content="text/html; charset=windows-1251">
    <META http-equiv=Expires content="0">
    <META content="example usage of configFile script" name=keywords>
    <STYLE></STYLE>
</HEAD>
<BODY>
<TABLE>
<TR><TD>&nbsp;<B> newsletter </B> example page </TD></TR>
<TR><TD>&nbsp;</TD></TR>
<TR><TD><A href='admin/admin.php' target='_new'>Admin panel</A> </TD></TR>
<TR><TD>&nbsp;</TD></TR>
<TR><TD>&nbsp;<?php  ECHO $news->showPanel(); ?></TD></TR>
</TABLE>
</BODY>
</HTML>


