/*	$Id: ebay_categories.sql,v 1.3 1999/05/07 02:32:52 wwen Exp $	*/
/*
 * ebay_categories.sql
 * contains hierarchy information as well as previous category for sibling
 * ordering
 */

drop table ebay_categories;

/* modified. see below for new declaration.
 create table ebay_categories
 (
	MARKETPLACE			NUMBER(38)
		constraint		categories_marketplace_fk
			references ebay_marketplaces(id),
	ID					NUMBER(38)
		constraint		categories_id_nn
			not null,
	NAME				VARCHAR2(20)
		constraint		categories_name_nn
			not null,
	DESCRIPTION			VARCHAR2(255)
		constraint		categories_desc_nn
			not null,
	ADULT				CHAR(1)
		constraint		categories_adult_nn
			not null,
	ISLEAF				CHAR(1)
		constraint		categories_isleaf_nn
			not null,
	ISEXPIRED			CHAR(1)
		constraint		categories_isexpired_nn
			not null,
	LEVEL1				NUMBER(38)
		constraint		categories_level1_nn
			not null,
	LEVEL2				NUMBER(38)
		constraint		categories_level2_nn
			not null,
	LEVEL3				NUMBER(38)
		constraint		categories_level3_nn
			not null,
	LEVEL4				NUMBER(38)
		constraint		categories_level4_nn
			not null,
	NAME1				VARCHAR2(20),
	NAME2				VARCHAR2(20),	
	NAME3				VARCHAR2(20),
	NAME4				VARCHAR2(20),				
	PREVCATEGORY		NUMBER(38)
		constraint		categories_prev_cat_nn
			not null,
	NEXTCATEGORY		NUMBER(38)
		constraint		categories_next_cat_nn
			not null,
	FEATUREDCOST		FLOAT(126)
		constraint		categories_featured_cost_nn
			not null,
	CREATED				DATE
		constraint		categories_created_nn
			not null,
	FILEREFERENCE		VARCHAR2(255),
	last_modified		date
		constraint		categories_last_modified_nn
		not null,
	constraint			categories_pk
		primary key		(marketplace, id)
		using index tablespace itemi01
		storage (initial 1M next 500K)
)
tablespace itemd01
storage (initial 1M next 500K);
*/
 drop sequence ebay_categories_sequence;

 create sequence ebay_categories_sequence start with 400;
/* old declaration 
 alter table ebay_categories
	add (order_no number(38) default 0);

 create index ebay_categories_sort_index
	on ebay_categories(order_no)
   tablespace itemi01
	storage(initial 1M next 1M);
*/
 create table ebay_categories
 (
	MARKETPLACE			NUMBER(38)
		constraint		categories_marketplace_fk
			references ebay_marketplaces(id),
	ID					NUMBER(10)
		constraint		categories_id_nn
			not null,
	NAME				VARCHAR2(20)
		constraint		categories_name_nn
			not null,
	DESCRIPTION			VARCHAR2(255)
		constraint		categories_desc_nn
			not null,
	ADULT				CHAR(1)
		constraint		categories_adult_nn
			not null,
	ISLEAF				CHAR(1)
		constraint		categories_isleaf_nn
			not null,
	ISEXPIRED			CHAR(1)
		constraint		categories_isexpired_nn
			not null,
	LEVEL1				NUMBER(10)
		constraint		categories_level1_nn
			not null,
	LEVEL2				NUMBER(10)
		constraint		categories_level2_nn
			not null,
	LEVEL3				NUMBER(10)
		constraint		categories_level3_nn
			not null,
	LEVEL4				NUMBER(10)
		constraint		categories_level4_nn
			not null,
	NAME1				VARCHAR2(20),
	NAME2				VARCHAR2(20),	
	NAME3				VARCHAR2(20),
	NAME4				VARCHAR2(20),				
	PREVCATEGORY		NUMBER(10)
		constraint		categories_prev_cat_nn
			not null,
	NEXTCATEGORY		NUMBER(10)
		constraint		categories_next_cat_nn
			not null,
	FEATUREDCOST		NUMBER(10,2)
		constraint		categories_featured_cost_nn
			not null,
	CREATED				DATE
		constraint		categories_created_nn
			not null,
	FILEREFERENCE		VARCHAR2(255),
	last_modified		date
		constraint		categories_last_modified_nn
		not null,
	order_no			number(10)
		default 0,
	ISFLAGGED			VARCHAR2(1),
	MASK_BIDDERS		VARCHAR2(1)
)
tablespace statmiscd
storage (initial 1M next 500K);

create index ebay_categories_sort_index
	on ebay_categories(order_no)
   tablespace statmisci
	storage(initial 1M next 500K) unrecoverable parallel (degree 3);

alter table ebay_categories
	add constraint			categories_pk
		primary key		(marketplace, id)
		using index tablespace statmisci
		storage (initial 1M next 500K);

 /*
  * These additional constraints are to force
  * Oracle to name the constraints what we want
  * them to. For example, if we list the constraints
  * on name as "not null, unique", only the FIRST
  * constraint gets the name, and the second one
  * gets and ORACLE generated name
  */

/*
insert into ebay_categories values(0, 1,   'Featured Auctions');
insert into ebay_categories values(0, 10,  'Misc. Collectibles');
insert into ebay_categories values(0, 10,  'Antiques (over 100 years)');
insert into ebay_categories values(0, 11,  'Autographs');
insert into ebay_categories values(0, 12,  'Figures, Dolls');
insert into ebay_categories values(0, 20,  'Art');
insert into ebay_categories values(0, 31,  'Books, Magazines');
insert into ebay_categories values(0, 32,  'Comics');
insert into ebay_categories values(0, 35,  'Coins, Currency, Certificates - U.S.');
insert into ebay_categories values(0, 36,  'Railroad');
insert into ebay_categories values(0, 66,  'Stamps: U.S.');
insert into ebay_categories values(0, 67,  'Stamps: Rest of World');
insert into ebay_categories values(0, 70,  'Trading Cards: Other Sports');
insert into ebay_categories values(0, 71,  'Trading Cards: Other Non-Sports');
insert into ebay_categories values(0, 72,  'Trading Cards: Magic');
insert into ebay_categories values(0, 73,  'Toys');
insert into ebay_categories values(0, 82,  'Costume Jewelry');
insert into ebay_categories values(0, 83,  'Decorative, Kitchenware, Pottery');
insert into ebay_categories values(0, 84,  'Games and Hobbies');
insert into ebay_categories values(0, 85,  'Memorabilia: Historical');
insert into ebay_categories values(0, 86,  'Memorabilia: Movie');
insert into ebay_categories values(0, 87,  'Memorabilia: Other');
insert into ebay_categories values(0, 88,  'Memorabilia: Sports');
insert into ebay_categories values(0, 89,  'Trading Cards: Baseball');
insert into ebay_categories values(0, 90,  'Trading Cards: Basketball');
insert into ebay_categories values(0, 91,  'Trading Cards: Football');
insert into ebay_categories values(0, 95,  'Postcards');
insert into ebay_categories values(0, 96,  'Barbie and Accessories');
insert into ebay_categories values(0, 97,  'Figures, Dolls: Action Figures');
insert into ebay_categories values(0, 98,  'Coins, Currency, Certificates - Non U.S.');
insert into ebay_categories values(0, 100, 'Stamps: British Commonwealth');
insert into ebay_categories values(0, 102, 'Antiques (less than 100 years)');
insert into ebay_categories values(0, 103, 'Clocks and Timepieces');
insert into ebay_categories values(0, 104, 'Vintage Sewing Items');
insert into ebay_categories values(0, 106, 'Antiques: Toys');
insert into ebay_categories values(0, 107, 'Art Pottery');
insert into ebay_categories values(0, 108, 'Contemporary Collectibles (less than 50 years)');
insert into ebay_categories values(0, 109, 'Cookie Jars, Salt and Pepper Shakers');
insert into ebay_categories values(0, 110, 'Decorative Collectibles');
insert into ebay_categories values(0, 111, 'Kitchen Collectibles');
insert into ebay_categories values(0, 112, 'Pottery, China, Porcelain');
insert into ebay_categories values(0, 113, 'Textiles, Linens, Quilts');
insert into ebay_categories values(0, 114, 'Vintage Collectibles (50-100 years old)');
insert into ebay_categories values(0, 115, 'Vintage: Clothing');
insert into ebay_categories values(0, 116, 'Advertising');
insert into ebay_categories values(0, 117, 'Magazines');
insert into ebay_categories values(0, 118, 'Epherma');
insert into ebay_categories values(0, 119, 'Hallmark');
insert into ebay_categories values(0, 120, 'Holiday, Seasonal Ornaments');
insert into ebay_categories values(0, 121, 'Soda Memorabilia');
insert into ebay_categories values(0, 122, 'Soda Memorabilia: Coca-Cola');
insert into ebay_categories values(0, 123, 'Exonumeria, Tokens, Medals');
insert into ebay_categories values(0, 124, 'Militaria');
insert into ebay_categories values(0, 125, 'Railroad: Models');
insert into ebay_categories values(0, 126, 'Disney');
insert into ebay_categories values(0, 127, 'Bears');
insert into ebay_categories values(0, 128, 'Figures: Sports');
insert into ebay_categories values(0, 129, 'Science Fiction');
insert into ebay_categories values(0, 130, 'Science Fiction: Star Wars');
insert into ebay_categories values(0, 131, 'Toys: Beanie Babies');
insert into ebay_categories values(0, 132, 'Toys: Diecast');
insert into ebay_categories values(0, 133, 'Toys: Plush');
insert into ebay_categories values(0, 136, 'Barbie and Accessories: Vintage');
insert into ebay_categories values(0, 40,  'Computer Hardware - General');
insert into ebay_categories values(0, 401, 'Computer Hardware - Books');
insert into ebay_categories values(0, 41,  'Computer Hardware - CPUs');
insert into ebay_categories values(0, 42,  'Computer Hardware - Drives');
insert into ebay_categories values(0, 421, 'Computer Hardware - Input Periphs.');
insert into ebay_categories values(0, 43,  'Computer Hardware - Macintosh');
insert into ebay_categories values(0, 44,	 'Computer Hardware - Memory');
insert into ebay_categories values(0, 45,  'Computer Hardware - Modems');
insert into ebay_categories values(0, 47,  'Computer Hardware - Multimedia');
insert into ebay_categories values(0, 471, 'Computer Hardware - Networking');
insert into ebay_categories values(0, 48,  'Computer Hardware - Printers');
insert into ebay_categories values(0, 46,  'Computer Hardware - Video');
insert into ebay_categories values(0, 134, 'Computer Hardware - Portable');
insert into ebay_categories values(0, 50,  'Computer Software - General');
insert into ebay_categories values(0, 501, 'Computer Software - Books');
insert into ebay_categories values(0, 51,  'Computer Software - Business');
insert into ebay_categories values(0, 52,  'Computer Software - Educational');
insert into ebay_categories values(0, 53,  'Computer Software - Games');
insert into ebay_categories values(0, 54,  'Computer Software - Macintosh');
insert into ebay_categories values(0, 55,  'Computer Software - Graphics and Multimedia');
insert into ebay_categories values(0, 57,  'Computer Software - Sega, Nintendo, etc.');
insert into ebay_categories values(0, 58,  'Computer Software - Utilities');
insert into ebay_categories values(0, 99,  'Audio Equipment');
insert into ebay_categories values(0, 30,  'Automotive');
insert into ebay_categories values(0, 81,  'Clothing');
insert into ebay_categories values(0, 60,  'Consumer Electronics');
insert into ebay_categories values(0, 64,  'Erotic, Adults Only');
insert into ebay_categories values(0, 80,  'Jewelry, Gemstones');
insert into ebay_categories values(0, 94,  'Movies, LaserDiscs, etc.');
insert into ebay_categories values(0, 92,  'Photography, Video Equipment');
insert into ebay_categories values(0, 61,  'Records, Tapes, CDs');
insert into ebay_categories values(0, 65,  'Services');
insert into ebay_categories values(0, 93,  'Sporting Goods');
insert into ebay_categories values(0, 105, 'Travel');
insert into ebay_categories values(0, 135, 'Furniture');
insert into ebay_categories values(0, 137, 'Musical Instruments');
insert into ebay_categories values(0, 990, 'Miscellaneous');

*/
