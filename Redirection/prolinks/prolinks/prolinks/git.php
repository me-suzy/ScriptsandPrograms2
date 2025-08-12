<?
#PHP Script by Ogün MERÝÇLÝGÝL, ©2000 ogun@photoshoptools.com
#---------------------------------------------
#<Professional Links v1.2>
#<By Ogün MERÝÇLÝGÝL from TURKEY/ISTANBUL>
#<ogun@photoshoptools.com>
#<http://www.photoshoptools.com/plinks
#---------------------------------------------
###

# Just one option to set

# What is the input name for the keyword in your form? (You can make it anything... the default is "go")
$anahtar = "go"; # Ex. if you say "go", you can go to keyword "abcdefg" with the url "git.php?go=qwerty"
####

$plinks = file("./links.txt");
$plinks = join($plinks,"");
$s = "`".substr($$anahtar, 0, 15)."`";
if(!stristr($plinks,$s)) {
print Header("Location: bilinmeyen.html");
exit;
}
$plinks = split($s,$plinks);
print Header("Location: http://$plinks[1]");
?>