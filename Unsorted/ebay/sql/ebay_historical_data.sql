/*	$Id: ebay_historical_data.sql,v 1.2 1999/02/21 02:53:41 josh Exp $	*/
/*
 * ebay_historical_data.sql
 *
 */

 drop table ebay_historical_data;

 create table ebay_historical_data
 (
	USER_ID					NUMBER(38)
		constraint		history_user_id_nn
			not null,
	CATEGORY_ID				NUMBER(38)
		constraint		history_category_id_nn
			not null,
	PERIOD_START			DATE
		constraint		history_period_start_nn
			not null,
	ITEMS_BOUGHT			NUMBER(4,0)
		constraint		history_items_bought_nn
			NOT NULL,
	DOLLARS_BOUGHT			NUMBER(9,2)
		constraint		history_dollars_bought_nn
			NOT NULL,
	ITEMS_SOLD				NUMBER(4,0)
		constraint		history_items_sold_nn
			NOT NULL,
	DOLLARS_SOLD			NUMBER(9,2)
		constraint		history_dollars_sold_nn
			NOT NULL,
	ITEMS_UNSOLD			NUMBER(4,0)
		constraint		history_items_unsold_nn
			NOT NULL,
	DOLLARS_UNSOLD			NUMBER(9,2)
		constraint		history_dollars_unsold_nn
			NOT NULL,
   constraint        history_pk
      primary key    (user_id, category_id, period_start)
      using index tablespace achistoryd01
      storage (initial 20M next 2M)
 )
 tablespace achistoryd01
 storage(initial 500M next 50m);


