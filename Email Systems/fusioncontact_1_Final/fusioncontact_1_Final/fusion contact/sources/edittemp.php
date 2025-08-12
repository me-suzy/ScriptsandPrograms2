<?
/*
Copyright Information
Script File :  edittemp.php
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
if($_GET["admin"]=="template"){ 
$title.="- edit Template";
if($_GET["do"]=="1"){
$temp=$_POST["template"];
       $conf='<?php'."\n";	
	  $conf.='$template=\''."\n";
      $conf.=$temp."\n";
      $conf.='\';'."\n";	  
	  $conf.='?>';
	  $conf=stripslashes($conf);
	   $loc=fopen("./inc/formtemp.db.php", "w");
 fwrite($loc, "$conf"); 
 fclose($loc); 
 $cont.=  <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">Form Template Has been 
        successfully Updated.</font></div></td>
  </tr>
</table>
	
html;
}
include("./inc/formtemp.db.php");
		  	 $cont.=  <<<html
       <form action="?admin=template&do=1" method="post">
<table width="549" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
  <tr> 
    <td width="337" align="center" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Edit 
      Form Template</strong></font></td>
    <td colspan="2" align="center" nowrap ><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><B>Template 
      Codes</B></font></td>
  </tr>
  <tr> 
    <td colspan="2" rowspan="23" valign="top"> 
      <textarea name="template" cols="70" rows="23" wrap="VIRTUAL">$template</textarea></td>
    <td width="75" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname1} 
      </font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname2} 
      </font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname3}</font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname4}</font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname5}</font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
      {fname6}</font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname7}</font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname8}</font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname9}</font></td>
  </tr>
  <tr> 
    <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{fname10}</font></td>
  </tr>
  <tr> 
    <td width="75" height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field1}&nbsp; 
      </font> </td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field2} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      {field3} </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field4} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field5} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field6} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field7} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field8} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{field9} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      {field10} </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{emails}</font></td>
  </tr>
  <tr> 
    <td height="16" align="center"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">{subjects} 
      </font></td>
  </tr>
  <tr> 
    <td height="16" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">{submit}</font></td>
  </tr>
  <tr> 
    <td height="23" colspan="3" valign="top"><div align="center"> 
        <input type="submit" name="Submit" value="Submit">
      </div></td>
  </tr>
</table>
</form>
	
html;
}
	}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
?>