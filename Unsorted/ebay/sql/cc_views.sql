/*	$Id: cc_views.sql,v 1.4 1999/02/21 02:52:44 josh Exp $	*/
drop view ebay_users;
drop view ebay_user_info;
drop view ebay_cobrand_partners;
drop view ebay_cobrand_headers;
drop view ebay_cobrand_headers_text;
drop view ebay_cobrand_email;
drop view ebay_cobrand_email_text;
drop view ebay_user_past_aliases;
drop view ebay_announce;
drop view ebay_admin;
commit;
create database link ccprod connect to scott identified by haw98 using 'ebay';
create view ebay_account_balances as select * from ebay_account_balances@ccprod;
create view ebay_users as select * from ebay_users@ccprod;
create view ebay_user_info as select * from ebay_user_info@ccprod;
create view ebay_cobrand_partners as select * from  ebay_cobrand_partners@ccprod;
create view ebay_cobrand_headers as select * from  ebay_cobrand_headers@ccprod;
create view ebay_cobrand_headers_text as select * from ebay_cobrand_headers_text@ccprod;
create view ebay_user_past_aliases as select * from ebay_user_past_aliases@ccprod;
create view ebay_announce as select * from ebay_announce@ccprod;
create view ebay_admin as select * from ebay_admin@ccprod;
commit;


