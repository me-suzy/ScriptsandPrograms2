<?
include("../include/filefunctions.php");

if($_FILES["filename"]["name"] != "" and $_POST["form_submitted"] == "yes"){
    include_once ("rootdatapath.php");
    includeLanguageFiles('admin','downloads');
    validatefiletypes('Modules');
	
    checkzip($_FILES);
      
    if(isset($GLOBALS["strErrors"])){
        admhdr();
        admintitle(4,"delete module");
        formError(1); 
        
    }
    else{
        Header("Location: ".BuildLink('m_subcontentform.php')."&page=".$_POST["page"]);
    }
}

// form for module upload
function getUploadform(){

safeModeWarning(4);	
//check if /modules is chmoded to 0777
if(!is_writable("../modules")){
$GLOBALS["strErrors"][] = $GLOBALS["modules_home"]." is not writable - installation or deletion of modules impossible.";
formError(1);
}
?>
<form name="forminst" method="POST" action="m_subcontentinst.php" enctype="multipart/form-data">
<table>
	<input type="hidden" name="form_submitted" value="yes">
	<input type="hidden" name="subdir" value="<?= $_GET["subdir"]; ?>">
	<tr class="tablecontent">
		<?php FieldHeading("ModuleName","filename"); ?></td>
		<td valign=top>
			<input type="file" size="50" maxlength="72" name="filename"<?= $GLOBALS["fieldstatus"]; ?>>
		</td>
		<td class="topmenu">
			<input type="hidden" name="MAX_FILE_SIZE" value="<?= $GLOBALS["maxfilesize"]; ?>">
			<input type="submit" value="Upload">
		</td>
	</tr>
	</table>
	</form>
        <?
}// getUploadform()

// checks if zip can be extracted into modulepath
function checkzip(){
	global $_FILES;
	
    // checks if module is zip
    if($_FILES["filename"]["type"] == 'application/zip'){
    	 	
    	$success = UploadNewFile($GLOBALS["modules_home"]);
		if($success){    
       //set modulename
       $modulename = str_replace(".zip", "", $_FILES["filename"]["name"]);
               
        //check if module allready exists
        $savedir = getcwd();
        chdir($GLOBALS["rootdp"].$GLOBALS["modules_home"]);
        $count = 0;
           if ($handle = @opendir('.')) {
              while ($filename = readdir($handle)) {
                if($filename == $modulename){$count++;}
              }
           }
           // if not exists $modulename exstract 
           if($count == 0){$Status = extractfile($_FILES["filename"]["name"]);
           }
           else{
               $GLOBALS["strErrors"][] = $modulename.' already exists in '.$GLOBALS["modules_home"];
           }
           if($Status){ unlink($_FILES['filename']['name']);}
		} else  { $GLOBALS["strErrors"][] = 'Module could not be Uploaded, check permissions on folder /modules';	}   
    }
    else{
        $GLOBALS["strErrors"][] = $_FILES["filename"]["name"].' is not a valid zip-file';
        }
   
}

// extract module into moduledir
function extractfile($module){
$Status = true;
        //including pclzip
        include_once($GLOBALS["rootdp"].$GLOBALS["include_home"].'pcl/pclzip.lib.php');
        //extracting archive
        $archive = new PclZip($module);
        if($archive->extract(PCLZIP_OPT_PATH, $GLOBALS["rootdp"].$GLOBALS["modules_home"]) == 0){
            $GLOBALS["strErrors"][] = $archive->errorInfo(true);
        }
     return $Status;  
}

?>