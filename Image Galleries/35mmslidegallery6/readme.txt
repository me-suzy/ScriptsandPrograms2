*************************************************
*                                                                              
*                35mm Slide Gallery 6.0
*                                                               
*                            by                               
*                                                              
*             www.andymack.com/freescripts/  
*                 
*************************************************

I decided not to reply any email due to:
1. I am too busy, sorry
2. My script should be very easy to use, sorry if you don't think so
3. There is no free lunch in this world, sorry


IF THE THUMBNAILS DOESN'T SHOW
1. Don't Email me
2. That means your server doesn't have G.D. library 2.01, either consult your web hosting company to upgrade the php version or set $thumb = false  (will run much small slower)in the config.php file.

IF THE UPLOAD MODULE DOESN'T WORK
1. Don't Email me
2. Most likely, it means the $abpath in the config.php file isn't set properly.

IF IMAGES DON'T SHOW PROPERLY
1. Don't Email me
2. Try not to use directories/files named with special characters. Preferably without space too.



Feel free to modify the code.
Nothing will be charged as it is distributed under GENERAL PUBLIC LICENSE.

INSTALLATION
1. Upload everything to to the same directory except the following files. (upload php files in ASCII mode)
    -slide.psd (photoshop 6 file, in case you want to change the design of the slide mount)
    -slidev.psd  (photoshop 6 file, in case you want to change the design of the slide mount)
    -GPL.TXT
    -readme.txt

2. Maintain the same hierarchy of both directories and files.
     /index.php
     /config.php
     /delete.php (optional)
     /upload.php (optional)
     /popup.php
     /thumbs.php
     /header.inc
     /footer.inc
     /gallery.css
     /slide_01.gif
     /slide_02.gif
     /slide_04.gif
     /slide_05.gif
     /slidev_01.gif
     /slidev_02.gif
     /slidev_04.gif
     /slidev_05.gif
     /imagefolder1/  (put images here, you can change the name)
     /imagefolder1/album.txt  (album description file, you can modify it or delete it)
     /imagefolder1/koalalikefather.jpg  (image for testing)
     /imagefolder1/koalalikefather.jpg.txt  (corresponding caption file)
     /imagefolder2/  (put images here, you can change the name)

3. edit the variables in the config.php file

5. The script will still work without the upload.php module, however, if you use the upload module to upload files, change the mode of the root directory as well as imagefolder1 and imagefolder2 (if you use them) to 777.

6. The default LOGIN and PASSWORD to the upload.php are both 'admin'. They can be changed in the config.php file.

4. Redesign the slide mounts if necessary

5. Upload images to imagefolder1 and imagefolder2 (rename these directories at your will)

6. *Add caption for images if you want to. If your image file is called "hello.jpg", your caption file for that image should be "hello.jpg.txt". 

7. *Upload a description file "album.txt" for that particular album if needed. It works fine even without it.

8.  Create more directories if needed.

9. Delete the whole directory using the Delete Module. All files within the directory will be deleted as well as the directory.

10. Enjoy! Donation is optional and appreciated. :)

*The new upload module can now add captions and album description.

www.andymack.com/freescripts/

Email me if you find any bugs. 
support@andymack.com