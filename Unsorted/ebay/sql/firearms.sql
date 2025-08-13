/*	$Id: firearms.sql,v 1.3 1999/02/21 02:54:33 josh Exp $	*/
/*to add after a category */
DECLARE
 v_sib NUMBER(10) := 319;
  v_id NUMBER(10) := 2037;
  v_name VARCHAR2(20) := 'Firearms';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
update ebay_categories set adult=1 where id=2037;
