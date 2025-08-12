<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################


include("./common.php");

switch ($HTTP_GET_VARS[action]) {
case 'fix_ads_home'			: fix_ads_home();
case 'fix_ads'				: fix_ads($HTTP_GET_VARS[size]);
case 'default_ads_home'		: default_ads_home();
case 'default_ad_add'		: default_ad_add($HTTP_GET_VARS);
case 'default_ad_edit'		: default_ad_edit($HTTP_GET_VARS);
}
switch ($HTTP_POST_VARS[action]) {
case 'default_ad_added' 	: default_ad_added($HTTP_POST_VARS);
case 'default_ad_edit'		: default_ad_edit($HTTP_POST_VARS);
case 'default_ad_edited'	: default_ad_edited($HTTP_POST_VARS);
case 'default_ad_disable'	: default_ad_disable($HTTP_POST_VARS[number]);
case 'default_ad_enable'	: default_ad_enable($HTTP_POST_VARS[number]);
case 'default_ad_delete'	: default_ad_delete($HTTP_POST_VARS);
}


#################################################################################
#################################################################################
#################################################################################

function default_ads_home() {
global $s;
check_session ('tmpl_msg');
include('./_head.txt');
echo $s[info];
echo iot('Default Ads');
echo '<span class="text13">These ads are displayed if no one account has available credits in the necessary category<br></span><br>';

for ($x=1;$x<=3;$x++)
{ $width = $s["w$x"]; $height = $s["h$x"];
  echo '<table border="0" width="600" cellspacing="2" cellpadding="4" class="table1"><tr><td align="center">
  <table border="0" width="500" cellspacing="0" cellpadding="2">
  <tr><td colspan=4 align="center" nowrap><span class="text13blue"><b>Size '.$x.' (width '.$width.' px, height '.$height.' px)</b></span></td></tr>';
  $q = dq("select number,description,enable from $s[pr]def_ads where size = '$x'",1);
  while ($data = mysql_fetch_assoc($q))
  { if ($data[enable]) { $action = 'default_ad_disable'; $text = 'Disable this ad'; }
    else  { $action = 'default_ad_enable'; $text = 'Enable this ad'; }
    $enablebutton = "<form method=\"POST\" action=\"t_ads.php\">
    <input type=\"hidden\" name=\"action\" value=\"$action\">
    <input type=\"hidden\" name=\"size\" value=\"$x\">
    <input type=\"hidden\" name=\"number\" value=\"$data[number]\">
    <td align=\"center\"><input type=\"submit\" value=\"$text\" name=\"D\" class=\"button1\" style=\"width:90\"></td>
    </form>";
    echo "
    <tr><td align=\"left\" nowrap><span class=\"text13\">$data[description]</span></td>
    <form method=\"POST\" action=\"t_ads.php\">
    <input type=\"hidden\" name=\"action\" value=\"default_ad_edit\">
    <input type=\"hidden\" name=\"size\" value=\"$x\">
    <input type=\"hidden\" name=\"number\" value=\"$data[number]\">
    <td align=\"center\"><input type=\"submit\" value=\"Edit this ad\" name=\"D\" class=\"button1\" style=\"width:90\"></td>
    </form>
    $enablebutton
    <form method=\"POST\" action=\"t_ads.php\">
    <input type=\"hidden\" name=\"action\" value=\"default_ad_delete\">
    <input type=\"hidden\" name=\"size\" value=\"$x\">
    <input type=\"hidden\" name=\"number\" value=\"$data[number]\">
    <td align=\"center\"><input type=\"submit\" value=\"Delete this ad\" name=\"D\" class=\"button1\" style=\"width:90\"></td>
    </form></tr>";
  }
  echo '<tr><td colspan=4 align="center" nowrap><a href="t_ads.php?action=default_ad_add&size='.$x.'">Click here to create a new ad</a></td></tr>
  </table></td></tr></table><br>';
}
include('./_footer.txt');
exit;
}


#################################################################################

function default_ad_add($a) {
global $s;
check_session ('tmpl_msg');
include('./_head.txt');
echo $s[info];
echo iot('Create New Default Ads for Size '.$a[size].' (width '.$s["w$a[size]"].'px, height '.$s["h$a[size]"].'px)');
$a[action] = 'default_ad_added'; default_ad_edit_table($a);
echo '<a href="t_ads.php?action=default_ads_home">Back to previous screen</a><br><br>';
include('./_footer.txt');
exit;
}

#################################################################################

function default_ad_added($a) {
global $s;
check_session ('tmpl_msg');

$a[action] = 'default_ad_add'; $a = check_default_ad($a);
$size = $a[size];
dq("insert into $s[pr]def_ads values(NULL,'$size','$a[description]','1','$a[c0]','$a[c1]','$a[c2]','$a[c3]','$a[c4]','$a[c5]','$a[complete_ad_1]','$a[complete_ad_2]','$a[complete_ad_3]')",1);
$a[number] = mysql_insert_id();
dq("insert into $s[pr]link$size values(0,0,0,'$a[url1]','$a[banner1]','$a[alt1]','$a[raw1]','$a[ad_kind_1]','$a[url2]','$a[banner2]','$a[alt2]','$a[raw2]','$a[ad_kind_2]','$a[url3]','$a[banner3]','$a[alt3]','$a[raw3]','$a[ad_kind_3]',0,'$a[number]')",1);
$s[info] = iot('New default ads have been created. You can view it below.');
default_ad_edit($a);
exit;
}

#################################################################################

function default_ad_edit($a) {
global $s;
check_session ('tmpl_msg');
include('./_head.txt');
$q = dq("select * from $s[pr]def_ads where number = '$a[number]'",1);
$a = mysql_fetch_assoc($q); $size = $a[size];
for ($x=1;$x<=3;$x++)
{ if (!$a["ad$x"]) $ads .= "Ad #$x not set<br>";
  else $ads .= "Current ad #$x<br>" .unreplace_once_html($a["ad$x"]).'<br><br>';
}
$q = dq("select * from $s[pr]link$size where def_number = '$a[number]'",1);
$link = mysql_fetch_assoc($q);
//foreach ($link as $k => $v) $link[$k] = stripslashes($v);
for ($x=1;$x<=3;$x++) $link["raw$x"] = htmlspecialchars(unreplace_once_html($link["raw$x"]));

$a = array_merge($link,$a);
echo $s[info];
echo iot('Default Ads for Size '.$a[size].' (width '.$s["w$a[size]"].'px, height '.$s["h$a[size]"].'px)');
$a[action] = 'default_ad_edited'; default_ad_edit_table($a);
echo '<table border="0" cellspacing="2" cellpadding="4" class="table1" width="620"><tr><td align="center">
<span class="text13blue"><b>Current ads</b></span><br><br><span class="text13">';
echo $ads;
echo '</span></td></tr></table><br><a href="t_ads.php?action=default_ads_home">Back to previous screen</a><br><br>';
include('./_footer.txt');
exit;
}

#################################################################################

function default_ad_edited($a) {
global $s;
check_session ('tmpl_msg');
$size = $a[size];
$a[action] = 'default_ad_edit'; $a = check_default_ad($a);
//echo "update $s[pr]def_ads set  description = '$a[description]',ad1 = '$a[complete_ad_1]',ad2 = '$a[complete_ad_2]',ad3 = '$a[complete_ad_3]',
 //c0 = '$a[c0]', c1 = '$a[c1]', c2 = '$a[c2]', c3 = '$a[c3]', c4 = '$a[c4]',c5 = '$a[c5]'  where number = '$a[number]'";
$q = dq("update $s[pr]def_ads set description = '$a[description]',ad1 = '$a[complete_ad_1]',ad2 = '$a[complete_ad_2]',ad3 = '$a[complete_ad_3]',
 c0 = '$a[c0]', c1 = '$a[c1]', c2 = '$a[c2]', c3 = '$a[c3]', c4 = '$a[c4]',c5 = '$a[c5]'  where number = '$a[number]'",1);

//echo "update $s[pr]link$size set url1 = '$a[url1]', banner1 = '$a[banner1]', alt1 = '$a[alt1]', raw1 = '$a[raw1]', ad_kind_1 = '$a[ad_kind_1]',
//url2 = '$a[url2]', banner2 = '$a[banner2]', alt2 = '$a[alt2]', raw2 = '$a[raw2]', ad_kind_2 = '$a[ad_kind_2]',
//url3 = '$a[url3]', banner3 = '$a[banner3]', alt3 = '$a[alt3]', raw3 = '$a[raw3]', ad_kind_3 = '$a[ad_kind_3]'  
 //where def_number = '$a[number]'"; exit;
$q = dq("update $s[pr]link$size set url1 = '$a[url1]', banner1 = '$a[banner1]', alt1 = '$a[alt1]', raw1 = '$a[raw1]', ad_kind_1 = '$a[ad_kind_1]',
url2 = '$a[url2]', banner2 = '$a[banner2]', alt2 = '$a[alt2]', raw2 = '$a[raw2]', ad_kind_2 = '$a[ad_kind_2]',
url3 = '$a[url3]', banner3 = '$a[banner3]', alt3 = '$a[alt3]', raw3 = '$a[raw3]', ad_kind_3 = '$a[ad_kind_3]'  
 where def_number = '$a[number]'",1);

$s[info] = iot('Your data have been saved');
default_ad_edit($a);
exit;
}

#################################################################################

function default_ad_enable($number) {
global $s;
check_session ('tmpl_msg');
dq("update $s[pr]def_ads set enable = 1 where number = '$number'",1);
$s[info] = iot('Selected default ad has been enabled');
default_ads_home();
}

function default_ad_disable($number) {
global $s;
check_session ('tmpl_msg');
dq("update $s[pr]def_ads set enable = 0 where number = '$number'",1);
$s[info] = iot('Selected default ad has been disabled');
default_ads_home();
}

function default_ad_delete($in) {
global $s;
check_session ('tmpl_msg');
$size = $in[size];
dq("delete from $s[pr]def_ads where number = '$in[number]'",1);
dq("delete from $s[pr]link$size where def_number = '$in[number]'",1);
$s[info] = iot('Selected default ad has been deleted');
default_ads_home();
}

#################################################################################

function default_ad_edit_table($a) {
// pomocna funkce
global $s;
if ($s["usecats$a[size]"])
{ $q = dq("select catid,catname from $s[pr]categories where size = '$a[size]'",1);
  while ($cats=mysql_fetch_array($q)) $c[$cats[0]]=$cats[1];
  $categories = '<SELECT class="field1" name="categories[]" size=5 multiple>';
  if ($a[c0]==1) $selected=' selected'; else $selected='';
  $categories .= '<option value="a"'.$selected.'>All</option>';
  if ($a[c0]==9) $selected=' selected'; else $selected='';
  $categories .= '<option value="na"'.$selected.'>Use it for non-existing accounts only*</option>';
  foreach ($c as $k => $v)
  { if ($a[c0]) $selected='';
    elseif (($a[c1]==$k) OR ($a[c2]==$k) OR ($a[c3]==$k) OR ($a[c4]==$k) OR ($a[c5]==$k)) $selected=' selected';
    else $selected='';
    $categories .= "<option value=\"$k\"$selected>$v</option>\n";
  }
  $categories .= '</select>';
}
else $categories = '<span class="text13">N/A</span>';

//foreach ($a as $k => $v) $a[$k] = stripslashes($v);

echo '<span class="text13">You can set up maximum of 3 rotating ads for each category. If you enter only 1 or 2 ads, the system completes the blank item(s) automatically.<br></span>
<span class="text10">Note: Make sure you have only one set of enabled ads for each category. For example if you will set up one set of default ads for categories Music, Art and MP3 and then you will set up a new set of default ads for categories Computer and Art, you should edit the first set of default ads in order to don\'t use it for category Art as there is another default ad for this category.<br></span><br>';
?>
<table border="0" cellspacing="2" cellpadding="4" class="table1" width="620">
<form METHOD="post" action="t_ads.php">
<input type="hidden" name="number" value="<?PHP echo $a[number]; ?>">
<input type="hidden" name="size" value="<?PHP echo $a[size]; ?>">
<input type="hidden" name="action" value="<?PHP echo $a[action]; ?>">
<tr>
<td align="left" nowrap><span class="text13">Description (for your reference)</span></td>
<td align="left" nowrap><INPUT class="field1" size="60" maxlength="60" name="description" value="<?PHP echo $a[description]; ?>"></td>
</tr>
<tr>
<td align="left" valign="top"><span class="text13">Categories where should be these ads displayed<br></span><span class="text10">Select "All" or "Use it for non-existing accounts" or maximum of 5 individual categories</span></td>
<td align="left" nowrap><?PHP echo $categories; ?></td>
</tr>
<?PHP
for ($x=1;$x<=3;$x++)
{ ?>
  <tr>
  <td align="center" nowrap colspan="2"><span class="text13blue"><b>Ad #<?PHP echo $x ?></b></span></td>
  </tr>
  <TR>
  <TD align="left" nowrap colspan="2"><span class="text13">
  <input type="radio" name="ad_kind_<?PHP echo $x ?>" value="picture"<?PHP if ($a["ad_kind_$x"]=='picture') echo ' checked' ?>> Use classic banner - picture</span>
  </TD></TR>
  <tr>
  <td align="left" nowrap><span class="text13">URL </span></td>
  <td align="left" nowrap><INPUT class="field1" size="60" maxlength="255" name="url<?PHP echo $x ?>" value="<?PHP echo $a["url$x"]; ?>"></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Banner </span></td>
  <td align="left" nowrap><INPUT class="field1" size="60" maxlength="255" name="banner<?PHP echo $x ?>" value="<?PHP echo $a["banner$x"] ?>"></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Alt tag</span></td>
  <td align="left" nowrap><INPUT class="field1" size="60" maxlength="255" name="alt<?PHP echo $x ?>" value="<?PHP echo $a["alt$x"] ?>"></td>
  </tr>
  <tr>
  <td align="left" colspan="2"><span class="text13"><input type="radio" name="ad_kind_<?PHP echo $x ?>" value="raw_html"<?PHP if ($a["ad_kind_$x"]=='raw_html') echo ' checked' ?>> Use the raw HTML code below to create a banner</span>
  <textarea class="field1" name="raw<?PHP echo $x ?>" rows="15" cols="95"><?PHP echo $a["raw$x"] ?></textarea></td>
  </tr>
  <?PHP
}
?>
<tr>
<td align="center" nowrap colspan="2">
<input type="submit" name="co" value="Save" class="button1"></td>
</td></tr>
<tr><td align="center" colspan="2"><span class="text10">* If this is selected, the ad will be used if the user who send the hit does not exist (probably the member was deleted by admin)</span></td></tr>
</form></table><br>
<?PHP
}

#################################################################################

function check_default_ad($a) {
// pomocna funkce
global $s;

for ($x=1;$x<=3;$x++) $b["raw$x"] = replace_once_html($a["raw$x"]);
$a = replace_array_text($a);
$a = array_merge($a,$b); // musi se takto protoze se to $a vraci zpatky jako pole

if ($a[categories])
{ if ($a[categories][0]=='a') $a[c0] = 1;
  elseif ($a[categories][0]=='na') $a[c0] = 9;
  else { $a[c0] = 0; $a[c1]=$a[categories][0]; $a[c2]=$a[categories][1]; $a[c3]=$a[categories][2]; $a[c4]=$a[categories][3]; $a[c5]=$a[categories][4]; }
}
else $a[c0] = 1;
for ($x=1;$x<=3;$x++)
{ $y = get_complete_html($a[size],0,$x,$a["banner$x"],$a["url$x"],$a["alt$x"],$a["raw$x"],$a["ad_kind_$x"]);
  if (trim($y[1])) $a["complete_ad_$x"] = $y[0].'<_>'.$y[1];
  else $a["complete_ad_$x"] = $y[0];
}
// pokud nejsou vsechny tri tak doplnit
for ($x=1;$x<=3;$x++) { if (($a["complete_ad_$x"]) AND (!$complete_ad)) $complete_ad = $a["complete_ad_$x"]; }
for ($x=1;$x<=3;$x++) { if (!$a["complete_ad_$x"]) $a["complete_ad_$x"] = $complete_ad; }
return $a;
}

#################################################################################
#################################################################################
#################################################################################

function fix_ads_home() {
global $s;
include('./_head.txt');
echo $s[info];
echo iot('Fix Ads');
?>
<table border="0" cellspacing="10" cellpadding="2" class="table1" width="600"><tr><td align="center">
<form method="get" action="t_ads.php">
<input type="hidden" name="action" value="fix_ads">
<span class="text13">This function must be used if you have modified some items marked by * in the Configuration. It fixes all ads of all members and also all default ads, so these will correspond to the actual settings.</span></td></tr>
<tr><td align="center" nowrap><span class="text13">
Select the ad size which should be fixed:<br>
<input type="radio" name="size" value="1" checked> 1 &nbsp;&nbsp;
<input type="radio" name="size" value="2"> 2 &nbsp;&nbsp;
<input type="radio" name="size" value="3"> 3
</td></tr><tr><td align="center" nowrap>
<input type="submit" name="A1" value="Submit" class="button1"><br>
</td></tr></form></table><br>
<?PHP
include('./_footer.txt');
exit;
}

#################################################################################

function fix_ads($size) {
global $s;
$q = dq("select * from $s[pr]link$size where number > 0",1);
while ($r = mysql_fetch_assoc($q))
{ for ($x=1;$x<=3;$x++)
  { $y = get_complete_html($size,$r[number],$x,$r["banner$x"],$r["url$x"],$r["alt$x"],$r["raw$x"],$r["ad_kind_$x"]);
    $a["htmla$x"] = $y[0]; $a["htmlb$x"] = $y[1];
  }
  dq("update $s[pr]stats$size set 
  linka1 = '$a[htmla1]', linkb1 = '$a[htmlb1]', linka2 = '$a[htmla2]', linkb2 = '$a[htmlb2]', linka3 = '$a[htmla3]', linkb3 = '$a[htmlb3]'
  where number = '$r[number]'",1);
}
$q = dq("select * from $s[pr]link$size where number = 0",1);
while ($r = mysql_fetch_assoc($q))
{ for ($x=1;$x<=3;$x++)
  { $y = get_complete_html($size,$r[def_number],$x,$r["banner$x"],$r["url$x"],$r["alt$x"],$r["raw$x"],$r["ad_kind_$x"]);
    if (trim($y[1])) $ad[$x] = $y[0].'<_>'.$y[1];
    else $ad[$x] = $y[0];
  }
  unset($complete_ad);
  for ($x=1;$x<=3;$x++) { if (($ad[$x]) AND (!$complete_ad)) $complete_ad = $ad[$x]; }
  for ($x=1;$x<=3;$x++) { if (!$ad[$x]) $ad[$x] = $complete_ad; }
  dq("update $s[pr]def_ads set ad1='$ad[1]',ad2='$ad[2]',ad3='$ad[3]' where number = '$r[def_number]'",1);
}
$s[info] = iot('Ads of exchange size '.$size.' fixed successfully');
fix_ads_home();
}

#################################################################################
#################################################################################
#################################################################################

?>