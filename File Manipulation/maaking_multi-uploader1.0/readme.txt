//---------------------------------------//
// (M@@king) Multi-File Uploader
//---------------------------------------//

               Contents
- About
- Description
- Instructions
- License


//---------------------------------------//
1- About

Author: 	Mohammed Ahmed(M@@king)
Version:	1.0
Date:		31.08.2005

E-mail:		m@maaking.com
MSN   :         m@maaking.com
WWW   : 	http://www.maaking.com

//---------------------------------------//

2- Description:


- If you really want to know how to upload multiple files using php,
   then you have just chosen the correct script.
- Go through it and read every line, there is a small description for each code line.
- If you don't understand any code line,
   please feel free to contact me: E/MSN: m@maaking.com

what we used?

-The Super Global Variable $_FILES is used in PHP 4.x.x.

   $_FILES['upload']['size'] ==>     Get the Size of the File in Bytes.
   $_FILES['upload']['tmp_name'] ==> Returns the Temporary Name of the File.
   $_FILES['upload']['name'] ==>     Returns the Actual Name of the File.
   $_FILES['upload']['type'] ==>     Returns the Type of the File.

                        ****************
-Functions used

1- (!is_dir("$upload_dir"))
   checks if the directory exist or not.

2- (!is_writeable("$upload_dir"))
   checks if the directory is writable.

3- (is_uploaded_file($_FILES['filetoupload']['tmp_name'])
   Checks first if a file has been selected

4- (!in_array($ext,$limitedext))) {
   checks file extension eg. "gif,jpg etc."

5- (file_exists($upload_dir.$filename))
   Checks if file is Already EXISTS.

6- (move_uploaded_file($_FILES['filetoupload']['tmp_name'],$upload_dir.$filename))
   Moves the File to the Directory of your choice eg. "images"

- Its very simple and powerful script.
- Enjoy



//---------------------------------------//

3- Instructions:

Edit upload.php using a text/php editor
and edit the configurations if needed.

Upload all files in "upload" directory
to your webserver. eg. (yoursite.com/upload/upload.php)
                                           /images
CHMOD images directory to 777.
 
Run it! (yoursite.com/upload/upload.php)

Have fun!

//---------------------------------------//
4- License

License is Free (distribution and modification is allowed inorder to learn PHP)
If you think that you developed my script please e-mail me.

Thanks a lot and have a nice DaY ;)
//---------------------------------------//