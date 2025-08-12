<?php
if($action == "decoder")
{
	include "decoder.php";
}
else if($action == "codegen")
{
	include "codegen.php";
}
else if($action == "decoderp")
{
	include "decoderp.php";
}
else if($action == "codegenp")
{
	include "codegenp.php";
}
else if($action == "batchgen")
{
	include "batchgen.php";
}
else if($action == "batchsearch")
{
	include "batchsearch.php";
}
else
{
?>
<title>Animal Crossing Code Project main screen</title>
<center>
<h1>Animal Crossing Code Project</h1>
<h3>Original by MooglyGuy / UltraMoogleMan</h3>
<h5>Ported to PHP by Gary Kertopermono</h5>
Welcome to the Animal Crossing Code Project. This page is dedicated to the Code Generator and Decryptor, both programmed by MooglyGuy. Here you can also find some other helpful things.<p>
Main:<br>
<a href="?action=decoder">Code Decoder</a><br>
<a href="?action=codegen">Code Generator</a><p>
Advanced versions:<br>
<a href="?action=decoderp">Code Decoder+</a><br>
<a href="?action=codegenp">Code Generator+</a><p>
Extra:<br>
<a href="./itemlisting.php" target="_blank">Item list</a><br>
<a href="./itemlistingp.php" target="_blank">Item list+</a><br>
<a href="./alllisting.php" target="_blank">All list</a><br>
<a href="./alllistingp.php" target="_blank">All list+</a><p>

<a href="?action=batchgen">Batch-Gen</a><br>
<a href="?action=batchsearch">Batch-Search</a><p>
Documents:<br>
<a href="./readme.txt" target="_blank">Readme</a><br>
<a href="./readme-original.txt" target="_blank">Official Readme</a><br>
<a href="./changes_decoder.txt" target="_blank">Changes - Decoder</a><br>
<a href="./changes_codegen.txt" target="_blank">Changes - Code Generator</a><p>
<a href="./gpl.txt" target="_blank">The GNU General Public License (GPL)</a><br>
</center>
<?php
}
?>
<hr>
<center><font size=1>Go to: <a href="?action=.">Index</a> - <a href="?action=decoder">Code Decoder</a> - <a href="?action=codegen">Code Generator</a><br><a href="?action=decoderp">Code Decoder+</a> - <a href="?action=codegenp">Code Generator+</a><br>Created by Gary Kertopermono.<br><a href="./gpl.txt" target="_blank">The GNU General Public License (GPL)</a><br>Visit <a href="http://www.multiverseworks.com" target="_blank">Multiverse Works</a><br>Go <a href="http://www.zophar.net/personal/mooglyguy/codegen.html" target="_blank">here</a> for the original code generator.</center>