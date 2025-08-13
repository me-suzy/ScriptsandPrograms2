/*	$Id: giga_pet118.sql,v 1.3 1999/02/21 02:54:34 josh Exp $	*/
/* rename Tamagotchi, Giga Pet into Giga Pet - it is leaf for now*/
Update ebay_categories
Set
Name='Giga Pet',
description= name1||' Giga Pet'
Where id=232;

/* to add a child category */
DECLARE
  v_par NUMBER(10) := 232;
  v_id NUMBER(10) := 1082;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 232;
  v_to NUMBER(10) := 1082;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 232;
  v_id NUMBER(10) := 1083;
  v_name VARCHAR2(20) := 'Furby';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1083;
  v_id NUMBER(10) := 1084;
  v_name VARCHAR2(20) := 'Tamagotchi';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* alphabetize Toys category */
update ebay_categories set prevcategory=1082 where id=1039;
update ebay_categories set nextcategory=1082 where id=233;

update ebay_categories set prevcategory=776 where id=756;
update ebay_categories set nextcategory=756 where id=776;

update ebay_categories set  prevcategory=233, nextcategory=1039 where id=1082;
