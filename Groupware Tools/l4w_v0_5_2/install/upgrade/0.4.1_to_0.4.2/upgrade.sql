alter table metainfo TYPE=InnoDB;
alter table users TYPE=InnoDB;
alter table contacts TYPE=InnoDB;
alter table gacl_aro_groups TYPE=InnoDB;


alter table metainfo ADD INDEX (owner);
alter table metainfo ADD INDEX (grp);

alter table metainfo ADD FOREIGN KEY (owner) REFERENCES users(id);


