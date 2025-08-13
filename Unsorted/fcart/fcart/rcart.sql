# MySQL dump 7.1
#
# Host: localhost    Database: rcart
#--------------------------------------------------------
# Server version	3.22.32

#
# Table structure for table 'cart_data'
#
CREATE TABLE cart_data (
  cart varchar(32) DEFAULT '' NOT NULL,
  productid int(11) DEFAULT '0' NOT NULL,
  amount int(11) DEFAULT '0' NOT NULL,
  wish enum('N','Y') DEFAULT 'N' NOT NULL,
  lastop timestamp(14),
  KEY cart_index (cart)
);

#
# Dumping data for table 'cart_data'
#


#
# Table structure for table 'config'
#
CREATE TABLE config (
  name varchar(32) DEFAULT '' NOT NULL,
  comment varchar(255),
  value text NOT NULL,
  field_order int(11) DEFAULT '99' NOT NULL,
  type enum('text','textarea','checkbox') DEFAULT 'text',
  PRIMARY KEY (name)
);

#
# Dumping data for table 'config'
#

INSERT INTO config VALUES ('default_category','Default category','Books',10,'text');
INSERT INTO config VALUES ('orders_email','FROM field of order receipt','ÊÀ¼Í»¥ÁªÔÚÏßÉÌ³¡ <baomail@371.net>',70,'text');
INSERT INTO config VALUES ('usd_to_dvd','Number of USD to spend to get 1 DVD point','10',30,'text');
INSERT INTO config VALUES ('dvd_to_usd','USD value of dvd point','.01',31,'text');
INSERT INTO config VALUES ('items_per_page','Items per web page','10',4,'text');
INSERT INTO config VALUES ('items_per_nav','Items per navigation bar','50',6,'text');
INSERT INTO config VALUES ('main_title','Page title','ÊÀ¼Í»¥ÁªÔÚÏßÉÌ³¡',1,'text');
INSERT INTO config VALUES ('orders_notification_email','Where to send order notifications','root@localhost',80,'text');
INSERT INTO config VALUES ('gift_logname','Log name to show to Gift Certificate owner','GC',60,'text');
INSERT INTO config VALUES ('items_per_adm_page','Items per admin web page','5',5,'text');
INSERT INTO config VALUES ('affiliates_url','Affiliates sale.cgi URL','http://sav.bpcnet/cgi-bin/affiliates/sale.cgi',51,'text');
INSERT INTO config VALUES ('affiliates_support','Enable affiliates support (Y/N)','N',50,'text');
INSERT INTO config VALUES ('min_gamount','Min. amount of Gift Certificate','5',61,'text');
INSERT INTO config VALUES ('max_gamount','Max. amount of Gift Certificate','1000',62,'text');
INSERT INTO config VALUES ('mail_notification_subj','Notification e-mail subject','Order #%ORDERID notification',81,'text');
INSERT INTO config VALUES ('mail_notification','Notification e-mail text','This is order #%ORDERID notification.\r\n%ORDERFEATURE\r\nCustomer information:\r\n---------------------\r\nUsername:     %UNAME\r\nFirst Name:   %FNAME\r\nLast Name:    %LNAME\r\nBilling address:\r\n  Street:     %B_ADDRESS\r\n  City:       %B_CITY\r\n  State:      %B_STATE\r\n  Country:    %B_COUNTRY\r\n  Zip Code:   %B_ZIPCODE\r\nShipping address:\r\n  Street:     %S_ADDRESS\r\n  City:       %S_CITY\r\n  State:      %S_STATE\r\n  Country:    %S_COUNTRY\r\n  Zip Code:   %S_ZIPCODE\r\nPhone:        %PHONE\r\nE-Mail:       %EMAIL\r\n\r\nProducts ordered:\r\nID    Amount Price    Product name\r\n------------------------------------------------------------------------------\r\n%PRODUCTS------------------------------------------------------------------------------\r\n%DISCOUNT%SHIPPING\r\n%DISC_COUPON%TOTAL\r\n\r\n--\r\nF-Cart Shopping System',82,'textarea');
INSERT INTO config VALUES ('items_toplist','Number of products listed in Top Sellers','15',9,'text');
INSERT INTO config VALUES ('mail_receipt_subj','Receipt e-mail subject','Order receipt',90,'text');
INSERT INTO config VALUES ('mail_receipt','Receipt e-mail text','Dear %FNAME!\r\n\r\nThank you for your order made with F-Cart Shopping System.\r\nPlease come back soon!\r\n%ORDERFEATURE\r\nProducts ordered:\r\nAmount Price    Product name\r\n------------------------------------------------------------------------------\r\n%PRODUCTS------------------------------------------------------------------------------\r\n%DISCOUNT%SHIPPING\r\n%DISC_COUPON%TOTAL\r\n\r\n--\r\nF-Cart Shopping System',92,'textarea');
INSERT INTO config VALUES ('mail_giftredeem_subj','Gift certificate redeem e-mail subject','Gift certificate redeemed',100,'text');
INSERT INTO config VALUES ('mail_giftredeem','Gift certificate redeem e-mail text','Dear %GIFTISSUER,\r\n\r\nThis is notification that your gift certificate was redeemed by %FNAME %LNAME.\r\nThe order is made to the following address:\r\n%S_ADDRESS, %S_CITY, %S_STATE, %S_COUNTRY, %S_ZIPCODE\r\n\r\nProducts ordered:\r\nAmount Price    Product name\r\n------------------------------------------------------------------------------\r\n%PRODUCTS------------------------------------------------------------------------------\r\n%TOTAL\r\n\r\n--\r\nF-Cart Shopping System',102,'textarea');
INSERT INTO config VALUES ('mail_notification_pl','Notification e-mail product line','%PRODUCTID %AMOUNT %PRICE %PRODUCTNAME',83,'text');
INSERT INTO config VALUES ('mail_receipt_pl','Receipt e-mail product line','%AMOUNT %PRICE %PRODUCTNAME',93,'text');
INSERT INTO config VALUES ('mail_giftredeem_pl','Gift certificate redeem e-mail product line','%AMOUNT %PRICE %PRODUCTNAME',103,'text');
INSERT INTO config VALUES ('mail_gift_subj','Gift certificate e-mail subject','Gift Certificate for you',110,'text');
INSERT INTO config VALUES ('mail_gift','Gift certificate e-mail text','Dear %RECIPIENT!\r\n\r\n%PURCHASER sent you a Gift Certificate for $%GIFTAMOUNT.\r\nMessage:\r\n%MESSAGE\r\n-------------------------\r\nGift Certificate ID: %GIFTCERT\r\nPlease use the Gift Certificate ID as username at www.fcart.com\r\n\r\n--\r\nF-Cart Shopping system',112,'textarea');
INSERT INTO config VALUES ('mysql_error_msg','MySQL server error message','MySQL server error. Please report to support@fcart.com',1000,'text');
INSERT INTO config VALUES ('shopcart_error_msg','Shopping cart failure error message','Shopping cart failure, please contact the developer at rrf@rrf.ru',1001,'text');
INSERT INTO config VALUES ('items_per_orders_page','Max. orders to display','100',8,'text');
INSERT INTO config VALUES ('mail_shipping_subj','Shipping information e-mail subject','Shipping information',120,'text');
INSERT INTO config VALUES ('mail_shipping','Shipping information e-mail text','Dear %FNAME!\r\n\r\nThis is confirmation that products you ordered have been shipped to you.\r\nShipping usually takes 1-2 days within US and 1-2 weeks abroads.\r\nYour FedEx tracking number is <number>.\r\nPlease save it for future.\r\n\r\nProducts ordered:\r\nAmount Price    Product name\r\n------------------------------------------------------------------------------\r\n%PRODUCTS------------------------------------------------------------------------------\r\n\r\n--\r\nF-Cart Shopping System',122,'textarea');
INSERT INTO config VALUES ('mail_shipping_pl','Shipping information e-mail product line','%AMOUNT %PRICE %PRODUCTNAME',123,'text');
INSERT INTO config VALUES ('mail_decline_subj','Decline notification e-mail subject','Order declined',130,'text');
INSERT INTO config VALUES ('mail_decline','Decline notification e-mail text','Dear %FNAME!\r\n\r\nWe are sorry to inform you that your order #%ORDERID was declined.\r\nCheck your information below:\r\n---------------------\r\nFirst Name:   %FNAME\r\nLast Name:    %LNAME\r\nBilling address:\r\n  Street:     %B_ADDRESS\r\n  City:       %B_CITY\r\n  State:      %B_STATE\r\n  Country:    %B_COUNTRY\r\n  Zip Code:   %B_ZIPCODE\r\nShipping address:\r\n  Street:     %S_ADDRESS\r\n  City:       %S_CITY\r\n  State:      %S_STATE\r\n  Country:    %S_COUNTRY\r\n  Zip Code:   %S_ZIPCODE\r\nPhone:        %PHONE\r\nE-Mail:       %EMAIL\r\n\r\nProducts ordered:\r\nAmount Price    Product name\r\n------------------------------------------------------------------------------\r\n%PRODUCTS------------------------------------------------------------------------------\r\n\r\nPlease report fraudulent orders to frauds@fcart.com\r\n\r\n--\r\nF-Cart Shopping System',132,'textarea');
INSERT INTO config VALUES ('mail_decline_pl','Decline notification e-mail product line','%AMOUNT %PRICE %PRODUCTNAME',133,'text');
INSERT INTO config VALUES ('minimal_dvd_points','Min. DVD points to buy for them','1000',32,'text');
INSERT INTO config VALUES ('cl_doc_bg','HTML body\'s background','#FFFFFF',200,'text');
INSERT INTO config VALUES ('cl_left_tab','Left tab\'s background','#DDDDFF',201,'text');
INSERT INTO config VALUES ('cl_tab_back','Deactive tab color','#BBBBCE',202,'text');
INSERT INTO config VALUES ('cl_tab_top','Active tab color','#EEEEFF',203,'text');
INSERT INTO config VALUES ('cl_win_cap1','Window/dialog title bar color','#0000A0',204,'text');
INSERT INTO config VALUES ('cl_win_cap2','Window/dialog title bar color','#000077',205,'text');
INSERT INTO config VALUES ('cl_win_title','Window/dialog title text color','#FFFFFF',206,'text');
INSERT INTO config VALUES ('cl_win_tab','Window/dialog canvas color','#D0CCFF',207,'text');
INSERT INTO config VALUES ('cl_win_border','Window border color','#000066',208,'text');
INSERT INTO config VALUES ('cl_css_link','CSS \'A: link\' color','#330066',210,'text');
INSERT INTO config VALUES ('cl_css_vizited','CSS \'A: vizited\' color','#330066',211,'text');
INSERT INTO config VALUES ('cl_css_hover','CSS \'A: hover\' color','#330066',212,'text');
INSERT INTO config VALUES ('cl_mod_bg','Product entry background','#FFFFFF',213,'text');
INSERT INTO config VALUES ('cl_mod_price','\'Price\' string color','#BB0000',214,'text');
INSERT INTO config VALUES ('cl_mod_border','Product entry border color','#000000',215,'text');
INSERT INTO config VALUES ('cl_mod_upsel1','Upselling table color 1','#AAAAAA',216,'text');
INSERT INTO config VALUES ('cl_mod_upsel2','Upselling table color 2','#DDDDFF',217,'text');
INSERT INTO config VALUES ('cl_sort_bg','Sort bar background','#AAAAAA',220,'text');
INSERT INTO config VALUES ('cl_sort_border','Sort bar border color','#000000',221,'text');
INSERT INTO config VALUES ('cl_sort_active','Sort bar active tab color','#D0CCFF',222,'text');
INSERT INTO config VALUES ('cl_sort_deactive','Sort bar deactive tab color','#FFFFFF',223,'text');
INSERT INTO config VALUES ('cl_sort_font','Sort bar text color','#000000',224,'text');
INSERT INTO config VALUES ('cl_order_border','Borders\' color at order page','#000000',226,'text');
INSERT INTO config VALUES ('cl_order_bg','Order page background','#FFFFFF',225,'text');
INSERT INTO config VALUES ('cl_order_total','Total value color at order page','#EE2222',227,'text');
INSERT INTO config VALUES ('cl_order_red','Hightlighted text at order page','#FF0000',228,'text');
INSERT INTO config VALUES ('cl_cat_active','Active category color','#F0FFFF',230,'text');
INSERT INTO config VALUES ('cl_nav_deactive','Deactive tab at navigation bar','#AAAAAA',231,'text');
INSERT INTO config VALUES ('cl_discount','Discount text color','#0000AA',232,'text');
INSERT INTO config VALUES ('cl_header','Header text color','#000099',233,'text');
INSERT INTO config VALUES ('cl_bot_1','Footer color 1','#BBBBFF',234,'text');
INSERT INTO config VALUES ('cl_bot_2','Footer color 1','#000099',254,'text');
INSERT INTO config VALUES ('font_css','CSS font','font-family: arial,helvetica,sans-serif;font-size: 12px',255,'text');
INSERT INTO config VALUES ('mail_disc_coupon_subj','Discount coupon e-mail subject','Get a %DISCOUNT discount at www.fcart.com!',140,'text');
INSERT INTO config VALUES ('mail_disc_coupon','Discount coupon e-mail text','Hello!\r\n\r\nYou are a V.I.P. customer at www.fcart.com\r\nWe give you a %DISCOUNT discount for any %PURCHASES at our site.\r\nYou discount coupon ID is %COUPON\r\nEnter it on the checkout page and get your discount!\r\nHurry up! This proposal exists no longer than %EXPDATE.\r\n\r\n--\r\nF-Cart Shopping System',142,'textarea');
INSERT INTO config VALUES ('default_image','Default product image','default.gif',11,'text');
INSERT INTO config VALUES ('cat_pulldown','Pull-down category list style (Y/N)','N',300,'text');
INSERT INTO config VALUES ('mail_pwd_subj','Password retrieve e-mail subject','Your password at www.fcart.com',150,'text');
INSERT INTO config VALUES ('mail_pwd','Password retrieve e-mail template','Hello %FNAME %LNAME!\r\n\r\nYou have requested your password on www.fcart.com\r\nHere is your log-in information:\r\nUsername: %USERNAME\r\nPassword: %PASSWORD\r\n\r\n--\r\nF-Cart Shopping System',152,'textarea');
INSERT INTO config VALUES ('support_email','Support e-mail address','F-Cart Shopping System <zorg@sav.bpcnet>',71,'text');
INSERT INTO config VALUES ('https_enabled','Enable secure HTTP (HTTPS) where necessary','N',350,'text');
INSERT INTO config VALUES ('shop_logo','Shop Logo (HTML code)','<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\">\r\n<tr>\r\n<td align=\"center\"><img src=\"images/selleasy.gif\" width=\"97\" height=\"16\"></td>\r\n</tr>\r\n<tr>\r\n<td align=\"center\"><a href=\"http://www.fcart.com\"><img src=\"images/fcart.gif\" width=\"100\" height=\"66\" border=\"0\"></a></td>\r\n</tr>\r\n</table>',20,'textarea');
INSERT INTO config VALUES ('shop_welcome','Shop Welcome image/text (HTML code)','<font size=\"3\">Welcome to on-line store demo based on F-Cart.</font>\r\n<p>',22,'textarea');
INSERT INTO config VALUES ('shop_header','Shop Header (HTML code)','',21,'textarea');
INSERT INTO config VALUES ('https_adm_enabled','Enable HTTPS in admin mode','N',351,'text');

#
# Table structure for table 'countries'
#
CREATE TABLE countries (
  country varchar(50),
  code char(2)
);

#
# Dumping data for table 'countries'
#

INSERT INTO countries VALUES ('Afghanistan','AF');
INSERT INTO countries VALUES ('Albania','AL');
INSERT INTO countries VALUES ('Algeria','DZ');
INSERT INTO countries VALUES ('American Samoa','AS');
INSERT INTO countries VALUES ('Andorra','AD');
INSERT INTO countries VALUES ('Angola','AO');
INSERT INTO countries VALUES ('Anguilla','AI');
INSERT INTO countries VALUES ('Antarctica','AQ');
INSERT INTO countries VALUES ('Antigua and Barbuda','AG');
INSERT INTO countries VALUES ('Argentina','AR');
INSERT INTO countries VALUES ('Armenia','AM');
INSERT INTO countries VALUES ('Aruba','AW');
INSERT INTO countries VALUES ('Australia','AU');
INSERT INTO countries VALUES ('Austria','AT');
INSERT INTO countries VALUES ('Azerbaijan','AZ');
INSERT INTO countries VALUES ('Bahamas','BS');
INSERT INTO countries VALUES ('Bahrain','BH');
INSERT INTO countries VALUES ('Bangladesh','BD');
INSERT INTO countries VALUES ('Barbados','BB');
INSERT INTO countries VALUES ('Belarus','BY');
INSERT INTO countries VALUES ('Belgium','BE');
INSERT INTO countries VALUES ('Belize','BZ');
INSERT INTO countries VALUES ('Benin','BJ');
INSERT INTO countries VALUES ('Bermuda','BM');
INSERT INTO countries VALUES ('Bhutan','BT');
INSERT INTO countries VALUES ('Bolivia','BO');
INSERT INTO countries VALUES ('Bosnia and Herzegowina','BA');
INSERT INTO countries VALUES ('Botswana','BW');
INSERT INTO countries VALUES ('Bouvet Island','BV');
INSERT INTO countries VALUES ('Brazil','BR');
INSERT INTO countries VALUES ('British Indian Ocean Territory','IO');
INSERT INTO countries VALUES ('British Virgin Islands','VG');
INSERT INTO countries VALUES ('Brunei Darussalam','BN');
INSERT INTO countries VALUES ('Bulgaria','BG');
INSERT INTO countries VALUES ('Burkina Faso','BF');
INSERT INTO countries VALUES ('Burundi','BI');
INSERT INTO countries VALUES ('Cambodia','KH');
INSERT INTO countries VALUES ('Cameroon','CM');
INSERT INTO countries VALUES ('Canada','CA');
INSERT INTO countries VALUES ('Cape Verde','CV');
INSERT INTO countries VALUES ('Cayman Islands','KY');
INSERT INTO countries VALUES ('Central African Republic','CF');
INSERT INTO countries VALUES ('Chad','TD');
INSERT INTO countries VALUES ('Chile','CL');
INSERT INTO countries VALUES ('China','CN');
INSERT INTO countries VALUES ('Christmas Island','CX');
INSERT INTO countries VALUES ('Cocos (Keeling) Islands','CC');
INSERT INTO countries VALUES ('Colombia','CO');
INSERT INTO countries VALUES ('Comoros','KM');
INSERT INTO countries VALUES ('Congo','CG');
INSERT INTO countries VALUES ('Cook Islands','CK');
INSERT INTO countries VALUES ('Costa Rica','CR');
INSERT INTO countries VALUES ('Cote D\'ivoire','CI');
INSERT INTO countries VALUES ('Croatia','HR');
INSERT INTO countries VALUES ('Cuba','CU');
INSERT INTO countries VALUES ('Cyprus','CY');
INSERT INTO countries VALUES ('Czech Republic','CZ');
INSERT INTO countries VALUES ('Czechoslovakia','CS');
INSERT INTO countries VALUES ('Denmark','DK');
INSERT INTO countries VALUES ('Djibouti','DJ');
INSERT INTO countries VALUES ('Dominica','DM');
INSERT INTO countries VALUES ('Dominican Republic','DO');
INSERT INTO countries VALUES ('East Timor','TP');
INSERT INTO countries VALUES ('Ecuador','EC');
INSERT INTO countries VALUES ('Egypt','EG');
INSERT INTO countries VALUES ('El Salvador','SV');
INSERT INTO countries VALUES ('Equatorial Guinea','GQ');
INSERT INTO countries VALUES ('Eritrea','ER');
INSERT INTO countries VALUES ('Estonia','EE');
INSERT INTO countries VALUES ('Ethiopia','ET');
INSERT INTO countries VALUES ('Falkland Islands (Malvinas)','FK');
INSERT INTO countries VALUES ('Faroe Islands','FO');
INSERT INTO countries VALUES ('Fiji','FJ');
INSERT INTO countries VALUES ('Finland','FI');
INSERT INTO countries VALUES ('France','FR');
INSERT INTO countries VALUES ('France, Metropolitan','FX');
INSERT INTO countries VALUES ('French Guiana','GF');
INSERT INTO countries VALUES ('French Polynesia','PF');
INSERT INTO countries VALUES ('French Southern Territories','TF');
INSERT INTO countries VALUES ('Gabon','GA');
INSERT INTO countries VALUES ('Gambia','GM');
INSERT INTO countries VALUES ('Georgia','GE');
INSERT INTO countries VALUES ('Germany','DE');
INSERT INTO countries VALUES ('Ghana','GH');
INSERT INTO countries VALUES ('Gibraltar','GI');
INSERT INTO countries VALUES ('Greece','GR');
INSERT INTO countries VALUES ('Greenland','GL');
INSERT INTO countries VALUES ('Grenada','GD');
INSERT INTO countries VALUES ('Guadeloupe','GP');
INSERT INTO countries VALUES ('Guam','GU');
INSERT INTO countries VALUES ('Guatemala','GT');
INSERT INTO countries VALUES ('Guinea','GN');
INSERT INTO countries VALUES ('Guinea-Bissau','GW');
INSERT INTO countries VALUES ('Guyana','GY');
INSERT INTO countries VALUES ('Haiti','HT');
INSERT INTO countries VALUES ('Heard and McDonald Islands','HM');
INSERT INTO countries VALUES ('Honduras','HN');
INSERT INTO countries VALUES ('Hong Kong','HK');
INSERT INTO countries VALUES ('Hungary','HU');
INSERT INTO countries VALUES ('Iceland','IS');
INSERT INTO countries VALUES ('India','IN');
INSERT INTO countries VALUES ('Indonesia','ID');
INSERT INTO countries VALUES ('Iraq','IQ');
INSERT INTO countries VALUES ('Ireland','IE');
INSERT INTO countries VALUES ('Islamic Republic of Iran','IR');
INSERT INTO countries VALUES ('Israel','IL');
INSERT INTO countries VALUES ('Italy','IT');
INSERT INTO countries VALUES ('Jamaica','JM');
INSERT INTO countries VALUES ('Japan','JP');
INSERT INTO countries VALUES ('Jordan','JO');
INSERT INTO countries VALUES ('Kazakhstan','KZ');
INSERT INTO countries VALUES ('Kenya','KE');
INSERT INTO countries VALUES ('Kiribati','KI');
INSERT INTO countries VALUES ('Korea, Democratic People\'s Republic of','KP');
INSERT INTO countries VALUES ('Korea, Republic of','KR');
INSERT INTO countries VALUES ('Kuwait','KW');
INSERT INTO countries VALUES ('Kyrgyzstan','KG');
INSERT INTO countries VALUES ('Laos','LA');
INSERT INTO countries VALUES ('Latvia','LV');
INSERT INTO countries VALUES ('Lebanon','LB');
INSERT INTO countries VALUES ('Lesotho','LS');
INSERT INTO countries VALUES ('Liberia','LR');
INSERT INTO countries VALUES ('Libyan Arab Jamahiriya','LY');
INSERT INTO countries VALUES ('Liechtenstein','LI');
INSERT INTO countries VALUES ('Lithuania','LT');
INSERT INTO countries VALUES ('Luxembourg','LU');
INSERT INTO countries VALUES ('Macau','MO');
INSERT INTO countries VALUES ('Macedonia','MK');
INSERT INTO countries VALUES ('Madagascar','MG');
INSERT INTO countries VALUES ('Malawi','MW');
INSERT INTO countries VALUES ('Malaysia','MY');
INSERT INTO countries VALUES ('Maldives','MV');
INSERT INTO countries VALUES ('Mali','ML');
INSERT INTO countries VALUES ('Malta','MT');
INSERT INTO countries VALUES ('Marshall Islands','MH');
INSERT INTO countries VALUES ('Martinique','MQ');
INSERT INTO countries VALUES ('Mauritania','MR');
INSERT INTO countries VALUES ('Mauritius','MU');
INSERT INTO countries VALUES ('Mayotte','YT');
INSERT INTO countries VALUES ('Mexico','MX');
INSERT INTO countries VALUES ('Micronesia','FM');
INSERT INTO countries VALUES ('Moldova, Republic of','MD');
INSERT INTO countries VALUES ('Monaco','MC');
INSERT INTO countries VALUES ('Mongolia','MN');
INSERT INTO countries VALUES ('Montserrat','MS');
INSERT INTO countries VALUES ('Morocco','MA');
INSERT INTO countries VALUES ('Mozambique','MZ');
INSERT INTO countries VALUES ('Myanmar','MM');
INSERT INTO countries VALUES ('Namibia','NA');
INSERT INTO countries VALUES ('Nauru','NR');
INSERT INTO countries VALUES ('Nepal','NP');
INSERT INTO countries VALUES ('Netherlands','NL');
INSERT INTO countries VALUES ('Netherlands Antilles','AN');
INSERT INTO countries VALUES ('New Caledonia','NC');
INSERT INTO countries VALUES ('New Zealand','NZ');
INSERT INTO countries VALUES ('Nicaragua','NI');
INSERT INTO countries VALUES ('Niger','NE');
INSERT INTO countries VALUES ('Nigeria','NG');
INSERT INTO countries VALUES ('Niue','NU');
INSERT INTO countries VALUES ('Norfolk Island','NF');
INSERT INTO countries VALUES ('Northern Mariana Islands','MP');
INSERT INTO countries VALUES ('Norway','NO');
INSERT INTO countries VALUES ('Oman','OM');
INSERT INTO countries VALUES ('Pakistan','PK');
INSERT INTO countries VALUES ('Palau','PW');
INSERT INTO countries VALUES ('Panama','PA');
INSERT INTO countries VALUES ('Papua New Guinea','PG');
INSERT INTO countries VALUES ('Paraguay','PY');
INSERT INTO countries VALUES ('Peru','PE');
INSERT INTO countries VALUES ('Philippines','PH');
INSERT INTO countries VALUES ('Pitcairn','PN');
INSERT INTO countries VALUES ('Poland','PL');
INSERT INTO countries VALUES ('Portugal','PT');
INSERT INTO countries VALUES ('Puerto Rico','PR');
INSERT INTO countries VALUES ('Qatar','QA');
INSERT INTO countries VALUES ('Reunion','RE');
INSERT INTO countries VALUES ('Romania','RO');
INSERT INTO countries VALUES ('Russian Federation','RU');
INSERT INTO countries VALUES ('Rwanda','RW');
INSERT INTO countries VALUES ('Saint Lucia','LC');
INSERT INTO countries VALUES ('Samoa','WS');
INSERT INTO countries VALUES ('San Marino','SM');
INSERT INTO countries VALUES ('Sao Tome and Principe','ST');
INSERT INTO countries VALUES ('Saudi Arabia','SA');
INSERT INTO countries VALUES ('Senegal','SN');
INSERT INTO countries VALUES ('Seychelles','SC');
INSERT INTO countries VALUES ('Sierra Leone','SL');
INSERT INTO countries VALUES ('Singapore','SG');
INSERT INTO countries VALUES ('Slovakia','SK');
INSERT INTO countries VALUES ('Slovenia','SI');
INSERT INTO countries VALUES ('Solomon Islands','SB');
INSERT INTO countries VALUES ('Somalia','SO');
INSERT INTO countries VALUES ('South Africa','ZA');
INSERT INTO countries VALUES ('South Georgia and The South Sandwich Islands','GS');
INSERT INTO countries VALUES ('Spain','ES');
INSERT INTO countries VALUES ('Sri Lanka','LK');
INSERT INTO countries VALUES ('St. Helena','SH');
INSERT INTO countries VALUES ('St. Kitts And Nevis','KN');
INSERT INTO countries VALUES ('St. Pierre and Miquelon','PM');
INSERT INTO countries VALUES ('St. Vincent And The Greadines','VC');
INSERT INTO countries VALUES ('Sudan','SD');
INSERT INTO countries VALUES ('Suriname','SR');
INSERT INTO countries VALUES ('Svalbard and Jan Mayen Islands','SJ');
INSERT INTO countries VALUES ('Swaziland','SZ');
INSERT INTO countries VALUES ('Sweden','SE');
INSERT INTO countries VALUES ('Switzerland','CH');
INSERT INTO countries VALUES ('Syrian Arab Republic','SY');
INSERT INTO countries VALUES ('Taiwan','TW');
INSERT INTO countries VALUES ('Tajikistan','TJ');
INSERT INTO countries VALUES ('Tanzania, United Republic of','TZ');
INSERT INTO countries VALUES ('Thailand','TH');
INSERT INTO countries VALUES ('Togo','TG');
INSERT INTO countries VALUES ('Tokelau','TK');
INSERT INTO countries VALUES ('Tonga','TO');
INSERT INTO countries VALUES ('Trinidad and Tobago','TT');
INSERT INTO countries VALUES ('Tunisia','TN');
INSERT INTO countries VALUES ('Turkey','TR');
INSERT INTO countries VALUES ('Turkmenistan','TM');
INSERT INTO countries VALUES ('Turks and Caicos Islands','TC');
INSERT INTO countries VALUES ('Tuvalu','TV');
INSERT INTO countries VALUES ('Uganda','UG');
INSERT INTO countries VALUES ('Ukraine','UA');
INSERT INTO countries VALUES ('United Arab Emirates','AE');
INSERT INTO countries VALUES ('United Kingdom (Great Britain)','GB');
INSERT INTO countries VALUES ('United States','US');
INSERT INTO countries VALUES ('United States Minor Outlying Islands','UM');
INSERT INTO countries VALUES ('United States Virgin Islands','VI');
INSERT INTO countries VALUES ('Uruguay','UY');
INSERT INTO countries VALUES ('Uzbekistan','UZ');
INSERT INTO countries VALUES ('Vanuatu','VU');
INSERT INTO countries VALUES ('Vatican City State','VA');
INSERT INTO countries VALUES ('Venezuela','VE');
INSERT INTO countries VALUES ('Viet Nam','VN');
INSERT INTO countries VALUES ('Wallis And Futuna Islands','WF');
INSERT INTO countries VALUES ('Western Sahara','EH');
INSERT INTO countries VALUES ('Yemen','YE');
INSERT INTO countries VALUES ('Yugoslavia','YU');
INSERT INTO countries VALUES ('Zaire','ZR');
INSERT INTO countries VALUES ('Zambia','ZM');
INSERT INTO countries VALUES ('Zimbabwe','ZW');

#
# Table structure for table 'customers'
#
CREATE TABLE customers (
  login varchar(32) DEFAULT '' NOT NULL,
  password varchar(32) DEFAULT '' NOT NULL,
  userid varchar(32) DEFAULT '' NOT NULL,
  points int(11) DEFAULT '0' NOT NULL,
  firstname varchar(32) DEFAULT '' NOT NULL,
  lastname varchar(32) DEFAULT '' NOT NULL,
  b_address varchar(64) DEFAULT '' NOT NULL,
  b_city varchar(64) DEFAULT '' NOT NULL,
  b_state char(2) DEFAULT '' NOT NULL,
  b_country char(2) DEFAULT '' NOT NULL,
  b_zipcode varchar(32) DEFAULT '' NOT NULL,
  s_address varchar(64) DEFAULT '' NOT NULL,
  s_city varchar(64) DEFAULT '' NOT NULL,
  s_state char(2) DEFAULT '' NOT NULL,
  s_country char(2) DEFAULT '' NOT NULL,
  s_zipcode varchar(32) DEFAULT '' NOT NULL,
  phone varchar(32) DEFAULT '' NOT NULL,
  email varchar(128) DEFAULT '' NOT NULL,
  card_type int(11) DEFAULT '0' NOT NULL,
  card_name varchar(64) DEFAULT '' NOT NULL,
  card_number varchar(20) DEFAULT '' NOT NULL,
  card_expire varchar(4) DEFAULT '' NOT NULL,
  UNIQUE userid (userid),
  UNIQUE email (email),
  PRIMARY KEY (login)
);

#
# Dumping data for table 'customers'
#


#
# Table structure for table 'discount_coupons'
#
CREATE TABLE discount_coupons (
  coupon varchar(32) DEFAULT '' NOT NULL,
  discount decimal(12,2) DEFAULT '0.00' NOT NULL,
  type enum('Fixed','Percent') DEFAULT 'Fixed' NOT NULL,
  count int(11) DEFAULT '1' NOT NULL,
  expire date DEFAULT '0000-00-00' NOT NULL,
  PRIMARY KEY (coupon)
);

#
# Dumping data for table 'discount_coupons'
#


#
# Table structure for table 'discounts'
#
CREATE TABLE discounts (
  price decimal(12,2) DEFAULT '0.00' NOT NULL,
  discount decimal(12,2) DEFAULT '0.00' NOT NULL,
  PRIMARY KEY (price)
);

#
# Dumping data for table 'discounts'
#

INSERT INTO discounts VALUES (250.00,2.00);
INSERT INTO discounts VALUES (500.00,3.00);
INSERT INTO discounts VALUES (1000.00,5.00);
INSERT INTO discounts VALUES (100.00,1.00);
INSERT INTO discounts VALUES (550.00,2.00);

#
# Table structure for table 'featured_products'
#
CREATE TABLE featured_products (
  productid int(11) DEFAULT '0' NOT NULL,
  product_order int(11) DEFAULT '0' NOT NULL,
  avail enum('Y','N') DEFAULT 'Y' NOT NULL,
  KEY productid_index (productid)
);

#
# Dumping data for table 'featured_products'
#

INSERT INTO featured_products VALUES (23,2,'Y');
INSERT INTO featured_products VALUES (19,1,'Y');

#
# Table structure for table 'giftcerts'
#
CREATE TABLE giftcerts (
  cert varchar(32) DEFAULT '' NOT NULL,
  userid varchar(32) DEFAULT '' NOT NULL,
  cart varchar(32) DEFAULT '' NOT NULL,
  purchaser varchar(64) DEFAULT '' NOT NULL,
  recipient varchar(64) DEFAULT '' NOT NULL,
  remail varchar(64) DEFAULT '' NOT NULL,
  message text,
  amount decimal(12,2) DEFAULT '0.00' NOT NULL,
  status enum('R','S','U','F') DEFAULT 'F' NOT NULL,
  a_date date DEFAULT '0000-00-00' NOT NULL,
  PRIMARY KEY (cert)
);

#
# Dumping data for table 'giftcerts'
#


#
# Table structure for table 'order_details'
#
CREATE TABLE order_details (
  orderid int(11) DEFAULT '0' NOT NULL,
  product int(11) DEFAULT '0' NOT NULL,
  price decimal(12,2) DEFAULT '0.00' NOT NULL,
  amount int(11) DEFAULT '0' NOT NULL,
  KEY orderid_index (orderid)
);

#
# Dumping data for table 'order_details'
#


#
# Table structure for table 'orders'
#
CREATE TABLE orders (
  orderid int(11) DEFAULT '0' NOT NULL auto_increment,
  login varchar(32) DEFAULT '' NOT NULL,
  order_total float(12,2) DEFAULT '0.00' NOT NULL,
  order_discount float(12,2) DEFAULT '0.00' NOT NULL,
  order_disc_coupon varchar(32) DEFAULT '' NOT NULL,
  order_shipping float(12,2) DEFAULT '0.00' NOT NULL,
  order_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  order_state enum('Queued','Processed','Shipped','Cancelled') DEFAULT 'Queued' NOT NULL,
  order_flag enum('','Gift','Reward') DEFAULT '' NOT NULL,
  firstname varchar(32) DEFAULT '' NOT NULL,
  lastname varchar(32) DEFAULT '' NOT NULL,
  b_address varchar(64) DEFAULT '' NOT NULL,
  b_city varchar(64) DEFAULT '' NOT NULL,
  b_state char(2) DEFAULT '' NOT NULL,
  b_country char(2) DEFAULT '' NOT NULL,
  b_zipcode varchar(32) DEFAULT '' NOT NULL,
  s_address varchar(64) DEFAULT '' NOT NULL,
  s_city varchar(64) DEFAULT '' NOT NULL,
  s_state char(2) DEFAULT '' NOT NULL,
  s_country char(2) DEFAULT '' NOT NULL,
  s_zipcode varchar(32) DEFAULT '' NOT NULL,
  phone varchar(32) DEFAULT '' NOT NULL,
  email varchar(128) DEFAULT '' NOT NULL,
  card_type int(11) DEFAULT '0' NOT NULL,
  card_name varchar(64) DEFAULT '' NOT NULL,
  card_number varchar(20) DEFAULT '' NOT NULL,
  card_expire varchar(4) DEFAULT '' NOT NULL,
  PRIMARY KEY (orderid),
  KEY order_date_index (order_date),
  KEY s_state_index (s_state),
  KEY b_state_index (b_state),
  KEY s_country_index (s_country),
  KEY b_country_index (b_country)
);

#
# Dumping data for table 'orders'
#


#
# Table structure for table 'product_links'
#
CREATE TABLE product_links (
  productid int(11) DEFAULT '0' NOT NULL,
  link int(11) DEFAULT '0' NOT NULL,
  KEY productid_index (productid)
);

#
# Dumping data for table 'product_links'
#

INSERT INTO product_links VALUES (1,32);
INSERT INTO product_links VALUES (32,1);
INSERT INTO product_links VALUES (26,27);
INSERT INTO product_links VALUES (29,28);
INSERT INTO product_links VALUES (28,29);
INSERT INTO product_links VALUES (15,17);
INSERT INTO product_links VALUES (16,18);
INSERT INTO product_links VALUES (14,19);
INSERT INTO product_links VALUES (17,18);
INSERT INTO product_links VALUES (18,13);
INSERT INTO product_links VALUES (18,14);
INSERT INTO product_links VALUES (14,13);
INSERT INTO product_links VALUES (20,21);
INSERT INTO product_links VALUES (20,22);
INSERT INTO product_links VALUES (20,23);
INSERT INTO product_links VALUES (20,24);
INSERT INTO product_links VALUES (21,22);
INSERT INTO product_links VALUES (23,20);
INSERT INTO product_links VALUES (24,21);
INSERT INTO product_links VALUES (5,6);
INSERT INTO product_links VALUES (5,8);
INSERT INTO product_links VALUES (6,5);
INSERT INTO product_links VALUES (6,10);
INSERT INTO product_links VALUES (3,11);
INSERT INTO product_links VALUES (3,12);
INSERT INTO product_links VALUES (3,33);
INSERT INTO product_links VALUES (25,11);
INSERT INTO product_links VALUES (33,9);
INSERT INTO product_links VALUES (9,11);
INSERT INTO product_links VALUES (24,26);

#
# Table structure for table 'products'
#
CREATE TABLE products (
  product varchar(255) DEFAULT '' NOT NULL,
  category varchar(255) DEFAULT '' NOT NULL,
  price decimal(12,2) DEFAULT '0.00' NOT NULL,
  image varchar(255),
  descr text,
  avail enum('N','Y') DEFAULT 'Y' NOT NULL,
  rating int(11) DEFAULT '0' NOT NULL,
  a_date date DEFAULT '0000-00-00' NOT NULL,
  productid int(11) DEFAULT '0' NOT NULL auto_increment,
  PRIMARY KEY (productid),
  KEY product (product),
  KEY category_index (category),
  KEY price_index (price),
  KEY rating_index (rating),
  KEY a_date_index (a_date)
);

#
# Dumping data for table 'products'
#

INSERT INTO products VALUES ('RAD Robot (27 MHz)','Toys',79.99,'toy1.jpg','The ultimate, high performance Remote Control Robot! Tough treads allow RAD to conquer rough terrain indoors or outdoors. With dual-speed, RAD goes forward, backward, left and right, and does a 360 degree spin.','Y',8,'2000-12-29',1);
INSERT INTO products VALUES ('The Sims','Games',37.99,'games1.jpg','Maxis\' The Sims is about creating, managing, and controlling the lives of tiny computerized people who dwell in miniature homes. The game\'s excellent music and sound effects, detailed scenery, cleverly animated characters, and equally clever writing go a long way toward fulfilling this intriguing premise.','Y',11,'2000-12-27',2);
INSERT INTO products VALUES ('HTML 4 for the World Wide Web Visual Quickstart Guide','Books/Erotic',19.99,'book1.jpg','Whether you use a high-end authoring application like Dreamweaver,or in the most economical fashion you write your own code out in a textfile, knowing your way around HTML comes in handy. <i>HTML 4 for theWorld Wide Web: Visual QuickStart Guide</i> will teach you what youneed to know quickly.','Y',3,'2001-01-23',3);
INSERT INTO products VALUES ('Dreamweaver 3 Bible : Gold Edition','Books/Web',59.99,'book11.jpg','If your paperback copy of Dreamweaver 3 Bible is dog-eared or you\'redelving into the more advanced realms of Web site production, thishardcover Gold version might be just the thing for you. It contains allof the material that the \"non-Gold\" edition has, and it sets a goodfoundation for beginners.','Y',2,'2000-12-29',5);
INSERT INTO products VALUES ('Designing Web Usability : The Practice of Simplicity','Books/Misc',45.00,'book2.jpg','Creating Web sites is easy. Creating sites that truly meet the needs andexpectations of a wide range of online users is quite another story.In <i>Designing Web Usability: The Practice of Simplicity</i>, renownedWeb usability guru Jakob Nielsen shares his insightful thoughts on thesubject.','Y',3,'2001-01-23',6);
INSERT INTO products VALUES ('Adobe Photoshop 5.5 Classroom in a Book','Books',45.00,'book3.jpg','<i>Adobe Photoshop 5.5 Classroom in a Book, Special Web Edition</i>, like othersin the fine Classroom in a Book series, is somewhere between a manualand a tutorial; the lessons can be read straight through or referencedon a need-to-know basis.','Y',24,'2001-01-07',7);
INSERT INTO products VALUES ('Javascript : The Definitive Guide','Books/Web',39.95,'book4.jpg','Provides a rapid and thorough exposition of the JavaScript programminglanguage, as well as an in-depth reference section covering each JavaScriptfunction, object, method, and even handler. Experienced programmers willquickly find the information they need to start writing JavaScript programs.','Y',0,'2000-12-29',8);
INSERT INTO products VALUES ('New Masters of Flash (WITH CD-ROM)','Books',59.99,'book6.jpg','Learning by doing is always good, especially if you have good role modelsto follow. As part of the publisher\'s \"Showing It\" series, this bookbrings together 19 of the most innovative designers who are working inmotion graphics, and gives them lots of page room to talk about theirwork in general.','Y',0,'2000-12-24',9);
INSERT INTO products VALUES ('Flash 5 for Windows and Macintosh: Visual QuickStart Guide','Books/Web',21.99,'book7.jpg','I am fonder of Macromedia Flash than just about any tool, and I feel muchthe same way about Peachpit\'s Visual QuickStart Guides; so, the ideaof a Visual QuickStart Guide about Flash 5 makes me weak in the knees.','Y',2,'2000-12-29',10);
INSERT INTO products VALUES ('Core Servlets and JavaServer Pages (JSP)','Books',42.99,'book8.jpg','Aimed at those with some previous Java experience, Core Servlets andJavaServer Pages covers all you need to know to create effective Webapplications using server-side Java.','Y',5,'2000-12-24',11);
INSERT INTO products VALUES ('Photoshop 6 Down and Dirty Tricks','Books',39.95,'book9.jpg','This is one of the very best books I\'ve seen to date on Photoshop Tricks.Do yourself a favor and add this powerhouse to your Photoshop arsenal.','Y',2,'2000-12-24',12);
INSERT INTO products VALUES ('Motorola Talkabout T8097 Phone (AT&T)','Cellular Phones',150.00,'cell1.jpg','<b>Features:</b><ul type=\"disc\"><li>Compact, light, sleek design<li>Solid construction, intuitive keypad and controls<li>Vibrating call alert<li>Supports SMS messaging<li>Rated up to 170 hours standby, 130 minutes talk time with standard battery</ul>','Y',0,'2000-12-24',13);
INSERT INTO products VALUES ('Motorola StarTAC ST7867W Phone (Sprint PCS)','Cellular Phones',199.00,'cell2.jpg','<b>Features:</b><ul type=\"disc\"><li>Compact and lightweight<li>Web enabled<li>Dual-mode digital and analog for widest coverage<li>Better-than-rated talk time<li>An update of a successful classic</ul>','Y',2,'2000-12-24',14);
INSERT INTO products VALUES ('Motorola M3682 Phone (VoiceStream Wireless)','Cellular Phones',150.00,'cell3.jpg','<b>Features:</b><ul type=\"disc\"><li>Flip cover protects keypad<li>Excellent menu buttons and navigation aids<li>Two-way SMS text messaging<li>Up to 180 minutes talk time, 180 hours standby time, with standard battery<li>Includes Jabra EarSet</ul>','Y',20,'2000-12-30',15);
INSERT INTO products VALUES ('Motorola Timeport P8167 Phone (Sprint PCS)','Cellular Phones',99.99,'cell4.jpg','<b>Features:</b><ul type=\"disc\"><li>Dual-mode analog and digital for widest coverage<li>Great battery life for talk and standby time<li>Packed with messaging features<li>Bundled with data cable and software to use with PCs and PDAs<li>Compatible with many StarTAC accessories</ul>','Y',3,'2000-12-24',16);
INSERT INTO products VALUES ('Motorola Timeport L7089 Phone (VoiceStream Wireless)','Cellular Phones',125.00,'cell5.jpg','<b>Features:</b><ul type=\"disc\"><li>Extremely lightweight and slim<li>Triband worldwide GSM capability<li>Voice memo recording<li>Up to 150 hours standby, 210 minutes talk time, with standard battery<li>Includes Jabra EarSet</ul>','Y',0,'2000-12-24',17);
INSERT INTO products VALUES ('Motorola StarTAC ST7797 Phone (AT&T)','Cellular Phones',170.00,'cell6.jpg','<b>Features:</b><ul type=\"disc\"><li>Lightweight, efficient design<li>Solid construction, intuitive keypad and controls<li>Vibrating call alert<li>Dual NAM capability<li>Up to 170 hours\' standby, 130 minutes\' talk time with standard battery</ul>','Y',3,'2000-12-24',18);
INSERT INTO products VALUES ('Motorola V2397 Phone (AT&T)','Cellular Phones',130.00,'cell7.jpg','<b>Features:</b><ul type=\"disc\"><li>Hot new design<li>2-way SMS support<li>Changeable faceplate<li>Up to 100 minutes\' talk time and 110 hours\' standby time with standard battery<li>Weighs 5.4 ounces with battery</ul>','Y',1,'2000-12-24',19);
INSERT INTO products VALUES ('Canon PowerShot S100 Digital ELPH Camera Kit','Cameras',499.99,'camera1.jpg','<b>Features:</b><ul type=\"disc\"><li>Ultracompact 2.1 megapixel digital camera<li>Rugged stainless steel body<li>4x digital zoom, 2x optical zoom brings you close to the action<li>Uses lithium-ion battery for longer battery life<li>Uses removable CompactFlash memory card, 8 MB card included</ul>','Y',31,'2000-12-29',20);
INSERT INTO products VALUES ('Kodak DC3200 Digital Camera','Cameras',188.94,'camera2.jpg','<b>Features:</b><ul type=\"disc\"><li>1-megapixel resolution<li>1.6-inch color LCD screen<li>2 MB internal memory; CompactFlash slot for additional memory<li>2x digital zoom<li>Uses 4 AA batteries (alkalines included)</ul>','Y',0,'2000-12-24',21);
INSERT INTO products VALUES ('Nikon Coolpix 800 Digital Camera','Cameras',473.94,'camera3.gif','<b>Features:</b><ul type=\"disc\"><li>1,600 x 1,200 top resolution<li>2x optical Zoom-Nikkor lens<li>2.11-million-pixel, 0.5-inch CCD<li>1.8-inch, 112,000-dot, TFT LCD monitor<li>Removable CompactFlash memory, 8 MB card included</ul>','Y',2,'2000-12-24',22);
INSERT INTO products VALUES ('Polaroid i-zone Digital and Instant Combo Camera','Cameras',89.99,'camera4.jpg','<b>Features:</b><ul type=\"disc\"><li>Takes i-zone pictures and digital images<li>Captures images at a resolution of 640 x 480<li>Holds up to 18 digital pictures<li>Focus-free with built-in flash<li>Kit includes film, serial cable, batteries, and software</ul>','Y',3,'2000-12-24',23);
INSERT INTO products VALUES ('KB Gear JamCam 3.0 Digital Camera (Silver)','Cameras',89.99,'camera5.jpg','<b>Features:</b><ul type=\"disc\"><li>Captures images at a resolution of 640 x 480 pixels (interpolated to 800 x 600)<li>Focus-free lens for simple operation<li>Built-in memory stores up to 8 images; MMC card slot for additional capacity<li>Connects with Macs and PCs using USB port<li>Uses a 9-volt battery for power</ul>','Y',2,'2000-12-24',24);
INSERT INTO products VALUES ('Microsoft FrontPage 2000','Books',115.99,'software1.gif','<b>Features:</b><ul type=\"disc\"><li>NOTE: In-Box Upgrade Rebate valid only for previous licensed users of Microsoft Windows 95 or later (see product description for complete list)<li>Allows users to easily create Web sites exactly the way they want<li>Makes site management easyMakes site management easy<li>Updates sites quickly<li>Works together with Microsoft Office</ul>','Y',1,'2000-12-30',25);
INSERT INTO products VALUES ('Adobe Acrobat 4.0','Software',199.99,'software2.jpg','<b>Features:</b><ul type=\"disc\"><li>Convert any document to Portable Document Format (PDF)<li>Mark up and annotate PDF documents<li>Create PDF Web forms<li>Integrate PDF files with Web servers and e-mail<li>Retain and print sophisticated PostScript 3 graphics</ul>','Y',10,'2000-12-24',26);
INSERT INTO products VALUES ('ViaVoice Pro 7.0 Millennium Edition','Software',33.99,'software3.jpg','<b>Features:</b><ul type=\"disc\"><li>Use voice commands to control and navigate your desktop, applications, and the Internet<li>Input, edit, and format text by voice using Microsoft Word 97 and 2000; Microsoft Excel 97 and 2000; and Microsoft Outlook 97, 98, and 2000<li>Explore specialized topics such as Cuisine, Chatters Jargon, Business & Finance, and Computer<li>Proofread your documents with the ViaVoice Outloud text-to-speech feature<li>Includes optional legal and medical dictionaries</ul>','Y',2,'2000-12-24',27);
INSERT INTO products VALUES ('Bach\'s Goldberg Variations','Music',13.99,'music1.jpg','As the legend goes, <i>Bach\'s Goldberg Variations</i>were written for an insomniac diplomat patron who desired music to help cheer him on lonely, sleepless nights. But the real legend is how Bach was able to produce, for an apparently throwaway occasion, one of the crowning masterpieces not only of the Baroque, but of all Western music. Here\'s a gathering of widely varied interpretations the music has inspired, from keyboard masters to arrangements for string orchestra, jazz trio, and even brass band.','Y',3,'2000-12-27',28);
INSERT INTO products VALUES ('Bach: Cello-Suiten / Mstislav Rostropovich','Music',31.32,'music2.gif','<b>Composer:</b> Johann Sebastian Bach<b><br>Performer:</b>Mstislav RostropovichEmd/Emi Classics - #55363 / June 13, 1995Audio CD / DDD / Number of Discs: 2','Y',0,'2001-01-07',29);
INSERT INTO products VALUES ('UAZ 3160 Automobile','Cars',12500.00,'uaz1_phpVAQ455.jpg','Ulianovsk Avtozavod 3160','Y',21,'2000-12-30',35);
INSERT INTO products VALUES ('Rock \'N Roll Ernie','Toys',10.00,'toy2.gif','Here\'s a side of Sesame Street\'s Ernie that you\'ve probably never seen before.','Y',9,'2001-01-05',32);
INSERT INTO products VALUES ('Professional Java Server Programming: with Servlets, JavaServer Pages (JSP), XML, Enterprise JavaBeans (EJB), JNDI, CORBA, Jini and Javaspaces','Books',47.99,'book5.jpg','Wrox specializes in books written by programmers, for programmers. <i>Professional Java Server Programming</i>, a volume on developing Java-based Web applications, is no different. All the 12 authors are developers and consultants -- including some who\'ve been part of Sun\'s own Java team.','Y',0,'2000-12-24',33);

#
# Table structure for table 'shipping'
#
CREATE TABLE shipping (
  price decimal(12,2) DEFAULT '0.00' NOT NULL,
  shipping decimal(12,2) DEFAULT '0.00' NOT NULL,
  PRIMARY KEY (price)
);

#
# Dumping data for table 'shipping'
#

INSERT INTO shipping VALUES (50.00,8.00);
INSERT INTO shipping VALUES (500.00,20.00);
INSERT INTO shipping VALUES (0.00,5.00);
INSERT INTO shipping VALUES (2000.00,100.00);

#
# Table structure for table 'states'
#
CREATE TABLE states (
  state varchar(20),
  code char(2)
);

#
# Dumping data for table 'states'
#

INSERT INTO states VALUES ('Alabama','AL');
INSERT INTO states VALUES ('Alaska','AK');
INSERT INTO states VALUES ('Arizona','AZ');
INSERT INTO states VALUES ('Arkansas','AR');
INSERT INTO states VALUES ('California','CA');
INSERT INTO states VALUES ('Colorado','CO');
INSERT INTO states VALUES ('Connecticut','CT');
INSERT INTO states VALUES ('Delaware','DE');
INSERT INTO states VALUES ('District of Columbia','DC');
INSERT INTO states VALUES ('Florida','FL');
INSERT INTO states VALUES ('Georgia','GA');
INSERT INTO states VALUES ('Guam','GU');
INSERT INTO states VALUES ('Hawaii','HI');
INSERT INTO states VALUES ('Idaho','ID');
INSERT INTO states VALUES ('Illinois','IL');
INSERT INTO states VALUES ('Indiana','IN');
INSERT INTO states VALUES ('Iowa','IA');
INSERT INTO states VALUES ('Kansas','KS');
INSERT INTO states VALUES ('Kentucky','KY');
INSERT INTO states VALUES ('Louisiana','LA');
INSERT INTO states VALUES ('Maine','ME');
INSERT INTO states VALUES ('Maryland','MD');
INSERT INTO states VALUES ('Massachusetts','MA');
INSERT INTO states VALUES ('Michigan','MI');
INSERT INTO states VALUES ('Minnesota','MN');
INSERT INTO states VALUES ('Mississippi','MS');
INSERT INTO states VALUES ('Missouri','MO');
INSERT INTO states VALUES ('Montana','MT');
INSERT INTO states VALUES ('Nebraska','NE');
INSERT INTO states VALUES ('Nevada','NV');
INSERT INTO states VALUES ('New Hampshire','NH');
INSERT INTO states VALUES ('New Jersey','NJ');
INSERT INTO states VALUES ('New Mexico','NM');
INSERT INTO states VALUES ('New York','NY');
INSERT INTO states VALUES ('North Carolina','NC');
INSERT INTO states VALUES ('North Dakota','ND');
INSERT INTO states VALUES ('Ohio','OH');
INSERT INTO states VALUES ('Oklahoma','OK');
INSERT INTO states VALUES ('Oregon','OR');
INSERT INTO states VALUES ('Pennsylvania','PA');
INSERT INTO states VALUES ('Puerto Rico','PR');
INSERT INTO states VALUES ('Rhode Island','RI');
INSERT INTO states VALUES ('South Carolina','SC');
INSERT INTO states VALUES ('South Dakota','SD');
INSERT INTO states VALUES ('Tennessee','TN');
INSERT INTO states VALUES ('Texas','TX');
INSERT INTO states VALUES ('Utah','UT');
INSERT INTO states VALUES ('Vermont','VT');
INSERT INTO states VALUES ('Virgin Islands','VI');
INSERT INTO states VALUES ('Virginia','VA');
INSERT INTO states VALUES ('Washington','WA');
INSERT INTO states VALUES ('West Virginia','WV');
INSERT INTO states VALUES ('Wisconsin','WI');
INSERT INTO states VALUES ('Wyoming','WY');

