<?

$file = "comments.txt";

function addcomment() {
global $nick, $comments, $file;

//opening the file comments.txt and preparing it for appending
       $f_write = fopen($file, "a");
//we format the line we want to be added into the file
       $array = "    <tr><td><font face=\"arial\" size=\"2\" color=\"#000000\"><u>" . $nick . "</u>: </font><font face=\"arial\" size=\"2\" color=\"#000000\">" . $comments . "</font></td></tr>" . "\r\n";
//adding the line into the file
       fputs($f_write, $array);
//closing the file comments.txt
       fclose($f_write);
}

?>
