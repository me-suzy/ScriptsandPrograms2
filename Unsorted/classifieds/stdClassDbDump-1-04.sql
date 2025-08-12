# phpMyAdmin MySQL-Dump
# version 2.2.5
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
# --------------------------------------------------------

#
# Table structure for table `std_blob_images`
#

CREATE TABLE std_blob_images (
  blob_id bigint(20) NOT NULL auto_increment,
  item_id bigint(20) NOT NULL default '0',
  bin_data longblob NOT NULL,
  filename varchar(255) NOT NULL default '',
  filesize varchar(255) NOT NULL default '',
  filetype varchar(255) NOT NULL default '',
  timestamp int(11) NOT NULL default '0',
  PRIMARY KEY (blob_id),
  KEY item_id(item_id)
) TYPE=MyISAM;
# --------------------------------------------------------



#
# Table structure for table `std_categories`
#

CREATE TABLE std_categories (
  cat_id bigint(20) NOT NULL auto_increment,
  parent_id bigint(20) NOT NULL default '0',
  top_parent_id bigint(20) NOT NULL default '0',
  cat_name varchar(50) NOT NULL default '',
  PRIMARY KEY (cat_id),
  UNIQUE KEY cat_id(cat_id),
  KEY parent_id(parent_id)
) TYPE=MyISAM;

#
# Dumping data for table `std_categories`
#

INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (1, 0, 0, 'Antiques');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (3, 0, 0, 'Comics, Cards, & Sci-fi');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (5, 0, 0, 'Computers & Software');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (6, 0, 0, 'Professional Services');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (7, 0, 0, 'Books');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (8, 0, 0, 'Clothing & Accessories');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (9, 0, 0, 'Collectibles');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (10, 0, 0, 'Electronics');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (11, 1, 1, 'Books & Manuscripts');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (12, 1, 1, 'Cameras');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (13, 1, 1, 'Ceramics & Glass');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (14, 1, 1, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (15, 1, 1, 'Musical Instruments');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (16, 1, 1, 'Painting');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (17, 1, 1, 'Scientific Instruments');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (18, 1, 1, 'Silver');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (19, 1, 1, 'Textiles & Linens');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (20, 1, 1, 'Art');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (21, 7, 7, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (22, 7, 7, 'Fiction');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (23, 7, 7, 'Non-Fiction');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (24, 8, 8, 'Clothing');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (25, 8, 8, 'Accessories');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (28, 9, 9, 'Advertising');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (29, 9, 9, 'Animals');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (30, 9, 9, 'Animation');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (31, 9, 9, 'Antique Reproductions');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (32, 9, 9, 'Autographs');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (33, 9, 9, 'Barber Shop');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (34, 9, 9, 'Bears');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (35, 9, 9, 'Bells');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (36, 9, 9, 'Bottles & Cans');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (37, 9, 9, 'Cars & Motorcycles');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (38, 9, 9, 'Cereal Boxes & Premiums');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (39, 9, 9, 'Character');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (40, 9, 9, 'Circus & Carnival');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (41, 9, 9, 'Collector Plates');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (42, 9, 9, 'Dolls');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (43, 9, 9, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (44, 9, 9, 'Historical & Cultural');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (45, 9, 9, 'Holiday & Seasonal');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (46, 9, 9, 'Household Items');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (47, 9, 9, 'Knives & Swords');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (48, 9, 9, 'Lunchboxes');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (49, 9, 9, 'Magic & Novelty Items');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (50, 9, 9, 'Memorabilia');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (51, 9, 9, 'Militaria');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (52, 9, 9, 'Music Boxes');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (53, 9, 9, 'Oddities');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (54, 9, 9, 'Paper');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (55, 9, 9, 'Pinbacks');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (56, 9, 9, 'Porcelain Figurines');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (57, 9, 9, 'Railroadiana');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (58, 9, 9, 'Religious');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (59, 9, 9, 'Rocks');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (60, 9, 9, 'Minerals & Fossils');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (61, 9, 9, 'Scientific Instruments');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (62, 9, 9, 'Textiles');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (65, 3, 3, 'Anime & Manga');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (66, 3, 3, 'Comic Books');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (67, 3, 3, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (68, 3, 3, 'Godzilla');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (69, 3, 3, 'Star Trek');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (70, 3, 3, 'The X-Files');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (71, 3, 3, 'Toys');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (72, 3, 3, 'Trading Cards');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (73, 5, 5, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (74, 5, 5, 'Hardware');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (75, 5, 5, 'Internet Services');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (76, 5, 5, 'Software');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (77, 10, 10, 'Consumer Electronics');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (78, 10, 10, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (79, 10, 10, 'Recording Equipment');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (80, 10, 10, 'Video Equipment');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (83, 0, 0, 'Jewelry');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (84, 83, 83, 'Ancient');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (85, 83, 83, 'Beaded Jewelry');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (86, 83, 83, 'Beads');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (87, 83, 83, 'Carved & Cameo');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (88, 83, 83, 'Contemporary');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (89, 83, 83, 'Costume');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (90, 83, 83, 'Fine Gemstones');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (91, 83, 83, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (92, 83, 83, 'Gold');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (93, 83, 83, 'Necklaces');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (94, 83, 83, 'Silver');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (95, 83, 83, 'Victorian');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (96, 83, 83, 'Vintage');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (97, 0, 0, 'Home & Garden');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (98, 97, 97, 'Baby Items');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (99, 97, 97, 'Crafts');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (100, 97, 97, 'Furniture');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (101, 97, 97, 'Garden');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (102, 97, 97, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (103, 97, 97, 'Household Items');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (104, 97, 97, 'Pet Supplies');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (105, 97, 97, 'Tools & Hardware');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (106, 97, 97, 'Weddings');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (107, 0, 0, 'Movies & Video');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (108, 107, 107, 'DVD');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (109, 107, 107, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (110, 107, 107, 'Laser Discs');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (111, 107, 107, 'VHS');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (112, 0, 0, 'Music');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (113, 112, 112, 'CDs');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (114, 112, 112, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (115, 112, 112, 'Instruments');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (116, 112, 112, 'Memorabilia');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (117, 112, 112, 'CDs');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (118, 112, 112, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (119, 112, 112, 'Instruments');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (120, 112, 112, 'Memorabilia');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (121, 112, 112, 'Records');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (122, 112, 112, 'Tapes');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (123, 0, 0, 'Office & Business');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (124, 123, 123, 'Briefcases');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (125, 123, 123, 'Fax Machines');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (126, 123, 123, 'General Equipment');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (127, 123, 123, 'Pagers');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (128, 0, 0, 'Sports & Recreation');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (129, 128, 128, 'Apparel & Equipment');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (130, 128, 128, 'Exercise Equipment');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (131, 128, 128, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (132, 0, 0, 'Toys & Games');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (133, 132, 132, 'Action Figures');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (134, 132, 132, 'Beanie Babies & Beanbag Toys');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (135, 132, 132, 'Diecast');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (136, 132, 132, 'Fast Food');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (137, 132, 132, 'Fisher-Price');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (138, 132, 132, 'Furby');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (139, 132, 132, 'Games');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (140, 132, 132, 'General');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (141, 132, 132, 'Giga Pet & Tamagotchi');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (142, 132, 132, 'Hobbies');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (143, 132, 132, 'Marbles');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (144, 132, 132, 'My Little Pony');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (145, 132, 132, 'Peanuts Gang');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (146, 132, 132, 'Pez');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (147, 132, 132, 'Plastic Models');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (148, 132, 132, 'Plush Toys');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (149, 132, 132, 'Puzzles');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (150, 132, 132, 'Slot Cars');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (151, 132, 132, 'Toy Soldiers');
INSERT INTO std_categories (cat_id, parent_id, top_parent_id, cat_name) VALUES (152, 132, 132, 'Vintage');

   

#
# Table structure for table `std_items`
#

CREATE TABLE std_items (
  item_id bigint(20) NOT NULL auto_increment,
  user_id bigint(20) NOT NULL default '0',
  cat_id bigint(20) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  the_desc text NOT NULL,
  email varchar(100) NOT NULL default '',
  email_visible varchar(4) NOT NULL default '',
  image_exists varchar(5) default 'false',
  date_time int(11) NOT NULL default '0',
  PRIMARY KEY (item_id),
  UNIQUE KEY item_id(item_id),
  KEY cat_id(cat_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `std_temp_users`
#

CREATE TABLE std_temp_users (
  user_name varchar(50) NOT NULL default '0',
  password varchar(10) NOT NULL default '',
  email varchar(30) NOT NULL default '',
  sign_up_date int(11) NOT NULL default '0',
  prelim_rand varchar(20) NOT NULL default '',
  PRIMARY KEY (user_name)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `std_users`
#

CREATE TABLE std_users (
  user_id bigint(20) NOT NULL auto_increment,
  user_name varchar(20) NOT NULL default '',
  password varchar(20) NOT NULL default '',
  email varchar(75) NOT NULL default '',
  sign_up_date int(11) NOT NULL default '0',
  cookie_id varchar(15) default NULL,
  PRIMARY KEY (user_id),
  UNIQUE KEY user_id(user_id),
  UNIQUE KEY user_name(user_name)
) TYPE=MyISAM;

    
