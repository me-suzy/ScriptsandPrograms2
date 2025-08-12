<?php
include 'classes/HEADER.PHP';
myheader("CHAPTER SELECTION");
?>

<SCRIPT LANGUAGE='JavaScript'>

var URL = window.location;
var page = URL.toString();
var start = page.indexOf("?");
var num = page.substring(start+1,start+4);
var pg = parseInt(num,10);
var last = 9;
if (isNaN(pg)){
   pg = 1;
   }
var fifty = ( pg * 50 - 49 );

document.write("<TABLE BORDER=0 ALIGN='CENTER'>");
   document.write("<TR>");
      document.write("<TD COLSPAN=5 ALIGN='CENTER'>");
         document.write("SELECT A CHAPTER TO EDIT <BR><BR>");
      document.write("</TD>");
   document.write("</TR>");

      for (LINK_X=0; LINK_X <= 4; LINK_X=LINK_X+1){
         document.write("<TD COLSPAN=1>");
            document.write("<TABLE BORDER=0>");
               for (LINK_Y = 0; LINK_Y <= 45; LINK_Y = LINK_Y+5){
                  LINK_XY = LINK_X + LINK_Y + fifty ;
                     document.write("<TR>");
                        document.write("<TD>");
                           document.write("<A HREF='ID_NODE.PHP?CHAPTER=" + LINK_XY  + "'>CHAPTER:  " + LINK_XY  + "</A><BR><BR>");
                        document.write("</TD>");
                     document.write("</TR>");
                  } 
            document.write("</TABLE>");
         document.write("</TD>");
      }
   document.write("</TR>");



   document.write("<TR>");
      document.write("<TD COLSPAN=2 ALIGN='RIGHT'>");
      var latest = pg - 1;
      var next = pg + 1;
      if (pg > 1){
         document.write("<A href='ID_CHAPTER.PHP?");
         document.write(latest);
         document.write("'>Last 50</A>");
         }
      document.write("</TD>");
      document.write("<TD COLSPAN=1 ALIGN='LEFT'>");
      document.write("</TD>");
      document.write("<TD COLSPAN=2 ALIGN='LEFT'>");
      if (pg <= last){
         document.write("<A href='ID_CHAPTER.PHP?");
         document.write(next);
         document.write("'>Next 50</A>");
         }
      document.write("</TD>");
   document.write("</TR>");
document.write("</TABLE>");
</SCRIPT>
   </BODY>
</HTML>