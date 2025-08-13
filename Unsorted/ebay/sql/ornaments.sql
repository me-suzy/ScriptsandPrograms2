/*	$Id: ornaments.sql,v 1.3 1999/02/21 02:54:45 josh Exp $	*/
/* move ornaments start with all siblings */
update ebay_categories set prevcategory=373 where id=124;
update ebay_categories set nextcategory=124 where id=373;

update ebay_categories set prevcategory=148 where id=1090;
update ebay_categories set nextcategory=148 where id=908;

/*now get ornament up the speed with levels */
update ebay_categories set 
description='Collectibles Holiday, Seasonal Christmas Ornaments', 
level1=1089, level2=907, level3= 1,
name1='Christmas', name2='Holiday, Seasonal', name3='Collectibles', 
prevcategory=908, nextcategory=1090 where id=148;
