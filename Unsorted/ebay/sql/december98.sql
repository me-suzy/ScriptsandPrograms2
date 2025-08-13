/*	$Id: december98.sql,v 1.3 1999/02/21 02:52:54 josh Exp $	*/
/* create new category - firdst child of computers */
insert into ebay_categories
(marketplace, id, name,	description,
adult, isleaf, isexpired, level1, level2, level3, level4,
name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified)			
values	
(0, 1085, 'Digital Cameras', 'Computers Digital Cameras',
'0', '1', '0', 160, 0, 0, 0, 
'Computers', '','', '', 0, 161,
9.95, sysdate, '', sysdate);

update ebay_categories set prevcategory=1085 where id=161;

/* adding sibling category */

DECLARE
 v_sib NUMBER(10) := 777;
  v_id NUMBER(10) := 1086;
  v_name VARCHAR2(20) := 'Artist Offerings';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* split handcrafted arts - start with adding a child */
DECLARE
  v_par NUMBER(10) := 122;
  v_id NUMBER(10) := 1087;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 122;
  v_to NUMBER(10) := 1087;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 122;
  v_id NUMBER(10) := 1088;
  v_name VARCHAR2(20) := 'Artist Offerings';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* split Christmas - start with adding a child */
DECLARE
  v_par NUMBER(10) := 908;
  v_id NUMBER(10) := 1089;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 908;
  v_to NUMBER(10) := 1089;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 908;
  v_id NUMBER(10) := 1090;
  v_name VARCHAR2(20) := 'Santa';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1090;
  v_id NUMBER(10) := 1091;
  v_name VARCHAR2(20) := 'Vintage';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

