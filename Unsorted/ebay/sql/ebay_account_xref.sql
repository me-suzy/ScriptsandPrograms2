/*	$Id: ebay_account_xref.sql,v 1.2 1999/02/21 02:52:59 josh Exp $	*/
/*
 * ebay_account_xref.sql
 */

	drop table ebay_account_xref;

	create table ebay_account_xref
	(
		id							int 
			constraint		account_xref_id_nn
			not null,
		awid					   int 
			constraint		account_xref_awid_nn
			not null,
		constraint     	account_xref_pk
 			primary key (id)
 			using index tablespace accounti01
 			storage (initial 5M next 2M),
		constraint			account_xref_fk
			foreign key(id)
			references ebay_users(id)
	)
	tablespace accountd01
	storage (initial 5M next 2M);

	create index ebay_account_xref_awid_index
		on ebay_account_xref(awid)
		tablespace accounti01
		storage(initial 5M next 2M);

