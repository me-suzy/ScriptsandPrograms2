/*	$Id: ebay_gift_occasions.sql,v 1.3 1999/02/21 02:56:22 josh Exp $	*/
/*
 * ebay_gift_occasions.sql
 *
 * ** NOTE **
 * Right now, items numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */
/* added last modified field; do we really need it? */

/*  drop table ebay_gift_occasions;
 */

 create table ebay_gift_occasions
 (
	MARKETPLACE			NUMBER(38)
		constraint		gift_occasions_marketplace_nn
			not null,
	ID					NUMBER(38)
		constraint		gift_occasions_id_nn
			not null,
	NAME				VARCHAR2(254)
		constraint		gift_occasions_name_nn
			NOT NULL,
	GREETING			VARCHAR2(254)
		constraint		gift_occasions_greeting_nn
			not null,
	FLAGS				NUMBER(38)
		constraint		gift_occasions_flags_nn
			not null,
	HEADER				VARCHAR(255),
	FOOTER				VARCHAR(255),
	constraint			gift_occasions_pk
		primary key		(marketplace, id)
		using index tablespace	tmisci01
		storage (initial 500K next 500K)
)
tablespace tmiscd01
storage(initial 1M next 1M);


 drop sequence ebay_gift_occasions_sequence;

 create sequence ebay_gift_occasions_sequence;

