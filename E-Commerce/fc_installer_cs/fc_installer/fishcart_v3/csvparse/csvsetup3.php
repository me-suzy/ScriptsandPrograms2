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
   This script was created by Chris Carroll
*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>CSV-Parse Field Setup</title>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="98%" height="11">&nbsp;</td>
    <td colspan="2" height="11" width="2%" bgcolor="#000000">&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td width="98%" height="62" valign="bottom"><img src="images/cvsparselogo.gif" width="242" height="52"></td>
    <td colspan="2" height="231" rowspan="2" bgcolor="#CCCCCC" width="2%"> 
      <p>&nbsp;</p>
      <p>&nbsp; </p>
    </td>
  </tr>
  <tr valign="top"> 
    <td width="98%" height="170"> 
      <p><br>
 <?
 /* 
	** open config file for writing 
	*/
	$field_config = fopen("./includes/csvfields.php","w+");
	
	/*
	** make sure the open was successful
	*/
	if(!($field_config))
	{
		print("Error: ");
		print("'csvfields.php' could not be opened, please check your file/directory permissions\n");
		
		print "</tr>\n";
print "</table>\n";
print "</body>\n";
print "</html>";
		exit;
		
	}
	
	/* 
	** open csv prod insert and update statements file for writing 
	*/
	$prod_state = fopen("./includes/csvprod.php","w+");
	
	/*
	** make sure the open was successful
	*/
	if(!($prod_state))
	{
		print("Error: ");
		print("'csvprod.php' could not be opened, please check your file/directory permissions\n");
		
		print "</tr>\n";
print "</table>\n";
print "</body>\n";
print "</html>";
		exit;
		
	}
	
	/* 
	** open csv prodlang insert and update statements file for writing 
	*/
	$prodlang_state = fopen("./includes/csvprodlang.php","w+");
	
	/*
	** make sure the open was successful
	*/
	if(!($prodlang_state))
	{
		print("Error: ");
		print("'csvprodlang.php' could not be opened, please check your file/directory permissions\n");
		
		print "</tr>\n";
print "</table>\n";
print "</body>\n";
print "</html>";
		exit;
		
	}
	
	/* 
	** open csv fieldlist file for writing 
	*/
	$field_list = fopen("./includes/fieldlist.php","w+");
	
	/*
	** make sure the open was successful
	*/
	if(!($field_list))
	{
		print("Error: ");
		print("'fieldlist.php' could not be opened, please check your file/directory permissions\n");
		
		print "</tr>\n";
print "</table>\n";
print "</body>\n";
print "</html>";
		exit;
		
	}

	 
	// write field config data to file 
	$fieldstuff .= "<?php\n";
	
	// write prod state data to file
	$prodstuff .= "<?php\n";
	
	// write prod state data to file
	$prodlangstuff .= "<?php\n";
	
	// write field list data to file
	$field_liststuff .= "<?php\n";
	
	// builds the product table insert statement  for selectable fields, 
	// see ereg_replace statements near bottom of script for reason "_" is inserted here
	$prodfieldstate = "_";
	// builds the product table insert statement for variables of selectable fields
	$prodvaristate  = "_";
	// builds the product table update statement for selectable fields
	$produpdstate  = "_";
	// builds the product lang table insert statement for selectable fields
	$prodlangfieldstate  = "_";
	// builds the product lang table insert statement for variables of selectable fields
	$prodlangvaristate  = "_";
	// builds the product lang table update statement for selectable fields
	$prodlangupdstate  = "_";
	
	
	
// below if statements needed if these fields are not selected for use in CSVparse
// because these fields are always used in the csvparse.php file	
	
if (empty($useinv)) {
$fieldstuff .= sprintf("\$useinv = 0;\n");
}
if (empty($noship)) {
$fieldstuff .= sprintf("\$noship = 0;\n");
}
if (empty($notax)) {
$fieldstuff .= sprintf("\$notax = 0;\n");
}

if (empty($prodseq)) {
$fieldstuff .= sprintf("\$prodseq = '';\n");
}

$fieldstuff .= "\n\n";

// make the product field : csv data field 	association and list order
	$i = 0;
	
$fieldstuff .= sprintf("\$sku = \$data[%s];\n", $i++);
	
	$field_order= "sku";
	
	
	//      Product Name
if (!empty($prodname0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodname0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodname';
$prodlangvaristate  = $prodlangvaristate.',\'$prodname\'';
$prodlangupdstate  = $prodlangupdstate.',prodname=\'$prodname\'';

	$field_order = $field_order.', prodname';
	
}

		//    Short Product Description
if (!empty($sdescr0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $sdescr0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodsdescr';
$prodlangvaristate  = $prodlangvaristate.',\'$tsdescr\'';
$prodlangupdstate  = $prodlangupdstate.',prodsdescr=\'$tsdescr\'';

	$field_order = $field_order.', prodsdescr';
	
}

		//    Product Description
if (!empty($descr0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $descr0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',proddescr';
$prodlangvaristate  = $prodlangvaristate.',\'$tdescr\'';
$prodlangupdstate  = $prodlangupdstate.',proddescr=\'$tdescr\'';

	if ($list == "long") {
	$field_order = $field_order.', Prod Descr.';
	} else {
	$field_order = $field_order.', proddescr';
	}
}

		//    Product keywords
if (!empty($keyword0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $keyword0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodkeywords';
$prodlangvaristate  = $prodlangvaristate.',\'$tkeyword\'';
$prodlangupdstate  = $prodlangupdstate.',prodkeywords=\'$tkeyword\'';

	$field_order = $field_order.', prodkeywords';
	
}


		//    Product Retail Price
if (!empty($rtlprice)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $rtlprice, $i++);
$prodfieldstate = $prodfieldstate.',prodrtlprice';
$prodvaristate =  $prodvaristate.',$rtlprice';
$produpdstate = $produpdstate.',prodrtlprice=$rtlprice';

	$field_order = $field_order.', prodrtlprice';
	
}

		//    Product Price
if (!empty($price)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $price, $i++);
$prodfieldstate = $prodfieldstate.',prodprice';
$prodvaristate =  $prodvaristate.',$price';
$produpdstate = $produpdstate.',prodprice=$price';

	$field_order = $field_order.', prodprice';
	
}

		//    Sale Price
if (!empty($saleprice)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $saleprice, $i++);
$prodfieldstate = $prodfieldstate.',prodsaleprice';
$prodvaristate =  $prodvaristate.',$saleprice';
$produpdstate = $produpdstate.',prodsaleprice=$saleprice';

	$field_order = $field_order.', prodsaleprice';
	
}


		//    Sale Start Date		
if (!empty($ssy)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $ssy, $i++);

		$field_order = $field_order.', ssy';
		
}
if (!empty($ssy) && empty($ssm)) {
	echo "ERROR: Please make sure ALL Three Sale Start Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($ssm)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $ssm, $i++);

		$field_order = $field_order.', ssm';
		
	}
if ((!empty($ssy) && !empty($ssm)) && empty($ssd)) {
echo "ERROR: Please make sure ALL Three Sale Start Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($ssd)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $ssd, $i++);

		$field_order = $field_order.', ssd';
		
	}

		//    Sale End Date		
if (!empty($ssy)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $sey, $i++);

	$field_order = $field_order.', sey';
	
}
if (!empty($sey) && empty($sem)) {
	echo "ERROR: Please make sure ALL Three Sale End Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($sem)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $sem, $i++);

		$field_order = $field_order.', sem';
		
	}
if ((!empty($sey) && !empty($sem)) && empty($sed)) {
echo "ERROR: Please make sure ALL Three Sale End Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($sed)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $sed, $i++);

		$field_order = $field_order.', sed';
		
	}

		//    Product Download URI
if (!empty($prodload0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodload0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',proddload';
$prodlangvaristate  = $prodlangvaristate.',\'$dload\'';
$prodlangupdstate  = $prodlangupdstate.',proddload=\'$dload\'';

	$field_order = $field_order.', prodload';
	
}

		//    Product Offer
if (!empty($prodoffer0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodoffer0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodoffer';
$prodlangvaristate  = $prodlangvaristate.',\'$toffer\'';
$prodlangupdstate  = $prodlangupdstate.',prodoffer=\'$toffer\'';

	$field_order = $field_order.', prodoffer';
	
}

		//    Product ISBN
if (!empty($prodisbn)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodisbn, $i++);
$prodfieldstate = $prodlangfieldstate.',prodisbn';
$prodvaristate  = $prodlangvaristate.',\'$prodisbn\'';
$produpdstate  = $prodlangupdstate.',prodisbn=\'$prodisbn\'';

	$field_order = $field_order.', prodisbn';
	
}

		//    Author
if (!empty($prodauth0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodauth0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodauth';
$prodlangvaristate  = $prodlangvaristate.',\'$prodauth\'';
$prodlangupdstate  = $prodlangupdstate.',prodauth=\'$prodauth\'';

	$field_order = $field_order.', prodauth';
	
}

		//    Product Lead Time Comments
if (!empty($prodleadtime0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodleadtime0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodleadtime';
$prodlangvaristate  = $prodlangvaristate.',\'$prodleadtime\'';
$prodlangupdstate  = $prodlangupdstate.',prodleadtime=\'$prodleadtime\'';

	$field_order = $field_order.', prodleadtime';
	
}

		//    Prod Material Code
if (!empty($prodmcode0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodmcode0, $i++);
$prodfieldstate = $prodfieldstate.',prodmcode';
$prodvaristate =  $prodvaristate.',\'$prodmcode0\'';
$produpdstate = $produpdstate.',prodmcode=\'$prodmcode0\'';

	$field_order = $field_order.', prodmcode';
	
}

		//    Audio Clip URI
if (!empty($audio0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $audio0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodaudio';
$prodlangvaristate  = $prodlangvaristate.',\'$taudio\'';
$prodlangupdstate  = $prodlangupdstate.',prodaudio=\'$taudio\'';

	$field_order = $field_order.', prodaudio';
	
}

		//    Video Clip URI
if (!empty($video0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $video0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodvideo';
$prodlangvaristate  = $prodlangvaristate.',\'$tvideo\'';
$prodlangupdstate  = $prodlangupdstate.',prodvideo=\'$tvideo\'';

	$field_order = $field_order.', prodvideo';
	
}

		//    Web Page Graphic URI
if (!empty($pic0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pic0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodpic,prodpich,prodpicw';
$prodlangvaristate  = $prodlangvaristate.',\'$tpic\',$tpich,$tpicw';
$prodlangupdstate  = $prodlangupdstate.',prodpic=\'$tpic\', prodpich=$tpich, prodpicw=$tpicw';

	$field_order = $field_order.', prodpic';
	
}

		//    Thumbnail Graphic URI
if (!empty($tpic0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $tpic0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodtpic,prodtpich,prodtpicw';
$prodlangvaristate  = $prodlangvaristate.',\'$ttpic\',$ttpich,$ttpicw';
$prodlangupdstate  = $prodlangupdstate.',prodtpic=\'$ttpic\', prodtpich=$ttpich, prodtpicw=$ttpicw';

	$field_order = $field_order.', prodtpic';
	
}

		//    Banner Graphic URI
if (!empty($banr0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $banr0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodbanr,prodbanrh,prodbanrw';
$prodlangvaristate  = $prodlangvaristate.',\'$tbanr\',$tbanrh,$tbanrw';
$prodlangupdstate  = $prodlangupdstate.',prodbanr=\'$tbanr\', prodbanrh=$tbanrh, prodbanrw=$tbanrw';

	$field_order = $field_order.', prodbanr';
	
}

		//    Sale Graphic URI
if (!empty($splash0)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $splash0, $i++);
$prodlangfieldstate = $prodlangfieldstate.',prodsplash';
$prodlangvaristate  = $prodlangvaristate.',\'$tsplash\'';
$prodlangupdstate  = $prodlangupdstate.',prodsplash=\'$tsplash\'';

	$field_order = $field_order.', prodsplash';
	
}

		//    Product Weight
if (!empty($prodweight)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodweight, $i++);
$prodfieldstate = $prodfieldstate.',prodweight';
$prodvaristate =  $prodvaristate.',$prodweight';
$produpdstate = $produpdstate.',prodweight=$prodweight';

	$field_order = $field_order.', prodweight';
	
}

		//    Setup Fee
if (!empty($setup)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $setup, $i++);
$prodfieldstate = $prodfieldstate.',prodsetup';
$prodvaristate =  $prodvaristate.',$setup';
$produpdstate = $produpdstate.',prodsetup=$setup';

	$field_order = $field_order.', prodsetup';
	
}

		//    Max Order Quantity
if (!empty($ordmax)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $ordmax, $i++);
$prodfieldstate = $prodfieldstate.',prodordmax';
$prodvaristate =  $prodvaristate.',$ordmax';
$produpdstate = $produpdstate.',prodordmax=$ordmax';

	$field_order = $field_order.', prodordmax';
	
}

		//    Product Sequence : this field is set above if 
		//     not selected to be used in csvparse
if (!empty($prodseq)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $prodseq, $i++);

	$field_order = $field_order.', prodseq';
	
}

		//    Product Activation Date
if (!empty($psy)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $psy, $i++);

	$field_order = $field_order.', psy';
	
}
if (!empty($psy) && empty($psm)) {
	echo "ERROR: Please make sure ALL Three Product Activation Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($psm)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $psm, $i++);

		$field_order = $field_order.', psm';
		
	}
if ((!empty($psy) && !empty($psm)) && empty($psd)) {
echo "ERROR: Please make sure ALL Three Product Activation Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($psd)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $psd, $i++);

		$field_order = $field_order.', psd';
		
	}

		//    Product Deactivation Date
if (!empty($pey)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pey, $i++);

	$field_order = $field_order.', pey';
	
}
if (!empty($pey) && empty($pem)) {
	echo "ERROR: Please make sure ALL Three Product Deactivation Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($pem)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $pem, $i++);

		$field_order = $field_order.', pem';
		
	}
if ((!empty($pey) && !empty($pem)) && empty($ped)) {
echo "ERROR: Please make sure ALL Three Product Deactivation Date Fields are Checked.</td></tr></table></body></html>";
	exit;
	} else if (!empty($ped)) {
	$fieldstuff .= sprintf("%s = \$data[%s];\n", $ped, $i++);

		$field_order = $field_order.', ped';
		
	}

		//    Inventory Qunatity
if (!empty($invqty)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $invqty, $i++);
$prodfieldstate = $prodfieldstate.',prodinvqty';
$prodvaristate =  $prodvaristate.',$invqty';
$produpdstate = $produpdstate.',prodinvqty=$invqty';

	$field_order = $field_order.', prodinvqty';
	
}


		//    Use Inventory Quantity Field? : this field is set at top of script if 
		//     not selected to be used in csvparse
if (!empty($useinv)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $useinv, $i++);

	$field_order = $field_order.', produseinvq';
	
}

		//    Charge Shipping? : this field is set at top of script if 
		//     not selected to be used in csvparse
if (!empty($noship)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $noship, $i++);

	$field_order = $field_order.', noship';
	
}

		//    Charge Tax? : this field is set at top of script if 
		//     not selected to be used in csvparse
if (!empty($notax)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $notax, $i++);

	$field_order = $field_order.', notax';
	
}

		//    Category Selection
if (!empty($pc00)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pc00, $i++);

	$field_order = $field_order.', pc00';
	
}
if (!empty($pcatseq00)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pcatseq00, $i++);

	$field_order = $field_order.', pcatseq00';
	
}

if (!empty($pc10)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pc10, $i++);

	$field_order = $field_order.', pc10';
	
}
if (!empty($pcatseq10)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pcatseq10, $i++);

	$field_order = $field_order.', pcatseq10';
	
}

if (!empty($pc20)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pc20, $i++);

	$field_order = $field_order.', pc20';
	
}
if (!empty($pcatseq20)) {
$fieldstuff .= sprintf("%s = \$data[%s];\n", $pcatseq20, $i++);

	$field_order = $field_order.', pcatseq20';
	
}
$fieldstuff .= "\n\n\n";


// determines how many times to loop through the Product/category association 
//  part of the cscparse.php script based on # of cats you want product to display in
if (empty($pc00) || empty($pcatseq00)) {
echo "Category Selection 1 and Category Sequence Code 1 must be selected.";
exit;
} 
if (!empty($pc00) && empty($pc10) && empty($pc20)) {
$catselloop = 1;
} 
else if (!empty($pc00) && !empty($pc10) && empty($pc20)) {
$catselloop = 2;
}
else if (!empty($pc00) && !empty($pc10) && !empty($pc20)) {
$catselloop = 3;
} 

$fieldstuff .= sprintf("\$catselloop = %s;\n\n\n", $catselloop);

$prodfieldstate=ereg_replace("_,","",$prodfieldstate);   /* remove comma from start of statement */
$prodstuff .= sprintf("\$prodfieldstate = \"%s\";\n\n", $prodfieldstate);

$prodvaristate=ereg_replace("_,","",$prodvaristate);   /* remove comma from start of statement */
$prodstuff .= sprintf("\$prodvaristate = \"%s\";\n\n", $prodvaristate);

$produpdstate=ereg_replace("_,","",$produpdstate);   /* remove comma from start of statement */
$prodstuff .= sprintf("\$produpdstate = \"%s\";\n\n", $produpdstate);

$prodlangfieldstate=ereg_replace("_,","",$prodlangfieldstate);   /* remove comma from start of statement */
$prodlangstuff .= sprintf("\$prodlangfieldstate = \"%s\";\n\n", $prodlangfieldstate);

$prodlangvaristate=ereg_replace("_,","",$prodlangvaristate);   /* remove comma from start of statement */
$prodlangstuff .= sprintf("\$prodlangvaristate = \"%s\";\n\n", $prodlangvaristate);

$prodlangupdstate=ereg_replace("_,","",$prodlangupdstate);   /* remove comma from start of statement */
$prodlangstuff .= sprintf("\$prodlangupdstate = \"%s\";\n\n", $prodlangupdstate);

	$fieldstuff .= "?>\n";	
	fputs($field_config, $fieldstuff);
	fclose($field_config); // close the config file
	
	$prodstuff .= "?>\n";	
	fputs($prod_state, $prodstuff);
	fclose($prod_state); // close the config file
	
	$prodlangstuff .= "?>\n";	
	fputs($prodlang_state, $prodlangstuff);
	fclose($prodlang_state); // close the config file
	
	$field_liststuff .= sprintf("\$field_order = \"%s\";\n\n", $field_order);
	$field_liststuff .= "?>\n";	
	fputs($field_list, $field_liststuff);
	fclose($field_list); // close the config file
?>
<p>The CSV-Parse Field Upload Selection file has been updated, thank you.</p>
<table width="100%"><tr><td>
<p><b>CSVPARSE Field Order</b></p>
<?php echo $field_order; ?>
</td></tr></table>
<p><a href="index.php">Back to the menu</a></p>
<p><a href="csvsetup.php">Back to the CSV-Parse Setup</a> Don't forget to run CSV-Parse Config modification if you haven't set it already.</p>
  </td></tr>
</table>
</body>
</html>
