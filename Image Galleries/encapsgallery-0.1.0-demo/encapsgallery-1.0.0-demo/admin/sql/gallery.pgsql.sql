DROP TABLE encapsgallery_test_table;
CREATE TABLE encapsgallery_test_table (test_field varchar(255)) ;
INSERT INTO encapsgallery_test_table (test_field) VALUES('test_value');

DROP TABLE  encapsgallery_category;
CREATE TABLE encapsgallery_category (
  id int NOT NULL default nextval('cat_id_seq'),
  title text,
  pos int default NULL,
  vis_anons VARCHAR(80),
   PRIMARY KEY  (id)
) ;

drop sequence cat_id_seq;
create sequence cat_id_seq;
select setval('cat_id_seq', (select max(id) from encapsgallery_category));

INSERT INTO encapsgallery_category (title,pos,vis_anons) VALUES ('Default',1,'checked');

DROP TABLE encapsgallery;
CREATE TABLE encapsgallery (
  id int NOT NULL default nextval('gallery_id_seq'),
  filename_normal varchar(255) default NULL,
  position int default NULL,
  visible varchar(255) default NULL,
  title varchar(255) default NULL,
  comment varchar(255) default NULL,
  cat int,
  PRIMARY KEY  (id)
);

drop sequence gallery_id_seq;
create sequence gallery_id_seq;
select setval('gallery_id_seq', (select max(id) from encapsgallery));
