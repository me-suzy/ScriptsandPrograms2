<HTML>
<HEAD>
<TITLE>Uploading files..</TITLE>

</HEAD>
<BODY bgcolor="#efefef">
<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="developer";
if($project_id!="XX"){$project=$project_id;}
include "../conf.php";
include "auth.php";


if($control){
echo '<font face="'.$admin_font.'" size="2"><B>File upload report</b><P>';

foreach($control as $cont){
 $userfile=$HTTP_POST_FILES['file'.$cont]['tmp_name'];
 $filename=$HTTP_POST_FILES['file'.$cont]['name'];
$place=$upload_dir.'/'.$client_id.'/'.$folder.$filename;
 //check if the file exists..
 $existed=0;if(is_file($upload_dir.'/'.$client_id.'/'.$folder.$filename)){
 //yes it does so we will just append the date onto the filename..
 list($fname, $type)=explode(".", $filename);
 $place=$upload_dir.'/'.$client_id.'/'.$folder.$fname.str_replace(":", "-", str_replace(",", "-", date("F j, Y, g:i a"))).'.'.$type;
 $newfilename=$fname.str_replace(":", "-", str_replace(",", "-", date("F j, Y, g:i a"))).'.'.$type;
 $existed=1;
 }
 if(is_uploaded_file($userfile)){copy($userfile, $place);
 //print out the report details..
 if($existed){
      echo $filename .' existed so it was uploaded as '.$newfilename.'<BR>';
 }else{
      echo $filename .' was uploaded as '.$filename.'<BR>';
 }
 }
}

echo '<script language="javascript">
</script>';
echo '<P><BR><font size=1><a href="javascript: window.close()">Close Me</a>';



}else{
echo '<FORM ENCTYPE="multipart/form-data" ACTION="pop_upload_files.php?client_id='.$client_id.'&folder='.$folder.'" METHOD="POST"><table cellpadding=4>';

for($x=1; $x<21; $x++){
echo '<tr><td><font face="'.$admin_font.'" size="2">File '.$x.':</td><td><INPUT TYPE="hidden" name="control['.$x.']" value="'.$x.'">
<INPUT NAME="file'.$x.'" TYPE="file"></td></tr>';
}


echo '</table><INPUT TYPE="submit" VALUE="Send File">
</FORM>';

}


?>
</BODY>
</HTML>
