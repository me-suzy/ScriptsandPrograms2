/*	$Id: beat_heat3.sql,v 1.3 1999/02/21 02:52:29 josh Exp $	*/
/* rename Elvis to Elvis:General */
update ebay_categories set name='Elvis:General', 
description = 'Collectibles Memorabilia Rock-n-Roll Elvis:General' 
where id=433;

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 433;
  v_id NUMBER(10) := 2007;
  v_name VARCHAR2(20) := 'Elvis:Buttons';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 2007;
  v_id NUMBER(10) := 2008;
  v_name VARCHAR2(20) := 'Elvis:Clothing';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 2008;
  v_id NUMBER(10) := 2009;
  v_name VARCHAR2(20) := 'Elvis:Concert/Tour';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2009;
  v_id NUMBER(10) := 2010;
  v_name VARCHAR2(20) := 'Elvis:Belongings';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2010;
  v_id NUMBER(10) := 2011;
  v_name VARCHAR2(20) := 'Elvis:Movie Items';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2011;
  v_id NUMBER(10) := 2012;
  v_name VARCHAR2(20) := 'Elvis:Music';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2012;
  v_id NUMBER(10) := 2013;
  v_name VARCHAR2(20) := 'Elvis:Novelties';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2013;
  v_id NUMBER(10) := 2014;
  v_name VARCHAR2(20) := 'Elvis:Photos';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2014;
  v_id NUMBER(10) := 2015;
  v_name VARCHAR2(20) := 'Elvis:Trading Cards';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Automobilia */
DECLARE
  v_par NUMBER(10) := 418;
  v_id NUMBER(10) := 2029;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 418;
  v_to NUMBER(10) := 2029;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 418;
  v_id NUMBER(10) := 2030;
  v_name VARCHAR2(20) := 'Classic Cars';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2030;
  v_id NUMBER(10) := 2031;
  v_name VARCHAR2(20) := 'Classic Parts';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Gardening  */
DECLARE
  v_par NUMBER(10) := 519;
  v_id NUMBER(10) := 2032;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 519;
  v_to NUMBER(10) := 2032;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 519;
  v_id NUMBER(10) := 2033;
  v_name VARCHAR2(20) := 'Plants/Seeds';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2033;
  v_id NUMBER(10) := 2034;
  v_name VARCHAR2(20) := 'Garden Accessories';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2034;
  v_id NUMBER(10) := 2035;
  v_name VARCHAR2(20) := 'Publications';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
