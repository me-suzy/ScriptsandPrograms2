<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////

function resizepic($width,$height){ 
global $max_width,$max_height;
if($width>$max_width){ 
$scale = (float)$max_width/(float)$width; 
$width = (int) $width*$scale; 
$height = (int) $height*$scale; 
} 
if($height>$max_height){ 
$scale = (float)$max_height/(float)$height; 
$width = (int) $width*$scale; 
$height = (int) $height*$scale; 
} return array($width,$height); 
} 

function logout() {
global $f;
session_start();
session_destroy();
header ("Set-Cookie: logged=$f; expires=Wed, 2-Jan-1987 00:00:00 GMT; path=/;");
header ("Set-Cookie: modlogin=$f; expires=Wed, 2-Jan-1987 00:00:00 GMT; path=/;");
header ("Set-Cookie: ivadmin=$f; expires=Wed, 2-Jan-1987 00:00:00 GMT; path=/;");
}

if ((isset($f)) && $f == "logout") logout();

require ("config.php");
langindex();
if (!$template) $template = "template1.php";

$cookieinfo = $HTTP_COOKIE_VARS[logged];

$originalc=$c;
// $u (user)  $c (category)  $f (function) $id ( id #)
if(!isset($c)) $c = "all";
if(IsSet($men_x)) $c="men";
if(IsSet($women_x)) $c="women";


$lastpicture="";

// connect to the database until the end
mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");

// if visitor just voted, update the last image's record first
if (!isset($vote)) {
if(IsSet($vote1_x)) $vote=1;  // support for image inputs
if(IsSet($vote2_x)) $vote=2;
if(IsSet($vote3_x)) $vote=3;
if(IsSet($vote4_x)) $vote=4;
if(IsSet($vote5_x)) $vote=5;
if(IsSet($vote6_x)) $vote=6;
if(IsSet($vote7_x)) $vote=7;
if(IsSet($vote8_x)) $vote=8;
if(IsSet($vote9_x)) $vote=9;
if(IsSet($vote10_x)) $vote=10;
}
if (isset($vote)) {
if ($vote != 99 && $vote > 10) $vote = 10;

if($vote == "1") $whatvote = "one";
if($vote == "2") $whatvote ="two";
if($vote == "3") $whatvote ="three";
if($vote == "4") $whatvote ="four";
if($vote == "5") $whatvote ="five";
if($vote == "6") $whatvote ="six";
if($vote == "7") $whatvote ="seven";
if($vote == "8") $whatvote ="eight";
if($vote == "9") $whatvote ="nine";
if($vote == "10") $whatvote ="ten";
// if ($vote < 3)  $oldrate+= 2;
if ($donerep < 1) $donerep=0;

if ($vote == "99") {
if ($donerep <= 1) {
$result=mysql_query("SELECT reported FROM $imagetable WHERE id = '$imgid'") or die(mysql_error());
$reports=mysql_result($result,0,"reported");
$reports++;
mysql_query("UPDATE $imagetable SET reported = '$reports', reason = 'autobroken' WHERE id = '$imgid'") or die(mysql_error());
if ( $reports >= $maxreport)
mysql_query("UPDATE $imagetable SET status = 'reported' WHERE id = '$imgid'") or die(mysql_error());
$donerep++;
}
$vote = "?"; $oldaverage = "?"; $oldtotal = "?"; $image = "broken.gif";
}
else {
$result=mysql_query("SELECT * FROM $imagetable WHERE id = '$imgid'");

$oldvoter1=mysql_result($result,0,"voter1");
$oldvoter2=mysql_result($result,0,"voter2");
$oldvoter3=mysql_result($result,0,"voter3");
$oldvoter4=mysql_result($result,0,"voter4");
$oldvoter5=mysql_result($result,0,"voter5");
$oldrate=mysql_result($result,0,"rate");
$oldno=mysql_result($result,0,$whatvote);
$oldtotal=mysql_result($result,0,"total");


if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"] != "")$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
else $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
if (strlen($ip) < 6) $ip = $REMOTE_ADDR;

if ($oldvoter1 != $ip && $oldvoter2 != $ip && $oldvoter3 != $ip && $oldvoter4 != $ip && $oldvoter5 != $ip) {
if ($vote != 0) $oldtotal++;
$oldrate += $vote;
$oldno++;
$oldaverage = $oldrate/$oldtotal;
$oldaverage = sprintf ('%0.1f', $oldaverage);

mysql_query("UPDATE $imagetable SET voter1 = '$ip', voter2 = '$oldvoter1', voter3 = '$oldvoter2', voter4 = '$oldvoter3', voter5 = '$oldvoter4' where id = '$imgid'");
mysql_query("UPDATE $imagetable SET rate = '$oldrate', total = '$oldtotal', $whatvote = '$oldno', average = '$oldaverage' where id = '$imgid'");

}
else {
$oldaverage = $oldrate/$oldtotal;
$oldaverage = sprintf ('%0.1f', $oldaverage);
}

if($oldtotal < $votesneeded) $oldaverage = "?";
$rrate = round ($oldaverage);
$rrate--;
}


$lastpicture="<table border=1 cellpadding=1 bgcolor=\"#e0e0e0\" align=\"center\">
<tr><td colspan=3 valign=top nowrap><center><img src=\"$image\" width=\"149\">
</center></td></tr><tr><tr><td align=center valign=top><font face=Arial color=#000000 size=1>
<b>".YOURVOTE."</b></font></td><td align=center valign=top><font face=Arial color=#000000 size=1>
<b>".OVERALL."</b></font></td><td align=center valign=top><font face=Arial color=#000000 size=1>
<b>".VOTES."</font></b></font></td></tr><tr><td align=center valign=top><font face=Arial color=#000000 size=+1>
<b>$vote</b></font></td><td align=center valign=top><font face=Arial color=red size=+1>
<b>$oldaverage</b></font></td><td align=center valign=top><font face=Arial color=#000000 size=+1><b>
$oldtotal</b></font></td></tr>";
if ($des) $lastpicture.="<tr><td colspan=3 valign=top nowrap><center><font face=Arial color=#000000 size=1><b>".stripslashes($des[$rrate])."</b></font></center></td></tr>";
$lastpicture.="</table>";
}

// pick category option
stripslashes($categories);
if ($pickcat == "radio") {
$pickcat = "<table border=0 cellpadding=1 cellspacing=0 bgcolor=\"#dedede\">";
$pickcat .= "<tr><td nowrap align=right><font size=\"1\">".DISPLAY." <br>";
foreach ($categories as $a) {
$pickcat .= "$a<input type=\"radio\" name=\"c\" value=\"$a\"";
if ($c == $a){$all=1;
$pickcat .= " checked";}
$pickcat .= " onclick=\"this.form.submit()\"><br>";
}
$pickcat .= "</font></td></tr><tr><td nowrap align=right><font size=\"1\">".VIEWALL;
$pickcat .= "<input type=\"radio\" name=\"c\" value=\"all\"";
if (!isset($all)) $pickcat .= " checked";
$pickcat .= " onclick=\"this.form.submit()\"></font></td></tr></table>";
}
else {
$pickcat = "<select name=\"c\" onChange=\"this.form.submit()\">";
foreach ($categories as $a) {
$pickcat .= "<option value=\"$a\"";
if ($c == $a) {$all=1; $pickcat .= " selected";}
$pickcat .= ">$a ".ONLY."</option>";
}
$pickcat .= "<option value=\"all\"";
if (!isset($all)) $pickcat .= " selected";
$pickcat .= ">".VIEWALL."</option>";
$pickcat .= "</select>";}

// select next picture from the database

if (isset($who)) {
$result = mysql_query("SELECT id FROM $imagetable WHERE name = '$who' order by average desc LIMIT 1");
$id = mysql_result($result,0,"id");
}

if ($id > 0){

$result = mysql_query("SELECT * FROM $imagetable WHERE id = '$id' LIMIT 1");
$rowz =mysql_num_rows($result);
if ($rowz < 1) $id = 0;
}

if ($id > 0) {

$query = "SELECT * FROM $imagetable WHERE id = '$id' LIMIT 1";
$counter = 0;
$result = mysql_query("SELECT id FROM $imagetable WHERE status = 'active' order by id DESC");

while ($row = mysql_fetch_array ($result)) {
if ($row["id"] == $id) { break; }
$counter++;
                       }
$rnum = $counter;
}
else {

srand ((double)microtime()*1000000);

if ($c == "all") {

if (($order == "loop")&& (isset($rnum))) {
$result = mysql_query("SELECT id FROM $imagetable WHERE status = 'active' order by id DESC");
$rnum++;
$rowz = mysql_num_rows($result);
if ($rowz < 1) {langerrors();print NOIMAGES; exit;}
          if ($rnum >= $rowz) $rnum = 0;
                                          }
               else           {
               $result = mysql_query("SELECT id FROM $imagetable WHERE status = 'active'");
               $rowz = mysql_numrows($result);

               if ($rowz < 1) {langerrors();print NOIMAGES; exit;}
               $rnum = rand() % $rowz;
                               }
               $query = "SELECT * FROM $imagetable WHERE id = ";
               $query .= mysql_result($result,$rnum,"id");
                       }
else {
// query for specific category
$result = mysql_query("SELECT id FROM $imagetable WHERE category = '$c' AND status = 'active' order by name");
$rowz = mysql_numrows($result);
if ($rowz < 1) {langerrors();print NOIMAGES; exit;}

if (($order == "loop")&& (isset($rnum))) {
$rnum++;
if ($rnum >= $rowz) $rnum = 0;
}
else $rnum = rand() % $rowz;
$query = "SELECT * FROM $imagetable WHERE id = ";
$query .= mysql_result($result,$rnum,"id");
     }

}
$result=mysql_query($query);
$newuser=mysql_fetch_array($result);
$newid =$newuser["id"];
$newmember=$newuser["name"];
$newurl =$newuser["url"];
$category =$newuser["category"];
$resize =$newuser["resize"];

// Loads id # of user's other images in $imgresult
// the next two lines can be removed if you don't use this feature
$imgresult=mysql_query("SELECT id FROM $imagetable WHERE name='$newmember'");
$numimages = mysql_num_rows($imgresult);

$userresult = mysql_query("SELECT * FROM $usertable WHERE name= '$newmember'") or die(mysql_error());
$newinfo=mysql_fetch_array($userresult);

/*  delete this line to add the $homepage variable
$result2 = mysql_query("SELECT homepage FROM $usertable WHERE name= '$newmember'");
$homepage = mysql_result($result2,0,"homepage");
delete this line to add the $homepage variable */

if ($reportauto == "yes") $autoreport = " name=userImage onAbort=\"document.reportForm.submit()\" onError=\"document.reportForm.submit()\"";

if($resize == "yes") $newimage = "<img src=\"$newurl\" width=\"$imgsize\"$autoreport>\n";
else { $newimage = "<img src=\"$newurl\"$autoreport>\n"; }

if($max_width > 0 && (ereg( "[4-9]\.[0-9]\.[5-9].*", phpversion() ) || ereg("[4-9]\.[1-9]\.[0-9].*", phpversion() ) ) ) {
list($image_width,$image_height) = GetImageSize($newurl);
list($displaywidth,$displayheight) = resizepic($image_width,$image_height);
$newimage = "<img src=\"$newurl\" width=\"$displaywidth\" heigth=\"$displayheight\">\n";
}



if ($commentson > 0) {
// get sample comments for user
if ($commentson == 1) $mailresult=mysql_query("SELECT * FROM $commenttable WHERE name='$newid' and status='ok' ORDER BY id DESC LIMIT $samplecomments");
else $mailresult=mysql_query("SELECT * FROM $commenttable WHERE name='$newid' ORDER BY id DESC LIMIT $samplecomments");

$samplecomments = mysql_num_rows($mailresult);

if ($samplecomments == 0) {$samples = NOCOMMENTS;
$samples .= "<br><center><a href=\"javascript:void(0);\" onClick=\"scrollScreen('$mailphp?to=$newid&type=comment&rnum=$rnum&c=$c')\">".POSTCOMMENT."</a>";
}
else {

$samples = VIEWERCOMMENTS.":<br>";
$bgcolor = "#E0E0E0";
$samples.= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n";

while ($row = mysql_fetch_array($mailresult)) {
$datearray[]=$row["datestamp"];
$comid=$row["id"];
$fromuser=$row["fromuser"];
$datestamp=$row["datestamp"];
$commentrate=$row["subject"];
$body=$row["body"];

$samples.= "<tr bgcolor=\"$bgcolor\"><td><font color=\"#000000\" size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">\n";
$samples.= BY.": <a href=\"javascript:void(0);\" onClick=\"scrollScreen('$profilephp?u=$fromuser')\">$fromuser</a>&nbsp;($datestamp)&nbsp;&nbsp;".RATING.": <font color=\"red\">$commentrate";
if (isset($ivadmin) || $fromuser==$logged) $samples .= "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp[<a href=\"javascript:void(0);\" onClick=\"fullScreen('$reportphp?id=$comid&uid=$newid&rnum=$rnum&type=comment')\">".REMOVE."</a>]";
$samples.= "</font><br>$body</font></td></tr>";
if ( $bgcolor == "#E0E0E0") $bgcolor = "#F2F2F2"; else $bgcolor = "#E0E0E0";

}

$samples.= "<tr bgcolor=\"$bgcolor\"><td><font color=\"#000000\" size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">\n";
$samples.= "<center><a href=\"javascript:void(0);\" onClick=\"scrollScreen('$profilephp?u=$newmember&id=$newid&c=$c&rnum=$rnum')\">".VIEWALLCOMS."</a>&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"scrollScreen('$mailphp?to=$newid&type=comment&c=$c&rnum=$rnum')\">".POSTCOMMENT."</a></td></tr>";
$samples.="</table>";
}
}

mysql_close ();  // done with database?  better close it

if ($numimages > 1) { 
$otherpics = "Other pictures for this user ($numimages total): <br>"; 
$i=1; while ($i <= $numimages) { 
$imid = mysql_result($imgresult,$i-1,"id"); 
if ($newid != $imid) $otherpics .= "<a href=\"".$indexphp."?id=".$imid."&c=".$c."&rnum=".$rnum."\">";
$otherpics .= "Pic".$i;
if ($newid != $imid) $otherpics .= "</a>";
$otherpics .= "&nbsp;&nbsp;"; 
$i=$i+1; 
} $otherpics.= "<br><br>"; } 
 

$loginbox = "";
if ($f == "logout") $loginbox =  LOGGEDOUT."<br><br>";
if ((isset ($logged)) && ($f != "logout")) $loginbox = LOGGEDIN." $cookieinfo.<br><br><a href=$gophp?go=userphp>".YOURACCT."</a><br><a href=$votephp?f=logout>".LOGOUT."</a><br>";
else {
$loginbox .= "<a href=\"$gophp?go=signupphp\">".SUBMITPIC."</a><br>";
$loginbox .= "<a href=\"javascript:void(0);\" onClick=\"fullScreen('$loginphp');\">".LOGIN."</a><br>";
$loginbox .= "<a href=\"$gophp?go=userphp\">".YOURACCT."</a><br>";

}
if ((isset ($ivadmin)) && ($f != "logout")) $loginbox .= ADMINLOGGED;
include ("./$template");
exit;
?>
