/* collectibles phase 3 */

/* Religious */
DECLARE
  v_par NUMBER(10) := 366;
  v_id NUMBER(10) := 1446;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 366;
  v_to NUMBER(10) := 1446;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 366;
  v_id NUMBER(10) := 1447;
  v_name VARCHAR2(20) := 'Christianity';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
DECLARE
 v_sib NUMBER(10) := 1447;
  v_id NUMBER(10) := 1448;
  v_name VARCHAR2(20) := 'Eastern';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
DECLARE
 v_sib NUMBER(10) := 1448;
  v_id NUMBER(10) := 1449;
  v_name VARCHAR2(20) := 'Judaism';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

/* Science Fiction */
DECLARE
 v_sib NUMBER(10) := 785;
  v_id NUMBER(10) := 1450;
  v_name VARCHAR2(20) := 'Dr. Who';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

/* Sheet Music */
DECLARE
  v_par NUMBER(10) := 157;
  v_id NUMBER(10) := 1451;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 157;
  v_to NUMBER(10) := 1451;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 157;
  v_id NUMBER(10) := 1452;
  v_name VARCHAR2(20) := 'Military/Historical';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
DECLARE
 v_sib NUMBER(10) := 1452;
  v_id NUMBER(10) := 1453;
  v_name VARCHAR2(20) := 'Movies/TV';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
DECLARE
 v_sib NUMBER(10) := 1453;
  v_id NUMBER(10) := 1454;
  v_name VARCHAR2(20) := 'Non-piano';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
DECLARE
 v_sib NUMBER(10) := 1454;
  v_id NUMBER(10) := 1455;
  v_name VARCHAR2(20) := 'Radio';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
DECLARE
 v_sib NUMBER(10) := 1455;
  v_id NUMBER(10) := 1456;
  v_name VARCHAR2(20) := 'Rag';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 1456;
  v_id NUMBER(10) := 1457;
  v_name VARCHAR2(20) := 'Song Books';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 1457;
  v_id NUMBER(10) := 1458;
  v_name VARCHAR2(20) := 'Theatre';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 1458;
  v_id NUMBER(10) := 1459;
  v_name VARCHAR2(20) := 'Transportation';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 1451;
  v_id NUMBER(10) := 1460;
  v_name VARCHAR2(20) := 'Souvenirs';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 593;
  v_id NUMBER(10) := 1461;
  v_name VARCHAR2(20) := 'Tools';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 668;
  v_id NUMBER(10) := 1462;
  v_name VARCHAR2(20) := 'Credit/Charge Cards';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 1462;
  v_id NUMBER(10) := 1463;
  v_name VARCHAR2(20) := 'Phone Cards';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
/* Railrodiana */
DECLARE
  v_par NUMBER(10) := 130;
  v_id NUMBER(10) := 1444;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 130;
  v_to NUMBER(10) := 1444;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 130;
  v_id NUMBER(10) := 1445;
  v_name VARCHAR2(20) := 'Paper';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/


DECLARE
 v_sib NUMBER(10) := 417;
  v_id NUMBER(10) := 1464;
  v_name VARCHAR2(20) := 'Umbrellas';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 606;
  v_id NUMBER(10) := 1465;
  v_name VARCHAR2(20) := 'Pin Cushions';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/
/* Weird Stuff */
DECLARE
  v_par NUMBER(10) := 515;
  v_id NUMBER(10) := 1466;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 515;
  v_to NUMBER(10) := 1466;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 515;
  v_id NUMBER(10) := 1467;
  v_name VARCHAR2(20) := 'Slightly Unusual';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 1467;
  v_id NUMBER(10) := 1468;
  v_name VARCHAR2(20) := 'Really Weird';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

DECLARE
 v_sib NUMBER(10) := 1468;
  v_id NUMBER(10) := 1469;
  v_name VARCHAR2(20) := 'Totally Bizarre';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/