/*	$Id: cat_proc.sql,v 1.4 1999/02/21 02:52:38 josh Exp $	*/
/* swap to  and from */
CREATE OR REPLACE PROCEDURE SWAP_CAT(
p_from_id IN NUMBER,
p_to_id IN NUMBER) AS
 v_nextcat NUMBER(10);
 v_name1 VARCHAR2(20);
 v_name2 VARCHAR2(20);
 v_name3 VARCHAR2(20);
 v_name4 VARCHAR2(20);
 v_level1 NUMBER(10);
 v_level2 NUMBER(10);
 v_level3 NUMBER(10);
 v_level4 NUMBER(10);
 v_desc VARCHAR2(255);
 v_name VARCHAR2(20);
 v_adult CHAR(1);
 v_isleaf CHAR(1);
 v_expired CHAR(1);
 v_prevcat NUMBER(10);
 v_featured NUMBER(10,2);
 v_fileref VARCHAR2(255);
 v_created date;
 v_ord NUMBER(3);

BEGIN
-- save to values in the v_vars
SELECT name 
INTO v_name
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT name1 
INTO v_name1
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT name2 
INTO v_name2
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT name3 
INTO v_name3
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT name4 
INTO v_name4
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT adult 
INTO v_adult
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT isleaf 
INTO v_isleaf
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT isexpired 
INTO v_expired
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
--
SELECT level1 
INTO v_level1
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT level2 
INTO v_level2
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT level3 
INTO v_level3
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT level4 
INTO v_level4
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
--
SELECT nextcategory
INTO v_nextcat
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT prevcategory
INTO v_prevcat
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT featuredcost
INTO v_featured
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT filereference
INTO v_fileref
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT created
INTO v_created
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT order_no
INTO v_ord
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;
SELECT description
INTO v_desc
FROM ebay_categories
WHERE id = p_to_id and marketplace = 0;

-- copy data into TO category
update ebay_categories
set name = (select name from ebay_categories where id = p_from_id),
description = (select description from ebay_categories where id = p_from_id),
adult = (select adult from ebay_categories where id = p_from_id),
isleaf = (select isleaf from ebay_categories where id = p_from_id),
isexpired = (select isexpired from ebay_categories where id = p_from_id),
level1 = (select level1 from ebay_categories where id = p_from_id),
level2 = (select level2 from ebay_categories where id = p_from_id),
level3 = (select level3 from ebay_categories where id = p_from_id),
level4 = (select level4 from ebay_categories where id = p_from_id),
name1 = (select name1 from ebay_categories where id = p_from_id),
name2 = (select name2 from ebay_categories where id = p_from_id),
name3 = (select name3 from ebay_categories where id = p_from_id),
name4 = (select name4 from ebay_categories where id = p_from_id),
prevcategory = (select prevcategory from ebay_categories where id = p_from_id),
nextcategory = (select nextcategory from ebay_categories where id = p_from_id),
featuredcost = (select featuredcost from ebay_categories where id = p_from_id),
created = (select created from ebay_categories where id = p_from_id),
filereference = (select filereference from ebay_categories where id = p_from_id),
last_modified = (select last_modified from ebay_categories where id = p_from_id),
order_no = (select order_no from ebay_categories where id = p_from_id)
where id = p_to_id;

-- set references
update ebay_categories set nextcategory = p_to_id where nextcategory = p_from_id;
update ebay_categories set prevcategory = p_to_id where prevcategory = p_from_id;

-- move saved data into FROM
update ebay_categories 
set name = v_name,	description = v_desc,
 adult = v_adult, isleaf = v_isleaf, isexpired = v_expired,
 level1 = p_to_id, level2 = v_level2, level3 = v_level3, level4 = v_level4,
 name1 = v_name1, name2 = v_name2, name3 = v_name3, name4 = v_name4, 
 prevcategory = v_prevcat, nextcategory = v_nextcat, 
featuredcost = v_featured, created = v_created, filereference = v_fileref,
 last_modified = sysdate, order_no = v_ord				
where
id = p_from_id;
END SWAP_CAT;
/
/* stored procedures to simplify things */

CREATE OR REPLACE PROCEDURE ADDCAT_AFTER (
p_sib_id IN NUMBER,
p_cat_id IN NUMBER,
p_cat_name IN VARCHAR2) AS
 v_nextcat NUMBER(10);
 v_name1 VARCHAR2(20);
 v_name2 VARCHAR2(20);
 v_name3 VARCHAR2(20);
 v_name4 VARCHAR2(20);
 v_level1 NUMBER(10);
 v_level2 NUMBER(10);
 v_level3 NUMBER(10);
 v_level4 NUMBER(10);
 v_desc VARCHAR2(255);
BEGIN
SELECT nextcategory 
INTO v_nextcat
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
--
SELECT name1 
INTO v_name1
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
SELECT name2 
INTO v_name2
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
SELECT name3 
INTO v_name3
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
SELECT name4 
INTO v_name4
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
--
SELECT level1 
INTO v_level1
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
SELECT level2 
INTO v_level2
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
SELECT level3 
INTO v_level3
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
SELECT level4 
INTO v_level4
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
--
select name4 || ' ' || name3 || ' ' || name2  || ' ' || name1  || ' ' || p_cat_name
INTO v_desc
FROM ebay_categories
WHERE id = p_sib_id and marketplace = 0;
--
INSERT INTO ebay_categories 
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified, order_no)				
values	
(0, p_cat_id, p_cat_name, v_desc, '0', '1', '0', 
v_level1, v_level2, v_level3, v_level4,
v_name1, v_name2, v_name3, v_name4,
p_sib_id, v_nextcat, 9.95, sysdate, '', sysdate, 0);
--
UPDATE ebay_categories 
set nextcategory = p_cat_id 
where id = p_sib_id and marketplace = 0;
--
if (v_nextcat != 0) THEN
UPDATE ebay_categories 
set prevcategory = p_cat_id 
where id = v_nextcat and marketplace = 0;
end if;
END ADDCAT_AFTER;
/
/* add child script */
CREATE OR REPLACE PROCEDURE ADDCAT_CHILD (
p_par_id IN NUMBER,
p_cat_id IN NUMBER,
p_cat_name IN VARCHAR2) AS
 v_nextcat NUMBER(10);
 v_name1 VARCHAR2(20);
 v_name2 VARCHAR2(20);
 v_name3 VARCHAR2(20);
 v_name4 VARCHAR2(20);
 v_level1 NUMBER(10);
 v_level2 NUMBER(10);
 v_level3 NUMBER(10);
 v_level4 NUMBER(10);
 v_desc VARCHAR2(255);
BEGIN
--
SELECT name 
INTO v_name1
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
SELECT name1 
INTO v_name2
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
SELECT name2 
INTO v_name3
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
SELECT name3 
INTO v_name4
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
--
SELECT level1 
INTO v_level1
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
SELECT level2 
INTO v_level2
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
SELECT level3 
INTO v_level3
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
SELECT level4 
INTO v_level4
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
--
select name3  || ' ' || name2  || ' ' || name1  || ' ' || name  || ' ' || p_cat_name 
INTO v_desc
FROM ebay_categories
WHERE id = p_par_id and marketplace = 0;
--
INSERT INTO ebay_categories 
(marketplace, id, name,	description,
 adult, isleaf, isexpired, level1, level2, level3, level4,
 name1, name2, name3, name4, prevcategory, nextcategory, 
featuredcost, created, filereference, last_modified, order_no)				
values	
(0, p_cat_id, p_cat_name, v_desc, '0', '1','0', 
p_par_id, v_level1, v_level2, v_level3, 
v_name1, v_name2, v_name3, v_name4,
0, 0, 9.95, sysdate, '', sysdate, 0);
--
UPDATE ebay_categories 
set isleaf = '0'
where id = p_par_id and marketplace = 0;
END ADDCAT_CHILD;
/
