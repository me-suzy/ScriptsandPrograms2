<?

#PHP Script by Ogün MERÝÇLÝGÝL, ©2000 ogun@photoshoptools.com

#---------------------------------------------

#<Professional Links v1.2>

#<By Ogün MERÝÇLÝGÝL from TURKEY/ISTANBUL>

#<ogun@photoshoptools.com>

#<http://www.photoshoptools.com/plinks

#---------------------------------------------

$plinks = file("./links.txt");

$plinks = join($plinks,"");

$s = "`".substr($HTTP_GET_VARS["go"], 0, 15)."`";

$plinks = split($s,$plinks);

print Header("Location: http://$plinks[1]");

?>