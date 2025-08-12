<?php
echo '<h1>Supported Counter Styles</h1>

<p>Special thanks to Nancy, MEPS, Scott "Webster" Wood, Mike Williams, 
Genie Murasaki, George Carmichael, William Hayden, Russ, TWC Design, 
Scott Remen, David Zhao, Xtremist, Eric Richter, George Carmichael, 
Bill Ingram , Kumiko Isogai, Matthias, Dan Murry and everyone else I 
haven\'t listed for all their great counter digits.</p>

<p><table border=0><tr><td class="boldtitle">text</td></tr><tr><td>0123456789</td></tr></table><br></p>
<p><table border=0><tr><td class="boldtitle">hidden</td></tr><tr><td>// count: 0123456789</td></tr></table><br></p>
';
$path = "digits";
$dh = opendir($path);
while ($file = readdir($dh)) {
	if (is_dir($path."/".$file)) {
		if (!is_file($path."/".$file)) {
			if (($file!=".") && ($file!="..")) {
				echo "<p><table border=0><tr><td class=\"boldtitle\">".$file."</td></tr><tr><td><img src=\"digits/".$file.".gif\"></td></tr></table><br></p>\n";
			}
		}
	}
}
closedir($dh);
?>