/*	$Id: extend_auctions.sql,v 1.5 1999/03/22 00:09:46 josh Exp $	*/
/* script to extend all auctions within certain date range */
/* change condition if needed depending on sale start or sale */
/* end. If sale start, make sure sale hasn't ended!!! */

/* invalidate bidder list - MODIFY DATE */
update ebay_bidder_item_lists set item_list_valid = 'N'
where id in 
(select high_bidder from ebay_items where
sale_end >= 
TO_DATE('1999-02-27 15:43:00', 'YYYY-MM-DD HH24:MI:SS')
and sale_end <= 
TO_DATE('1999-02-27 16:00:00', 'YYYY-MM-DD HH24:MI:SS'));

/* invalidate seller list - MODIFY DATE */
update ebay_seller_item_lists set item_list_valid = 'N'
where id in 
(select seller from ebay_items where
sale_end >= 
TO_DATE('1999-02-27 15:43:00', 'YYYY-MM-DD HH24:MI:SS')
and sale_end <= 
TO_DATE('1999-02-27 16:00:00', 'YYYY-MM-DD HH24:MI:SS'));

/* extend auctions, touch - MODIFY DATE */
update ebay_items set sale_end = sale_end + 1,
last_modified = sysdate where 
sale_end >= 
TO_DATE('1999-02-27 12:43:00', 'YYYY-MM-DD HH24:MI:SS')
and sale_end <= 
TO_DATE('1999-02-27 16:00:00', 'YYYY-MM-DD HH24:MI:SS');
commit;
