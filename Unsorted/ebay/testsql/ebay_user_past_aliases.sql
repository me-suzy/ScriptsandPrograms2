/*	$Id: ebay_user_past_aliases.sql,v 1.2 1999/02/21 02:56:55 josh Exp $	*/
/*
 * ebay_user_past_aliases.sql
 *
 * contains user's past userid and email aliases with change dates
 * aliasflag: 0 indicates userid alias change; 1 indicates email alias change
 *
 */

/* drop table ebay_user_past_aliases;
 */

 create table ebay_user_past_aliases
 (
	marketplace		int
		constraint	user_alias_marketplace_fk
		references	ebay_marketplaces(id),
	id			int 
		constraint	user_alias_id_nn
		not null,
	alias			varchar(64) 
		constraint user_alias_alias_nn
		not null,
	aliasflag		char(1)
		constraint	user_alias_flag_nn
		not null,
	modified		date
		constraint	user_alias_modified_nn
		not null,
	host			varchar(64)
		constraint	user_alias_hostname_nn
		not null
 )
 tablespace tuserd01
 storage (initial 1M next 100K);

 create index ebay_user_alias_idmkt_index
   on ebay_user_past_aliases(marketplace, id)
   tablespace tuseri01
   storage(initial 100K next 100K);

 create index ebay_user_alias_index
   on ebay_user_past_aliases(alias)
   tablespace tuseri01
   storage(initial 100K next 100K);

/* script to move from ebay_renamed_users to ebay_user_past_aliases */
