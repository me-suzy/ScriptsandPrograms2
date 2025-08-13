/*	$Id: ebay_finance.sql,v 1.2 1999/02/21 02:56:20 josh Exp $	*/
/*
 * ebay_finance.sql
 *
 *	This table contains daily financial information
 *
 */

	drop table ebay_finance;

	create table ebay_finance
	(
		when			date
			constraint	finance_when_nn
			not null,
		action			number(3)
			constraint	finance_action_fk
			not null,
		count			number(38)
			constraint	finance_count_nn
			not null,
		amount			number(12,2)
			constraint	finance_amount_nn
			not null,
		constraint		finance_pk
		primary key		(when, action)
		using index tablespace	tstatsi01
		storage (initial 10K next 5K)
	)
tablespace tstatsd01
storage (initial 20K next 5K);

