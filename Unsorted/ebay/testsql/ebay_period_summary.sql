/*	$Id: ebay_period_summary.sql,v 1.2 1999/02/21 02:56:39 josh Exp $	*/
create table ebay_period_summary(bucket number not null,
                                 dollars_bought number,
                                 dollars_sold number)
/
create unique index ebay_period_summary_u1 on ebay_period_summary(bucket)
/
