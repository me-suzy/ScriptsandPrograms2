Random Image Display script
Author: Nenad Motika <nmotika@bezveze.com>
URL: http://www.bezveze.com/skripte/rid/
Date: 21-08-2001.
Upgrade: 28-11-2001. (multiple random images on one page).
Usage: Put the script (rid.php) in image folder and include it in php document you want. Or in html/shtml/* files put
<img src="/path_to_the_script_folder/rid.php?pic=random">.
If you need more than one picture on your page, then put
<img src="/path_to_the_script_folder/rid.php?pic=random1"> 
<img src="/path_to_the_script_folder/rid.php?pic=random2"> 
<img src="/path_to_the_script_folder/rid.php?pic=random3"> etc...

HELP :)

If you have problems with proxy/cache servers put next two lines in the beginning of page                                      #
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0

If you want some additional info about picture put this before last "}"
echo "<br>$slika<br>";
echo "Width: $dimensions[0]<br>";
echo "Height: $dimensions[1]<br>";
echo "Image type: $dimensions[2]<br>";
echo "Dimensions: $dimensions[3]<br>";*/


