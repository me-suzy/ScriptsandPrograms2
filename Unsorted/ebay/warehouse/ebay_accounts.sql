/*	$Id: ebay_accounts.sql,v 1.2 1999/02/21 02:57:40 josh Exp $	*/
create table ebay_accounts(
 ID                   NUMBER(38),
 TDATE                DATE,
 ACTION               NUMBER(3),
 AMOUNT               NUMBER(10,2),
 TRANSACTION_ID       NUMBER(38),
 MEMO                 VARCHAR2(255),
 MIGRATION_BATCH_ID   NUMBER(3))
tablespace ACCOUNTSD01
storage(initial 500M next 100M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_accounts_id_index on ebay_accounts(id)
tablespace ACCOUNTSI01
storage(initial 100M next 100M minextents 1 maxextents 99 pctincrease 0)
/
