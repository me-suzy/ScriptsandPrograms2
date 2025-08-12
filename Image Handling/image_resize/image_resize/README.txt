Created: Nov 18,2004 
     By: Ryan Stemkoski
Contact: ryan@ipowerplant
Website: http://www.ipowerplant.com

Version: 1.0


This script was created due to my inability to find what I needed online.  The goal of the script was to create a function, which could successfully resize an image with the fewest amount of supplied parameters possible without third party software being installed.  The resized image was stored and the function ends by supplying the path to the image for storage in a database or printing to the browser.   This script can easily be used as a function within an upload script to resize an image on the fly.  Multiple instances of the script can be executed in one document by changing the $newfilename variable for each so that the images do not overwrite each other.

How to use:
I have included an example of the script in use.  Simply include the function script named (resize.php) in your document then specify the parameters listed and time image will be resized, saved, and the path to it will be returned.

Problems:
It does require the GD library and you must have write/execute permissions for the folder where the image will be created.  Make sure that the folder the path is pointing to (example: imgs/) is created. 

Script will only resize: .gif, .png and .jpg images.  Other image or file types will cause an error.

Enjoy!
 
If you have any comments or questions you can contact me at: ryan@ipowerplant.com

