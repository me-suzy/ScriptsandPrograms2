<?

 +---------------------------------------------------------+
 | Alfa-File-Manager (v1.2)  Copyright www.YugDesign.com   |
 +---------------------------------------------------------+
 | This program may be used and hosted free of charge by   |
 |anyone for personal purpose as long as this copyright    |
 |notice and back link remain intact.                      |
 +---------------------------------------------------------+




// No installation required
// put $otherpath="." to view directory where the script is
// put $otherpath=".." to view upper directory from where the script is

$otherpath=".."; 

$show_details = 1; 
$dirs = array();
$fils = array();
$size_bytes = 100000; // max file size to upload


// edit html template

$tpl = <<<FO
<html>
<head>
<title>AlfaFM</title>
<style>
A:link {	COLOR: #663300; TEXT-DECORATION: none} A:visited {	COLOR: #FF6600; TEXT-DECORATION: none} A:active {	COLOR: #FF0000; TEXT-DECORATION: none} A:hover {	COLOR: #990000; TEXT-DECORATION: underline;}
.link_tabl_nav { 
 font-family: Arial,Verdana; 
 font-size: 0.6em;
 color: #575757;
 background: #E0E0E0;
}
.link_tabl { 
 border-bottom: #E0E0E0 1px ridge;
 border-right: #E0E0E0 1px solid;
 font-family: Arial,Verdana; 
}
</style>
</head>
<body>
<div align="center">
  <center>
    <table class=link_tabl_nav border="0" cellpadding="2" cellspacing="2" width="100%">
  <tr>
    <td>
<font size="4">Alfa File Manager</font>
	   </td>
    <td width=100>
<font size="4"><%main%></font>
	   </td>
  </tr>
  </table>
  <br>
  <table border="0" cellpadding="4" cellspacing="4" width="100%">
  <tr>
    <td>

<%page%>


	   </td>
  </tr>
  </table>
Report any found bugs <a href='http://www.yugdesign.com/forums/index.html'>here</a>

  </center>
</div>
</body>
</html>
FO;













//################################################################

function type_switch($in_file) {
			    $filename_ex = explode(".",$in_file);
				switch(strtolower($filename_ex[1])) {
					case "png":
					case "bmp":
					case "jpg":
					case "jpeg":
					case "gif":
						$filetype = "image2";
						break;
					case "psd":
						$filetype = "portal";
						break;
					case "php":
					case "php3":
						$filetype = "quill";
						break;
					case "zip":
						$filetype = "compressed";
						break;
					case "html":
					case "htm":
						$filetype = "layout";
						break;
					case "ini":
					case "htaccess":
					case "htpasswd":
					case "db":
						$filetype = "alert.black";
						break;
					case "txt":
					case "text":
						$filetype = "text";
						break;
					case "js":
						$filetype = "script";
						break;
					case "css":
						$filetype = "c";
						break;
					default:
						$filetype = "generic";
				}
return $filetype;
}

function edit_switch($in_file) {
			    $filename_ex = explode(".",$in_file);
				switch(strtolower($filename_ex[1])) {
					case "png":
					case "bmp":
					case "jpg":
					case "jpeg":
					case "gif":
						$edittype = "view";
						break;
					case "php":
					case "php3":
					case "html":
					case "htm":
					case "ini":
					case "htaccess":
					case "htpasswd":
					case "db":
					case "txt":
					case "text":
					case "js":
					case "css":
						$edittype = "edit";
						break;
					default:
						$edittype = "";
				}
return $edittype;
}

function filesizepre($filesize){ return round( ($filesize / 1024),1  ).k;  }


//################################################################


if($new_dir){
if(!is_dir("$otherpath$p/$new_dir")) mkdir("$otherpath$p/$new_dir", 0755);
}

if($del_dir){
if(is_dir("$otherpath$p/$del_dir") && rmdir("$otherpath$p/$del_dir")) $uperror = "Directory was deleted.";;
}

if($del_fil){
if(is_file("$otherpath$p/$del_fil")) unlink("$otherpath$p/$del_fil");
$uperror = "File was deleted.";
}

if($new_file){

   $upload_dir = "$otherpath$p/";
   if (!is_dir("$upload_dir")) {
    $uperror = "Error: The directory <b>($upload_dir)</b> doesn't exist";
   }
   if (!is_writeable("$upload_dir")){
    $uperror = "Error: The directory <b>($upload_dir)</b> is NOT writable, Please CHMOD (777)";
   }
   $file_tmp = $_FILES['new_file']['tmp_name'];
   $file_name = $_FILES['new_file']['name'];
   $file_size = $_FILES['new_file']['size'];
   if (!is_uploaded_file($file_tmp)){
    $uperror = "Error: Please select a file to upload!";
   }
    if ($file_size > $size_bytes){
     $uperror = "Error: File Too Large. File must be <b>". $size_bytes / 1024 ."</b> KB.";
   }

   if(file_exists($upload_dir.$file_name)){
    $uperror =  "There is a file with the same name.";
   }

           if (!$uperror && move_uploaded_file($file_tmp,$upload_dir.$file_name)) {
                 $uperror = "File was uploaded!";
           }else{
                $uperror =  "There was a problem uploading your file.";
           }
		   
}



if($edit_fil_confirm){

$edit_form = str_replace("#=start#", "<?", $edit_form);
$edit_form = str_replace("#=end#", "?>", $edit_form);
$edit_form = str_replace("#tarea#", "textarea", $edit_form);

$parts = split("\n", $edit_form);
foreach($parts as $part){
$part = chop($part);
$out .= "$part\n";
}

$burl="$otherpath$p/$edit_fil_confirm";
$nf = fopen("$burl", "w");
fwrite ($nf, $out);
fclose($nf);
$uperror =  "File updated";
}

//################################################################


$script = $_SERVER['PHP_SELF'];
if(!$p){
$path = "$otherpath/";
$uphtml = ".. <img src=\"/icons/folder.open.gif\"> <a href='$script'>Home Directory</a>";
}else{
$path = "$otherpath$p";
$part = array_pop(split("/", $p));
$up = str_replace("/$part", "", $p);
$uphtml = " .. <img src=\"/icons/folder.open.gif\"> <a href='$script?p=$up'>Go to Up Directory</a>";
}


if(is_dir($path)){
$dh  = @opendir($path);
	 while (false !== ($dirfile = @readdir($dh))) {
	    if($dirfile !== '..' && $dirfile !== '.' ){
	 			 if(is_dir("$path/$dirfile")){ array_push ($dirs,$dirfile);}
				 elseif(is_file("$path/$dirfile")){ 
				 array_push ($fils,$dirfile); 
				 $files[$dirfile][fd] = @filemtime("$path/$dirfile");
				 $files[$dirfile][size] = @filesize("$path/$dirfile");
	    }
 	 }
}
closedir($dh);
}else{$uphtml = "Directory Path ERROR";}


array_multisort($dirs,SORT_ASC,SORT_STRING);
array_multisort($fils,SORT_ASC,SORT_STRING);

foreach ($dirs as $dir) {
$chtml .= "<span style='cursor:hand;background:#F2DFDF' onClick='del_ask(\"$script?p=$p&del_dir=$dir\")' title='delete'>&nbsp;X </span> &nbsp; <img src=\"/icons/folder.gif\"> &nbsp; <a href='$script?p=$p/$dir'>$dir</a>\n<br>\n";
}

foreach ($fils as $fil) {
$type = type_switch($fil);

if(edit_switch($fil) == "view") {
$img_size = @getimagesize("$otherpath$p/$fil"); 
$fil_w = $img_size[0];				
$fil_h = $img_size[1];

if ($fil_w > 250) {
$gwidth = $fil_w;
$gheight = $fil_h;
$a = $gwidth - 250;
$b = $gwidth / $a ;
$c = $gheight / $b;
$fil_w = 250;
$fil_h = intval($gheight - $c);
}

$butt = "<span style='cursor:hand;background:#F2DFDF' onClick=\"showimg('$fil', '$fil_w', '$fil_h')\" title='view'>&nbsp;V </span> &nbsp; ";
}elseif(edit_switch($fil) == "edit"){
$butt = "<span style='background:#F2DFDF' title='edit'>&nbsp;<a href='$script?p=$p&edit_fil=$fil'>E</a> </span> &nbsp; ";
}else{ $butt = "";}

$fhtml .= "<span style='cursor:hand;background:#F2DFDF' onClick='del_ask(\"$script?p=$p&del_fil=$fil\")' title='delete'>&nbsp;X </span> &nbsp; $butt<img src=\"/icons/$type.gif\"> &nbsp; $fil<br>\n";
if($show_details) $fhtml .= "<font style='color:#C0C0C0' size=1>[".(filesizepre($files[$fil][size]) )."] &nbsp; [".date("j-M-y h:iA", $files[$fil][fd])."]</font><br>\n";
}

if(!$chtml) $chtml = "No Directories";
if(!$fhtml) $fhtml = "No Files";




$pageout = <<<FO

<script language=javascript>
function showimg(img, img_w, img_h){
	document.getimg.src = "$path/" + img;
	document.getimg.width = img_w;
	document.getimg.height = img_h;
}
function del_ask(gourl){
var ask = window.confirm('DELETE - Are you sure?');
if(ask) document.location.href = gourl;
}
</script>

<center><font size="4">$uperror</font></center>
<TABLE class=link_nave width=750 align=center border=0 cellpadding=2 cellspacing=2>
<TR valign=top>
<TD colspan=5>
$uphtml
<a name="up"><hr size="1" noshade color="#808080"></a>
</TD>
</TR>
<TR valign=top>
<TD width=10>&nbsp;
</TD>
<TD width=300>
<p class=link_tabl>
$chtml
<br>
</p>
<br>
<form action=$script method=post><input name="p" type="hidden" value="$p">
<input name="new_dir" style="width:70%" type="text" value=""><input name="target" style="width:30%" type="submit" value="add new">
</form>
<p align=center>
<IMG border="0" src="/icons/blank.gif" 
 name="getimg" width="" height="">

</p>
</TD>
<TD width=10>&nbsp;
</TD>
<TD>
$fhtml 

<p align=right><a href="#up"><IMG border="0" src="/icons/up.gif" alt="Up"></a></p>
<hr size="1" noshade color="#808080">
<form action=$script method=post enctype="multipart/form-data"><input name="p" type="hidden" value="$p">
<input name="new_file" style="width:68%" type="file"> <input name="target" style="width:30%" type="submit" value="upload">
</form>
<IMG border="0" src="/icons/blank.gif" height="1" width=310>
</TD>
<TD width=10>&nbsp;
</TD>
</TR>
</TABLE>
FO;

//################################################################



if($edit_fil){

$editing = implode("", file("$otherpath$p/$edit_fil"));
$editing = str_replace("<?", "#=start#", $editing);
$editing = str_replace("?>", "#=end#", $editing);
$editing = str_replace("textarea", "#tarea#", $editing);
$editing = str_replace("TEXTAREA", "#tarea#", $editing);



$pageout = <<<FO
<TABLE align=center width=750 border=0 cellpadding=2 cellspacing=3>
<TR valign=top>
<TD align=left>
$uphtml
$otherpath$p/<b>$edit_fil</b> &nbsp; &nbsp; &nbsp; (don't change: #=start# &nbsp; #=end# &nbsp; #tarea#)
</TD>
</TR>
<TR valign=top>
<form action="$script?p=$p&edit_fil_confirm=$fil" method=post>
<TD class=link_nave align=center>
$mesage
<TEXTAREA style="width:740; height:400px" name=edit_form>$editing</TEXTAREA>
</TD>
</TR>
<TR valign=top>
<TD align=center>
<input name="target" style="width:740" type="submit" value="edit">
</TD>
</TR></form>
</TABLE>

FO;
	

}



	$tpl = str_replace("<%page-title%>", $site_title, $tpl);
	$tpl = str_replace("<%page%>", $pageout, $tpl);
	$tpl = str_replace("<%main%>", "<a href='http://www.yugdesign.com'>YugDesign</a>", $tpl);
eval ("?>".$tpl."<?");


?>