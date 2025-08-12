Welcome to HJB Flash Cart, the 100% Flash shopping cart that makes your users experience as seemless and easy as possible.
This project was created by the programmers at NationWideShelving for selling shelving and garage products.  When we realized
how innovative this cart is, we determined to make it available to others at a cost hundreds of times lower then our own development
cost.  

HJB stands for Hiram James Bertoch, who is the project manager for this shopping cart.

***************************************************************************************************************************
*****************************************COPYRIGHT 2005 YOU MAY NOT USE THIS WITHOUT PERMISSION****************************

HJB IS PROVIDED "As Is" FOR USE ON WEBSITES WHERE A LICENSE FOR SUCH USE WAS PURCHASED.  IT MAY ONLY BE USED ON ONE SITE PER LICENSING
FEE.  IN ORDER TO USE ON ADDITIONAL SITES, ADDITIONAL LICENSES MUST BE PURCHASED.  


THE PHP SCRIPTS MAY BE ALTERED, AS LONG AS THE CREDIT LINE AND LINKS AT THE BOTTOM OF EACH PAGE REMAIN. THE FLASH MAY NOT IN ANY
WAY BE CHANGED OR ALTERED.  ANY VIOLATION OF THESE TERMS WILL RESULT IN THE FORFEITING OF YOUR RIGHT TO USE THIS SOFTWARE.

NationWideShelving.com does not guarantee this software in anyway.  You use this at your own risk.  NationWideShelving or any of its
employees or subsidiaries are not responsible for any damage, and / or loss of business, reputation, or other damages of any kind
which are caused whether actual or not, by the use of this product.  By using this product you agree to hold NationWideShelving, its
employees, and all subsidiaries harmless for any and all reasons associated with your use of this product.

Your installation of this software consititues an agreement to these terms.

****************************************************************************************************************************

INSTALLATION INSTRUCTIONS:
Installation is very easy.  Follow theses step by step instructions and your shopping cart will be live in no time.

What you need to run these scripts:
1.  A web hosting account that supports PHP, and MySQL.
2.  FTP Software to load these files to your hosting server.

SETUP
1. Unzip the files.
2.  Configure your cart.
	A.  Open /library/db.php, and follow the instructions found in that file to confiqure your cart.
	B.  Open /library/inc/validate_user.php and configure your admin username and password.
3. FTP files to the server
	A.  images folder must be in the root or main folder of your website.
	B.  update must be in the root or main folder of your website.
	C.  library must be in the root or main folder of your website.
	D.  cart.php, cart.swf, load_products.php, and process_order.php can be anywhere on your website, but must all be in the same folder.
4.  Permissions
	A.  UNIX users must chmod images/cart 777
5.  Database
	A. Create a new mysql database, and run the sql found in database.sql.  This will create the tables you need for your
		shopping cart.
6.  Go to www.yoursite.com/update
	A.  Login with the username and password you created.  The default are u. admin p. temp.
	B.  Click on "Manage Sites".
	C.  You can add as many categories as you like.  These will appear on the left side of your HJB flash cart, and will be 
		used to categorize your products.
	D.  You can now add prodcuts to your shopping cart.  Once added, a product can be assigned to multiple categories.  A
		Product will appear on your user's screen whenever they select a category the product has been assigned to.
	E.  Orders will be sent to your email, as well as stored in the database.  You can view your orders by logging into the control
		panel.
		
#############################################################################################################################
#############################################################################################################################
Note:
Macromedia Flash does not support any image type other then .jpg.  All jpg's must be non-progressive to appear in Flash.  This
is a shortcomming of Flash that they will hopefully some day correct.
