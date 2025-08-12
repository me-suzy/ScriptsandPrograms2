<?
/*
Copyright Information
Script File :  editemail.php
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
if($_GET["admin"]=="email"){
$title.="- edit Emails";
if($_GET["do"]=="1"){
$n1=$_POST["n1"];
$n2=$_POST["n2"];
$n3=$_POST["n3"];
$n4=$_POST["n4"];
$n5=$_POST["n5"];
$n6=$_POST["n6"];
$n7=$_POST["n7"];
$n8=$_POST["n8"];
$n9=$_POST["n9"];
$n10=$_POST["n10"];
$e1=$_POST["e1"];
$e2=$_POST["e2"];
$e3=$_POST["e3"];
$e4=$_POST["e4"];
$e5=$_POST["e5"];
$e6=$_POST["e6"];
$e7=$_POST["e7"];
$e8=$_POST["e8"];
$e9=$_POST["e9"];
$e10=$_POST["e10"];

       $conf='<?php'."\n";
   	  $conf.='$n1="'.$n1.'"'.";\n";
	  $conf.='$n2="'.$n2.'"'.";\n";
	  $conf.='$n3="'.$n3.'"'.";\n";
	  $conf.='$n4="'.$n4.'"'.";\n";
	  $conf.='$n5="'.$n5.'"'.";\n";
	  $conf.='$n6="'.$n6.'"'.";\n";
	  $conf.='$n7="'.$n7.'"'.";\n";
	  $conf.='$n8="'.$n8.'"'.";\n";
	  $conf.='$n9="'.$n9.'"'.";\n";
	  $conf.='$n10="'.$n10.'"'.";\n";

	  $conf.='$e1="'.$e1.'"'.";\n";
	  $conf.='$e2="'.$e2.'"'.";\n";
	  $conf.='$e3="'.$e3.'"'.";\n";
	  $conf.='$e4="'.$e4.'"'.";\n";
	  $conf.='$e5="'.$e5.'"'.";\n";
	  $conf.='$e6="'.$e6.'"'.";\n";
	  $conf.='$e7="'.$e7.'"'.";\n";
	  $conf.='$e8="'.$e8.'"'.";\n";
	  $conf.='$e9="'.$e9.'"'.";\n";
	  $conf.='$e10="'.$e10.'"'.";\n";
	  
	  $conf.='?>';
	  $conf=stripslashes($conf);
	  $loc=fopen("./inc/emails.db.php", "w");
      fwrite($loc, "$conf");
      fclose($loc);
	 $cont.=  <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">E-mails have 
        been successfully Updated.</font></div></td>
  </tr>
</table>

html;
 } 
 include("./inc/emails.db.php"); 

	 $cont.=  <<<html
					
<form action="?admin=email&do=1" method="post">
  <table width="403" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
    <tr> 
      <td width="9%" align="center" ><font size="1" face="Verdana"><B>ID</B></font></td>
      <td width="47%" align="center" ><font size="1" face="Verdana"><B>Name</B></font></td>
      <td align="center" ><font size="1" face="Verdana"><B>E-mail</B></font></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 1 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="n1" type="text" id="n13" value="$n1">
        </font></td>
      <td width="44%" align="center"> <input name="e1" type="text" id="e12" value="$e1"> 
      </td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 2 &nbsp;</font></td>
      <td align="center"><input name="n2" type="text" id="n23" value="$n2"></td>
      <td align="center"> <input name="e2" type="text" id="e22" value="$e2"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 3 &nbsp;</font></td>
      <td align="center"><input name="n3" type="text" id="n33" value="$n3"></td>
      <td align="center"> <input name="e3" type="text" id="e32" value="$e3"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 4 &nbsp;</font></td>
      <td align="center"><input name="n4" type="text" id="n43" value="$n4"></td>
      <td align="center"> <input name="e4" type="text" id="e42" value="$e4"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 5 &nbsp;</font></td>
      <td align="center"><input name="n5" type="text" id="n53" value="$n5"></td>
      <td align="center"> <input name="e5" type="text" id="e52" value="$e5"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 6 &nbsp;</font></td>
      <td align="center"><input name="n6" type="text" id="n63" value="$n6"></td>
      <td align="center"> <input name="e6" type="text" id="e62" value="$e6"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 7 &nbsp;</font></td>
      <td align="center"><input name="n7" type="text" id="n73" value="$n7"></td>
      <td align="center"> <input name="e7" type="text" id="e72" value="$e7"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 8 &nbsp;</font></td>
      <td align="center"><input name="n8" type="text" id="n83" value="$n8"></td>
      <td align="center"> <input name="e8" type="text" id="e82" value="$e8"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 9 &nbsp;</font></td>
      <td align="center"><input name="n9" type="text" id="n93" value="$n9"></td>
      <td align="center"> <input name="e9" type="text" id="e92" value="$e9"></td>
    </tr>
    <tr> 
      <td height="27" align="center"><font size="1" face="Verdana">&nbsp; 10 &nbsp;</font></td>
      <td align="center"><input name="n10" type="text" id="n103" value="$n10"></td>
      <td align="center"> <input name="e10" type="text" id="e102" value="$e10"></td>
    </tr>
    <tr> 
      <td height="27" colspan="3" align="center"><input name="submit"  type="submit" value=" Save "> 
      </td>
    </tr>
  </table>
  <div align="center"> </div>
</form>

html;
 } 
	}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
 ?>