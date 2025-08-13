/* toys and Beanie babies */
/* rename first */
update ebay_categories set name='Action/Revell', 
Description=name3||' '||name2||' '||name1||' Action/Revell' 
where id=763;

Update ebay_categories
Set
Name='Toys &'||' Beanies',
description='Toys &'||' Beanies' 
Where id=220;

Update ebay_categories 
Set name1 = 'Toys &'||' Beanies',
description= 'Toys &'||' Beanies ' || name
Where level1 = 220;

Update ebay_categories 
Set name2 = 'Toys &'||' Beanies',
description= 'Toys &'||' Beanies ' || name1 || ' ' ||name
Where level2 = 220;

Update ebay_categories 
Set name3 = 'Toys &'||' Beanies',
description= 'Toys &'||' Beanies' || name2 || ' ' ||name1 || ' ' ||name
Where level3 = 220;

Update ebay_categories
Set
Name='Beanies',
description=name3||' '||name2||' '||name1|| 'Beanies' 
Where id=436;

Update ebay_categories 
Set name1 = 'Beanies',
description= name3||' '||name2||' '||name1|| 'Beanies' 
Where level1 = 436;

/* splits */

/* GI Joe */
DECLARE
  v_par NUMBER(10) := 349;
  v_id NUMBER(10) := 1167;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 349;
  v_to NUMBER(10) := 1167;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 349;
  v_id NUMBER(10) := 1168;
  v_name VARCHAR2(20) := '12 Inch';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* Hot Wheels */
DECLARE
  v_par NUMBER(10) := 224;
  v_id NUMBER(10) := 1171;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 224;
  v_to NUMBER(10) := 1171;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 224;
  v_id NUMBER(10) := 1172;
  v_name VARCHAR2(20) := 'Red Line';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Matchbox */
DECLARE
  v_par NUMBER(10) := 761;
  v_id NUMBER(10) := 1174;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 761;
  v_to NUMBER(10) := 1174;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 761;
  v_id NUMBER(10) := 1175;
  v_name VARCHAR2(20) := 'Lesney';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Board Games */
DECLARE
  v_par NUMBER(10) := 235;
  v_id NUMBER(10) := 1176;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 235;
  v_to NUMBER(10) := 1176;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 235;
  v_id NUMBER(10) := 1177;
  v_name VARCHAR2(20) := 'Horror/Monster';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1177;
  v_id NUMBER(10) := 1178;
  v_name VARCHAR2(20) := 'Movie/TV';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1178;
  v_id NUMBER(10) := 1179;
  v_name VARCHAR2(20) := 'Space';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1179;
  v_id NUMBER(10) := 1180;
  v_name VARCHAR2(20) := 'Sports';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1180;
  v_id NUMBER(10) := 1181;
  v_name VARCHAR2(20) := 'War Games';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Hobbies */

DECLARE
  v_par NUMBER(10) := 1039;
  v_id NUMBER(10) := 1199;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 1039;
  v_to NUMBER(10) := 1199;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 1039;
  v_id NUMBER(10) := 1200;
  v_name VARCHAR2(20) := 'Remote Control';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Plastic Models */
DECLARE
  v_par NUMBER(10) := 774;
  v_id NUMBER(10) := 1188;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 774;
  v_to NUMBER(10) := 1188;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 774;
  v_id NUMBER(10) := 1189;
  v_name VARCHAR2(20) := 'Air';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1189;
  v_id NUMBER(10) := 1190;
  v_name VARCHAR2(20) := 'Automotive';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1190;
  v_id NUMBER(10) := 1191;
  v_name VARCHAR2(20) := 'Military';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1191;
  v_id NUMBER(10) := 1192;
  v_name VARCHAR2(20) := 'Monster';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1192;
  v_id NUMBER(10) := 1193;
  v_name VARCHAR2(20) := 'Science Fiction';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1193;
  v_id NUMBER(10) := 1194;
  v_name VARCHAR2(20) := 'Space';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Add new siblings to exiting categpries */
DECLARE
 v_sib NUMBER(10) := 747;
  v_id NUMBER(10) := 1169;
  v_name VARCHAR2(20) := 'Mego';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 755;
  v_id NUMBER(10) := 1170;
  v_name VARCHAR2(20) := 'Wrestling';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 760;
  v_id NUMBER(10) := 1173;
  v_name VARCHAR2(20) := 'Lledo';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1176;
  v_id NUMBER(10) := 1182;
  v_name VARCHAR2(20) := 'Electronic';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1182;
  v_id NUMBER(10) := 1183;
  v_name VARCHAR2(20) := 'Role Playing';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1183;
  v_id NUMBER(10) := 1184;
  v_name VARCHAR2(20) := 'Vintage';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 221;
  v_id NUMBER(10) := 1185;
  v_name VARCHAR2(20) := 'Battery Operated';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1042;
  v_id NUMBER(10) := 1186;
  v_name VARCHAR2(20) := 'Lego';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1186;
  v_id NUMBER(10) := 1187;
  v_name VARCHAR2(20) := 'Space Toys';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 228;
  v_id NUMBER(10) := 1195;
  v_name VARCHAR2(20) := 'Garfield';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 756;
  v_id NUMBER(10) := 1196;
  v_name VARCHAR2(20) := 'Toy Rings';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 2005;
  v_id NUMBER(10) := 1197;
  v_name VARCHAR2(20) := 'Wooden';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 733;
  v_id NUMBER(10) := 1198;
  v_name VARCHAR2(20) := 'Toy Parts';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 441;
  v_id NUMBER(10) := 1496;
  v_name VARCHAR2(20) := 'Trading Cards';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* reorganize disney existing categories script */
Update ebay_categories
Set
Name='Disneyana',
description=name3||' ' || name2||' '|| name1||' Disneyana'
Where id=137;

insert into ebay_categories
(marketplace, id, name,	description,
 	adult, isleaf, isexpired, level1, level2, level3, level4,
 	name1, name2, name3, name4, prevcategory, nextcategory, 
 	featuredcost, created, filereference, last_modified)			
   values	
(0, 1369, 'Contemporary', 'Collectibles Disneyana Contemporary',
'0', '0', '0', 137, 1, 0, 0, 
'Disneyana','Collectibles','', '', 0, 139,
9.95, sysdate, '', sysdate);

update ebay_categories 
set
Name='Vintage',
description = 'Collectibles Disneyana Vintage',
name1='Disneyana',
prevcategory=1369, nextcategory=0 
where id=139;

update ebay_categories 
set
Name='General',
description = 'Collectibles Disneyana Contemporary General',
level1=1369, level2=137, level3=1,
name1='Contemporary', name2='Disneyana', name3='Collectibles',
prevcategory=0, nextcategory=144
where id=138;

update ebay_categories 
set
description = 'Collectibles Disneyana Contemporary '||name,
level1=1369,
name1='Contemporary', 
name2='Disneyana',
prevcategory=138, nextcategory=141
where id=144;

update ebay_categories 
set
description = 'Collectibles Disneyana Contemporary '||name,
level1=1369,
name1='Contemporary', 
name2='Disneyana',
prevcategory=144, nextcategory=142
where id=141;

update ebay_categories 
set
description = 'Collectibles Disneyana Contemporary '||name,
level1=1369,
name1='Contemporary', 
name2='Disneyana',
prevcategory=141, nextcategory=143
where id=142;

update ebay_categories 
set
description = 'Collectibles Disneyana Contemporary '||name,
level1=1369,
name1='Contemporary', 
name2='Disneyana',
prevcategory=142, nextcategory=145
where id=143;

update ebay_categories 
set
name='Video/Laserdiscs',
description = 'Collectibles Disneyana Contemporary Video/Laserdiscs',
level1=1369,
name1='Contemporary', 
name2='Disneyana',
prevcategory=143, nextcategory=0
where id=145;

update ebay_categories 
set
description = 'Collectibles Disneyana Vintage General',
name1='Vintage', 
name2='Disneyana',
prevcategory=0, nextcategory=0
where id=140;

update ebay_items set category=138, last_modified=sysdate where category=146;

delete from ebay_categories where id=146;

/* consumer electronics */
/* additions */
DECLARE
 v_sib NUMBER(10) := 296;
  v_id NUMBER(10) := 1503;
  v_name VARCHAR2(20) := 'Telephone';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1503;
  v_id NUMBER(10) := 1504;
  v_name VARCHAR2(20) := 'Test Equipment';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* splits */
DECLARE
  v_par NUMBER(10) := 295;
  v_id NUMBER(10) := 1497;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 295;
  v_to NUMBER(10) := 1497;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 295;
  v_id NUMBER(10) := 1498;
  v_name VARCHAR2(20) := 'Auto';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1498;
  v_id NUMBER(10) := 1499;
  v_name VARCHAR2(20) := 'Home';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
  v_par NUMBER(10) := 296;
  v_id NUMBER(10) := 1500;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 296;
  v_to NUMBER(10) := 1500;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 296;
  v_id NUMBER(10) := 1501;
  v_name VARCHAR2(20) := 'CB';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1501;
  v_id NUMBER(10) := 1502;
  v_name VARCHAR2(20) := 'Ham';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
