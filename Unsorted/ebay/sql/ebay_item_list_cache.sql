DROP TABLE EBAY_ITEM_LIST_CACHE CASCADE CONSTRAINTS ;

CREATE TABLE EBAY_ITEM_LIST_CACHE
(
  ACTIVE          NUMBER(2)     NOT NULL,
  KIND            NUMBER(3)     NOT NULL,
  CATEGORY        NUMBER(38)    NOT NULL,
  SCOPE           VARCHAR2(2)   NOT NULL,
  COUNTRY         NUMBER(5)     NOT NULL,
  ITEM_COUNT      NUMBER(38)    NOT NULL,
  ITEM_LIST_SIZE  NUMBER(38)    NOT NULL,
  LAST_UPDATE     DATE          NOT NULL,
  ITEM_NUMBERS    LONG RAW      NOT NULL,
  CONSTRAINT ILC_PK
  PRIMARY KEY ( ACTIVE, KIND, CATEGORY, COUNTRY )
		using index tablespace dynmisci
		storage (initial 5M next 1M))
tablespace dynmiscd
storage (initial 10M next 10M pctincrease 0);

