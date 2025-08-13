/*	$Id: ebay_historical_data.sql,v 1.2 1999/02/21 02:57:45 josh Exp $	*/
create table ebay_historical_data(
 USER_ID           NUMBER(38)
 CATEGORY_ID       NUMBER(38)
 PERIOD_START      DATE
 ITEMS_BOUGHT      NUMBER(4)
 DOLLARS_BOUGHT    NUMBER(9,2)
 ITEMS_SOLD        NUMBER(4)
 DOLLARS_SOLD      NUMBER(9,2)
 ITEMS_UNSOLD      NUMBER(4)
 DOLLARS_UNSOLD    NUMBER(9,2))
tablespace HISTORYD01
storage(initial 500M next 100M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_historical_data_n1 on ebay_historical_data(user_id)
tablespace HISTORYI01
storage(initial 100M next 50M minextents 1 maxextents 99 pctincrease 0)
/
