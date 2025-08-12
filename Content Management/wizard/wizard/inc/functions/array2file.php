<?
/*
    Array to File 
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

 * Outputs array to file by converting it to a string
 *
 * array The array 
 * name array name 
 * filename the file to write to (must exist and be writable)
 */
  
    /* Array to File */
    function __process_array($array,$indent)
    {
    $ret_str = "";
    $first = true;
    foreach ($array as $key => $value)
    {
    if (!$first) 
    $ret_str .= ",\n";
    else
    $first = false;
    			
    $ret_str .= str_repeat(" ",$indent);
    						
    if (is_array($value)) 
    {
    $ret_str .= "'$key' => array(\n".__process_array($value,$indent+5)."\n".str_repeat(" ",$indent).")";
    }
    elseif (is_string($value))
    {
    $ret_str .= "'$key' => '$value'";
    }
    elseif (is_int($value))
    {			
    $ret_str .= "'$key' => $value";
    }
    elseif (is_bool($value))
    {			
    $ret_str .= "'$key' => ".($value?"true":"false");
    }
    }
    return $ret_str;
    }
    	
    function array_to_file($array,$name,$filename)
    {
    $file_str = "<?\n $"."$name = array(\n";
    $file_str .= __process_array($array,6);	
    $file_str .= "\n );\n?>";
    		
    $file = fopen($filename,"w");
    fwrite($file,$file_str);
    fclose($file);
    }
    ?>
