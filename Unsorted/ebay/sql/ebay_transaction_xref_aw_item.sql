/*	$Id: ebay_transaction_xref_aw_item.sql,v 1.2 1999/02/21 02:54:09 josh Exp $	*/
/*
 * ebay_account_xref.sql
 */

	drop table ebay_transaction_xref_aw_item;

	create table ebay_transaction_xref_aw_item
	(
		id							number(38) 
			constraint		xaction_xref_aw_id_nn
			not null,
		aw_item					varchar(12)
			constraint		xaction_xref_aw_item_nn
			not null,
		constraint     	xaction_xref_aw_item_pk
 			primary key (id)
 			using index tablespace accounti01
 			storage (initial 5M next 2M)
	)
	tablespace accountd01
	storage (initial 10M next 2M);

