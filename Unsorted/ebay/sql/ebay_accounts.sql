/*	$Id: ebay_accounts.sql,v 1.2 1999/02/21 02:53:00 josh Exp $	*/
/*
 * ebay_accounts.sql
 *
 *	This table is actually the account detail 
 * information for a user. The transaction id 
 * (see ebay_transaction_sequence) is actually
 * a value which can be used as a foriegn key for
 * other tables.
 *
 */

	drop table ebay_accounts;

	create table ebay_accounts
	(
		id							int
			constraint	accounts_id_nn
			not null,
		when						date
			constraint	accounts_when_nn
			not null,
		action					number(3,0)
			constraint	accounts_action_nn
			not null,
		amount					number(10,2)
			constraint	accounts_amount_nn
			not null,
		transaction_id			number(38)
			constraint	accounts_xaction_nn
			not null,
		memo						varchar(255),
		migration_batch_id	number(3,0),
		constraint		accounts_fk
			foreign key (id)
			references	ebay_users(id)
	)
tablespace accountd01
storage (initial 500M next 20M);

	commit;

	drop sequence ebay_transaction_sequence;

	create sequence ebay_transaction_sequence
		start with 1
		increment by 1
		nomaxvalue;

