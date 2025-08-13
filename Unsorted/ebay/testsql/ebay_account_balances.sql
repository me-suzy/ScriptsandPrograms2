/*	$Id: ebay_account_balances.sql,v 1.2 1999/02/21 02:55:44 josh Exp $	*/
/*
 * ebay_account_balances.sql
 */

/* 	 drop table ebay_account_balances; */
 

	create table ebay_account_balances
	(
		id							int 
			constraint		account_balances_id_nn
			not null,
		last_modified			date 
			constraint		account_balance_last_nn
			not null,
		balance					float,
		constraint		account_balances_pk
			primary key (id)
			using index tablespace taccounti01
			storage (initial 5M next 2m),
		constraint		account_balances_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace taccountd01
	storage (initial 10M next 2M);

	/* Add Columns to maintain Customer Credit Card Information */
	alter table ebay_account_balances
	add (
			cc_last4digits number(4),
			cc_expiration_date date,
			cc_data_last_modified date
		);


	commit;
