<?php


/*
=============================================================
	
	Copyright (C) 2004 Alex B

	E-Mail: dirmass@yahoo.com
	URL: http://www.devplant.com
	
    This file is part of Bumpy Tables.

    Bumpy Tables is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    Bumpy Tables is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

=============================================================== 
*/

//////////////////////////
////Bumpy Tables Start///
/////////////////////////
	
	$border=$_POST["border"];
	$width=$_POST["width"];
	$height=$_POST["height"];
	$bgcolor=$_POST["bgcolor"];
	$cspacing=$_POST["cspacing"];
	$cpadding=$_POST["cpadding"];
	$t_bgcolor=$_POST["t_bgcolor"];
	$td_bgcolor=$_POST["td_bgcolor"];
	$td_color=$_POST["td_color"];
	$td_family=$_POST["td_family"];
	$td_size=$_POST["td_size"];
	$td_align=$_POST["td_align"];
	$border_size=$_POST["border_type"];
	$border_color=$_POST["border_color"];


             echo "
<!DOCTYPE html
PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
   <head>
   
      <title>Table Generated</title>
      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />



<!-- Style Start -->



<style type=\"text/css\">
body {
background: $bgcolor;
}

table {
height: $height;
background: $t_bgcolor;
border: $border_type $border_color;
border-width: $border_width;
}

td {
background: $td_bgcolor;
color: $td_color;
font-family: $td_family;
font-size: $td_size;
text-align: $td_align;
}
</style>


<!-- Style End -->


   </head>
   
   <body>



<!-- Table Start -->



<table border=\"$border\" width=\"$width\" cellspacing=\"$cspacing\" cellpadding=\"$cpadding\">
";


function showtable()
{
	
	$cols=$_POST["cols"];	
	$rows=$_POST["rows"];


   for ($x=1; $x<=$rows; $x++) {
       echo "<tr>\n";

       for ($y=1; $y<=$cols; $y++) {
           echo "<td>Text</td>\n";
       }

       echo "</tr>\n";
   }

}

$show=showtable();

echo $show;

echo "
</table>



<!-- Table End -->



   </body>
</html>

<!--COPY THE TABLE AND STYLE OR ALL ABOVE-->

<!--DO NOT COPY BELOW THIS LINE -->

<br /><br /><br />
<h4>
Above you have a preview of the table. If you need to make any modifications, hit your browsers back buttong to return to Step 1.
<hr />

Step 3: 
<br />
View the page source (IE: View > Source)<br /><br />

Copy the Table AND the Style OR all the html to your file eg:(table.html) (make sure the style remains in the head section of your document)
<br /><br/ >
Step 4:
<br />
Replace \"Text\" with your own content.

</h4>

";

?>	    



