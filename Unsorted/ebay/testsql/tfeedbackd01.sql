/*	$Id: tfeedbackd01.sql,v 1.2 1999/02/21 02:57:15 josh Exp $	*/
/*
** feedbackd01.sql
**
** Create tablespace for feedback data
*/
create tablespace tfeedbackd01
	datafile '/oracle01/ebay/oradata//oradata/oradata/tfeedbackd01.dbf'
	size 100M
	autoextend off;
