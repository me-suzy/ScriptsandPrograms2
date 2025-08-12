This script will show any image (JPEG, GIF or PNG) and automaticly will place logo over the image (logo is just another image).
This script will also bring you an original image protection. Path to original image isn't visible, so users can't download them.

Now to the script:

The script is just single file. It's name can be anything you like. So you can have as many copies of this script as you want. Each script can have it's own settings.

How to call this script?

Very simple: http://www.domain.com/scriptname.php?img=imagename
Where imagename is name of the image.
Real image path isn't the imagename. Real path will be image_path + imagefile.
image_path is defined inside of the script Wink (you can call it secret path)

Script settings:

You will find some vars (that have to be set) inside of the script.

$image_quality - quality of JPEG conpression [0-100]

$image_path - path to images
examples: "./" - all images are in the same dir as the script
"./images/" - all images are in subdir called images

$logo_path - path and name of the LOGO image (can be PNG,GIF,JPEG).
example: "./logo.gif"

$logo_pos_x - Horizontal position of the logo.
Should be set to: left, right or center

$logo_pos_y - Vertical position of the logo.
Should be set to: top, middle or bottom

$error_not_found - where image is not found, show this error text

$error_not_supported - where image is not supported, show this error text

$error_bg_color and $error_text_color are background and text color for error messages.
This have to be array, so set it by using [i]array(RRR,BBB,GGG)[/i[
where RRR is RED, BBB is BLUE and GGG is GREEN. All these values have to be in range of 0 - 255