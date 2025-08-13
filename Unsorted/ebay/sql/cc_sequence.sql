/*	$Id: cc_sequence.sql,v 1.3 1999/02/21 02:52:43 josh Exp $	*/
CREATE SEQUENCE cc_refid_sequence
   START WITH 1
   INCREMENT BY 1
   NOMAXVALUE
   NOCYCLE
   CACHE 50;
