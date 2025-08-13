/*	$Id: ebay_companies.sql,v 1.2 1999/02/21 02:56:13 josh Exp $	*/
/*
 * ebay_companies.sql
 *
 *	This table contains information about the company information
 * who bought ad spaces from ebay.
 *
 */

 	drop table ebay_companies;

	create table ebay_companies
	(
		id				int
			constraint	company_id_nn
			not null,
		name			varchar(64),
			constraint	company_name_nn
			not null,
		address			varchar(255),
		phone			varchar(32),
		fax				varchar(32),
		email			varchar(64),
		contact			varchar(64),

		constraint		company_pk
			primary key		(id)
			using index tablespace	adi01
			storage (initial 2K next 2K)
	)
	tablespace tadd01
	storage (initial 10K next 10K);

