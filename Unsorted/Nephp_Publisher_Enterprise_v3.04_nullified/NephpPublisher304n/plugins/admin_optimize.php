<?php
/*
 ####   ###        ###                  ##						  
  ####   #          ##                  ##						  
  #####  #          ##									  
  # ###  #   ####   ##    ###    #########    ###					  
  #  ### #   #  ##  ##   #   #  ##  ##  ##   #  ##					  
  #  #####  ######  ##  ##   ## ##  ##  ##  ##  ##					  
  #   ####  ##      ##  ##   ## ##  ##  ##  ##						  
  #    ###  ##   #  ##  ##   ##   ###   ##  ##						  
  #     ##  ###  #  ##   #   #  ##      ##  ##	 #
 ###     #   ####  ####   ###   ###### ####  ####
                                 ######		
                                ##   ##						  
                                 #####							  
  ########              ###                      ###                  ##		  
  #  ##  #               ##                       ##                  ##		  
     ##                  ##                       ##					  
     ##    ####    ###   ## ### ### ###    ###    ##    ###    #########   ####    ####  
     ##    #  ##  #  ##  ###  ## ###  ##  #   #   ##   #   #  ##  ##  ##   #  ##  ##  #  
     ##   ###### ##  ##  ##   ## ##   ## ##   ##  ##  ##   ## ##  ##  ##  ######  ####	 
     ##   ##     ##      ##   ## ##   ## ##   ##  ##  ##   ## ##  ##  ##  ##       ####  
     ##   ##   # ##      ##   ## ##   ## ##   ##  ##  ##   ##   ###   ##  ##   #  #  ##  
     ##   ###  # ###     ##   ## ##   ##  #   #   ##   #   #  ##      ##  ###  #  #  ##  
    ####   ####   ##### ###   #####   ###  ###   ####   ###   ###### ####  ####   ####	 
                                                               ######			 
                                                              ##   ##			 
                                                               ######			 
 Program name: NePublisher Server ( PHP EDITION )
 Version: v2.0
 April 26th, 2002
 Coded by: Kenny Ngo
 =======================================================================================
 Contact Information								
 -------------------									
                                                                                        
 DECLAIMER										  
 =========										  
 This program is protected by the US pattern services. Any illegal possession will	  
 persecuted under the law. The owner of this program may modify the coding to fit their  
 needs. Please keep in mind that if we find out that you share this program to others    
 without our permission, your license might be terminated. Thank you. */
///////////////////////////////////////////////////////////////////////////////
// Program Name         : Nephp Publisher Enterprise                         //
// Release Version      : 3.04                                               //
// Program Author       : Kenny Ngo     (CTO of Nelogic Technologies.)       //
// Program Author       : Ewdision Then (CEO of Nelogic Technologies.)       //
// Retail Price         : $499.00 United States Dollars                      //
// WebForum Price       : $000.00 Always 100% Free                           //
// ForumRu Price        : $000.00 Always 100% Free                           //
// xCGI Price           : $000.00 Always 100% Free                           //
// Supplied by          : Scoons [WTN]                                       //
// Nullified by         : CyKuH [WTN]                                        //
// Distribution         : via WebForum, ForumRU and associated file dumps    //
///////////////////////////////////////////////////////////////////////////////


if(!function_exists("_detect"))
{
	print "You can't make direct access to this file";
	exit();
}
function mod_optimize()
{
	global $_env,$sql_statement,$_cfig,$dir_template;
        global $url_image,$sql_table;

	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$dir_template/admin_header.html",0));
        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$dir_template/admin_optimize.html",0));
        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$dir_template/admin_footer.html",0));

	print "<script>document.ne_catdb.src=\"$url_image/processing.gif\";</script>";
	
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_catdb`;"))
	{
		print "<script>setTimeout(\"ne_catdb()\",300);function ne_catdb(){document.ne_catdb.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_catdb()\",300);function ne_catdb(){document.ne_catdb.src=\"$url_image/failed.gif\";}</script>";
	}		
	mysql_free_result($result);
	
	print "<script>document.ne_polls.src=\"$url_image/processing.gif\";</script>";
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_polls`;"))
	{
		print "<script>setTimeout(\"ne_polls()\",300);function ne_polls(){document.ne_polls.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_polls()\",400);function ne_polls(){document.ne_polls.src=\"$url_image/failed.gif\";}</script>";		
	}
	mysql_free_result($result);
	
	print "<script>document.ne_posts.src=\"$url_image/processing.gif\";</script>";
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_posts`;"))
	{
		print "<script>setTimeout(\"ne_posts()\",500);function ne_posts(){document.ne_posts.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_posts()\",500);function ne_posts(){document.ne_posts.src=\"$url_image/failed.gif\";}</script>";
	}
	mysql_free_result($result);
	
	print "<script>document.ne_rating.src=\"$url_image/processing.gif\";</script>";
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_ratings`;"))
	{
		print "<script>setTimeout(\"ne_rating()\",600);function ne_rating(){document.ne_rating.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_rating()\",600);function ne_rating(){document.ne_rating.src=\"$url_image/failed.gif\";}</script>";
	}
	mysql_free_result($result);
	
	print "<script>document.ne_reviews.src=\"$url_image/processing.gif\";</script>";
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_reviews`;"))
	{
		print "<script>setTimeout(\"ne_reviews()\",700);function ne_reviews(){document.ne_reviews.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_reviews()\",700);function ne_reviews(){document.ne_reviews.src=\"$url_image/failed.gif\";}</script>";
	}	
	mysql_free_result($result);
	
	print "<script>document.ne_session.src=\"$url_image/processing.gif\";</script>";
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_session`;"))
	{
		print "<script>setTimeout(\"ne_session()\",800);function ne_session(){document.ne_session.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_session()\",800);function ne_session(){document.ne_session.src=\"$url_image/failed.gif\";}</script>";
	}
	mysql_free_result($result);
	
	print "<script>document.ne_subdb.src=\"$url_image/processing.gif\";</script>";
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_subdb`;"))
	{
		print "<script>setTimeout(\"ne_subdb()\",900);function ne_subdb(){document.ne_subdb.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_subdb()\",900);function ne_subdb(){document.ne_subdb.src=\"$url_image/failed.gif\";}</script>";
	}
	mysql_free_result($result);
	
	print "<script>document.ne_users.src=\"$url_image/processing.gif\";</script>";
	if($result = mysql_query("FLUSH TABLE `$sql_table`.`ne_users`;"))
	{
		print "<script>setTimeout(\"ne_users()\",1000);function ne_users(){document.ne_users.src=\"$url_image/check.gif\";}</script>";
	}
	else
	{
		print "<script>setTimeout(\"ne_users()\",1000);function ne_users(){document.ne_users.src=\"$url_image/failed.gif\";}</script>";
	}
	mysql_free_result($result);	
	
}
?>
