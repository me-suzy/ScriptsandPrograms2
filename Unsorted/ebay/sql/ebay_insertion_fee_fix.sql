/*	$Id: ebay_insertion_fee_fix.sql,v 1.4 1999/02/21 02:53:42 josh Exp $	*/
/*
 * 1. Create a table to hold the problem relist records
 * 2. find the problem records in ebay_accounts
 * 3. find corresponding item and figure out the correct listing fee
 *    into same table? Via update?
 * 4. To correct, we have to update records in ebay_accounts with the
 *    records in ebay_insertion_fee_fix
 * 5. 
 */
	drop table ebay_insertion_fee_fix;
	create table ebay_insertion_fee_fix
	(
		id			int
			constraint	insfix_id_nn
			not null,
		when			date
			constraint	insfix_when_nn
			not null,
		action			number(3,0)	
			constraint	insfix_action_nn
			not null,
		amount			number(10,2)
			constraint	insfix_amount_nn
			not null,
		transaction_id		number(38)
			constraint	insfix_xaction_nn
			not null,
		memo			varchar(255),
		migration_batch_id	number(3,0),
		item_id			number(38),
		newamount		number(10,2)
			default 0
	)
	tablespace dynmiscd
	storage (initial 1M next 1M);

/*
 * First, we find out which account records are affected (include item trans#)
 * within date range (mid august - now) insert into table
 * 
 */
 insert into ebay_insertion_fee_fix
 (	id,
	when,
	action,
	amount,
	transaction_id,
	memo,
	migration_batch_id
 )
select id, when, action, amount, transaction_id, memo,
migration_batch_id
from ebay_accounts 
where when > TO_DATE('1998-08-28 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
and action = 1 
and amount >= 0
/
commit;
/* 246502 rows created - starting from Sept 2*/
/* of those, 1840 were credits total of $1047.25 - sept 22 */
/* $0 relist fee: 246502 - 1840 = 244662 records */

/* join the fix table with ebay_transaction_xref_item to get item_id
 * how to do this efficiently?
 */

update ebay_insertion_fee_fix a
set item_id = 
(select b.item_id from ebay_transaction_xref_item b 
where b.id = a.transaction_id);

 create index ebay_ins_id_index
   on ebay_insertion_fee_fix(id)
   tablespace dynmisci
   storage(initial 5m next 2m);
