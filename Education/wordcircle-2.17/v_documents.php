<?php


if (!file_exists("c".$_GET['gid'])) { 
       mkdir("c".$_GET['gid']);} 

 $doc=array();
 $path = "c".$_GET['gid']."/";    // Open the folder
 
 if(isset($_GET['dir'])){
 	$path .= $_GET['dir']."/";    //for subdirectories
 }
     
 $dir_handle = @opendir($path) or die("Unable to open $path - your webserver may not have privelages to create directories - check with your admin");  
   // Loop through the files    
   while ($file = readdir($dir_handle)) {    if($file == "." || $file == ".." || $file == "index.php" || strstr($file,"mpub")<>false || strstr($file,"mpriv")<>false )        continue;
   if(is_dir($path.$file)){
   $temp['files'] = "<a href='index.php?a=documents&gid=".$_GET['gid']."&dir=".$file."'><img src='icon_fold.gif' width='15' height='13' alt='' border='0' align='top'> ".$file."</a><br />";
   array_push($doc,$temp);
   }
   else{
   $temp['files'] = "<a href='".$path.$file."'><img src='icon_file.gif' width='14' height='16' alt='' border='0' align='top'> ".$file."</a><br />";
	array_unshift($doc,$temp);
	} 
	}    // Close   
   closedir($dir_handle);
   $doc = array_reverse($doc);
   
	$linkArray[0] = "index.php?a=editdoc&gid=".$_GET['gid'];
	$GLOBALS['page']->tableStart("","100%","TAB","<a class='tabanchor'  href='index.php?a=documents&gid=".$_GET['gid']."'>Course Materials</a> &nbsp;",$linkArray);

	if(isset($_GET['dir'])){
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo('<img src="icon_foldup.gif" width="15" height="13" alt=""><a href="index.php?a=documents&gid='.$_GET['gid'].'"> Go up to main folder</a><br>Current subfolder: <strong>'.$_GET['dir'].'</strong> ');
	$GLOBALS['page']->tableEnd("TEXT");
	
	}

	if(count($doc)>0){
	
	
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($doc,"odd","even","40%");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no materials in this folder");
	$GLOBALS['page']->tableEnd("TEXT");
	}	
	//start links
	
	$result = $GLOBALS['db']->execQuery("select id, descr, name,descr from documents where group_id = ".$_GET['gid']." order by name");
	$i = 0;
	$doc = array();
	while ($row = mysql_fetch_assoc($result)) { 
       $doc[$i]['web links'] = "<a href='".$row['name']."'><img src='icon_link.gif' width='16' height='16' alt='' border='0' align='top' hspace='1'>".$row['descr']."</a>";
	   $i++;
   }
	
	if($_GET['a']=='documents'){
	$linkArray[0] = "index.php?a=editdoc&gid=".$_GET['gid'];
	$GLOBALS['page']->tableEnd("TAB");echo("<br>");
	$GLOBALS['page']->tableStart("","100%","TAB","links",$linkArray);
	}
	
	if(count($doc)>0){
	;
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($doc,"odd","even","40%");
	$GLOBALS['page']->tableEnd("GRID");
	}
	
	$GLOBALS['page']->tableEnd("TAB");
	
	?>