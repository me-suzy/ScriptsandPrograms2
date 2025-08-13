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
include "../conf.php";
include "auth.php";

?> <script language="JavaScript">
			<!--

   function chooseclient(id)
   {
      window.location="pop_file_list.php?client_id="+id;
   }
	
	function insertfile(file){
	window.opener.document.newticket.attatchments.value=window.opener.document.newticket.attatchments.value+";"+file
	}



			//-->
</script>
<?


$handle=opendir($upload_dir.'/'.$folder);

while (($file = readdir($handle))!==false) {
      if($file!="." && $file!=".."){
    //sort them into dirs and files..
      $thisfile=$upload_dir.'/'.$folder.'/'.$file;
	  if(is_dir($thisfile)){
         $alldirs[$file]=$thisfile;
      }else{
         $allfiles[$file]=$thisfile;
      }
  }
}
closedir($handle);
@ksort($allfiles);
@ksort($alldirs);
echo '<font size="2" face="'.$admin_font.'"><font size="1"><a href="javascript: window.history.back()">Back</a>&nbsp;Uploaded Files: '.$folder.'</font><BR><B><U>Folders</u></B><BR>';
if($alldirs){foreach($alldirs as $file=>$dir){
echo '<a href="pop_file_list.php?folder='.$folder.'/'.$file.'" class="left_menu">'.$file.'</a><BR>';
}}

echo '<font size="2" face="'.$admin_font.'"><P><B><U>Files</u></B><BR>';
if($allfiles){foreach($allfiles as $file=>$dir){
echo '<a href="javascript: insertfile(\''.$folder.'/'.$file.'\')" class="left_menu">'.$file.'</a><BR>';
}}


?>
</BODY>
</HTML>
