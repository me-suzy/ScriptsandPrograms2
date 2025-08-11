
<?php
/*
Zircote Web Management
Copyright (C) 1999  Robert D. Allen <allenb@home-networking.org>

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

email: allenb@home-networking.org
*/
  error_reporting(0);   /* This Can be set to your preferance turned off  -0 PHP Default is -7 */

  if (!isset ($sess)) {
     page_open(array("sess"=>"slashSess"));   
     $usid = $sess->id;
     page_close();
     /*This determine if the the session has been establiblished- It provides for the session establishment in the 
       event it is a non-PHP called page i.e. HTML pages */
  } else {
     $usid = $sess->id; /*Here the session is made from the php page and we use the id as it is now */ 
  };

ereg("^([A-Za-z]+)/([0-9]+\.[0-9]+) (.*)$", $HTTP_USER_AGENT, $match);  /* Here we extract the raw USER AGENT it makes it easier for the many differant form that exist */
$Browser_Name = $match[1];               //This acquires the Mozzila Name
$Browser_Version = $match[2];            //This gets the Mozzilla version number
$Browser_Description = $match[3];        //And this gets the Remaining data i.e. Specific browser name OS and other extended data

if(eregi("htdig", $HTTP_USER_AGENT)) {   //This prevent the htdig:// search engine form being processed from your site as data
   exit;   
}

if(eregi("msie", $Browser_Description)) { // This will initiate a more specific MSIE data aquisition since MS reports itself as mozilla
   ereg("^\((.*); (.*) (.*); (.*) (.*)\)$",
   $Browser_Description, $match);
   $Browser_Name = $match[2];
   $Browser_Version = $match[3];
}

if(eregi("mozilla", $Browser_Name))  {   //This gets the OS for non MSIE browsers

   if(eregi("bsd",   $Browser_Description)) $OS = "BSD";
   if(eregi("linux", $Browser_Description)) $OS = "Linux";
   if(eregi("sun",   $Browser_Description)) $OS = "Sun OS";
   if(eregi("winnt", $Browser_Description)) $OS = "Windows NT";
   if(eregi("win95", $Browser_Description)) $OS = "Windows 95";
   if(eregi("win98", $Browser_Description)) $OS = "Windows 98";
   if(eregi("win32", $Browser_Description)) $OS = "Windows 32s";
   if(eregi("mac",   $Browser_Description)) $OS = "Macintosh PPC";
   if(eregi("windows", $Browser_Description)) $OS = "Windows";

   $Browser = "$Browser_Name $Browser_Version";
   $Browser = (eregi_replace("^mozilla", "Navigator", $Browser));

   if(eregi("opera 1", $Browser_Description)) $Browser = "Opera 1.X";
   if(eregi("opera 2", $Browser_Description)) $Browser = "Opera 2.X";
   if(eregi("opera 3", $Browser_Description)) $Browser = "Opera 3.X";

} else { //This will determine OS for MSIE Browsers

   if(eregi("MSIE 2", $Browser_Description)) $Browser = "MSIE 2.X"; 
   if(eregi("MSIE 3", $Browser_Description)) $Browser = "MSIE 3.X"; 
   if(eregi("MSIE 5", $Browser_Description)) $Browser = "MSIE 5.X";
   if(eregi("MSIE 4", $Browser_Description)) $Browser = "MSIE 4.X";
   if(eregi("MSIE 5", $Browser_Description)) $Browser = "MSIE 5.X";
   if(eregi("Windows NT", $Browser_Description)) $OS = "Windows NT";
   if(eregi("Windows 95", $Browser_Description)) $OS = "Windows 95";
   if(eregi("Windows 98", $Browser_Description)) $OS = "Windows 98";
   if(eregi("win32", $Browser_Description)) $OS = "Windows 32s";
   if(eregi("mac",   $Browser_Description)) $OS = "Macintosh PPC";
   if(eregi("bsd",   $Browser_Description)) $OS = "BSD";
   if(eregi("linux", $Browser_Description)) $OS = "Linux";
   if(eregi("sun",   $Browser_Description)) $OS = "Sun OS";
} 

//This will determine if there is a spider indexing site

if(eregi("slurp", $Browser_Description)) { //Lycos; Yahoo; and others use this index spider
   $OS = "Spider: Slurp.so";
   $Browser = $OS;
} 

if(eregi("admin@crawler.de", $Browser_Description)) { 
   $OS = "Spider: Crawler V 0.2.1 admin@crawler.de";
   $Browser = $OS;
}

if(eregi("ultraseek", $Browser_Description)) { //Infoseek
   $OS = "Spider: Infoseek Ultraseek";
   $Browser = $OS;
}

if($REMOTE_HOST == "crawlit.crawler.de") {  //crawlit.crawler.de
   $OS = "Spider: Crawlit.Crawler.de";
   $Browser = $OS;
} 

if ($Browser ==""){ //If all else failed we will add data anyways for examiniation later
   $Browser = $Browser_Name;
   $Browser .= " ";
   $Browser .= $Browser_Version;
}

if ($OS =="") $OS = $Browser_Description;

$zd = date( "Y-m-d H:i:s");
$log_sql = new slashDB; //Lets make a new database class for the work
 /* this is the time format 0000-00-00 00:00:00 */
$q= "INSERT INTO activity_log 
          VALUES ('$zd ',  
                  '$HTTP_USER_AGENT',
                  '$REMOTE_ADDR',   
                  '$REMOTE_HOST',
                  '$REQUEST_URI', 
                  '$OS',
                  '$Browser',              
                  '$pagetitle',
                  '$HTTP_REFERER',
                  '$usid')";     

if (!$log_sql->query($q)) { //This will place a hidden error message for later examination
    echo  "<!--An error occured in the logger.-->\n"; 
    echo $log_sql->halt($q); 
    exit; 
} 
?>
