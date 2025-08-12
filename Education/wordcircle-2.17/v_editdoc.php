<?php


if(isset($_GET['rdir'])){
			$fh = new filer();
			$fh->recursiveDelete("c".$_GET['gid']."/".$_GET['rdir']."/");
			$GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we delete this directory","index.php?a=editdoc&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;

}

if (isset($_GET['delid']) ){
		
		$GLOBALS['db']->execQuery("delete from documents where id = ".$_GET['delid']);
		$GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we delete this link","index.php?a=editdoc&gid=".$_GET['gid'].(isset($_GET['dir'])?"&dir=".$_GET['dir']:""));
			include("v_footer.php");
			exit;
}

if (isset($_GET['f'])){
	
 $path = "c".$_GET['gid']."/";    // Open the folder
 
 if(isset($_GET['dir'])){
 	$path .= $_GET['dir']."/";    //for subdirectories
 }
	
	 if (@unlink($path.$_GET['f'])) {
     $GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we delete this file","index.php?a=editdoc&gid=".$_GET['gid'].(isset($_GET['dir'])?"&dir=".$_GET['dir']:""));
			include("v_footer.php");
			exit;
    } else {
      $GLOBALS['error'][0] = "An error occurred deleting your file";
    }
}

if (isset($_POST['submit'])){

	//
	
if (isset($_GET['t'])){
		
	
	
	$GLOBALS['db']->checkTyped($_POST['link'],"You must enter text for your link");
	$GLOBALS['db']->checkLen($_POST['link'],255,"links must be less than 255 characters");
	if(!stristr($_POST['link'],"://")){$GLOBALS['error'][0] = "Links must begin with http:// or another valid protocol";}
	
		
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("insert into documents(name,group_id,descr) values('". trim($_POST['link'])."',".$_GET['gid'].",'".$_POST['desc']."')");
			$GLOBALS['page']->head("wordcircle","","Be patient");
			$GLOBALS['page']->pleaseWait("Please wait while we add this link","index.php?a=editdoc&gid=".$_GET['gid'].(isset($_GET['dir'])?"&dir=".$_GET['dir']:""));
			include("v_footer.php");
			exit;
			}
			
}elseif ($_POST['submit'] == 'create new directory'){

	
	$GLOBALS['db']->checkLen($_POST['dirname'],14,"Directories must be less than 14 characters");
	$GLOBALS['db']->checkNameSpace($_POST['dirname'],"Directory names cannot have spaces or special characters in them\\nNames that start with numbers are also not allowed");
	
	if (file_exists("c".$_GET['gid']."/".$_POST['dirname'])) { 
		$GLOBALS['error'][0]="Either the directory exists already or you have named it the same as a file name (not allowed)";
	}
       

	if(count($GLOBALS['error']) == 0){
	
		mkdir("c".$_GET['gid']."/".$_POST['dirname']);
		 $GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we create this directory","index.php?a=editdoc&gid=".$_GET['gid'].(isset($_POST['dirname'])?"&dir=".$_POST['dirname']:""));
			include("v_footer.php");
			exit;
	
	}

}elseif(isset($_FILES['aFile'])){	

	// Upload processor script 

$path = "c".$_GET['gid']."/";    // Open the folder
 
 if(isset($_GET['dir'])){
 	$path .= $_GET['dir']."/";    //for subdirectories
 }	
 
  if (isset($_POST['subdir']) and $_POST['subdir'] <> ""){
 	$path .= $_POST['subdir']."/";    //for subdirectories
 }
	

$GLOBALS['db']->checkNameSpace($_FILES['aFile']['name'],"File names cannot have spaces or special characters in them\\nFile names that start with numbers are also not allowed");
$GLOBALS['db']->checkFileTypes($_FILES['aFile']['name'],"This file type is now allowed");

if(count($GLOBALS['error']) == 0){
 if (copy($_FILES['aFile']['tmp_name'], $path.$_FILES['aFile']['name'])) {
      if (move_uploaded_file($_FILES['aFile']['tmp_name'], $path.$_FILES['aFile']['name'])) {
        @chmod($_FILES['aFile']['name'], $path.$_FILES['aFile']['name'], 0777);
       $GLOBALS['page']->head("wordcircle","","Please wait");
		$GLOBALS['page']->pleaseWait("Please wait while we save this file","index.php?a=editdoc&gid=".$_GET['gid'].(isset($_GET['dir'])?"&dir=".$_GET['dir']:""));
			include("v_footer.php");
			exit;
      } else {
      $GLOBALS['error'][0] = 'an error occurred uploading your file';
      }
    } else {
   	  $GLOBALS['error'][0] = 'an error occurred uploading your file';
    }
}
}
}

$GLOBALS['page']->head("wordcircle","","Only the course owner can post documents");

//start documents
	
	
if (!file_exists("c".$_GET['gid'])) { 
       mkdir("c".$_GET['gid']);} 
 $i=0;
 $doc=array();
 $path = "c".$_GET['gid']."/";    // Open the folder 
 
 if(isset($_GET['dir'])){
 	$path .= $_GET['dir']."/";    //for subdirectories
 }
 

  
    
 $dir_handle = @opendir($path) or die("Unable to open $path - your webserver may not have permissions to read / write directories");  
$dirs = array();
   // Loop through the files    
   while ($file = readdir($dir_handle)) {    if($file == "." || $file == ".." || $file == "index.php" || strstr($file,"mpub")<>false || strstr($file,"mpriv")<>false)        continue;
if(is_dir($path.$file)){
   $temp['files'] = "<a href='index.php?a=editdoc&gid=".$_GET['gid']."&dir=".$file."'><img src='icon_fold.gif' width='15' height='13' alt='' border='0' align='top'> ".$file."</a><br />";
$temp[''] = "<a href='index.php?a=editdoc&gid=".$_GET['gid']."&rdir=".$file."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this directory and everything in it?'); return confirmDownload;\"'><img src='icon_delete.gif' width='14' height='16' alt='delete this document' border=0>delete</a>";
   array_push($dirs,$file);
   array_push($doc,$temp);
   }
   else{
   $temp['files'] = "<a href='".$path.$file."'><img src='icon_file.gif' width='14' height='16' alt='' border='0' align='top'> ".$file."</a><br />";
	$temp[''] = "<a href='index.php?a=editdoc&gid=".$_GET['gid'].(isset($_GET['dir'])?"&dir=".$_GET['dir']:"")."&f=".$file."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this file?'); return confirmDownload;\"'><img src='icon_delete.gif' width='14' height='16' alt='delete this document' border=0>delete</a>";
	array_unshift($doc,$temp);
	} 	
	}    // Close   
   closedir($dir_handle);
   $doc = array_reverse($doc);
   asort($dirs);
  
	$GLOBALS['page']->tableStart("","100%","TAB","Course Materials");
	
	if(isset($_GET['dir'])){
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo('<img src="icon_foldup.gif" width="15" height="13" alt=""><a href="index.php?a=editdoc&gid='.$_GET['gid'].'"> Go up to main folder</a><br>Current subfolder: <strong>'.$_GET['dir'].'</strong> ');
	$GLOBALS['page']->tableEnd("TEXT");
	}else{
		$GLOBALS['page']->tableStart("","100%","TEXT");
		echo('<form method="post" action="index.php?a=editdoc&gid='.$_GET['gid'].'" onSubmit="count()"><input type="text" name="dirname" SIZE="20" class="inputs"><input type="submit" name="submit" value="create new directory" class="inputs"></form>');
		$GLOBALS['page']->tableEnd("TEXT");
	
	}
	if(count($doc)>0){
	;
	
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($doc,"odd","even","40%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no files available");
	$GLOBALS['page']->tableEnd("TEXT");
	}	
	//start links
	
	$result = $GLOBALS['db']->execQuery("select id, descr, name,descr from documents where group_id = ".$_GET['gid']." order by name");
	$i = 0;
	$doc = array();
	while ($row = mysql_fetch_assoc($result)) { 
       $doc[$i]['links'] = "<a href='".$row['name']."' class='".(($i%2==0)?"even":"odd")."'>".$row['descr']."</a>";
 	$doc[$i][''] = "<a href='index.php?a=editdoc&gid=".$_GET['gid']."&delid=".$row['id']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this link?'); return confirmDownload;\"'><img src='icon_delete.gif' width='14' height='16' alt='delete this document' border=0>delete</a>";
	   $i++;
   }
	
	$GLOBALS['page']->tableEnd("TAB");echo("<br>");
	$GLOBALS['page']->tableStart("","100%","TAB","links");
	
	if(count($doc)>0){
	;
	
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($doc,"odd","even","40%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no links available");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	$GLOBALS['page']->tableEnd("TAB");
	echo("<br>");
	$GLOBALS['page']->tableStart("","100%","TAB","Modify Course Materials");
	
			$GLOBALS['page']->tableStart("","100%","TEXT");
		
	echo("<script language='JavaScript'>
		<!-- Copyright 2000 by William and Will Bontrager.
		counter=-1;
		function count() {
		counter++;
		if(counter > 1) {
		if(counter > 2) { return false; }
		alert('This operation will take some time\\n\\nIf you keep re-submitting the page it will just take longer!');
		return false;
		}
		return true;
		} // -->
		</script>");	
	echo('Use form <strong>(a)</strong> to upload files and form <strong>(b)</strong> to save links (website addresses)<br><br>
<strong>(a)</strong> upload a file
	<form method="post" action="index.php?a=editdoc&gid='.$_GET['gid'].(isset($_GET['dir'])?"&dir=".$_GET['dir']:"").'" enctype="multipart/form-data" onSubmit="count()"> 
<input type="file" name="aFile" size="20" class="inputs">
<select name="subdir" class="inputs"><option value="">put this file in...</option><option value="">(main folder)</option>');
foreach ($dirs as $val){
echo('<option value="'.$val.'">'.$val.'</option>');
}
echo('
</select> 
<input type="submit" name="submit" value="submit" class="inputs"> 
</form>');
echo('<hr><strong>(b)</strong> save a link (website address)<br>
	<form method="post" action="index.php?a=editdoc&gid='.$_GET['gid'].'&t=link" onSubmit="count()"> 
	<table><tr><td align="right">url address:</td><td><input type="text" name="link" SIZE="20" class="inputs"> example: <em>http://www.democracynow.org/</em></td></tr>
<tr><td align="right">description:</td><td><input type="text" size="20" name="desc" class="inputs"> example: <em>article by Seymour Hirsch</em></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="submit" class="inputs"></td></tr></table>
</form>');
	$GLOBALS['page']->tableEnd("TEXT");
	
	$GLOBALS['page']->tableEnd("TAB");
	
?>