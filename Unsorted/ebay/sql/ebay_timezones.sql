/*	$Id	*/
/*
 * ebay_timezones.sql
 * is the place where we keep info about a timezone
*/

drop table ebay_timezones;

create table ebay_timezones
 (
	TIMEZONE_ID				NUMBER(5)
		constraint		timezone_id_nn
			not null,
	NAME_STANDARD			VARCHAR2(10)
		constraint		name_standard_nn
			not null,
	NAME_SUMMER				VARCHAR2(10),
	TIMEZONE_OFFSET			NUMBER(5)
		constraint		timezone_offset_nn
			not null
)

alter table ebay_timezones
	add constraint			timezones_pk
		primary key		(timezone_id);


/*
INSERT INTO EBAY_TIMEZONES (TIMEZONE_ID, NAME_STANDARD, NAME_SUMMER, TIMEZONE_OFFSET) 
VALUES (0, 'PST', 'PDT', -28800);
INSERT INTO EBAY_TIMEZONES (TIMEZONE_ID, NAME_STANDARD, NAME_SUMMER, TIMEZONE_OFFSET) 
VALUES (1, 'GMT', 'BST', 0);
INSERT INTO EBAY_TIMEZONES (TIMEZONE_ID, NAME_STANDARD, NAME_SUMMER, TIMEZONE_OFFSET) 
VALUES (2, 'JST', null, 32400);
INSERT INTO EBAY_TIMEZONES (TIMEZONE_ID, NAME_STANDARD, NAME_SUMMER, TIMEZONE_OFFSET) 
VALUES (3, 'EST', 'EDT', -18000);
INSERT INTO EBAY_TIMEZONES (TIMEZONE_ID, NAME_STANDARD, NAME_SUMMER, TIMEZONE_OFFSET) 
VALUES (4, 'AEST', 'AEDT', 36000);
INSERT INTO EBAY_TIMEZONES (TIMEZONE_ID, NAME_STANDARD, NAME_SUMMER, TIMEZONE_OFFSET) 
VALUES (5, 'CET', 'CEST', 3600);
*/