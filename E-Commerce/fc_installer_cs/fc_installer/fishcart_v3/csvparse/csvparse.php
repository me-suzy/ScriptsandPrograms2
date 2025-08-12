<?php
/*
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

Version 2.04
Please read the readme.txt file before attempting to use this script.
* This is the thrid major release of this script, designed to allow mass products to be 
added/updated into the  fishcart database via loading sku's via a CSV file. 
Makes it a lot easy to manage a large product list.
Just drop a list of SKU's in CSV format, load it into the script, and hit process.
regards,

Simon Weller
simon@nzservers.com
NZservers.com
This code is provided without warranty and does not assert to work for your given situation. 

regards,

Chris Carroll
ctcarroll@mindspring.com
Do not edit below this line!
*/
error_reporting(0);
header("Last-Modified: ". gmdate("D, d M Y H:i:s",time()) . " GMT");
require('./includes/csvconfig.php');

$prodtable = $custID.prod;
$langtable = $custID.lang;
$nprodtable = $custID.nprod;
$webtable = $custID.web;
$prodcattable = $custID.prodcat;
$prodlangtable = $custID.prodlang;
$cattable = $custID.cat;

require('../admin.php');
$flags_file = $droot.'/flags.php';
require($flags_file);

?>
<html>
<head>
<title>CSV-Parse</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<img src="images/cvsparselogo.gif" width="242" height="52">
<p>&nbsp;</p>
<?
 
    
          $row=1;
              $fp = fopen("$userfile","r");
              while ($data = fgetcsv($fp,1000))     {
              $num = count($data);
              $row++;
             

require('./includes/csvfields.php');

// cover old sku value for update
$oldsku = $sku;


$fcl = new FC_SQL;
$fcp = new FC_SQL;
$fcu = new FC_SQL;
$fcw = new FC_SQL;
$fcc = new FC_SQL;

if(!$zoneid||!$langid){?>
  A zone or language ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.
    <?php exit;
}

$price=ereg_replace(",","",$price);   /* remove commas from price */
$price=ereg_replace("[\$]{1,}","",$price);  /* remove $ from price */
$rtlprice=ereg_replace(",","",$rtlprice);   /* remove commas from price */
$rtlprice=ereg_replace("[\$]{1,}","",$rtlprice);  /* remove $ from price */
$saleprice=ereg_replace(",","",$saleprice);   /* remove commas from price */
$saleprice=ereg_replace("[\$]{1,}","",$saleprice);  /* remove $ from price */

$price=(double)$price;
$rtlprice=(double)$rtlprice;
$saleprice=(double)$saleprice;
$setup=(double)$setup;

$ordmax=(int)$ordmax;
$invqty=(int)$invqty;
$useinv=(int)$useinv;

if($ssm && $ssd && $ssy){
	$ssdate=mktime(0,0,0,$ssm,$ssd,$ssy);
}else{
	$ssdate=0;
}

if($sem && $sed && $sey){
	$sedate=mktime(23,59,59,$sem,$sed,$sey);
}else{
	$sedate=0;
}

if($psm && $psd && $psy){
	$psdate=mktime(0,0,0,$psm,$psd,$psy);
}else{
	$psdate=0;
}

if($pem && $ped && $pey){
	$pedate=mktime(23,59,59,$pem,$ped,$pey);
}else{
	$pedate=0;
}

// build up flag1
$flag1=0;
if($noship){
  $flag1 |= (int)$flag_noship;
}
if($notax){
  $flag1 |= (int)$flag_notax;
}


require('./includes/csvprod.php');

if($act=="insert") {

  /* get the count of language table entries */
  $fcl->query("select count(*) as cnt from $langtable where langzid=$zoneid");
  $fcl->next_record();
  $lt=(int)$fcl->f("cnt");
  $fcl->free_result();
  $fcp->query("select prodsku from $prodtable ".
		"where prodsku='$sku' and prodzid=$zoneid"); 
  if( $fcp->next_record() ){
	$fcu->query("update $prodtable ".
	"set prodsku='$sku',".
	"$produpdstate,".
	"produseinvq=$useinv,prodflag1=$flag1,prodseq='$prodseq',".
	"prodsalebeg=$ssdate, prodsaleend=$sedate,prodstart=$psdate,prodstop=$pedate ".
	"where prodsku='$oldsku' and prodzid=$zoneid"); 
  }else{
  	$fcu->query("insert into $prodtable ".
	"(prodzid,prodsku,".
	"$prodfieldstate,".
	"prodsalebeg,prodsaleend, prodstart,prodstop,".
	"produseinvq,prodseq,prodflag1)" .
	" values ($zoneid,'$sku',".
	"$prodvaristate,".
	"$ssdate,$sedate,$psdate,$pedate,$useinv,'$prodseq',$flag1)"); 
  }
  $fcp->free_result();

  $fcp->query("select nprodsku from $nprodtable ".
    "where nprodsku='$sku' and nzid=$zoneid"); 
  if( $fcp->next_record() ){?>
   Product already listed in New Products<br>
  <?php }else{
    $fcw->query("select webdaysinnew from $webtable ".
        "where webzid=$zoneid and weblid=$langid"); 
    if( !$fcw->next_record() ){?>
Internal error: $webtable table not found for zone:<?php echo $zoneid?>
and language:<?php echo $langid?>.  Contact FishNet support
at <a href="mailto:support@fni.com">support@fni.com</a>.<p>
<?php } else {
	 $now=time();
	 $days=$fcw->f("webdaysinnew");
	 $nend=$now+(86400*$days);
	 $fcp->query("insert into $nprodtable ".
		"(nprodsku,nstart,nend,nzid) values ('$sku',$now,$nend,$zoneid)");
	}
 	$fcw->free_result();
  }
  $fcp->free_result();

} elseif($act=="update") {

 /* update just one product */
 $lt=1;

 $fcp->query("update $prodtable ".
 	"set prodsku='$sku',".
	"$produpdstate,".
	"produseinvq=$useinv,prodflag1=$flag1,prodseq='$prodseq',".
	"prodsalebeg=$ssdate, prodsaleend=$sedate,prodstart=$psdate,prodstop=$pedate ".
	"where prodsku='$oldsku' and prodzid=$zoneid"); 

 /* Modify this SKU in the product/category database */

 if ( $sku != $oldsku ) {
	$res=$fcp->query("update $prodcattable ".
		"set pcatsku='$sku' where pcatsku='$oldsku' and pcatzid=$zoneid"); 
 }

} elseif($act=="delete") {

 $fcp->query("delete from $prodtable where prodsku='$sku'"); 
 $fcp->query("delete from $prodlangtable where prodlsku='$sku'"); 

 /* Delete this product from product/category and new items database */

 $fcp->query("delete from $prodcattable ".
 	"where pcatsku='$sku' and pcatzid=$zoneid"); 

 $fcp->query("delete from $nprodtable where nprodsku='$sku'"); 

 /* categories can contain SKUs; see if this is referenced */

 $fcc->query("select catval from $cattable where catsku='$sku'"); 
 while( $fcc->next_record() ){
  $fcu->query("update $cattable set catsku='' where catval=$val"); 
 }
 $fcc->free_result();

}

/* PRODUCT LANGUAGE TABLES */
$i=0;
while($i<$lt){
	$prodname="prodname$i";
	$prodname=${$prodname};	
	$tdescr  ="descr$i";
	$tdescr  ="${$tdescr}";
	$tsdescr  ="sdescr$i";
	$tsdescr  ="${$tsdescr}";
	$tkeyword="keyword$i";
	$tkeyword="${$tkeyword}";
	$toffer="prodoffer$i";
	$toffer="${$toffer}";
	$tpic    ="pic$i";
	$tpic    =${$tpic};
	$ttpic   ="tpic$i";
	$ttpic   =${$ttpic};
	$tbanr   ="banr$i";
	$tbanr   =${$tbanr};
	$taudio  ="audio$i";
	$taudio  =${$taudio};
	$tvideo  ="video$i";
	$tvideo  =${$tvideo};
	$tsplash ="splash$i";
	$tsplash =${$tsplash};
	$dload   ="proddload$i";
	$dload   =${$dload};
	$prodlid ="prodlid$i";
	$prodauth="prodauth$i";
	$prodauth=${$prodauth};
	$prodleadtime="prodleadtime$i";
	$prodleadtime=${$prodleadtime};


	// $tdescr=ereg_replace("\$","\$",$tdescr);
	// $tsdescr=ereg_replace("\$","\$",$tsdescr);
	$tdescr=addslashes($tdescr);
	$tsdescr=addslashes($tsdescr);
	$tkeyword=ereg_replace("\r","",$tkeyword);
	$tkeyword=ereg_replace("\n","",$tkeyword);
	$tkeyword=addslashes($tkeyword);


	if($tpic!=""){
	// below variable used to make path correct for getimagesize function while still
	// using the function imagepath($img) located in the included file admin.php 
	$imgpath=ereg_replace("\.\./","../.",imagepath($tpic).$tpic); 
	
	$imgs=getimagesize($imgpath);
	 
     if($imgs[0]==0){
      echo "<b>The image file $tpic was not found.</b><p>\n";
	  $tpic="";
	  $tpicw=0;
	  $tpich=0;
     }else{
	  $tpicw=$imgs[0];
	  $tpich=$imgs[1];
     }
	}else{
	 $tpic="";
	 $tpicw=0;
	 $tpich=0;
	}
	if($ttpic!=""){
	  // below variable used to make path correct for getimagesize function while still
	// using the function imagepath($img) located in the included file admin.php 
	$imgpath=ereg_replace("\.\./","../.",imagepath($ttpic).$ttpic); 
	
	$imgs=getimagesize($imgpath);
	 
     if($imgs[0]==0){
      echo "<b>The image file $ttpic was not found.</b><p>\n";
	  $ttpic="";
	  $ttpicw=0;
	  $ttpich=0;
     }else{
	  $ttpicw=$imgs[0];
	  $ttpich=$imgs[1];
     }
	}else{
	 $ttpic="";
	 $ttpicw=0;
	 $ttpich=0;
	}
	if($tbanr!=""){
	 // below variable used to make path correct for getimagesize function while still
	// using the function imagepath($img) located in the included file admin.php 
	$imgpath=ereg_replace("\.\./","../.",imagepath($tbanr).$tbanr); 
	
	$imgs=getimagesize($imgpath);
	
     if($imgs[0]==0){
      echo "<b>The image file $tbanr was not found.</b><p>\n";
	  $tbanr="";
	  $tbanrw=0;
	  $tbanrh=0;
     }else{
	  $tbanrw=$imgs[0];
	  $tbanrh=$imgs[1];
     }
	}else{
	 $tbanr="";
	 $tbanrw=0;
	 $tbanrh=0;
	}
	
		require('./includes/csvprodlang.php');

	$fcp->query("select prodlsku from $prodlangtable where prodlsku='$sku'".
		" and prodlzid=$zoneid and prodlid=$langid");
	$pirc=(int)$fcp->next_record();
	$fcp->free_result();
	if($act=="insert" && $pirc==0){

		$tprodlid=${$prodlid};
		$fcp->query("insert into $prodlangtable ".
		"(prodlid,prodlzid,prodlsku,".
		"$prodlangfieldstate)".
		"values ($tprodlid,$zoneid,'$sku',".
		"$prodlangvaristate)");  

	} elseif($act=="update" || ($act=="insert" && $pirc>0) ){

		$fcp->query("update $prodlangtable ".
		"set prodlsku='$sku', ".
		"$prodlangupdstate ".
		"where prodlsku='$oldsku' and prodlzid=$zoneid and prodlid=$langid"); 

	}

	if(strlen($pc1)==0){$pc1=0;}
	if(strlen($pc2)==0){$pc2=0;}
	if(strlen($pc3)==0){$pc3=0;}

	$k=0;
	while($k<$catselloop){
	 $tpc="pc$k$i";
	 $pc=${$tpc};
	 $tpsq="psq$k$i";
	 $psq=(int)${$tpsq};
	 $catscata=0;
	 if ($pc) {
      if(strchr($pc,":")){
       $cats=explode(":",$pc);
	   $catscata=1;
      }
	  if($catscata){
	   // subcats
	   if($act=="update"){
	    $fcp->query("select pcatsku from $prodcattable where pcatsku='$oldsku' ".
		"and pcatzid=$zoneid and pcatval=".$cats[0]." and pscatval=$cats[1]"); 
	   }else{
	    $fcp->query("select pcatsku from $prodcattable where pcatsku='$sku' ".
		"and pcatzid=$zoneid and pcatval=".$cats[0]." and pscatval=$cats[1]"); 
	   }
	  }else{
	   // no subcats
	   if($act=="update"){
	    $fcp->query("select pcatsku from $prodcattable ".
	     "where pcatsku='$oldsku' and pcatzid=$zoneid and pcatval=$pc"); 
	   }else{
	    $fcp->query("select pcatsku from $prodcattable ".
	     "where pcatsku='$sku' and pcatzid=$zoneid and pcatval=$pc"); 
	   }
	  }
	  if( $fcp->next_record() ){
?>
Product/category association for <?php echo $sku?>/<?php echo $pc?> already exists.<br>
<?php 
	   $fcp->free_result();
	  }else{
	   if($catscata){
	    if($act=="insert"){
	     $fcp->query(
		 "insert into $prodcattable (pcatval,pcatsku,pcatzid,pscatval,pcatseq) ".
		 "values (" .$cats[0]. ",'$sku',$zoneid," .$cats[1]. ",$psq)");
	    }
	    elseif($act=="update"){
	     $fcp->query(
		 "update $prodcattable set pcatval=" .$cats[0]. ",pcatseq=" .$cats[1].
		 ", pcatsku='$sku', pcatzid=$zoneid, pcatseq=$psq ".
	     "where pcatsku='$oldsku' and pcatzid=$zoneid");
	    }
	   }else{
	    if($act=="insert"){
	     $fcp->query("insert into $prodcattable (pcatval,pcatsku,".
	      "pcatzid,pscatval,pcatseq) values ($pc,'$sku',$zoneid,0,$psq)");
	    }
	    elseif($act=="update"){
	     $fcp->query("update $prodcattable set pcatval=$pc,pcatsku='$sku',".
		  "pcatzid=$zoneid,pcatseq=$psq ".
	      "where pcatsku='$oldsku' and pcatzid=$zoneid");
	    }
	   }
	  }
	 }
	 $k++;
	}
	$i++;
}

$fcp->commit();

if ($act=="delete") {
echo "$sku deleted successfully <br>\n";
} else if ($act=="insert") {
echo "$sku added successfully <br>\n";
} else {
echo "$sku updated successfully <br>\n";
}


 }
fclose($fp);

	/* 
	** open log file for writing 
	*/
	$myFile = fopen("./includes/csvparse.log","a");
	
	/*
	** make sure the open was successful
	*/
	if(!($myFile))
	{
		print("Error: ");
		print("'csvparse.log' could not be created\n");
		exit;
	}

	 
	// write process information to the filelog 
	$currentdate = date("l, F j, Y");
	fputs($myFile, "$userfile_name processed via add or update on $currentdate, it was $userfile_size bytes in size\n");
	fclose($myFile); // close the file

if ($act=="delete") {
echo "<b>Product Deletions now complete, thank you for using CSVParse</b> <br>\n";
} else if ($act=="insert") {
echo "<b>Product Additions now complete, thank you for using CSVParse</b> <br>\n";
} else {
echo "<b>Product Updates now complete, thank you for using CSVParse</b> <br>\n";
}
?>
<p><a href="index.php">Back to the menu</a></p>
</body>
</html>



             
