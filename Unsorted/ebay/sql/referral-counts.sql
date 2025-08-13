/*	$Id: referral-counts.sql,v 1.2 1999/02/21 02:54:53 josh Exp $	*/
SET NEWPAGE 0
SET SPACE 0
SET LINESIZE 80
SET PAGESIZE 0
SET ECHO OFF
SET FEEDBACK OFF
SET HEADING OFF
COLUMN a format a40
select 'Netscape ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 1;
select 'Microsoft ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 2;
select 'Excite ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 3;
select 'Yahoo! ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 4;
select 'Infoseek ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 5;
select 'Lycos ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 6;
select 'AOL ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 7;
select 'WhoWhere ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 8;
select 'Four11 ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 9;
select 'InfoSpace ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 10;
select 'Search.com ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 11;
select 'Planet Direct ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 12;
select 'DejaNews ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 13;
select 'AngelFire ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 14;
select 'USA Today ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 15;
select 'Other Search engine ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 16;
select 'Friend or Family ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 17;
select 'Business Associate ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 18;
select 'Internet Search Engine ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 19;
select 'Radio, TV, Newspaper or Magazine ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 20;
select 'Trade Show or Event ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 21;
select 'Advertisement ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value = 22;
select 'Other ' a, count(*) from ebay_user_attributes
   where attribute_id = 1 and number_value in (0,23);
select 'Total ' a, count(*) from ebay_user_attributes 
	where attribute_id = 1;
