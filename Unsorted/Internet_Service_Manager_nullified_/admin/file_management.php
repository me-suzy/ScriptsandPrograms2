<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="developer"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.
if($project_id!=="XX"){$project=$project_id;}
include "header.php";

if($newname){
    $dir=$upload_dir.'/'.$client_id.'/'.$folder;
  rename($dir.$file, $dir.$newname);
 echo '<script language="javascript">
         alert("File renamed!");
         window.location="file_management.php?client_id='.$client_id.'&folder='.$folder.'";
 </script>';
}

if($newdirname){
$newdirname=str_replace(" ", "", $newdirname);
    $dir=$upload_dir.'/'.$client_id.'/'.$folder;
 //check it doesnt already exist..
   if(is_dir($dir.$newdirname)){
 echo '<script language="javascript">
         alert("Sorry! It already exists..");
         window.location="file_management.php?client_id='.$client_id.'&folder='.$folder.'";
         </script>';
   }else{
    mkdir($dir.$newdirname, 0700);
 echo '<script language="javascript">
         alert("Directory Created!..");
         window.location="file_management.php?client_id='.$client_id.'&folder='.$folder.'";
         </script>';
   }

}

if($deletedir){
               if(!@rmdir($deletedir)){
               echo '<script language="javascript">
         alert("Directory cannot be deleted with items in it");
window.location="file_management.php?client_id='.$client_id.'&folder='.$folder.'";
 </script>';
}else{
 echo '<script language="javascript">
         alert("Directory Deleted");
         window.location="file_management.php?client_id='.$client_id.'&folder='.$folder.'";
 </script>';
}
}

if($deletefile){
               if(@unlink($deletefile)){
        echo '<script language="javascript">
         alert("File Deleted!!");
 </script>';
            }

}


?> <script language="JavaScript">
			<!--

      function pop_uploads(folder,client){
				window.name='opener';
				dfgdfgfdg=window.open('pop_upload_files.php?folder='+folder+'&client_id='+client,'popupwinPUP2','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');
                    }

         function rename_file(client, folder, file)
         {
                var newname=prompt("What would you like to rename this file to?", file);
                if(newname!="null"){
                    window.location="file_management.php?newname="+newname+"&client_id="+client+"&folder="+folder+"&file="+file
                }
         }

    function add_dir(client, folder)
         {
                var newdirname=prompt("Name of the new directory...", "New Folder");
                if(newdirname!="null"){
                    window.location="file_management.php?newdirname="+newdirname+"&client_id="+client+"&folder="+folder
                }
         }

   function chooseclient(id)
   {
      window.location="file_management.php?client_id="+id;
   }




			//-->
</script>
<?

if($client_id){

//make sure the main dir exists for this client..
if(!is_dir($upload_dir.'/'.$client_id)){
   mkdir ($upload_dir.'/'.$client_id, 0777);
}

echo '<font face="'.$admin_font.'" size="2"><B>File Management...</B><P>';

if($client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"))){
}else{
 //probably an admins files..
 $client=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='".str_replace("admin", "", $client_id)."'"));
 $client[name]="Admin: ".$client[firstname]." ".$client[lastname];
 //check that this admin is the one that owns the files..
 if($admin_id!=$client[id]){
                             echo "Sorry your not that admin!";
                             exit;
 }
}

echo 'You are at: Client Files/'.$client[name].'/'.$folder.'<P>';

echo '<table width="500" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td height="11" width="40"></td>
    <td height="11" width="153"><font face="'.$admin_font.'" size="2"><B>FileName</td>
    <td height="11" width="95"><font face="'.$admin_font.'" size="2"><B>Size</td>
    <td height="11" width="177"><font face="'.$admin_font.'" size="2"><B>Actions</td>
  </tr>
  <tr><td colspan="4" height="2" bgcolor="'.$admin_color_2.'"></td></tr>';

  //make a move up link if this isnt the top folder..
  if($folder){
      $places=explode("/", $folder);
      $items=count($places);
      for($r=0; $r<$items-2; $r++){
           $upfolder.=$places[$r].'/';
      }
     echo '  <tr>
    <td height="11" width="40"><a href="file_management.php?client_id='.$client_id.'&folder='.$upfolder.'" class="left_menu"><img border="0" src="'.$image_url.'/icons/move_up.gif"></a></td>
    <td height="11" width="153"><font face="'.$admin_font.'" size="2"><a href="file_management.php?client_id='.$client_id.'&folder='.$upfolder.'" class="left_menu">Move Up</a></td>
    <td height="11" width="95"><font face="'.$admin_font.'" size="2">-</td>
    <td height="11" width="177"><font face="'.$admin_font.'" size="2">-</td>
  </tr>
  <tr><td colspan="4" height="2" bgcolor="'.$admin_color_2.'"></td></tr>';
  }

//grab all the files and folders in this dir..

$handle=opendir($upload_dir.'/'.$client_id.'/'.$folder);

while (($file = readdir($handle))!==false) {
      if($file!="." && $file!=".."){
    //sort them into dirs and files..
      $thisfile=$upload_dir.'/'.$client_id.'/'.$folder.$file;
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

//handle the directories
     if($alldirs){foreach($alldirs as $file=>$thisfile){
           echo '<tr>
    <td height="11" width="40"><a href="file_management.php?client_id='.$client_id.'&folder='.$folder.''.$file.'/" class="left_menu"><img border="0" src="'.$image_url.'/icons/folder.gif"></a></td>
    <td height="11" width="153"><font face="'.$admin_font.'" size="2"><a href="file_management.php?client_id='.$client_id.'&folder='.$folder.''.$file.'/" class="left_menu">'.$file.'</a></td>
    <td height="11" width="95"><font face="'.$admin_font.'" size="2">-</td>
    <td height="11" width="177"><font face="'.$admin_font.'" size="2"><a href="file_management.php?folder='.$folder.'&client_id='.$client_id.'&deletedir='.$thisfile.'" class="left_menu">Delete</a></td>
  </tr><tr><td colspan="4" height="1" bgcolor="'.$admin_color_2.'"></td></tr>';
      }}

//handle the files..
   if($allfiles){foreach($allfiles as $file=>$thisfile){
           //its a file..
           
           //try and establish its file-type and grab an icon for it..
             list(,$type)=explode(".", $file);
              if(is_file($image_dir.'/icons/'.$type.'.gif')){
                   $icon=$image_url.'/icons/'.$type.'.gif';
              }else{
                   $icon=$image_url.'/icons/unknown.gif';
              }


           
           echo '<tr>
    <td height="11" width="40"><a target="_blank" href="'.$upload_url.'/'.$client_id.'/'.$folder.$file.'" class="left_menu"><img src="'.$icon.'" border=0></a></td>
    <td height="11" width="153"><font face="'.$admin_font.'" size="2"><a target="_blank" href="'.$upload_url.'/'.$client_id.'/'.$folder.$file.'" class="left_menu">'.$file.'</a></td>
    <td height="11" width="95"><font face="'.$admin_font.'" size="2">'.round((filesize($thisfile)/1000), 2).'Kb</td>
    <td height="11" width="177"><font face="'.$admin_font.'" size="2"><a href="file_management.php?folder='.$folder.'&client_id='.$client_id.'&deletefile='.$thisfile.'" class="left_menu">Delete</a> | <a class="left_menu" href="javascript: rename_file('."'".$client_id."'".', '."'".$folder."'".', '."'".$file."'".')">Rename</a></td>
  </tr><tr><td colspan="4" height="1" bgcolor="'.$admin_color_2.'"></td></tr>';

   echo   '<tr><td colspan="4" bgcolor="'.$admin_color_2.'"></td></tr>';
  }}



echo '</table><P>';
  






echo '<a class="left_menu" href="javascript: pop_uploads('."'".''.$folder.''."'".', '."'".$client_id."'".')">Upload Files to this Directory</a>';
echo '&nbsp;&nbsp;|&nbsp;&nbsp;<a class="left_menu" href="javascript: add_dir('."'".$client_id."'".', '."'".$folder."'".')">Add Directory to this one</a>';


exit;
//end file management
}
//select client!

      echo '<form name="f"><font face="'.$admin_font.'" size="2">

      Select the client the project is for..<P>';
      echo '<select onChange="chooseclient(this.value)"><option value="">---</option>
      <option value="admin'.$admin_id.'">My Files</option>';

$pe=mysql_query("SELECT * FROM clients ORDER BY name");
while($p=mysql_fetch_array($pe)){
 $sel="";if($client_id==$p[id]){$sel="SELECTED";}
 echo '<option '.$sel.' value="'.$p[id].'">'.$p[name].'</option>';
}
echo '</select>';




include "footer.php";
?>
