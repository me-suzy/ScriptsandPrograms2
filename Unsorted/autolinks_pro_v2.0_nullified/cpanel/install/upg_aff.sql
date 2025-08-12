ALTER TABLE al_aff RENAME al_ref;

ALTER TABLE al_ref CHANGE name name varchar(32);

ALTER TABLE al_ref ADD description varchar(255) NOT NULL default '';
ALTER TABLE al_ref ADD thumb varchar(32) NOT NULL default '/';
ALTER TABLE al_ref ADD category tinyint(3) unsigned NOT NULL default '1';
ALTER TABLE al_ref ADD fromsite varchar(16) NOT NULL default '';
ALTER TABLE al_ref ADD code varchar(8) NOT NULL default '';