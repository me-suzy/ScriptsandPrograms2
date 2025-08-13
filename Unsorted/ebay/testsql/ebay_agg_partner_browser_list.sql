/*	$Id: ebay_agg_partner_browser_list.sql,v 1.2 1999/02/21 02:55:52 josh Exp $	*/
/*
 * ebay_agg_partner_browser_list
 *
 *	This table contains a lookup list
 *  of partner and browser ids.
 *  entry_type 0 is partner
 *  entry_type 1 is browser
 */
 
 drop table ebay_agg_partner_browser_list;

create table ebay_agg_partner_browser_list
(
	id int
	constraint nn_pos_agg_partner_browser_id
		not null
		CHECK(id >= 0),
	entry_type int
	constraint nn_pos_agg_p_browser_type
		not null
		CHECK (entry_type >= 0),
	partner_name varchar(255)
	constraint nn_agg_partner_browser_name
		not null,
	constraint agg_partner_browser_pk
	primary key (id, entry_type)
		using index tablespace tbizdevi01
		storage (initial 1K next 1K)
		)
tablespace tbizdevd01
storage (initial 10K next 10K);

 drop sequence ebay_agg_browser_sequence;
 drop sequence ebay_agg_partner_sequence;

 create sequence ebay_agg_browser_sequence
	minvalue 0;

 create sequence ebay_agg_partner_sequence
	minvalue 0;
