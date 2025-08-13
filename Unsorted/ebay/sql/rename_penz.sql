/*	$Id: rename_penz.sql,v 1.3 1999/02/21 02:54:57 josh Exp $	*/
update ebay_categories set 
name='Pennzoil',
description='Collectibles Advertising Gasoline Pennzoil'
where id=835;
commit;
