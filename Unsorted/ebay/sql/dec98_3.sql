/* computers */

/* splits */
/* Printers */
DECLARE
  v_par NUMBER(10) := 178;
  v_id NUMBER(10) := 1245;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 178;
  v_to NUMBER(10) := 1245;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 178;
  v_id NUMBER(10) := 1246;
  v_name VARCHAR2(20) := 'Accessories';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1246;
  v_id NUMBER(10) := 1483;
  v_name VARCHAR2(20) := 'Supplies';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* Memory */
DECLARE
  v_par NUMBER(10) := 172;
  v_id NUMBER(10) := 1480;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 172;
  v_to NUMBER(10) := 1480;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 172;
  v_id NUMBER(10) := 1481;
  v_name VARCHAR2(20) := 'Ram';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1481;
  v_id NUMBER(10) := 1482;
  v_name VARCHAR2(20) := 'Sdram';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* Games */
DECLARE
  v_par NUMBER(10) := 187;
  v_id NUMBER(10) := 1249;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 187;
  v_to NUMBER(10) := 1249;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 187;
  v_id NUMBER(10) := 1250;
  v_name VARCHAR2(20) := 'Atari';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1250;
  v_id NUMBER(10) := 1487;
  v_name VARCHAR2(20) := 'Commodore';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1487;
  v_id NUMBER(10) := 1488;
  v_name VARCHAR2(20) := 'Sega';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1488;
  v_id NUMBER(10) := 1489;
  v_name VARCHAR2(20) := 'Sony';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/ 
DECLARE
 v_sib NUMBER(10) := 1489;
  v_id NUMBER(10) := 1490;
  v_name VARCHAR2(20) := 'Nintendo';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/ 

/* add new categories */

DECLARE
 v_sib NUMBER(10) := 174;
  v_id NUMBER(10) := 1244;
  v_name VARCHAR2(20) := 'Motherboards';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 180;
  v_id NUMBER(10) := 1247;
  v_name VARCHAR2(20) := 'Vintage';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 184;
  v_id NUMBER(10) := 1248;
  v_name VARCHAR2(20) := 'Children';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 189;
  v_id NUMBER(10) := 1251;
  v_name VARCHAR2(20) := 'Programming';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 190;
  v_id NUMBER(10) := 1252;
  v_name VARCHAR2(20) := 'Reference';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 179;
  v_id NUMBER(10) := 1484;
  v_name VARCHAR2(20) := 'Servers';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1484;
  v_id NUMBER(10) := 1485;
  v_name VARCHAR2(20) := 'Terminals';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1485;
  v_id NUMBER(10) := 1486;
  v_name VARCHAR2(20) := 'Unix';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 171;
  v_id NUMBER(10) := 1479;
  v_name VARCHAR2(20) := 'Mainframes';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/*  Services cat */
DECLARE
 v_sib NUMBER(10) := 193;
  v_id NUMBER(10) := 1253;
  v_name VARCHAR2(20) := 'Informational';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
  v_par NUMBER(10) := 1253;
  v_id NUMBER(10) := 1491;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1491;
  v_id NUMBER(10) := 1311;
  v_name VARCHAR2(20) := 'Auction Services';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* Miscellaneous */

/* additions */
DECLARE
 v_sib NUMBER(10) := 292;
  v_id NUMBER(10) := 1261;
  v_name VARCHAR2(20) := 'Baby Items';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1061;
  v_id NUMBER(10) := 1262;
  v_name VARCHAR2(20) := 'Boots,Shoes';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1063;
  v_id NUMBER(10) := 1263;
  v_name VARCHAR2(20) := 'Evening Wear';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1263;
  v_id NUMBER(10) := 1264;
  v_name VARCHAR2(20) := 'Maternity';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1065;
  v_id NUMBER(10) := 1265;
  v_name VARCHAR2(20) := 'Wedding';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 322;
  v_id NUMBER(10) := 1273;
  v_name VARCHAR2(20) := 'DVD';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1273;
  v_id NUMBER(10) := 1274;
  v_name VARCHAR2(20) := 'Laserdisc';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* equipment */
DECLARE
 v_sib NUMBER(10) := 1048;
  v_id NUMBER(10) := 1266;
  v_name VARCHAR2(20) := 'Equipment';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
  v_par NUMBER(10) := 1266;
  v_id NUMBER(10) := 1267;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1267;
  v_id NUMBER(10) := 1268;
  v_name VARCHAR2(20) := 'Construction';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1268;
  v_id NUMBER(10) := 1269;
  v_name VARCHAR2(20) := 'Farm';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1269;
  v_id NUMBER(10) := 1270;
  v_name VARCHAR2(20) := 'Industrial';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1270;
  v_id NUMBER(10) := 1271;
  v_name VARCHAR2(20) := 'Restaurant';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1271;
  v_id NUMBER(10) := 1272;
  v_name VARCHAR2(20) := 'Shop';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* end equipment */

/* more additions */
DECLARE
 v_sib NUMBER(10) := 2037;
  v_id NUMBER(10) := 1275;
  v_name VARCHAR2(20) := 'Foodstuff';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2032;
  v_id NUMBER(10) := 1276;
  v_name VARCHAR2(20) := 'Hardware Supplies';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 300;
  v_id NUMBER(10) := 1277;
  v_name VARCHAR2(20) := 'Beauty';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1277;
  v_id NUMBER(10) := 1278;
  v_name VARCHAR2(20) := 'Fitness';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1278;
  v_id NUMBER(10) := 1279;
  v_name VARCHAR2(20) := 'Health';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1279;
  v_id NUMBER(10) := 1280;
  v_name VARCHAR2(20) := 'Home Furnishings';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 620;
  v_id NUMBER(10) := 1287;
  v_name VARCHAR2(20) := 'Electronic';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1287;
  v_id NUMBER(10) := 1288;
  v_name VARCHAR2(20) := 'Equipment';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 621;
  v_id NUMBER(10) := 1289;
  v_name VARCHAR2(20) := 'Keyboard, Piano';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 318;
  v_id NUMBER(10) := 1290;
  v_name VARCHAR2(20) := 'Shipping';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 310;
  v_id NUMBER(10) := 1291;
  v_name VARCHAR2(20) := 'Archery';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 2023;
  v_id NUMBER(10) := 1292;
  v_name VARCHAR2(20) := 'Billiards';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* add with sub cats */
DECLARE
 v_sib NUMBER(10) := 1292;
  v_id NUMBER(10) := 1293;
  v_name VARCHAR2(20) := 'Boating';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
  v_par NUMBER(10) := 1293;
  v_id NUMBER(10) := 1294;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1294;
  v_id NUMBER(10) := 1295;
  v_name VARCHAR2(20) := 'Jet Ski';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1295;
  v_id NUMBER(10) := 1296;
  v_name VARCHAR2(20) := 'Power';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1296;
  v_id NUMBER(10) := 1297;
  v_name VARCHAR2(20) := 'Sail';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* splits */
/* Fishing */
DECLARE
  v_par NUMBER(10) := 384;
  v_id NUMBER(10) := 1492;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 384;
  v_to NUMBER(10) := 1492;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 384;
  v_id NUMBER(10) := 1493;
  v_name VARCHAR2(20) := 'Lures';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1493;
  v_id NUMBER(10) := 1494;
  v_name VARCHAR2(20) := 'Reels';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1494;
  v_id NUMBER(10) := 1495;
  v_name VARCHAR2(20) := 'Rods';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* more additions */
DECLARE
 v_sib NUMBER(10) := 383;
  v_id NUMBER(10) := 1299;
  v_name VARCHAR2(20) := 'Rock Climbing';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1299;
  v_id NUMBER(10) := 1300;
  v_name VARCHAR2(20) := 'Scuba';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1300;
  v_id NUMBER(10) := 1301;
  v_name VARCHAR2(20) := 'Skating';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1301;
  v_id NUMBER(10) := 1302;
  v_name VARCHAR2(20) := 'Skiing';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1302;
  v_id NUMBER(10) := 1303;
  v_name VARCHAR2(20) := 'Snow Boarding';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 382;
  v_id NUMBER(10) := 1304;
  v_name VARCHAR2(20) := 'Show Supplies';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 631;
  v_id NUMBER(10) := 1305;
  v_name VARCHAR2(20) := 'Tickets';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1305;
  v_id NUMBER(10) := 1310;
  v_name VARCHAR2(20) := 'Travel';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* Tickets sub cats */
DECLARE
  v_par NUMBER(10) := 1305;
  v_id NUMBER(10) := 1306;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1306;
  v_id NUMBER(10) := 1307;
  v_name VARCHAR2(20) := 'Concert';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1307;
  v_id NUMBER(10) := 1308;
  v_name VARCHAR2(20) := 'Sporting Events';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1308;
  v_id NUMBER(10) := 1309;
  v_name VARCHAR2(20) := 'Theatre';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* splits */
/* Automotive */
DECLARE
  v_par NUMBER(10) := 292;
  v_id NUMBER(10) := 1254;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 292;
  v_to NUMBER(10) := 1254;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 292;
  v_id NUMBER(10) := 1255;
  v_name VARCHAR2(20) := 'Accessories';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1255;
  v_id NUMBER(10) := 1256;
  v_name VARCHAR2(20) := 'Parts';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1256;
  v_id NUMBER(10) := 1257;
  v_name VARCHAR2(20) := 'Vehicles';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
  v_par NUMBER(10) := 1257;
  v_id NUMBER(10) := 1258;
  v_name VARCHAR2(20) := 'Cars';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1258;
  v_id NUMBER(10) := 1259;
  v_name VARCHAR2(20) := 'Trucks';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1259;
  v_id NUMBER(10) := 1260;
  v_name VARCHAR2(20) := 'RV';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* Pet Supplies */
DECLARE
  v_par NUMBER(10) := 301;
  v_id NUMBER(10) := 1281;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 301;
  v_to NUMBER(10) := 1281;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 301;
  v_id NUMBER(10) := 1282;
  v_name VARCHAR2(20) := 'Bathroom Accessories';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1282;
  v_id NUMBER(10) := 1283;
  v_name VARCHAR2(20) := 'Canine';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1283;
  v_id NUMBER(10) := 1284;
  v_name VARCHAR2(20) := 'Feline';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1284;
  v_id NUMBER(10) := 1285;
  v_name VARCHAR2(20) := 'Reptile';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1285;
  v_id NUMBER(10) := 1286;
  v_name VARCHAR2(20) := 'Rodent';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* glass - all aditions */
DECLARE
 v_sib NUMBER(10) := 18;
  v_id NUMBER(10) := 1229;
  v_name VARCHAR2(20) := 'Opalescent';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 20;
  v_id NUMBER(10) := 1230;
  v_name VARCHAR2(20) := 'Stain Glass';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 442;
  v_id NUMBER(10) := 1231;
  v_name VARCHAR2(20) := 'Blue Ridge';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1043;
  v_id NUMBER(10) := 1232;
  v_name VARCHAR2(20) := 'Buffalo';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 589;
  v_id NUMBER(10) := 1233;
  v_name VARCHAR2(20) := 'Headvases';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 454;
  v_id NUMBER(10) := 1234;
  v_name VARCHAR2(20) := 'Moorcroft';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1234;
  v_id NUMBER(10) := 1235;
  v_name VARCHAR2(20) := 'Newcomb';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1235;
  v_id NUMBER(10) := 1236;
  v_name VARCHAR2(20) := 'Owens';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1236;
  v_id NUMBER(10) := 1237;
  v_name VARCHAR2(20) := 'Pfaltzgraff';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 517;
  v_id NUMBER(10) := 1238;
  v_name VARCHAR2(20) := 'Tea Pots, Tea Sets';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1238;
  v_id NUMBER(10) := 1239;
  v_name VARCHAR2(20) := 'UHL';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 460;
  v_id NUMBER(10) := 1240;
  v_name VARCHAR2(20) := 'Wall Pockets';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 474;
  v_id NUMBER(10) := 1241;
  v_name VARCHAR2(20) := 'Flow Blue';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 92;
  v_id NUMBER(10) := 1242;
  v_name VARCHAR2(20) := 'Lladro';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 470;
  v_id NUMBER(10) := 1243;
  v_name VARCHAR2(20) := 'Royal Bayreuth';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
update ebay_categories set name=name||chr(39)||'s' where id in (1248, 1260,1295);