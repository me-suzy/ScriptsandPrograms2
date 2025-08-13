/*	$Id: ebay_agg_partner_browser_list.sql,v 1.2 1999/02/21 02:53:08 josh Exp $	*/
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
	constraint nn_agg_partner_browser_id
		not null
	constraint pos_agg_partner_browser_id
		CHECK(id >= 0),
	entry_type int
	constraint nn_agg_p_browser_type
		not null
	constraint pos_agg_p_browser_type
		CHECK (entry_type >= 0),
	partner_name varchar(255)
	constraint nn_agg_partner_browser_name
		not null,
	constraint agg_partner_browser_pk
	primary key (id, entry_type)
		using index tablespace bizdevi01
		storage (initial 1K next 1K)
		)
tablespace bizdevd01
storage (initial 5K next 2K);

insert into ebay_agg_partner_browser_list
	values (0, 0, 'ebay');

insert into ebay_agg_partner_browser_list
	values (1, 0, 'netscape');

insert into ebay_agg_partner_browser_list
	values (2, 0, 'yahoo');

insert into ebay_agg_partner_browser_list
	values (3, 0, 'excite');

insert into ebay_agg_partner_browser_list
	values (4, 0, 'angelfire');

insert into ebay_agg_partner_browser_list
	values (5, 0, 'infospace');

insert into ebay_agg_partner_browser_list
	values (6, 0, 'four11');

insert into ebay_agg_partner_browser_list
	values (7, 0, 'whowhere');

insert into ebay_agg_partner_browser_list
	values (8, 0, 'cnet');

insert into ebay_agg_partner_browser_list
	values (9, 0, 'visa');

insert into ebay_agg_partner_browser_list
	values (10, 0, 'pacbell');

insert into ebay_agg_partner_browser_list
	values (11, 0, 'wbs');

/* Begin AOL sites */
insert into ebay_agg_partner_browser_list
	values (12, 0, 'clmsgen01');

insert into ebay_agg_partner_browser_list
	values (13, 0, 'clmscom01');

insert into ebay_agg_partner_browser_list
	values (14, 0, 'clmsbus01');

insert into ebay_agg_partner_browser_list
	values (15, 0, 'clmstra01');

insert into ebay_agg_partner_browser_list
	values (16, 0, 'clssgen01');

insert into ebay_agg_partner_browser_list
	values (17, 0, 'clsscom01');

insert into ebay_agg_partner_browser_list
	values (18, 0, 'clssbus01');

insert into ebay_agg_partner_browser_list
	values (19, 0, 'clsstra01');

insert into ebay_agg_partner_browser_list
	values (20, 0, 'clfindb01');

insert into ebay_agg_partner_browser_list
	values (21, 0, 'clbs01');

insert into ebay_agg_partner_browser_list
	values (22, 0, 'hcms01');

insert into ebay_agg_partner_browser_list
	values (23, 0, 'hccolds01');

insert into ebay_agg_partner_browser_list
	values (24, 0, 'hccolbeacat01');

insert into ebay_agg_partner_browser_list
	values (25, 0, 'hccolspocat01');

insert into ebay_agg_partner_browser_list
	values (26, 0, 'hccoltoycat01');

insert into ebay_agg_partner_browser_list
	values (27, 0, 'hcantds01');

insert into ebay_agg_partner_browser_list
	values (28, 0, 'sichatrm01');

/* End AOL sites */

insert into ebay_agg_partner_browser_list
	values (29, 0, 'netnoir');

 drop sequence ebay_agg_browser_sequence;
 drop sequence ebay_agg_partner_sequence;

 create sequence ebay_agg_browser_sequence
	minvalue 1;

 create sequence ebay_agg_partner_sequence
	minvalue 30;
