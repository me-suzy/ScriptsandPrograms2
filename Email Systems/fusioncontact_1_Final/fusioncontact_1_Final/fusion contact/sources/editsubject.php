<?
/*
Copyright Information
Script File :  editsubject.php
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
if($_GET["admin"]=="subject"){
$title.="- edit subjects";
if($_GET["do"]=="1"){
//subject
$sub1=$_POST["sub1"];
$sub2=$_POST["sub2"];
$sub3=$_POST["sub3"];
$sub4=$_POST["sub4"];
$sub5=$_POST["sub5"];
$sub6=$_POST["sub6"];
$sub7=$_POST["sub7"];
$sub8=$_POST["sub8"];
$sub9=$_POST["sub9"];
$sub10=$_POST["sub10"];
//subjects values
$sv1=$_POST["sv1"];
$sv2=$_POST["sv2"];
$sv3=$_POST["sv3"];
$sv4=$_POST["sv4"];
$sv5=$_POST["sv5"];
$sv6=$_POST["sv6"];
$sv7=$_POST["sv7"];
$sv8=$_POST["sv8"];
$sv9=$_POST["sv9"];
$sv10=$_POST["sv10"];

       $conf='<?php'."\n";
   	  $conf.='$sub1="'.$sub1.'"'.";\n";
	  $conf.='$sub2="'.$sub2.'"'.";\n";
	  $conf.='$sub3="'.$sub3.'"'.";\n";
	  $conf.='$sub4="'.$sub4.'"'.";\n";
	  $conf.='$sub5="'.$sub5.'"'.";\n";
	  $conf.='$sub6="'.$sub6.'"'.";\n";
	  $conf.='$sub7="'.$sub7.'"'.";\n";
	  $conf.='$sub8="'.$sub8.'"'.";\n";
	  $conf.='$sub9="'.$sub9.'"'.";\n";
	  $conf.='$sub10="'.$sub10.'"'.";\n";

	  $conf.='$sv1="'.$sv1.'"'.";\n";
	  $conf.='$sv2="'.$sv2.'"'.";\n";
	  $conf.='$sv3="'.$sv3.'"'.";\n";
	  $conf.='$sv4="'.$sv4.'"'.";\n";
	  $conf.='$sv5="'.$sv5.'"'.";\n";
	  $conf.='$sv6="'.$sv6.'"'.";\n";
	  $conf.='$sv7="'.$sv7.'"'.";\n";
	  $conf.='$sv8="'.$sv8.'"'.";\n";
	  $conf.='$sv9="'.$sv9.'"'.";\n";
	  $conf.='$sv10="'.$sv10.'"'.";\n";
	  
	  $conf.='?>';
	  $conf=stripslashes($conf);
	  $loc=fopen("./inc/subject.db.php", "w");
      fwrite($loc, "$conf");
      fclose($loc);
	  
	 $cont.=  <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">Subjects have 
        been successfully Updated.</font></div></td>
  </tr>
</table>
html;
 } 
 include("./inc/subject.db.php"); 
	 $cont.=  <<<html
					
<form action="?admin=subject&do=1" method="post">
  <table width="403" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
    <tr> 
      <td width="9%" align="center" ><font size="1" face="Verdana"><B>ID</B></font></td>
      <td width="47%" align="center" ><font size="1" face="Verdana"><B>Subject</B></font></td>
      <td align="center" ><font size="1" face="Verdana"><B>value</B></font></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 1 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="sub1" type="text" id="sub1" value="$sub1">
        </font></td>
      <td width="44%" align="center"> <input name="sv1" type="text" id="e12" value="$sv1"> 
      </td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 2 &nbsp;</font></td>
      <td align="center"><input name="sub2" type="text" id="n23" value="$sub2"></td>
      <td align="center"> <input name="sv2" type="text" id="e22" value="$sv2"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 3 &nbsp;</font></td>
      <td align="center"><input name="sub3" type="text" id="n33" value="$sub3"></td>
      <td align="center"> <input name="sv3" type="text" id="e32" value="$sv3"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 4 &nbsp;</font></td>
      <td align="center"><input name="sub4" type="text" id="n43" value="$sub4"></td>
      <td align="center"> <input name="sv4" type="text" id="e42" value="$sv4"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 5 &nbsp;</font></td>
      <td align="center"><input name="sub5" type="text" id="n53" value="$sub5"></td>
      <td align="center"> <input name="sv5" type="text" id="e52" value="$sv5"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 6 &nbsp;</font></td>
      <td align="center"><input name="sub6" type="text" id="n63" value="$sub6"></td>
      <td align="center"> <input name="sv6" type="text" id="e62" value="$sv6"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 7 &nbsp;</font></td>
      <td align="center"><input name="sub7" type="text" id="n73" value="$sub7"></td>
      <td align="center"> <input name="sv7" type="text" id="e72" value="$sv7"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 8 &nbsp;</font></td>
      <td align="center"><input name="sub8" type="text" id="n83" value="$sub8"></td>
      <td align="center"> <input name="sv8" type="text" id="e82" value="$sv8"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 9 &nbsp;</font></td>
      <td align="center"><input name="sub9" type="text" id="n93" value="$sub9"></td>
      <td align="center"> <input name="sv9" type="text" id="e92" value="$sv9"></td>
    </tr>
    <tr> 
      <td height="27" align="center"><font size="1" face="Verdana">&nbsp; 10 &nbsp;</font></td>
      <td align="center"><input name="sub10" type="text" id="n103" value="$sub10"></td>
      <td align="center"> <input name="sv10" type="text" id="e102" value="$sv10"></td>
    </tr>
    <tr> 
      <td height="27" colspan="3" align="center"><input name="submit"  type="submit" value=" Save "> 
      </td>
    </tr>
  </table>

</form>
html;
} 
	}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
?> 