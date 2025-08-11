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
    $pagetitle="Reporter";
        $xsiteobject = "Live Stats Reporter";
        $yoursite = "your-domain";   //Filters referer reports from your domain i.e. + www.site.com = "site"
            require("config.php");
            page_open(array("sess"=>"slashSess"));
            require("./rpt_func.inc");
            slashhead($pagetitle,$xsiteobject);

    error_reporting(7);
    
    titlebar("100%","Other Reports");
echo "<a href=\"referrer.php\">Referrer Report</a>";

    ########################### General Stats ####################################################
     titlebar("100%","General Statistics");
    echo"
    <div align=\"center\"><TABLE border=1 cellSpacing=0 width=\"100%\">\n
  <TBODY style=\"font-size: x-small;\">\n
  <TR bgColor=#ffffff>\n
    <TD align=left><B>Date &amp; Time This Report was Generated</B></TD>\n
    <TD align=left>"; print (date( "l F d, Y - H:i:s"));echo "</TD></TR>\n
   <TR bgColor=#ffffff>\n
    <TD align=left><B>Number of Hits for Home Page</B></TD>\n
    <TD align=left>$qtotalh</TD></TR>\n
  <TR bgColor=#f0f0f0>\n
    <TD align=left><B>Number of Page Views (Impressions)</B></TD>\n
    <TD align=left>$qtotal</TD></TR>\n
  <TR bgColor=#f0f0f0>\n
    <TD align=left><B>Number of User  Sessions</B></TD>\n
    <TD align=left>$qnttotses</TD></TR>\n
  <TR bgColor=#f0f0f0>\n
    <TD align=left><B>Average Number of  Page Views (Impressions) For 7 Days</B></TD>\n
    <TD align=left>";avghitcnt();echo"</TD></TR>\n
  <TR bgColor=#ffffff>\n
    <TD align=left><B>Average Number of User Sessions For 7  Days</B></TD>\n
    <TD align=left>";avgsescnt (); echo "</TD></TR>\n
 <TR bgColor=#f0f0f0>\n
    <TD align=left><B>Average Number of Unique Users For 7  Days</B></TD>\n
    <TD align=left>"; avguniusr (); echo "</TD></TR>\n
  <TR bgColor=#ffffff>\n
    <TD align=left><B>Number of Users Who Visited Once</B></TD>\n
    <TD align=left>";  avgsing (); echo "</TD></TR>\n
  <TR bgColor=#f0f0f0>\n
    <TD align=left><B>Number of Users Who Visited More Than Once</B></TD>\n
    <TD align=left>"; avgmul ();  echo "</TD></TR></TBODY></TABLE></div>\n
  ";
########################### IMPRESSIONS ####################################################
    titlebar("100%","Impression Data");
        sect_open ();
            rowhead("Impression Category","Result");
                rowit("Unique User Sessions for $today","$qntuniqeses");
                rowit("Unique Host for $today","$qntuniqe");
                rowit("Unique Host For  $month","$qntuniqem");
                rowit("Impresions for $today","$qntd");
                rowit("Impresions For $yes","$qnty");
                rowit("Impresions for the Month $month","$qntm");
                rowit("Impresions this year","$qnty");
            close_table ();
        seper ();
                $total =$qntuniqeses+$qntuniqe+$qntd;
                echo "<TD><img src=\"$rootdir/logging_mod/graph_func.php?d1=$qntuniqeses&amp;d2=$qntuniqe&amp;d3=$qntd&amp;l1=User+Sessions&amp;l2=Unique+Host&amp;l3=Impressions&amp;sum=$total\" width=400 height=200></td>";
                unset($total);
    sect_close ();
    ########################### BROWSERS ####################################################
    titlebar("100%","MS Internet Explorer Data");
        sect_open ();
            rowhead("MSIE  Category","Result");
                rowit("Microsoft IE Browsers Today","$qntie");
                rowit("Microsoft IE Browsers Month","$qntiem");
                rowit("Microsoft IE 2.X Browsers Today","$qntie2");
                rowit("Microsoft IE 2.X Browsers Month","$qntie2m");
                rowit("Microsoft IE 3.X Browsers Today","$qntie3");
                rowit("Microsoft IE 3.X Browsers Month","$qntie3m");
                rowit("Microsoft IE 4.X Browsers Today","$qntie4");
                rowit("Microsoft IE 4.X Browsers Month","$qntie4m");
                rowit("Microsoft IE 5.X Browsers Today","$qntie5");
                rowit("Microsoft IE 5.X Browsers Month","$qntie5m");
            close_table ();
        seper ();
                echo "<TD><img src=\"$rootdir/logging_mod/graph_func.php?d1=$qntie5&amp;d2=$qntie4&amp;d3=$qntie3&amp;d4=$qntie2&amp;l1=IE+5.X&amp;l2=IE+4.X&amp;l3=IE+3.X&amp;l4=IE+2.X&amp;sum=$qntie\" width=400 height=200></td>";
    sect_close ();
    ######################################Start NS####################################################
     titlebar("100%","Netscape Data");
         sect_open ();
                rowhead ("Navigator  Category","Result");
                    rowit("Navigator Browsers Today","$qntnav");
                    rowit("Navigator  Browsers This Month","$qntnavm");
                    rowit("Navigator 2.X Browsers Today","$qntnav2");
                    rowit("Navigator  2.X Browsers This Month","$qntnav2m");
                    rowit("Navigator 3.X Browsers Today","$qntnav3");
                    rowit("Navigator  3.X Browsers This Month","$qntnav3m");
                    rowit("Navigator 4.X Browsers Today","$qntnav4");
                    rowit("Navigator  4.X Browsers This Month","$qntnav4m");
                    rowit("Navigator 5.X Browsers Today","$qntnav5");
                    rowit("Navigator  5.X Browsers This Month","$qntnav5m");
                close_table ();
            seper ();
                echo "<TD><img src=\"$rootdir/logging_mod/graph_func.php?d1=$qntnav5&amp;d2=$qntnav4&amp;d3=$qntnav3&amp;d4=$qntnav2&amp;l1=Navigator+5.X&amp;l2=Navigator+4.X&amp;l3=Navigator+3.X&amp;l4=Navigator+2.X&amp;sum=$qntnav\" width=400 height=200></td>";
    sect_close ();
    ####################################OTHER DATA Below Here###################################
    titlebar("100%","Other Browser Data");
         sect_open ();
            rowhead ("Other  Category","Result");
                rowit("Opera Browsers Today","$qntoper");
                rowit("Opera  Browsers This Month","$qntoperm");
                rowit("Undefined Browsers For The Month","$qntudefm");
                rowhead (" Monthly Data Averages For Browsers","AVG");
                    $res = percent_it ($qntiem,$qntm);
                rowit("MSIE", $res);
                    $res = percent_it ($qntnavm,$qntm);
                rowit("Navigator", $res);
                    $res = percent_it ($qnoperm,$qntm);
                rowit("Opera", $res);
                    $res = percent_it (($qnoperm-$qntnavm-$qntiem+$qntm),$qntm);
                rowit("Else", $res);
            close_table ();
        seper ();
                echo "<TD><img src=\"$rootdir/logging_mod/chart_pie.php?d1=$qntiem&amp;d2=$qntnavm&amp;d3=$qntoperm&amp;d4=$qntudefm&amp;l1=MSIE&amp;l2=Navigator&amp;l3=Opera&amp;l4=Undefined\" width=300 height=327><BR><b>Monthly Browser Data Averages</b></td>";
    sect_close ();
    ########################### OS ####################################################
    titlebar("100%","OS Data");
         sect_open ();
            rowhead ("OS Category","Result");
                rowit("Total Windows Today","$qntwin");
                rowit("Total Windows for Month","$qntwinm");
                rowit("Total Linux Today","$qntlin");
                rowit("Total Linux for Month","$qntlinm");
                rowit("Total SunOS Today","$qntsun");
                rowit("Total SunOS for Month","$qntsunm");
                rowit("Total BSD Today","$qntbsd");
                rowit("Total BSD for Month","$qntbsdm");
                rowit("Total Macintosh PPC Today","$qntmac");
                rowit("Total Macintosh PPC Month","$qntmacm");
            close_table ();
        seper ();
                    $ossum = $qntmac+$qntsun+$qntlin+$qntwin+$qntbsd;
                echo "<TD><img src=\"$rootdir/logging_mod/graph_func.php?d1=$qntwin&amp;d2=$qntlin&amp;d3=$qntsun&amp;d4=$qntmac&amp;d5=$qntbsd&amp;l1=Windows&amp;l2=Linux&amp;l3=Sun+OS&amp;l4=Macintosh+PPC&amp;l5=BSD&amp;sum=$ossum\"width=400 height=200></td>";
    sect_close ();
    ############################ WINDOWS BREAKDOWN #############################
    titlebar("100%","Windows OS Data");
         sect_open ();
            rowhead ("Windows OS Category","Result");
                rowit("Total Windows 95 Today","$qntwin95");
                rowit("Total Windows 95 for Month","$qntwin95m");
                rowit("Total Windows 98 Today","$qntwin98");
                rowit("Total Windows 98 for Month","$qntwin98m");
                rowit("Total Windows NT Today","$qntwinnt");
                rowit("Total Windows NT for Month","$qntwinntm");
                rowit("Total Windows 3.X Today","$qntwin3x");
                rowit("Total Windows 3.X for Month","$qntwin3xm");
                rowit("Total Windows 32 Today","$qntwin32");
                rowit("Total Windows 32 for Month","$qntwin32m");
            close_table ();
        seper ();
                echo "<TD><img src=\"$rootdir/logging_mod/chart_pie.php?d1=$qntwin95&amp;d2=$qntwin98&amp;d3=$qntwinnt&amp;d4=$qntwin3x&amp;d5=$qntwin32&amp;l1=Windows+95&amp;l2=Windows+98&amp;l3=Windows+NT&amp;l4=Windows+3.X&amp;l5=Windows+32s\" width=300 height=327></td>";
    sect_close ();
    ################################# END WINDOWS ###########################################

    titlebar("100%","Referring Search Engines This Month");
         sect_open ();
            rowhead ("Search Engine","Result");
                rowit("www.yahoo.com","$qntyahoo");
                rowit("www.excite.com","$qntexcite");
                rowit("www.webcrawler.com","$qntwebcrawler");
                rowit("www.infoseek.com","$qntinfoseek");
                rowit("www.snap.com","$qntsnap");
                rowit("search.msn.com","$qntmsn");
                rowit("go2net.com","$qntgo2");
            close_table ();
        seper ();
                echo "<TD><img src=\"$rootdir/logging_mod/graph_func.php?d1=$qntyahoo&amp;d2=$qntexcite&amp;d3=$qntwebcrawler&amp;d4=$qntinfoseek&amp;d5=$qntsnap&amp;d6=$qntmsn&amp;d7=$qntgo2&amp;l1=yahoo&l2=excite&amp;l3=webcrawler&amp;l4=infoseek&amp;l5=snap&amp;l6=msn&amp;l7=go2net\" width=400 height=200></td>";
    sect_close ();

slashfoot();
page_close();   //Close the PHPLIB session and save relevant session data

?>
