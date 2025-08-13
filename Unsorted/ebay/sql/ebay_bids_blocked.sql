/*	$Id: ebay_bids_blocked.sql,v 1.2 1999/05/19 02:35:06 josh Exp $	*/
/*
 * ebay_bids_blocked.sql
 *
 * ebay_bids_blocked contains detailed information
 * about the bids on a blocked item.
 *
 */


create table ebay_bids_blocked tablespace ebaydata01 pctfree 10 storage
(initial 100m next 100m pctincrease 0) as select * from ebay_bids
where 1=2;

create index ebay_bids_blocked_iu_index
	on ebay_bids_blocked(item_id, user_id)
   tablespace ebayindx01
	storage(initial 20M next 10M pctincrease 0) ;

 create index ebay_bids_blocked_user_index
   on ebay_bids_blocked(user_id)
   tablespace ebayindx01
   storage(initial 20M next 10M pctincrease 0);
