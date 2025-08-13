/*	$Id: ebay_user_survey.sql,v 1.3 1999/03/22 00:09:46 josh Exp $	*/
/*
 * ebay_user_survey.sql
 *
 * This table helps us keep track of user surveys.
 *
 * The convention for use of the questions is as 
 * follows:
 *
 * Question 1 is "Has this user participated in this survey or not?".
 *
 * Question 2 is "Does thie user want to participate in this survey?".
 *
 */

 drop table ebay_user_survey_responses;
/* obsolete
 create table ebay_user_survey_responses
 (
	marketplace				int
		constraint	responses_marketplace_fk
		references	ebay_marketplaces(id),
	user_id					int 
		constraint	responses_id_nn
		not null,
	survey_id				number(3,0)
		not null,
	question_id				number(2,0)
		not null,
	boolean_response		char,
	number_response			number,
	text_response_length	int,
	text_response			long raw,
	constraint		user_survey_response_pk
		primary key(marketplace, user_id, survey_id, question_id)
		using index	storage(initial 100K next 100K)
						tablespace useri01,
	constraint		user_survey_responses_fk
		foreign key (user_id)
		references	ebay_users(id)
 )
 tablespace userd01
 storage (initial 10M next 2m);

*/

 create table ebay_user_survey_responses
 (
	marketplace				int
		constraint	responses_marketplace_fk
		references	ebay_marketplaces(id),
	user_id					int 
		constraint	responses_id_nn
		not null,
	survey_id				number(3,0)
		not null,
	question_id				number(2,0)
		not null,
	boolean_response		char,
	number_response			number,
	text_response_length	int,
	text_response			long raw
 )
 tablespace ruserd05
 storage (initial 10M next 2m);

alter table ebay_user_survey_responses
	add constraint		user_survey_response_pk
		primary key(marketplace, user_id, survey_id, question_id)
		using index	storage(initial 100K next 100K)
						tablespace ruseri05;
commit;
alter table ebay_user_survey_responses
	add constraint		user_survey_responses_fk
		foreign key (user_id)
		references	ebay_users(id);
commit;


create table ebay_user_survey_record
 (	
	survey_id		number(3,0)
		constraint record_survey_nn
		not null,
	user_id			int
		constraint record_user_nn
		not null,
		constraint user_survey_record_pk
		primary key(survey_id, user_id)
		using index storage (initial 5M next 5M)
		tablespace ruseri05
 )
 tablespace ruserd05
 storage (initial 10M next 10M);

