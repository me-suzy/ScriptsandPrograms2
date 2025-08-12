

<?
function words($number, $word){
if($number == 1){ return "<strong>$number</strong> $word"; }else{ return "<strong>$number</strong> $word"."s"; }
}

function checked($key,$what,$sign=checked){if($key==$what){ echo $sign;}}

if ($hori == ""){ $hori ='12'; }
if ($bytes == ""){ $bytes ='28,672'; }
if ($type == ""){ $type ='image/jpeg'; }
if ($ffmat == ""){ $ffmat ='awdigie'; }


?> 
<style type="text/css">

.text {
	font-size: 12px;
}

.infield {
	font-size: 10px;
	color: #000000;
	background-color: #E4E4E4;
	border-top-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #999999;
	border-right-color: #999999;
	border-bottom-color: #999999;
	border-left-color: #999999;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
}


.box {
	background-color: #F0E6E6;
	border: 1px solid #666666;
}
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<em><strong>Aw Image Show </strong></em> 
<form action="" method="post" name="form1" class="text">
  <table width="48%">
    <tr>
      <td width="58%"><table width="187" cellpadding="2" cellspacing="2" class="box">
          <tr> 
            <td height="161"><a class=text> 
              <p><strong>Filters :</strong><br>
                Image Size<br>
                <select name="sizes" class="infield">
                  <option value="0" <? checked($sizes,"0",selected) ?>>Any Size</option>
                  <option value="64" <? checked($sizes,"64",selected) ?>>64 X 
                  64 Pixels</option>
                  <option value="128" <? checked($sizes,"128",selected) ?>>128 
                  X 128 Pixels</option>
                  <option value="256" <? checked($sizes,"256",selected) ?>>256 
                  X 256 Pixels</option>
                  <option value="512" <? checked($sizes,"512",selected) ?>>512 
                  X 512 Pixels</option>
                </select>
                <br>
                <br>
                Image File Size<br>
                Is less then 
                <input name="bytes" type="text" class="infield" id="bytes4" value="<? echo $bytes ?>" size="8" maxlength="8">
                Bytes<br>
                <br>
                Image Type<br>
                <select name="type" class="infield">
                  <option value="image/jpeg" <? checked($type,"image/jpeg",selected) ?>>JPEG</option>
                  <option value="image/gif" <? checked($type,"image/gif",selected) ?>>GIF</option>
                  <option value="image/png" <? checked($type,"image/png",selected) ?>>PNG</option>
                </select>
                <br>
                Last Modified <br>
                <select name="lastdate" class="infield" >
                  <option value="9999999999" <? checked($lastdate,"9999999999",selected) ?>>Any 
                  Date</option>
                  <option value="2629743.83" <? checked($lastdate,"2629743.83",selected) ?>>Within 
                  a Month</option>
                  <option value="1814400" <? checked($lastdate,"1814400",selected) ?>>Within 
                  3 Weeks</option>
                  <option value="604800" <? checked($lastdate,"604800",selected) ?>>Within 
                  a Week</option>
                  <option value="345600" <? checked($lastdate,"345600",selected) ?>>Within 
                  4 days</option>
                  <option value="86400" <? checked($lastdate,"86400",selected) ?>>Within 
                  a Day</option>
                </select>
              </p>
              </a></td>
          </tr>
        </table></td>
      <td width="42%"><table cellpadding="2" cellspacing="2" class="box">
          <tr> 
            <td height="111"><a class=text><strong>Show : </strong><br>
              Images horizontal<br>
              <input name="hori" type="text" class="infield" id="hori4" value="<? echo $hori;  ?>" size="8" maxlength="8">
              <br>
              Image Type 
              <input name="showtype" type="checkbox"  value="y" <? checked($showtype,y) ?>>
              <br>
              Image Size 
              <input name="showimagesize" type="checkbox" id="showimagesize4"  value="y" <? checked($showimagesize,y) ?>>
              <br>
              File Size 
              <input name="showfilesize" type="checkbox"  value="y" <? checked($showfilesize,y) ?>>
              <br>
              Filterd Images only 
              <input name="filter" type="checkbox"   value="y" <? checked($filter,y) ?>>
              </a> </td>
          </tr>
        </table></td>
    </tr>
  </table>
  Selectable Images For Output 
  <input name="showboxes" type="checkbox" id="showboxes" value="y" <? checked($showboxes,y) ?>>
  <br>
  
  <input name="Submit" type="submit" class="infield" value="Submit">
<table cellpadding="2" cellspacing="2">
 <tr> 
<?

$dh  = opendir("./");
$i=1;
$c=0;
$selnum =1;
while (false !== ($filename = readdir($dh))) {

if($filename !== '..' && $filename !== '.' ){
if( ($info= @getimagesize($filename)) == true){ 
$alpha='';
//$bgcolor="#FAFAFA";
$ched='';
if(filesize($filename) <= str_replace(',', '',$bytes) && $type == $info[mime] && ($info[0] == $sizes && $info[1]== $sizes  or $sizes ==0) && (time()-$lastdate) <= filemtime($filename) ){ $bgcolor= "#E4CBCB"; $ched='checked'; $selnum++; }else{  $alpha = 'style="filter:alpha(opacity=30)" ';   }

if($filter == y && $alpha == "" or $filter == ""){
echo '
      <td  bgcolor="'.$bgcolor.'" '.$alpha.'><img src="'.$filename.'" width="64" height="64" alt="'.$filename.'"><br> 
      <font size="1">';

if($showboxes==y){ echo '<input type="checkbox" name="imb['.$c.']" value="'.$filename.'" '.$ched.'>'; }

if($showtype == y ){ echo strtoupper(str_replace('image/','',$info[mime]))."<br>"; }	  

if($showfilesize == y ){ echo round((filesize($filename)/1024))."KB<br>"; }	  

if($showimagesize == y ){ echo "$info[0] X $info[1] <br>"; }	  
 echo '</font></td>';
$i++; 
$c++;
	if($i == $hori+1){ echo "<tr></tr>\n"; $i=1; }

}
}// if $info
}//if ..
}

?>
  </tr>
</table>
<hr align="left" width=600 size="1">
 <a class=text>
    <?
echo "<em>".words($c, Image)." -/ ".words($selnum, Image)."</em>";

if($showboxes==y){


if($outname){

if( ($fp = @fopen("$outname","w+"))==false){ echo "<br><strong>Cant open \"$outname\" file</strong>";  }else{
echo "<br><strong>Success!</strong>";  
$k=0;
foreach($imb as $name){
if($ffmat == awdigie){ 
$out= $name; 
}else{
if($k==0){
$out='$apics= array(';  
}else{
$out='"'.$name.'",';
if($k==(count($imb)-1)){
$out=');';
}//if k end
}
}
fputs($fp,"$out\n");
$k++;
}// end of loop
fclose($fp);
}//if valid file
}//if outname

?>
    <br>
    <br>
   Outputting asumes that the path is relitive to where </a> Aw 
    Image Show is.<br>
    Only the images with the checks in them will be outputted.<br>
  Filename output as:<a class=text> 
  <input name="outname" type="text" class="infield" id="outname" value="<? echo $outname;  ?>">
  </a><br>
    <a class=text> </a>Format as: <br>
    PHP Array 
    <input type="radio" name="ffmat" value="php" <? checked($ffmat,php) ?>>
    <br>
    One on each line (Awdigie Format) 
    <input type="radio" name="ffmat" value="awdigie" <? checked($ffmat,awdigie) ?>>
  </p> 
  <p>
    <input type="submit" class="infield"  value="Save">
    <br>
    <?   }//if showboxes  ?>
  </a>
</form>