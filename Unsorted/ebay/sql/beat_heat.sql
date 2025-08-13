/*	$Id: beat_heat.sql,v 1.3 1999/02/21 02:52:27 josh Exp $	*/
/* add Hawaiiana */
DECLARE
 v_sib NUMBER(10) := 576;
  v_id NUMBER(10) := 2000;
  v_name VARCHAR2(20) := 'Hawaiiana';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* to add a child category; this proc commits*/
DECLARE
  v_par NUMBER(10) := 2000;
  v_id NUMBER(10) := 2001;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* add Hawaiiana's children */
DECLARE
 v_sib NUMBER(10) := 2001;
  v_id NUMBER(10) := 2002;
  v_name VARCHAR2(20) := 'Accessories';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 2002;
  v_id NUMBER(10) := 2003;
  v_name VARCHAR2(20) := 'Apparel';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 2003;
  v_id NUMBER(10) := 2004;
  v_name VARCHAR2(20) := 'Hula';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* add Toys: Toy Soldiers */
DECLARE
 v_sib NUMBER(10) := 756;
  v_id NUMBER(10) := 2005;
  v_name VARCHAR2(20) := 'Toy Soldiers';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
