<?php
/* 
   This file contain all data required to install CAT correct
   If you change any of this data you may cause the script to
   crash. Please don't change anything here unless you
   know what you're up to.
   
   If you want to add modules that uses MySQL and that you want
   to go with the standard setup then add the sql code to
   the end of this file. Please use the same error message
   as all the other query strings. 
   
   Version 1.3.4
   D-M-Y: 05-05-05 19:56
   
*/
    $sql = 'CREATE TABLE '.$db_prefix.'news (
     id int(3) NOT NULL auto_increment,
     dates varchar(50) NOT NULL,
     nickname varchar(25) NOT NULL,
     topic varchar(50) NOT NULL,
     newspost text NOT NULL,
     newstype varchar(15) NOT NULL,
     PRIMARY KEY (id)
    );';
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'news <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		    
    $sql = 'CREATE TABLE '.$db_prefix.'members (
     id int(3) NOT NULL auto_increment,
     name varchar(50) NOT NULL,
     nickname varchar(25) NOT NULL,
     picture varchar(255) NOT NULL,
     age char(2) NOT NULL,
     position varchar(15) NOT NULL,
     work varchar(255) NOT NULL,
     resolution varchar(15) NOT NULL,
     sex varchar(5) NOT NULL,
     quote varchar(25) NOT NULL,
     location varchar(255) NOT NULL,
     cpu varchar(15) NOT NULL,
     mouse varchar(15) NOT NULL,
     gfx varchar(255) NOT NULL,
     mousepad varchar(15) NOT NULL,
     memory varchar(15) NOT NULL,
     os varchar(15) NOT NULL,
     hdd varchar(15) NOT NULL,
     mail varchar(255) NOT NULL,
     screen varchar(15) NOT NULL,
     PRIMARY KEY (id)
    );';
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'crew <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

    $sql = 'CREATE TABLE '.$db_prefix.'game (
     id int(3) NOT NULL auto_increment,
     dates varchar(50) NOT NULL,
     team1 varchar(25) NOT NULL,
     team2 varchar(25) NOT NULL,
     point1 varchar(15) NOT NULL,
     point2 varchar(15) NOT NULL,
     type varchar(25) NOT NULL,
     map varchar(25) NOT NULL,
     lineup varchar(255) NOT NULL,
     report text NOT NULL,
     PRIMARY KEY (id)
    );';
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'game <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

    $sql = 'CREATE TABLE '.$db_prefix.'users (
     id int(3) NOT NULL auto_increment,
     username varchar(25) NOT NULL,
     password varchar(255) NOT NULL,
     admin INT(1) NOT NULL,
     PRIMARY KEY (id)
    );';
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'users <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		
		$sql = "INSERT INTO ".$db_prefix."users (username, password, admin) 
			VALUES ('$username','$password', '1')";
			
		$db->query($sql) or exit('An error occured while saving data into '.$db_prefix.'users <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

    // DO NOT CHANGE ANYTHING UNDER THIS LINE!
    // -------------------------------------
    
	$sql = 'CREATE TABLE '.$db_prefix.'script (
     version varchar(10) NOT NULL,
     installdate varchar(15) NOT NULL,
     PRIMARY KEY (version)
    );';
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'script <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
    
	$sql = "INSERT INTO ".$db_prefix."script (version, installdate) 
			VALUES ('$script','$date')";
			
		$db->query($sql) or exit('An error occured while saving data into '.$db_prefix.'script <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

    // -------------------------------------
    // DO NOT CHANGE ANYTHING ABOVE THIS LINE!
    
    $sql= 'CREATE TABLE '.$db_prefix.'motd (
     id int(3) NOT NULL auto_increment,
     dates varchar(50) NOT NULL,
     username varchar(25) NOT NULL,
     motd text NOT NULL,
     PRIMARY KEY (id)
    );';
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'motd <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

    $sql = "INSERT INTO ".$db_prefix."motd (id, dates, username, motd) 
			VALUES ('1', '$date', 'CAT', 'Success! You have now installed ClanAdmin Tools $script')";

    $db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'motd <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
    
		$sql = 'CREATE TABLE '.$db_prefix.'comments (
     id int(3) NOT NULL auto_increment,
     pid int(4) DEFAULT 0 NOT NULL,
     names varchar(100) NOT NULL,
     email varchar(255) NOT NULL,
     comment text NOT NULL,
     date varchar(20) NOT NULL,
     ip varchar(15) NOT NULL,
     PRIMARY KEY (id)
    );'; 
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'comments <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
    
    $sql = 'CREATE TABLE '.$db_prefix.'reported (
     id int(3) NOT NULL auto_increment,
     rid int(3) DEFAULT 0 NOT NULL,
     names varchar(100) NOT NULL,
     comment text NOT NULL,
     date varchar(20) NOT NULL,
     ip varchar(15) NOT NULL,
     PRIMARY KEY (id)
    );';
		
		$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'reported <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

    $sql = 'CREATE TABLE '.$db_prefix.'spons (
      id int(3) NOT NULL auto_increment,
      spons_cat int(3) NOT NULL default 0,
      spons_name varchar(255) NOT NULL,
      spons_info text NOT NULL,
      PRIMARY KEY (id)
    );';
    
    	$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'spons <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

    $sql = 'CREATE TABLE '.$db_prefix.'spons_cat (
      spons_cat int(3) NOT NULL auto_increment,
      spons_type varchar(255) NOT NULL,
      PRIMARY KEY (spons_cat)
    );';
    
    	$db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'spons_cat <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
    	
    $sql = 'CREATE TABLE '.$db_prefix.'online (
              user_id int(3) unsigned NOT NULL default 0,
              cookiesum varchar(255) NOT NULL default '',
              KEY user_id_online(user_id)
            ) TYPE=HEAP;';
            
        $db->query($sql) or exit('An error occured while installing DB '.$db_prefix.'online <br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

// ADD YOUR OWN SQL QUERIES
// ------------------------

// EOF
?>