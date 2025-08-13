/*	$Id: custrank.sql,v 1.2 1999/02/21 02:57:39 josh Exp $	*/
create table custrank(
 USERID 				  NUMBER,
 RANK					  NUMBER)
tablespace CUSTOMERD01
storage(initial 20M next 1M minextents 1 maxextents 99 pctincrease 0)
/
