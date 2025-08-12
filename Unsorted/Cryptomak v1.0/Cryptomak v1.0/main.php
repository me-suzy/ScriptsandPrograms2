<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   main.php -> CryptoMAK Main Configuration     			|
 +----------------------------------------------------------------------+  						        |	
 |								        |
 | (c) 2002 by M.Abdullah Khaidar (khaidarmak@yahoo.com)                |
 |								        |	
 | This program is free software. You can redistribute it and/or modify |
 | it under the terms of the GNU General Public License as published by |
 | the Free Software Foundation; either version 2 of the License.       |
 |                                                                      |
 +----------------------------------------------------------------------+
 ************************************************************************/



/*-------------  Begin Configuration --------------*/ 

$MAXLENGTH=1024;	// maximum length of input text (byte)
$MAXCOLUMN=10;		// maximum column of columnar transposition
$MAXKEYLENGTH=10;	// maximum key length of index coincidence
$MAXPERMUTE=8;          // maximum permutation length


/* each file's title and head title is included in their file */

$TITLE="....::: CryptoMAK Cipher Tools :::...."; // index page title
$HEADTITLE="CryptoMAK Cipher Tools";             // index head title


/* note: you must know RGB hexadecimal color to change below */ 

$LINK="#000000";  
$HOVER="#00bb00"; 
$ALINK="#00bb00";
$VLINK="#007700";
$BGCOLOR="#005500";       // background color
$TEXT="#000000";          // text color
$BORDERCOLOR="#003300";   // border color
$TXTFIELDCOLOR="#358C35"; // text field color



/*---------------  End Configuration ----------------*/ 



function top(){

   // Top HTML page function

   global $TITLE,$HEADTITLE,$LINK,$HOVER,$ALINK,$VLINK,$BGCOLOR,$TEXT,$BORDERCOLOR,$TXTFIELDCOLOR;

   echo "<html>\n";
   echo "<head>\n";
   echo "<title>$TITLE</title>\n";
   echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
   echo "<style type=\"text/css\">\n";
   echo "<!--\n";
   echo "a:active {  color: $ALINK; text-decoration: none}\n";
   echo "a:hover {  color: $HOVER; text-decoration: none}\n";
   echo "a:link {  color: $LINK; text-decoration: none}\n";
   echo "a:visited {  color: $VLINK; text-decoration: none}\n";
   echo ".txtcolor {  background-color: $TXTFIELDCOLOR; border: thin $BORDERCOLOR solid; font-family: Verdana, Arial, Helvetica, sans-serif;";
   echo " color: $TEXT; font-size: 8pt;border-width: auto thick thick; border-color: $BORDERCOLOR solid}\n";
   echo "-->\n";
   echo "</style>\n";
   echo "</head>\n";
   echo "<body bgcolor=\"$BGCOLOR\" text=\"$TEXT\">\n";
   echo "<table width=\"90%\" border=0 cellspacing=\"0\" cellpadding=\"0\" align=\"center\" bgcolor=\"$BGCOLOR\" bordercolor=\"$BORDERCOLOR\">\n";
   echo "  <tr>\n";
   echo "    <td>\n";
   echo "      <div align=\"center\"><a href=\"\">$HEADTITLE</a></div>\n";
   echo "    </td>\n";
   echo "  </tr>\n";
   echo "</table>\n";
   echo "<br>\n";
   echo "<table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" bordercolor=\"$BORDERCOLOR\" bgcolor=\"$BGCOLOR\">\n";
   echo "  <tr align=\"center\" valign=\"top\">\n"; 
   echo "    <td>\n"; 
	
}


function bottom(){
   
   // Bottom HTML page function
    
   global $BGCOLOR,$BORDERCOLOR;
   
   echo "     </td>\n";
   echo "   </tr>\n";
   echo " </table>\n";
   echo "<br>\n";
   echo "<table width=\"90%\" border=0 cellspacing=\"0\" cellpadding=\"0\" align=\"center\" bgcolor=\"$BGCOLOR\" bordercolor=\"$BORDERCOLOR\">\n";
   echo "  <tr>\n";
   echo "    <td>\n";
   echo "<hr width=50% color=\"#003300\">";
   echo "<hr width=50% color=\"#003300\">";
   echo "      <div align=\"center\">&copy; 2002 by MAKCoder</div>\n";
   echo "    </td>\n";
   echo "  </tr>\n";
   echo "</table>\n";
   echo "<br><div align=\"center\">";
   echo "<a href=\"http://makcoder.sourceforge.net\"><img src=\"makcoder.jpg\" alt=\"MAKCoder Logo\" width=\"88\" height=\"31\" border=\"0\"></a>";
   echo "</div>\n";
   echo "</body>\n";
   echo "</html>\n";
}


function decimal($letter){
   
   // function to change ascii letter (a..z) to decimal value (0..25) 
   
   switch ($letter){
	case "a": $decvalue=0;break;
	case "b": $decvalue=1;break;
	case "c": $decvalue=2;break;
	case "d": $decvalue=3;break;
	case "e": $decvalue=4;break;
	case "f": $decvalue=5;break;
	case "g": $decvalue=6;break;
	case "h": $decvalue=7;break;
	case "i": $decvalue=8;break;
	case "j": $decvalue=9;break;
	case "k": $decvalue=10;break;
	case "l": $decvalue=11;break;
	case "m": $decvalue=12;break;
	case "n": $decvalue=13;break;
	case "o": $decvalue=14;break;
	case "p": $decvalue=15;break;
	case "q": $decvalue=16;break;
	case "r": $decvalue=17;break;
	case "s": $decvalue=18;break;
	case "t": $decvalue=19;break;
	case "u": $decvalue=20;break;
	case "v": $decvalue=21;break;
	case "w": $decvalue=22;break;
	case "x": $decvalue=23;break;
	case "y": $decvalue=24;break;
	case "z": $decvalue=25;break;
	default: $decvalue=$letter+26;break;
   }
   
return $decvalue;
}


function ascii_letter($decvalue){
   
   // function to change decimal value (0..25) to ascii letter (a..z)
   
   switch($decvalue){
	case 0:$letter="a";break;
	case 1:$letter="b";break;
	case 2:$letter="c";break;
	case 3:$letter="d";break;
	case 4:$letter="e";break;
	case 5:$letter="f";break;
	case 6:$letter="g";break;
	case 7:$letter="h";break;
	case 8:$letter="i";break;
	case 9:$letter="j";break;
	case 10:$letter="k";break;
	case 11:$letter="l";break;
	case 12:$letter="m";break;
	case 13:$letter="n";break;
	case 14:$letter="o";break;
	case 15:$letter="p";break;
	case 16:$letter="q";break;
	case 17:$letter="r";break;
	case 18:$letter="s";break;
	case 19:$letter="t";break;
	case 20:$letter="u";break;
	case 21:$letter="v";break;
	case 22:$letter="w";break;
	case 23:$letter="x";break;
	case 24:$letter="y";break;
	case 25:$letter="z";break;
	default:$letter=$decvalue-26;break;
   }
   
return $letter;
}


function filter($input_text){
   
   // function to filter input text
   
   $input_text=preg_replace("/\s/","",$input_text); // eliminate whitespace
   $input_text=preg_replace("/\W/","",$input_text); // eliminate non-alphabet character
   $input_text=strtolower($input_text); // replace uppercase to lowercase
   return $input_text;
}


function check_length($input_text){

   // function to check allowed maximum length
    
   global $MAXLENGTH;
   
   if(strlen($input_text)>=$MAXLENGTH){
      $input_text=substr($input_text,0,$MAXLENGTH);
   }
   return $input_text;
}


?>