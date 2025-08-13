/*	$Id: ebay_items.sql,v 1.2 1999/02/21 02:57:46 josh Exp $	*/
create table ebay_items(
 id              NUMBER(38)
 sale_type       NUMBER(8)
 title           VARCHAR2(254)
 location        VARCHAR2(254)
 seller          NUMBER(38)
 owner           NUMBER(38)
 password        NUMBER(38)
 category        NUMBER(8)
 quantity        NUMBER(8)
 bidcount        NUMBER(8)
 created         DATE
 sale_start      DATE
 sale_end        DATE
 sale_status     NUMBER(8)
 current_price   NUMBER(10,2)
 start_price     NUMBER(10,2)
 reserve_price   NUMBER(10,2)
 high_bidder     NUMBER(38)
 featured        CHAR(1)
 super_featured  CHAR(1)
 bold_title      CHAR(1)
 private_sale    CHAR(1)
 registered_only CHAR(1)
 visitcount      NUMBER(8)
 last_modified   DATE)
tablespace ITEMSD01
storage(initial 750M next 100M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_items_n1 on ebay_items(id)
tablespace ITEMSI01
storage(initial 50M next 10M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_items_n2 on ebay_items(seller)
tablespace ITEMSI01
storage(initial 50M next 10M minextents 1 maxextents 99 pctincrease 0)
/
