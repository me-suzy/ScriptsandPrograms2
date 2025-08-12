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


$Sn=$SCRIPT_NAME;

$Sn=ereg_replace("/[^/]+$","/", $Sn);

$basedir="http://".$HTTP_HOST.$Sn;



$loggedin=0;



myconnect();

$password=mysql_result(mysql_query("SELECT value FROM dsp_options WHERE label='adminpassword'  "),0);

mydisconnect();

if(isset($passfromform)) $pass=packx($passfromform);

if($pass==$password ) $loggedin=1;


include_once("adminfunctions.php");

if($a=="plainlist" && $loggedin) // list domains

{

	
	myconnect();

	$q=mysql_query("SELECT * FROM dsp_domains WHERE status=0 or status=1 ORDER BY name");

	while ($d = mysql_fetch_object($q)) { echo $d->name."<br>"; }
	mydisconnect();

	exit;
	}

?>
<html>
<head>
<title>Domain Saller Pro :: Admin Control</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="expires" content="0">
<meta http-equiv="Cache-Control" content="no-cache">
<style type="text/css">
<!--
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; text-decoration: none}
body {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; text-decoration: none}
a {  color: #0000FF; text-decoration: none}
a:hover {  color: #FF0000; text-decoration: none}
-->
</style>

<script language="JavaScript" type="text/JavaScript">
function mOvr(src,clrOver)
{
	if(!src.contains(event.fromElement))
	{
		src.style.cursor = "hand";
		src.bgColor = clrOver;
	}
}

function mOut(src,clrIn)
{
	if(!src.contains(event.toElement))
	{
		src.style.cursor = "default";
		src.bgColor = clrIn;
	}
}
</script>
</head>
<body bgcolor="#78A4FF"><center>


<h3><font color="#FFFFFF">Administrative Control</font></h3>



<?
adminboxstart($param);


if($loggedin) { 

}  else  {  ?>



<form action=admin.php method=post>

<img src=img/1x1.gif width=19 height=1>Enter password:<br>
<img src=img/1x1.gif width=1 height=5><br>
<img src=img/1x1.gif width=19 height=1><input type=password name=passfromform style="width:100px;">
<img src=img/1x1.gif width=1 height=5><br>
<img src=img/1x1.gif width=19 height=1><input type=submit value='Enter &raquo;&raquo;'  class=but>

</form>



<?  };  


if($a=="" && $loggedin)
adminmenu($param);

if($a=="adding" && $loggedin)

{

	$cats=array();

	myconnect();

	$cats[] = "1";
	$q=mysql_query("SELECT * FROM dsp_cats ORDER BY category");

	while($cat=mysql_fetch_object($q)) {
		if(${"cat$cat->ID"}==1 && $cat->ID != 1) { 
			$cats[]=$cat->ID;

			} }
	if (count($cats) > 1) unset($cats[0]);
	$cats=@implode(" ",$cats);

	if(!isset($buynow)) $buynow=0;

	if(!isset($minimum)) $minimum=$buynow;

	$name = strtolower($name);
	$keywords = strtolower($keywords);
	$description = strtolower($description);
	          // check if domain exists          
          $query = "SELECT * FROM dsp_domains WHERE name = '$name'";
          $result=mysql_query($query);
		if (strlen($name) < 4) { $notice .= "<font color=red>[Invalid domain $name ]</font><br>"; }
          elseif(mysql_num_rows($result) > 0){
                              $notice .= "<font color=red>[Domain $name was already in database]</font><br>";
							             }
										 else{
	mysql_query("INSERT INTO dsp_domains (ID,category,name,description,keywords,logourl,minimum,buynow,status) 
								VALUES('0','$cats','$name','$description','$keywords','$logourl','$minimum','$buynow','0')") or die(mysql_error());

										 }
	mydisconnect();

	$a="list";

}

if($a=="importing" && $loggedin)

{

	myconnect();

	if(!isset($buynow)) $buynow=0;

	if(!isset($minimum)) $minimum=$buynow;

	$domain=get_domains($domain);
$notice=import_domains($domain);
	mydisconnect();

	$a="list";

}






if($a=="changing" && $loggedin)

{

	$cats=array('1');

	myconnect();

	
	$q=mysql_query("SELECT * FROM dsp_cats ORDER BY category");

	while($cat=mysql_fetch_object($q)) if(${"cat$cat->ID"}==1 && $cat->ID != 1) $cats[]=$cat->ID;

if (count($cats) > 1) unset($cats[0]);
	$cats=@implode(" ",$cats);

	mysql_query("UPDATE dsp_domains SET category='$cats', name='$name', description='$description', keywords='$keywords', logourl='$logourl', minimum='$minimum', buynow='$buynow',status='$status' WHERE ID='$id'");

	mydisconnect();

	if (isset($ret)) $a=$ret; else $a="list";

}



if($a=="dch" && $loggedin) // domain changing form

{

	myconnect();

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$id"));

	echo "<table width=500 align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	echo "<form action=admin.php>";

	echo "<input type=hidden name=a value=changing>";

	echo "<input type=hidden name=pass value=$pass>";

	if (isset($ret)) echo "<input type=hidden name=ret value=process>";

	echo "<input type=hidden name=id value=$id>";

	echo "<tr><td align=center colspan=2 bgcolor=#eeeeee><b>Domain Details</b></td></tr>";

	echo "<tr><td align=right>Domain name:</td><td><input type=text name=name size=40 value='$dom->name'></td></tr>";

	echo "<tr><td valign=top align=right>Categories:</td><td>";

	$q=mysql_query("SELECT * FROM dsp_cats ORDER BY category");

	while($cat=mysql_fetch_object($q)) {

		$ch="";

		$re="(^".$cat->ID." )|( ".$cat->ID." )|( ".$cat->ID."$)|(^".$cat->ID."$)";

if(ereg($re, $dom->category)) $ch=" checked";

		echo "<input type=checkbox name=cat$cat->ID value=1$ch>$cat->category<br>";

	}

	echo "</td></tr>";

	echo "<tr><td align=right valign=top>Description:</td><td><textarea name=description cols=30 rows=6>".ucfirst($dom->description)."</textarea></td></tr>";

	echo "<tr><td align=right valign=top>Keywords:</td><td><textarea name=keywords cols=30 rows=3>$dom->keywords</textarea></td></tr>";

	echo "<tr><td align=right>Logo URL:</td><td><input type=text name=logourl size=40 value=\"$dom->logourl\"></td></tr>";

	echo "<tr><td align=right>Minimum offer ($):<br>0=No min</td><td><input type=text name=minimum size=6 value=$dom->minimum></td></tr>";

	echo "<tr><td align=right>Purchase price ($):<br>0=Make offer</td><td><input type=text name=buynow size=6 value=$dom->buynow></td></tr>";

	?>
<tr><td align=right>Purchase Status:</td><td><select name="status"><option value=0 <? if ($dom->status == 0) echo "selected";?>>Available For Sale</option><option value=1 <? if ($dom->status == 1) echo "selected";?>>Pending Sale</option><option value=2 <? if ($dom->status == 2) echo "selected";?>>SOLD</option></select></td></tr>
	<?
	echo "<tr><td>&nbsp;</td><td><input type=submit value=\"Submit changes &raquo;&raquo;\"  class=but></td></tr>";

	echo "</table>";

?>

<?
$purresult=mysql_query("SELECT *,UNIX_TIMESTAMP(data) as data FROM dsp_purchases WHERE domain='$id'");
if (mysql_num_rows($purresult) > 0) {
?>
<p><b>Purchase log for <?=$dom->name?></b></p>
<?
	while ($pur=mysql_fetch_object($purresult)) { 
		$buyer = mysql_fetch_object(mysql_query("SELECT * FROM dsp_buyers WHERE ID='$pur->user'"));
if ($pur->status == 1) echo "Pending sale with "; elseif ($pur->status == 2) echo "Purchased by "; elseif ($pur->status == 0) echo "Almost sale with ";
?><a href=admin.php?a=user&id=<?=$buyer->ID?>&pass=<?=$pass?>><?=$buyer->firstname?> <?=$buyer->lastname?></a> for $<?=$pur->price?> on <? echo date("n/j/y d:i a",$pur->data);?><br>
<a href=admin.php?a=completep&pass=<?=$pass?>&id=<?=$id?>&purid=<?=$pur->ID?>>[mark as completed]</a> - <a href=admin.php?a=cancelp&pass=<?=$pass?>&id=<?=$dom->ID?>&purid=<?=$pur->ID?>>[cancel purchase]</a> - <a href=admin.php?a=pendp&pass=<?=$pass?>&id=<?=$dom->ID?>&purid=<?=$pur->ID?>>[set to pending]</a>
<hr>
<?

} }
$offresult=mysql_query("SELECT *,UNIX_TIMESTAMP(data) as data FROM dsp_offers WHERE domain='$id'");
if (mysql_num_rows($offresult) > 0) {
?>
<p><b>Offers log for <?=$dom->name?></b></p>
<?
	while ($off=mysql_fetch_object($offresult)) { 
if ($off->status == 0) $status= "Unreviewed"; elseif ($off->status==1) $status = "Accepted"; elseif ($off->status == 2) $status= "Ignored"; elseif ($off->status == 3) $status = "Counteroffered";
?>$<?=$off->price?> offer from <a href="mailto:<?=$off->email?>"><?=$off->email?></a> on <? echo date("n/j/y d:i a",$off->data);?> (<i><?=$status?></i>)<br>
<a href=admin.php?a=oaccept&pass=<? echo $pass;?>&id=<?=$off->ID?>>[accept offer]</a> - <a href=admin.php?a=oignore&pass=<? echo $pass;?>&id=<?=$off->ID?>>[ignore]</a> - <a href=admin.php?a=odelete&pass=<? echo $pass;?>&id=<?=$off->ID?>>[delete]</a> - <a href=admin.php?a=ocounter&pass=<? echo $pass;?>&id=<?=$off->ID?>>[counteroffer]</a>
<?
$coresult=mysql_query("SELECT *,UNIX_TIMESTAMP(data) as data FROM dsp_counteroffers WHERE offer='$off->ID'");
if (mysql_num_rows($coresult) > 0) {
	while ($co=mysql_fetch_object($coresult)) { 
?><br>Sent counteroffer of $<?=$co->price?> on <? echo date("n/j/y d:i a",$co->data);?>
<? } }?>
<hr>
<?

} }



	mydisconnect();

	

}



if($a=="add" && $loggedin)

{

	echo "<table border=1 cellpadding=4 cellspacing=0 width=500 align=center>";

	echo "<form action=admin.php>";

	echo "<input type=hidden name=a value=adding>";

	echo "<input type=hidden name=pass value=$pass>";

	echo "<tr><td align=center colspan=2 bgcolor=#eeeeee><b>Add A Domain</b></td></tr>";

	echo "<tr><td align=right>Domain name:</td><td><input type=text name=name size=40></td></tr>";

	echo "<tr><td valign=top align=right>Categories:</td><td>";

	myconnect();	

	$q=mysql_query("SELECT * FROM dsp_cats ORDER BY category");

	while($cat=mysql_fetch_object($q)) echo "<input type=checkbox name=cat$cat->ID value=1>&nbsp;$cat->category<br>";

	mydisconnect();

	echo "</td></tr>";

	echo "<tr><td align=right valign=top>Description:</td><td><textarea name=description cols=30 rows=6></textarea></td></tr>";

	echo "<tr><td align=right valign=top>Keywords:</td><td><textarea name=keywords cols=30 rows=3></textarea></td></tr>";

	echo "<tr><td align=right>Logo URL:</td><td><input type=text name=logourl size=40></td></tr>";

	echo "<tr><td align=right>Minimum offer ($):<br>0 = No min</td><td><input type=text name=minimum size=6></td></tr>";

	echo "<tr><td align=right>Purchase price ($):<br>0 = Make offer</td><td><input type=text name=buynow size=6></td></tr>";

	echo "<tr><td>&nbsp;</td><td><input type=submit value=\"Add domain &raquo;&raquo;\"  class=but></td></tr>";

	echo "</form></table>";

}



if($a=="import" && $loggedin)

{

	echo "<table border=1 cellpadding=4 cellspacing=0 align=center>";

	echo "<form action=admin.php>";

	echo "<input type=hidden name=a value=importing>";

	echo "<input type=hidden name=pass value=$pass>";

	echo "<tr><td align=center colspan=2 bgcolor=#eeeeee><b>Import Domains</b><br>Use this form to import a list of domain names (one domain name per line)<br>Domains will be assigned to the 'Unclassified' category.</td></tr>";

	
	echo "<tr><td align=right>Domain list<br>one email address per line</td><td><textarea name=\"domain\" cols=\"40\" rows=\"10\"></textarea></td></tr>";

	echo "<tr><td align=right>Minimum offer ($):<br>0=No min</td><td><input type=text name=minimum size=6 value=0></td></tr>";

	echo "<tr><td align=right>Purchase price ($):<br>0=Make offer</td><td><input type=text name=buynow size=6 value=0></td></tr>";

	echo "<tr><td>&nbsp;</td><td><input type=submit value=\"Import domains &raquo;&raquo;\"  class=but></td></tr>";

	echo "</form></table>";

}





if($a=="newpass" && $loggedin)

{

	if($newpass1!=$newpass2) { echo "Passwords do not match.  Please try again.<br>"; $a="chpass"; } else

	{	

		myconnect();

		$newpass=packx($newpass1);

		mysql_query("UPDATE dsp_options SET value='$newpass' WHERE label='adminpassword'");

		mydisconnect();

		echo "Password changed.";

	}

}



if($a=="chpass" && $loggedin)

{

	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	echo "<form action=admin.php method=post>";

	echo "<input type=hidden name=a value=newpass>";

	echo "<tr><td colspan=2 bgcolor=#eeeeee>&nbsp;<b>Administrator's password changing:</b></td></tr>";

	echo "<tr><td align=right valign=top>Old password:</td><td><input type=password  name=passfromform></td></tr>";

	echo "<tr><td align=right valign=top>New password:</td><td><input type=password  name=newpass1></td></tr>";

	echo "<tr><td align=right valign=top>Repeat new pasword:</td><td><input type=password  name=newpass2></td></tr>";

	echo "<tr><td>&nbsp;</td><td align=left><input type=submit value='Change password &raquo;&raquo;' class=but></td></tr>";

	echo "</form>";

	echo "</table>";

}



if($a=="newemail" && $loggedin)

{

	myconnect();

	mysql_query("UPDATE dsp_options SET value='$email' WHERE label='adminemail'");

	mydisconnect();

	echo "Email changed.";

}



if($a=="chemail" && $loggedin)

{

	myconnect();

	$em=mysql_result(mysql_query("SELECT value FROM dsp_options WHERE label='adminemail'"),0);

	mydisconnect();

	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	echo "<form action=admin.php method=post>";

	echo "<input type=hidden name=a value=newemail>";

	echo "<input type=hidden name=pass value=$pass>";

	echo "<tr><td colspan=2 bgcolor=#eeeeee>&nbsp;<b>Administrator's email changing:</b></td></tr>";

	echo "<tr><td align=right valign=top>New email:</td><td><input type=text name=email value='$em'></td></tr>";

	echo "<tr><td>&nbsp;</td><td align=left><input type=submit value='Submit change &raquo;&raquo;' class=but></td></tr>";

	echo "</form>";

	echo "</table>";

}



if($a=="del" && $loggedin)

{

	myconnect();

	mysql_query("delete from dsp_domains WHERE ID=$id");

	mysql_query("delete from dsp_offers WHERE domain='$id'");
	mysql_query("delete from dsp_purchases WHERE domain='$id'");
	mysql_query("delete from dsp_counteroffers WHERE domain='$id'");
	mydisconnect();

	$a="list";

}



if($a=="delfa" && $loggedin)

{

	myconnect();

	mysql_query("delete from dsp_domains WHERE ID='$id'");

	mysql_query("delete from dsp_offers WHERE domain='$id'");
	mysql_query("delete from dsp_purchases WHERE domain='$id'");
	mysql_query("delete from dsp_counteroffers WHERE domain='$id'");
	mydisconnect();

	$a="archive";

}



if($a=="list" && $loggedin) // list domains

{

	echo $notice;
	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	myconnect();

	$q=mysql_query("SELECT * FROM dsp_domains WHERE status=0 or status=1 ORDER BY name");

	echo "<tr><td bgcolor=#eeeeee><b>Domain</b></td><td bgcolor=#eeeeee><b>Minimum offer</b></td><td bgcolor=#eeeeee><b>Purchase price</b></td><td bgcolor=#eeeeee><b>&nbsp;</b></td></tr>";

	while ($d = mysql_fetch_object($q)) {  if ($d->minimum == $d->buynow && $d->minimum > 0) $mino = "N/A"; else $mino = $d->minimum; if ($d->buynow == 0) $purch = "Make offer"; else $purch = $d->buynow; 
	echo "<tr><td><a href=admin.php?a=dch&pass=$pass&id=$d->ID>$d->name</a>";
	if ($d->status == 1) echo " <font color=red>*P</font>";
	echo "</td><td>$mino&nbsp;</td><td>$purch&nbsp;</td><td><a href=admin.php?a=del&pass=$pass&id=$d->ID>delete</a></td></tr>";
 }
	mydisconnect();

	echo "</table><center><br><font color=red>*P</font> = Pending Sale - <a href=admin.php?a=plainlist&pass=$pass>Plain text list</a></center>";

}



if($a=="process" && $loggedin) // list domains

{

	echo $notice;
	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	myconnect();

	$q=mysql_query("SELECT * FROM dsp_domains WHERE (status=0 or status=1) and category = '1' ORDER BY name");

	echo "<tr><td bgcolor=#eeeeee><b>Domain</b></td><td bgcolor=#eeeeee><b>Minimum offer</b></td><td bgcolor=#eeeeee><b>Purchase price</b></td><td bgcolor=#eeeeee><b>&nbsp;</b></td></tr>";

		if (mysql_num_rows($q) > 0) {
	while ($d = mysql_fetch_object($q)) {  if ($d->minimum == $d->buynow && $d->minimum > 0) $mino = "N/A"; else $mino = $d->minimum; if ($d->buynow == 0) $purch = "Make offer"; else $purch = $d->buynow; 
	echo "<tr><td><a href=admin.php?a=dch&pass=$pass&id=$d->ID&ret=process>$d->name</a>";
	if ($d->status == 1) echo " <font color=red>*P</font>";
	echo "</td><td>$mino&nbsp;</td><td>$purch&nbsp;</td><td><a href=admin.php?a=del&pass=$pass&id=$d->ID>delete</a></td></tr>";
 }
	}
	else echo "<tr><td colspan=4>(none)</td></tr>";
	mydisconnect();

echo "</table>";
	}



if($a=="cancelp" && $loggedin)  // cancel the purchase

{

	myconnect();

	mysql_query("UPDATE dsp_domains SET status=0 WHERE ID='$id'");
	mysql_query("UPDATE	purchases SET status=0 WHERE ID='$purid'");

	mydisconnect();

	$a="archive";

}


if($a=="completep" && $loggedin)  // complete purchase

{

	myconnect();

	mysql_query("UPDATE dsp_domains SET status=2 WHERE ID='$id'");

	mysql_query("UPDATE dsp_purchases set status=2 WHERE ID='$purid'");

	mydisconnect();

	$a="plist";

}


if($a=="pendp" && $loggedin)  // complete purchase

{

	myconnect();

	mysql_query("UPDATE dsp_domains SET status=1 WHERE ID='$id'");

	mysql_query("UPDATE dsp_purchases set status=1 WHERE ID='$purid'");

	mydisconnect();

	$a="archive";

}




if($a=="archive" && $loggedin) // list completed sales
{

	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	myconnect();

	$q=mysql_query("SELECT * FROM dsp_purchases where status = '2' ORDER BY data DESC");

?>	
<tr><td bgcolor=#eeeeee><b>Completed Sales</b></td></tr>
<?	
	while ($pur = mysql_fetch_object($q))  {

		$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID='$pur->domain'"));

		$user=mysql_fetch_object(mysql_query("SELECT * FROM dsp_buyers WHERE ID='$pur->user'"));

	$qhelp.= "and ID !='$dom->ID' ";
?>
<tr><td>
<table width=100%><td><div align="left"><b><font size="+1"><a href="admin.php?a=dch&id=<?=$dom->ID?>&pass=<?=$pass?>"><?=$dom->name?></a></b></font></div></td><td><div align="right"><font color=green size="+1">$<?=$pur->price?></font></div></td></table>
Buyer <a href=admin.php?a=user&id=<?=$user->ID?>&pass=<?=$pass?>><?=$user->firstname?> <?=$user->lastname?></a> - Completed (<? echo date("n/j/y a:d i",$pur->data);?>)<br><br>
<a href=admin.php?a=delfa&pass=<?=$pass?>&id=<?=$dom->ID?>>[delete from archive]</a> - <a href=admin.php?a=cancelp&pass=<?=$pass?>&id=<?=$dom->ID?>&purid=<?=$pur->ID?>>[cancel purchase]</a> - <a href=admin.php?a=pendp&pass=<?=$pass?>&id=<?=$dom->ID?>&purid=<?=$pur->ID?>>[set to pending]</a></td></tr>


	
	<tr><td><hr width=100%></td></tr>
	<?
	}
$doms=mysql_query("SELECT * FROM dsp_domains where status = '2' $qhelp") or die(mysql_error());
	
if (mysql_num_rows($doms) > 0 ) { ?>
<tr><td bgcolor=#eeeeee><b>Other Domains Manually Marked as Sold</b></td></tr>
<?
	while ($dom=mysql_fetch_object($doms)) { 
	?>
<tr><td valign=top>
<table width=100%><td><div align="left"><b><font size="+1"><a href="admin.php?a=dch&id=<?=$dom->ID?>&pass=<?=$pass?>"><?=$dom->name?></a></b></font></div></td></table>
</td></tr>
<?
	}
}

	mydisconnect();

	echo "</table>";

}



if($a=="catdel" && $loggedin)  // manage categories

{

	myconnect();

	mysql_query("delete from dsp_cats WHERE ID=$id");

	mydisconnect();

	$a="cats";

}

if($a=="catadd" && $loggedin)  // manage categories

{

	myconnect();

	mysql_query("INSERT INTO dsp_cats VALUES(0,'$name')") or print(mysql_error());

	mydisconnect();

	$a="cats";

}

if($a=="catch" && $loggedin)  // manage categories

{

	myconnect();

	mysql_query("UPDATE dsp_cats SET category='$name' WHERE ID=$id");

	mydisconnect();

	$a="cats";

}



if($a=="cats" && $loggedin)  // manage categories

{

	myconnect();

	$q=mysql_query("SELECT * FROM dsp_cats ORDER BY category");

	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	echo "<form action=admin.php method=POST>";

	echo "<input type=hidden name=a value=catadd>";

	echo "<input type=hidden name=pass value=$pass>";

	echo "<tr><td bgcolor=#eeeeee colspan=3><b>Add new category</b></td></tr>";

	echo "<tr><td><input type=text name=name></td><td colspan=2><input type=submit value=\"Create\"  class=but></td></tr>";

	echo "</form>";

	echo "<tr><td bgcolor=#eeeeee colspan=3><b>Manage existing categories</b></td></tr>";

	while($c=mysql_fetch_object($q)){

		echo "<form action=admin.php method=POST>";

		echo "<tr><td align=left>";

		echo "<input type=hidden name=a value=catch>";

		echo "<input type=hidden name=pass value=$pass >";

		echo "<input type=hidden name=id value=$c->ID>";

		echo "<input type=text name=name value=\"$c->category\">";

	//if ($c->ID == 1) echo "<br>(Unclassified Category)";
		echo "</td><td><input type=submit value=\"Save\"  class=but></td>";

	if ($c->ID != 1) echo "<td><a href=admin.php?a=catdel&id=$c->ID&pass=$pass><b>Delete</b></a></td>";
else echo"<td>REQUIRED</td>";
		echo "</tr></form>";

	}

	mydisconnect();

	echo "</table>";

}



if($a=="odelete" && $loggedin) // delete new offers

{

	myconnect();

	mysql_query("delete from dsp_offers WHERE ID=$id");

	mydisconnect();

	$a="o";

	if($arch==123) $a="oarchive";

}



if($a=="oaccept" && $loggedin) // browse new offers

{

	myconnect();

	mysql_query("UPDATE dsp_offers SET status=1 WHERE ID=$id");

	$of=mysql_fetch_object(mysql_query("SELECT * FROM dsp_offers WHERE ID='$id'"));

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID='$of->domain'  "));

	mysql_query("UPDATE dsp_domains set status='1', buynow='$of->price' where ID='$of->domain'");
	mysql_query("INSERT INTO dsp_buyers (email,password,data) VALUES ('$of->email', 'not registered', '$of->data')");
	$buyid = mysql_insert_id();
	mysql_query("INSERT INTO dsp_purchases(domain,price,user,data,status) VALUES ('$of->domain','$of->price','$buyid',CURRENT_TIMESTAMP,'1')");
//	mysql_query("INSERT INTO dsp_counteroffers (offer,domain,price,email,data) VALUES ( '$of->ID', '$dom->ID', '$of->price', '$of->email', CURRENT_TIMESTAMP) ") or print(mysql_error());

	$prop=mysql_insert_id();

  		$headers = "From: $adminemail\n"; 

  		$headers .= "Reply-to: $adminemail\n"; 

  		$headers .= "Content-Type: text/plain; charset=Windows-1251\n"; 

  		$mess="Your offer has been accepted!:\n===================================================\n";

  		$mess.="Domain:  $dom->name\nYour offer of $$of->price\n====================================\n";

  		$mess.="Now press this link to process the next step:\n";

  		$mess.=$basedir."index.php?a=purclog&d=$dom->ID&email=$of->email \n====================================\n";

		mail ($of->email, "$dom->name Offer Accepted", $mess, $headers ); 

	mydisconnect();

	$a="o";

}



if($a=="oignore" && $loggedin) // ignore offer
{

	myconnect();

	$of=mysql_fetch_object(mysql_query("SELECT * FROM dsp_offers where ID='$id'"));
	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains where ID='$of->domain'"));
	mysql_query("UPDATE dsp_offers SET status=2 WHERE ID='$id'");

	if ($of->price > $dom->minimum)	mysql_query("UPDATE dsp_domains set minimum='$of->price' where ID = '$of->domain'");
	mydisconnect();

	$a="o";

}



if($a=="ocountering" && $loggedin) // counteroffer process

{

	myconnect();

	$of=mysql_fetch_object(mysql_query("SELECT * FROM dsp_offers WHERE ID=$id"));

	if ($counteroffer >0) mysql_query("UPDATE dsp_domains set buynow = '$counteroffer' where ID ='$of->domain'");
	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$of->domain"));

	if ($of->price > $dom->minimum)	mysql_query("UPDATE dsp_domains set minimum='$of->price' where ID = '$of->domain'");
	mysql_query("INSERT INTO dsp_counteroffers (domain,price,email,data) VALUES ('$dom->ID','$counteroffer', '$of->email',CURRENT_TIMESTAMP)") or print(mysql_error());

	$prop=mysql_insert_id();

  		$headers = "From: $adminemail\n"; 

  		$headers .= "Reply-to: $adminemail\n"; 

  		$headers .= "Content-Type: text/plain; charset=Windows-1251\n"; 

  		$mess="We have received your offer for the domain $dom->name. We thank you for your interest in the domain, and would like to re-offer it to you for $$counteroffer\n===================================================\n";

  		$mess.="Domain:  $dom->name\n";

  		$mess.="Price: $$counteroffer\n====================================\n";

		$mess.="If this price is agreeable to you, please click the link below:\n";

  		$mess.=$basedir."index.php?a=purclog&d=$dom->ID&email=$of->email \n====================================\n\n";

		$mess.="Please be aware the Buy Now price on this domain has been lowered to $dom->buynow, and it is available on a first come basis at this price.\n\n";
		$mess.=$comments;
		mail ($of->email, "$dom->name Offer", $mess, $headers ); 

	mysql_query("UPDATE dsp_offers SET status=3 WHERE ID=$id");

	mydisconnect();

	$a="o";

}



if($a=="ocounter" && $loggedin) // counteroffer form

{

	myconnect();

	$of=mysql_fetch_object(mysql_query("SELECT * FROM dsp_offers WHERE ID=$id"));

	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$of->domain"));

	?>
<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>
<form action=admin.php method=post>
<input type=hidden name=a value=ocountering>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=pass value=<?=$pass?>>
<tr><td colspan=2 bgcolor=#eeeeee>&nbsp;<b>Making the counteroffer:</b></td></tr>
<tr><td align=right>Domain:</td><td><?=$dom->name?></td></tr>
<tr><td align=right>Offer price:</td><td><font color=#ff0000><b>$<?=$of->price?></b></font></td></tr>
<tr><td align=right>Your counteroffer:</td><td>$&nbsp;<input type=text name=counteroffer size=6 value=<?=$dom->buynow?>></td></tr>
<tr><td align=right>Comments (optional):</td><td><textarea cols=30 rows=6 name="comments"></textarea> 
<tr><td>&nbsp;</td><td align=left><input type=submit value='Make counteroffer &raquo;&raquo;' class=but></td></tr>
</form>
</table>
<?
	mydisconnect();

}



if($a=="o" && $loggedin) // browse new offers

{

?>
<table width="400" align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>
<?
	myconnect();

	$q=mysql_query("SELECT *,unix_timestamp(data) as data FROM dsp_offers WHERE status=0");

?>
<tr><td bgcolor=#eeeeee><b>New Offers</b></td><td>Action</td></tr>
<?
	while ($d = mysql_fetch_object($q))  {

		$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$d->domain"));

	?>
<tr><td><font size="+1"><b><a href="admin.php?a=dch&id=<?=$dom->ID?>&pass=<?=$pass?>"><? echo $dom->name;?></a></b></font><br>(Buy price: <? if ($dom->buynow > 0)echo $dom->buynow; else echo "none";?>, Min offer: <? if ($dom->minimum >0) echo $dom->minimum; else echo "none";?>)<br><br>
<? if ($dom->status ==1) { ?><FONT color="red">NOTE: PENDING SALE WITH ANOTHER BUYER!</FONT><BR>This is a backup offer only.  Can not accept or counter until pending sale is removed.<br><? } ?>
<? if ($dom->status ==2) { ?><FONT color="red">NOTE: SALE ALREADY MADE WITH ANOTHER BUYER!</FONT><BR>Can not accept or counter until pending sale is removed.<br><? } ?>
<b><font color=#ff0000 size="+1">$<? echo $d->price;?></font></b> offer on <? echo date("n/j/y g:i a", $d->data);?><br>
Submitted by <a href="mailto:<? echo $d->email;?>"><? echo $d->email;?></a><br>
</td>
<td>
<? if ($dom->status == 0) { ?><p><a href=admin.php?a=oaccept&pass=<? echo $pass;?>&id=<? echo $d->ID;?>>[accept]</a></p><?}?>
<p><a href=admin.php?a=oignore&pass=<? echo $pass;?>&id=<? echo $d->ID;?>>[ignore]</a></p>
<p><a href=admin.php?a=odelete&pass=<? echo $pass;?>&id=<? echo $d->ID;?>>[delete]</a></p>
<? if ($dom->status == 0) { ?><p><a href=admin.php?a=ocounter&pass=<? echo $pass;?>&id=<? echo $d->ID;?>>[counteroffer]</a></p><? }?>
</td>
</tr>
	<?
	}

	mydisconnect();

	echo "</table>";

}



if($a=="oarchive" && $loggedin) // browse offers archive

{

	myconnect();

$offresult=mysql_query("SELECT *,UNIX_TIMESTAMP(data) as data FROM dsp_offers where status != 1 order by ID desc") or die(mysql_error());
if (mysql_num_rows($offresult) > 0) {
?>
<p><b>Offers log for all 'available' domains</b><br>Pending and completed sales are not listed.</p>
<?
while ($off=mysql_fetch_object($offresult)) { 
	$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains where ID = '$off->domain'"));
	if ($off->status == 0) $status= "Unreviewed"; elseif ($off->status==1) $status = "Accepted"; elseif ($off->status == 2) $status= "Ignored"; elseif ($off->status == 3) $status = "Counteroffered";
?><b><a href="admin.php?a=dch&id=<?=$dom->ID?>&pass=<?=$pass?>"><?=$dom->name?></a></b>&nbsp;&nbsp;(Asking price <?=$dom->buynow?>, Min offer <?=$dom->minimum?>)<br>
$<?=$off->price?> offer from <a href="mailto:<?=$off->email?>"><?=$off->email?></a> on <? echo date("n/j/y d:i a",$off->data);?> (<i><?=$status?></i>)<br>
<a href=admin.php?a=oaccept&pass=<? echo $pass;?>&id=<?=$off->ID?>>[accept offer]</a> - <a href=admin.php?a=oignore&pass=<? echo $pass;?>&id=<?=$off->ID?>>[ignore]</a> - <a href=admin.php?a=odelete&pass=<? echo $pass;?>&id=<?=$off->ID?>&arch=123>[delete]</a> - <a href=admin.php?a=ocounter&pass=<? echo $pass;?>&id=<?=$off->ID?>>[counteroffer]</a>
<?
$coresult=mysql_query("SELECT *,UNIX_TIMESTAMP(data) as data FROM dsp_counteroffers WHERE offer='$off->ID'");
if (mysql_num_rows($coresult) > 0) {
	while ($co=mysql_fetch_object($coresult)) { 
?><br>Sent counteroffer of $<?=$co->price?> on <? echo date("n/j/y d:i a",$co->data);?>
<? } }?>
<hr>
<? }}
	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	

	$q=mysql_query("SELECT * FROM dsp_offers WHERE status>0 ORDER BY ID DESC" );

	echo "<tr><td bgcolor=#eeeeee><b>Domain</b></td><td bgcolor=#eeeeee><b>Offer</b></td><td bgcolor=#eeeeee><b>Status</b></td><td bgcolor=#eeeeee>&nbsp;</td></tr>";

	while ($d = mysql_fetch_object($q))  {

		$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID=$d->domain"));

		echo "<tr><td>$dom->name</td><td><b><font color=#ff0000>$$d->price</font></b></td>";

		$status="ignored"; // =2

		if($d->status==1) $status="sale pending";

		if($d->status==3) $status="counteroffered";

		echo "<td>$status</td>";

		echo "<td><a href=admin.php?a=odelete&pass=$pass&id=$d->ID&arch=123>delete from archive</a></td>";

		echo "</tr>";

	}

	mydisconnect();

	echo "</table>";

}



if($a=="plist" && $loggedin) // list pending sales
{

	?>
<table width=400 align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>
		
<tr><td bgcolor=#eeeeee><b>Pending Sales - Payments Not Processed</b></td></tr>
<?
	myconnect();

	$q=mysql_query("SELECT *,UNIX_TIMESTAMP(data) as data FROM dsp_purchases where status = '1' ORDER BY data DESC");

	while ($pur = mysql_fetch_object($q))  {

		$dom=mysql_fetch_object(mysql_query("SELECT * FROM dsp_domains WHERE ID='$pur->domain'"));

		$user=mysql_fetch_object(mysql_query("SELECT * FROM dsp_buyers WHERE ID='$pur->user'"));

	$qhelp.= "and ID !='$dom->ID' ";
	?>
<tr><td valign=top>
<table width=100%><td><div align="left"><b><font size="+1"><a href="admin.php?a=dch&id=<?=$dom->ID?>&pass=<?=$pass?>"><?=$dom->name?></a></b></font></div></td><td><div align="right"><font color=green size="+1">$<?=$pur->price?></font></div></td></table>
Buyer <a href=admin.php?a=user&id=<?=$user->ID?>&pass=<?=$pass?>><?=$user->firstname?> <?=$user->lastname?>  <?=$user->email?></a> (<? echo date("n/j/y d:i a",$pur->data);?>)<br><br>
<a href=admin.php?a=completep&pass=<?=$pass?>&id=<?=$dom->ID?>&purid=<?=$pur->ID?>>[mark as completed]</a> - <a href=admin.php?a=cancelp&pass=<?=$pass?>&id=<?=$dom->ID?>&purid=<?=$pur->ID?>>[cancel purchase]</a>
</td></tr>

<?
	}

$doms=mysql_query("SELECT * FROM dsp_domains where status = '1' $qhelp") or die(mysql_error());
	
if (mysql_num_rows($doms) > 0 ) { ?>
<tr><td bgcolor=#eeeeee><b>Other Domains Manually Marked as Pending</b></td></tr>
<?
	while ($dom=mysql_fetch_object($doms)) { 
	?>
<tr><td valign=top>
<table width=100%><td><div align="left"><b><font size="+1"><a href="admin.php?a=dch&id=<?=$dom->ID?>&pass=<?=$pass?>"><?=$dom->name?></a></b></font></div></td></table>
</td></tr>
<?
	}
		}
	mydisconnect();

	echo "</table>";

}



if($a=="user" && $loggedin) // buyer info

{

	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	myconnect();

	$u=mysql_fetch_object(mysql_query("SELECT *,UNIX_TIMESTAMP(data) as data FROM dsp_buyers WHERE ID=$id"));

	echo "<tr><td bgcolor=#eeeeee colspan=2 align=center><b>Buyer's profile:</b></td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Name:</td><td>$u->firstname $u->lastname</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Email:</td><td>$u->email</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Organization:</td><td>$u->organization</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Address:</td><td>$u->address</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>City:</td><td>$u->city</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Postal/ZIP code:</td><td>$u->postalcode</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>State:</td><td>$u->state</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Country:</td><td>$u->country</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Phone:</td><td>$u->phone</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Fax:</td><td>$u->fax</td></tr>";

	echo "<tr><td bgcolor=#eeeeee align=right>Password:</td><td>$u->password</td></tr>";

?>
<tr><td bgcolor=#eeeeee align=right>Registered:</td><td><? echo date ("n/j/y d:i a",$u->data);?></td></tr>
<?
mydisconnect();

	echo "</table>";

}





if($a=="users" && $loggedin) // list buyers

{

	echo "<table align=center border=1 cellspacing=0 cellpadding=5 bordercolorlight=#ffffff bordercolordark=#bbbbbb>";

	myconnect();

	$q=mysql_query("SELECT * FROM dsp_buyers ORDER BY ID DESC");

	echo "<tr><td bgcolor=#eeeeee><b>Name</b></td><td bgcolor=#eeeeee><b>Email</b></td></tr>";

	while ($user = mysql_fetch_object($q))  {

		echo "<tr><td><a href=admin.php?a=user&id=$user->ID&pass=$pass>$user->firstname $user->lastname</a>&nbsp;</td><td>$user->email&nbsp;</td></tr>";

	}

	mydisconnect();

	echo "</table>";

}



adminboxend($param);
?>

&nbsp;

<br><br>
Domain Seller Pro (c) 2002 <!--CyKuH [WTN]-->Pro PHP
</body>
</html>
