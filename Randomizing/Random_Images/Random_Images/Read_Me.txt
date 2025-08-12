
Skorch's Random Image Display

Version 1.0) Random image script generates a w3 valid img tag with text caption.
Version 1.1) Added MySql db support. Also added an add image form. 
Version 1.2) General code refinements. Got the add multiple image sets structure done but not implemented. It was producing errors when two instances of sets were on 1 page...

I would like credit but if you want to razz the comments for size feel free. if you really like this script you can show your appreciation by writing a link to my cliff jumping website http://12feetunder.com. I'm trying to stimulate a nautral linking campaign so choose your favorite page and hook it up with some unique anchor text and maybe a quickie description! You can also contact me with ideas, updated versions and help questions

Coming Soon...Better Admin Section/Options! Add an image set!

Installation for advanced php users

1) edit include.php to reflect your settings.
2) run install.php with your browser. When done delete install.php.
3) Goto test.php, Do you see the form? if Yes congrats and have fun.

installtion for new php users

1) Create a mysql_db, Use phpmyadmin or the add_new_db button provided by your host.
2) Name the db, assign a password and then specify a user(all of this is done on your host account).
3) Open include.php and edit the variables found there(they are the ones you assigned when you created the db.
4) now open the file install.php in your browser. This creates the sql table structure. Delete when done.
5) Now Goto test.php You should see a form(if you right click the page and then select "view source" you should see an empty img tag and h1 tag.)
6) Congratulations!

Insertion of Info. 

1) If you prefer you can use phpmyadmin(you can see your info and correct typos. Phpmyadmin also takes care of escaping control chars.)
2) If you want you can use the form. 
  2a) The first field is your image class, defined by your css. if you do not use css this is useless. if you do this field allows you to specify different sizes based on your css definitions.
  2b) The next field is the url of the image. I always use full paths {http://your_site.com/path/to/image.jpg} lose the brackets.
  2c) the next field is the alt tag. This field is necessary for a strictly validated img tag. Some viewers are blind while others disable images. The alt tag should describe the image without keyword stuffing. The use of one keyword phrase is recommended. See bottom of read_me for better description.
  2d) This field allows you to set a text caption between <h1></h1>. If you load a pic off a green donkey in the url field you want the caption to say "green donkey". the caption for a pic of a blue donkey should say "Blue Donkey".
3) Now click "Insert". An id is generated and this new row is inserted. Repeat until you are satisfied. That's all, unless you forgot to upload your pics to the named dir(step 2b)!
4) Error correcting or file dumping is done via phpmyadmin. WARNING if you specify a value for id this script will not display all rows. leave it blank and let the script take care of that value.

Displaying image sets

1) write <?php include('Ran_Img.php'); ?> on your page where you want the image to appear.
  1a) You just have to upload images
2)I'll wait for V1.3 to get into image set selection!



The use of one keyword phrase is recommended. I.e. imagine a pic of a blue donkey standing in a meadow good tag alt="A blue donkey standing in a meadow at sunset" spammed tag alt="Blue donkey farms sells blue donkeys and blue donkey related accessories" it should be obvious which tag is more descriptive of the pic and you still got to include a keyword phrase "blue donkey" and possibly created two other keywords"meadow and standing"(this will work if you optimize the rest of your page to take adv. of the new keywords. this was an example, Choose better keywords for your site! Google images also likes descriptive alt tags. it reads the alt tag and surrounding text to guess at the "subject" of a pic. It then returns your image when a search is done for images of that "subject". It could mean a conversion if the image viewer clicks through to your site!