/*	$Id: ebay_categories.sql,v 1.2 1999/02/21 02:57:42 josh Exp $	*/
create table ebay_categories(
 MARKETPLACE        NUMBER(38)
 ID                 NUMBER(10)
 NAME               VARCHAR2(20)
 DESCRIPTION        VARCHAR2(255)
 ADULT              CHAR(1)
 ISLEAF             CHAR(1)
 ISEXPIRED          CHAR(1)
 LEVEL1             NUMBER(10)
 LEVEL2             NUMBER(10)
 LEVEL3             NUMBER(10)
 LEVEL4             NUMBER(10)
 NAME1              VARCHAR2(20)
 NAME2              VARCHAR2(20)
 NAME3              VARCHAR2(20)
 NAME4              VARCHAR2(20)
 PREVCATEGORY       NUMBER(10)
 NEXTCATEGORY       NUMBER(10)
 FEATUREDCOST       NUMBER(10,2)
 CREATED            DATE
 FILEREFERENCE      VARCHAR2(255)
 LAST_MODIFIED      DATE
 ORDER_NO           NUMBER(10))
tablespace CATEGORYD01
storage(initial 1M next 1M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_categories_n1(id)
tablespace CATEGORYI01
storage(initial 1M next 1M minextents 1 maxextents 99 pctincrease 0)
/

