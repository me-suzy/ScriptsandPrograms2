/*	$Id: reorder_top.sql,v 1.3 1999/02/21 02:54:58 josh Exp $	*/
update ebay_categories set 
nextcategory=266
where id=353;

update ebay_categories set 
nextcategory=237
where id=160;

update ebay_categories set 
nextcategory=99
where id=220;

update ebay_categories set 
prevcategory=866
where id=1;

update ebay_categories set 
prevcategory=870
where id=888;

update ebay_categories set 
prevcategory=220
where id=99;

update ebay_categories set 
prevcategory=160,
nextcategory=281
where id=237;

update ebay_categories set 
prevcategory=266,
nextcategory=1
where id=866;

update ebay_categories set 
prevcategory=353,
nextcategory=866
where id=266;

update ebay_categories set 
prevcategory=237,
nextcategory=1047
where id=281;

update ebay_categories set 
prevcategory=1047,
nextcategory=888
where id=870;

update ebay_categories set 
prevcategory=281,
nextcategory=870
where id=1047;

commit;
