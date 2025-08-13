/*	$Id: MergeXAccounts.sql,v 1.2 1999/02/21 02:52:06 josh Exp $	*/
/*
 *
 * Merge ebay_xaccounts into ebay_accounts
 *
 * This script needs to insert all the "missing" records from
 * ebay accounts from ebay_xaccounts into ebay_accounts, but
 * cause a "zero" affect on customer balances.
 *
 * To do this, we need to create a "companion" record for each
 * xaccount row which negates it's affect. Thus, if the record
 * is a $10.00 charge, we need a $10.00 credit.
 *
 * This script creates a "companion" row table, creates the
 * records, and then inserts them all into ebay_accounts.
*/

/*
 * Step 1: Create the companion table. Just like ebay_accounts
*/

create table ebay_xaccounts_companion
(
		id							int
			constraint	xaccounts_comp_id_nn
			not null,
		when						date
			constraint	xaccounts_comp_when_nn
			not null,
		action					number(3,0)
			constraint	xaccounts_comp_action_nn
			not null,
		amount					number(10,2)
			constraint	xaccounts_comp_amount_nn
			not null,
		transaction_id			number(38)
			constraint	xaccounts_comp_xaction_nn
			not null,
		memo						varchar(255),
		migration_batch_id	number(3,0),
		constraint		xaccounts_comp_fk
			foreign key (id)
			references	ebay_users(id)
	)
tablespace accountd03
storage (initial 10M next 5M);

/*
 * Step 2: Create the companion rows for DEBITS in 
 * ebay_xaccounts. The companion rows are CREDITS
 * for the same amount.  
*/

/* commented out due to possible syntax error
(	id,
	when,
	action,
	amount,
	transaction_id,
	memo,
	migration_batch_id
)
values
*/

insert into ebay_xaccounts_companion
select	id,
		when,
		41,
		-amount,
		ebay_transaction_sequence.nextval,
		'Accounting Adjustment',
		600 
from	ebay_xaccounts 
where	amount < 0;

commit;			

/*
 * Step 3: Create the companion rows for CREDITS in 
 * ebay_xaccounts. The companion rows are DEBITS
 * for the same amount.  
*/

/* commented out due to possible syntax error
(	id,
	when,
	action,
	amount,
	transaction_id,
	memo,
	migration_batch_id
)
values
*/

insert into ebay_xaccounts_companion
select	id,
		when,
		40,
		-amount,
		ebay_transaction_sequence.nextval,
		'Accounting Adjustment',
		601 
from	ebay_xaccounts 
where	amount > 0;
			
commit;			

/*
 * Step 4. Insert the ebay_xaccounts into the accounts table. 
 * 66,089 rows
*/
/* commented out due to possible syntax error
(	id,
	when,
	action,
	amount,
	transaction_id,
	memo,
	migration_batch_id
)
values
*/

insert into ebay_accounts
select	id,
		when,
		action,
		amount,
		transaction_id,
		memo,
		migration_batch_id 
from	ebay_xaccounts;

commit;			

/*
 * Step 5. Insert the companion records (65,027 rows)
*/
/* commented out due to possible syntax error
(	id,
	when,
	action,
	amount,
	transaction_id,
	memo,
	migration_batch_id
)
values

*/

insert into ebay_accounts
select 	id,
		when,
		action,
		amount,
		transaction_id,
		memo,
		migration_batch_id 
from	ebay_xaccounts_companion;

commit;			

/*
 * Step 6. Insert the EBAY_XTRANSACTION_XREF_AW_ITEM
 * rows into the ebay_tranaction_xref_aw_item table. 
 * 62,436 rows
*/
/* commented out due to possible syntax error
(	id,
	aw_item
)
values
*/
insert into ebay_transaction_xref_aw_item
select	id,
		aw_item
from	ebay_xtransaction_xref_aw_item;

commit;			

/* 
 * Step 7 - Done!
 * clean up duplicated tables later...
*/

