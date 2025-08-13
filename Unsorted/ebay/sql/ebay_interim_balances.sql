/*	$Id: ebay_interim_balances.sql,v 1.5 1999/02/21 02:53:43 josh Exp $	*/
drop table ebay_interim_balances;

	create table ebay_interim_balances
	(
		id							number(38) 
			constraint		interimt_balances_id_nn
			not null,
		when			date 
			constraint		interim_balance_when_nn
			not null,
		balance					number(10,2),
		constraint		interim_balances_pk
			primary key (id, when)
			using index tablespace accounti04 
			storage (initial 50M next 50M),
		constraint		interim_balances_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace accountd04 
	storage (initial 100M next 50M);

	commit;
