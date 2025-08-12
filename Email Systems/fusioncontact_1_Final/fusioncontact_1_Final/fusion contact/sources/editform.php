  <?
  /*
Copyright Information
Script File :  editform.php
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
 if($_GET["admin"]=="forms"){
$title.="- edit Forms";
if($_GET["do"]=="1"){
//box names
$bn1=$_POST["bn1"];
$bn2=$_POST["bn2"];
$bn3=$_POST["bn3"];
$bn4=$_POST["bn4"];
$bn5=$_POST["bn5"];
$bn6=$_POST["bn6"];
$bn7=$_POST["bn7"];
$bn8=$_POST["bn8"];
$bn9=$_POST["bn9"];
$bn10=$_POST["bn10"];
$bn11=$_POST["bn11"];
$bn12=$_POST["bn12"];
$bn13=$_POST["bn13"];
$bn14=$_POST["bn14"];
$bn15=$_POST["bn15"];
//name ids
$w1=$_POST["w1"];
$w2=$_POST["w2"];
$w3=$_POST["w3"];
$w4=$_POST["w4"];
$w5=$_POST["w5"];
$w6=$_POST["w6"];
$w7=$_POST["w7"];
$w8=$_POST["w8"];
$w9=$_POST["w9"];
$w10=$_POST["w10"];


//type
$m1=$_POST["m1"];
$m2=$_POST["m2"];
$m3=$_POST["m3"];
$m4=$_POST["m4"];
$m5=$_POST["m5"];
$m6=$_POST["m6"];
$m7=$_POST["m7"];
$m8=$_POST["m8"];
$m9=$_POST["m9"];
$m10=$_POST["m10"];
//hieght
$h1=$_POST["h1"];
$h2=$_POST["h2"];
$h3=$_POST["h3"];
//options
$op1=$_POST["op1"];
$op2=$_POST["op2"];
$op3=$_POST["op3"];
$op4=$_POST["op4"];
$op5=$_POST["op5"];
$op6=$_POST["op6"];
$op7=$_POST["op7"];
$op8=$_POST["op8"];
$op9=$_POST["op9"];
$op10=$_POST["op10"];



       $conf='<?php'."\n";
	   // field name
	  $conf.='$bn1="'.$bn1.'"'.";\n";
	  $conf.='$bn2="'.$bn2.'"'.";\n";
	  $conf.='$bn3="'.$bn3.'"'.";\n";
	  $conf.='$bn4="'.$bn4.'"'.";\n";
	  $conf.='$bn5="'.$bn5.'"'.";\n";
	  $conf.='$bn6="'.$bn6.'"'.";\n";
	  $conf.='$bn7="'.$bn7.'"'.";\n";
	  $conf.='$bn8="'.$bn8.'"'.";\n";
	  $conf.='$bn9="'.$bn9.'"'.";\n";
	  $conf.='$bn10="'.$bn10.'"'.";\n";	
	  // Max chars
	  $conf.='$m1="'.$m1.'"'.";\n";
	  $conf.='$m2="'.$m2.'"'.";\n";
	  $conf.='$m3="'.$m3.'"'.";\n";
	  $conf.='$m4="'.$m4.'"'.";\n";
	  $conf.='$m5="'.$m5.'"'.";\n";
	  $conf.='$m6="'.$m6.'"'.";\n";
	  $conf.='$m7="'.$m7.'"'.";\n";
	  
	  //hieght
	  $conf.='$h1="'.$h1.'"'.";\n";
	  $conf.='$h2="'.$h2.'"'.";\n";
	  $conf.='$h3="'.$h3.'"'.";\n";
	 
	  //width
	  $conf.='$w1="'.$w1.'"'.";\n";
	  $conf.='$w2="'.$w2.'"'.";\n";
	  $conf.='$w3="'.$w3.'"'.";\n";
	  $conf.='$w4="'.$w4.'"'.";\n";
	  $conf.='$w5="'.$w5.'"'.";\n";
	  $conf.='$w6="'.$w6.'"'.";\n";
	  $conf.='$w7="'.$w7.'"'.";\n";
	  $conf.='$w8="'.$w8.'"'.";\n";
	  $conf.='$w9="'.$w9.'"'.";\n";
	  $conf.='$w10="'.$w10.'"'.";\n";
	 //options	  
	  $conf.='$op1="'.$op1.'"'.";\n";
	  $conf.='$op2="'.$op2.'"'.";\n";
	  $conf.='$op3="'.$op3.'"'.";\n";
	  $conf.='$op4="'.$op4.'"'.";\n";
	  $conf.='$op5="'.$op5.'"'.";\n";
	  $conf.='$op6="'.$op6.'"'.";\n";
	  $conf.='$op7="'.$op7.'"'.";\n";
	  $conf.='$op8="'.$op8.'"'.";\n";
	  $conf.='$op9="'.$op9.'"'.";\n";
	  $conf.='$op10="'.$op10.'"'.";\n";

	  
	  $conf.='?>';
	  $conf=stripslashes($conf);
	  $loc=fopen("./inc/form.db.php", "w");
      fwrite($loc, "$conf");
      fclose($loc);
	  	$cont.=  <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF" size="1" face="Verdana, Arial, Helvetica, sans-serif">Form Has been 
        successfully Updated.</font></div></td>
  </tr>
</table>

html;
  
}
include("./inc/form.db.php");

	$cont .=  <<<html
<form  method="post" action="?admin=forms&do=1">
  <table width="549" border="1" style="border-style: dashed; border-collapse:collapse" bordercolor="#000000">
    <tr> 
      <td width="36" align="center" ><font size="1" face="Verdana"><B>ID</B></font></td>
      <td width="55" align="center" ><font size="1" face="Verdana"><B>Enabled</B></font></td>
      <td width="70" align="center" ><font size="1" face="Verdana"><B>Type</B></font></td>
      <td width="56" align="center" ><font size="1" face="Verdana"><B>Width</B></font></td>
      <td width="56" align="center" ><font size="1" face="Verdana"><B>Height</B></font></td>
      <td width="56" align="center" ><font size="1" face="Verdana"><B>Max 
        Chars</B></font></td>
      <td width="168" align="center" ><font size="1" face="Verdana"><B>Name</B></font></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 1 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana">Yes 
        <input name="op1" type="hidden" id="op1" value="1">
        </font></td>
      <td align="center"><font size="1" face="Verdana">Input Box</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w1" type="text" class="post" id="w1" value="$w1" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="m1" type="text" class="post" id="m1" value="$m1" size="9">
        </font></td>
      <td align="center"> <font size="1" face="Verdana">$bn1 
        <input name="bn1" type="hidden" id="bn1" value="Name">
        </font></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 2 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana">Yes 
        <input name="op2" type="hidden" id="op2" value="1">
        </font></td>
      <td align="center"><font size="1" face="Verdana">Input Box</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w2" type="text" class="post" id="w2" value="$w2" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="m2" type="text" class="post" id="m2" value="$m2" size="9">
        </font></td>
      <td align="center"> <font size="1" face="Verdana"> $bn2
        <input name="bn2" type="hidden" id="bn2" value="E-mail">
        </font></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 3 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op3"  id="op3">
html;
          if($op3=="0"){
		  $cont.= "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op3=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Input Box</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w3" type="text" class="post" id="w3" value="$w3" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="m3" type="text" class="post" id="m3" value="$m3" size="9">
        </font></td>
      <td align="center"> 
        <input name="bn3" type="text" id="bn32" value="$bn3"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 4 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op4"  id="op4">
html;
          if($op4=="0"){
		   $cont.=  "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op4=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Input Box</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w4" type="text" class="post" id="w4" value="$w4" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="m4" type="text" class="post" id="m4" value="$m4" size="9">
        </font></td>
      <td align="center"> 
        <input name="bn4" type="text" id="bn42" value="$bn4"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 5 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op5"  id="op5">
html;
          if($op5=="0"){
		   $cont.=  "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op5=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Input Box</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w5" type="text" class="post" id="w5" value="$w5" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="m5" type="text" class="post" id="m5" value="$m5" size="9">
        </font></td>
      <td align="center"> 
        <input name="bn5" type="text" id="bn52" value="$bn5"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 6 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op6"  id="op6">
html;
          if($op6=="0"){
		   $cont.=  "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op6=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Input Box</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w6" type="text" class="post" id="w6" value="$w6" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="m6" type="text" class="post" id="m6" value="$m6" size="9">
        </font></td>
      <td align="center"> 
        <input name="bn6" type="text" id="bn62" value="$bn6"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 7 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op7"  id="op7">
html;
          if($op7=="0"){
		   $cont.=  "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op7=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Input Box</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w7" type="text" class="post" id="w7" value="$w7" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="m7" type="text" class="post" id="m7" value="$m7" size="9">
        </font></td>
      <td align="center"> 
        <input name="bn7" type="text" id="bn72" value="$bn7"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 8 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op8"  id="op8">
html;
          if($op8=="0"){
		   $cont.=  "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op8=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Textarea</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w8" type="text" class="post" id="w8" value="$w8" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="h1" type="text" class="post" id="h1" value="$h1" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"> 
        <input name="bn8" type="text" id="bn82" value="$bn8"></td>
    </tr>
    <tr> 
      <td align="center"><font size="1" face="Verdana">&nbsp; 9 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op9"  id="op9">
html;
          if($op9=="0"){
		   $cont.=  "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op9=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Textarea</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w9" type="text" class="post" id="w9" value="$w9" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="h2" type="text" class="post" id="h2" value="$h2" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"> 
        <input name="bn9" type="text" id="bn92" value="$bn9"></td>
    </tr>
    <tr> 
      <td height="27" align="center"><font size="1" face="Verdana">&nbsp; 10 &nbsp;</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <select name="op10"  id="op10">
html;
          if($op10=="0"){
		   $cont.=  "<option value=\"0\" selected>No</option>
		        <option value=\"1\" >Yes</option>";
		  }	 elseif($op10=="1"){
		    $cont.=  "<option value=\"0\" >No</option>
		        <option value=\"1\" selected>Yes</option>";
		  }		
          
          
		  
		  	 $cont.=  <<<html
        </select>
        </font></td>
      <td align="center"><font size="1" face="Verdana">Textarea</font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="w10" type="text" class="post" id="w10" value="$w10" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana"> 
        <input name="h3" type="text" class="post" id="h3" value="$h3" size="9">
        </font></td>
      <td align="center"><font size="1" face="Verdana">---</font></td>
      <td align="center"> 
        <input name="bn10" type="text" id="bn102" value="$bn10"></td>
    </tr>
    <tr> 
      <td height="27" colspan="7" align="center"><input type="submit" name="Submit" value="Submit"></td>
    </tr>
  </table>
</form>
	
html;
}  
	}// Ends Securtiy Check now lets display error msg
	else die(" You can not run this file from here");
?>
 </p> 

