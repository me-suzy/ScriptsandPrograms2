<?php
/*
Doorway generator
http://www.phpdevs.com/  by vadim@phpdevs.com
*/

if ($_POST['generate']) {
	generate();
}else {
	main_form();
}

/*              **********************                  */

function translate($text) {
	$search = array ("'À'","'Á'","'Â'","'Ã'","'Ä'","'Å'","'¨'","'Æ'","'Ç'",
					"'È'","'É'","'Ê'","'Ë'","'Ì'","'Í'","'Î'","'Ï'","'Ð'",
					"'Ñ'","'Ò'","'Ó'","'Ô'","'Õ'","'Ö'","'×'","'Ø'","'Ù'",
					"'Ú'","'Û'","'Ü'","'Ý'","'Þ'","'ß'","'à'","'á'","'â'",
					"'ã'","'ä'","'å'","'¸'","'æ'","'ç'","'è'","'é'","'ê'",
					"'ë'","'ì'","'í'","'î'","'ï'","'ð'","'ñ'","'ò'","'ó'",
					"'ô'","'õ'","'ö'","'÷'","'ø'","'ù'","'ú'","'û'","'ü'",
					"'ý'","'þ'","'ÿ'","' '","','");

	$replace = array ("a","b","v","g","d","e","e","zh","z",
					"i","j","k","l","m","n","o","p","r",
					"s","t","u","f","h","c","ch","sh","sc",
					"","y","","e","u","ya","a","b","v",
					"g","d","e","e","j","z","i","i","k",
					"l","m","n","o","p","r","s","t","u",
					"f","h","c","ch","sh","sc","","y","",
					"e","u","ya","_","_");
	$text = preg_replace($search, $replace, $text);
	return eregi_replace("[^a-z0-9_]","",strtolower($text));
}

function keywords($txt) {
	$txt=ereg_replace("(\r\n|\r|\n|;|,|'|\")"," ",$txt);
	$a=explode(" ",$txt);
	while(list($k,$v)=each($a)) {
		if (trim(strlen($v)) >= 3 && trim(strlen($v)) <= 50) {
			$out[trim($v)]=trim($v);
		}
	}
	return implode(' ',$out);
}

function generate() {
	$p=array();
	if ($_POST['tpl'] && $_POST['keywords']) {
		$_POST['keywords']=ereg_replace("(\r\n|\r)","\n",stripcslashes($_POST['keywords']));
		$_POST['keywords']=ereg_replace("(,|'|\"|:|;)","",$_POST['keywords']);
		$keys=explode("\n",trim($_POST['keywords']));

		/* create pages array */
		for($i=0;$i<intval($_POST['count']);$i++) {
			list($k,$v)=each($keys);
			if (!$v) {
				reset($keys);
				list($k,$v)=each($keys);
			}
			if (in_array(trim($v),$p)) {
				$v=trim($v).' '.$i;
			}
			$p[]=trim($v);
		}
		

		/* prepare template */
		$tpl=implode('',file('./tpl/'.$_POST['tpl']));

		/* get keyword */
		$keywords=keywords(implode(' ',$keys));

		/* generate links */
		reset($p);
		while(list($k,$v)=each($p)) {
			$name=translate($v);
			$l.='<a href="'.$name.'.html">'.trim($v).'</a> - ';
		}

		/* generate pages */
		reset($p);
		@mkdir('./out/dimg',0777);
		$tfp = @fopen('./db/sample.txt','r') or print('Cannot open ./db/sample.txt ... ignoring {TEXT} <br>');
		while(list($k,$v)=each($p)) {
			$k++;
			$name=trim(translate($v));

			$tpl_out=str_replace('{TITLE}',$v,$tpl);
			$tpl_out=str_replace('{KEYWORDS}',ereg_replace("(\r\n|\n|\r)","",$keywords),$tpl_out);
			$tpl_out=str_replace('{DESC}',ereg_replace("(\r\n|\n|\r)","",implode(' ',$keys)),$tpl_out);
			$tpl_out=str_replace('{LINK}',$l,$tpl_out);
			$tpl_out=str_replace('{I}',$k,$tpl_out);
			$tpl_out=str_replace('{ENTER}',trim($_POST['enter']),$tpl_out);

			@copy('./db/sample.gif','./out/dimg/'.$name.'.gif');
			$tpl_out=str_replace('{IMG}',$name,$tpl_out);

			if ($tfp) {
				$buffer=''; $str='';
				if (feof($tfp)) {fseek($tfp,0);}
				while(trim($str) == '' && $buffer=='') {
					$str = trim(fgets($tfp, 4096));
					if ($str) {$buffer.=$str;}
					if (feof($tfp)) {fseek($tfp,0);}
				}
				$tpl_out=str_replace('{TEXT}',$buffer,$tpl_out);
			}

			$fp=fopen('./out/'.$name.'.html','w');
			fwrite($fp,$tpl_out);
			fclose($fp);
			print 'page created -> '.$name.'.html<br>';
			flush();
		}
		if ($tfp) {fclose($tfp);}

		// generate sitemap
		$fp=fopen('./out/sitemap.html','w');
		fwrite($fp,$l);
		fclose($fp);
		print 'page created -> sitemap.html<br>';
		print '<font color="#FF0000">done.</font>';
	}else {
		print 'Sorry, but I need template and keywords <br>';
	}
}

function main_form() {
	?>
Help: (<a href="readmy-ru.txt">russian</a>)	<br><br>
	<table width="500" border="0" cellspacing="2" cellpadding="0" align="center">
		<form action="" method="post">
		<tr>
			<td width="30%">Pages count:</td>
			<td width="70%"><input type="text" name="count" size="2" maxlength="2" value="5"></td>
		</tr>
		<tr>
			<td>Template:</td>
			<?php
			$a=array();
			if ($handle = opendir('./tpl')) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && is_file('./tpl/'.$file)) {
						$a[]=$file;
					}
				}
				closedir($handle);
			}
			?>
			<td><select name="tpl">
					<?php while(list($k,$v)=each($a)) {?>
					<option value="<?php print $v;?>"><?php print $v;?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Enter page:</td>
			<td><input type="text" name="enter" value="http://" style="width: 90%;"></td>
		</tr>
		<tr>
			<td>Keywords:</td>
			<td>
<textarea cols="30" rows="5" name="keywords" style="width: 90%;">
sample
sample2
sample 2
sample word
</textarea>
			</td>
		</tr>
		<tr><td colspan="2"><input type="submit" name="generate" value="Generate pages"></td></tr>
		</form>
	</table>
	<?php
}

?>