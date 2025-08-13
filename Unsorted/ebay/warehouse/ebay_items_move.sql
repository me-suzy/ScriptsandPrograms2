/*	$Id: ebay_items_move.sql,v 1.2 1999/02/21 02:57:47 josh Exp $	*/
create table ebay_items_move(
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
insert into ebay_items_move(
       ID,
       SALE_TYPE,
       TITLE,
       LOCATION,
       SELLER,
       OWNER,
       PASSWORD,
       CATEGORY,
       QUANTITY,
       BIDCOUNT,
       CREATED,
       SALE_START,
       SALE_END,
       SALE_STATUS,
       CURRENT_PRICE,
       START_PRICE,
       RESERVE_PRICE,
       HIGH_BIDDER,
       FEATURED,
       SUPER_FEATURED,
       BOLD_TITLE,
       PRIVATE_SALE,
       REGISTERED_ONLY,
       VISITCOUNT,
       LAST_MODIFIED)
select ID,
       SALE_TYPE,
       TITLE,
       LOCATION,
       SELLER,
       OWNER,
       PASSWORD,
       CATEGORY,
       QUANTITY,
       BIDCOUNT,
       CREATED,
       SALE_START,
       SALE_END,
       SALE_STATUS,
       CURRENT_PRICE,
       START_PRICE,
       RESERVE_PRICE,
       HIGH_BIDDER,
       FEATURED,
       SUPER_FEATURED,
       BOLD_TITLE,
       PRIVATE_SALE,
       REGISTERED_ONLY,
       VISITCOUNT,
       LAST_MODIFIED
from ebay_items
where trunc(sale_end) = trunc(sysdate - 1)
/
