/* simple additions to book category */

/* split children */
DECLARE
  v_par NUMBER(10) := 279;
  v_id NUMBER(10) := 1093;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 279;
  v_to NUMBER(10) := 1093;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 279;
  v_id NUMBER(10) := 1094;
  v_name VARCHAR2(20) := 'Big Little Books';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1094;
  v_id NUMBER(10) := 1095;
  v_name VARCHAR2(20) := 'Classics';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1095;
  v_id NUMBER(10) := 1096;
  v_name VARCHAR2(20) := 'Early Readers';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1096;
  v_id NUMBER(10) := 1097;
  v_name VARCHAR2(20) := 'Fairy Tales';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1097;
  v_id NUMBER(10) := 1098;
  v_name VARCHAR2(20) := 'Non-Fiction';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1098;
  v_id NUMBER(10) := 1099;
  v_name VARCHAR2(20) := 'Little Golden Books';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1099;
  v_id NUMBER(10) := 1100;
  v_name VARCHAR2(20) := 'Mystery, Adventure';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1100;
  v_id NUMBER(10) := 1101;
  v_name VARCHAR2(20) := 'Mythology';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1101;
  v_id NUMBER(10) := 1102;
  v_name VARCHAR2(20) := 'Picture Books';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1102;
  v_id NUMBER(10) := 1103;
  v_name VARCHAR2(20) := 'Series';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1103;
  v_id NUMBER(10) := 1104;
  v_name VARCHAR2(20) := 'Young Adult';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* fiction */
DECLARE
 v_sib NUMBER(10) := 271;
  v_id NUMBER(10) := 1116;
  v_name VARCHAR2(20) := 'Horror';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1116;
  v_id NUMBER(10) := 1117;
  v_name VARCHAR2(20) := 'Humor';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1117;
  v_id NUMBER(10) := 1118;
  v_name VARCHAR2(20) := 'Military';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 273;
  v_id NUMBER(10) := 1119;
  v_name VARCHAR2(20) := 'Western';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* non-fiction */
DECLARE
 v_sib NUMBER(10) := 378;
  v_id NUMBER(10) := 1121;
  v_name VARCHAR2(20) := 'Arts, Entertainment';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 276;
  v_id NUMBER(10) := 1122;
  v_name VARCHAR2(20) := 'Geography';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1122;
  v_id NUMBER(10) := 1123;
  v_name VARCHAR2(20) := 'History';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1123;
  v_id NUMBER(10) := 1124;
  v_name VARCHAR2(20) := 'Hobby, Crafts';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1124;
  v_id NUMBER(10) := 1125;
  v_name VARCHAR2(20) := 'Home &'||' Garden';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1125;
  v_id NUMBER(10) := 1126;
  v_name VARCHAR2(20) := 'Hunting, Fishing';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1126;
  v_id NUMBER(10) := 1127;
  v_name VARCHAR2(20) := 'Military';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1127;
  v_id NUMBER(10) := 1128;
  v_name VARCHAR2(20) := 'Paranormal';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1128;
  v_id NUMBER(10) := 1129;
  v_name VARCHAR2(20) := 'Poetry';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1129;
  v_id NUMBER(10) := 1130;
  v_name VARCHAR2(20) := 'Price Guides';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1130;
  v_id NUMBER(10) := 1131;
  v_name VARCHAR2(20) := 'Religion';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 275;
  v_id NUMBER(10) := 1132;
  v_name VARCHAR2(20) := 'Vehicles';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1132;
  v_id NUMBER(10) := 1133;
  v_name VARCHAR2(20) := 'Western';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* first level childeren of books */
DECLARE
 v_sib NUMBER(10) := 270;
  v_id NUMBER(10) := 1120;
  v_name VARCHAR2(20) := 'First Editions';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 274;
  v_id NUMBER(10) := 1134;
  v_name VARCHAR2(20) := 'Pulps, Club Editions';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1134;
  v_id NUMBER(10) := 1135;
  v_name VARCHAR2(20) := 'Paperbacks';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 269;
  v_id NUMBER(10) := 1136;
  v_name VARCHAR2(20) := 'School Annuals';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

/* Magazines */
DECLARE
 v_sib NUMBER(10) := 608;
  v_id NUMBER(10) := 1137;
  v_name VARCHAR2(20) := 'Business';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 609;
  v_id NUMBER(10) := 1138;
  v_name VARCHAR2(20) := 'Collector';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1138;
  v_id NUMBER(10) := 1139;
  v_name VARCHAR2(20) := 'Cooking';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1139;
  v_id NUMBER(10) := 1140;
  v_name VARCHAR2(20) := 'Crafts, Hobby';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1140;
  v_id NUMBER(10) := 1141;
  v_name VARCHAR2(20) := 'Home &'||' Garden';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1141;
  v_id NUMBER(10) := 1142;
  v_name VARCHAR2(20) := 'Horror, Monster';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1142;
  v_id NUMBER(10) := 1143;
  v_name VARCHAR2(20) := 'Humor';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/

DECLARE
 v_sib NUMBER(10) := 610;
  v_id NUMBER(10) := 1144;
  v_name VARCHAR2(20) := 'Medical';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 611;
  v_id NUMBER(10) := 1145;
  v_name VARCHAR2(20) := 'Movie,TV';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 612;
  v_id NUMBER(10) := 1146;
  v_name VARCHAR2(20) := 'Pulp';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 613;
  v_id NUMBER(10) := 1147;
  v_name VARCHAR2(20) := 'Science &' || ' Nature';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1147;
  v_id NUMBER(10) := 1148;
  v_name VARCHAR2(20) := 'Sci-Fi';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 614;
  v_id NUMBER(10) := 1149;
  v_name VARCHAR2(20) := 'Technical';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1149;
  v_id NUMBER(10) := 1150;
  v_name VARCHAR2(20) := 'Travel';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1150;
  v_id NUMBER(10) := 1160;
  v_name VARCHAR2(20) := 'True Crime';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* movies */
DECLARE
 v_sib NUMBER(10) := 381;
  v_id NUMBER(10) := 1476;
  v_name VARCHAR2(20) := '8mm';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1476;
  v_id NUMBER(10) := 1477;
  v_name VARCHAR2(20) := '16mm';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1477;
  v_id NUMBER(10) := 1478;
  v_name VARCHAR2(20) := '32mm';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* split Movies */
DECLARE
  v_par NUMBER(10) := 309;
  v_id NUMBER(10) := 1151;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 309;
  v_to NUMBER(10) := 1151;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 309;
  v_id NUMBER(10) := 1161;
  v_name VARCHAR2(20) := 'Action, Adventure';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1161;
  v_id NUMBER(10) := 1162;
  v_name VARCHAR2(20) := 'Cartoons';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1162;
  v_id NUMBER(10) := 1163;
  v_name VARCHAR2(20) := 'Childrens';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1163;
  v_id NUMBER(10) := 1164;
  v_name VARCHAR2(20) := 'Classics';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1164;
  v_id NUMBER(10) := 1165;
  v_name VARCHAR2(20) := 'Comedy';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1165;
  v_id NUMBER(10) := 1166;
  v_name VARCHAR2(20) := 'Documentary';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1166;
  v_id NUMBER(10) := 1470;
  v_name VARCHAR2(20) := 'Foreign';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1470;
  v_id NUMBER(10) := 1471;
  v_name VARCHAR2(20) := 'Horror';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1471;
  v_id NUMBER(10) := 1472;
  v_name VARCHAR2(20) := 'Music';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1472;
  v_id NUMBER(10) := 1473;
  v_name VARCHAR2(20) := 'Mystery';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1473;
  v_id NUMBER(10) := 1474;
  v_name VARCHAR2(20) := 'Romance';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
DECLARE
 v_sib NUMBER(10) := 1474;
  v_id NUMBER(10) := 1475;
  v_name VARCHAR2(20) := 'Sci Fi';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/
/* reorg fiction */
update ebay_categories set
nextcategory=325
where id=272;

update ebay_categories set
prevcategory=325
where id=273;

update ebay_categories set
nextcategory=0
where id=1119;

update ebay_categories set
prevcategory=272,
nextcategory=273
where id=325;

/* reorg non fiction */
update ebay_categories set
nextcategory=277
where id=1121;

update ebay_categories set
prevcategory=1121
where id=277;

update ebay_categories set
nextcategory=276
where id=278;

update ebay_categories set
prevcategory=278
where id=276;

update ebay_categories set
nextcategory=275
where id=1131;

update ebay_categories set
prevcategory=1131
where id=275;

update ebay_categories set
nextcategory=0
where id=1133;

/* reorg magazines */
update ebay_categories set
nextcategory=616
where id=1137;

update ebay_categories set
prevcategory=616
where id=609;

update ebay_categories set
nextcategory=0
where id=615;

update ebay_categories set
prevcategory=1137,
nextcategory=609
where id=616;

/* merge sega, nintendo into games::general */

update ebay_items set category=187, last_modified=sysdate where category=190;

delete from ebay_categories where id=190;

update ebay_categories set
prevcategory=1251
where id=1252;

update ebay_categories set
nextcategory=1252
where id=1251;

/* split utilities */
DECLARE
  v_par NUMBER(10) := 191;
  v_id NUMBER(10) := 1505;
  v_name VARCHAR2(20) := 'General';
begin
addcat_child (p_par_id => v_par, 
			p_cat_id => v_id, 
			p_cat_name => v_name);
end;
/

/* swap cats */
DECLARE
  v_from NUMBER(10) := 191;
  v_to NUMBER(10) := 1505;
begin
swap_cat (p_from_id => v_from, 
			p_to_id => v_to);
end;
/

/* start adding siblings */

DECLARE
 v_sib NUMBER(10) := 191;
  v_id NUMBER(10) := 1506;
  v_name VARCHAR2(20) := 'Auction Utilities';
begin
addcat_after (p_sib_id => v_sib, 
		p_cat_id => v_id, 
		p_cat_name => v_name); 

end;
/