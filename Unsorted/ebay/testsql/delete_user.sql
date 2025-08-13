/*	$Id: delete_user.sql,v 1.2 1999/02/21 02:55:42 josh Exp $	*/
/* script to remove user from the database */
/* only used in test, please */

PROMPT Please enter a valid user id (number)
accept newid char prompt 'ID:'

delete from ebay_feedback_detail where id=&newid;
delete from ebay_feedback where id=&newid;
delete from ebay_feedback_detail where id=&newid ;
delete from ebay_feedback_detail where commenting_id=&newid;

delete from ebay_bb_board where id=&newid;
delete from ebay_qa_board where id=&newid;

delete from ebay_account_balances where id=&newid;
delete from ebay_accounts where id=&newid;

delete from ebay_admin where id=&newid;
delete from ebay_bids where user_id=&newid;

delete from ebay_item_dutch_high_bidder where HIGH_BIDDER=&newid;
delete from ebay_items where seller=&newid;
delete from ebay_items where owner=&newid;
delete from ebay_items where HIGH_BIDDER=&newid;

delete from ebay_user_info where id=&newid;
delete from ebay_users where id=&newid;

