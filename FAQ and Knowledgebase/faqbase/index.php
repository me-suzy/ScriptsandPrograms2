<?
include "./faq-config.php";

$sql = "select * from $faqcats order by id";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$catid = $resrow[0];
	$cat = $resrow[1];
	$faqcathtml .= "<a href='".$mainfile."?cid=$catid'><font face='$fontname' size='$fontsize' color='$textcolor'>$cat</font></a><br>";
}
if ($ckAdminPass==$adminpass) $faqcathtml .= "<br><a href='#' onClick=\"javascript:window.open('".$adminfile."?editcats=1','pop_admin','height=330,width=530,top=0,left=0,resizable=no,scrollbars=yes');\"><font face='$fontname' size='$fontsize' color='$textcolor'><b>Add/Edit Categories</b></font></a>";
$faqcathtml = "<table width='$tablewidth' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' bordercolor='$bordercolor' align='center'><tr><td bgcolor='$bgcolor'><font size='$fontsize' face='$fontname' color='$textcolor'><b>$faq_title &gt; Categories</b></font></td></tr><tr><td bgcolor='$bgcolor'><blockquote><p><font color='$textcolor' size='$fontsize' face='$fontname'>$faqcathtml</font></p></blockquote></td></tr></table><br>";


if ($cid){
	$sql = "select cat from $faqcats where id='$cid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$cattext = $resrow[0];
	$sql = "select * from $table where catid='$cid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	$faq_title .= " &gt; $cattext";
	for($x=0;$x<$numrows;$x++){
		$resrow = mysql_fetch_row($result);
		$faqid = $resrow[0];
		$catid = $resrow[1];
		$question = $resrow[2];
		$answer = $resrow[3];
		$added = $resrow[4];
		$question = stripslashes($question);
		$answer = stripslashes($answer);
		$dta = explode(" ", $added);
		$added = $dta[0];
		$queslist .= "<a href='#faq".$faqid."'><font face='$fontname' size='$fontsize' color='$textcolor'>$question</font></a><br>";
		if ($ckAdminPass==$adminpass) $adminlinks = "<a href='#' onClick=\"javascript:window.open('".$adminfile."?editentry=$faqid','pop_admin','height=330,width=530,top=0,left=0,resizable=no,scrollbars=yes');\"><font face='$fontname' size='$fontsize' color='$textcolor'><b>(Edit)</b></font></a> <a href='#' onClick=\"javascript:window.open('".$adminfile."?deleteentry=$faqid','pop_admin','height=20,width=50,top=0,left=0,resizable=no,scrollbars=yes');\"><font face='$fontname' size='$fontsize' color='$textcolor'><b>(Delete)</b></font></a>";
		$faqlist .= "<a name='faq".$faqid."'></a><table width='$tablewidth' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' bordercolor='$bordercolor' align='center'><tr><td bgcolor='$bgcolor'><font size='$fontsize' face='$fontname' color='$textcolor'><b>$question</b> $adminlinks</font><font face='$fontname' size='-2' color='$textcolor'><i>($added)</i></font></td></tr><tr><td bgcolor='$bgcolor'><blockquote><p><font color='$textcolor' size='$fontsize' face='$fontname'>$answer</font></p></blockquote></td></tr></table><br>";
	}
	if ($ckAdminPass==$adminpass) $adminlinks = "<a href='#' onClick=\"javascript:window.open('".$adminfile."?newentry=$cid','pop_admin','height=330,width=530,top=0,left=0,resizable=no,scrollbars=yes');\"><font face='$fontname' size='$fontsize' color='$textcolor'><b>(Add New Entry)</b></font></a>";
	$queshtml = "<table width='$tablewidth' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' bordercolor='$bordercolor' align='center'><tr><td bgcolor='$bgcolor'><font size='$fontsize' face='$fontname' color='$textcolor'><b>$faq_title</b> $adminlinks</font></td></tr><tr><td bgcolor='$bgcolor'><blockquote><p><font color='$textcolor' size='$fontsize' face='$fontname'>$queslist</font></p></blockquote></td></tr></table><br><br>";
}

if ($search){
	$sql = "select * from $table where question rlike '$search' or answer rlike '$search'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	for($x=0;$x<$numrows;$x++){
		$resrow = mysql_fetch_row($result);
		$faqid = $resrow[0];
		$catid = $resrow[1];
		$question = $resrow[2];
		$answer = $resrow[3];
		$added = $resrow[4];
		$question = stripslashes($question);
		$answer = stripslashes($answer);
		$dta = explode(" ", $added);
		$added = $dta[0];
		$queslist .= "<a href='#faq".$faqid."'><font face='$fontname' size='$fontsize' color='$textcolor'>$question</font></a><br>";
		if ($ckAdminPass==$adminpass) $adminlinks = "<a href='#' onClick=\"javascript:window.open('".$adminfile."?editentry=$faqid','pop_admin','height=330,width=530,top=0,left=0,resizable=no,scrollbars=yes');\"><font face='$fontname' size='$fontsize' color='$textcolor'><b>(Edit)</b></font></a> <a href='#' onClick=\"javascript:window.open('".$adminfile."?deleteentry=$faqid','pop_admin','height=20,width=50,top=0,left=0,resizable=no,scrollbars=yes');\"><font face='$fontname' size='$fontsize' color='$textcolor'><b>(Delete)</b></font></a>";
		$faqlist .= "<a name='faq".$faqid."'></a><table width='$tablewidth' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' bordercolor='$bordercolor' align='center'><tr><td bgcolor='$bgcolor'><font size='$fontsize' face='$fontname' color='$textcolor'><b>$question</b> $adminlinks</font><font face='$fontname' size='-2' color='$textcolor'><i>($added)</i></font></td></tr><tr><td bgcolor='$bgcolor'><blockquote><p><font color='$textcolor' size='$fontsize' face='$fontname'>$answer</font></p></blockquote></td></tr></table><br>";
	}
	$queshtml = "<table width='$tablewidth' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' bordercolor='$bordercolor' align='center'><tr><td bgcolor='$bgcolor'><font size='$fontsize' face='$fontname' color='$textcolor'><b>$faq_title</b> Search Results</font></td></tr><tr><td bgcolor='$bgcolor'><blockquote><p><font color='$textcolor' size='$fontsize' face='$fontname'>$queslist</font></p></blockquote></td></tr></table><br><br>";
}
$pagetitle = $faq_title;
if ($headerfile) include $headerfile;
print "<form name='form2' method='post' action='$mainfile'><table width='340' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' bordercolor='$bordercolor' align='center'><tr><td bgcolor='$bgcolor'><div align='center'><font size='$fontsize' face='$fontname' color='$textcolor'><b>Search the FAQ: <input type='text' name='search'><input type='submit' value='Search'></b></font></div></td></tr></table></form>";
print $faqcathtml;
print $queshtml;
print $faqlist;
print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>FAQBase</a></center>";
if ($footerfile) include $footerfile;
?>