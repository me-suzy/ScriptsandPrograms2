/*	$Id: drop_all.sql,v 1.2 1999/02/21 02:52:56 josh Exp $	*/
/*
** drop_all.sql
** 
** Drops all tables in the right order to 
** avoid integrity constraints
**
*/
drop table ebay_accounts;
drop table ebay_account_balances;
drop table ebay_feedback;
drop table ebay_feedback_detail;
drop table ebay_bids;
drop table ebay_items;
drop table ebay_item_counts;
drop table ebay_user_info;
drop table ebay_users;
