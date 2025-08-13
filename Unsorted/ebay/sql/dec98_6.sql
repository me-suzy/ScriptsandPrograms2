/* collectibles phase 2 */

/* photographic omages */
DECLARE
 v_sib NUMBER(10) := 48;
  v_id NUMBER(10) := 1507;
  v_name VARCHAR2(20) := 'Risque';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* advertising */
DECLARE
 v_sib NUMBER(10) := 813;
  v_id NUMBER(10) := 1312;
  v_name VARCHAR2(20) := 'United';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 805;
  v_id NUMBER(10) := 1313;
  v_name VARCHAR2(20) := 'Auto';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
  v_par NUMBER(10) := 1313;
  v_id NUMBER(10) := 1314;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1314;
  v_id NUMBER(10) := 1315;
  v_name VARCHAR2(20) := 'Buick';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1315;
  v_id NUMBER(10) := 1316;
  v_name VARCHAR2(20) := 'Chevrolet';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1316;
  v_id NUMBER(10) := 1317;
  v_name VARCHAR2(20) := 'Chrysler';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1317;
  v_id NUMBER(10) := 1318;
  v_name VARCHAR2(20) := 'Dodge';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1318;
  v_id NUMBER(10) := 1319;
  v_name VARCHAR2(20) := 'Ford';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1319;
  v_id NUMBER(10) := 1320;
  v_name VARCHAR2(20) := 'Pontiac';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1313;
  v_id NUMBER(10) := 1321;
  v_name VARCHAR2(20) := 'Bakery';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 872;
  v_id NUMBER(10) := 1322;
  v_name VARCHAR2(20) := 'Cereal';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 40;
  v_id NUMBER(10) := 1328;
  v_name VARCHAR2(20) := 'Citgo';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 839;
  v_id NUMBER(10) := 1329;
  v_name VARCHAR2(20) := 'Gulf';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1329;
  v_id NUMBER(10) := 1330;
  v_name VARCHAR2(20) := 'Hess';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 837;
  v_id NUMBER(10) := 1331;
  v_name VARCHAR2(20) := 'Sinclair';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 838;
  v_id NUMBER(10) := 1332;
  v_name VARCHAR2(20) := 'Sunoco';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 849;
  v_id NUMBER(10) := 1333;
  v_name VARCHAR2(20) := 'Seed, Feed';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 877;
  v_id NUMBER(10) := 1334;
  v_name VARCHAR2(20) := 'Moxie';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Animals */
DECLARE
 v_sib NUMBER(10) := 34;
  v_id NUMBER(10) := 1335;
  v_name VARCHAR2(20) := 'Animals';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
  v_par NUMBER(10) := 1335;
  v_id NUMBER(10) := 1336;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
 v_sib NUMBER(10) := 1336;
  v_id NUMBER(10) := 1337;
  v_name VARCHAR2(20) := 'Bird';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1337;
  v_id NUMBER(10) := 1338;
  v_name VARCHAR2(20) := 'Cat';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1338;
  v_id NUMBER(10) := 1339;
  v_name VARCHAR2(20) := 'Dog';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1339;
  v_id NUMBER(10) := 1340;
  v_name VARCHAR2(20) := 'Fantasy';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1340;
  v_id NUMBER(10) := 1341;
  v_name VARCHAR2(20) := 'Horse';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1341;
  v_id NUMBER(10) := 1342;
  v_name VARCHAR2(20) := 'Reptile';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1342;
  v_id NUMBER(10) := 1343;
  v_name VARCHAR2(20) := 'Zoo';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* animation */
DECLARE
  v_par NUMBER(10) := 363;
  v_id NUMBER(10) := 1344;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 363;
  v_to NUMBER(10) := 1344;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 363;
  v_id NUMBER(10) := 1345;
  v_name VARCHAR2(20) := 'Japanese Animation';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

/* Autographs::entertainment */
DECLARE
 v_sib NUMBER(10) := 59;
  v_id NUMBER(10) := 1346;
  v_name VARCHAR2(20) := 'Space';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 
end;
/

/* Bears::Boyds */
DECLARE
  v_par NUMBER(10) := 390;
  v_id NUMBER(10) := 1347;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 390;
  v_to NUMBER(10) := 1347;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 390;
  v_id NUMBER(10) := 1348;
  v_name VARCHAR2(20) := 'Resin';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* bottles */
DECLARE
 v_sib NUMBER(10) := 369;
  v_id NUMBER(10) := 1349;
  v_name VARCHAR2(20) := 'Avon';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 897;
  v_id NUMBER(10) := 1350;
  v_name VARCHAR2(20) := 'Soda';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1350;
  v_id NUMBER(10) := 1351;
  v_name VARCHAR2(20) := 'Whiskey';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* clocks */
DECLARE
 v_sib NUMBER(10) := 119;
  v_id NUMBER(10) := 1352;
  v_name VARCHAR2(20) := 'Character Watches';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Collector plates */

DECLARE
  v_par NUMBER(10) := 574;
  v_id NUMBER(10) := 1353;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 574;
  v_to NUMBER(10) := 1353;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 574;
  v_id NUMBER(10) := 1354;
  v_name VARCHAR2(20) := 'Bradford Exchange';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1354;
  v_id NUMBER(10) := 1355;
  v_name VARCHAR2(20) := 'Danbury Mint';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1355;
  v_id NUMBER(10) := 1356;
  v_name VARCHAR2(20) := 'Franklin Mint';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1356;
  v_id NUMBER(10) := 1357;
  v_name VARCHAR2(20) := 'Hamilton';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1357;
  v_id NUMBER(10) := 1358;
  v_name VARCHAR2(20) := 'Knowles';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1358;
  v_id NUMBER(10) := 1359;
  v_name VARCHAR2(20) := 'Princess House';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1359;
  v_id NUMBER(10) := 1360;
  v_name VARCHAR2(20) := 'State Plates';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1360;
  v_id NUMBER(10) := 1361;
  v_name VARCHAR2(20) := 'Wedgewood';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* crafts */
DECLARE
 v_sib NUMBER(10) := 1087;
  v_id NUMBER(10) := 1362;
  v_name VARCHAR2(20) := 'Knitting';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1362;
  v_id NUMBER(10) := 1363;
  v_name VARCHAR2(20) := 'Needlepoint';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 904;
  v_id NUMBER(10) := 1364;
  v_name VARCHAR2(20) := 'Tole Painting';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* department 56 */
DECLARE
  v_par NUMBER(10) := 370;
  v_id NUMBER(10) := 1365;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 370;
  v_to NUMBER(10) := 1365;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 370;
  v_id NUMBER(10) := 1366;
  v_name VARCHAR2(20) := 'Dickens Village';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1366;
  v_id NUMBER(10) := 1367;
  v_name VARCHAR2(20) := 'Snow Babies';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1367;
  v_id NUMBER(10) := 1368;
  v_name VARCHAR2(20) := 'Snow Village';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* disneyana */
DECLARE
 v_sib NUMBER(10) := 138;
  v_id NUMBER(10) := 1370;
  v_name VARCHAR2(20) := 'Apparel,Accessories';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1370;
  v_id NUMBER(10) := 1371;
  v_name VARCHAR2(20) := 'Animation/Cels';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 144;
  v_id NUMBER(10) := 1372;
  v_name VARCHAR2(20) := 'Books';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1372;
  v_id NUMBER(10) := 1373;
  v_name VARCHAR2(20) := 'Collector Clubs';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1373;
  v_id NUMBER(10) := 1374;
  v_name VARCHAR2(20) := 'Comics';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 141;
  v_id NUMBER(10) := 1375;
  v_name VARCHAR2(20) := 'Figurines';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1375;
  v_id NUMBER(10) := 1376;
  v_name VARCHAR2(20) := 'Holiday';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1376;
  v_id NUMBER(10) := 1377;
  v_name VARCHAR2(20) := 'Housewares';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1377;
  v_id NUMBER(10) := 1378;
  v_name VARCHAR2(20) := 'Jewelry';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1378;
  v_id NUMBER(10) := 1379;
  v_name VARCHAR2(20) := 'Limited Editions';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1379;
  v_id NUMBER(10) := 1380;
  v_name VARCHAR2(20) := 'Magic Kingdoms';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 143;
  v_id NUMBER(10) := 1381;
  v_name VARCHAR2(20) := 'Premiums';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1381;
  v_id NUMBER(10) := 1382;
  v_name VARCHAR2(20) := 'Records/Tapes/Cd';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1382;
  v_id NUMBER(10) := 1383;
  v_name VARCHAR2(20) := 'Toys,Games,Puzzles';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 145;
  v_id NUMBER(10) := 1384;
  v_name VARCHAR2(20) := 'Watches';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 140;
  v_id NUMBER(10) := 1385;
  v_name VARCHAR2(20) := 'Apparel/Accessories';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1385;
  v_id NUMBER(10) := 1386;
  v_name VARCHAR2(20) := 'Animation,Cels';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1386;
  v_id NUMBER(10) := 1387;
  v_name VARCHAR2(20) := 'Posters &'||' Lithos';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1387;
  v_id NUMBER(10) := 1388;
  v_name VARCHAR2(20) := 'Books';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1388;
  v_id NUMBER(10) := 1389;
  v_name VARCHAR2(20) := 'Comics';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1389;
  v_id NUMBER(10) := 1390;
  v_name VARCHAR2(20) := 'Figures';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1390;
  v_id NUMBER(10) := 1391;
  v_name VARCHAR2(20) := 'Figurines';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1391;
  v_id NUMBER(10) := 1392;
  v_name VARCHAR2(20) := 'Holiday';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1392;
  v_id NUMBER(10) := 1152;
  v_name VARCHAR2(20) := 'Housewares';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1152;
  v_id NUMBER(10) := 1393;
  v_name VARCHAR2(20) := 'Jewelry';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1393;
  v_id NUMBER(10) := 1394;
  v_name VARCHAR2(20) := 'Pins, Buttons';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1394;
  v_id NUMBER(10) := 1395;
  v_name VARCHAR2(20) := 'Plush Toys';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1395;
  v_id NUMBER(10) := 1396;
  v_name VARCHAR2(20) := 'Premiums';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1396;
  v_id NUMBER(10) := 1397;
  v_name VARCHAR2(20) := 'Records';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1397;
  v_id NUMBER(10) := 1398;
  v_name VARCHAR2(20) := 'Toys,Games,Puzzles';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1398;
  v_id NUMBER(10) := 1399;
  v_name VARCHAR2(20) := 'Watches';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* furniture */
DECLARE
 v_sib NUMBER(10) := 792;
  v_id NUMBER(10) := 1400;
  v_name VARCHAR2(20) := 'Furniture';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* kitchenware */
DECLARE
  v_par NUMBER(10) := 475;
  v_id NUMBER(10) := 1401;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 475;
  v_to NUMBER(10) := 1401;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 475;
  v_id NUMBER(10) := 1402;
  v_name VARCHAR2(20) := 'Commemorative';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1402;
  v_id NUMBER(10) := 1403;
  v_name VARCHAR2(20) := 'Pocket';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
  v_par NUMBER(10) := 371;
  v_id NUMBER(10) := 1404;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 371;
  v_to NUMBER(10) := 1404;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 371;
  v_id NUMBER(10) := 1405;
  v_name VARCHAR2(20) := 'Electric';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1405;
  v_id NUMBER(10) := 1406;
  v_name VARCHAR2(20) := 'Non Electric';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1406;
  v_id NUMBER(10) := 1407;
  v_name VARCHAR2(20) := 'Parts';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1407;
  v_id NUMBER(10) := 1408;
  v_name VARCHAR2(20) := 'Shades';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
  v_par NUMBER(10) := 150;
  v_id NUMBER(10) := 1409;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

DECLARE
  v_from NUMBER(10) := 150;
  v_to NUMBER(10) := 1409;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 150;
  v_id NUMBER(10) := 1410;
  v_name VARCHAR2(20) := 'Metal';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1410;
  v_id NUMBER(10) := 1411;
  v_name VARCHAR2(20) := 'Plastic';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
  v_par NUMBER(10) := 582;
  v_id NUMBER(10) := 1412;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

DECLARE
  v_from NUMBER(10) := 582;
  v_to NUMBER(10) := 1412;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 582;
  v_id NUMBER(10) := 1413;
  v_name VARCHAR2(20) := 'Road Maps';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Royal */
DECLARE
 v_sib NUMBER(10) := 427;
  v_id NUMBER(10) := 1414;
  v_name VARCHAR2(20) := 'Royal';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Movie rename first */

Update ebay_categories
Set
Name='Lobby Cards:General',
description='Lobby Cards:General' 
Where id=198;

DECLARE
 v_sib NUMBER(10) := 198;
  v_id NUMBER(10) := 1417;
  v_name VARCHAR2(20) := 'Lobby Cards:Foreign';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1417;
  v_id NUMBER(10) := 1419;
  v_name VARCHAR2(20) := 'Posters:General';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 1419;
  v_id NUMBER(10) := 1420;
  v_name VARCHAR2(20) := 'Posters:Foreign';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* rock-n-roll*/
DECLARE
 v_sib NUMBER(10) := 210;
  v_id NUMBER(10) := 1421;
  v_name VARCHAR2(20) := 'Photos';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1421;
  v_id NUMBER(10) := 1422;
  v_name VARCHAR2(20) := 'Hard Rock Cafe';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 445;
  v_id NUMBER(10) := 1423;
  v_name VARCHAR2(20) := 'The Who';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* television */
DECLARE
  v_par NUMBER(10) := 201;
  v_id NUMBER(10) := 1424;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
  v_from NUMBER(10) := 201;
  v_to NUMBER(10) := 1424;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 201;
  v_id NUMBER(10) := 1425;
  v_name VARCHAR2(20) := '50';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1425;
  v_id NUMBER(10) := 1426;
  v_name VARCHAR2(20) := '60';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1426;
  v_id NUMBER(10) := 1427;
  v_name VARCHAR2(20) := '70';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1427;
  v_id NUMBER(10) := 1428;
  v_name VARCHAR2(20) := '80';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1428;
  v_id NUMBER(10) := 1429;
  v_name VARCHAR2(20) := '90';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* metalware */
DECLARE
  v_par NUMBER(10) := 28;
  v_id NUMBER(10) := 1430;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/
DECLARE
  v_from NUMBER(10) := 28;
  v_to NUMBER(10) := 1430;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 28;
  v_id NUMBER(10) := 1431;
  v_name VARCHAR2(20) := 'Aluminum';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1431;
  v_id NUMBER(10) := 1432;
  v_name VARCHAR2(20) := 'Bronze';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1432;
  v_id NUMBER(10) := 1433;
  v_name VARCHAR2(20) := 'Copper';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1433;
  v_id NUMBER(10) := 1434;
  v_name VARCHAR2(20) := 'Pewter';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1434;
  v_id NUMBER(10) := 1435;
  v_name VARCHAR2(20) := 'Silver';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1435;
  v_id NUMBER(10) := 1436;
  v_name VARCHAR2(20) := 'Silver Plate';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* paper */
DECLARE
 v_sib NUMBER(10) := 477;
  v_id NUMBER(10) := 1437;
  v_name VARCHAR2(20) := 'Menus';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1437;
  v_id NUMBER(10) := 1439;
  v_name VARCHAR2(20) := 'Newspapers';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1439;
  v_id NUMBER(10) := 1438;
  v_name VARCHAR2(20) := 'Playing Cards';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 914;
  v_id NUMBER(10) := 1440;
  v_name VARCHAR2(20) := 'Scrapbooks';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 151;
  v_id NUMBER(10) := 1442;
  v_name VARCHAR2(20) := 'Phonographs';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 367;
  v_id NUMBER(10) := 1443;
  v_name VARCHAR2(20) := 'Phonographs';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* rename and move around Trains */
update ebay_categories set 
name='Trains, Railrodiana', 
description='Collectibles Trains, Railrodiana' 
where id=130;

update ebay_categories set
nextcategory = 366
where id = 931;

update ebay_categories set
prevcategory = 931
where id = 366;

update ebay_categories set
prevcategory = 868
where id = 130;

update ebay_categories set
nextcategory = 417
where id = 479;

update ebay_categories set
nextcategory = 130
where id = 868;

update ebay_categories set
prevcategory = 479
where id = 417;

update ebay_categories set name=name||chr(39)||'s' 
where id in (1382,1425, 1426, 1427,1428, 1429);