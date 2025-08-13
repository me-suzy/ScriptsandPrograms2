<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <? StyleSheet(); ?>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="struktur">
<table width="195" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F4F5F7">
  <tr>
    <td align="center" width="100%" colspan="2" height="25">[ <a href="dokument.php?mode=new&d4sess=<? echo($sessid); ?>" target="inhalt">Neues 
      Dokument erstellen</a> ]</td>
</tr>
<tr>
<td width="5" bgcolor="#ffffff">&nbsp;</td>
<td bgcolor="#ffffff" valign="top"><br>

<div class="dtree">
<div class="dTreeNode"><img id="id0" src="gfx/tree/base.gif" alt="" /><a class="node" href="dokumente_main.php?d4sess=<?=$sessid;?>" target="inhalt">Dokumente</a></div>

<?
$elem = array();

$a = "/";


function readfiles($a,$v) {
global $elem;

$d = dir(".." . $a);

while ($entry = $d->read()) {
	if ($entry!="." && $entry!="..") {
		if (is_dir(".." . $a . $entry)) {
			
		} else {
			if (substr($entry,strlen($entry)-5)==".html" or substr($entry,strlen($entry)-4)==".htm") {
				$edfunc = "EditDocument";
				$docid = PageID(".." . $a . $entry);
			} else {
				$edfunc = "ShowFile";
				$docid = $a . $entry;
			}
			if ($docid==0) {
				$edfunc = "ShowFile";
				$docid = $a . $entry;
			}			
			$elem[] = array(
				'typ'	=> 'file',
				'titel'	=> "$entry",
				'v'		=> $v,
				'link'  => "javascript:$edfunc('$docid','$sessid');"
			);		
		}
	}
}

$d->close();
}

function readdirs($a,$v) {
global $elem;

$d = dir(".." . $a);

while ($entry = $d->read()) {
	if ($entry!="." && $entry!="..") {
		if (is_dir(".." . $a . $entry)) {
			if ($_REQUEST['a']==$a.$entry."/"  ||  ereg("^" . preg_quote($a . $entry) . "\/", $_REQUEST['a'])) {
				$b = "folderopen";
				$ex = true;
			} else {
				$b = "folder";
				$ex = false;
			}
			$elem[] = array(
				'typ'	=> 'folder',
				'titel'	=> "$entry",
				'v'		=> $v,
				'gra'   => "$b",
				'ex'	=> $ex,
				'link'  => "struktur_dokumente.php?a=$a$entry/&d4sess=$sessid"
			); 
			
			if ($ex) {
				readdirs($a.$entry."/", $v."<img src=\"gfx/tree/line.gif\" border=\"0\">");
				readfiles($a.$entry."/", $v."<img src=\"gfx/tree/line.gif\" border=\"0\">");
			}
		} else {
			
		}
	}
}

$d->close();

return $elem;
}

readdirs($a,"");

readfiles($a, "");

$i = 0;

while (list($key,$val) = each($elem)) {
	$i++;
	
	if ($i==count($elem)) {
		$bt = "bottom";
	} else {
		$bt = "";
	}
	
	if ($val['typ']=="folder") {
		?>
<div class="clip" style="display:block;"><div class="dTreeNode"><?=$val['v'];?><img src="gfx/tree/join<?=$bt;?>.gif" alt="" /><img src="gfx/tree/<?=$val['gra'];?>.gif" alt="" /><a class="node" href="<?=$val['link'];?>"><?=$val['titel'];?></a></div>
<?
	} else {
		?>
<div class="clip" style="display:block;"><div class="dTreeNode"><?=$val['v'];?><img src="gfx/tree/join<?=$bt;?>.gif" alt="" /><img src="gfx/tree/page.gif" alt="" /><a class="node" href="<?=$val['link'];?>"><?=$val['titel'];?></a></div>
<?
	}
}
?>

</div>

</td>
</tr>
</table>
</body>
</html>