<?
include("config.php");
echo "<font size=6><u><b>Log Viewer</u></b></font><br><br>";
$folder = $logs;
echo "<u>Single Logs</u><br>";
if ($handle = opendir($folder)) {
    while (false !== ($file = readdir($handle))) { 
        if (is_file("$folder/$file")) { 
            $size = filesize("$folder/$file");
            if($file != "log.html"){
            echo "<a href=view.php?$file>$file</a><br>"; 
            } else {
            continue;
            }
        } 
    }
    closedir($handle); 
}
echo "<br><u>Master Log</u><br>";
echo "<form action=del.php method=POST><input type=hidden name=del value=qwertyuiop321><input type=submit value=Delete_Log></form><br>";
readfile("$logs/log.html");
?>