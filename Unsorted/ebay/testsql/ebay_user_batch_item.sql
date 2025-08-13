/*	$Id: ebay_user_batch_item.sql,v 1.4 1999/02/21 02:56:53 josh Exp $	*/
DROP TABLE EBAY_USER_BATCH_ITEM CASCADE CONSTRAINTS ; 

CREATE TABLE EBAY_USER_BATCH_ITEM ( 
  MARKETPLACE  NUMBER        NOT NULL , 
  BATCHID      NUMBER(38)    NOT NULL , 
  ID           NUMBER(38)    NOT NULL , 
  BUID         NUMBER(38)    NOT NULL , 
  CREATED      DATE          NOT NULL , 
  SALE_START   DATE          NOT NULL 
, 
 CONSTRAINT BATCH_ITEM_PK  
 PRIMARY KEY ( MARKETPLACE, ID, BATCHID, BUID ) 
  ) ; 

alter table ebay_user_batch_item add (host varchar(64));
