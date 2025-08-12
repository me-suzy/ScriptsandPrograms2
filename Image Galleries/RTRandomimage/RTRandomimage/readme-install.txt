RTRandomImage ver 0.1

This script is a very simple php random images and text loader that doesn't requires mysql to run.

INSTALLATION

1. In the page where you want to run your script write the following line of code as show in index.php:
   <?php include("/path_to/random.php"); ?> on the top of the page   
   <?php echo "<img src=\"$image_folder/$image_name\" alt=\"$image_name\" />";?> where you want to show the random loaded image
   <?php include ("$image_folder/$text_name");?> where you want to show the relative text
2. Upload all files
3. Upload your images(format .jpg) and the relative text(format .txt) in your /images folder with this scheme:
First image:	1.jpg	text:	1.txt
Second image:	2.jpg	text:	2.txt
...
n image:	n.jpg	text:	n.txt

If you have any comment or critic please write me(webmaster@toldo.info)