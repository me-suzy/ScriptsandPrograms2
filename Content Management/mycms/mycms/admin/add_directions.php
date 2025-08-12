<?
if($mode == "other"){
$str = "Alernative route";


$table = "<tr> 
                                <td height='20' class = 'fshow4'>&nbsp;&nbsp;Travel 
                                  by</td>
                                <td class = 'fshow4'>$type</td>
                              </tr>";

$hidden = "<input name='type' type='hidden' id='type' value='$type'>";							  


} else{
 
 
 $table = "<tr> 
                                <td height='20' class = 'fshow4'>&nbsp;&nbsp;Travel 
                                  by</td>
                                <td class = 'fshow6'>Walking 
                                  <input name='type' type='radio' value='walking' checked> 
                                  &nbsp;Driving 
                                  <input type='radio' name='type' value='driving'></td>
                              </tr>";

}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Real Directions, Directions to your favorite pub, nightclub, cinema, restaurant, or theatre</title>
<LINK href="style.css" type="text/css" rel="styleSheet">
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta Http-Equiv="Content-language" Content="en-UK">
<meta Name="Coverage" Content="Worldwide">
<meta Name="rating" Content="General">
<meta Name="ROBOTS" Content="ALL">
<meta name="keywords" content="driving direction,direction,map direction,travel direction,get direction,road direction,new direction,same direction,find direction,drive direction,driving direction shortest,street direction,traveling direction,direction finder,car direction,trip direction,finding direction">

<script>

function checkrequired(which){
var pass=true
if (document.images){
for (i=0;i<which.length;i++){
var tempobj=which.elements[i]
if (tempobj.name.substring(0,8)=="required"){
if (((tempobj.type=="text"||tempobj.type=="textarea")&&tempobj.value=='')||(tempobj.type.toString().charAt(0)=="s"&&tempobj.selectedIndex==-1)){
pass=false
break
}
}
}
}
if (!pass){
alert("One or more of the required elements are not completed. Please complete them, then submit again!")
return false
}
else
return true
}
</script>



<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script>

</head>

<body bgcolor="#FAEBC5">
<center>
  <table width="80%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="293" valign="top" class = "sbox5">
	  
	  
	  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <tr> 
            <td colspan="3" align="center" valign="middle">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="28%" align="left">&nbsp;</td>
                  <td width="36%"><a href="index.php"><img src="images/logo.jpg" alt="directions" width="276" height="110" border="0"></a></td>
                  <td width="36%" align="right" valign="bottom" class = "fshow7"><a href="add_directions.php"><img src="images/adddirections.jpg" alt="directions" width="130" height="83" border="0"></a></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td width="10%" align="center"></td>
            <td width="56%" align="left">&nbsp;</td>
            <td width="34%" align="right" class = "fshow7"><a href="index.php" class = "homelink">Home</a><br><a href="link.php" class = "homelink">Link to 
              us</a><br> <a href="contact.php" class = "homelink">Contact</a></td>
          </tr>
          <tr> 
            <td align="left"></td>
            <td colspan="2" align="left"></td>
          </tr>
          <tr valign="top"> 
            <td height="295" colspan="3" > <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="62%" height="295" bgcolor="#FBF2E6" class = "phead"><form action="directions.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkrequired(this)">
                      <table width="98%" height="263" border="0" cellpadding="0" cellspacing="0" bgcolor="#FBF2E6">
                        <tr> 
                          <td align="center" valign="top"> <table width="92%" border="0" cellspacing="0" cellpadding="0">
                              <tr> 
                                <td width="38%" class = "fshow4"><font color="#FF0000">*</font> 
                                  Place</td>
                                <td width="62%" class = "fshow6"><input name="requiredplace" type="text" id="requiredplace" value="<? echo $place;?>" size="30"> 
                                  &nbsp;</td>
                              </tr>
                              <? echo $table; ?>
                              <tr> 
                                <td height="20" align="center" class = "fshow5" hieght = "3">&nbsp;</td>
                                <td height="20" align="left" class = "fshow8" hieght = "3"><? echo $str; ?></td>
                              </tr>
                              <tr> 
                                <td class = "fshow4"><font color="#FF0000">*</font> 
                                  Directions</td>
                                <td valign="top" class = "fshow6"><textarea name="requireddirections" cols="26" rows="4" id="requireddirections"></textarea> 
                                  <font color="#FF0000">&nbsp;</font></td>
                              </tr>
                              <tr> 
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr> 
                                <td class = "fshow4">&nbsp;&nbsp;Upload Picture</td>
                                <td align="left" class = "fshow6"><input name="img1" type="file" id="img1"></td>
                              </tr>
                              <tr> 
                                <td>&nbsp;</td>
                                <td align="center">&nbsp;</td>
                              </tr>
                              <tr> 
                                <td class = "fshow4">&nbsp; Address</td>
                                <td align="left" class = "fshow6"><input name="address" type="text" id="address" size="30"></td>
                              </tr>
                              <tr> 
                                <td>&nbsp;</td>
                                <td align="center">&nbsp;</td>
                              </tr>
                              <tr> 
                                <td><input name="did" type="hidden" id="did" value="<? echo $did; ?>">
								<? echo $hidden; ?>
								
								</td>
                                <td align="center"><input type="reset" name="Reset" value="Reset"> 
                                  &nbsp;&nbsp;&nbsp; <input type="submit" name="Submit2" value="Submit"></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                    </form></td>
                  <td width="38%" valign="top" bgcolor="#E9FCFE" class = "fshow6"><p>Please 
                      try to be as descriptive as posible when entering directions. 
                      Give landmarks i.e buildings and or street signs. </p>
                    <p>When giving directions for driving, include where to park 
                      and if posible where drivers can get free parking.</p>
                    <p>e.g Entry<br></b><strong>Place:</strong> Pearson professional centre<br>
					 <strong>Travel By: </strong> walking<br>
                      <strong>Directions: </strong>This is the Direction to get 
                      to the Car theory test centre based in Southwark. From London 
                      Bridge station turn right into Tooley Street. You then continue 
                      down the road pass The London Dungeons. At the third turning, 
                      turn right into Bermonsey Street, walk along for about 4 
                      minutes. Holyrood street on your left before you go under 
                      the bridge. The road is a bit dodgy as i didn't notice the 
                      road sign so be clearful you don't miss it. </p>
                    </td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="22" colspan="3" align="center">&nbsp; </td>
          </tr>
        </table>
        
      </td>
    </tr>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="44%" class = "fshow5"><a href="disclaimer.php">Disclaimer</a></td>
      <td width="56%" class = "fshow5">&copy;2005 RealDirections</td>
    </tr>
	<tr> 
      <td colspan="2" align="right" class = "fshow11"><a href="directionlist.php" class = "gallink">directions</a></td>
    </tr>
  </table>
  <script language="JavaScript1.2" defer>
editor_generate('requireddirections');
</script>
</center>
</body>
</html>
