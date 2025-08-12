<?php
////////////////////////////////////////////////////////////////////////////////
//
// EzMap - Sitemap System
// sitemap.php
// Sitemap for your website
// Date Created : 15 October, 2003
// Last Modified: 31 October, 2005
//
// Version 1.01
//
// * This script is released under the terms of the GNU General Public License. 
// A copy of the GPL is included with this script.
//
// * Mystic Dreams Enterprises - http://www.mysticdreams.net
//
////////////////////////////////////////////////////////////////////////////////

// User configuration
// Show size of each file, 1 for YES, 0 for NO
$showsize = 1;

// Array with file types to display and the images to use.
// Syntax: $display['filetype'] = "image";
$display['php'] = "php.gif";
$display['html'] = "html.gif";
$display['htm'] = "html.gif";
$display['shtml'] = "html.gif";

// Array with directories to exclude.
// Syntax: $excludedir[] = "directory";
$excludedir[] = "cgi-bin";
$excludedir[] = "images";
$excludedir[] = "realaudio";
$excludedir[] = "style";

// Array with files to exclude.
// Syntax: $excludefile[] = "filename";
$excludefile[] = "phpinfo.php";

$stime = gettimeofday();

// Set some important stuff
$root = getcwd();

$pre = explode("/", $_SERVER['REQUEST_URI']);
array_pop($pre);
$prefix = join("/", $pre);

// Uncomment the 2 lines below to create a tree of all files and directories on 
// your webserver if the script is in a subdirectory
$root = str_replace($prefix, "", $root);
$prefix = "";

$root .= "/";

// Display server name and directory
echo "<table width=\"80%\" class=\"sitemap_tag2\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
echo "<tr><td><img align=\"absmiddle\" src=\"server.gif\" border=\"0\"> <b>http://".$_SERVER['SERVER_NAME'];
echo "$prefix/";
echo "</b></td></tr><tr><td><img align=\"absmiddle\" src=\"vertical.gif\" border=\"0\"></td></tr>\n";

function get_extension($name) {
$array = explode(".", $name);
$retval = strtolower(array_pop($array));
return $retval;
}

// Recursion! And away we go...
// Set some globals and clean up a bit...
// What a pig...
function list_dir($chdir) {
global $root, $prefix, $showsize, $display, $excludedir, $excludefile;
unset($sdirs);
unset($sfiles);
chdir($chdir);
$self = basename($_SERVER['PHP_SELF']);

// Open the current directory
$handle = opendir('.');
// Read directory. If the item is a directory, place it in $sdirs.
// If it's a filetype we want, put it in $sfiles */
while ($file = readdir($handle))
{
if(is_dir($file) && $file != "." && $file != ".." && !in_array($file, $excludedir))
{ $sdirs[] = $file; }
elseif(is_file($file) && $file != "$self" && array_key_exists(get_extension($file), $display)
&& !in_array($file, $excludefile))
{ $sfiles[] = $file; }
}
		  
// Count the slashes to determine how deep we're in the directory.
// Add lines to make it pretty.
$dir = getcwd();
$dir1 = str_replace($root, "", $dir."/");
$count = substr_count($dir1, "/") + substr_count($dir1, "\\");
		  	  
// Display directory names and recursively list them.
if(is_array($sdirs)) {
sort($sdirs);
reset($sdirs);
			 
for($y=0; $y<sizeof($sdirs); $y++) {
echo "<tr><td>";
for($z=1; $z<=$count; $z++)
{ echo "<img align=\"absmiddle\" src=\"vertical.gif\" border=\"0\">&nbsp;&nbsp;&nbsp;"; }
if(is_array($sfiles))
{ echo "<img align=\"absmiddle\" src=\"verhor.gif\" border=\"0\">"; }
else
{ echo "<img align=\"absmiddle\" src=\"verhor1.gif\" border=\"0\">"; }
echo "<img align=\"absmiddle\" src=\"folder.gif\" border=\"0\"> <a href=\"http://".$_SERVER['SERVER_NAME']."$prefix/$dir1$sdirs[$y]\" target=\"_top\">$sdirs[$y]</a>";
list_dir($dir."/".$sdirs[$y]);
}
}
		 		  
chdir($chdir);
		  
// Run through the array of files and show them.
if(is_array($sfiles)) {
sort($sfiles);
reset($sfiles);
				 
$sizeof = sizeof($sfiles);
			 
// What file types shall we display?
for($y=0; $y<$sizeof; $y++) {
echo "<tr><td>";
for($z=1; $z<=$count; $z++)
{ echo "<img align=\"absmiddle\" src=\"vertical.gif\" border=\"0\">&nbsp;&nbsp;&nbsp;"; }
if($y == ($sizeof -1))
{ echo "<img align=\"absmiddle\" src=\"verhor1.gif\" border=\"0\">"; }
else
{ echo "<img align=\"absmiddle\" src=\"verhor.gif\" border=\"0\">"; }
echo "<img align=\"absmiddle\" src=\"";
echo $display[get_extension($sfiles[$y])];
echo "\" border=\"0\"> ";
echo "<a href=\"http://".$_SERVER['SERVER_NAME']."$prefix/$dir1$sfiles[$y]\" target=\"_top\">$sfiles[$y]</a>";
if($showsize) {
$fsize = @filesize($sfiles[$y])/1024;
printf(" (%.2f kB)", $fsize);
}
echo "</td></tr>";
			
}
echo "<tr><td>";
for($z=1; $z<=$count; $z++)
{ echo "<img align=\"absmiddle\" src=\"vertical.gif\" border=\"0\">&nbsp;&nbsp;&nbsp;"; }
echo "</td></tr>\n"; 
}
}

list_dir($root);

echo "</table><br>\n";

// How long did that take?
$ftime = gettimeofday();
$time = round(($ftime[sec] + $ftime[usec] / 1000000) - ($stime[sec] + $stime[usec] / 1000000), 5);
echo "<div align=\"center\" class=\"sitemap_tag2\">This page was generated in $time seconds.</div>\n";

?>