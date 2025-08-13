/* Dolls and figures */

/* reorder first */
update ebay_categories set nextcategory=1034, name='Cabbage Patch' where id=243;
update ebay_categories set prevcategory=1034, nextcategory=0 where id=242;
update ebay_categories set prevcategory=243, nextcategory=242 where id=1034;

/* additions */
DECLARE
 v_sib NUMBER(10) := 1032;
  v_id NUMBER(10) := 1201;
  v_name VARCHAR2(20) := 'Furniture';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1034;
  v_id NUMBER(10) := 1204;
  v_name VARCHAR2(20) := 'Rainbow Brite';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 368;
  v_id NUMBER(10) := 1205;
  v_name VARCHAR2(20) := 'Patterns';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 245;
  v_id NUMBER(10) := 1206;
  v_name VARCHAR2(20) := 'Trolls';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* splits */
DECLARE
  v_par NUMBER(10) := 333;
  v_id NUMBER(10) := 1202;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 333;
  v_to NUMBER(10) := 1202;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 333;
  v_id NUMBER(10) := 1203;
  v_name VARCHAR2(20) := 'Vintage';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* antiques */

/* additions */
DECLARE
 v_sib NUMBER(10) := 355;
  v_id NUMBER(10) := 1207;
  v_name VARCHAR2(20) := 'Architectural';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 356;
  v_id NUMBER(10) := 1208;
  v_name VARCHAR2(20) := 'European';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 357;
  v_id NUMBER(10) := 1209;
  v_name VARCHAR2(20) := 'Furniture';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1209;
  v_id NUMBER(10) := 1210;
  v_name VARCHAR2(20) := 'Medical';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 359;
  v_id NUMBER(10) := 1217;
  v_name VARCHAR2(20) := 'Primitives';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 362;
  v_id NUMBER(10) := 1218;
  v_name VARCHAR2(20) := 'Toleware';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1218;
  v_id NUMBER(10) := 1219;
  v_name VARCHAR2(20) := 'Woodenware';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/


/* splits */
DECLARE
  v_par NUMBER(10) := 358;
  v_id NUMBER(10) := 1211;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 358;
  v_to NUMBER(10) := 1211;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 358;
  v_id NUMBER(10) := 1212;
  v_name VARCHAR2(20) := 'Bronze';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1212;
  v_id NUMBER(10) := 1213;
  v_name VARCHAR2(20) := 'Copper';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1213;
  v_id NUMBER(10) := 1214;
  v_name VARCHAR2(20) := 'Pewter';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1214;
  v_id NUMBER(10) := 1215;
  v_name VARCHAR2(20) := 'Silver';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1215;
  v_id NUMBER(10) := 1216;
  v_name VARCHAR2(20) := 'Silver Plate';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Sports Memorabilia- add only */

DECLARE
 v_sib NUMBER(10) := 212;
  v_id NUMBER(10) := 1222;
  v_name VARCHAR2(20) := 'Minor League';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 55;
  v_id NUMBER(10) := 1223;
  v_name VARCHAR2(20) := 'Golf';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 56;
  v_id NUMBER(10) := 1224;
  v_name VARCHAR2(20) := 'Olympic';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1224;
  v_id NUMBER(10) := 1225;
  v_name VARCHAR2(20) := 'Racing';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1225;
  v_id NUMBER(10) := 1226;
  v_name VARCHAR2(20) := 'Tennis';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 205;
  v_id NUMBER(10) := 1227;
  v_name VARCHAR2(20) := 'Boxing';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 465;
  v_id NUMBER(10) := 1228;
  v_name VARCHAR2(20) := 'Indy 500';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* split post 1990 */
DECLARE
  v_par NUMBER(10) := 364;
  v_id NUMBER(10) := 1220;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 364;
  v_to NUMBER(10) := 1220;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 364;
  v_id NUMBER(10) := 1221;
  v_name VARCHAR2(20) := 'Furniture';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
