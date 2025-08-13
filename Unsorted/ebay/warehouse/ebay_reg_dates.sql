/*	$Id: ebay_reg_dates.sql,v 1.2 1999/02/21 02:57:48 josh Exp $	*/
create table ebay_reg_dates(
 REGDATE1				  NUMBER,
 CUSTNAME				  VARCHAR2(100),
 REGDATE				  DATE,
 USERID 				  NUMBER)
tablespace REGDATESD01
storage(initial 10M next 1M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_reg_dates_n1 on ebay_reg_dates(custname)
tablespace REGDATESI01
storage(initial 10M next 1M minextents 1 maxextents 99 pctincrease 0)
/
