# --------------------------------------------------------

#
# Table structure for table `cartcategories`
#

CREATE TABLE `cartcategories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(35) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `cartcategories`
#


# --------------------------------------------------------

#
# Table structure for table `cartcategories_to_sites`
#

CREATE TABLE `cartcategories_to_sites` (
  `id` int(11) NOT NULL auto_increment,
  `sitesID` int(11) NOT NULL default '0',
  `cartCategoriesID` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `cartcategories_to_sites`
#


# --------------------------------------------------------

#
# Table structure for table `cartitems`
#

CREATE TABLE `cartitems` (
  `id` int(11) NOT NULL auto_increment,
  `price` decimal(10,2) NOT NULL default '0.00',
  `status` int(11) NOT NULL default '1',
  `name` varchar(200) NOT NULL default '',
  `description` mediumtext NOT NULL,
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `cartitems`
#


# --------------------------------------------------------

#
# Table structure for table `cartitems_to_cartcategories`
#

CREATE TABLE `cartitems_to_cartcategories` (
  `id` int(11) NOT NULL auto_increment,
  `cartItemsID` int(11) NOT NULL default '0',
  `cartCategoriesID` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `cartitems_to_cartcategories`
#


# --------------------------------------------------------

#
# Table structure for table `cartitems_to_sites`
#

CREATE TABLE `cartitems_to_sites` (
  `id` int(11) NOT NULL auto_increment,
  `cartItemsID` int(11) NOT NULL default '0',
  `SitesID` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `cartitems_to_sites`
#


# --------------------------------------------------------

#
# Table structure for table `orders`
#

CREATE TABLE `orders` (
  `id` int(11) NOT NULL auto_increment,
  `firstName` varchar(35) NOT NULL default '',
  `lastName` varchar(35) NOT NULL default '',
  `phone` varchar(35) NOT NULL default '',
  `email` varchar(75) NOT NULL default '',
  `shippingAddress` varchar(75) NOT NULL default '',
  `shippingCity` varchar(35) NOT NULL default '',
  `shippingState` varchar(35) NOT NULL default '',
  `shippingZip` int(11) NOT NULL default '0',
  `billingFirstName` varchar(25) NOT NULL default '',
  `billingLastName` varchar(25) NOT NULL default '',
  `creditCardNumber` varchar(23) NOT NULL default '',
  `expDate` varchar(10) NOT NULL default '',
  `billingAddress` varchar(75) NOT NULL default '',
  `billingCity` varchar(35) NOT NULL default '',
  `billingState` varchar(35) NOT NULL default '',
  `billingZip` int(11) NOT NULL default '0',
  `totalPrice` decimal(10,2) NOT NULL default '0.00',
  `taxPrice` decimal(10,2) NOT NULL default '0.00',
  `finalPrice` decimal(10,2) NOT NULL default '0.00',
  `totalCartItems` int(11) NOT NULL default '0',
  `checkoutProcessType` varchar(20) NOT NULL default '',
  `cartContents` mediumtext NOT NULL,
  `orderStatus` varchar(15) NOT NULL default 'new',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `orders`
#


# --------------------------------------------------------

#
# Table structure for table `sites`
#

CREATE TABLE `sites` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `url` varchar(200) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

#
# Dumping data for table `sites`
#

INSERT INTO `sites` VALUES (1, 'Shopping Cart', 'http://');
    