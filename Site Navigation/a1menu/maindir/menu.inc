<?php

function zeigen ($type) {
   function echo_link($art, $link, $text, $type) {
        $col = "#303030";  // Color of a standard entry
//        $logo = "&#149";
        $logo = "<img SRC=/images/ballbluesmall.gif border=0>";  // Pictur of standard entry
        if ($art=="0") {$col = "#FF0000";$logo = "<img SRC=/images/home2.gif border=0>";}  // Home
        if ($art=="1") {$col = "#FF0000";$logo = "<img SRC=/images/up.gif border=0>";}   // Aufw&auml;rts - Up
        if ($art=="2") {$col = "#FF0000";$logo = "<img SRC=/images/ballbluebig.gif border=0>";} // The Index-File in the directory
        if ($art=="3") {$col = "#0000e0";$logo = "<img SRC=/images/folder1.gif border=0>";} // Sub-Folders
      if ($art=="4") {$col = "#800000";$logo = "<img SRC=/images/ballred.gif border=0>";} // Currently selected File

        // Die Druckspalte vertikal - printed entry for a vertical column
        If ($type == "1")
        echo "<tr><td align=right><a href=\"$link\">$logo</td><td><font size=\"-2\"><a href=\"$link\" style=\"text-decoration: none; color: $col;\" >$text</a></font></td></tr>\r";

        // Horizontal
        else
        echo "<font size=\"-1\"><a href=\"$link\" style=\"text-decoration: none; color: $col;\" >&nbsp;$logo&nbsp;$text</a></font>\r";
        }

// -------------------- Main programm starts here ---------------------------


// *** Define horizontal or vertical layout ***
        // Okay, let's open the TABLE and define size, background color etc
          If ($type == "1")
              echo "<table bgcolor=\"#D1D1E9\" width = \"150\" border =\" 0\"><tr><td>";
          else
              echo "<table bgcolor=\"#FEF5E2\" width = \"600\"><tr><td>";

        // Now, the "home" and "up" link

           echo_link("0", "http://www.alopezie.de/A1menu", "Home",$type);
           echo_link("1", "../", "Ebene h&ouml;her",$type);  // Upwards Link

        /* If you're including this script from a function, you'll need
        to make $SCRIPT_FILENAME a global variable. */

        if (!$SCRIPT_FILENAME)
                global $SCRIPT_FILENAME;

        // We'll have to loop through all the files in the current directory
        $d = dir(dirname($SCRIPT_FILENAME));
        while($file=$d->read()){

         // ************* FILTER files and directories **************
          $wahl = "N";                            // not selected a standard
          if (is_dir($file)) {$wahl = "J";}       // Select if Directory
          if (eregi("php3", $file)){$wahl = "J"; }  // Select if PHP3 - take HTM HTML or whatever you need
          $rest = substr ($file, 0, 1);
          if ($rest == "_") {$wahl = "N";}        // deselect if starts with "_" or "."
          if ($rest == ".") {$wahl = "N";}

          if ($wahl =="J") {
//           ------------- Takes Subdirectories here ----------------
              if (is_dir($file))
                      {
                       $xbuild = "3"."|".$file."|".$file."|3";  // Build print line for DIRECTORY files:  Sorting = 3, Type is also 3
                       $xline[] = $xbuild;
                      }
             else {
//                --------------------  normale file -----------------
                        $fp = fopen("$file", "r");   // Not a directory file -> normal file
                        $build = "";
                        $line = "";
                        $flag = 0;
                        $i = 0;
                        while (($i < 20)and (!feof ($fp)) ) {
                            $i = $i +1;

                            $line = fgets($fp, 1024);
                            $lineup = strtoupper($line); // search in uppercase string

                            /* The <TITLE> tag MUST be opened at the beginning of a new line */
                             if (ereg("^<TITLE>", $lineup))
                               {$flag = 1;
                                $build = substr($line,1,100);  // Maximal 100 chars
}
                              if ($flag == 1)
                                        {$build = $build.$line;
                                        }
                               if (ereg("</TITLE>", $lineup))
                                        {$flag = 0;
                                       }
                                $flag = 0;

                        }
                        $lineup = strtoupper($build);
                        if ($build > ""):
                                $start = strpos($lineup,"TITLE");
                                $end = strpos($lineup,"</TITLE>");
                                $laenge = $end - $start - 6;
                                $build = substr($build,$start + 6,$laenge);

                                $xsort ="5";     // File sorting is standard
                                $xtype ="5";     // File type is standard

                                //  Currently Selected File is treated differently
                                if ($file == basename($SCRIPT_FILENAME)){$xtype = "4";}

                                // Index-File File is treated differently
                                $pos = strpos ("1".strtoupper($file), "INDEX.");
                                if ($pos == 1){$xsort = "2";$xtype = "2";}

                                $buildline = $xsort."|".$build."|".$file."|".$xtype;  // build line
                                // echo $buildline;
                                $xline[] = $buildline;

                        endif;
                }
              }
        }

        // and finally, close the directory
        $d->close();


        // ------------------ now sort the whole thing following code + Title
        sort ($xline);
        reset ($xline);
        while (list ($key, $val) = each ($xline)) {
           $pieces = explode ("|", $val);    // restore original values for type, filename and title
           echo_link($pieces[3], $pieces[2], $pieces[1],$type);
       }

        // To finish off the bar  - you may add links for example
        // echo_link("5", "http://www.derminfo.de/links/link.html","Links",$type);

        // Close Table
        echo "</td></TR></table>";

};
        ?>