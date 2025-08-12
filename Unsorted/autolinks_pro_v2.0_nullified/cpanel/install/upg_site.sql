ALTER TABLE al_site CHANGE name name varchar(32);

ALTER TABLE al_site ADD alurl varchar(100) NOT NULL default '';
ALTER TABLE al_site ADD categories set('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30') NOT NULL default '1';
ALTER TABLE al_site ADD added date NOT NULL default '0000-00-00';
ALTER TABLE al_site ADD nextupdate datetime NOT NULL default '0000-00-00 00:00:00';
ALTER TABLE al_site ADD updinterval mediumint(9) NOT NULL default '15';