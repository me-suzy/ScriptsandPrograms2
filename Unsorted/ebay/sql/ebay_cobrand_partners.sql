/*	$Id: ebay_cobrand_partners.sql,v 1.2 1999/02/21 02:53:29 josh Exp $	*/
/*
 * ebay_cobrand_partners
 * One of Five Easy Pieces for cobranding.
 */

drop table ebay_cobrand_partners;
drop sequence ebay_cobrand_partners_sequence;

create table ebay_cobrand_partners
(
	id				number(3)
		constraint		cobrand_partners_id_pos
		check (id >= 0),
	partner_name	varchar(255)
		constraint		cobrand_partner_name_nn
		not null,
	partner_desc	varchar(255)
		constraint		cobrand_partner_desc_nn
		not null,
	constraint		cobrand_partners_pk
		primary key (id)
			using index tablespace	parti01
			storage (initial 1M next 1M)
)
tablespace partd01
storage (initial 5M next 1M);

create sequence ebay_cobrand_partners_sequence
	start with 1
	increment by 1;

