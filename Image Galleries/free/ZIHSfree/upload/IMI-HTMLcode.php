<?php
//IMI-HTMLcode.php
//Created on: September 18th, 2005, Version 1.0918
//Created by:  Michael J. Muff, Iron Muskie, Inc., customer_service@iron-muskie-inc.com
//All Rights Reserved!


//Create Table Features

function table_html($attributes, $data) {
	print "<table $attributes>\n";
	print "$data";
	print "</table>\n";
}

function tr_html($attributes, $data){
	print "<tr $attributes>\n";	
	print "$data";
	print "</tr>\n";
}

function td_html($attributes, $data){
	print "<td $attributes>\n";
	print "$data";
	print "</td>\n";
}

function th_html($attributes, $data){
	print "<th $attributes>\n";
	print "$data";
	print "</th>\n";
}

// Date Conversion

function mys2us($value){
	$ret="";
	if(ereg("-",$value)){
		$ret = substr($value,5,2)."/".substr($value,8,2)."/".substr($value,0,4);
	}
	return $ret;
}

function us2mys($value){
	$ret="";
	if(ereg("\/",$value)){
		$ret = substr($value,6,4)."-".substr($value,0,2)."-".substr($value,3,2);
	}
	return $ret;
}



//Images

function displayImage($image,$width,$height){
    print "<img src=\"{$image}\" width=\"{$width}\" height=\"{$height}\">";
}

//Layer Tags

function div_css_html($class, $data){
    print "<div class=\"{$class}\">\n";
    print "$data";
    print "</div>\n";

}

function div_html($data){
    	print "<div>\n";
    	print "$data";
    	print "</div>\n";
}

//File Handlers
function strip_ext($name) { 
     $ext = strrchr($name, '.'); 
     if($ext !== false) 
     { 
         $name = substr($name, 0, -strlen($ext)); 
     } 
     return $name; 
} 

function get_ext($name) { 
     $ext = strrchr($name, '.'); 
     
     return $ext; 
}

?>
