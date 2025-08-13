/*	$Id: ebay_accounts.sql,v 1.2 1999/02/21 02:55:45 josh Exp $	*/
/*
 * ebay_accounts.sql
 *
 *	This table is actually the account detail 
 * information for a user. The transaction id 
 * (see ebay_transaction_sequence) is actually
 * a value which can be used as a foriegn key for
 * other tables.
 *
 */

/* 	 drop table ebay_accounts; */
 

	create table ebay_accounts
	(
		id							int
			constraint	accounts_id_nn
			not null,
		when						date
			constraint	accounts_when_nn
			not null,
		action					number(3,0)	
			constraint	accounts_action_nn
			not null,
		amount					number(10,2)
			constraint	accounts_amount_nn
			not null,
		transaction_id			number(38)
			constraint	accounts_xaction_nn
			not null,
		memo						varchar(255),
		migration_batch_id	number(3,0),
		constraint		accounts_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace taccountd01
	storage (initial 1M next 1M);

	alter table ebay_accounts
		modify	(	id
						constraint	accounts_id_nn
						not null);

	/* drop sequence ebay_transaction_sequence; */

	create sequence ebay_transaction_sequence
		start with 1
		increment by 1
		nomaxvalue;

/* for temporary use */
	create table ebay_account_temp
	(
		id							int
			constraint	account_tmp_id_nn
			not null,
		when						date
			constraint	account_tmp_when_nn
			not null,
		action					number(3,0)	
			constraint	account_tmp_action_nn
			not null,
		amount					number(10,2)
			constraint	account_tmp_amount_nn
			not null,
		transaction_id			number(38)
			constraint	account_tmp_xaction_nn
			not null,
		memo						varchar(255),
		migration_batch_id	number(3,0),
		constraint		account_tmp_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace dynmiscd
	storage (initial 10M next 1M);

insert into ebay_account_temp
  (select * from ebay_accounts
     where
       action = 5 and when >= 
	   TO_DATE('1998-02-04 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
/* in test
	   TO_DATE('1997-11-04 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));
	   */

/* check the table ebay_account_temp */

commit;

delete from ebay_accounts
where action = 5 and when >=
	   TO_DATE('1998-02-04 00:00:00', 'YYYY-MM-DD HH24:MI:SS');
/* in prod
		TO_DATE('1997-11-04 00:00:00', 'YYYY-MM-DD HH24:MI:SS');	   
	   */

commit;
/* should be same # rows */

/* nullify bill_time for items in the transactions */

/* find all items with the transaction */
create table ebay_items_bad
( id			number(38)
  constraint item_bad_id_nn
      not null)
tablespace titemd01
storage (initial 1K next 1K);
/* or truncate ebay_items_bad */

insert into ebay_items_bad 
select item_id from ebay_transaction_xref_item, ebay_account_temp
  where ebay_account_temp.transaction_id = ebay_transaction_xref_item.id;

/* may contain duplicates */
  
/* set bill_time to null */
update ebay_item_info set bill_time = NULL where id in
(select id from ebay_items_bad);

