/*	$Id: test_users_table.sql,v 1.3 1999/02/21 02:55:09 josh Exp $	*/
truncate table mw_gorp;

insert into mw_gorp (select id from ebay_users where email like '%@ebay.com');

/* statc list of test users not @ebay.com */

insert into mw_gorp (id) values (142697);
insert into mw_gorp (id) values (221514);
insert into mw_gorp (id) values (1481274);
insert into mw_gorp (id) values (1038366);
insert into mw_gorp (id) values (963356);
insert into mw_gorp (id) values (925563);
insert into mw_gorp (id) values (2890);
insert into mw_gorp (id) values (48009);
insert into mw_gorp (id) values (1131649);
insert into mw_gorp (id) values (239414);
insert into mw_gorp (id) values (610331);
insert into mw_gorp (id) values (96021);
insert into mw_gorp (id) values (1503032);
insert into mw_gorp (id) values (221523);
insert into mw_gorp (id) values (1184778);
insert into mw_gorp (id) values (1184582);
insert into mw_gorp (id) values (152399);
insert into mw_gorp (id) values (93984);
insert into mw_gorp (id) values (242739);
insert into mw_gorp (id) values (121013);

