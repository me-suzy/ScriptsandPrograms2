/*	$Id: ebay_account_balances.sql,v 1.2 1999/02/21 02:52:58 josh Exp $	*/
/*
 * ebay_account_balances.sql
 */

	drop table ebay_account_balances;

	create table ebay_account_balances
	(
		id							number(38) 
			constraint		account_balances_id_nn
			not null,
		last_modified			date 
			constraint		account_balance_last_nn
			not null,
		balance					number(10,2),
		pastduebase				date,
		pastdue30days			number(10,2),
		pastdue60days			number(10,2),
		pastdue90days			number(10,2),
		pastdue120days			number(10,2),
		pastdueover120days	number(10,2),
		constraint		account_balances_pk
			primary key (id)
			using index tablespace accounti01
			storage (initial 5M next 1M),
		constraint		account_balances_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace accountd01
	storage (initial 30M next 5M);

	commit;

