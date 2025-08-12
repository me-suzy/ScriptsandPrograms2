<?
/*
Copyright Information
Script File :  options.php
Creator:  Jose Blanco (snyper)
Version:  1.0
Date Created: Feb. 20 / 2005
Released :  Feb. 27 / 2005
website: http://x-php.com , Shadowphp.net
e-mail: joseblanco.jr@g-mail.com
Aim: xphp snyper , Junior Snyper
please keep this copyright in place. :)
*/
if($sec_inc_code=="081604"){
if($_GET["admin"]=="option"){ 
$title.="- Options";
if($_GET["do"]=="1"){
$floodtime = $_POST["floodtime"];     
       $conf='<?php'."\n";	
	  $conf.='$skin="'.$skin.'";'."\n";  
	    $conf.='$floodtime="'.$floodtime.'";'."\n";  
	  $conf.='?>';
	  $conf=stripslashes($conf);
	   $loc=fopen("./inc/options.db.php", "w");
 fwrite($loc, "$conf"); 
 fclose($loc); 
 $cont.=  <<<html
 <table width="400" border="1"  cellpadding="1" cellspacing="1" bordercolor="#B5B59C" align="center">
  <tr>
    <td bgcolor="#FFFFCC"><div ><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">Options Have 
        successfully Been Updated.</font></div></td>
  </tr>
</table>
	
html;
}
include("./inc/options.db.php");
$cont.= <<<HTML
<form  method="post" action="?admin=option&do=1">
  <table width="549" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
    <tr> 
      <td colspan="2" align="center"  ><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Options</strong></font></td>
    </tr>
    <tr> 
      <td width="100"  ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Admincp 
        Skin</font></td>
      <td width="427"  ><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      
HTML;
				
  list($skins) = explode("/", $skin);
            $bar = "<select name=\"skin\">";
            $dir = "./skins/";
            $handle = opendir($dir);
            while (false !== ($files = readdir($handle))) { 
                $file = $files;
                if($file == "." || $file == ".." || $file == "index.html"){
                   }else{
				   $files = $file;
				   $files[0] = strtoupper($files[0]);
                    $iskin = ($skins == $file)? " selected" : "";
                    if($skin == $file){
$bar = $bar."<option value=\"$file\" selected>".$files;
}else{
$bar = $bar."<option value=\"$file\">".$files;
}
                    
                }
            }
            $bar = $bar;
$cont .= "$bar";

$cont.= <<<HTML
        </select>
        </font></td>
    </tr>
	  <tr>
      <td width="100"  ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Flood 
        Time</font></td>
      <td  ><input name="floodtime" type="text" id="floodtime" value="$floodtime" size="5">
        <font size="1" face="Verdana, Arial, Helvetica, sans-serif">( set to 0 to disable)</font></td>
    </tr>
    <tr> 
      <td colspan="2"  ><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="submit" name="Submit" value="Submit">
        </font></td>
    </tr>
  </table>
</form>
HTML;

	}}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
?> 

