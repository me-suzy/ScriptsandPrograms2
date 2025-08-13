/*	$Id: ebay_item_indexes.sql,v 1.2 1999/02/21 02:53:48 josh Exp $	*/
 create index ebay_items_starting_index
   on ebay_items(sale_start)
   tablespace bidi01
   storage(initial 10M next 2M);

 create index ebay_items_ending_index
   on ebay_items(sale_end)
   tablespace bidi01
   storage(initial 10M next 2M);

 create index ebay_items_category_index 
	on ebay_items(category)
	tablespace bidi01
	storage(initial 10m next 2m);

 create index ebay_items_seller_index
	on ebay_items(seller)
	tablespace bidi01
	storage(initial 10m next 2);

 create index ebay_items_high_bidder_index
   on ebay_items(high_bidder)
   tablespace bidi01
   storage(initial 10m next 2);
