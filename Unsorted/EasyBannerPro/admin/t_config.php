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
case 'blacklist'			: blacklist('');
case 'messages_edit'		: messages_edit();
case 'templates_home'		: templates_home();
case 'moderators_home'		: moderators_home();
}
switch ($HTTP_POST_VARS[action]) {
case 'blacklist'			: blacklist($HTTP_POST_VARS);
case 'messages_edited'		: messages_edited($HTTP_POST_VARS);
case 'template_edit'		: template_edit($HTTP_POST_VARS[template]);
case 'template_edited'		: template_edited($HTTP_POST_VARS);
}

#################################################################################
#################################################################################
#################################################################################

function messages_edit() {
global $s;
check_session ('tmpl_msg');
$template = "$s[phppath]/data/messages.php";
$m='';
$m[info] = $s[info];
include('./_head.txt');
include($template);
parse_messages_template("$template.html",$m);
include('./_footer.txt');
}

#################################################################################

function parse_messages_template($template,$value) {
global $s;
$fh = fopen($template,'r') or problem("Cannot read from file $template");
while (!feof($fh)) $line .= fgets ($fh,4096);
fclose($fh);
reset ($value);
while (list($k,$v) = each($value)) $line = str_replace("#%$k%#",$v,$line);
$line = eregi_replace("#%[a-z0-9_]*%#",'',$line);
echo stripslashes($line);
exit;
}

#########################################################################

function messages_edited($a) {
global $s;
check_session ('tmpl_msg');
$m='';
unset($a[D1],$a[action]);
$a = replace_array_text($a);
if (!$file = fopen ("$s[phppath]/data/messages.php","w")) problem("Cannot write to file $s[phppath]/data/messages.php.");
$data = fwrite ($file,"<?PHP\n\n");
foreach ($a as $k => $v)
$data = fwrite ($file,"\$m[$k] = '$v';\n");
$data = fwrite ($file,"\n?>");
fclose($file);
if (!$data)
$s[info] = iot('Can not write to file "'.$s[phppath]/data/messages.php.'".<br>Make sure that your "data/messages" directory exists and has 777 permission and the file "messages.php" inside has permission 666. Can\'t continue.');
else
$s[info] = iot('Your messages have been updated.');
messages_edit();
exit;
}

#################################################################################
#################################################################################
#################################################################################

function templates_home() {
global $s;
check_session ('tmpl_msg');
$dr = opendir("$s[phppath]/data/templates");
rewinddir($dr);
while ($q = readdir($dr))
{ if (($q != '.') AND ($q != '..') AND (is_file("$s[phppath]/data/templates/$q")))
  if (eregi(".*txt$",$q)) $txt[] = $q;
  else $html[] = $q;
}
closedir ($dr);
sort($txt); sort($html);
foreach ($txt as $k => $v) $txt_list .= "<input type=\"radio\" name=\"template\" value=\"templates/$v\">$v<br>\n";
foreach ($html as $k => $v) $html_list .= "<input type=\"radio\" name=\"template\" value=\"templates/$v\">$v<br>\n";

/*unset($q,$pole);
$dr = opendir("$s[phppath]/data/ads");
rewinddir($dr);
while ($q = readdir($dr))
{ if (($q != '.') AND ($q != '..') AND (is_file("$s[phppath]/data/ads/$q")))
  $pole[] = $q;
}
sort($pole);
foreach ($pole as $k => $v) $list1 .= "<input type=\"radio\" name=\"template\" value=\"ads/$v\">$v<br>\n";
closedir ($dr);*/

include('./_head.txt');
echo iot('Edit Templates');
echo $s[info];
?>
<table border="0" cellspacing="0" cellpadding="2"><tr>
<td align="right">
<table border="0" cellspacing="20" cellpadding="2" class="table1">
<form method="POST" action="t_config.php">
<input type="hidden" name="action" value="template_edit">
<tr><td align="left" nowrap>
<?PHP echo iot('HTML format'); ?>
<span class="text13">
<?PHP echo $html_list ?>
</td></tr></table>
</td><td width="20">&nbsp;</td><td align="left" valign="top">
<table border="0" cellspacing="10" cellpadding="2" class="table1">
<form method="POST" action="t_config.php">
<input type="hidden" name="action" value="template_edit">
<tr><td align="left" valign="top" nowrap>
<?PHP echo iot('TXT format'); ?>
<span class="text13">
<?PHP echo $txt_list ?>
</td></tr></table>
</td></tr><tr><td colspan="3" align="center">
<br><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1">
</td></tr></form></table><br>
<?PHP
include('./_footer.txt');
exit;
}

########################################################################

function template_edit($template) {
global $s;
check_session ('tmpl_msg');
if (!$template) templates_home();
$filename = "$s[phppath]/data/$template";
$fd = fopen($filename,'r');
$ct = fread ($fd, filesize ($filename));
fclose ($fd);
$ct = htmlspecialchars(stripslashes($ct));
$template_name = str_replace('templates/','',str_replace('ads/','',$template));
include('./_head.txt');
echo $info;
echo iot('Edit template "'.$template_name.'"');
?>
<table border="0" cellspacing="10" cellpadding="2" class="table1">
<form method="POST" action="t_config.php">
<input type="hidden" name="action" value="template_edited">
<input type="hidden" name="template" value="<?PHP echo $template; ?>">
<tr><td align="left" nowrap><span class="text13">
<textarea name="html" rows="25" cols="95" class="field1"><?PHP echo $ct; ?></textarea>
</td></tr>
<tr><td align="center" nowrap>
<input type="submit" name="A1" value="Save" class="button1"><br>
</td></tr></form></table><br>
<?PHP
include('./_footer.txt');
exit;
}

########################################################################

function template_edited($a) {
global $s;
check_session ('tmpl_msg');
if (!$a[template]) templates_home();
$filename = "$s[phppath]/data/$a[template]";
//$a[html] = stripslashes($a[html]);
if (!$file = fopen($filename,'w')) problem("Cannot write to file '$filename'.");
$zapis = fwrite ($file,stripslashes($a[html]));
fclose($file);
if (!$zapis)
$s[info] = iot('Can\'t write to file "'.$filename.'".<br>Make sure that your templates directory exists and has 777 permission and the file "'.$a[template].'" inside has permission 666. Can\'t continue.');
else
{ $template_name = str_replace('templates/','',str_replace('ads/','',$a[template]));
  $s[info] = iot('Template "'.$template_name.'" has been updated.');
}
template_edit($a[template]);
exit;
}

#########################################################################
#########################################################################
#########################################################################

function blacklist($a) {
global $s;
check_session ('blacklist');
if ($a[addremove])
{ if ($a[addremove] == 'add') 
  { dq("insert into $s[pr]blacklist values('$a[domain]')",1); $i = iot('Domain '.$a[domain].' has been added to the blacklist'); }
  if ($a[addremove] == 'remove') 
  { dq("delete from $s[pr]blacklist where url like '$a[domain]'",1); $i = iot('Domain '.$a[domain].' has been removed from the blacklist'); }
}
$q = dq("select * from $s[pr]blacklist",0);
include('./_head.txt');
echo $i;
echo iot('Blacklist');
?>
<span class="text13">Domains listed here can be used in URL of a link by no one user.</span><br><br>
<table border="0" width="200" cellspacing="1" cellpadding="2" class="table1"><tr>
<td align="center"><span class="text13blue"><b>Domains on the blacklist</b></span></td></tr>
<?PHP
while ($zaznam = mysql_fetch_row($q))
echo '<tr><td align="center"><span class="text13">'.$zaznam[0].'</span></td></tr>';
?>
</table><br>
<table border="0" width="200" cellspacing="8" cellpadding="2" class="table1">
<form action="t_config.php" method="post">
<input type="hidden" name="action" value="blacklist">
<tr>
<td align="center"><span class="text13blue"><b>Update your blacklist</b></span></td></tr>
<tr><td align="center"><input class="field1" type="text" size="50" name="domain"></td></tr>
<tr><td align="left" nowrap><span class="text13">
<input type="radio" name="addremove" value="add" checked> Add this domain to the blacklist<br>
<input type="radio" name="addremove" value="remove"> Remove this domain from the blacklist
</span></td></tr>
<tr><td align="center"><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</table></form></center></div>
<?PHP
include('./_footer.txt');
exit;
}

#################################################################################
#################################################################################
#################################################################################

?>