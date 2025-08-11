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

$pagetitle="Referrer Log Reporter"; 
$xsiteobject = "Live Stats Reporter";
$yoursite = "your-domain";   //Filters referer reports from your domain i.e. + www.site.com = "site"

require("config.php");

page_open(array("sess"=>"slashSess"));
slashhead($pagetitle,$xsiteobject);

error_reporting(7);
    
titlebar("100%","Other Reports");
echo "<a href=\"${basedir}/logging_mod/report.php\">User agent Data</a>";

titlebar("100%","Referring Sites");


echo "<TABLE  border=1 cellPadding=1 cellSpacing=0  bordercolor=\"#5B69A6\" style=\"font-size: xx-small;\" width=100%>\n";  //Start the table For the referer data
echo "<tr><th bgcolor=$gloss_tbl_1>#</th><th bgcolor=$gloss_tbl_1>Referer</th><th bgcolor=$gloss_tbl_1>Time</th><th bgcolor=$gloss_tbl_1>Keywords</th></tr>\n"; //header keys for referer data

$ref_q = new slashDB;   //new database class
$qref= "select * from activity_log where referer not like '%$yoursite%' and referer not like '' group by referer order by time desc limit 50"; 
$ref_q->query($qref);
$i = 0;

while ($ref_q->next_record()) {               //Keyword Aquisition And Referer Name Refinement
   eregi ("http://(.*\.com|.*\.net|.*\.org)/.*\?(.*)",$ref_q->Record[referer],$match);
   if (!eregi("(\?)",$ref_q->Record[referer])) {    //YAHOO words=network+help
      eregi ("http://(.*\.com|.*\.net|.*\.org)/(.*)",$ref_q->Record[referer],$match);
   }
   if (eregi("(s\=)",$match[2]))  {
      $ex1 = explode("s=",$ref_q->Record[referer]);
   }
   if (eregi("(qt\=)",$match[2])) {
      $ex1 = explode("qt=",$ref_q->Record[referer]);
   }
   if (eregi("(qt\=)*(oq\=)",$match[2])) {
      $ex1 = explode("qt=",$ref_q->Record[referer]);
   }
   if (eregi("(p\=)",$match[2])) {
      $ex1 = explode("p=",$ref_q->Record[referer]); 
   }
   if (eregi("(QRY\=)",$match[2])) {
      $ex1 = explode("QRY=",$ref_q->Record[referer]);
   }
   if (eregi("(searchText\=)",$match[2]))  {
      $ex1 = explode("searchText=",$ref_q->Record[referer]);
   }
   if (eregi("(MT\=)",$match[2]))   {
      $ex1 = explode("MT=",$ref_q->Record[referer]);
   }
   if (eregi("(query\=)",$match[2])) {
      $ex1 = explode("query=",$ref_q->Record[referer]);
   }
   if (eregi("(FI_1\=)",$match[2]))  {
      $ex1 = explode("FI_1=",$ref_q->Record[referer]);
   }
   if (eregi("(general\=)",$match[2])) {
      $ex1 = explode("general=",$ref_q->Record[referer]);
   }
   if (eregi("(general\=)",$match[2])) {
      $ex1 = explode("general=",$ref_q->Record[referer]);
   }
   if (eregi("(search\=)",$match[2])) {
      $ex1 = explode("search=",$ref_q->Record[referer]);
   }
   if (eregi("(KW\=)",$match[2]))  {
      $ex1 = explode("KW=",$ref_q->Record[referer]);
   }
   if (eregi("(ask\=)",$match[2]))  {
      $ex1 = explode("ask=",$ref_q->Record[referer]);
   }
   if (eregi("(category\=)",$match[2]))  {
      $ex1 = explode("category=",$ref_q->Record[referer]);
   }
   if (eregi("(q\=)",$match[2])) {
      $ex1 = explode("q=",$ref_q->Record[referer]);
   }
   if (eregi("(megaspider)",$match[0]))  {
      $ex = $match[2];
   }
   if (eregi("(ink\.yahoo\.com)",$match[0]))  { // ink.yahoo.com 
      $ex1 = explode("p=",$ref_q->Record[referer]);
   }
   if (eregi("(go\.com)",$match[0]))  { // ink.yahoo.com 
      $ex1 = explode("qt=",$ref_q->Record[referer]);
   }
   $ex2 = explode("&",$ex1[1]);    //Seperate from the other form variables in the referer string
   if (!isset($ex)) {
      $ex = urldecode($ex2[0]);   //get rid of the URL Encoding from the keywords
   }

   // END Keyword Aquisition And Referer Name Refinement

   $bgcolor=$gloss_tbl_1;  //Starting color for alternating background colors
   $i % 2 ? 0: $bgcolor= $gloss_tbl_2; //Used for alternating background colors
   $i++;                   //Used for alternating background colors

   if ($ex =="") {
      $ex="</B>None<B>";    //If we didn't get any keywords let's say so
   }
   $ex =eregi_replace("\+"," ",$ex);   //Remove + signs that are left from urldecode
   $ex =eregi_replace("\""," ",$ex);   //Remove \" signs that are left from urldecode
   if ($match[1] =="")  {
      $match[1]=$ref_q->Record[referer];
   }
   printf ("<tr><TD bgcolor=$bgcolor>$i</TD><TD bgcolor=$bgcolor><a href=\"%s\" target=\"_blank\">
            %s</a></TD><TD bgcolor=$bgcolor>%s</TD><TD bgcolor=$bgcolor><b>%s</b></TD></TR>\n",$ref_q->Record[referer],$match[1],$ref_q->Record[time],$ex);

   //Clear All Variable so they won''t interfere with next run

   unset ($match);
   unset ($ex);
   unset ($ex1);
   unset ($ex2);
}  // end While.

echo "</TABLE>";    //All finished and now we close the table
slashfoot();
page_close();   //Close the PHPLIB session and save relevant session data
?>
