<html><head>
<title>Help</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<?
include "../cssstyle.php";
include "../config.php";
?>
</head>
<body bgcolor="<? echo $cl_tab_top ?>" text="#000080">

<font size="+2"><b>Help</b></font>
<hr>
<?
if (strstr($topic,"add.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Add new product
</b></font><br>
Here you can add new product.<br>
Specify product name, image filename, description, category to be placed in (or a new category if needed) and price. Please follow the recommended image dimensions, or the product will be too big in shop and it won't be good for the product look. The product will be entered into database and will appear in shop (if marked as 'Available', of course).
EOT;
} elseif (strstr($topic,"config_edit.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Edit config
</b></font><br>
This page contains list of parameters for your shop and their values.<br>
Each parameter is commented for you to understand its meaning.<br>
Please be careful with the values, incorrectly specified values can make your site stop working properly.<br>
Make a point of the ability to change colors of outlook, and font style. It can really make your site look different.<br>
When you are done with the values, press 'Update' button, and the config will be updates right away. Now you can see how the site looks now!
EOT;
} elseif (strstr($topic,"disc_coupons.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Send discount coupon
</b></font><br>
Here you can send discount coupon(s) to any person you like.<br>
Specify the recipient's e-mail address or leave empty this field if you want to send discount coupons to every user in customers database.<br>
Enter the USD amount of the discount for fixed discount.<br>
Note that user cannot make a purchase if total is less than discount.<br>
For percent discount, enter value in percents and set type to '%'<br>
Specify how many times this discount may be used (default is 1), and the expiration date (default 1 month forward).
EOT;
} elseif (strstr($topic,"discounts.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Discount rates
</b></font><br>
This section controls discounts at your shop.<br>
All discounts are calculated upon calculated sum of order.<br>
The discount values are made in the following way: for all orders more than specified amount, there will be a specified USD discount.<br>
Each discount may de deleted, and you can enter a new discount and see new discount ranges.
EOT;
} elseif (strstr($topic,"f_products.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Featured products
</b></font><br>
The featured products are displayed on main page of the shop.<br>
You can enter the order in which the products will be displayed, and the products (ProductID) to be displayed.<br>
Each product in list can be modified or deleted, or you can enter a new one.
EOT;
} elseif (strstr($topic,"index.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Main page
</b></font><br>
This is the main page of admin back office.<br>
In any section, by clicking on 'Get help' you can get help for this section.<br>
EOT;
} elseif (strstr($topic,"main.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Products database
</b></font><br>
This is the place where you manage your products.<br>
You can add/delete/modify each product, or create upselling links for the products.<br><br>
The input fields are: product name, availability checkbox (if not checked, the product will not be displayed to customers), product image filename, description, category (existing, select from list, or enter a new one in the textbox), and the price. Upon change, press 'Apply' button.<br><br>
You can enter into 'Link ID' field the id of product, which will be displayed in "We also recommend you" list when user puts this product into cart. If checked 'Create two-tier link', the linked product will be linked itself with the current product.
EOT;
} elseif (strstr($topic,"orders.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Orders
</b></font><br>
This is the place where you manage your orders.<br><br>
You can specify the query parameters, or leave them unchanged in order not to limit query by this field. The default values of parameters don't limit query. If you click 'Display today orders', the order date will be set from today to today.<br><br>
When you get a list of orders, you see order id, username of a person who made the order, order total and date, order feature and status, and products the customer has ordered. If order festure is 'Gift', then actually the person, whose credit card was used, issued the gift certificate, and the person under first/last name and shipping address, redeemed it. It looks like the customer has really made a gift, and wished it to be sent to different shipping address than his own. if order feature is 'Reward', it means that the customer by order time has accumulated on his account enough points in USD value to pay the order, and his credit card must not be charged, it is really a reward for his buying activity. Click on order fields, and an 'Order details' window will pop up, from this window you can print order details, send customer shipping information (generated from template), send customer decline notification, or issue him a discount coupon.<br><br>
In order list you can change the status of an order, just select the state from list, and it will be updated at once. And by clicking on product, you'll get a pop-up window displaying product details.
EOT;
} elseif (strstr($topic,"search.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Search
</b></font><br>
This page displays search results matching your query.<br>
You can specify the query parameter in the left column under 'Search' header.<br>
Either specify the id of a product to quickly find a product with this id, or string in the product name/description.
EOT;
} elseif (strstr($topic,"shipping.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Shipping rates
</b></font><br>
This section controls shipping costs at your shop.<br>
All shipping costs are calculated upon calculated sum of order.<br>
The shipping cost values are made in the following way: for all orders more than specified amount, there will be a specified USD shipping cost.<br>
Each shipping rate may de deleted, and you can enter a new shipping rate and see new shipping rate ranges.
EOT;
} elseif (strstr($topic,"stats.php")) {
echo <<<EOT
<font color=#CC6600 face=verdana,arial,helvetica><b>
Graphical statistics
</b></font><br>
This is where you can see graphical statistics of your shop activity.<br><br>
There are four graphs, namely:
<ul>
<li>Sales by hour. Taken for all the time. You can see in what hours your shop is most actively visited.
<li>Sales this month. You see shop activity for the current month.
<li>Sales this year. The same for this year.
<li>Customers by country. The pie displays registered customers by their countries.
</ul>
EOT;
} else {
echo <<<EOT
No help available for this context.<br>
EOT;
}
?>
<hr>
<center><form><input type="button" value="Close" onClick="self.close()"></form></center>
</body></html>
