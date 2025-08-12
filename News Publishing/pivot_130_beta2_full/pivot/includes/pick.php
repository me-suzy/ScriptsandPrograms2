<?php


chdir('../');
include ('pv_core.php');
CheckLogin();
Setpaths();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CurrentEncoding; ?>\"" />
	<title>Pivot &#187; <?php echo lang('upload', 'insert_image'); ?></title>
	<link href="../<?php echo $theme['css']; ?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
    //<![CDATA[

    function popup(t, w, h) {
    	window.open('photo.php?img='+t+'&w='+w+'&h='+h+'&t='+t,'photo','width='+w+',height='+h);
    }

    function select(name,width,height) {
    	if (window.opener) {
    		//alert(window.opener.document.pick_f_image);
    		window.opener.document.pick_f_image.f_image.value=name;
    		self.close();
    	} else {
    		alert("The window that opened this window is closed.. (try again, from the 'Insert Image' or 'Insert Popup-sreen)");
    		self.close();
    	}
    } 

    function changePage(newLoc) {
    	nextPage = newLoc.options[newLoc.selectedIndex].value;
    	if (nextPage != '') {
    		document.location.href = nextPage;
    	}
    }

    //]]>
    </script>
</head>
  <body style="margin: 6px 6px 6px 6px; background-image: none;" onload="self.focus()">
    <table border="0" cellspacing="0" cellpadding="2">
<?php

$show = 17;


list($fileArray, $thumbArray) = getFileList();

$count = count($fileArray);
$loop= 0;
do {
	$mystart = substr(strtolower(urldecode($fileArray[$loop]['name'])),0,18);
	if (isset($fileArray[($loop + $show -1)])) {
		$mystop = substr(strtolower(urldecode($fileArray[($loop + $show -1)]['name'])),0,18);
	} else {
		$mystop = "zzz";
	}
	$url = "pick.php?session=".$Pivot_Vars['session']."&slice=$loop";
	$slice_arr[]="<option value=\"$url\">$mystart - $mystop</option>";
	$loop = $loop + $show;
} while ($loop < $count);

if (count($slice_arr)>1) {
	echo "<form name='form1' action=''>";
	echo "<select name='selectedPage' onchange='changePage(this.form.selectedPage)'><option value='#'>Jump to: </option>";
	echo implode("\n", $slice_arr);
	echo "</select></form><hr size='1' noshade='noshade' />";
}

if (isset($Pivot_Vars['slice'])) {
	$slice = $Pivot_Vars['slice'];
} else {
	$slice = 0;
}

$fileArray = array_slice($fileArray, $Pivot_Vars['slice'], $show);
$thumbArray = array_slice($thumbArray, $Pivot_Vars['slice'], $show);





foreach($fileArray as $file) {
	$ext=getextension($file['name']);
	if ((strtolower($ext)=="jpg")||(strtolower($ext)=="gif")||(strtolower($ext)=="png")) {

		
		$fullentry = urlencode(fixpath(sprintf('%s/../%s%s', $Paths['log_url'] , $Cfg['upload_path'], $file['name'])));

		//debug("full: $fullentry");

		// get the image's witdh and height
		list($x, $y) = getimagesize( urldecode( sprintf('../%s%s' ,$Cfg['upload_path'],$file['name'] ) ) );
		
		$filesize= $file['size'];
		$disp_entry= trimtext(strtolower(urldecode($file['name'])), 30, TRUE);
		echo "<tr><td><a href=\"javascript:select('".$file['name']."', $x, $y);\">$disp_entry</a></td><td>( $x x $y px, $filesize kb. )\n";
		echo "</td><td><a href=\"javascript:popup('$fullentry', $x, $y);\">preview</a></td></tr>\n";
	} else if ( ($file['name']!=".") && ($file['name']!=".."))  {

		$fullentry = urlencode(fixpath(sprintf('%s/../%s%s',dirname($base_url) ,$Cfg['upload_path'],$file['name'])));

		$filesize= $file['size'];
		$disp_entry= trimtext(strtolower(urldecode($file['name'])), 30, TRUE);
		echo "<tr><td><a href=\"javascript:select('".$file['name']."', 0, 0);\">$disp_entry</a></td><td> ( $filesize kb. )</td><td>&nbsp;</td></tr>\n";
	}
}





?>
    </table>
    <script type="text/javascript">
    //<![CDATA[
    self.focus();
    //]]>
    </script>
  </body>
</html>
