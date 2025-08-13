/*	$Id: ebay_user_survey.sql,v 1.2 1999/02/21 02:56:56 josh Exp $	*/
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
 tablespace tuserd01
 storage (initial 10K next 2K);

alter table ebay_user_survey_responses
	add constraint		user_survey_response_pk
		primary key(marketplace, user_id, survey_id, question_id)
		using index	storage(initial 1K next 1K)
						tablespace tuseri01;
commit;
alter table ebay_user_survey_responses
	add constraint		user_survey_responses_fk
		foreign key (user_id)
		references	ebay_users(id);
commit;
