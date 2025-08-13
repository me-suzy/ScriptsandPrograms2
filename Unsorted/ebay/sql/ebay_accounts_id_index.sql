/*	$Id: ebay_accounts_id_index.sql,v 1.2 1999/02/21 02:53:01 josh Exp $	*/
 create index ebay_accounts_id_index
   on ebay_accounts(id)
   tablespace accounti02
   storage(initial 10m next 2m);
