#
# Table structure for table `foodcomp`
#

CREATE TABLE `foodcomp` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `food_name` varchar(35) NOT NULL default '',
  `food_category` varchar(35) NOT NULL default '',
  `serving_size` varchar(35) NOT NULL default '',
  `gmwt_desc1` varchar(35) NOT NULL default '',
  `gmwt1` varchar(35) NOT NULL default '',
  `gmwt_desc2` varchar(35) NOT NULL default '',
  `gmwt2` varchar(35) NOT NULL default '',
  `calories` varchar(35) NOT NULL default '',
  `total_fat` varchar(35) NOT NULL default '',
  `saturated_fat` varchar(35) NOT NULL default '',
  `trans_fat` varchar(35) NOT NULL default '',
  `carbohydrates` varchar(35) NOT NULL default '',
  `dietary_fiber` varchar(35) NOT NULL default '',
  `sugar` varchar(35) NOT NULL default '',
  `protein` varchar(35) NOT NULL default '',
  `cholesterol` varchar(35) NOT NULL default '',
  `sodium` varchar(35) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;


#

INSERT INTO `foodcomp` VALUES (1, 'rice cakes, brown rice, plain', 'Snacks', '100 grams', '1 cake', '9', '2 cakes', '18', '387', '2.8', '0.570', '0', '81.50', '4.2', '0.88', '8.20', '0', '326');
