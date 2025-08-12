<?php 

include "config.php";
include "incl/pss.inc";
include "incl/head.inc";

if(!isset($topic)){redirect("admin.php?f=$f");}
else{$file="$data/$topic";file_allowed($topic);} 

$fs=open_file($file);
$fs=explode("\n",$fs);

if(isset($line)&&isset($subject)&&isset($post)){
$line=(int)$line;
$subject=str_replace(':|:','',$subject);
$post=str_replace(':|:','',$post);
$post=str_replace("\r",'',$post);
$post=str_replace("\n",'<br />',$post);
$subject=stripslashes($subject);
$post=stripslashes($post);
$row=explode(":|:",$fs[$line]);
$row[1]=$subject;
$row[3]=$post;
$fs[$line]=implode(':|:',$row);
$fs=implode("\n",$fs);
save_file($file,$fs);
die("<script type=\"text/javascript\">a='adminsh.php?f=$f&topic=$topic';window.location=a;</script></head></body><body></body></html>");
}
?>
</head><body>
<?php

for($i=0;$i<count($fs);$i++){
$row=explode(":|:",$fs[$i]);
print '<form action="admined.php" method="post">'."\n";
print '<input type="hidden" name="f" value="'.$f.'" />'."\n";
print '<input type="hidden" name="topic" value="'.$topic.'" />'."\n";
print '<input type="hidden" name="line" value="'.$i.'" />'."\n";
print '<input type="text" style="position:relative;width:60%" name="subject" value="'.$row[1].'" /><br />'."\n";
$msg=str_replace('<br />',"\r\n",$row[3]);
print '<textarea cols="20" rows="8" name="post" style="position:relative;width:60%">'.$msg.'</textarea>'."\n";
print '<br /><input type="submit" value="Save" />'."\n";
print '</form><br />'."\n";
}
?>
</body></html>