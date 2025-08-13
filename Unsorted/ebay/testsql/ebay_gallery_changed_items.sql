 create table ebay_gallery_changed_item_1
 (
 ID     NUMBER(38)
  constraint  gallery_change_id_nn
   not null,
 SEQUENCE   NUMBER(38)
  constraint  gallery_change_sequence_nn
   not null,
 URL     VARCHAR2(255)
  constraint  gallery_change_url_nn
   not null,
 STATE    NUMBER(4,0)
  constraint  gallery_change_state_nn
   not null,
 START_DATE    DATE
  constraint  gallery_change_start_nn
   not null,
 END     DATE
  constraint  gallery_change_end_nn
   not null
 )
 tablespace titemd02
 storage(initial 1M next 100K);

 create index ebay_gallery_item_index_1
   on ebay_gallery_changed_item_1(sequence)
   tablespace titemi02
   storage(initial 100K next 10K);

--
-- ebay_gallery_changed_item_2
--

 create table ebay_gallery_changed_item_2
 (
 ID     NUMBER(38)
  constraint  gallery_change_id2_nn
   not null,
 SEQUENCE   NUMBER(38)
  constraint  gallery_change_sequence2_nn
   not null,
 URL     VARCHAR2(255)
  constraint  gallery_change_url2_nn
   not null,
 STATE    NUMBER(4,0)
  constraint  gallery_change_state2_nn
   not null,
 START_DATE    DATE
  constraint  gallery_change_start2_nn
   not null,
 END     DATE
  constraint  gallery_change_end2_nn
   not null
 )
 tablespace titemd02
 storage(initial 1M next 100K);

 create index ebay_gallery_item_index_2
   on ebay_gallery_changed_item_2(sequence)
   tablespace titemi02
   storage(initial 100K next 10K);

 create table ebay_gallery_failed_item
 (
 ID     NUMBER(38)
  constraint  gallery_failed_id_nn
   not null,
 ATTEMPTS   NUMBER(4,0)
  constraint  gallery_failed_attempts_nn
   not null,
 LAST_ATTEMPT  DATE
  constraint  gallery_failed_last_attempt_nn
   not null,
 STATE    NUMBER(4,0)
  constraint  gallery_failed_state_nn
   not null
 )
 tablespace titemd02
 storage(initial 100K next 10K);

