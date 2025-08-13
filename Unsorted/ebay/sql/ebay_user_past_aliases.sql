/*	$Id: ebay_user_past_aliases.sql,v 1.2 1999/02/21 02:54:15 josh Exp $	*/
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
 tablespace userd03
 storage (initial 50M next 20M);

 create index ebay_user_alias_idmkt_index
   on ebay_user_past_aliases(marketplace, id)
   tablespace useri03
   storage(initial 20M next 10M);

 create index ebay_user_alias_index
   on ebay_user_past_aliases(alias)
   tablespace useri03
   storage(initial 20M next 10M);

/* run script to move from ebay_renamed_users to ebay_user_past_aliases */
