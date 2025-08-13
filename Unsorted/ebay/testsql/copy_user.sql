/*	$Id: copy_user.sql,v 1.2 1999/02/21 02:55:39 josh Exp $	*/
/* sql script to copy users from scott to ebaytest 
 * input is userid; inserts relevant user data including
 * feedback and feedback_detail to ebaytest */

PROMPT Please enter a valid user id
accept newuser char prompt 'User:'
select id, userid from scott.ebay_users
  where userid = '&newuser';

insert into ebay_users
    select * from scott.ebay_users
	where userid = '&newuser';

insert into ebay_feedback
	select * from scott.ebay_feedback
	where id = 
		(select id from scott.ebay_users
		   where userid='&newuser');
	
insert into ebay_feedback_detail
	select id, 
		time, 
		1, 
		commenting_host,
		comment_type, 
		comment_score, 
		comment_text
	from scott.ebay_feedback_detail
	where id = 
		(select id from ebay_users
		where userid='&newuser');
		
