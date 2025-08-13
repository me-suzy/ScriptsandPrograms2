/* reorganize books existing categories script */
/* add some 1st level children of books-siblings of exisitng cats */


DECLARE
 v_sib NUMBER(10) := 268;
  v_id NUMBER(10) := 1092;
  v_name VARCHAR2(20) := 'Audio';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 279;
  v_id NUMBER(10) := 1105;
  v_name VARCHAR2(20) := 'Educational';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* educational kids */
DECLARE
  v_par NUMBER(10) := 1105;
  v_id NUMBER(10) := 1106;
  v_name VARCHAR2(20) := 'Business, Finance';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1106;
  v_id NUMBER(10) := 1107;
  v_name VARCHAR2(20) := 'Computers, Internet';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1107;
  v_id NUMBER(10) := 1108;
  v_name VARCHAR2(20) := 'Encyclopedias';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1108;
  v_id NUMBER(10) := 1109;
  v_name VARCHAR2(20) := 'Homeschool';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1109;
  v_id NUMBER(10) := 1110;
  v_name VARCHAR2(20) := 'Language';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1110;
  v_id NUMBER(10) := 1111;
  v_name VARCHAR2(20) := 'Medical';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1111;
  v_id NUMBER(10) := 1112;
  v_name VARCHAR2(20) := 'Physical Sciences';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1112;
  v_id NUMBER(10) := 1113;
  v_name VARCHAR2(20) := 'Reference';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1113;
  v_id NUMBER(10) := 1114;
  v_name VARCHAR2(20) := 'Science/Technology';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1114;
  v_id NUMBER(10) := 1115;
  v_name VARCHAR2(20) := 'Self-Help';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* move textbooks after this one*/
update ebay_categories 
set
prevcategory=1115, nextcategory=0,
name3=name2, level3=level2,
name2=name1, level2=level1,
name1='Educational', level1=1105,
description = 'Books, Movies, Music Books Educational Textbooks'
where id=2026;

update ebay_categories 
set
nextcategory=2026
where id=1115;

/* reorder children level1 */
update ebay_categories set 
nextcategory=279
where id=1092;

update ebay_categories set 
prevcategory=1092, nextcategory=1105
where id=279;

update ebay_categories set 
nextcategory=269
where id=274;

update ebay_categories set 
prevcategory=1105
where id=270;

update ebay_categories set 
nextcategory=270, prevcategory=279
where id=1105;

update ebay_categories set 
nextcategory=0, prevcategory=274
where id=269;




