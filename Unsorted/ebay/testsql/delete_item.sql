/*	$Id: delete_item.sql,v 1.2 1999/02/21 02:55:41 josh Exp $	*/
/* script to remove item from the database */
/* only used in testsql, please */

PROMPT Please enter a valid item id (number)
accept newid char prompt 'ID:'

delete from ebay_item_info where id=&newid;
delete from ebay_item_dutch_high_bidder where id=&newid;
delete from ebay_bids where item_id=&newid;
/* delete from ebay_items_xref where id=&newid; */
delete from ebay_items where id=&newid;

/*  commit; */
