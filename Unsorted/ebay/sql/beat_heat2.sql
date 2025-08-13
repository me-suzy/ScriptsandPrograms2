/*	$Id: beat_heat2.sql,v 1.3 1999/02/21 02:52:28 josh Exp $	*/
/* circuses and carnivals */
DECLARE
 v_sib NUMBER(10) := 791;
  v_id NUMBER(10) := 2016;
  v_name VARCHAR2(20) := 'Circus,Carnival';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* add General to C&C*/
DECLARE
  v_par NUMBER(10) := 2016;
  v_id NUMBER(10) := 2017;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 2017;
  v_id NUMBER(10) := 2018;
  v_name VARCHAR2(20) := 'Programs and Posters';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2018;
  v_id NUMBER(10) := 2019;
  v_name VARCHAR2(20) := 'Souvenirs';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/


/* Sporting Goods */
/* switch fishing, golf and hunting */
update ebay_categories set nextcategory=384 where id=310;
update ebay_categories set prevcategory=310, nextcategory=630 where id=384;
update ebay_categories set prevcategory=384, nextcategory=383 where id=630;
update ebay_categories set prevcategory=630, nextcategory=0 where id=383;

/* start adding now */
DECLARE
 v_sib NUMBER(10) := 310;
  v_id NUMBER(10) := 2022;
  v_name VARCHAR2(20) := 'Baseball';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2022;
  v_id NUMBER(10) := 2023;
  v_name VARCHAR2(20) := 'Basketball';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2023;
  v_id NUMBER(10) := 2020;
  v_name VARCHAR2(20) := 'Camping';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 384;
  v_id NUMBER(10) := 2024;
  v_name VARCHAR2(20) := 'Football';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 630;
  v_id NUMBER(10) := 2021;
  v_name VARCHAR2(20) := 'Hiking';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Add to Movies */
DECLARE
 v_sib NUMBER(10) := 197;
  v_id NUMBER(10) := 2025;
  v_name VARCHAR2(20) := 'Gone With The Wind';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Add to Books */
DECLARE
 v_sib NUMBER(10) := 279;
  v_id NUMBER(10) := 2026;
  v_name VARCHAR2(20) := 'Textbooks';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
