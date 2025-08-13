/*	$Id: ebay_renamed_users.sql,v 1.2 1999/02/21 02:57:49 josh Exp $	*/
create table ebay_renamed_users(
fromuserid varchar2(64),
touserid   varchar2(64))
tablespace USERSD02
storage(initial 2M next 2M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_renamed_users_n1 on ebay_renamed_users(fromuserid)
tablespace USERSI02
storage(initial 2M next 2M minextents 1 maxextents 99 pctincrease 0)
/
