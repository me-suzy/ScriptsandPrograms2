<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Domain Seller Pro                                 //
// Release Version      : 1.5.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////

include_once("config_inc.php");
$displayrows = 10;

myconnect();

$ncat=mysql_result(mysql_query("SELECT COUNT(*) FROM dsp_cats"),0);
$cats=mysql_query("SELECT * FROM dsp_cats ORDER BY category");
$ncat3= (int) $ncat/3;

for($i=0;$i<$ncat;$i++) {   
	$cat=mysql_fetch_object($cats); 
	$re="(^".$cat->ID." )|( ".$cat->ID." )|( ".$cat->ID."$)|(^".$cat->ID."$)";
	$nnn=mysql_result(mysql_query("SELECT COUNT(*) FROM dsp_domains WHERE category REGEXP '$re'"),0);
	$selected = ""; $sel=0;
	if ($cc == $cat->category) { $selected = "selected"; $sel = 1; }
	$cats1 .="<option value=\"$cat->ID\" $selected>$cat->category ($nnn)</option>
	";
}
	if ($sel==0) $cats1 .= "<option value=\"1\" selected>[Choose a category]</selected>";

preg_match("/[^\.\/]+\.[^\.\/]+$/",$HTTP_SERVER_VARS["HTTP_HOST"],$host); 

if (
(is_array($homedomain) && !@in_array($host[0],$homedomain) || !isset($homedomain))&& 
$parkeddomains==1 && 
strlen($HTTP_SERVER_VARS['QUERY_STRING']) < 2 
&& !isset($a)) $lookupdom =$host[0];

if (eregi(".", $HTTP_SERVER_VARS['QUERY_STRING']) && !isset($a)) $lookupdom = $HTTP_SERVER_VARS['QUERY_STRING'];

if (isset($lookupdom)) { 
$result=mysql_query("select id FROM dsp_domains where name = '".addslashes(strtolower($lookupdom))."' LIMIT 1");
	if (mysql_num_rows($result) > 0) { 
		$id=mysql_result($result,0,"id");
		$a='d';
		}
}

mydisconnect();

@include ('header.html');

if($a=="" || $a=="main") 

{

	myconnect();
	$query = mysql_query("SELECT value FROM dsp_options WHERE label='adminemail' ") or die(mysql_error());
	$adminemail=mysql_result($query,0,"value");

	$q=mysql_query("SELECT * FROM dsp_domains WHERE status=0 ORDER BY ID DESC LIMIT 10");

?><br>
<b>Welcome to Domain Seller Pro.</b><br><br>
We offer a variety of high quality domain names available for purchase.  The names we havs selected for sale are primarily those that are generic in nature and marketable.  If you could use assistance selecting a domain name for your next project, please e-mail us anytime at <a href="mailto:<?=$adminemail?>"><?=$adminemail?></a>.  Thank you for visiting our site!<br><br>
<form method="post" action="https://www.registryrocket.com/check.asp" id="form1" name="form1">
				<input type="hidden" name="action" value="check">
				<input type="hidden" name="ec" value="ZPKPEJ0UWYST2YN67Q6GNU505" >
				<input type="hidden" name="referredby" value="<?echo "http://".$HTTP_SERVER_VARS['HTTP_HOST']."/".$REQUEST_URI; ?>" >
				<input type="hidden" name="callbackURL" value="<?echo "http://".$HTTP_SERVER_VARS['HTTP_HOST']."/".$REQUEST_URI; ?>">
				<input type="hidden" name="color" value="tan" >
			Check for an unregistered domain: <input size="18" type="text" maxlength="63" name="sld" id="idsld">&nbsp;.&nbsp;
									<select name="tld" id="idtld">
									<option>com</option>
									<option>net</option>
									<option>org</option>
									<option>cc</option>
									<option>info</option>
									<option>biz</option>
									<option>tv</option>
									<option>ws</option>
									<option>us</option>
									</select> <input type="submit" value="submit">
				</form>

  <table width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
<tr>
	<tr> 
      <td bgcolor=#B7CFFF align=center><font color="#FFFFFF">Recently added domains:</font></td>
    </tr>

    <tr> 
      <td align=center> 
<table  border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#FFFFFF" style="border-collapse: collapse">
<tr bgcolor="#FFFFCC"> 
            <td><b>Domain name</b></td>
            <? if ($listmin==1) { ?>
            <td><b>Minimum Offer</b></td>
            <? } ?>
            <td><b>Target Price</b></td>
            <td><b>Status</b></td>
          </tr>
          <?
	while($d=mysql_fetch_object($q))  {

		$bn="$".number_format($d->buynow,2);	if($d->buynow==0) $bn="Make Offer!";

		$st=$d->status; if ($st == 0) $st = "Available!";
		if($d->minimum==0) $d->minimum=$d->buynow;

?>
          <tr> 
            <td><a href="index.php?a=d&id=<? echo $d->ID;?>"><? echo $d->name; ?></a></td>
            <? if ($listmin) { ?>
            <td align=center>$<? echo number_format($d->minimum,2); ?></td>
            <? } ?>
            <td align=center> 
              <?=$bn?>
            </td>
            <td align=center> 
              <?=$st?>
            </td>
          </tr>
          <?
	}

?>
        </table>
      </td>
    </tr>
	</table>
    <?
	mydisconnect();

}



if($a=="cat") 

{

	myconnect();
	if (!isset($start)) $start =0;

	$catname=mysql_query("SELECT * FROM dsp_cats WHERE ID='$cc'");

	$catname=mysql_fetch_object($catname);

	$re="(^".$cc." )|( ".$cc." )|( ".$cc."$)|(^".$cc."$)";

	$r=mysql_query("SELECT * FROM dsp_domains WHERE (status=0 or status=1 or status=2) AND category REGEXP '$re' ORDER BY buynow DESC");
$numdoms = mysql_num_rows($r);

	$q=mysql_query("SELECT * FROM dsp_domains WHERE (status=0 or status=1 or status=2) AND category REGEXP '$re' ORDER BY buynow DESC LIMIT $start, $displayrows");

$end = $start + $displayrows;
if ($end > $numdoms) { $end = $numdoms; }
$nextr = min($displayrows,$displayrows-($end+$displayrows-$numdoms));

?>
  <table  width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
    <tr> 
      <td align=center bgcolor=#B7CFFF><font color="#0F5FFF" size=4>Category Listing - <b><? echo $catname->category; ?></b></font><br>
	  <font color="#0F5FFF"><?=$numdoms?> domains in this category.<br><br>Displaying <? echo ($start+1)." thru ".($end); ?><br>
	  <? if ($end < $numdoms) echo "<a href=index.php?a=cat&cc=".$cc."&start=".$end.">See next ".$nextr." &raquo;&raquo;</a>"; ?>
	  </font></td></tr>
    <?
if (mysql_num_rows($q) < 1) {
	?>
    <tr> 
      <td align=center>No domains are currently available in this category.</td>
    </tr>
    <? } else {?>
    <tr> 
      <td align=center> 
<table  border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#FFFFFF" style="border-collapse: collapse">
<tr bgcolor="#FFFFCC"> 
            <td><b>Domain name</b></td>
            <? if ($listmin) { ?>
            <td><b>Minimum offer</b></td>
            <? } ?>
            <td><b>Target Price</b></td>
            <td><b>Status</b></td>
          </tr>
          <?
	while($d=mysql_fetch_object($q))  {

		$bn="$".number_format($d->buynow,2);	if($d->buynow==0) $bn="Make Offer!";

		if($d->minimum==0) $d->minimum=$d->buynow;

		$st=$d->status; 
		if ($st == 0) $st = "Available!";
		elseif ($st == 1) $st = "<font color=green>Pending Sale</font>";
		elseif ($st == 2) $st = "<font color=red>Sold</font>";
?>
          <tr> 
            <td><a href="index.php?a=d&id=<? echo $d->ID; ?>"><? echo $d->name;?></a></td>
            <? if ($listmin) { ?>
            <td align=center>$<? echo number_format($d->minimum,2); ?></td>
            <? } ?>
            <td align=center><a href="index.php?a=d&id=<? echo $d->ID; ?>"> 
              <?=$bn?>
              </a>&nbsp;</td>
            <td align=center> 
              <?=$st?>
              &nbsp;</td>
          </tr>
          <?
	}
?>  
</table>
<?
}
?>
      
		</td>
    </tr>
	</table>
<? $result=mysql_query("select * FROM dsp_cats order by category");
$count=1;
$prev['ID']="";
$prev['name']="";
$next['ID']="";
$next['name']="";
$cur="";
unset($cat);

$numcats = mysql_num_rows($result);
while ($ob = mysql_fetch_array($result)) { 
$cat[$count]['ID']=$ob['ID'];
$cat[$count]['name']=$ob['category'];
if ($ob['ID'] == $cc) $cur = $count;
$count++;
}
if ($cur == 1) { $prev['ID']=$cat[$numcats]['ID']; $prev['name']=$cat[$numcats]['name']; $next['ID']=$cat[$cur+1]['ID'];$next['name']=$cat[$cur+1]['name'];}
elseif ($cur == $numcats) { $prev['ID']=$cat[$numcats-1]['ID']; $prev['name']=$cat[$numcats-1]['name']; $next['ID']=$cat[1]['ID'];$next['name']=$cat[1]['name'];}
else { $prev['ID']=$cat[$cur-1]['ID']; $prev['name']=$cat[$cur-1]['name']; $next['ID']=$cat[$cur+1]['ID'];$next['name']=$cat[$cur+1]['name'];}

?><br>
<table border=0 width=100%><tr><td align="left"><a href="index.php?a=cat&cc=<? echo $prev['ID'];?>"><< Prev category (<? echo $prev['name'];?>)</a></td><td align="right"><a href="index.php?a=cat&cc=<? echo $next['ID'];?>">Next category (<? echo $next['name'];?>) &raquo;&raquo;</a></td></tr></table>
    <?
	mydisconnect();

}



if($a=="d")    // domain listing

{

	myconnect();

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$id"));

	$cats=explode(" ",$dom->category);

	for($i=0;$i<count($cats);$i++) $cats[$i] = "(ID=".$cats[$i].")";

	$idorid=implode(" OR ", $cats);

	$cats=mysql_query("SELECT * FROM dsp_cats WHERE $idorid ORDER BY category");	$c="";

	while($cat=mysql_fetch_object($cats)) $c[]= "<a href=index.php?a=cat&cc=$cat->ID>$cat->category</a>";

	$cats=implode(", ",$c);

	
?>

  <table  width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
    <tr> 
      <td align=center valign="middle" bgcolor=#B7CFFF><b><font color="#0F5FFF" size=5><? echo $dom->name;?></font></b></td>
    </tr>
    <tr> 
      <td align=center>
	  <font color="#0000A0" size="4"><? if ($dom->status == 0) { ?>This Domain Is For Sale!<? } ?>
	  <? if ($dom->status == 1) { ?>Pending Sale<? } ?>
	  <? if ($dom->status == 2) { ?>DOMAIN IS SOLD<? } ?></font>
	  <br>
        <br>
        <? if($dom->logourl != "") { ?>
        <img src="<? echo $dom->logourl;?>" border=0><br> <br> 
        <? }
 ?>
        <table  border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#FFFFFF" style="border-collapse: collapse">
          <tr> 
            <td align=right>Uppercase:</td>
            <td><? echo strtoupper($dom->name);?></td>
          </tr>
          <tr bgcolor="#EFEFEF"> 
            <td align=right bgcolor="#EFEFEF">Lowercase:</td>
            <td><? echo strtolower($dom->name);?></td>
          </tr>
          <tr> 
            <td align=right>Length:</td>
            <td> 
              <? $fr = explode(".",$dom->name); echo strlen($fr[0]);?>
              characters</td>
          </tr>
          <tr bgcolor="#EFEFEF"> 
            <td align=right valign=top>Categories:</td>
            <td><? echo $cats;?></td>
          </tr>
          <?	$dom->description=str_replace("\n","<br>",$dom->description);
?>
          <? if (strlen($dom->description) > 1) { ?>
          <tr> 
            <td align=right valign=top>Description:</td>
            <td><? echo ucfirst($dom->description);?>&nbsp;</td>
          </tr>
          <? } if (strlen($dom->keywords) > 1) { ?>
          <tr bgcolor="#EFEFEF"> 
            <td align=right valign=top>Keywords:</td>
            <td><? echo $dom->keywords;?>&nbsp;</td>
          </tr>
          <? } 
		     		  $bn="$".number_format($dom->buynow,2);	


	if($dom->buynow==0) $bn="Make An Offer!";
?>
          <tr> 
            <td align=right valign=top><font color=#000066><b>Buy It Now Price:</b></font></td>
            <td><font color=#000066><b><? echo $bn;?></b></font></td>
          </tr>
          <?   
	if($dom->minimum>0 && $dom->minimum!==$dom->buynow) { ?>
          <tr> 
            <td colspan=2><div align="center"><font color=#CC0000>Offers will 
                be considered</font></div></td>
          </tr>
          <? } ?>
        </table>
        <br>
      </td>
    </tr>
    <tr> 
      <td align=center><br>
	  <? if ($dom->status ==1) {?><b>This domain is pending sale to another buyer.</b><br>You may however submit an offer to be considered<br>in the event the purchase is not completed.<br><br><? } ?>
        <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse" bordercolor="#5D617A">
<tr bgcolor="#FFFFFF"> 
            <?
if($dom->buynow>0 && $dom->status == 0) { ?>
            <td width="200" align=center> <p>Buy Domain Now<br>
                <strong>For Only $<a href="index.php?a=purclog&d=<? echo $dom->ID;?>"><? echo number_format($dom->buynow,2);?></a></strong></p>
              <p><b><a href="index.php?a=purclog&d=<? echo $dom->ID;?>">PURCHASE 
                NOW <br>
                </a></b></p></td>
            <? }
if((($dom->minimum>0 && $dom->minimum!=$dom->buynow)|| $dom->minium==0) && $dom->status != 2) { ?>
            <td width="200" align=center><b>Make An Offer! <br>
              </b><font color="#CC0000">Minimum offer $<? echo number_format($dom->minimum,2);?></font> 
              <form method="get" action="index.php">
                <b> <br>
                <input type="hidden" name="a" value="o">
                <input type="hidden" name="id" value="<? echo $dom->ID;?>">
                Offer Amount $ 
                <input type="text" name="offamount" size="8">
                <br>
                <input type="submit" value="Submit">
                </b> 
              </form>
              <em>*There is a 3% escrow fee added to all transactions (min $15)</em> 
            </td>
            <? } ?>
          </tr>
        </table>
        <? if ($showoffers==1) { 
	$offresult=mysql_query("SELECT * FROM dsp_offers WHERE domain='$dom->ID' order by ID desc");
	?>
        <br> 
        <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse" bordercolor="#5D617A">
          <tr bgcolor="#FFFFCC"> 
            <td colspan="3"> <div align="center"><strong>Recent Offers</strong></div></td>
          </tr>
          <? 	if (mysql_num_rows($offresult) < 1) { ?>
          <tr bgcolor="#FFFFFF"> 
            <td colspan=3> <div align="center"><font color="<?=$fontcolor?>">(none)</font></div></td>
          </tr>
          <? } else { 
		$fo=0; while ($off=mysql_fetch_object($offresult)) {
if ($fo==0) $fontcolor = "green"; else $fontcolor="grey";
	?>
          <tr bgcolor="#FFFFFF"> 
            <td> <div align="center"><font color="<?=$fontcolor?>">$<? echo number_format($off->price,2);?></font></div></td>
            <td> <div align="center"><font color="<?=$fontcolor?>"><? echo date("m/d/y",$off->data);?></font></div></td>
            <td> <div align="center"><font color="<?=$fontcolor?>"> 
                <? $nm = split("@", $off->email); echo $nm[0];?>
                </font></div></td>
          </tr>
          <? $fo=1; } }?>
        </table>
        <? } ?>
      </td>
    </tr>
	</table>
    <?
	mydisconnect();

}



if($a=="offer")

{

	myconnect();

	if (valid_email($email)) {
	$offer=(int) $offer;

	$date=date("YmdHis");

	mysql_query("INSERT INTO dsp_offers (domain,price,email,data,status)  VALUES($domain, $offer, '$email', '$date','0')");

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID='$domain'"));

	$query = mysql_query("SELECT value FROM dsp_options WHERE label='adminemail' ") or die(mysql_error());
	$adminemail=mysql_result($query,0,"value");
	
	mydisconnect();

?>
  <table  width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
	<tr> 
      <td bgcolor=#FFFFCC align=center>Placing an offer on domain <b><? echo $dom->name;?></b></td>
    </tr>
    <tr> 
      <td align=center><br>Your offer has been received.<br><br>
        You've just placed an offer to buy the domain <b><? echo $dom->name;?></b> 
        for <font color=#ff0000><b>$ 
        <?=$offer?>
        </b></font><br><br><br>
        We will contact you by e-mail if we accept your offer.<br> <br><br>
      </td>
    </tr>
	</table>
    <?
		if ($dom->buynow == 0) $buynow ="None"; else $buynow = $dom->buynow;
		if ($dom->minimum == 0) $minimum ="None"; else $minimum = $dom->minimum;

  		$headers = "From: $email\n"; 
  		$headers .= "Reply-to: $email\n"; 
  		$headers .= "Content-Type: text/plain; charset=Windows-1251\n"; 
  		$mess="A new offer has been received from $email:\n===================================================\n";

  		$mess.="Domain:  $dom->name\nOffer: $$offer\n\nFor reference,\nList Price $buynow \n Min offer $minimum \n\nVisit the admin control center to accept this offer or submit a counteroffer.\n====================================\n";

		mail ($adminemail, "NEW OFFER", $mess, $headers ); 
} 	else { $notice = "<font color=red>Enter a valid e-mail address ($email) <br></font>"; $a="o";$id=$domain;$offamount=$offer; }

}



if($a=="o")

{

	myconnect();

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$id"));

	mydisconnect();

?>
  <table  width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
    <tr> 
      <td bgcolor=#FFFFCC align=center>Placing an offer on domain <b><? echo $dom->name;?></b></td>
    </tr>
    <tr> 
      <td align=center>The minimum offer is: <font color=#ff0000><b> 
        <? if ($dom->minimum > 0) echo "$".$dom->minimum; else echo "(None)";?>
        </b></font> 
        <? if($dom->buynow>0) { ?>
        <br>
        The buy it now price is: <font color=#ff0000><b>$<? echo $dom->buynow;?></b></font> 
        <? } ?>
      </td>
    </tr>
    <tr> 
      <td align=center> 
<p>Please confirm your offer and intent to purchase the 
          domain name <b><? echo $dom->name;?></b>. <br>
          <br>
          The price you submit now will be considered a binding offer to which 
          domain seller will have up to seven (7) days to accept or reject. </p>
        <table border=0 cellpadding=3 cellspacing=0 bgcolor="#FFFFFF">
          <form action="index.php" method="POST">
            <input type="hidden" name="a" value="offer">
            <input type="hidden" name="domain" value="<?=$id?>">
            <tr> 
              <td align="right">Your offer:</td>
              <td><input type="text" size="6" name="offer" value="<?=$offamount?>">
                USD</td>
            </tr>
            <tr> 
              <td align="right">Enter your e-mail address:</td>
              <td><input type="text" size="30" name="email" value="<?=$email?>"><br><?=$notice?></td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
              <td><input type=submit value="Confirm offer &raquo;&raquo;" class=but></td>
            </tr>
          </form>
        </table>
        <p>A 3% transaction fee will be added to the final purchase price (minimum 
          $15).<br>
          <br>
        </p></td>
    </tr>
	</table>
    <?	

}




if($a=="purclogreg")

{

	$regerr="";

	myconnect();

	if($state==-1) $stl="Outside of USA"; else $stl=$states_list[$state];

	$col=$countries_list[$country];

	$date=date("YmdHis");

	if($password1==$password2 && $password1!="") $password=$password1; else $regerr.="Password does not match.<br>";

	if($email=="") $regerr.="Enter your email please!<br>";

	$email = strtolower($email);
	$firstname=ucwords($firstname);
	$lastname=ucwords($lastname);
	$address=ucwords($address);
	$city=ucwords($city);
	$password=strtolower($password);
	if($regerr=="") mysql_query("INSERT INTO dsp_buyers (email,firstname,lastname,organization,address,city,state,postalcode,country,phone,fax,password, data) VALUES('$email', '$firstname', '$lastname', '$organization', '$address', '$city', '$stl', '$postalcode', '$col', '$phone','$fax','$password', '$date' )") or die(mysql_error());
	else ($a="purclog");

	$pass=$password;
	mydisconnect();

	$a="purclogging"; $aaa="kkkkk";

}


if($a=="purclogging")

{

	myconnect();

	$email = strtolower($email);
	$pass = strtolower($pass);
	$user=mysql_fetch_object(mysql_query("SELECT * FROM dsp_buyers WHERE email='$email' AND password='$pass' "));
	$uid=$user->ID;
	$a="purclog"; $purcerr=1;

	if(isset($user)) $a="p";

	mydisconnect();

}



if($a=="purclog")

{

	myconnect();


	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$d"));

	mydisconnect();

?>
  <table  width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
    <tr> 
      <td align=center bgcolor=#B7CFFF><b>Domain Contact Information</b></td>
    </tr>
    <tr> 
      <td align=center bgcolor="#EFEFEF"> 
        <?
if($purcerr==1) echo "<font color=#ff0000><b>Error: email or password incorrect!</b></font><br><br>";

	if($regerr!="") echo "<font color=#ff0000><b>$regerr</b></font><br><br>"; 

	if($regerr=="" && $aaa=="kkkkk") echo "<font color=#ff0000><b>You have been registered!<br>Login now to continue!</b></font><br><br>"; 

?>
        <table  border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#FFFFFF" style="border-collapse: collapse">
          <form action=index.php method=POST>
            <input type=hidden name=a value=purclogging>
            <input type=hidden name=prop value=<?=$prop?>>
			<input type=hidden name=d value=<?=$d?>>
            <tr> 
              <td align=right><b>Domain:</b></td>
              <td><? echo $dom->name;?></td>
            </tr>
            <tr> 
              <td align=right><b>Price:</b></td>
              <td><font color=#ff0000><b>$<? echo $dom->buynow;?></b></font></td>
            </tr>
            <tr bgcolor="#FFFFCC"> 
              <td colspan=2 align=center><b>If you have purchased from us before 
                and remember your password, you can login here. Otherwise, skip 
                to Registration Information</b></td>
            </tr>
            <tr> 
              <td align=right><b>Your e-mail:</b></td>
              <td><input size=40 type=text name=email value="<?=$email?>"></td>
            </tr>
            <tr> 
              <td align=right><b>Your password:</b></td>
              <td><input  size=40 type=text name=pass></td>
            </tr>
            <tr> 
              <td align=right>&nbsp;</td>
              <td><input type=submit value="Repeat Buyer Login &raquo;&raquo;" class=but></td>
            </tr>
          </form>
          <form action="index.php" method="POST">
            <input type=hidden name=a value=purclogreg>
            <input type="hidden" name="prop" value="<?=$prop?>">
			<input type=hidden name=d value=<?=$d?>>
            <tr bgcolor="#FFFFCC"> 
              <td colspan=2 align=center><b>Your Domain Registration Information</b></td>
            </tr>
            <tr> 
              <td align=right><b>First name:</b></td>
              <td><input  size=40 type=text name=firstname></td>
            </tr>
            <tr> 
              <td align=right><b>Last name:</b></td>
              <td><input  size=40 type=text name=lastname></td>
            </tr>
            <tr> 
              <td align=right><b>Organization:</b></td>
              <td><input  size=40 type=text name=organization></td>
            </tr>
            <tr> 
              <td align=right><b>Address:</b></td>
              <td><input  size=40 type=text name=address></td>
            </tr>
            <tr> 
              <td align=right><b>City:</b></td>
              <td><input  size=40 type=text name=city></td>
            </tr> 
			            <?
	for($states="",$i=0;$i<count($states_list);$i++) $states.="<option value=$i>".$states_list[$i]."</option>";

	for($countries="",$i=0;$i<count($countries_list);$i++) $countries.= "<option value=$i>".$countries_list[$i]."</option>";

?>
			<tr> 
              <td align=right><b>State:</b></td>
              <td><select name=state>
                  <option value=-1>outside of USA</option>
                  <?=$states?>
                </select></td>
            </tr>
            <tr> 
              <td align=right><b>Postal/ZIP code:</b></td>
              <td><input type=text size=6 name=postalcode></td>
            </tr>

           
            <tr> 
              <td align=right><b>Country:</b></td>
              <td><select name=country>
                  <?=$countries?>
                </select></td>
            </tr>
            <tr> 
              <td align=right><b>Your e-mail:</b></td>
              <td><input  size=40 type=text name=email value="<?=$email?>"></td>
            </tr>
            <tr> 
              <td align=right><b>Phone:</b></td>
              <td><input  size=40 type=text name=phone></td>
            </tr>
            <tr> 
              <td align=right><b>Fax (if any):</b></td>
              <td><input  size=40 type=text name=fax></td>
            </tr>
            <tr> 
              <td align=right><b>Create password:</b></td>
              <td><input  size=40 type=text name=password1></td>
            </tr>
            <tr> 
              <td align=right><b>Repeat password:</b></td>
              <td><input  size=40 type=text name=password2></td>
            </tr>
            <tr> 
              <td align=right>&nbsp;</td>
              <td><input type=submit value="Continue To Purchase &raquo;&raquo;" class=but></td>
            </tr>
          </form>
        </table></td>
    </tr>
	</table>
    <?
}



if($a=="purcomplete")

{		myconnect();
	$result=mysql_query("SELECT * FROM dsp_buyers WHERE email='$email' AND password='$pass'") or die(mysql_error());
	$user=mysql_fetch_object($result);

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID='$d'"));

	$date=date("YmdHis");

	mysql_query("INSERT INTO dsp_purchases (domain, price, user, data, status) VALUES ('$dom->ID', '$dom->buynow', '$user->ID', '$date','1')");

	mysql_query("UPDATE dsp_domains SET status='1' WHERE ID='$dom->ID'");

	mydisconnect();

?>

<table  width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
<tr><td align="center"><b>Thank You, <?=$user->firstname?>!</b><br><br>Your purchase has been received for<br> <br> 
        <table border="0" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#FFFFFF" style="border-collapse: collapse">
		<tr> 
            <td align=right><b>Domain:</b></td>
            <td><? echo $dom->name;?></td>
          </tr>
          <tr> 
            <td align=right><b>Price:</b></td>
            <td><font color=#006600><b>$<? echo $dom->buynow;?></b></font></td>
          </tr>
          <tr> 
            <td align=right><strong>Transaction&nbspFee:</strong></td>
            <td>$<font color=#ff0000><? echo number_format(max(.03*$dom->buynow,15.00),2);?></font></td>
          </tr>
        </table>
        <p>This domain is now pending sale, awaiting your payment. You will be 
          contacted with payment details and instructions for completing this 
          transaction. </p>
        <p> <b>Payment:</b><br>
          <br>
          For most transactions, we utilize Escrow.Com or PayPal to process payments. 
          <br>
          <br>
          <strong>You will be contacted by e-mail with payment details.<br>
          If you have any questions, e-mail 
          <?=$adminemail?>
          </strong><br>
          <br>
        </p>
		
		</td>
    </tr>
	</table>
    <?
}



if($a=="directp")

{

	myconnect();

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$d"));

	$prop=mysql_insert_id();

	mydisconnect();

	$a="p";

}



if($a=="p")

{

	myconnect();

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$d"));

	mydisconnect();

?>
  <table width = "100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#EFEFEF" style="border-collapse: collapse">
    <tr> 
      <td align=center bgcolor=#FFFFCC><b>Purchasing confirmation</b></td>
    </tr>
    <tr> 
      <td align=center> 
<p align="justify"><br>
          <strong>Please confirm your offer and intent to purchase the domain 
          name <? echo $dom->name;?> at the price listed below. </strong>This 
          is a binding contract between you and the current domain owner.</p>
        <p align="justify">Current domain registration fees have been paid by 
          the seller through the domains existing registration period. Future 
          domain registration fees will be the responsibility of the purchaser. 
          A transaction fee of $<? echo number_format(max(.03*$dom->buynow,15.00),2);?> 
          will be added to the purchase price.<br>
        </p>
        <table  border="0" cellpadding="5" cellspacing="0" bordercolor="#5D617A" bgcolor="#FFFFFF" style="border-collapse: collapse">
          <form action="index.php" method="POST">
            <input type=hidden name=a value=purcomplete>
            <input type="hidden" name="prop" value="<?=$prop?>">
			
			<input type="hidden" name="email" value="<?=$email?>">
			<input type="hidden" name="pass" value="<?=$pass?>">
            <input type="hidden" name="d" value="<?=$d?>">

            <tr> 
              <td align=right><b>Domain:</b></td>
              <td><? echo $dom->name;?></td>
            </tr>
            <tr> 
              <td align=right><b>Price:</b></td>
              <td><font color=#000066><b>$<? echo $dom->buynow;?></b></font></td>
            </tr>
            <tr> 
              <td align=right>&nbsp;</td>
              <td><input type=submit value="Yes, I agree to purchase &raquo;&raquo;"  class=but></td>
            </tr>
          </form>
        </table>
        <p>&nbsp;</p></td>
    </tr>
	</table>
<?
}
@include ('footer.html'); 
?>
