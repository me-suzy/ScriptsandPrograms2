/*
 * ebay_locations.sql
 */
/* drop table ebay_locations;
 */

 create table ebay_locations
 (
	ZIP				VARCHAR2(12)
		constraint		locations_zip_nn
			not null,
	CITY			VARCHAR2(64)
		constraint		locations_city_nn
			not null,
	STATE			VARCHAR2(64),
	COUNTY			VARCHAR2(64),
	COUNTRY			VARCHAR2(64)
		constraint		locations_country_nn
			not null,
	FIPS			NUMBER(5,0),
	AREACODE		NUMBER(3,0)
		constraint		locations_areacode_nn
			not null,
	TIMEZONE		NUMBER(2,0)
		constraint		locations_timezone_nn
			not null,
	DST				CHAR(1)
		constraint		locations_dst_nn
			not null,
	LATITUDE		NUMBER(7,4),
	LONGITUDE		NUMBER(7,4),
	ALIASCODE		CHAR(1)
		constraint		locations_aliascode_nn
			not null,
	SOURCE			NUMBER(5,0)
		constraint		locations_source_nn
			not null
)
tablespace statmiscd
storage (initial 10M next 500K pctincrease 0);

/* for faster city lookups */
alter table ebay_locations add FASTCITY varchar2(64) default 'tbd';
alter table ebay_locations modify (FASTCITY constraint locations_fastcity_nn not null);
update ebay_locations set FASTCITY=LOWER(TRANSLATE(CITY,'0 -','0'));

/* indices for speed */
create index ebay_locations_zip_index on ebay_locations(zip)
   tablespace statmisci
   storage (initial 1M next 1M pctincrease 0);

create index ebay_locations_areacode_index on ebay_locations(areacode)
   tablespace statmisci
   storage (initial 1M next 1M pctincrease 0);

create index ebay_locations_state_index on ebay_locations(state)
   tablespace statmisci
   storage (initial 1m next 1m pctincrease 0);

create index ebay_locations_fastcity_index on ebay_locations(fastcity)
   tablespace statmisci
   storage (initial 1M next 1M pctincrease 0);

