<? 
# sent form? 
if(isset($_POST['MM_submit'])) { 
$imgdir=$_GET['dir']; 
foreach($_POST['delete'] as $del) { 
unlink($imgdir.$del); # deletes the file 
} 
} 
$imgdir = 'images/'; # the directory, where your images are 
$allowed_types = array('png','jpg','jpeg','gif'); # list of filetypes you want to show 

$dimg = opendir($imgdir); 
while($imgfile = readdir($dimg)) 
{ 
if(in_array(strtolower(substr($imgfile,-3)),$allowed_types)) 
{ 
$a_img[] = $imgfile; 
sort($a_img); 
reset ($a_img); 
} 
} 
$i=0; 
$totimg = count($a_img); # total image number 
?> 
<form name="delete_form" method="post" action="?&dir=<? echo $imgdir; ?>"> 
<table width="300" border="0" cellpadding="0" cellspacing="0" class="border"> 
<? 
for($x=0; $x < $totimg; $x++){ # start a loop 
if (($i++ % 1) == 0) { echo "</tr>\n<tr>\n"; } # loops the table after 3 columns 
$size = getimagesize($imgdir.'/'.$a_img[$x]); 
// do whatever 
$halfwidth = ceil($size[0]/2); 
$halfheight = ceil($size[1]/2); ?> 
<td width="300" height="61" valign="top"> 
<table width="99%" border="1" cellpadding="1" cellspacing="2" bordercolor="#ECE9D8"> 
<tr bgcolor="#ECE9D8"> 
<td width="46%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Picture 
Info:</font></td> 
<td width="54%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo 'name: '.$a_img[$x].' width: '.$size[0].' height: '.$size[1]; ?></font></td> 
</tr> 
<tr align="center"> 
<td colspan="2"><img src="<? echo $imgdir.$a_img[$x]; ?>" width="<?php echo "$size[0]" ?>" height="<?php echo "$size[1]" ?>"></td> 
</tr> 
<tr bgcolor="#ECE9D8"> 
<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">delete: 
<input name="delete[]" type="checkbox" id="checkbox" value="<? echo $a_img[$x]; ?>"> 
</font></td> 
<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td> 
</tr> 
</table> 
<br> 
<hr> 
<? } ?> 
</td> 
</tr> 
</table> 
<br> 
<input type="hidden" name="MM_submit"> 
<input type="submit" name="Submit" value="Submit"> 
</form> 

<br /> 


