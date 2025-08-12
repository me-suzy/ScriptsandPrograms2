<?php

function copyandmodifyfile($sourcefolder,$destfolder,$filename,$copyfilename,$linearray,$contentarray)
{

$sourcefilename = $sourcefolder.$filename;
$destfilename = $destfolder.$copyfilename;

$filecontent = file($sourcefilename);

for ($i=0;$i<=count($linearray);$i++){
$templine = $linearray[$i];
$filecontent[$templine] = $contentarray[$i];
}

$targetFd = fopen($destfilename, "w");
for ($i=0;$i<=count($filecontent);$i++){
fputs($targetFd, "$filecontent[$i]");
}
fclose($targetFd);

}

function copyfolder($sourcefolder,$destfolder)
{

@mkdir($destfolder, 0777);
$sourcehandle = opendir($sourcefolder);
      
while ($filelist = readdir($sourcehandle)) {
if (($filelist!=".")&&($filelist!="..")){
copy ($sourcefolder.$filelist,$destfolder.$filelist);
}
      
}

closedir($sourcehandle);

}

?>