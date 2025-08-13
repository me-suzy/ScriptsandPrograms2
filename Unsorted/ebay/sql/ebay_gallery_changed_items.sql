
drop sequence ebay_gallery_sequence;
create sequence ebay_gallery_sequence;
---- ebay_gallery_read_sequence--

drop sequence ebay_gallery_read_sequence;
create sequence ebay_gallery_read_sequence;

---- EBAY_GALLERY_CHANGED_ITEMS--
DROP TABLE EBAY_GALLERY_CHANGED_ITEMS CASCADE CONSTRAINTS ; 

CREATE TABLE EBAY_GALLERY_CHANGED_ITEMS 
(   ID            NUMBER(38)    NOT NULL,
    SEQUENCE_ID   NUMBER(38)    NOT NULL,    
	URL           VARCHAR2(255)  NOT NULL,    
	STATE         NUMBER(4)     NOT NULL,    
	START_DATE    DATE          NOT NULL,    
	END_DATE      DATE          NOT NULL,    
	ATTEMPTS      NUMBER(4)     NOT NULL,    
	LAST_ATTEMPT  DATE          NOT NULL)  
	TABLESPACE RITEMD04 
	STORAGE(INITIAL 500M NEXT 100M PCTINCREASE 0 ); 

CREATE INDEX EBAY_GALLERY_CHANGED_ITEMS_IDX  ON   
EBAY_GALLERY_CHANGED_ITEMS(SEQUENCE_ID)    
TABLESPACE RITEMI04  
STORAGE(INITIAL 100M NEXT 100M PCTINCREASE 0 ) ; 

