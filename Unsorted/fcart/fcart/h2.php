<html>
<head><? include "meta.php" ?>
<title>Help</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<?
include "cssstyle.php";
include "config.php";
?>
</head>
<body bgcolor="<? echo $cl_tab_top ?>" text="#000080">

<font size="+2"><b>Help</b></font>
<hr>
<?
if ($topic == "newcustomers") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Assistance for New Customers
</b></font><br>
Shopping on our site is extremely easy. Browse the shop, when you add an item to your shopping cart, you will see you cart contents. Then click to 'Order' tab, or order link down the view cart page, enter your personal information (everything is on one page), and you're done with the ordering!
EOT;
} elseif ($topic == "registering") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Registering at our shop
</b></font><br>
Register at our shop!<br>
<ul>
<li>registration is ablsolutely free!
<li>registered users get wish list stored for forever
<li>registered users can order in 1-click
<li>registered users can issue gift certificates
<li>registered users can get discount coupons
<li>registered users can receive rewards, depending on how much they spent at our shop for all time
</ul>
To register, click on 'Register for free' link in the upper-left corner. You will get a simple form, just fill it out, and you're registered now!
EOT;
} elseif ($topic == "usingshopcart") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Using the Shopping Cart
</b></font><br>
The shopping cart is easy to use.<br>
Browse the shop using the categories box on the left side, add items to your shopping cart (optionally to wish lists), then go to checkout and finish your order.<br><br>
On 'View cart' page you can manage your cart in many ways. Here you can see your shopping cart contents and optionally wish list. A product can be moved from wish list to cart, and deleted from wish list or cart.<br>
If you wish to change product quantity in the following way: click on product name, a window will pop-up, select positive quantity to increase the product quantity in the shopping cart, or enter negative quantity, to reduce it.<br><br>
If you wish to remove everything from your shopping cart, click on 'Clear shoping cart' link, the same is for wish list.
EOT;
} elseif ($topic == "wishlists") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Wish list
</b></font><br>
Besides your shopping cart, there exist wish list for every customer.<br><br>
Here you put the items, which you wish to buy, but can't afford now. Or you can plan to buy something later, then you ut this product to wish list, and when you're ready to buy, just move selected item to shopping cart.<br><br>
For registered users, the wish lists are stored forever, so you can store items in it for a long time. The cart contents are stored also (in case you get internet connection problems), but they are cleared if cart contents didn't re-new for long.
EOT;
} elseif ($topic == "orderpage") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Order page
</b></font><br>
This is the last step before the order is done.<br>
You will get brief list of products in cart, their quantity and cost.<br>
The subtotal, shipping cost, and discount (if applicable) will also be displayed.<br><br>
In the discount coupon box you can enter a discount coupon number, if you have one, and get additional discount. It will also be displayed before order total value.<br>
The 'total' is actually the amount that your credit card will be charged.<br><br>
Below you have to enter your personal information necessary for the order, or, if you have registered at our shop, only the credit card info, or, if you have entered it already, just the password to confirm your identity. Please fill out the forms carefully, especially shipping address and credit card information.<br><br>
Upon pressing the 'Confirm' button, you will get a confirmation message that your order has been processed, and you'll receive an e-mail with the order receipt. Shortly after, you'll get another e-mail saying that your order was mailed to you, and the shipping time.
EOT;
} elseif ($topic == "shopguarantee") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Safe Shopping Guarantee
</b></font><br>
While shopping, you are guaranteed of safe shopping.<br>
Nobody but you can do purchases, send gift certificates, or change your account data, since these all are password protected, and password is transferred using httpd, preventing anyone from intercepting your information. All data stored on the server, and only authorized personnel has the access to it. We don't disclose personal data for any reason, since we respect anyone privacy.
EOT;
} elseif ($topic == "giftissue") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Send a gift certificate
</b></font><br>
Gift certificates are the perfect solution when you just can't seem to find the right gift or you've waited till the last minute. Gift certificates make the perfect present for friends, family, and business recipients.<br>
To send a gift certificate, select 'Gift certificate' in categories box, and fill out the form. Note that you must be registered to do so.<br>
When, you send a gift certificate, the recipient will receive unique id, for which he can make purchase for the specified dollar amount. When he actually has made a purchase, you will receive an e-mail telling you what have been ordered.
EOT;
} elseif ($topic == "giftredeem") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Redeem a gift certificate
</b></font><br>
If you have received a gift certificate, you can redeem it at any time at our shop. Just enter the id you have got from us into 'username' field in login box, and you'll log in using this Gift Certificate, and make a purchase. Note that purchase can be done only one time, if you haven't used the whole amount of certificate, you won't be able to shop for the rest amount of gift certificate.<br>
EOT;
} elseif ($topic == "whenarrive") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
When your order will arrive
</b></font><br>
Some time after the order was made, you will receive an e-mail confirming that the products you ordered, were mailed to you. The delivery time will also be in this e-mail letter.
EOT;
} elseif ($topic == "internationalorders") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
International shipping rates
</b></font><br>
Our shipping rates are worldwide, independent of your location. We ship around the planet cheaply and fast.
EOT;
} elseif ($topic == "returnpolicy") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Return policy
</b></font><br>
Unfortunately, you cannot return things that you have received from us.
EOT;
} elseif ($topic == "editdata") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Edit your account information
</b></font><br>
Logged in, you can change your account data any time, thus keeping you account data current.<br>
Click on 'Edit info' link in the upper-left corner.<br>
You will get the form similar to the one you have filled out when registering. Here you can change anything you like, and you have to enter your credit card information here, to save it for future orders, and not to enter it when ordering.<br>
EOT;
} elseif ($topic == "orders") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Orders
</b></font><br>
You can always view your previous orders history.<br>
Having logged in, click on 'Orders history' link in the upper-left corner.<br>
You can specify the query parameters, or leave them unchanged in order not to limit query by this field. The default values of parameters don't limit query.<br><br>
When you get a list of orders, you see order id, order total and date, order feature and status, and products ordered. Click on order fields, and an 'Order details' window will pop up. And by clicking on product, you'll get a pop-up window displaying product details.<br><br>
If the order feature is 'Gift', then this order was actually made by the person, whom you have sent a gift certificate. You can his data by clicking on order.<br><br>
If the order feature is 'Reward', it means that you have used your points earned on our shop, to pay your order. Your credit card will not be charged, and it's really the reward for your good buying activity.<br>
EOT;
} elseif ($topic == "cancelling") {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Cancelling
</b></font><br>
If somehow you wish to cancel your order and have not received a shipping notification, you can send e-mail to orders@fcart.com, saying that you want to cancel order number N. But if the products have been already mailed to you, you cannot do it.
EOT;
} else {
echo <<<EOT
No help available for this context.<br>
EOT;
}
?>
<hr>
<center><form><input type="button" value="Back" onClick="self.history.back()">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Close" onClick="self.close()"></form></center>
</body></html>
