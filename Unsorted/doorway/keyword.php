<?php
/*
Keyword analizer
http://www.phpdevs.com/  by vadim@phpdevs.com
*/

if ($_POST['generate']) {
	generate();
}else {
	main_form();
}

function generate() {
	$_POST['keywords']=ereg_replace("(\r\n|\r|\n|;|,|[0123456789])"," ",$_POST['keywords']);
	$a=explode(" ",$_POST['keywords']);
	while(list($k,$v)=each($a)) {
		if (trim(strlen($v)) >= $_POST['min'] && trim(strlen($v)) <= $_POST['max']) {
			$out[trim($v)]=trim($v);
		}
	}
	print implode(' ',$out);
}

function main_form() {
	?>
	Ñêîðìèòå ìíå ñïèñîê ñëîâ, è ÿ âûáåðó èç íåãî òîëüêî óíèêàëüíûå. Âñå çàïÿòûå è öèôðû áóäóò âûðåçàííû.<br>
	<table width="400" border="0" cellspacing="2" cellpadding="0" align="center">
		<form action="" method="post">
		<tr>
			<td>Min. keyword size:</td>
			<td><input type="text" name="min" size="2" maxlength="2" value="3"></td>
		</tr>
		<tr>
			<td>Max. keyword size:</td>
			<td><input type="text" name="max" size="2" maxlength="2" value="50"></td>
		</tr>
		<tr>
			<td>Keywords:</td>
			<td> <textarea cols="30" rows="5" name="keywords">sample sample2 sample 2 sample word</textarea> </td>
		</tr>
		<tr><td colspan="2"><input type="submit" name="generate" value="Generate pages"></td></tr>
		</form>
	</table>
	<?php
}

?>