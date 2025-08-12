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
*/

header("Expires: 0");
header("Pragma: no-cache");
header("Cache-control: No-Cache");

error_reporting(0);

require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape
$CookieCustID = getcookie("Cookie${instid}CustID");
$CookieCart   = getcookie("Cookie${instid}Cart");
$cartid=getparam('cartid');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
// ==========  end of variable loading  ==========

require('./public.php');
require('./cartid.php');
require('./languages.php');
require('./flags.php');

// front page promotion category
$fp_cat = 1;

$fcz = new FC_SQL;
$fcz->query("select zflag1,usescat,zonecurrsym from ZONETABLE where zoneid=$zid"); 
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $zscat=(int)$fcz->f("usescat");
 $zflag1=(int)$fcz->f("zflag1");
}else{
 $csym='';
 $zscat=0;
 $zflag1=0;
}

if( $zflag1 & $flag_zonedebug ){
	error_reporting(15);
}else{
	error_reporting(0);
}

$fcw=new FC_SQL;
$fcl=new FC_SQL;
$fcg=new FC_SQL;
$fcc=new FC_SQL;
$fcal=new FC_SQL;
$fcn=new FC_SQL;
$fcs=new FC_SQL;
$fcv=new FC_SQL;
$fcsc=new FC_SQL;

$showscat=(int)$showscat;

if( $zflag1 & $flag_zonepwcatalog ){
	// password controlled access
	include('./pw.php');
}

// get the Web table
$fcw->query("select * from WEBTABLE where webzid=$zid and weblid=$lid"); 
$fcw->next_record();

// get the language templates
$fcl->query("select langtmpl,langshow,langstmpl from LANGTABLE ".
	"where langid=$lid");
$fcl->next_record();
if($zscat && $showscat){
	$tmpl=stripslashes($fcl->f("langstmpl"));
}else{
	$tmpl=stripslashes($fcl->f("langtmpl"));
}
$show=$fcl->f("langshow");
$fcl->free_result();

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE?>

<html><head>
<link rel=stylesheet ID href="style.css" type="text/css" />
<title>COMPANY <?php echo fc_text("titletag"); ?></title></head>

<body<?php 
if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php }
if($fcw->f("webtext")){?> text="<?php echo $fcw->f("webtext")?>"<?php }
if($fcw->f("weblink")){?> link="<?php echo $fcw->f("weblink")?>"<?php }
if($fcw->f("webvlink")){?> vlink="<?php echo $fcw->f("webvlink")?>"<?php }
if($fcw->f("webalink")){?> alink="<?php echo $fcw->f("webalink")?>"<?php }
if($fcw->f("webbg")){?> bgcolor="<?php echo $fcw->f("webbg")?>"<?php }?>
 marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<table border="0" cellpadding="0" cellspacing="0" width="600">

<!-- FIRST COLUMN -->
<tr><td align="left" valign="top" width="10">
<img src="clearpixel.gif" width="10" height="1" /><br />
<!-- SECOND COLUMN -->
</td><td align="left" valign="top" width="160">

<img src="clearpixel.gif" height="10" /><br />

<?php  // if multiple catalogs to list
$fcz->query(
 "select count(*) as cnt from ZONETABLE where zoneact=1");
$fcz->next_record();
$cnt=(int)$fcz->f("cnt");
$fcz->free_result();
if( $cnt > 1 ){
$fcz->query(
 "select zoneid,zonedescr from ZONETABLE where zoneact=1 order by zoneid");
?>
<table border="0" cellpadding="0">
<tr><td align="center" valign="top">
<form name="zoneform" method="post" action="index.php">
<select name="zid" size="<?php echo $cnt; ?>" onChange="submit(); return false;">
<?php 
while( $fcz->next_record() ){
 $ztg=(int)$fcz->f("zoneid");
 if($ztg==$zid){?>
<option value="<?php echo $ztg?>" selected>
<?php }else{?>
<option value="<?php echo $ztg?>">
<?php }
 echo stripslashes($fcz->f("zonedescr"))."\n";?>
</option>
<?php
}
$fcz->free_result();
?>
</select><br />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<noscript>
<input type="submit" value="<?php echo fc_text("choosezone"); ?>" />
</noscript>
</form>
</td></tr></table>
<?php } ?>

<?php  // if multiple language profiles to list
$fcg->query(
 "select count(*) as cnt from LANGTABLE where langzid=$zid");
$fcg->next_record();
$cnt=(int)$fcg->f("cnt");
$fcg->free_result();
if( $cnt > 1 ){
$fcg->query(
 "select langid,langdescr from LANGTABLE where langzid=$zid order by langid"); 
?>
<table border="0" cellpadding="0">
<tr><td align="center" valign="top">
<form name="langform" method="post" action="index.php">
<select name="lid" size="<?php echo $cnt; ?>" onChange="submit(); return false;">
<?php 
while( $fcg->next_record() ){
 $ltg=(int)$fcg->f("langid");
 if($ltg==$lid){?>
<option value="<?php echo $ltg?>" selected>
<?php }else{?>
<option value="<?php echo $ltg?>">
<?php }
 echo stripslashes($fcg->f("langdescr"))."\n";?>
</option>
<?php
}
$fcg->free_result();
?>
</select><br />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="submit" value="<?php echo fc_text("chooselang"); ?>" />
</form>
</td></tr></table>
<?php } ?>

<form name="homecat" method="get" action="display.php"
 onSubmit="
  if( document.homecat.cat.options.selectedIndex == 0 &&
      document.homecat.key1.value == '' ){
    alert('<?php echo fc_text('jspickone'); ?>');
    return false;
  }else{
    return true;
  }
 ">
<select name="cat" size="1" onChange="submit(); return false;">
<option value=""><?php echo fc_text('selectcat') ?></option>
<?php  // display a select list of product categories
// under='0' tells mysql to return the top level cats only
$fcc->query("select catval,catdescr,catscata from CATTABLE where catact=1 and catlid=$lid and catzid=$zid and catunder=0 order by catdescr"); 
while($fcc->next_record()){
?><option value="<?php
  echo $fcc->f("catval") .'">'. stripslashes($fcc->f("catdescr")) . "\n";?>
</option>
<?php
 }
$fcc->free_result();
?>
</select><br />

<input type="hidden" name="olimit" value="0" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="submit" value="<?php echo fc_text("choosecat"); ?>" />
<p>
<?php echo fc_text("choosekey"); ?><br />
<input name="key1" size="14">
</p>
<p>
<?php //auxilliary links. jheg
$fcal->query("select title, url from AUXLINKTABLE where loc=1 order by seq");
while ($fcal->next_record()){
echo '<a href="'.stripslashes($fcal->f("url")).'">'.stripslashes($fcal->f("title"))."</a><br />";
}
$fcal->free_result();
?>

<?php  // show the new items button
$fcn->query("select count(*) as cnt from NPRODTABLE where nzid=0 or nzid=$zid");
$fcn->next_record();
if($fcn->f('cnt')){?>
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&nlst=y&olimit=0&cat=&key1=&psku=">
<img src="<?php echo $fcw->f("webnewlogo")?>" width="<?php echo $fcw->f("webnewlogow")?>" height="<?php echo $fcw->f("webnewlogoh")?>" alt="<?php echo fc_text("newitems"); ?>" border="0"></a><br />
<?php } $fcn->free_result(); ?>

<?php  // show the view cart button ?>
<a href="<?php echo $show?>?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>">
<img src="<?php echo $fcw->f("webviewlogo")?>" width="<?php echo $fcw->f("webviewlogow")?>" height="<?php echo $fcw->f("webviewlogoh")?>"
 alt="<?php echo fc_text("vieworder"); ?>" border="0"></a><br />

<?php /* } */?>

</p>
</form>

<?php include ('preview.php');?>
<!-- THIRD COLUMN -->
</td><td valign="top" width="10">
<img src="clearpixel.gif" width="10" height="1"><br />
<!-- FOURTH COLUMN -->
</td><td valign="top" cellspacing="0" cellpadding="0" width="500">
<table cellpadding="0" width="500" border="0">
<!-- BODY TEXT GOES HERE -->
<tr><td align="left" colspan="3">
<p>
<?php echo fc_text("welcome"); ?>
</p>
</td></tr>
<?php
$now = time();
$fcp = new FC_SQL;
$fco = new FC_SQL;


// set the product start/stop date search parameters
if( $zflag1 & $flag_zoneproddate ){
	$pj="((prodstart=0 or (prodstart <> 0 and $now > prodstart)) and ".
		" (prodstop =0 or (prodstop  <> 0 and $now < prodstop ))) and ";
}else{
	$pj='';
}

// look for Front Page Promotion products

$pj.="catval=$fp_cat and catval=pcatval and pcatsku=prodsku and ". 
"pcatsku=prodlsku and catlid=$lid and prodlid=$lid and prodzid=$zid and prodlzid=$zid";

// same for all queries
$fds="proddescr,prodpic,prodpicw,prodpich,prodtpic,prodtpicw,prodtpich,".
 "prodsku,prodprice,prodinvqty,prodaudio,prodvideo,prodsalebeg,prodsaleend,".
 "prodsplash,prodsaleprice,produseinvq,prodseq,prodoffer,prodsdescr,proddload,".
 "prodsetup,prodflag1";

$tbs="CATTABLE,PRODCATTABLE,PRODTABLE,PRODLANG";
$fcp->query("select count(*) as cnt from $tbs where $pj");
$fcp->next_record();
$total=(int)$fcp->f("cnt");
$fcp->free_result();
$count=$total;
//echo "<b>count: $count</b><br>\n";

$fcp->query("select $fds from $tbs where $pj order by prodseq");

// main product display table; only show if there are products

// display the products
$j=0;
while( $fcp->next_record() ){
 $flag1=(int)$fcp->f('prodflag1');
?>

<tr><td align="left" valign="top" colspan="3">

<?php 
if($fcp->f("prodpic")){ // show the product picture (if defined)
/*
 width="<?php echo $fcp->f("prodpicw")?>"
 height="<?php echo $fcp->f("prodpich")?>"
*/
?>

<img src="<?php echo $fcp->f("prodtpic")?>"
 width="<?php echo $fcp->f("prodtpicw")?>"
 height="<?php echo $fcp->f("prodtpich")?>"
 alt="<?php echo stripslashes($fcp->f("prodsdescr"))?>" align="left" />

<?php } // end of the product picture ?>

 <b><?php echo stripslashes($fcp->f("prodsdescr"))?>:</b>
 <?php echo stripslashes($fcp->f("proddescr"))?><br />

 </td></tr>
 <tr><td align="left" valign="bottom" colspan="1">

 <?php if( $fcp->f("prodaudio") ){?>
 <a href="<?php echo $fcp->f("prodaudio")?>"><i><?php echo fc_text("audiosample"); ?></i></a><br />
 <?php }?>

 </td><td align="center" valign="bottom" colspan="1">

 <?php if( $fcp->f("prodvideo") ){?>
 <a href="<?php echo $fcp->f("prodvideo")?>"><i><?php echo fc_text("videosample"); ?></i></a><br />
 <?php }?>

 </td><td align="right" valign="bottom">
 </td></tr>
<tr><td align="left" valign="top" colspan="3">
<?php $prodsku=$fcp->f("prodsku"); ?>

<form method="post" action="showcart.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&product=<?php echo $fcp->f("prodsku")?>&cat=<?php echo $cat?>&olimit=<?php echo $olimit?>">


<?php // show the product options; see showcart for a detailed description

 $poptqty=0;
 $poptgrp=0;	// nmb
 $poptflag1=0;	// nmb
 $poptogrp=-1;		// -1 is initial value
 $poptgrpcnt=0; 	// # of options per group
 $poptgrplst='';	// : separated list of all represented groups
 
 $fco->query("select poptid,poptname,poptsdescr,poptsetup,poptprice,poptgrp,".
 	"poptflag1 from PRODOPT where poptsku='$prodsku' order by poptgrp,poptseq");
 if( $fco->next_record() ){
 $i=0;
 do{

  $poptid =(int)$fco->f("poptid");
  $poptgrp=(int)$fco->f("poptgrp");
  $poptflag1=(int)$fco->f("poptflag1");
  $poptsetup=(double)$fco->f("poptsetup");
  $poptprice=(double)$fco->f("poptprice");
  $poptname=stripslashes($fco->f("poptname"));
  $poptsdescr=stripslashes($fco->f("poptsdescr"));

  if( $poptogrp != -1 && $poptogrp != $poptgrp ){	// group rollover check
	echo '</select>';
    if( $poptoflg & $flag_poptgrpqty ){	// qty is required
     echo '&nbsp;&nbsp;'.fc_text("qty").
      '<input name="'.$prodsku.'_'.$poptogrp.'_qty" size="3" />'."\n";
    }
    if( $poptoflg & $flag_poptgrpreq ){	// option group is required
      echo '<input type="hidden" name="'.$prodsku.'_'.$poptogrp.'_req" value="1" />'.fc_text('reqflag')."<br />\n";
    }else{
      echo '<input type="hidden" name="'.$prodsku.'_'.$poptogrp.'_req" value="0" /><br />'."\n";
	}
	echo "<br />\n<select name=\"${prodsku}_${poptgrp}_popt[]\">\n".
		 '<option value="">'.fc_text('emptyopt')."</option>\n";

	if( $poptogrp >= 0 ){
      $poptgrplst .= "$poptogrp:";
	}
    $poptgrpcnt=0;		// zero the counter
  }elseif( !$i ){
	echo "<select name=\"${prodsku}_${poptgrp}_popt[]\">\n".
		 '<option value="">'.fc_text('emptyopt')."</option>\n";
  }

  if( $poptflag1 & $flag_poptgrpexc ){
   $popttype = 'radio';
  }else{
   $popttype = 'checkbox';
  }

  // compose composite sku
  if( $poptflag1 & $flag_poptskupre ){
    $csku=stripslashes($fco->f("poptskumod")) . $csku;
  }elseif( $poptflag1 & $flag_poptskusuf ){
    $csku=$csku . stripslashes($fco->f("poptskumod"));
  }elseif( $poptflag1 & $flag_poptskumod ){
    $csku=ereg_replace(stripslashes($fco->f("poptskusub")),stripslashes($fco->f("poptskumod")),$csku);
  }elseif( $poptflag1 & $flag_poptskusub ){
    $csku=stripslashes($fco->f("poptskumod"));
  }             

  echo "<option value=\"${poptid}\"> $poptname $poptsdescr\n";

  if( $poptsetup ){
   echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("setup").
		sprintf("%s%.2f\n",$csym,$poptsetup);
  }
  
  echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("price");
  if( ($poptflag1 & $flag_poptprcrel) && $poptprice ){
   $relflg='+';
  }else{
   $relflg='';
  }
  if( $poptprice ){
	echo ' '.$relflg.sprintf("%s%.2f\n",$csym,$poptprice);
  }else{
	echo ' '.$relflg.fc_text("nocharge")."\n";
  }
  echo "</option>\n";
  
  $poptgrpcnt++;		// incr count of options per group
  $poptogrp=$poptgrp;	// keep the current group ID
  $poptoflg=$poptflag1;	// keep the current group flag set
  
  $i++;
 } while( $fco->next_record() );
 $fco->free_result();

 // nmb
 if( $i ){
  echo '</select>';
 }

 // always do this stuff for last option group rollover check
 if( $poptflag1 & $flag_poptgrpqty ){	// qty is required
   echo '&nbsp;&nbsp;'.fc_text("qty").
    '<input name="'.$prodsku.'_'.$poptgrp.'_qty" size="3" />'."\n";
 }
 if( $poptflag1 & $flag_poptgrpreq ){	// option group is required
 	echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_req" value="1" />'.fc_text('reqflag')."<br />\n";
 }else{
    echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_req" value="0" /><br />'."\n";
 }

 if( $poptgrp >= 0 ){
   $poptgrplst .= "$poptgrp";
 }
 echo '<input type="hidden" name="'.$prodsku.'_grplst" value="'.
 		$poptgrplst.'" />'."\n";
 } // if product options
 ?>
 
</td></tr>
<tr><td align="left" valign="bottom" colspan="1">
<i><?php echo fc_text("sku"); ?> <?php $prodsku=$fcp->f("prodsku"); echo $prodsku; ?></i>
</td><td align="left" valign="bottom" colspan="1">

	<?php  // show the product price
	$setup=(double)$fcp->f("prodsetup");
	if( $setup ){
	  echo sprintf("%s %s%8.2f ", fc_text("setup"),$csym,$setup);
	  }
	  $prc='';
	  if($fcp->f("prodprice")==0){
    // free, show alternative text
    if(!empty($cat) && !$cscat){ $prc=stripslashes($fcs->f("catfree")); }
	 if(!empty($cat) &&  $cscat){ $prc=stripslashes($fcs->f("scatfree")); }
	  if( empty($prc)){ $prc=stripslashes($fcw->f("webfree")); }
  }else{ // not free, check for sale price
   if( $fcp->f("prodsalebeg")<$now && $now<$fcp->f("prodsaleend") ){
    // on sale
    $prc=sprintf(
    "<b>%s %s%8.2f</b>", fc_text("onsale"),$csym,$fcp->f("prodsaleprice"));
   }else{
    $prc=sprintf("%s %s%8.2f",fc_text("price"),$csym,$fcp->f("prodprice"));
   }
  }
  echo $prc;
  if( $flag1 & $flag_persvc ){
    echo ' <i>'.fc_text('periodic').'</i>';
  }
?>

</td><td align="right" valign="bottom" colspan="1">
<?php

// SHOW THE ADD TO ORDER BUTTON
// with product options, it is no longer feasible to show the qty
// on order, as we don't know which options have been chosen
if( $fcw->f("webflags1") & $flag_webshowqty ) {
  $qty="1";
}else{
  $qty="";
}
?>

<p>
<right>
<input type="text" size="3" name="quantity" value="<?php echo $qty?>" />
<input type="submit" value="<?php echo fc_text('shortadd'); ?>" />
</right>
</p>
</form>

</td></tr>
<tr><td align="left" valign="top" colspan="3">
<hr />
<?php 
} // end of product display loop
?>
</td></tr>
</table>

<?php
// if count
?>

</td></tr></table>
<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php  /* ?>
<table border="0" cellpadding="3" cellspacing="0">
<tr><td valign="top">

<i><?php echo fc_text("contactinfo"); ?></i><br />
<?php  // display the vendor contact information
$fcv->query("select * from VENDORTABLE where vendzid=$zid"); 
$fcv->next_record();

if($fcv->f("vendname")){?><?php echo stripslashes($fcv->f("vendname"))?><br /><?php }
if($fcv->f("vendaddr1")){?><?php echo stripslashes($fcv->f("vendaddr1"))?><br /><?php }
if($fcv->f("vendaddr2")){?><?php echo stripslashes($fcv->f("vendaddr2"))?><br /><?php }?>
<?php echo stripslashes($fcv->f("vendstate"))?> <?php echo stripslashes($fcv->f("vendzip"))?>  <?php echo stripslashes($fcv->f("vendnatl"))?><br />
<?php if($fcv->f("vendphone")){?><?php echo stripslashes($fcv->f("vendphone"))?><br /><?php }
if($fcv->f("vendfax")){?><?php echo stripslashes($fcv->f("vendfax"))?><br /><?php }
if($fcv->f("vendemail")){?><a href="mailto:<?php stripslashes(echo $fcv->f("vendemail"))?>"><?php echo stripslashes($fcv->f("vendemail"))?></a><br />
<?php }?>

</td><td valign="top">

<i><?php echo fc_text("supportinfo"); ?></i><br />
<?php  // display the vendor service information
if($fcv->f("vsvcname")){?><?php echo stripslashes($fcv->f("vsvcname"))?><br /><?php }
if($fcv->f("vsvcaddr1")){?><?php echo stripslashes($fcv->f("vsvcaddr1"))?><br /><?php }
if($fcv->f("vsvcaddr1")){?><?php echo stripslashes($fcv->f("vsvcaddr1"))?><br /><?php }
if($fcv->f("vsvccity")){?><?php echo stripslashes($fcv->f("vsvccity"))?>, <?php echo stripslashes($fcv->f("vsvcstate"))?> <?php echo stripslashes($fcv->f("vsvczip"))?>  <?php echo stripslashes($fcv->f("vsvcnatl"))?><br /><?php }
if($fcv->f("vsvcphone")){?><?php echo stripslashes($fcv->f("vsvcphone"))?><br /><?php }
if($fcv->f("vsvcfax")){?><?php echo stripslashes($fcv->f("vsvcfax"))?><br /><?php }
if($fcv->f("vsvcemail")){?><a href="mailto:<?php echo stripslashes($fcv->f("vsvcemail"))?>"><?php echo stripslashes($fcv->f("vsvcemail"))?></a><br />
<?php }?>

</td></tr></table>
<?php  */ ?>

</body></html>
