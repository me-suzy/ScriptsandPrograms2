/*	$Id: ebay_marketplaces.sql,v 1.2 1999/02/21 02:56:35 josh Exp $	*/
/*
 * ebay_marketplaces.sql
 */

/*  drop table ebay_marketplaces;
 */

 create table ebay_marketplaces
 (
	ID						NUMBER(38)
		constraint		marketplaces_id_nn
			not null,
	name					varchar(255)
		constraint		marketplace_name_unq
			unique,
	constraint			marketplaces_pk
		primary key(id)
		using index tablespace tuseri01
		storage (initial 10K next 1K)
)
tablespace tuserd01
	storage (initial 1M next 100K);


/*
 * These additional constraints are to force
 * Oracle to name the constraints what we want
 * them to. For example, if we list the constraints
 * on name as "not null, unique", only the FIRST
 * constraint gets the name, and the second one
 * gets and ORACLE generated name
 */
 alter table ebay_marketplaces
	modify (	name
				constraint	marketplaces_name_nn
				not null);



