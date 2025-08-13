/*	$Id: remove_dup_fb.sql,v 1.2 1999/02/21 02:57:02 josh Exp $	*/
/* sql script to copy user's feedback to temp_feedback_detail,
 * delete their feedback detail and copy back, removing duplicates */

PROMPT Please enter a valid user id (number)
accept newid char prompt 'Id:'

insert into temp_feedback_detail
  select * from ebay_feedback_detail where id=&newid;

delete ebay_feedback_detail where id=&newid;

insert into ebay_feedback_detail
  select distinct * from temp_feedback_detail where id=&newid;
commit

/* need to cleanup temp_feedback_detail once we're sure this works */
