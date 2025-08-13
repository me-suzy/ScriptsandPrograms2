/*
 * ebay_deadbeats.sql
 *
 * ** NOTE **
 * Right now, item numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

/*  drop table ebay_deadbeats;
 */

 create table ebay_deadbeats
 (
	ID							NUMBER(38)
		constraint				deadbeats_id_nn
			not null,
	CREATION_DATE				DATE
		constraint				deadbeats_creation_date_nn
			not null,
	LAST_MODIFIED_DATE			DATE
		constraint				deadbeats_modified_date_nn
			not null,	
	VALID_DEADBEAT_SCORE		VARCHAR2(1)
		constraint				deadbeats_valid_backouts_nn
			not null,
	VALID_CREDIT_REQUEST_COUNT	VARCHAR2(1)
		constraint				deadbeats_valid_requests_nn
			not null,
	VALID_WARNING_COUNT			VARCHAR2(1)
		constraint				deadbeats_valid_warnings_nn
			not null,
	DEADBEAT_SCORE				NUMBER(38),
	CREDIT_REQUEST_COUNT		NUMBER(38),
	WARNING_COUNT				NUMBER(38),
	constraint					deadbeat_pk
		primary key		(id)
		using index tablespace	deadbeati01
		storage (initial 50M next 50M)
)
tablespace deadbeatd01
storage(initial 200M next 50M)
;
