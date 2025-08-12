
INSERT INTO ###TABLE_PREFIX###gacl_aro VALUES("13", "Person", "4", "3", "guest", "0");

INSERT INTO ###TABLE_PREFIX###gacl_aro_groups VALUES("16", "10", "12", "13", "public", "7");

UPDATE ###TABLE_PREFIX###gacl_aro_groups_id_seq SET id=16;

UPDATE ###TABLE_PREFIX###gacl_aro_seq SET id=13;

INSERT INTO ###TABLE_PREFIX###gacl_groups_aro_map VALUES("16", "13");


INSERT INTO ###TABLE_PREFIX###user_details (
	user_id) 
VALUES (4);								
    
INSERT INTO ###TABLE_PREFIX###users (
	id, login,  password, 
	grp, salutation, firstname, 
	lastname, email)
VALUES    
   (4,  'guest','d41d8cd98f00b204e9800998ecf8427e',
    15,'','',
    'Guest','nomail@nomail.de');
