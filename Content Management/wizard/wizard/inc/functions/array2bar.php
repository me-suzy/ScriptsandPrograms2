<?php

/*  
	Array to Bar
	(c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

   Transforms Array to Horizontal Bar Gray
   (c) 2004-2005 Philip Shaddock All rights reserved.
       pshaddock@wizardinteractive.com 
	   * expects as input a multidimensional array
	   * $array[0][1] = numerical value
	   * $array[0][0] = bar graph label
	   * Returns: HTML bar graph 
*/ 	

function array2bar ($array, $max_width) {

//skip if the array is empty
if (!$array) {
    return;
}

  $counter = 0;
  foreach ($array as $value) {
    //limit value given guest
	if ($array[$counter][0] == "Guest") {
	    $value = 1;
	}
	else{
    $value = $array[$counter][1];
	}
	
    if ((IsSet($max_value) && 
         	($value > $max_value)) ||
        	(!IsSet($max_value))) {
      	$max_value = $value;
    }
	
	$counter++;
  }
  if (!$max_value) {
      return;
  }
  $pixels_per_value = ((double) $max_width)
                       / $max_value;
                  
  $string_to_return = "<TABLE CELLPADDING=5>";
  $counter = 0;
  foreach ($array as $name => $value) {
    $value = $array[$counter][1];
    $bar_width = $value * $pixels_per_value;
	if ($array[$counter][0] == "Guest") {
		// do not show
	}
	else {
    	$string_to_return .= 
     	"<TR><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><TD bgcolor=\"#f1f1f1\"><span class=\"smallText\"><b>" . $array[$counter][0] . "</span><b></TD>
      		<TD bgcolor=\"#FdFdFd\"><IMG SRC=\"admin/stats/images/bar$counter.gif\"
               WIDTH=$bar_width
               HEIGHT=10>
      		</TD></TR>";
	}
    $counter++;
  }
  $string_to_return .= "</TABLE>";
  
  return($string_to_return);
}
?>
