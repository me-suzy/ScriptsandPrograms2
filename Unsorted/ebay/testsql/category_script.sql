/*	$Id: category_script.sql,v 1.2 1999/02/21 02:55:38 josh Exp $	*/
/* script to generate ebay categories */

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 1, 'Collectible', 'Collectible',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	102, 160,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 99,	'Miscellaneous', 'Miscellaneous',
'0','0', '0', 0, 0, 0, 0, 
'', '', '', '', 281, 0,
9.95, sysdate, '',	sysdate	);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 102,'Antiques', 'Antiques',
'0','0', '0', 0, 0, 0, 0, 
'', '', '', '', 0, 1,
9.95, sysdate, '',	sysdate	);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 12, 'General', 'Antique General',
'0', '1', '0', 102, 0, 0, 0, 
'Antiques', '',	'', '',	0, 13,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 13, 'Glass', 'Antique Glass',
'0', '0', '0', 102, 0, 0, 0, 
'Antiques', '',	'', '',	12, 24,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 14, 'General', 'Antiques Glass General',
'0', '1', '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	0, 15,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 15, 'Art Glass', 'Antiques Glass Art Glass',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	14, 16,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 16, 'Carnival', 'Antiques Glass Carnival',
'0', '1', '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	15, 17,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 17, 'Depression', 'Antiques Glass Depression',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	16, 18,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 18, 'Milk', 'Antiques Glass Milk',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	17, 19,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 19, 'Vaseline', 'Antiques Glass Vaseline',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	18, 20,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 20, 'Paperweights', 'Antiques Glass Paperweights',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	19, 21,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 21, 'Fenton', 'Antiques Glass Fenton',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	20, 22,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 22, 'EAPG', 'Antiques Glass EAPG',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	21, 23,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 23, 'Elegant', 'Antiques Glass Elegant',
'0', '1',  '0', 13, 102, 0, 0, 
'Glass', 'Antiques',	'', '',	22, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 24, 'Porcelain, China', 'Antiques, Porcelain, China',
'0', '0', '0', 102, 0, 0, 0, 
'Antiques', '',	'', '',	13, 28,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 25, 'General', 'Antiques, Porcelain, China, General',
'0', '1', '0', 24, 102, 0, 0, 
'Porcelain, China', 'Antiques',	'', '',	0, 26,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 26, 'Figurines', 'Antiques, Porcelain, China, Figurines',
'0', '1', '0', 24, 102, 0, 0, 
'Porcelain, China', 'Antiques',	'', '',	25, 27,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 27, 'Pottery', 'Antiques, Porcelain, China, Pottery',
'0', '1', '0', 24, 102, 0, 0, 
'Porcelain, China', 'Antiques',	'', '',	26, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 28, 'Metalware', 'Antiques, Metalware',
'0', '1', '0', 102, 0, 0, 0, 
'Antiques', '',	'', '',	24, 29,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 29, 'Toys', 'Antiques, Toys',
'0', '0', '0', 102, 0, 0, 0, 
'Antiques', '',	'', '',	28, 33,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 30, 'General', 'Antiques, Toys, General',
'0', '1', '0', 29, 102, 0, 0, 
'Toys', 'Antiques', '', '', 0, 31,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 31, 'Bears', 'Antiques, Toys, Bears',
'0', '1', '0', 29, 102, 0, 0, 
'Toys', 'Antiques', '', '', 30, 32,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 32, 'Dolls', 'Antiques, Toys, Dolls',
'0', '1', '0', 29, 102, 0, 0, 
'Toys', 'Antiques', '', '', 31, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 33, 'Textiles', 'Antiques, Textiles',
'0', '1', '0', 102, 0, 0, 0, 
'Antiques', '',	'', '',	29, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 34, 'Advertising', 'Collectible Advertising',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	0, 46,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 35, 'General', 'Collectibles Advertising General',
'0', '1', '0', 34, 1, 0, 0, 
'Advertising', 'Collectible',	'', '',	0, 36,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 36, 'Soda', 'Collectibles Advertising Soda',
'0', '0', '0', 34, 1, 0, 0, 
'Advertising', 'Collectible',	'', '',	35, 39,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 37, 'General', 'Collectibles Advertising Soda General',
'0', '1', '0', 36, 34, 1, 0,
'Soda', 'Advertising', 'Collectible', '', 0, 38,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 38, 'Coca-Cola', 'Collectibles Advertising Soda Coca-Cola',
'0', '1', '0', 36, 34, 1, 0,
'Soda', 'Advertising', 'Collectible', '', 37, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 39, 'Gasoline', 'Collectibles Advertising Gasoline',
'0', '0', '0', 34, 1, 0, 0, 
'Advertising', 'Collectible',	'', '',	36, 43,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 40, 'General', 'Collectibles Advertising Gasoline General',
'0', '1', '0', 39, 34, 1, 0,
'Gasoline', 'Advertising', 'Collectible', '',	0, 41,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 41, 'Esso', 'Collectibles Advertising Gasoline Esso',
'0', '1', '0', 39, 34, 1, 0,
'Gasoline', 'Advertising', 'Collectible', '',	40, 42,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 42, 'Texaco', 'Collectibles Advertising Gasoline Texaco',
'0', '1', '0', 39, 34, 1, 0,
'Gasoline', 'Advertising', 'Collectible', '',	41, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 43, 'Cigarette', 'Collectibles Advertising Cigarette',
'0', '0', '0', 34, 1, 0, 0, 
'Advertising', 'Collectible',	'', '',	39, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 44, 'General', 'Collectibles Advertising Cigarette General',
'0', '1', '0', 43, 34, 1, 0,
'Cigarette', 'Advertising', 'Collectible', '',	0, 45,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 45, 'Joe Camel', 'Collectibles Advertising Cigarette Joe Camel',
'0', '1', '0', 43, 34, 1, 0,
'Cigarette', 'Advertising', 'Collectible', '',	45, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 46, 'Art', 'Collectible Art',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	34, 49,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 47, 'General', 'Collectible Art General',
'0', '1', '0', 46, 1, 0, 0,
'Art', 'Collectible', '', '',	0, 48,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 48, 'Photographic Images', 'Collectible Art Photographic Images',
'0', '1', '0', 46, 1, 0, 0,
'Art', 'Collectible', '', '',	47, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 49, 'Autographs', 'Collectible Autographs',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	46, 63,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 50, 'General', 'Collectible Autographs General',
'0', '1', '0', 49, 1, 0, 0,
'Autographs', 'Collectible', '', '',	0, 51,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 51, 'Sports', 'Collectible Autographs Sports',
'0', '0', '0', 49, 1, 0, 0,
'Autographs', 'Collectible', '', '',	50, 57,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 52, 'General', 'Collectible Autographs Sports General',
'0', '1', '0', 51, 49, 1, 0,
'Sports', 'Autographs', 'Collectible', '', 0, 53,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 53, 'Baseball', 'Collectible Autographs Sports Baseball',
'0', '1', '0', 51, 49, 1, 0,
'Sports', 'Autographs', 'Collectible', '', 52, 54,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 54, 'Basketball', 'Collectible Autographs Sports Basketball',
'0', '1', '0', 51, 49, 1, 0,
'Sports', 'Autographs', 'Collectible', '', 53, 55,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 55, 'Football', 'Collectible Autographs Sports Football',
'0', '1', '0', 51, 49, 1, 0,
'Sports', 'Autographs', 'Collectible', '', 54, 56,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 56, 'Hockey', 'Collectible Autographs Sports Hockey',
'0', '1', '0', 51, 49, 1, 0,
'Sports', 'Autographs', 'Collectible', '', 56, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 57, 'Entertainment', 'Collectible Autographs Entertainment',
'0', '0', '0', 49, 1, 0, 0,
'Autographs', 'Collectible', '', '',	51, 62,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 58, 'General', 'Collectible Autographs Entertainment General',
'0', '1', '0', 57, 49, 1, 0,
'Entertainment', 'Autographs', 'Collectible', '',	0, 59,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 59, 'Movies', 'Collectible Autographs Entertainment Movies',
'0', '1', '0', 57, 49, 1, 0,
'Entertainment', 'Autographs', 'Collectible', '', 58, 60,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 60, 'Television', 'Collectible Autographs Entertainment Television',
'0', '1', '0', 57, 49, 1, 0,
'Entertainment', 'Autographs', 'Collectible', '', 59, 61,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 61, 'Recording Artists', 'Collectible Autographs Entertainment Recording Artists',
'0', '1', '0', 57, 49, 1, 0,
'Entertainment', 'Autographs', 'Collectible', '', 60, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 62, 'Sci-Fi', 'Collectible Autographs Sci-Fi',
'0', '1', '0', 49, 1, 0, 0,
'Autographs', 'Collectible', '', '',	57, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 63, 'Comic Books', 'Collectible Comic Books',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	49, 81,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 64, 'General', 'Collectible Comic Books General',
'0', '1', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	0, 65,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 65, 'Pre-Golden Age', 'Collectible Comic Books Pre-Golden Age (pre-1938)',
'0', '1', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	64, 66,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 66, 'Golden Age', 'Collectible Comic Books Golden Age (pre-1956)',
'0', '0', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	65, 72,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 67, 'General', 'Collectible Comic Books Golden Age (pre-1956) General',
'0', '1', '0', 66, 63, 1, 0,
'Golden Age', 'Comic Books', 'Collectible', '', 0, 68,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 68, 'Superhero', 'Collectible Comic Books Golden Age (pre-1956) Superhero',
'0', '1', '0', 66, 63, 1, 0,
'Golden Age', 'Comic Books', 'Collectible', '', 67, 69,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 69, 'Crime', 'Collectible Comic Books Golden Age (pre-1956) Crime',
'0', '1', '0', 66, 63, 1, 0,
'Golden Age', 'Comic Books', 'Collectible', '', 68, 70,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 70, 'Horror/Sci-Fi', 'Collectible Comic Books Golden Age (pre-1956) Horror/Sci-Fi',
'0', '1', '0', 66, 63, 1, 0,
'Golden Age', 'Comic Books', 'Collectible', '', 69, 71,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 71, 'Funny Animal', 'Collectible Comic Books Golden Age (pre-1956) Funny Animal',
'0', '1', '0', 66, 63, 1, 0,
'Golden Age', 'Comic Books', 'Collectible', '', 70, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 72, 'Classic', 'Collectible Comic Books Classic',
'0', '1', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	66, 73,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 73, 'Silver Age', 'Collectible Comic Books Silver Age',
'0', '0', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	72, 77,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 74, 'General', 'Collectible Comic Books Silver Age General',
'0', '1', '0', 73, 63, 1, 0,
'Silver Age', 'Comic Books', 'Collectible', '',	0, 75,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 75, 'Superhero', 'Collectible Comic Books Silver Age Superhero',
'0', '1', '0', 73, 63, 1, 0,
'Silver Age', 'Comic Books', 'Collectible', '',	74, 76,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 76, 'Horror/Sci-Fi', 'Collectible Comic Books Silver Age Horror/Sci-Fi',
'0', '1', '0', 73, 63, 1, 0,
'Silver Age', 'Comic Books', 'Collectible', '',	75, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 77, 'Modern (1970-now)', 'Collectible Comic Books Modern',
'0', '1', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	73, 78,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 78, 'Comic Magazines', 'Collectible Comic Books Comic Magazines',
'0', '1', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	77, 79,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 79, 'Newspaper Comics', 'Collectible Comic Books Newspaper Comics',
'0', '1', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	78, 80,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 80, 'Comic Figurines', 'Collectible Comic Books Comic Figurines',
'0', '1', '0', 63, 1, 0, 0,
'Comic Books', 'Collectible', '', '',	79, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 81, 'Kitchenware', 'Collectible Kitchenware',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	63, 85,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 82, 'General', 'Collectible Kitchenware General',
'0', '1', '0', 81, 1, 0, 0,
'Kitchenware', 'Collectible','', '', 0, 83,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 83, 'Cookie Jars', 'Collectible Kitchenware Cookie Jars',
'0', '1', '0', 81, 1, 0, 0,
'Kitchenware', 'Collectible','', '', 82, 84,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 84, 'Salt, Pepper Shakers', 'Collectible Kitchenware Salt, Pepper Shakers',
'0', '1', '0', 81, 1, 0, 0,
'Kitchenware', 'Collectible','', '', 83, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 85, 'Pottery, Porcelain', 'Collectible Pottery, Porcelain',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	81, 98,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 86, 'General', 'Collectible Pottery, Porcelain General',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	0, 87,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 87, 'Fiesta', 'Collectible Pottery, Porcelain Fiesta',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	86, 88,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 88, 'Goebel', 'Collectible Pottery, Porcelain Goebel',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	87, 89,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 89, 'McCoy', 'Collectible Pottery, Porcelain McCoy',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	88, 90,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 90, 'Rookwood', 'Collectible Pottery, Porcelain Rookwood',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	89, 91,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 91, 'Shawnee', 'Collectible Pottery, Porcelain Shawnee',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	90, 92,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 92, 'Lenox', 'Collectible Pottery, Porcelain Lenox',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	91, 93,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 93, 'Nippon', 'Collectible Pottery, Porcelain Nippon',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	92, 94,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 94, 'Noritake', 'Collectible Pottery, Porcelain Noritake',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	93, 95,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 95, 'Royal Doulton', 'Collectible Pottery, Porcelain Royal Doulton',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	94, 96,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 96, 'Roseville', 'Collectible Pottery, Porcelain Roseville',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	95, 97,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 97, 'Avon Works', 'Collectible Pottery, Porcelain Avon Works',
'0', '1', '0', 85, 1, 0, 0,
'Pottery, Porcelain', 'Collectible', '', '',	96, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 98, 'Decorative', 'Collectible Decorative',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	85, 109,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 100, 'General', 'Collectible Decorative General',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 0, 101,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 101, 'Longaberger', 'Collectible Decorative Longaberger',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 100, 103,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 103, 'Hummel', 'Collectible Decorative Hummel',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 101, 104,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 104, 'Enesco', 'Collectible Decorative Enesco',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 103, 105,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 105, 'Josef', 'Collectible Decorative Josef',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 104, 106,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 106, 'Wade', 'Collectible Decorative Wade',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 105, 107,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 107, 'Precious Moments', 'Collectible Decorative Precious Moments',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 106, 108,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 108, 'Norman Rockwell', 'Collectible Decorative Norman Rockwell',
'0', '1', '0', 98, 1, 0, 0,
'Decorative', 'Collectible', '', '', 107, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 109, 'Contemporary', 'Collectible Contemporary',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	98, 110,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 110, 'Vintage', 'Collectible Vintage',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	109, 117,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 111, 'General', 'Collectible Vintage General',
'0', '1', '0', 110, 1, 0, 0,
'Vintage', 'Collectible', '', '', 0, 112,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 112, 'Clothing', 'Collectible Vintage Clothing',
'0', '1', '0', 110, 1, 0, 0,
'Vintage', 'Collectible', '', '', 111, 113,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 113, 'Sewing Items', 'Collectible Vintage Sewing Items',
'0', '0', '0', 110, 1, 0, 0,
'Vintage', 'Collectible', '', '', 112, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 114, 'General', 'Collectible Vintage Sewing Items General',
'0', '1', '0', 113, 110, 1, 0,
'Sewing Items', 'Vintage', 'Collectible', '', 0, 115,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 115, 'Buttons', 'Collectible Vintage Sewing Items Buttons',
'0', '1', '0', 113, 110, 1, 0,
'Sewing Items', 'Vintage', 'Collectible', '', 114, 116,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 116, 'Thimbles', 'Collectible Vintage Sewing Items Thimbles',
'0', '1', '0', 113, 110, 1, 0,
'Sewing Items', 'Vintage', 'Collectible', '', 115, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 117, 'Bears', 'Collectible Bears',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	110, 118,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 118, 'Breweriana', 'Collectible Breweriana',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	117, 119,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 119, 'Clocks, Timepieces', 'Collectible Clocks, Timepieces',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	118, 120,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 120, 'Crafts', 'Collectible Crafts',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	119, 124,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 121, 'General', 'Collectible Crafts General',
'0', '1', '0', 120, 1, 0, 0,
'Crafts', 'Collectible', '', '', 0, 122,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 122, 'Handcrafted Arts', 'Collectible Crafts Handcrafted Arts',
'0', '1', '0', 120, 1, 0, 0,
'Crafts', 'Collectible', '', '', 121, 123,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 123, 'Supplies', 'Collectible Crafts Supplies',
'0', '1', '0', 120, 1, 0, 0,
'Crafts', 'Collectible', '', '', 122, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 124, 'Paper', 'Collectible Paper',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	120, 128,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 125, 'General', 'Collectible Paper General',
'0', '1', '0', 124, 1, 0, 0,
'Paper', 'Collectible', '', '',	0, 126,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 126, 'Ephemera', 'Collectible Paper Ephemera',
'0', '1', '0', 124, 1, 0, 0,
'Paper', 'Collectible', '', '',	125, 127,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 127, 'Postcards', 'Collectible Paper Postcards',
'0', '1', '0', 124, 1, 0, 0,
'Paper', 'Collectible', '', '',	126, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 128, 'Ethnographic', 'Collectible Ethnographic',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	124, 129,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 129, 'Railroadiana', 'Collectible Railroadiana',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	128, 132,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 130, 'General', 'Collectible Railroadiana General',
'0', '1', '0', 129, 1, 0, 0,
'Railroadiana', 'Collectible', '', '',	0, 131,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 131, 'Models', 'Collectible Railroadiana Models',
'0', '1', '0', 129, 1, 0, 0,
'Railroadiana', 'Collectible', '', '',	130, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 132, 'Radio', 'Collectible Radio',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	129, 133,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 133, 'Tobacciana', 'Collectible Tobacciana',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	132, 134,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 134, 'Militaria', 'Collectible Militaria',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	133, 137,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 135, 'General', 'Collectible Militaria General',
'0', '1', '0', 134, 1, 0, 0, 
'Militaria', 'Collectible','', '', 0, 136,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 136, 'Civil War', 'Collectible Militaria Civil War',
'0', '1', '0', 134, 1, 0, 0, 
'Militaria', 'Collectible','', '', 135, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 137, 'Disney', 'Collectible Disney',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	134, 147,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 138, 'Current Year', 'Collectible Disney Current Year',
'0', '1', '0', 137, 1, 0, 0, 
'Disney', 'Collectible',	'', '',	0, 139,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 139, 'Past Years', 'Collectible Disney Past Years',
'0', '0', '0', 137, 1, 0, 0, 
'Disney', 'Collectible',	'', '',	138, 146,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 140, 'General', 'Collectible Disney Past Years General',
'0', '1', '0', 139, 137, 1, 0,
'Past Years', 'Disney', 'Collectible', '', 0, 141,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 141, 'Figures', 'Collectible Disney Past Years Figures',
'0', '1', '0', 139, 137, 1, 0,
'Past Years', 'Disney', 'Collectible', '', 140, 142,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 142, 'Plush Toys', 'Collectible Disney Past Years Plush Toys',
'0', '1', '0', 139, 137, 1, 0,
'Past Years', 'Disney', 'Collectible', '', 141, 143,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 143, 'Pins, Buttons', 'Collectible Disney Past Years Pins and Buttons',
'0', '1', '0', 139, 137, 1, 0,
'Past Years', 'Disney', 'Collectible', '', 142, 144,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 144, 'Posters, Lithos', 'Collectible Disney Past Years Posters, Lithos',
'0', '1', '0', 139, 137, 1, 0,
'Past Years', 'Disney', 'Collectible', '', 143, 145,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 145, 'Videos', 'Collectible Disney Past Years Videos',
'0', '1', '0', 139, 137, 1, 0,
'Past Years', 'Disney', 'Collectible', '', 144, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 146, 'Other', 'Collectible Disney Other',
'0', '1', '0', 137, 1, 0, 0, 
'Disney', 'Collectible',	'', '',	139, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 147, 'Hallmark', 'Collectible Hallmark',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	137, 148,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 148, 'Ornaments', 'Collectible Ornaments',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	147, 149,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 149, 'Banks', 'Collectible Banks',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	148, 150,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 150, 'Lunchboxes', 'Collectible Lunchboxes',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	149, 151,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 151, 'Pez', 'Collectible Pez',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	150, 152,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 152, 'Science Fiction', 'Collectible Science Fiction',
'0', '0', '0', 1, 0, 0, 0, 
'Collectible', '',	'', '',	151, 157,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 153, 'General', 'Collectible Science Fiction General',
'0', '1', '0', 152, 1, 0, 0,
'Science Fiction', 'Collectible','', '', 0, 154,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 154, 'Star Wars', 'Collectible Science Fiction Star Ward',
'0', '1', '0', 152, 1, 0, 0,
'Science Fiction', 'Collectible','', '', 153, 155,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 155, 'Star Trek', 'Collectible Science Fiction Star Trek',
'0', '1', '0', 152, 1, 0, 0,
'Science Fiction', 'Collectible','', '', 154, 156,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 156, 'X Files', 'Collectible Science Fiction X Files',
'0', '1', '0', 152, 1, 0, 0,
'Science Fiction', 'Collectible','', '', 155, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 157, 'Sheet Music', 'Collectible Sheet Music',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible','', '', '', 152, 158,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 158, 'Writing Instruments', 'Collectible Writing Instruments',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible','', '', '', 157, 159,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 159, 'Miscellaneous', 'Collectible Miscellaneous',
'0', '1', '0', 1, 0, 0, 0, 
'Collectible','', '', '', 158, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 160, 'Computers', 'Computers',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	1, 195,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 161, 'Hardware', 'Computers Hardware',
'0', '0', '0', 160, 0, 0, 0, 
'Computers', '',	'', '',	0, 181,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 162, 'General', 'Computers Hardware General',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	0, 163,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 163, 'Books', 'Computers Hardware Books',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	162, 164,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 164, 'CPUs', 'Computers Hardware CPUs',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	163, 165,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 165, 'Drives', 'Computers Hardware Drives',
'0', '0', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	164, 170,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 166, 'CD ROM', 'Computers Hardware Drives CD ROM',
'0', '1', '0', 165, 161, 160, 0,
'Drives', 'Hardware', 'Computers', '',	0, 167,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 167, 'IDE', 'Computers Hardware Drives IDE',
'0', '1', '0', 165, 161, 160, 0,
'Drives', 'Hardware', 'Computers', '',	166, 168,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 168, 'SCSI', 'Computers Hardware Drives SCSI',
'0', '1', '0', 165, 161, 160, 0,
'Drives', 'Hardware', 'Computers', '',	167, 169,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 169, 'Floppy, Other', 'Computers Hardware Drives Floppy, other',
'0', '1', '0', 165, 161, 160, 0,
'Drives', 'Hardware', 'Computers', '',	168, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 170, 'Input Peripherals', 'Computers Hardware Input Peripherals',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	165, 171,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 171, 'Macintosh', 'Computers Hardware Macintosh',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	170, 172,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 172, 'Memory', 'Computers Hardware Memory',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	171, 173,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 173, 'Modems', 'Computers Hardware Modems',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	172, 174,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 174, 'Monitors', 'Computers Hardware Monitors',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	173, 175,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 175, 'Multimedia', 'Computers Hardware Multimedia',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	174, 176,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 176, 'Networking', 'Computers Hardware Networking',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	175, 177,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 177, 'Portable', 'Computers Hardware Portable',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	176, 178,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 178, 'Printers', 'Computers Hardware Printers',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	177, 179,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 179, 'Systems', 'Computers Hardware Systems',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	178, 180,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 180, 'Video', 'Computers Hardware Video',
'0', '1', '0', 161, 160, 0, 0,
'Hardware', 'Computers','', '',	179, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 181, 'Software', 'Computers Software',
'0', '0', '0', 160, 0, 0, 0, 
'Computers', '',	'', '',	161, 192,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 182, 'General', 'Computers Software General',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	0, 183,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 183, 'Books', 'Computers Software Books',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	182, 184,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 184, 'Business', 'Computers Software Business',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	183, 185,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 185, 'Desktop Publishing', 'Computers Software Desktop Publishing',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	184, 186,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 186, 'Educational', 'Computers Software Educational',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	185, 187,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 187, 'Games', 'Computers Software Games',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	186, 188,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 188, 'Graphics, Multimedia', 'Computers Software Graphics, Multimedia',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	187, 189,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 189, 'Macintosh', 'Computers Software Macintosh',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	188, 190,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 190, 'Sega, Nintendo', 'Computers Software Sega, Nintendo',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	189, 191,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 191, 'Utilities', 'Computers Software Utilities',
'0', '1', '0', 181, 160, 0, 0, 
'Software', 'Computers','', '',	190, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 192, 'Services', 'Computers Services',
'0', '0', '0', 160, 0, 0, 0, 
'Computers', '',	'', '',	181, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 193, 'General', 'Computers Services General',
'0', '1', '0', 192, 160, 0, 0, 
'Services', 'Computers','', '',	0, 194,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 194, 'Web Hosting', 'Computers Services Web Hosting',
'0', '1', '0', 192, 160, 0, 0, 
'Services', 'Computers','', '',	193, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 195, 'Memorabilia', 'Memorabilia',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	160, 212,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 196, 'Movie', 'Memorabilia Movie',
'0', '0', '0', 195, 0, 0, 0, 
'Memorabilia', '',	'', '',	0, 201,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 197, 'General', 'Memorabilia Movie General',
'0', '1', '0', 196, 195, 0, 0, 
'Movie', 'Memorabilia', '', '',	0, 198,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 198, 'Posters, Lobby Cards', 'Memorabilia Movie Posters, Lobby Cards',
'0', '1', '0', 196, 195, 0, 0, 
'Movie', 'Memorabilia', '', '',	197, 199,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 199, 'Lithographs', 'Memorabilia Movie Lithographs',
'0', '1', '0', 196, 195, 0, 0, 
'Movie', 'Memorabilia', '', '',	198, 200,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 200, 'Photos', 'Memorabilia Movie Photos',
'0', '1', '0', 196, 195, 0, 0, 
'Movie', 'Memorabilia', '', '',	199, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 201, 'Television', 'Memorabilia Television',
'0', '1', '0', 195, 0, 0, 0, 
'Memorabilia', '',	'', '',	196, 202,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 202, 'Sports', 'Memorabilia Sports',
'0', '0', '0', 195, 0, 0, 0, 
'Memorabilia', '',	'', '',	201, 208,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 203, 'General', 'Memorabilia Sports General',
'0', '1', '0', 202, 195, 0, 0, 
'Sports', 'Memorabilia','', '',	0, 204,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 204, 'Baseball', 'Memorabilia Sports Baseball',
'0', '1', '0', 202, 195, 0, 0, 
'Sports', 'Memorabilia','', '',	203, 205,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 205, 'Basketball', 'Memorabilia Sports Basketball',
'0', '1', '0', 202, 195, 0, 0, 
'Sports', 'Memorabilia','', '',	204, 206,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 206, 'Football', 'Memorabilia Sports Football',
'0', '1', '0', 202, 195, 0, 0, 
'Sports', 'Memorabilia','', '',	205, 207,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 207, 'NASCAR', 'Memorabilia Sports NASCAR',
'0', '1', '0', 202, 195, 0, 0, 
'Sports', 'Memorabilia','', '',	206, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 208, 'Historical', 'Memorabilia Historical',
'0', '1', '0', 195, 0, 0, 0,
'Memorabilia','', '', '',	202, 209,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 209, 'Western', 'Memorabilia Western',
'0', '1', '0', 195, 0, 0, 0,
'Memorabilia','', '', '',	208, 210,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 210, 'Rock-n-Roll', 'Memorabilia Rock-n-Roll',
'0', '1', '0', 195, 0, 0, 0,
'Memorabilia','', '', '',	209, 211,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 211, 'Other', 'Memorabilia Other',
'0', '1', '0', 195, 0, 0, 0,
'Memorabilia','', '', '',	210, 0,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 212, 'Trading Cards', 'Trading Cards',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	195, 220,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 213, 'Baseball', 'Trading Cards Baseball',
'0', '1', '0', 212, 0, 0, 0, 
'Trading Cards', '',	'', '',	0, 214,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 214, 'Basketball', 'Trading Cards Basketball',
'0', '1', '0', 212, 0, 0, 0, 
'Trading Cards', '',	'', '',	213, 215,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 215, 'Football', 'Trading Cards Football',
'0', '1', '0', 212, 0, 0, 0, 
'Trading Cards', '',	'', '',	214, 216,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 216, 'Hockey', 'Trading Cards Hockey',
'0', '1', '0', 212, 0, 0, 0, 
'Trading Cards', '',	'', '',	215, 217,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 217, 'Other Sports', 'Trading Cards Other Sports',
'0', '1', '0', 212, 0, 0, 0, 
'Trading Cards', '',	'', '',	216, 218,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 218, 'Magic', 'Trading Cards Magic',
'0', '1', '0', 212, 0, 0, 0, 
'Trading Cards', '',	'', '',	217, 219,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 219, 'Other Non-Sports', 'Trading Cards Other Non-Sports',
'0', '1', '0', 212, 0, 0, 0, 
'Trading Cards', '',	'', '',	218, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 220, 'Toys', 'Toys',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	212, 237,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 221, 'General', 'Toys General',
'0', '1', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	0, 222,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 222, 'Diecast', 'Toys Diecast',
'0', '0', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	221, 226,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 223, 'General', 'Toys Diecast General',
'0', '1', '0', 222, 220, 0, 0,
'Diecast', 'Toys',	'', '',	0, 224,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 224, 'HotWheels', 'Toys Diecast Hot Wheels',
'0', '1', '0', 222, 220, 0, 0,
'Diecast', 'Toys',	'', '',	223, 225,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 225, 'NASCAR', 'Toys Diecast NASCAR',
'0', '1', '0', 222, 220, 0, 0,
'Diecast', 'Toys',	'', '',	224, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 226, 'Plush', 'Toys Plush',
'0', '0', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	222, 229,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 227, 'General', 'Toys Plush General',
'0', '1', '0', 226, 220, 0, 0, 
'Plush', 'Toys','', '',	0, 228,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 228, 'Care Bears', 'Toys Plush Care Bears',
'0', '1', '0', 226, 220, 0, 0, 
'Plush', 'Toys', '', '',	227, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 229, 'Wind-up', 'Toys Wind-up',
'0', '1', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	226, 230,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 230, 'Beanine Babies', 'Toys Beanie Babies',
'0', '1', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	229, 231,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 231, 'Fast Food', 'Toys Fast Food',
'0', '1', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	230, 232,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 232, 'Tamagotchi, Giga Pet', 'Toys Tamagotchi, Giga Pets',
'0', '1', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	231, 233,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 233, 'Games, Hobbies', 'Toys Games, Hobbies',
'0', '0', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	232, 236,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 234, 'General', 'Toys Games, Hobbies, General',
'0', '1', '0', 233, 220, 0, 0, 
'Games, Hobbies', 'Toys', '', '', 0, 235,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 235, 'Board Games', 'Toys Games, Hobbies, Board Games',
'0', '1', '0', 233, 220, 0, 0, 
'Games, Hobbies', 'Toys', '', '', 234, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 236, 'Marbles', 'Toys Marbles',
'0', '1', '0', 220, 0, 0, 0, 
'Toys', '',	'', '',	233, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 237, 'Dolls, Figures', 'Dolls, Figures',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	220, 252,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 238, 'Dolls', 'Dolls, Figures Dolls',
'0', '0', '0', 237, 0, 0, 0, 
'Dolls, Figures', '',	'', '',	0, 244,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 239, 'General', 'Dolls, Figures Dolls General',
'0', '1', '0', 238, 237, 0, 0,
'Dolls', 'Dolls, Figures','', '',	0, 240,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 240, 'Madame Alexander', 'Dolls, Figures Dolls Madame Alexander',
'0', '1', '0', 238, 237, 0, 0,
'Dolls', 'Dolls, Figures','', '', 239, 241,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 241, 'Raggedy Ann, Andy', 'Dolls, Figures Dolls Raggedy Ann',
'0', '1', '0', 238, 237, 0, 0,
'Dolls', 'Dolls, Figures','', '', 240, 242,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 242, 'Strawberry Shortcake', 'Dolls, Figures Dolls Strawberry Shortcake',
'0', '1', '0', 238, 237, 0, 0,
'Dolls', 'Dolls, Figures','', '', 241, 243,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 243, 'Cabbage patch', 'Dolls, Figures Dolls Cabbage Patch',
'0', '1', '0', 238, 237, 0, 0,
'Dolls', 'Dolls, Figures','', '', 242, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 244, 'Figures', 'Dolls, Figures Figures',
'0', '0', '0', 237, 0, 0, 0, 
'Dolls, Figures', '',	'', '',	238, 247,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 245, 'General', 'Dolls, Figures Figures General',
'0', '1', '0', 244, 237, 0, 0,
'Figures', 'Dolls, Figures','', '',	0, 246,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 246, 'Action Figures', 'Dolls, Figures Figures Action Figures',
'0', '1', '0', 244, 237, 0, 0,
'Figures', 'Dolls, Figures','', '', 245, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 247, 'Barbie', 'Dolls, Figures Barbie',
'0', '0', '0', 237, 0, 0, 0, 
'Dolls, Figures', '',	'', '',	244, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 248, 'General', 'Dolls, Figures Barbie General',
'0', '1', '0', 247, 237, 0, 0,
'Barbie', 'Dolls, Figures','', '',	0, 249,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 249, 'Accessories', 'Dolls, Figures Barbie Accessories',
'0', '1', '0', 247, 237, 0, 0,
'Barbie', 'Dolls, Figures','', '', 248, 250,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 250, 'Vintage Barbie', 'Dolls, Figures Barbie Vintage Barbie',
'0', '1', '0', 247, 237, 0, 0,
'Barbie', 'Dolls, Figures','', '', 249, 251,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 251, 'Vintage Accessories', 'Dolls, Figures Barbie Vintage Accessories',
'0', '1', '0', 247, 237, 0, 0,
'Barbie', 'Dolls, Figures','', '', 250, 0,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 252, 'Coins', 'Coins',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	237, 260,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 253, 'US', 'Coins US',
'0', '0', '0', 252, 0, 0, 0, 
'Coins', '',	'', '',	0, 256,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 254, 'Coins, Currency', 'Coins US Coins and Currency',
'0', '1', '0', 253, 252, 0, 0,
'US', 'Coins', '', '',	0, 255,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 255, 'Certificates', 'Coins US Certificates',
'0', '1', '0', 253, 252, 0, 0,
'US', 'Coins', '', '',	255, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 256, 'Non-US', 'Coins Non-US',
'0', '0', '0', 252, 0, 0, 0, 
'Coins', '',	'', '',	253, 259,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 257, 'Coins, Currency', 'Coins Non-US Coins and Currency',
'0', '1', '0', 256, 252, 0, 0,
'Non-US', 'Coins','', '', 0, 258,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 258, 'Certificates', 'Coins Non-US Certificates',
'0', '1', '0', 256, 252, 0, 0,
'Non-US', 'Coins','', '', 257, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 259, 'Exonumia', 'Coins Exonumia',
'0', '1', '0', 252, 0, 0, 0, 
'Coins', '',	'', '',	256, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 260, 'Stamps', 'Stamps',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	252, 266,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 261, 'Geographical', 'Stamps Geographical',
'0', '0', '0', 260, 0, 0, 0, 
'Stamps', '',	'', '',	0, 265,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 262, 'US', 'Stamps Geographical US',
'0', '1', '0', 261, 260, 0, 0,
'Geographical', 'Stamps','', '', 0, 263,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 263, 'Britain, Colonies', 'Stamps Geographical Britain, Colonies',
'0', '1', '0', 261, 260, 0, 0,
'Geographical', 'Stamps','', '', 262, 264,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 264, 'Rest of World', 'Stamps Geographical Rest of World',
'0', '1', '0', 261, 260, 0, 0,
'Geographical', 'Stamps','', '', 263, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 265, 'Topical', 'Stamps Topical',
'0', '1', '0', 260, 0, 0, 0, 
'Stamps', '',	'', '',	261, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 266, 'Books, Magazines', 'Books, Magazines',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	260, 281,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 267, 'Books', 'Books, Magazines Books',
'0', '0', '0', 266, 0, 0, 0, 
'Books, Magazines', '',	'', '',	0, 280,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 268, 'General', 'Books, Magazines Books General',
'0', '1', '0', 267, 266, 0, 0,
'Books', 'Books, Magazines','', '',	0, 269,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 269, 'Rare', 'Books, Magazines Books Rare',
'0', '1', '0', 267, 266, 0, 0,
'Books', 'Books, Magazines','', '',	268, 270,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 270, 'Fiction', 'Books, Magazines Books Fiction',
'0', '0', '0', 267, 266, 0, 0,
'Books', 'Books, Magazines','', '',	269, 274,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 271, 'Adventure', 'Books, Magazines Books Fiction Adventure',
'0', '1', '0', 270, 267, 266, 0,
'Fiction', 'Books', 'Books, Magazines','',0, 272,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 272, 'Mystery', 'Books, Magazines Books Fiction Mystery',
'0', '1', '0', 270, 267, 266, 0,
'Fiction', 'Books', 'Books, Magazines','',271, 273,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 273, 'Sci-Fi', 'Books, Magazines Books Fiction Sci-Fi',
'0', '1', '0', 270, 267, 266, 0,
'Fiction', 'Books', 'Books, Magazines','',272, 325,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 325, 'Romance', 'Books, Magazines Books Fiction Romance',
'0', '1', '0', 270, 267, 266, 0,
'Fiction', 'Books', 'Books, Magazines','',273, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 274, 'Non-Fiction', 'Books, Magazines Books Non-Fiction',
'0', '0', '0', 267, 266, 0, 0,
'Books', 'Books, Magazines','', '',	270, 279,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 275, 'Travel', 'Books, Magazines Books Non-Fiction Travel',
'0', '1', '0', 274, 267, 266, 0,
'Non-Fiction', 'Books', 'Books, Magazines', '',	0, 276,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 276, 'Cooking', 'Books, Magazines Books Non-Fiction Cooking',
'0', '1', '0', 274, 267, 266, 0,
'Non-Fiction', 'Books', 'Books, Magazines', '',	275, 277,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 277, 'Auto/Biography', 'Books, Magazines Books Non-Fiction Auto/Biography',
'0', '1', '0', 274, 267, 266, 0,
'Non-Fiction', 'Books', 'Books, Magazines', '',	276, 278,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 278, 'Collectibles', 'Books, Magazines Books Non-Fiction Collectibles',
'0', '1', '0', 274, 267, 266, 0,
'Non-Fiction', 'Books', 'Books, Magazines', '',	277, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 279, 'Children', 'Books, Magazines Books Children',
'0', '1', '0', 267, 266, 0, 0,
'Books', 'Books, Magazines','', '',	267, 0,
9.95, sysdate, '', sysdate);


insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 280, 'Magazines', 'Books, Magazines Magazines',
'0', '1', '0', 266, 0, 0, 0, 
'Books, Magazines', '',	'', '',	267, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 281, 'Jewelry, Gemstones', 'Jewelry, Gemstones',
'0', '0', '0', 0, 0, 0, 0, 
'', '',	'', '',	266, 99,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 282, 'Gemstones', 'Jewelry, Gemstones, Gemstones',
'0', '1', '0', 281, 0, 0, 0, 
'Jewelry, Gemstones', '',	'', '',	0, 283,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 283, 'Jewelry', 'Jewelry, Gemstones, Jewelry',
'0', '0', '0', 281, 0, 0, 0, 
'Jewelry, Gemstones', '',	'', '',	282, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 284, 'General', 'Jewelry, Gemstones, Jewelry General',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 0, 285,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 285, 'Sterling', 'Jewelry, Gemstones, Jewelry Sterling',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 284, 286,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 286, 'Gold', 'Jewelry, Gemstones, Jewelry Gold',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 285, 287,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 287, 'Carved, Cameo', 'Jewelry, Gemstones, Jewelry Carved, Cameo',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 286, 288,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 288, 'Ancient/Ethnographic', 'Jewelry, Gemstones, Jewelry Ancient/Ethnographic',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 287, 289,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 289, 'Costume', 'Jewelry, Gemstones, Jewelry Costume',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 288, 290,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 290, 'Watches, Timepieces', 'Jewelry, Gemstones, Jewelry Watches, Timepieces',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 289, 291,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 291, 'Vintage', 'Jewelry, Gemstones, Jewelry Vintage',
'0', '1', '0', 283, 281, 0, 0,
'Jewelry', 'Jewelry, Gemstones', '',	'', 290, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 324, 'General', 'Miscellaneous General',
'0', '1', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	0, 292,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 292, 'Automotive', 'Miscellaneous Automotive',
'0', '1', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	324, 293,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 293, 'Consumer Electronics', 'Miscellaneous Consumer Electronics',
'0', '0', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	292, 299,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 294, 'General', 'Miscellaneous Consumer Electronics General',
'0', '1', '0', 293, 99, 0, 0,
'Consumer Electronics', 'Miscellaneous', '',	'', 0, 295,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 295, 'Audio Equipment', 'Miscellaneous Consumer Electronics Audio Equipment',
'0', '1', '0', 293, 99, 0, 0,
'Consumer Electronics', 'Miscellaneous', '',	'', 294, 296,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 296, 'Radio Equipment', 'Miscellaneous Consumer Electronics Radio Equipment',
'0', '1', '0', 293, 99, 0, 0,
'Consumer Electronics', 'Miscellaneous', '',	'', 295, 297,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 297, 'Photo Equipment', 'Miscellaneous Consumer Electronics Photo Equipment',
'0', '1', '0', 293, 99, 0, 0,
'Consumer Electronics', 'Miscellaneous', '',	'', 296, 298,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 298, 'Video Equipment', 'Miscellaneous Consumer Electronics Video Equipment',
'0', '1', '0', 293, 99, 0, 0,
'Consumer Electronics', 'Miscellaneous', '',	'', 297, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 299, 'Household', 'Miscellaneous Household',
'0', '0', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	293, 302,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 300, 'General', 'Miscellaneous Household General',
'0', '1', '0', 299, 99, 0, 0,
'Household', 'Miscellaneous','', '',	0, 301,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 301, 'Pet Supplies', 'Miscellaneous Household Pet Supplies',
'0', '1', '0', 299, 99, 0, 0,
'Household', 'Miscellaneous','', '',	300, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 302, 'Business, Office', 'Miscellaneous Business Office',
'0', '1', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	299, 303,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 303, 'Tools', 'Miscellaneous Tools',
'0', '1', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	302, 304,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 304, 'Music', 'Miscellaneous Music',
'0', '0', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	302, 309,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 305, 'General', 'Miscellaneous Music General',
'0', '1', '0', 304, 99, 0, 0,
'Music', 'Miscellaneous','', '', 0, 306,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 306, 'Records, Tapes', 'Miscellaneous Music Records, Tapes',
'0', '1', '0', 304, 99, 0, 0,
'Music', 'Miscellaneous','', '', 305, 307,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 307, 'CDs', 'Miscellaneous Music CDs',
'0', '1', '0', 304, 99, 0, 0,
'Music', 'Miscellaneous','', '', 306, 308,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 308, 'Instruments', 'Miscellaneous Music Instruments',
'0', '1', '0', 304, 99, 0, 0,
'Music', 'Miscellaneous','', '', 307, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 309, 'Movies, LaserDiscs', 'Miscellaneous Movies, LaserDiscs',
'0', '1', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	304, 310,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 310, 'Sporting Goods', 'Miscellaneous Sporting Goods',
'0', '1', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	309, 311,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 311, 'Clothing', 'Miscellaneous Clothing',
'0', '0', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	310, 316,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 312, 'General', 'Miscellaneous Clothing General',
'0', '1', '0', 311, 99, 0, 0,
'Clothing', 'Miscellaneous','', '', 0, 313,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 313, 'Men', 'Miscellaneous Clothing Men',
'0', '1', '0', 311, 99, 0, 0,
'Clothing', 'Miscellaneous','', '', 312, 314,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 314, 'Women', 'Miscellaneous Clothing Women',
'0', '1', '0', 311, 99, 0, 0,
'Clothing', 'Miscellaneous','', '', 313, 315,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 315, 'Children', 'Miscellaneous Clothing Children',
'0', '1', '0', 311, 99, 0, 0,
'Clothing', 'Miscellaneous','', '', 314, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 316, 'Services', 'Miscellaneous Services',
'0', '0', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	311, 319,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 317, 'General', 'Miscellaneous Services General',
'0', '1', '0', 316, 99, 0, 0,
'Services', 'Miscellaneous', '', '', 0, 318,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 318, 'Information Services', 'Miscellaneous Services Information Services',
'0', '1', '0', 316, 99, 0, 0,
'Services', 'Miscellaneous', '', '', 317, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 319, 'Erotica, Adult Only', 'Miscellaneous Erotica, Adult only',
'1', '0', '0', 99, 0, 0, 0, 
'Miscellaneous', '',	'', '',	316, 0,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 320, 'General', 'Miscellaneous Erotica, Adult only, General',
'1', '1', '0', 319, 99, 0, 0,
'Erotica, Adult Only', 'Miscellaneous',	'', '',	0, 321,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 321, 'Video', 'Miscellaneous Erotica, Adult only, Video',
'1', '1', '0', 319, 99, 0, 0,
'Erotica, Adult Only', 'Miscellaneous',	'', '',	320, 322,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 322, 'CD', 'Miscellaneous Erotica, Adult only, CD',
'1', '1', '0', 319, 99, 0, 0,
'Erotica, Adult Only', 'Miscellaneous',	'', '',	321, 323,
9.95, sysdate, '', sysdate);

insert into ebay_categories
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)				
values	
(0, 323, 'Photographic', 'Miscellaneous Erotica, Adult only, Photographic',
'1', '1', '0', 319, 99, 0, 0,
'Erotica, Adult Only', 'Miscellaneous',	'', '',	323, 0,
9.95, sysdate, '', sysdate);


