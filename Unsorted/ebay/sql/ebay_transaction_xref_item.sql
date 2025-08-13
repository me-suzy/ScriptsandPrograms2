/*	$Id: ebay_transaction_xref_item.sql,v 1.2 1999/02/21 02:54:10 josh Exp $	*/
/*
 * ebay_account_xref.sql
 */

	drop table ebay_transaction_xref_item;

	create table ebay_transaction_xref_item
	(
		id							number(38) 
			constraint		xaction_xref_item_id_nn
			not null,
		item_id					number(38)	
			constraint		xaction_xref_item_nn
			not null,
		constraint     	xaction_xref_item_pk
 			primary key (id)
 			using index tablespace accounti01
 			storage (initial 5M next 2M)
	)
	tablespace accountd01
	storage (initial 10M next 2M);

