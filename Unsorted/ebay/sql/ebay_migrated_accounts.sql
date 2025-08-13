/*	$Id: ebay_migrated_accounts.sql,v 1.2 1999/02/21 02:53:58 josh Exp $	*/
/*
 * ebay_account_balances.sql
 */

	drop table ebay_migrated_accounts;

	create table ebay_migrated_accounts
	(
		id							int 
			constraint		migrated_accounts_id_nn
			not null,
		last_modified			date 
			constraint		migrated_accounts_last_nn
			not null,
		constraint     	migrated_accounts_pk
 			primary key (id)
 			using index tablespace accounti01
 			storage (initial 100K next 50k)
	)
	tablespace accountd01
	storage (initial 1M next 500K);

	commit;
