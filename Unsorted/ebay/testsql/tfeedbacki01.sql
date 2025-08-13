/*	$Id: tfeedbacki01.sql,v 1.2 1999/02/21 02:57:16 josh Exp $	*/
/*
** feedbacki01.sql
**
** Create tablespace for feedback indicies
*/
create tablespace tfeedbacki01
	datafile '/oracle01/ebay/oradata/oradata/test/tfeedbacki01.dbf'
	size 60M
	autoextend off;
