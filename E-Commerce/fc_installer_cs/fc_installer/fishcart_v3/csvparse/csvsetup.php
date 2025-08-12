<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2002  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972
   
   CSVParse version 2.04 created by Chris Carroll
   ctcarroll@mindspring.com
   Completely modified version of CSVParse based on Simon Weller's original work
*/

include "./includes/fieldlist.php";

$fields=explode(",",$field_order);
$fieldcount = count($fields);

$j=0;
while ($j<$fieldcount) {
if (trim($fields[$j])=="prodname") { $ckedprodname=1; }
if (trim($fields[$j])=="prodsdescr") { $ckedprodsdescr=1; }
if (trim($fields[$j])=="proddescr") { $ckedproddescr=1; }
if (trim($fields[$j])=="prodkeywords") { $ckedprodkeywords=1; }
if (trim($fields[$j])=="prodrtlprice") { $ckedprodrtlprice=1; }
if (trim($fields[$j])=="prodprice") { $ckedprodprice=1; }
if (trim($fields[$j])=="prodsaleprice") { $ckedprodsaleprice=1; }
if (trim($fields[$j])=="ssy") { $ckedssy=1; }
if (trim($fields[$j])=="ssm") { $ckedssm=1; }
if (trim($fields[$j])=="ssd") { $ckedssd=1; }
if (trim($fields[$j])=="sey") { $ckedsey=1; }
if (trim($fields[$j])=="sem") { $ckedsem=1; }
if (trim($fields[$j])=="sed") { $ckedsed=1; }
if (trim($fields[$j])=="prodload") { $ckedprodload=1; }
if (trim($fields[$j])=="prodoffer") { $ckedprodoffer=1; }
if (trim($fields[$j])=="prodisbn") { $ckedprodisbn=1; }
if (trim($fields[$j])=="prodauth") { $ckedprodauth=1; }
if (trim($fields[$j])=="prodleadtime") { $ckedprodleadtime=1; }
if (trim($fields[$j])=="prodmcode") { $ckedprodmcode=1; }
if (trim($fields[$j])=="prodaudio") { $ckedprodaudio=1; }
if (trim($fields[$j])=="prodvideo") { $ckedprodvideo=1; }
if (trim($fields[$j])=="prodpic") { $ckedprodpic=1; }
if (trim($fields[$j])=="prodtpic") { $ckedprodtpic=1; }
if (trim($fields[$j])=="prodbanr") { $ckedprodbanr=1; }
if (trim($fields[$j])=="prodsplash") { $ckedprodsplash=1; }
if (trim($fields[$j])=="prodweight") { $ckedprodweight=1; }
if (trim($fields[$j])=="prodsetup") { $ckedprodsetup=1; }
if (trim($fields[$j])=="prodordmax") { $ckedprodordmax=1; }
if (trim($fields[$j])=="prodseq") { $ckedprodseq=1; }
if (trim($fields[$j])=="psy") { $ckedpsy=1; }
if (trim($fields[$j])=="psm") { $ckedpsm=1; }
if (trim($fields[$j])=="psd") { $ckedpsd=1; }
if (trim($fields[$j])=="pey") { $ckedpey=1; }
if (trim($fields[$j])=="pem") { $ckedpem=1; }
if (trim($fields[$j])=="ped") { $ckedped=1; }
if (trim($fields[$j])=="prodinvqty") { $ckedprodinvqty=1; }
if (trim($fields[$j])=="produseinvq") { $ckedprodinvq=1; }
if (trim($fields[$j])=="noship") { $ckednoship=1; }
if (trim($fields[$j])=="notax") { $ckednotax=1; }
if (trim($fields[$j])=="pc10") { $ckedpc10=1; }
if (trim($fields[$j])=="pcatseq10") { $ckedpcatseq10=1; }
if (trim($fields[$j])=="pc20") { $ckedpc20=1; }
if (trim($fields[$j])=="pcatseq20") { $ckedpcatseq20=1; }
$j++;
}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>CSV-Parse Setup</title>
</head>

<body>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="98%" height="11">&nbsp;</td>
    <td colspan="2" height="11" width="2%" bgcolor="#000000">&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td width="98%" height="62" valign="bottom"><img src="images/cvsparselogo.gif" width="242" height="52"></td>
    <td colspan="2" rowspan="4" bgcolor="#CCCCCC" width="2%"> 
      <p>&nbsp;</p>
      <p>&nbsp; </p>
    </td>
  </tr>
  <tr valign="top"> 
    <td width="98%"> 
      <p><br>
 <form action="csvsetup2.php" method="post" enctype="multipart/form-data">
 CSV-Parse Config modification<br>
<textarea cols="60" rows="12" name="configstuff">
<?php
readfile("./includes/csvconfig.php");
 ?>
</textarea><br>
    <input type="submit" value="Modify Config">
  </form></p>
  </td></tr>
  <tr valign="top"> 
    <td width="98%"> 
      <p><br>
<p>A list of fields and their field order currently selected to be used in your csvparse product upload.</p>  
  <?php
echo "<p><b>".$field_order."</b></p>\n";
?>

</p>   
 <form action="csvsetup3.php" method="post" enctype="multipart/form-data">
 <b>CSV-Parse Field Upload Selection</b><br>
 Select the fields you want to use for your csvparse product upload.<br>
 <b>Product SKU is always used.</b><br>
 <input type="checkbox" name="prodname0" value="$prodname0" <?php if ($ckedprodname==1) {echo "checked";} ?>>Product Name or prodname (This field is currently not use in an unmodified cart installation)<br>
 <input type="checkbox" name="sdescr0" value="$sdescr0" <?php if ($ckedprodsdescr==1) {echo "checked";} ?>>Short Description or prodsdescr<br>
 <input type="checkbox" name="descr0" value="$descr0" <?php if ($ckedproddescr==1) {echo "checked";} ?>>Long Description or proddescr<br>
 <input type="checkbox" name="keyword0" value="$keyword0" <?php if ($ckedprodkeywords==1) {echo "checked";} ?>>Keywords or prodkeywords<br>
 <input type="checkbox" name="rtlprice" value="$rtlprice" <?php if ($ckedprodrtlprice==1) {echo "checked";} ?>>Retail Price or prodrtlprice<br>
 <input type="checkbox" name="price" value="$price" <?php if ($ckedprodprice==1) {echo "checked";} ?>>Product Price or prodprice<br>
 <input type="checkbox" name="saleprice" value="$saleprice" <?php if ($ckedprodsaleprice==1) {echo "checked";} ?>>Sale Price or prodsaleprice<br><br>
 <b>Sale Start Date YY MM DD **</b> All three must be checked to use<br>
 <input type="checkbox" name="ssy" value="$ssy" <?php if ($ckedssy==1) {echo "checked";} ?>><font color="#006600">Sale Start Date Year or ssy<br>
 <input type="checkbox" name="ssm" value="$ssm" <?php if ($ckedssm==1) {echo "checked";} ?>>Sale Start Date Month or ssm<br>
 <input type="checkbox" name="ssd" value="$ssd" <?php if ($ckedssd==1) {echo "checked";} ?>>Sale Start Date Day or ssd</font><br>
 <b>Sale End Date YY MM DD **</b> All three must be checked to use<br>
 <input type="checkbox" name="sey" value="$sey" <?php if ($ckedsey==1) {echo "checked";} ?>><font color="#CC0000">Sale End Date Year or sey<br>
 <input type="checkbox" name="sem" value="$sem" <?php if ($ckedsem==1) {echo "checked";} ?>>Sale End Date Month or sem<br>
 <input type="checkbox" name="sed" value="$sed" <?php if ($ckedsed==1) {echo "checked";} ?>>Sale End Date Day or sed</font><br><br>
 <input type="checkbox" name="prodload0" value="$prodload0" <?php if ($ckedprodload==1) {echo "checked";} ?>>Product Download URI or prodload<br>
 <input type="checkbox" name="prodoffer0" value="$prodoffer0" <?php if ($ckedprodoffer==1) {echo "checked";} ?>>Product Offer or prodoffer<br>
 <input type="checkbox" name="prodisbn" value="$prodisbn" <?php if ($ckedprodisbn==1) {echo "checked";} ?>>Product ISBN<br>
 <input type="checkbox" name="prodauth0" value="$prodauth0" <?php if ($ckedprodauth==1) {echo "checked";} ?>>Author or prodauth<br>
 <input type="checkbox" name="prodleadtime0" value="$prodleadtime0" <?php if ($ckedprodleadtime==1) {echo "checked";} ?>>Product Lead Time Comments or prodleadtime<br>
 <input type="checkbox" name="prodmcode0" value="$prodmcode0" <?php if ($ckedprodmcode==1) {echo "checked";} ?>>Product Material Code or prodmcode<br>
 <input type="checkbox" name="audio0" value="$audio0" <?php if ($ckedprodaudio==1) {echo "checked";} ?>>Audio Clip or prodaudio<br>
 <input type="checkbox" name="video0" value="$video0" <?php if ($ckedprodvideo==1) {echo "checked";} ?>>Video Clip or prodvideo<br>
 <input type="checkbox" name="pic0" value="$pic0" <?php if ($ckedprodpic==1) {echo "checked";} ?>>Web Page Graphic URI or prodpic<br>
 <input type="checkbox" name="tpic0" value="$tpic0" <?php if ($ckedprodtpic==1) {echo "checked";} ?>>Thumbnail Graphic URI or prodtpic&nbsp;&nbsp;&nbsp;&nbsp;Graphic paths should be either relative to the installed cart (./...)<br> 
 <input type="checkbox" name="banr0" value="$banr0" <?php if ($ckedprodbanr==1) {echo "checked";} ?>>Banner Graphic URI or prodbanr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;or absolute with respect to the top of the Web site (//...).<br>
 <input type="checkbox" name="splash0" value="$splash0" <?php if ($ckedprodsplash==1) {echo "checked";} ?>>Sale Graphic URI or prodsplash<br>
 <input type="checkbox" name="prodweight" value="$prodweight" <?php if ($ckedprodweight==1) {echo "checked";} ?>>Product Weight or prodweight (format: 0.0000)<br>
 <input type="checkbox" name="setup" value="$setup" <?php if ($ckedprodsetup==1) {echo "checked";} ?>>Setup Fee or prodsetup<br>
 <input type="checkbox" name="ordmax" value="$ordmax" <?php if ($ckedprodordmax==1) {echo "checked";} ?>>Max Order Qty or prodmaxqty<br>
 <input type="checkbox" name="prodseq" value="$prodseq" <?php if ($ckedprodseq==1) {echo "checked";} ?>>Product Sequence #: optional for alternate product sort ordering<br>
 <b>Product Activation Date YY MM DD **</b> All three must be checked to use<br>
 <input type="checkbox" name="psy" value="$psy" <?php if ($ckedpsy==1) {echo "checked";} ?>><font color="#006600">Product Activation Date Year or psy<br>
 <input type="checkbox" name="psm" value="$psm" <?php if ($ckedpsm==1) {echo "checked";} ?>>Product Activation Date Month or psm<br>
 <input type="checkbox" name="psd" value="$psd" <?php if ($ckedpsd==1) {echo "checked";} ?>>Product Activation Date Day or psd</font><br>
 <b>Product Deactivation Date YY MM DD **</b> All three must be checked to use</font><br>
 <input type="checkbox" name="pey" value="$pey" <?php if ($ckedpey==1) {echo "checked";} ?>><font color="#CC0000">Product Deactivation Date Year or pey<br>
 <input type="checkbox" name="pem" value="$pem" <?php if ($ckedpem==1) {echo "checked";} ?>>Product Deactivation Date Month or pem<br>
 <input type="checkbox" name="ped" value="$ped" <?php if ($ckedped==1) {echo "checked";} ?>>Product Deactivation Date Day or ped</font><br><br>
 <input type="checkbox" name="invqty" value="$invqty" <?php if ($ckedprodinvqty==1) {echo "checked";} ?>><font color="#CC6600">Inventory Quantity or prodinvqty</font> (Use in conjunction with "Use Inventory Quantity Field?" checkbox below)<br><br>
 
 If below 3 fields are not selected they are defaulted to the following in the csvparse script.<br>
 <b>Use Inventory Quantity Field for product? NO<br>
 Charge Shipping on product? YES<br>
 Charge Tax on product? YES<br></b>
 <input type="checkbox" name="useinv" value="$useinv" <?php if ($ckedprodinvq==1) {echo "checked";} ?>><font color="#CC6600">Use Inventory Quantity Field? or produseinvq</font> (Uploaded data should be a 1(YES) or 0(NO))<br>
 <input type="checkbox" name="noship" value="$noship" <?php if ($ckednoship==1) {echo "checked";} ?>>Charge Shipping? or noship (Uploaded data should be a 1(NO) or 0(YES))<br>
 <input type="checkbox" name="notax" value="$notax" <?php if ($ckednotax==1) {echo "checked";} ?>>Charge Tax? or notax (Uploaded data should be a 1(NO) or 0(YES))<br><br>
 
 <b>Category Selection: Select up to three categories for a product<br>
 At least one category and sequence code must be checked. This determines the category or categories the product shows up in.</b><br>
 
 <input type="checkbox" name="pc00" value="$pc00" checked><b><font color="#330099">Category 1 or pc00</font></b> <font size="2">(Uploaded data should be a number. See /maint/categoryndx.php for Cat Name/Number pairs)</font><br>
 <input type="checkbox" name="pcatseq00" value="$pcatseq00" checked><b><font color="#330099">Category Sequence Code or pcatseq00</font> <font size="2">Must be selected in conjunction with above Category 1 check box</font></b><br>
 <input type="checkbox" name="pc10" value="$pc10>" <?php if ($ckedpc10==1) {echo "checked";} ?>><b>Category 2 or pc10</b> <font size="2">(Uploaded data should be a number. See /maint/categoryndx.php for Cat Name/Number pairs)</font><br>
 <input type="checkbox" name="pcatseq10" value="$pcatseq10" <?php if ($ckedpcatseq10==1) {echo "checked";} ?>><b>Category Sequence Code or pcatseq10 <font size="2">Must be selected in conjunction with above Category 2 check box</font></b><br>
 <input type="checkbox" name="pc20" value="$pc20" <?php if ($ckedpc20==1) {echo "checked";} ?>><b>Category 3 or pc20</b> <font size="2">(Uploaded data should be a number. See /maint/categoryndx.php for Cat Name/Number pairs)</font><br>
 <input type="checkbox" name="pcatseq20" value="$pcatseq20" <?php if ($ckedpcatseq20==1) {echo "checked";} ?>><b>Category Sequence Code or pcatseq20 <font size="2">Must be selected in conjunction with above Category 3 check box</font></b><br><br>

    <input type="submit" value="Submit Field Selection for Product Upload">
  </form></p>
  </td></tr>
</table>

</body>
</html>
